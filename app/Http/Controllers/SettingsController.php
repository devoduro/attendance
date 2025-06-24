<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $generalSettings = Setting::getByGroup('general');
        $academicSettings = Setting::getByGroup('academic');
        $smsSettings = Setting::getByGroup('sms');
        
        return view('settings.index', compact('generalSettings', 'academicSettings', 'smsSettings'));
    }

    /**
     * Update the specified settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        try {
            // Update general settings
            if ($request->has('general')) {
                foreach ($request->general as $key => $value) {
                    Setting::setValue($key, $value, 'general');
                }
            }
            
            // Update academic settings
            if ($request->has('academic')) {
                foreach ($request->academic as $key => $value) {
                    Setting::setValue($key, $value, 'academic');
                }
            }
            
            // Update SMS settings
            if ($request->has('sms')) {
                foreach ($request->sms as $key => $value) {
                    Setting::setValue($key, $value, 'sms');
                }
            }
            
            // Handle school logo upload
            if ($request->hasFile('school_logo')) {
                $request->validate([
                    'school_logo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);
                
                $logoPath = $request->file('school_logo')->store('public/settings');
                Setting::setValue('school_logo', str_replace('public/', '', $logoPath), 'general');
            }
            
            // Clear settings cache
            Cache::forget('settings');
            
            return redirect()->route('settings.index')
                ->with('success', 'Settings updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating settings: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Initialize default settings.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function initialize()
    {
        try {
            // General settings
            Setting::setValue('school_name', 'Senior High School', 'general', 'Name of the school');
            Setting::setValue('school_address', '123 Education Street, Accra', 'general', 'Address of the school');
            Setting::setValue('school_phone', '+233 123456789', 'general', 'Phone number of the school');
            Setting::setValue('school_email', 'info@shs.edu.gh', 'general', 'Email address of the school');
            Setting::setValue('school_website', 'www.shs.edu.gh', 'general', 'Website of the school');
            Setting::setValue('school_logo', '', 'general', 'Logo of the school');
            
            // Academic settings
            Setting::setValue('current_academic_year', date('Y') . '/' . (date('Y') + 1), 'academic', 'Current academic year');
            Setting::setValue('current_term', '1', 'academic', 'Current term (1, 2, or 3)');
            Setting::setValue('grading_system', 'A:80-100,B:70-79,C:60-69,D:50-59,E:40-49,F:0-39', 'academic', 'Grading system');
            Setting::setValue('passing_grade', 'D', 'academic', 'Minimum passing grade');
            
            // SMS settings
            Setting::setValue('sms_enabled', 'true', 'sms', 'Enable SMS notifications');
            Setting::setValue('sms_api_key', '', 'sms', 'SMS API key');
            Setting::setValue('sms_sender_id', 'SHS', 'sms', 'SMS sender ID');
            
            return redirect()->route('settings.index')
                ->with('success', 'Default settings initialized successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error initializing settings: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the academic years management page.
     *
     * @return \Illuminate\View\View
     */
    public function academicYears()
    {
        $academicYears = \App\Models\AcademicYear::orderBy('name', 'desc')->get();
        $currentAcademicYear = Setting::getValue('current_academic_year', '', 'academic');
        
        return view('settings.academic-years', compact('academicYears', 'currentAcademicYear'));
    }
    
    /**
     * Store a new academic year.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeAcademicYear(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:academic_years,name',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);
        
        try {
            $academicYear = new \App\Models\AcademicYear();
            $academicYear->name = $request->name;
            $academicYear->start_date = $request->start_date;
            $academicYear->end_date = $request->end_date;
            $academicYear->status = $request->has('status') ? 'active' : 'inactive';
            $academicYear->save();
            
            // If this is the first academic year or set as current
            if ($request->has('set_as_current')) {
                Setting::setValue('current_academic_year', $academicYear->name, 'academic');
            }
            
            return redirect()->route('settings.academic-years')
                ->with('success', 'Academic year created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating academic year: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Display the grade schemes management page.
     *
     * @return \Illuminate\View\View
     */
    public function gradeSchemes()
    {
        // Get the current grading system from settings
        $gradingSystem = Setting::getValue('grading_system', '', 'academic');
        $passingGrade = Setting::getValue('passing_grade', 'D', 'academic');
        
        // Parse the grading system string into an array
        $grades = [];
        if (!empty($gradingSystem)) {
            $gradeItems = explode(',', $gradingSystem);
            foreach ($gradeItems as $item) {
                // Skip malformed items
                if (strpos($item, ':') === false) {
                    continue;
                }
                
                $parts = explode(':', $item);
                if (count($parts) < 2) {
                    continue;
                }
                
                $grade = $parts[0];
                $range = $parts[1];
                
                // Handle range format
                if (strpos($range, '-') === false) {
                    // If no range separator, use the value as both min and max
                    $min = $max = trim($range);
                } else {
                    $rangeParts = explode('-', $range);
                    $min = trim($rangeParts[0]);
                    $max = isset($rangeParts[1]) ? trim($rangeParts[1]) : $min;
                }
                
                $grades[] = [
                    'grade' => trim($grade),
                    'min_score' => $min,
                    'max_score' => $max,
                    'is_passing' => trim($grade) === $passingGrade || strcmp(trim($grade), $passingGrade) <= 0
                ];
            }
            
            // Sort grades by max_score in descending order
            usort($grades, function($a, $b) {
                return $b['max_score'] - $a['max_score'];
            });
        }
        
        return view('settings.grade-schemes', compact('grades', 'passingGrade'));
    }
    
    /**
     * Show the form for creating a new grade scheme.
     *
     * @return \Illuminate\View\View
     */
    public function createGradeScheme()
    {
        return view('settings.grade-schemes.create');
    }
    
    /**
     * Update the grade schemes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateGradeSchemes(Request $request)
    {
        $request->validate([
            'grades' => 'required|array',
            'grades.*.grade' => 'required|string|max:2',
            'grades.*.min_score' => 'required|numeric|min:0|max:100',
            'grades.*.max_score' => 'required|numeric|min:0|max:100',
            'passing_grade' => 'required|string|max:2',
        ]);
        
        try {
            // Build the grading system string
            $gradingSystem = [];
            foreach ($request->grades as $grade) {
                $gradingSystem[] = $grade['grade'] . ':' . $grade['min_score'] . '-' . $grade['max_score'];
            }
            
            // Save the grading system and passing grade
            Setting::setValue('grading_system', implode(',', $gradingSystem), 'academic');
            Setting::setValue('passing_grade', $request->passing_grade, 'academic');
            
            // Clear settings cache
            Cache::forget('settings');
            
            return redirect()->route('settings.grade-schemes')
                ->with('success', 'Grade schemes updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating grade schemes: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Display the degree classifications management page.
     *
     * @return \Illuminate\View\View
     */
    public function classifications()
    {
        // Get the current classifications from settings
        $classificationsString = Setting::getValue('degree_classifications', '', 'academic');
        
        // Parse the classifications string into an array
        $classifications = [];
        if (!empty($classificationsString)) {
            $classItems = explode(',', $classificationsString);
            foreach ($classItems as $item) {
                list($name, $range) = explode(':', $item);
                list($min, $max) = explode('-', $range);
                $classifications[] = [
                    'name' => $name,
                    'min_cgpa' => $min,
                    'max_cgpa' => $max
                ];
            }
            
            // Sort classifications by max_cgpa in descending order
            usort($classifications, function($a, $b) {
                return $b['max_cgpa'] - $a['max_cgpa'];
            });
        }
        
        return view('settings.classifications', compact('classifications'));
    }
    
    /**
     * Update the degree classifications.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateClassifications(Request $request)
    {
        $request->validate([
            'classifications' => 'required|array',
            'classifications.*.name' => 'required|string|max:50',
            'classifications.*.min_cgpa' => 'required|numeric|min:0|max:5',
            'classifications.*.max_cgpa' => 'required|numeric|min:0|max:5',
        ]);
        
        try {
            // Build the classifications string
            $classificationsArray = [];
            foreach ($request->classifications as $classification) {
                $classificationsArray[] = $classification['name'] . ':' . $classification['min_cgpa'] . '-' . $classification['max_cgpa'];
            }
            
            // Save the classifications
            Setting::setValue('degree_classifications', implode(',', $classificationsArray), 'academic');
            
            // Clear settings cache
            Cache::forget('settings');
            
            return redirect()->route('settings.classifications')
                ->with('success', 'Degree classifications updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating degree classifications: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Display the database backup management page.
     *
     * @return \Illuminate\View\View
     */
    public function backup()
    {
        // Get all backup files from the backup directory
        $backupPath = storage_path('app/backups');
        
        // Create the directory if it doesn't exist
        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }
        
        $files = glob($backupPath . '/*.sql');
        
        $backups = [];
        foreach ($files as $file) {
            $backups[] = [
                'filename' => basename($file),
                'size' => $this->formatBytes(filesize($file)),
                'date' => date('Y-m-d H:i:s', filemtime($file))
            ];
        }
        
        // Sort backups by date (newest first)
        usort($backups, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return view('settings.backup', compact('backups'));
    }
    
    /**
     * Create a new database backup.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createBackup()
    {
        try {
            // Get database configuration
            $host = config('database.connections.mysql.host');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $database = config('database.connections.mysql.database');
            
            // Create backup filename with timestamp
            $backupPath = storage_path('app/backups');
            
            // Create the directory if it doesn't exist
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }
            
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filePath = $backupPath . '/' . $filename;
            
            // Create backup command with full path to mysqldump in XAMPP
            $mysqldumpPath = '/Applications/XAMPP/xamppfiles/bin/mysqldump';
            
            // Check if the mysqldump executable exists
            if (!file_exists($mysqldumpPath)) {
                throw new \Exception('mysqldump executable not found at ' . $mysqldumpPath);
            }
            
            $command = "\"{$mysqldumpPath}\" --host={$host} --user={$username}";
            if ($password) {
                $command .= " --password={$password}";
            }
            $command .= " {$database} > {$filePath}";
            
            // Execute backup command
            exec($command, $output, $returnVar);
            
            if ($returnVar !== 0) {
                throw new \Exception('Database backup failed. Error code: ' . $returnVar);
            }
            
            return redirect()->route('settings.backup')
                ->with('success', 'Database backup created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('settings.backup')
                ->with('error', 'Error creating database backup: ' . $e->getMessage());
        }
    }
    
    /**
     * Download a database backup file.
     *
     * @param  string  $filename
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadBackup($filename)
    {
        $backupPath = storage_path('app/backups');
        $filePath = $backupPath . '/' . $filename;
        
        if (!file_exists($filePath)) {
            abort(404, 'Backup file not found.');
        }
        
        return response()->download($filePath);
    }
    
    /**
     * Delete a database backup file.
     *
     * @param  string  $filename
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteBackup($filename)
    {
        try {
            $backupPath = storage_path('app/backups');
            $filePath = $backupPath . '/' . $filename;
            
            if (!file_exists($filePath)) {
                throw new \Exception('Backup file not found.');
            }
            
            unlink($filePath);
            
            return redirect()->route('settings.backup')
                ->with('success', 'Backup file deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('settings.backup')
                ->with('error', 'Error deleting backup file: ' . $e->getMessage());
        }
    }
    
    /**
     * Format bytes to human-readable format.
     *
     * @param  int  $bytes
     * @param  int  $precision
     * @return string
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
    
    /**
     * Display the system settings page.
     *
     * @return \Illuminate\View\View
     */
    public function system()
    {
        // Get system settings
        $settings = [
            'system_name' => Setting::getValue('system_name', 'School Management System', 'system'),
            'system_email' => Setting::getValue('system_email', 'admin@example.com', 'system'),
            'system_phone' => Setting::getValue('system_phone', '', 'system'),
            'system_address' => Setting::getValue('system_address', '', 'system'),
            'system_currency' => Setting::getValue('system_currency', 'USD', 'system'),
            'system_timezone' => Setting::getValue('system_timezone', 'UTC', 'system'),
            'system_date_format' => Setting::getValue('system_date_format', 'Y-m-d', 'system'),
            'system_time_format' => Setting::getValue('system_time_format', 'H:i', 'system'),
            'system_language' => Setting::getValue('system_language', 'en', 'system'),
            'enable_registration' => Setting::getValue('enable_registration', '0', 'system'),
            'enable_email_verification' => Setting::getValue('enable_email_verification', '0', 'system'),
            'maintenance_mode' => Setting::getValue('maintenance_mode', '0', 'system'),
        ];
        
        // Get available timezones
        $timezones = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);
        
        // Get available date formats
        $dateFormats = [
            'Y-m-d' => date('Y-m-d') . ' (YYYY-MM-DD)',
            'd-m-Y' => date('d-m-Y') . ' (DD-MM-YYYY)',
            'm/d/Y' => date('m/d/Y') . ' (MM/DD/YYYY)',
            'd/m/Y' => date('d/m/Y') . ' (DD/MM/YYYY)',
            'Y/m/d' => date('Y/m/d') . ' (YYYY/MM/DD)',
        ];
        
        // Get available time formats
        $timeFormats = [
            'H:i' => date('H:i') . ' (24-hour)',
            'h:i A' => date('h:i A') . ' (12-hour)',
        ];
        
        // Get available languages
        $languages = [
            'en' => 'English',
            'fr' => 'French',
            'es' => 'Spanish',
            'de' => 'German',
            'ar' => 'Arabic',
        ];
        
        // Get available currencies
        $currencies = [
            'USD' => 'US Dollar ($)',
            'EUR' => 'Euro (€)',
            'GBP' => 'British Pound (£)',
            'NGN' => 'Nigerian Naira (₦)',
            'GHS' => 'Ghana Cedi (₵)',
            'KES' => 'Kenyan Shilling (KSh)',
            'ZAR' => 'South African Rand (R)',
        ];
        
        return view('settings.system', compact('settings', 'timezones', 'dateFormats', 'timeFormats', 'languages', 'currencies'));
    }
    
    /**
     * Update the system settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSystem(Request $request)
    {
        $request->validate([
            'system_name' => 'required|string|max:255',
            'system_email' => 'required|email|max:255',
            'system_phone' => 'nullable|string|max:20',
            'system_address' => 'nullable|string|max:500',
            'system_currency' => 'required|string|max:10',
            'system_timezone' => 'required|string|max:100',
            'system_date_format' => 'required|string|max:20',
            'system_time_format' => 'required|string|max:20',
            'system_language' => 'required|string|max:10',
            'enable_registration' => 'sometimes|boolean',
            'enable_email_verification' => 'sometimes|boolean',
            'maintenance_mode' => 'sometimes|boolean',
        ]);
        
        try {
            // Update system settings
            Setting::setValue('system_name', $request->system_name, 'system');
            Setting::setValue('system_email', $request->system_email, 'system');
            Setting::setValue('system_phone', $request->system_phone, 'system');
            Setting::setValue('system_address', $request->system_address, 'system');
            Setting::setValue('system_currency', $request->system_currency, 'system');
            Setting::setValue('system_timezone', $request->system_timezone, 'system');
            Setting::setValue('system_date_format', $request->system_date_format, 'system');
            Setting::setValue('system_time_format', $request->system_time_format, 'system');
            Setting::setValue('system_language', $request->system_language, 'system');
            Setting::setValue('enable_registration', $request->has('enable_registration') ? '1' : '0', 'system');
            Setting::setValue('enable_email_verification', $request->has('enable_email_verification') ? '1' : '0', 'system');
            Setting::setValue('maintenance_mode', $request->has('maintenance_mode') ? '1' : '0', 'system');
            
            // Clear settings cache
            Cache::forget('settings');
            
            return redirect()->route('settings.system')
                ->with('success', 'System settings updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating system settings: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Display the institution settings page.
     *
     * @return \Illuminate\View\View
     */
    public function institution()
    {
        // Get institution settings
        $settings = [
            'institution_name' => Setting::getValue('institution_name', 'School Name', 'institution'),
            'institution_short_name' => Setting::getValue('institution_short_name', '', 'institution'),
            'institution_code' => Setting::getValue('institution_code', '', 'institution'),
            'institution_address' => Setting::getValue('institution_address', '', 'institution'),
            'institution_phone' => Setting::getValue('institution_phone', '', 'institution'),
            'institution_email' => Setting::getValue('institution_email', '', 'institution'),
            'institution_website' => Setting::getValue('institution_website', '', 'institution'),
            'institution_logo' => Setting::getValue('institution_logo', '', 'institution'),
            'institution_motto' => Setting::getValue('institution_motto', '', 'institution'),
            'institution_established' => Setting::getValue('institution_established', '', 'institution'),
        ];
        
        return view('settings.institution', compact('settings'));
    }
    
    /**
     * Update the institution settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateInstitution(Request $request)
    {
        $request->validate([
            'institution_name' => 'required|string|max:255',
            'institution_short_name' => 'nullable|string|max:50',
            'institution_code' => 'nullable|string|max:50',
            'institution_address' => 'nullable|string|max:500',
            'institution_phone' => 'nullable|string|max:20',
            'institution_email' => 'nullable|email|max:255',
            'institution_website' => 'nullable|url|max:255',
            'institution_motto' => 'nullable|string|max:255',
            'institution_established' => 'nullable|string|max:20',
        ]);
        
        try {
            // Update institution settings
            Setting::setValue('institution_name', $request->institution_name, 'institution');
            Setting::setValue('institution_short_name', $request->institution_short_name, 'institution');
            Setting::setValue('institution_code', $request->institution_code, 'institution');
            Setting::setValue('institution_address', $request->institution_address, 'institution');
            Setting::setValue('institution_phone', $request->institution_phone, 'institution');
            Setting::setValue('institution_email', $request->institution_email, 'institution');
            Setting::setValue('institution_website', $request->institution_website, 'institution');
            Setting::setValue('institution_motto', $request->institution_motto, 'institution');
            Setting::setValue('institution_established', $request->institution_established, 'institution');
            
            // Clear settings cache
            Cache::forget('settings');
            
            return redirect()->route('settings.institution')
                ->with('success', 'Institution settings updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating institution settings: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Update the institution logo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateLogo(Request $request)
    {
        $request->validate([
            'institution_logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        try {
            if ($request->hasFile('institution_logo')) {
                // Get the old logo path
                $oldLogo = Setting::getValue('institution_logo', '', 'institution');
                
                // Delete the old logo if it exists
                if ($oldLogo && file_exists(public_path('storage/' . $oldLogo))) {
                    unlink(public_path('storage/' . $oldLogo));
                }
                
                // Store the new logo
                $logoPath = $request->file('institution_logo')->store('logos', 'public');
                
                // Update the logo setting
                Setting::setValue('institution_logo', $logoPath, 'institution');
                
                // Clear settings cache
                Cache::forget('settings');
                
                return redirect()->route('settings.institution')
                    ->with('success', 'Institution logo updated successfully.');
            }
            
            return redirect()->back()
                ->with('error', 'No logo file provided.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating institution logo: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the academic years listing page.
     *
     * @return \Illuminate\View\View
     */
    public function listAcademicYears()
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        return view('settings.academic-years.index', compact('academicYears'));
    }
    
    /**
     * Show the form for creating a new academic year.
     *
     * @return \Illuminate\View\View
     */
    public function createAcademicYear()
    {
        return view('settings.academic-years.create');
    }
    
    /**
     * Store a newly created academic year in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createNewAcademicYear(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:academic_years,name',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive,completed',
            'description' => 'nullable|string|max:500',
        ]);
        
        try {
            // Create new academic year
            AcademicYear::create([
                'name' => $request->name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => $request->status,
                'description' => $request->description,
            ]);
            
            return redirect()->route('settings.academic-years')
                ->with('success', 'Academic year created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating academic year: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Show the form for editing the specified academic year.
     *
     * @param  \App\Models\AcademicYear  $academicYear
     * @return \Illuminate\View\View
     */
    public function editAcademicYear(AcademicYear $academicYear)
    {
        return view('settings.academic-years.edit', compact('academicYear'));
    }
    
    /**
     * Update the specified academic year in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AcademicYear  $academicYear
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAcademicYear(Request $request, AcademicYear $academicYear)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:academic_years,name,' . $academicYear->id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive,completed',
            'description' => 'nullable|string|max:500',
        ]);
        
        try {
            // Update academic year
            $academicYear->update([
                'name' => $request->name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => $request->status,
                'description' => $request->description,
            ]);
            
            return redirect()->route('settings.academic-years')
                ->with('success', 'Academic year updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating academic year: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Remove the specified academic year from storage.
     *
     * @param  \App\Models\AcademicYear  $academicYear
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyAcademicYear(AcademicYear $academicYear)
    {
        try {
            // Check if academic year has related records
            if ($academicYear->classes()->count() > 0 || $academicYear->results()->count() > 0 || $academicYear->academicRecords()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete academic year. It has associated classes, results, or academic records.');
            }
            
            // Delete academic year
            $academicYear->delete();
            
            return redirect()->route('settings.academic-years')
                ->with('success', 'Academic year deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting academic year: ' . $e->getMessage());
        }
    }
}
