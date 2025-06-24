<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Gate;

// Test for teacher ID 4 (Linda Nkrumah)
$teacher = User::find(4);

if (!$teacher) {
    echo "Teacher with ID 4 not found.\n";
    exit;
}

echo "Testing permissions for teacher: " . $teacher->name . "\n";
echo "Roles assigned: " . implode(', ', $teacher->getRoleNames()->toArray()) . "\n";
echo "Has 'teacher' role: " . ($teacher->hasRole('teacher') ? 'Yes' : 'No') . "\n";
echo "Can 'manage-exams': " . (Gate::forUser($teacher)->allows('manage-exams') ? 'Yes' : 'No') . "\n";

// Check if teacher profile exists
if ($teacher->teacherProfile) {
    echo "Teacher profile exists with ID: " . $teacher->teacherProfile->id . "\n";
    
    // Check assigned classes
    $classes = $teacher->teacherProfile->classes;
    echo "Number of assigned classes: " . $classes->count() . "\n";
    
    if ($classes->count() > 0) {
        echo "Assigned classes:\n";
        foreach ($classes as $class) {
            echo "- {$class->name} (ID: {$class->id})\n";
        }
    }
} else {
    echo "Teacher profile does not exist!\n";
}

// Check if the user can access the ExamController
echo "\nTesting access to ExamController:\n";
$request = new Illuminate\Http\Request();
$controller = new App\Http\Controllers\ExamController();

try {
    $reflectionClass = new ReflectionClass($controller);
    $middleware = $reflectionClass->getProperty('middleware');
    $middleware->setAccessible(true);
    
    echo "Controller middleware: " . print_r($middleware->getValue($controller), true) . "\n";
} catch (Exception $e) {
    echo "Could not access controller middleware: " . $e->getMessage() . "\n";
}

echo "\nDone testing permissions.\n";
