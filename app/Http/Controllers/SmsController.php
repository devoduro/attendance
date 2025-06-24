<?php

namespace App\Http\Controllers;

use App\Models\SmsNotification;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\AcademicRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class SmsController extends Controller
{
    /**
     * Display a listing of the SMS notifications.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $smsNotifications = SmsNotification::with(['sentBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('sms.index', compact('smsNotifications'));
    }

    /**
     * Show the form for creating a new SMS notification.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $students = Student::with('user')->where('status', 'active')->get();
        $teachers = Teacher::with('user')->where('status', 'active')->get();
        $classes = SchoolClass::where('status', 'active')->get();
        
        return view('sms.create', compact('students', 'teachers', 'classes'));
    }

    /**
     * Store a newly created SMS notification in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'recipient_type' => 'required|in:student,parent,teacher',
            'recipients' => 'required_if:recipient_type,student,teacher|array',
            'class_id' => 'required_if:recipient_type,class|exists:classes,id',
            'message' => 'required|string|max:160',
        ]);

        try {
            $recipients = [];
            
            if ($request->recipient_type === 'student') {
                foreach ($request->recipients as $studentId) {
                    $student = Student::find($studentId);
                    if ($student && $student->mobile_phone) {
                        $recipients[] = [
                            'recipient_type' => 'student',
                            'recipient_id' => $student->id,
                            'phone_number' => $student->mobile_phone,
                        ];
                    }
                }
            } elseif ($request->recipient_type === 'parent') {
                if ($request->has('class_id')) {
                    $students = Student::where('class_id', $request->class_id)->get();
                    foreach ($students as $student) {
                        // Try father's phone first, then mother's, then guardian's
                        $parentPhone = null;
                        if ($student->fathers_name && $student->mobile_phone) {
                            $parentPhone = $student->mobile_phone;
                        } elseif ($student->mothers_name && $student->alternate_phone) {
                            $parentPhone = $student->alternate_phone;
                        } elseif ($student->guardians_name && $student->whatsapp_contact) {
                            $parentPhone = $student->whatsapp_contact;
                        }
                        
                        if ($parentPhone) {
                            $recipients[] = [
                                'recipient_type' => 'parent',
                                'recipient_id' => $student->id,
                                'phone_number' => $parentPhone,
                            ];
                        }
                    }
                }
            } elseif ($request->recipient_type === 'teacher') {
                foreach ($request->recipients as $teacherId) {
                    $teacher = Teacher::find($teacherId);
                    if ($teacher && $teacher->phone_number) {
                        $recipients[] = [
                            'recipient_type' => 'teacher',
                            'recipient_id' => $teacher->id,
                            'phone_number' => $teacher->phone_number,
                        ];
                    }
                }
            }
            
            if (empty($recipients)) {
                return redirect()->back()
                    ->with('error', 'No valid recipients found with phone numbers.')
                    ->withInput();
            }
            
            // Send SMS to each recipient
            foreach ($recipients as $recipient) {
                $smsNotification = new SmsNotification();
                $smsNotification->recipient_type = $recipient['recipient_type'];
                $smsNotification->recipient_id = $recipient['recipient_id'];
                $smsNotification->phone_number = $recipient['phone_number'];
                $smsNotification->message = $request->message;
                $smsNotification->status = 'pending';
                $smsNotification->sent_by = Auth::id();
                $smsNotification->save();
                
                // Send SMS using API (this is a placeholder, replace with actual SMS API)
                $response = $this->sendSms($recipient['phone_number'], $request->message);
                
                // Update SMS notification with response
                $smsNotification->status = $response['success'] ? 'sent' : 'failed';
                $smsNotification->sent_at = now();
                $smsNotification->response_code = $response['code'];
                $smsNotification->response_message = $response['message'];
                $smsNotification->save();
            }

            return redirect()->route('sms.index')
                ->with('success', count($recipients) . ' SMS notifications sent successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error sending SMS: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for sending results via SMS.
     *
     * @return \Illuminate\View\View
     */
    public function resultsForm()
    {
        $classes = SchoolClass::where('status', 'active')->get();
        
        return view('sms.results_form', compact('classes'));
    }

    /**
     * Send results via SMS.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResults(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'academic_year' => 'required|string',
            'term' => 'required|integer|min:1|max:3',
            'recipient_type' => 'required|in:student,parent',
        ]);

        try {
            $students = Student::where('class_id', $request->class_id)
                ->where('status', 'active')
                ->get();
                
            $sentCount = 0;
            
            foreach ($students as $student) {
                // Get academic records for the student
                $academicRecords = AcademicRecord::with('subject')
                    ->where('student_id', $student->id)
                    ->where('academic_year', $request->academic_year)
                    ->where('term', $request->term)
                    ->get();
                    
                if ($academicRecords->isEmpty()) {
                    continue;
                }
                
                // Calculate average score
                $totalScore = $academicRecords->sum('total_score');
                $averageScore = round($academicRecords->count() > 0 ? $totalScore / $academicRecords->count() : 0, 1);
                
                // Compose message
                $message = "Results for {$student->user->name}, {$request->academic_year}, Term {$request->term}: ";
                $message .= "Average: {$averageScore}%. ";
                
                // Add top 3 subjects
                $topSubjects = $academicRecords->sortByDesc('total_score')->take(3);
                if ($topSubjects->isNotEmpty()) {
                    $message .= "Top subjects: ";
                    foreach ($topSubjects as $record) {
                        $message .= "{$record->subject->code}({$record->total_score}%), ";
                    }
                    $message = rtrim($message, ", ");
                }
                
                // Determine recipient phone number
                $phoneNumber = null;
                if ($request->recipient_type === 'student' && $student->mobile_phone) {
                    $phoneNumber = $student->mobile_phone;
                } elseif ($request->recipient_type === 'parent') {
                    // Try father's phone first, then mother's, then guardian's
                    if ($student->fathers_name && $student->mobile_phone) {
                        $phoneNumber = $student->mobile_phone;
                    } elseif ($student->mothers_name && $student->alternate_phone) {
                        $phoneNumber = $student->alternate_phone;
                    } elseif ($student->guardians_name && $student->whatsapp_contact) {
                        $phoneNumber = $student->whatsapp_contact;
                    }
                }
                
                if ($phoneNumber) {
                    // Create SMS notification
                    $smsNotification = new SmsNotification();
                    $smsNotification->recipient_type = $request->recipient_type;
                    $smsNotification->recipient_id = $student->id;
                    $smsNotification->phone_number = $phoneNumber;
                    $smsNotification->message = $message;
                    $smsNotification->status = 'pending';
                    $smsNotification->sent_by = Auth::id();
                    $smsNotification->save();
                    
                    // Send SMS using API (this is a placeholder, replace with actual SMS API)
                    $response = $this->sendSms($phoneNumber, $message);
                    
                    // Update SMS notification with response
                    $smsNotification->status = $response['success'] ? 'sent' : 'failed';
                    $smsNotification->sent_at = now();
                    $smsNotification->response_code = $response['code'];
                    $smsNotification->response_message = $response['message'];
                    $smsNotification->save();
                    
                    if ($response['success']) {
                        $sentCount++;
                    }
                }
            }

            return redirect()->route('sms.index')
                ->with('success', $sentCount . ' results SMS notifications sent successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error sending results SMS: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Send SMS using API.
     * 
     * Note: This is a placeholder method. Replace with actual SMS API integration.
     *
     * @param  string  $phoneNumber
     * @param  string  $message
     * @return array
     */
    private function sendSms($phoneNumber, $message)
    {
        // This is a placeholder. In a real application, you would integrate with an SMS API.
        // For example, using Africa's Talking, Twilio, or other SMS gateway.
        
        try {
            // Simulate API call
            // In a real application, you would make an HTTP request to the SMS API
            // For example:
            // $response = Http::post('https://api.smsgateway.com/send', [
            //     'apiKey' => config('services.sms.api_key'),
            //     'to' => $phoneNumber,
            //     'message' => $message,
            // ]);
            
            // Simulate successful response
            return [
                'success' => true,
                'code' => '200',
                'message' => 'SMS sent successfully (simulated)',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'code' => '500',
                'message' => 'Error sending SMS: ' . $e->getMessage(),
            ];
        }
    }
}
