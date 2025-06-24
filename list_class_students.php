<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use App\Models\Exam;

// Find the class with our test
$class = SchoolClass::find(1); // SHS1 Science 1A
if (!$class) {
    echo "Class not found\n";
    exit(1);
}

echo "Class: " . $class->name . " (ID: " . $class->id . ")\n";
echo "-------------------------------------\n";

// Find exams for this class
$exams = Exam::whereHas('classes', function($query) use ($class) {
    $query->where('class_id', $class->id);
})->get();

echo "Exams for this class:\n";
foreach ($exams as $exam) {
    echo "- " . $exam->title . " (ID: " . $exam->id . ")\n";
}
echo "-------------------------------------\n";

// Find students in this class
$students = Student::where('class_id', $class->id)
    ->with('user')
    ->get();

echo "Students in this class:\n";
if ($students->isEmpty()) {
    echo "No students found in this class.\n";
} else {
    foreach ($students as $student) {
        echo "- " . $student->user->name . " (ID: " . $student->id . ", User ID: " . $student->user->id . ")\n";
    }
}
