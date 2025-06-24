<?php

use App\Http\Controllers\AcademicRecordController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ClassController;
 
use App\Http\Controllers\DashboardController;
 
 
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SettingsController;
 
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ProfileController;
 
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::get('password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Guest Routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('password.update.profile');
     
    // Students Management
    Route::resource('students', StudentController::class);
    Route::get('students/assign-class', [StudentController::class, 'assignClassForm'])->name('students.assign');
    Route::post('students/assign-class', [StudentController::class, 'syncStudentsToClass'])->name('students.assign.store');
    Route::put('students/{student}/remove-from-class/{class}', [StudentController::class, 'removeFromClass'])->name('students.remove-from-class');
    Route::get('students/{student}/results', [StudentController::class, 'results'])->name('students.results');
    Route::get('students-import', [StudentController::class, 'importForm'])->name('students.import.form');
    Route::post('students-import', [StudentController::class, 'processImport'])->name('students.import.process');
    Route::get('students-import-template', [StudentController::class, 'downloadImportTemplate'])->name('students.import.template');
     
     
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/students', [ReportController::class, 'studentPerformance'])->name('reports.students');
    Route::get('/reports/classes', [ReportController::class, 'classPerformance'])->name('reports.classes');
    Route::get('/reports/programmes', [ReportController::class, 'programmeStatistics'])->name('reports.programmes');
    Route::get('/reports/custom', [ReportController::class, 'customReport'])->name('reports.custom');
    Route::get('/reports/subject/{subject}', [ReportController::class, 'subjectReport'])->name('reports.subject');
    Route::get('/reports/gpa-distribution', [ReportController::class, 'gpaDistribution'])->name('reports.gpa-distribution');
    Route::get('/reports/course-performance', [ReportController::class, 'coursePerformance'])->name('reports.course-performance');
    Route::get('/reports/classification-distribution', [ReportController::class, 'classificationDistribution'])->name('reports.classification-distribution');
    Route::get('/reports/semester-comparison', [ReportController::class, 'semesterComparison'])->name('reports.semester-comparison');
    Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');
    
    // Settings Management
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('settings/initialize', [SettingsController::class, 'initialize'])->name('settings.initialize');
    Route::get('settings/academic-years', [SettingsController::class, 'academicYears'])->name('settings.academic-years');
    Route::post('settings/academic-years', [SettingsController::class, 'createNewAcademicYear'])->name('settings.academic-years.store');
    Route::get('settings/grade-schemes', [SettingsController::class, 'gradeSchemes'])->name('settings.grade-schemes');
    Route::get('settings/grade-schemes/create', [SettingsController::class, 'createGradeScheme'])->name('settings.grade-schemes.create');
    Route::post('settings/grade-schemes', [SettingsController::class, 'updateGradeSchemes'])->name('settings.grade-schemes.update');
    Route::get('settings/classifications', [SettingsController::class, 'classifications'])->name('settings.classifications');
    Route::post('settings/classifications', [SettingsController::class, 'updateClassifications'])->name('settings.classifications.update');
    Route::get('settings/backup', [SettingsController::class, 'backup'])->name('settings.backup');
    Route::post('settings/backup/create', [SettingsController::class, 'createBackup'])->name('settings.backup.create');
    Route::get('settings/backup/download/{filename}', [SettingsController::class, 'downloadBackup'])->name('settings.backup.download');
    Route::delete('settings/backup/delete/{filename}', [SettingsController::class, 'deleteBackup'])->name('settings.backup.delete');
    Route::get('settings/system', [SettingsController::class, 'system'])->name('settings.system');
    Route::post('settings/system', [SettingsController::class, 'updateSystem'])->name('settings.system.update');
    Route::get('settings/institution', [SettingsController::class, 'institution'])->name('settings.institution');
    Route::post('settings/institution', [SettingsController::class, 'updateInstitution'])->name('settings.institution.update');
    Route::post('settings/institution/logo', [SettingsController::class, 'updateLogo'])->name('settings.institution.logo.update');
    
    // Academic Years routes
    Route::get('settings/academic-years', [SettingsController::class, 'listAcademicYears'])->name('settings.academic-years');
    Route::get('settings/academic-years/create', [SettingsController::class, 'createAcademicYear'])->name('settings.academic-years.create');
    Route::post('settings/academic-years', [SettingsController::class, 'createNewAcademicYear'])->name('settings.academic-years.store');
    Route::get('settings/academic-years/{academicYear}/edit', [SettingsController::class, 'editAcademicYear'])->name('settings.academic-years.edit');
    Route::put('settings/academic-years/{academicYear}', [SettingsController::class, 'updateAcademicYear'])->name('settings.academic-years.update');
    Route::delete('settings/academic-years/{academicYear}', [SettingsController::class, 'destroyAcademicYear'])->name('settings.academic-years.destroy');
    
    // Teachers Management
    Route::resource('teachers', \App\Http\Controllers\TeacherController::class);
    
    // Academic Records Management
    Route::resource('academic-records', AcademicRecordController::class);
    Route::get('academic-records/reports', [AcademicRecordController::class, 'reports'])->name('academic-records.reports');
    Route::get('academic-records/{record}/print', [AcademicRecordController::class, 'print'])->name('academic-records.print');
    Route::get('academic-records/class/{class_id}/subject/{subject_id}', [AcademicRecordController::class, 'classSubject'])->name('academic-records.class-subject');
    Route::get('academic-records-bulk-entry', [AcademicRecordController::class, 'bulkEntryForm'])->name('academic-records.bulk-entry');
        
    // Teacher route redirects
    Route::middleware(['role:teacher'])->prefix('teacher')->group(function() {
         
     
    });
       
  
    Route::post('academic-records-bulk-store', [AcademicRecordController::class, 'bulkStore'])->name('academic-records.bulk-store');
    Route::get('academic-records-import-export', [AcademicRecordController::class, 'importExportForm'])->name('academic-records.import-export');
    Route::post('academic-records-import', [AcademicRecordController::class, 'import'])->name('academic-records.import');
    Route::get('academic-records-export', [AcademicRecordController::class, 'export'])->name('academic-records.export');
    Route::get('academic-records-export-template', [AcademicRecordController::class, 'exportTemplate'])->name('academic-records.export-template');
    Route::get('academic-records-export-report-cards', [AcademicRecordController::class, 'exportReportCards'])->name('academic-records.export-report-cards');
    Route::get('academic-records-bulk-upload', [AcademicRecordController::class, 'bulkUploadForm'])->name('academic-records.bulk-upload');
    Route::post('academic-records-bulk-upload', [AcademicRecordController::class, 'bulkUpload'])->name('academic-records.bulk-upload.store');
    
    // SMS Management
    Route::resource('sms', SmsController::class);
    Route::post('sms/send-bulk', [SmsController::class, 'sendBulk'])->name('sms.send-bulk');
    Route::get('sms/history', [SmsController::class, 'history'])->name('sms.history');
    Route::get('sms/results-form', [SmsController::class, 'resultsForm'])->name('sms.results-form');
    Route::post('sms/send-results', [SmsController::class, 'sendResults'])->name('sms.send-results');
      
   
  
    // API Routes for AJAX requests
    Route::prefix('api')->group(function() {
        Route::get('classes/{class}/subjects', [ClassController::class, 'getSubjects']);
        Route::get('classes/{class}/students', [ClassController::class, 'getStudents']);
        Route::get('subjects', [SubjectController::class, 'getAllSubjects']);
        Route::get('students/search', [StudentController::class, 'search']);
    });
     
    // Admin Only Routes - direct access without middleware
    // User Management
    Route::resource('users', UserController::class);
    Route::post('users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');
    
    // Teacher Management
    Route::resource('teachers', TeacherController::class);
    Route::post('teachers/bulk-action', [TeacherController::class, 'bulkAction'])->name('teachers.bulk-action');
    Route::put('teachers/{teacher}/toggle-status', [TeacherController::class, 'toggleStatus'])->name('teachers.toggle-status');
    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
    Route::get('/users/filter/role', [UserController::class, 'filterByRole'])->name('users.filter.role');
    
    // Lesson Attendance System Routes
    
    // Centres Management
    Route::resource('centres', \App\Http\Controllers\CentreController::class);
    
    // Lesson Sections Management
    Route::resource('lesson-sections', \App\Http\Controllers\LessonSectionController::class);
    
    // Lesson Schedules Management
    Route::resource('lesson-schedules', \App\Http\Controllers\LessonScheduleController::class);
    Route::get('lesson-schedules/{lessonSchedule}/assign-students', [\App\Http\Controllers\LessonScheduleController::class, 'assignStudentsForm'])->name('lesson-schedules.assign-students');
    Route::post('lesson-schedules/{lessonSchedule}/assign-students', [\App\Http\Controllers\LessonScheduleController::class, 'assignStudents'])->name('lesson-schedules.assign-students.store');
    
    // Lesson Attendance Management
    Route::get('lesson-attendances', [\App\Http\Controllers\LessonAttendanceController::class, 'index'])->name('lesson-attendances.index');
    Route::get('lesson-attendances/daily', [\App\Http\Controllers\LessonAttendanceController::class, 'daily'])->name('lesson-attendances.daily');
    Route::get('lesson-attendances/weekly', [\App\Http\Controllers\LessonAttendanceController::class, 'weekly'])->name('lesson-attendances.weekly');
    Route::get('lesson-attendances/monthly', [\App\Http\Controllers\LessonAttendanceController::class, 'monthly'])->name('lesson-attendances.monthly');
    Route::get('lesson-attendances/report', [\App\Http\Controllers\LessonAttendanceController::class, 'report'])->name('lesson-attendances.report');
    Route::get('lesson-attendances/{lessonSchedule}/take', [\App\Http\Controllers\LessonAttendanceController::class, 'takeAttendance'])->name('lesson-attendances.take');
    Route::post('lesson-attendances/{lessonSchedule}/store', [\App\Http\Controllers\LessonAttendanceController::class, 'storeAttendance'])->name('lesson-attendances.store');
    Route::get('lesson-attendances/send-birthday-wishes', [\App\Http\Controllers\LessonAttendanceController::class, 'sendBirthdayWishes'])->name('lesson-attendances.send-birthday-wishes');
});
