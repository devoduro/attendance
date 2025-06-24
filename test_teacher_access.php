<?php
require __DIR__.'/vendor/autoload.php';

// Load environment variables
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Set up the application
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Find teacher with ID 4 (Linda Nkrumah)
$teacher = App\Models\Teacher::with(['user', 'classes.students'])->find(4);

if (!$teacher) {
    echo "Teacher with ID 4 not found.\n";
    exit;
}

echo "=== Teacher Information ===\n";
echo "ID: {$teacher->id}\n";
echo "Name: {$teacher->user->name}\n";
echo "Email: {$teacher->user->email}\n";
echo "Role: " . ($teacher->user->hasRole('teacher') ? 'Teacher' : 'Role missing!') . "\n\n";

echo "=== Assigned Classes ===\n";
if ($teacher->classes->isEmpty()) {
    echo "No classes assigned to this teacher.\n";
} else {
    foreach ($teacher->classes as $class) {
        echo "Class ID: {$class->id}, Name: {$class->name}, Level: {$class->level}\n";
        echo "  Students in this class: {$class->students->count()}\n";
        
        if ($class->students->count() > 0) {
            echo "  === Students ===\n";
            foreach ($class->students as $student) {
                echo "  - Student ID: {$student->id}, Name: {$student->user->name}\n";
            }
        }
        echo "\n";
    }
}

// Test the access control logic in ClassController
echo "=== Testing Access Control Logic ===\n";

// Create a mock request
$request = new Illuminate\Http\Request();

// Create a mock auth user (teacher ID 4)
$user = $teacher->user;
Auth::login($user);

// Create an instance of the ClassController
$classController = new App\Http\Controllers\ClassController();

// Test the index method (should return classes for teacher ID 4)
echo "Testing ClassController@index...\n";
try {
    $response = $classController->index($request);
    if ($response instanceof Illuminate\View\View) {
        $classes = $response->getData()['classes'];
        echo "Success! Found " . $classes->count() . " classes for teacher ID 4\n";
    } else {
        echo "Error: Unexpected response type\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Test the students method with the first class
if (!$teacher->classes->isEmpty()) {
    $firstClass = $teacher->classes->first();
    echo "\nTesting ClassController@students with class ID {$firstClass->id}...\n";
    try {
        $response = $classController->students($firstClass);
        if ($response instanceof Illuminate\View\View) {
            $students = $response->getData()['students'];
            echo "Success! Found " . $students->count() . " students in class ID {$firstClass->id}\n";
        } else {
            echo "Error: Unexpected response type\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

echo "\nTest completed.\n";
