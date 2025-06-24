<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get the column listing for the results table
$columns = \Illuminate\Support\Facades\Schema::getColumnListing('results');
print_r($columns);

// Check if assessment_score and exam_score columns exist
echo "\nDoes assessment_score column exist? " . (in_array('assessment_score', $columns) ? 'Yes' : 'No');
echo "\nDoes exam_score column exist? " . (in_array('exam_score', $columns) ? 'Yes' : 'No');
