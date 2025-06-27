<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\SchoolClass;
// Program model import removed
use App\Models\AcademicRecord;
use App\Exports\StudentsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Str;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of the students.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
        // Program ID parameter removed
        $classId = $request->input('class_id');
        
        // Initialize variables to avoid undefined variable errors
        $selectedClass = null;
        
        $studentsQuery = Student::with(['user', 'class', 'house']);
        
        // Restrict teachers to only see students assigned to their classes
        // Restrict students to only see their classmates
        $user = auth()->user();
        if ($user->hasRole('teacher')) {
            $teacher = $user->teacherProfile;
            if ($teacher) {
                // Get IDs of classes assigned to this teacher
                $teacherClassIds = $teacher->classes()->pluck('classes.id')->toArray();
                // Only show students from teacher's classes
                $studentsQuery->whereIn('class_id', $teacherClassIds);
            } else {
                // If teacher profile not found, don't show any students
                $studentsQuery->where('id', 0); // This will return empty results
            }
        } elseif ($user->hasRole('student')) {
            $student = $user->studentProfile;
            if ($student) {
                // Only show students from the same class
                $studentsQuery->where('class_id', $student->class_id);
            } else {
                // If student profile not found, don't show any students
                $studentsQuery->where('id', 0); // This will return empty results
            }
        }
        
        // Apply search filter if query parameter exists
        if ($query) {
            // For teachers, we need to maintain the class restriction while searching
            if ($user->hasRole('teacher')) {
                $studentsQuery->where(function($query) use ($request) {
                    $searchTerm = $request->input('query');
                    $query->whereHas('user', function($q) use ($searchTerm) {
                        $q->where('name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhere('enrollment_code', 'like', "%{$searchTerm}%")
                    ->orWhere('mobile_phone', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%");
                });
            } else {
                // For admin, use the original search logic
                $studentsQuery->where(function($query) use ($request) {
                    $searchTerm = $request->input('query');
                    $query->whereHas('user', function($q) use ($searchTerm) {
                        $q->where('name', 'like', "%{$searchTerm}%");
                    })
                    ->orWhere('enrollment_code', 'like', "%{$searchTerm}%")
                    ->orWhere('mobile_phone', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%");
                });
            }
        }
        
        // Program filtering removed
        
        // Apply class filter if class_id parameter exists
        if ($classId) {
            // For teachers, only allow filtering by classes they teach
            if ($user->hasRole('teacher')) {
                $teacher = $user->teacherProfile;
                if ($teacher) {
                    $teacherClassIds = $teacher->classes()->pluck('classes.id')->toArray();
                    if (in_array($classId, $teacherClassIds)) {
                        $studentsQuery->where('class_id', $classId);
                        $selectedClass = SchoolClass::find($classId);
                    }
                }
            } else {
                // For admin, allow filtering by any class
                $studentsQuery->where('class_id', $classId);
                $selectedClass = SchoolClass::find($classId);
            }
        }
        
        $students = $studentsQuery->orderBy('created_at', 'desc')->paginate(15);
        
        // Programs removed
        
        // Get all active classes for the filter dropdown
        $classes = SchoolClass::where('status', 'active')->get();
        
        return view('students.index', compact(
            'students', 
            'classes', 
            'query',
            'selectedClass'
        ));
    }

    /**
     * Show the form for creating a new student.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $classes = SchoolClass::where('status', 'active')->get();
        
        return view('students.create', compact('classes'));
    }

    /**
     * Handle bulk actions on multiple students.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkActions(Request $request)
    {
        $request->validate([
            'bulk_action' => 'required|string|in:activate,deactivate,export,delete',
            'selected_students' => 'required|array',
            'selected_students.*' => 'exists:students,id',
        ]);

        $action = $request->input('bulk_action');
        $studentIds = $request->input('selected_students');
        $count = count($studentIds);

        switch ($action) {
            case 'activate':
                Student::whereIn('id', $studentIds)->update(['status' => 'active']);
                return redirect()->route('students.index')
                    ->with('success', "{$count} students have been activated successfully.");

            case 'deactivate':
                Student::whereIn('id', $studentIds)->update(['status' => 'inactive']);
                return redirect()->route('students.index')
                    ->with('success', "{$count} students have been deactivated successfully.");

            case 'delete':
                // Check for related records before deletion
                $cannotDelete = [];
                foreach ($studentIds as $studentId) {
                    $student = Student::find($studentId);
                    if ($student && $student->hasRelatedRecords()) {
                        $cannotDelete[] = $student->user->name;
                    }
                }

                if (!empty($cannotDelete)) {
                    return redirect()->route('students.index')
                        ->with('error', "The following students cannot be deleted because they have related records: " . implode(', ', $cannotDelete));
                }

                // Delete students that can be deleted
                DB::beginTransaction();
                try {
                    foreach ($studentIds as $studentId) {
                        $student = Student::find($studentId);
                        if ($student) {
                            // Delete associated user account
                            $userId = $student->user_id;
                            $student->delete();
                            User::where('id', $userId)->delete();
                        }
                    }
                    DB::commit();
                    return redirect()->route('students.index')
                        ->with('success', "{$count} students have been deleted successfully.");
                } catch (\Exception $e) {
                    DB::rollBack();
                    return redirect()->route('students.index')
                        ->with('error', "An error occurred while deleting students: {$e->getMessage()}");
                }

            case 'export':
                // Generate Excel export of selected students
                return Excel::download(new StudentsExport($studentIds), 'selected_students.xlsx');

            default:
                return redirect()->route('students.index')
                    ->with('error', 'Invalid bulk action specified.');
        }
    }

    /**
     * Store a newly created student in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'jhs_attended' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'program_id' => 'required|exists:programs,id',
            'date_of_birth' => 'required|date',
            'mobile_phone' => 'nullable|string|max:20',
            'nationality' => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();

        try {
            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('password'), // Default password
                'role' => 'student',
            ]);

            // Generate unique student ID
            $year = date('Y');
            $latestStudent = Student::whereYear('created_at', $year)
                ->orderBy('id', 'desc')
                ->first();
            
            $sequence = 1;
            if ($latestStudent) {
                // Extract sequence number from the latest student ID
                $parts = explode('-', $latestStudent->enrollment_code);
                if (count($parts) >= 3 && is_numeric($parts[2])) {
                    $sequence = intval($parts[2]) + 1;
                }
            }
            
            // Format: SMS-YYYY-XXXX (where XXXX is a sequential number)
            $studentId = 'SMS-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
            
            // Create student profile
            $student = Student::create([
                'user_id' => $user->id,
                'enrollment_code' => $studentId,
                'jhs_attended' => $request->jhs_attended,
                'mobile_phone' => $request->mobile_phone,
                'alternate_phone' => $request->alternate_phone,
                'email' => $request->email,
                'fathers_name' => $request->fathers_name,
                'fathers_occupation' => $request->fathers_occupation,
                'mothers_name' => $request->mothers_name,
                'mothers_occupation' => $request->mothers_occupation,
                'guardians_name' => $request->guardians_name,
                'residential_address' => $request->residential_address,
                'residence_telephone' => $request->residence_telephone,
                'digital_address' => $request->digital_address,
                'jhs_type' => $request->jhs_type,
                'date_of_birth' => $request->date_of_birth,
                'place_of_birth' => $request->place_of_birth,
                'interests' => $request->interests,
                'religion' => $request->religion,
                'town' => $request->town,
                'region' => $request->region,
                'district' => $request->district,
                'ghana_card_number' => $request->ghana_card_number,
                'nationality' => $request->nationality ?? 'Ghanaian',
                'whatsapp_contact' => $request->whatsapp_contact,
                'class_id' => $request->class_id,
                'program_id' => $request->program_id,
                'admission_date' => now(),
                'status' => 'active',
            ]);

            DB::commit();

            return redirect()->route('students.index')
                ->with('success', 'Student created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error creating student: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified student.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\View\View
     */
    public function show(Student $student)
    {
        $user = auth()->user();
        
        // Check if user is a teacher and restrict access to only their students
        if ($user->hasRole('teacher')) {
            $teacher = $user->teacherProfile;
            if ($teacher) {
                // Get IDs of classes assigned to this teacher
                $teacherClassIds = $teacher->classes()->pluck('classes.id')->toArray();
                
                // Check if the student belongs to one of the teacher's classes
                if (!in_array($student->class_id, $teacherClassIds)) {
                    return redirect()->route('students.index')
                        ->with('error', 'You do not have permission to view this student.');
                }
            } else {
                // If teacher profile not found, deny access
                return redirect()->route('students.index')
                    ->with('error', 'You do not have permission to view this student.');
            }
        }
        // Check if user is a student and restrict access to only their classmates
        elseif ($user->hasRole('student')) {
            $currentStudent = $user->studentProfile;
            if ($currentStudent) {
                // Check if the student being viewed is in the same class
                if ($student->class_id != $currentStudent->class_id) {
                    return redirect()->route('students.index')
                        ->with('error', 'You can only view students in your class.');
                }
            } else {
                // If student profile not found, deny access
                return redirect()->route('students.index')
                    ->with('error', 'You do not have permission to view this student.');
            }
        }
        
        $student->load(['user', 'class', 'program']);
        $academicRecords = AcademicRecord::where('student_id', $student->id)
            ->with(['subject', 'class'])
            ->orderBy('academic_year', 'desc')
            ->orderBy('term', 'desc')
            ->get();
            
        return view('students.show', compact('student', 'academicRecords'));
    }

    /**
     * Show the form for editing the specified student.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\View\View
     */
    public function edit(Student $student)
    {
        $classes = SchoolClass::where('status', 'active')->get();
        $programs = Program::where('status', 'active')->get();
        
        return view('students.edit', compact('student', 'classes', 'programs'));
    }

    /**
     * Update the specified student in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $student->user_id,
            'jhs_attended' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'program_id' => 'required|exists:programs,id',
            'date_of_birth' => 'required|date',
            'mobile_phone' => 'nullable|string|max:20',
            'nationality' => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();

        try {
            // Update user account
            $student->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Update student profile
            $student->update([
                'jhs_attended' => $request->jhs_attended,
                'mobile_phone' => $request->mobile_phone,
                'alternate_phone' => $request->alternate_phone,
                'email' => $request->email,
                'fathers_name' => $request->fathers_name,
                'fathers_occupation' => $request->fathers_occupation,
                'mothers_name' => $request->mothers_name,
                'mothers_occupation' => $request->mothers_occupation,
                'guardians_name' => $request->guardians_name,
                'residential_address' => $request->residential_address,
                'residence_telephone' => $request->residence_telephone,
                'digital_address' => $request->digital_address,
                'jhs_type' => $request->jhs_type,
                'date_of_birth' => $request->date_of_birth,
                'place_of_birth' => $request->place_of_birth,
                'interests' => $request->interests,
                'religion' => $request->religion,
                'town' => $request->town,
                'region' => $request->region,
                'district' => $request->district,
                'ghana_card_number' => $request->ghana_card_number,
                'nationality' => $request->nationality ?? 'Ghanaian',
                'whatsapp_contact' => $request->whatsapp_contact,
                'class_id' => $request->class_id,
                'program_id' => $request->program_id,
                'status' => $request->status ?? $student->status,
            ]);

            DB::commit();

            return redirect()->route('students.show', $student)
                ->with('success', 'Student updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error updating student: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified student from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Student $student)
    {
        try {
            // Delete the user account (will cascade delete the student profile)
            $student->user->delete();
            
            return redirect()->route('students.index')
                ->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting student: ' . $e->getMessage());
        }
    }

    // Search and filter functionality has been consolidated into the index method
    
    /**
     * Show the form for importing students.
     *
     * @return \Illuminate\View\View
     */
    public function importForm()
    {
        $classes = SchoolClass::where('status', 'active')->get();
        $programs = Program::where('status', 'active')->get();
        
        return view('students.import', compact('classes', 'programs'));
    }
    
    /**
     * Import students from uploaded file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xls,xlsx|max:10240',
            'class_id' => 'required|exists:classes,id',
            'program_id' => 'required|exists:programs,id',
        ]);
        
        $path = $request->file('file')->store('temp');
        $rows = Excel::toArray([], storage_path('app/' . $path))[0];
        
        // Skip header row
        array_shift($rows);
        
        $successCount = 0;
        $errorCount = 0;
        $errors = [];
        
        DB::beginTransaction();
        
        try {
            foreach ($rows as $index => $row) {
                // Validate required fields
                if (empty($row[0]) || empty($row[1]) || empty($row[2])) {
                    $errors[] = "Row " . ($index + 2) . ": Missing required fields (name, email or enrollment code).";
                    $errorCount++;
                    continue;
                }
                
                $studentData = [
                    'name' => $row[0],
                    'email' => $row[1],
                    'enrollment_code' => $row[2],
                    'date_of_birth' => !empty($row[3]) ? date('Y-m-d', strtotime($row[3])) : null,
                    'gender' => $row[4] ?? null,
                    'mobile_phone' => $row[5] ?? null,
                    'jhs_attended' => $row[6] ?? 'Not Provided',
                ];
                
                // Check if email or enrollment code already exists
                $emailExists = User::where('email', $studentData['email'])->exists();
                $enrollmentCodeExists = Student::where('enrollment_code', $studentData['enrollment_code'])->exists();
                
                if ($emailExists) {
                    $errors[] = "Row " . ($index + 2) . ": Email {$studentData['email']} already exists.";
                    $errorCount++;
                    continue;
                }
                
                if ($enrollmentCodeExists) {
                    $errors[] = "Row " . ($index + 2) . ": Enrollment code {$studentData['enrollment_code']} already exists.";
                    $errorCount++;
                    continue;
                }
                
                // Create user
                $user = User::create([
                    'name' => $studentData['name'],
                    'email' => $studentData['email'],
                    'password' => Hash::make('password'),
                    'role' => 'student',
                ]);
                
                // Create student
                Student::create([
                    'user_id' => $user->id,
                    'enrollment_code' => $studentData['enrollment_code'],
                    'date_of_birth' => $studentData['date_of_birth'],
                    'gender' => $studentData['gender'],
                    'mobile_phone' => $studentData['mobile_phone'],
                    'jhs_attended' => $studentData['jhs_attended'],
                    'email' => $studentData['email'],
                    'class_id' => $request->class_id,
                    'program_id' => $request->program_id,
                    'admission_date' => now(),
                    'status' => 'active',
                ]);
                
                $successCount++;
            }
            
            // Delete the temporary file
            Storage::delete($path);
            
            // Commit if no errors or if there are some successes
            if ($errorCount === 0 || $successCount > 0) {
                DB::commit();
                
                $message = $successCount . ' students imported successfully.';
                if ($errorCount > 0) {
                    $message .= ' ' . $errorCount . ' records had errors.';
                }
                
                return redirect()->route('students.index')
                    ->with('success', $message)
                    ->with('import_errors', $errors);
            } else {
                DB::rollBack();
                return redirect()->route('students.import.form')
                    ->with('error', 'No students were imported due to errors.')
                    ->with('import_errors', $errors);
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Storage::delete($path);
            
            return redirect()->route('students.import.form')
                ->with('error', 'Error importing students: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the student's academic results.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    /**
     * Show the form for assigning students to a class.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function assignClassForm(Request $request)
    {
        $class = SchoolClass::findOrFail($request->class_id);
        $students = Student::whereDoesntHave('class', function ($query) use ($class) {
            $query->where('class_id', $class->id);
        })->get();

        return view('students.assign-class', compact('class', 'students'));
    }

    /**
     * Sync students to a class.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function syncStudentsToClass(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        $class = SchoolClass::findOrFail($request->class_id);
        $class->students()->attach($request->student_ids);

        return redirect()->route('classes.show', $class->id)
            ->with('success', 'Students assigned to class successfully.');
    }

    /**
     * Remove the specified student from a class.
     *
     * @param  \App\Models\Student  $student
     * @param  \App\Models\SchoolClass  $class
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeFromClass(Student $student, SchoolClass $class)
    {
        // Ensure the student is actually in the class before removing
        if ($student->class_id == $class->id) {
            $student->class_id = null;
            $student->save();

            return redirect()->route('classes.show', $class->id)
                ->with('success', 'Student removed from class successfully.');
        }

        return redirect()->route('classes.show', $class->id)
            ->with('error', 'Student is not in this class.');
    }

    public function results(Student $student)
    {
        // Get all academic records for the student with their related data
        $academicRecords = AcademicRecord::with(['subject', 'class', 'result', 'academicYear'])
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Group records by academic year
        $recordsByYear = $academicRecords->groupBy(function($record) {
            return $record->academicYear ? $record->academicYear->name : 'Unknown';
        });
        
        return view('students.results', [
            'student' => $student,
            'recordsByYear' => $recordsByYear,
            'cgpa' => $student->calculateCGPA(),
            'classification' => $student->getClassification()
        ]);
    }
    
    /**
     * Download Excel template for student import
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadImportTemplate()
    {
        $headers = [
            'Name', 'Email', 'Enrollment Code', 'Date of Birth', 'Gender', 
            'Mobile Phone', 'Address', 'Guardian Name', 'Guardian Phone'
        ];
        
        $rows = [
            [
                'John Doe', 'john@example.com', 'STU001', '2005-01-15', 'male',
                '1234567890', '123 Main St', 'Jane Doe', '0987654321'
            ],
            [
                'Jane Smith', 'jane@example.com', 'STU002', '2005-03-20', 'female',
                '2345678901', '456 Oak Ave', 'John Smith', '1234567890'
            ]
        ];
        
        return Excel::download(new class($headers, $rows) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
            protected $headers;
            protected $rows;
            
            public function __construct($headers, $rows)
            {
                $this->headers = $headers;
                $this->rows = $rows;
            }
            
            public function array(): array
            {
                return $this->rows;
            }
            
            public function headings(): array
            {
                return $this->headers;
            }
        }, 'student_import_template.xlsx');
    }
}
