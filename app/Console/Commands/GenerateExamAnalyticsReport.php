<?php

namespace App\Console\Commands;

use App\Models\Exam;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class GenerateExamAnalyticsReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exams:analytics-report
                            {exam_id? : The ID of the exam to generate analytics for (optional)}
                            {--all : Generate reports for all published exams}
                            {--completed : Only include exams with completed attempts}
                            {--days=30 : Only include exams active in the last X days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate analytics reports for exams';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $examId = $this->argument('exam_id');
        $all = $this->option('all');
        $onlyCompleted = $this->option('completed');
        $days = $this->option('days');
        
        if (!$examId && !$all) {
            $this->error('Please provide an exam ID or use the --all option.');
            return 1;
        }
        
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $reportPath = 'reports/exam_analytics/' . $timestamp;
        Storage::makeDirectory($reportPath);
        
        if ($examId) {
            $exam = Exam::find($examId);
            
            if (!$exam) {
                $this->error("Exam with ID {$examId} not found.");
                return 1;
            }
            
            $this->generateReportForExam($exam, $reportPath);
        } else {
            $query = Exam::where('status', 'published');
            
            if ($days > 0) {
                $cutoffDate = Carbon::now()->subDays($days);
                $query->where('created_at', '>=', $cutoffDate);
            }
            
            if ($onlyCompleted) {
                $query->whereHas('studentExams', function ($q) {
                    $q->whereIn('status', ['completed', 'graded']);
                });
            }
            
            $exams = $query->get();
            
            if ($exams->isEmpty()) {
                $this->info('No exams found matching the criteria.');
                return 0;
            }
            
            $this->info("Generating reports for {$exams->count()} exams...");
            
            $bar = $this->output->createProgressBar($exams->count());
            $bar->start();
            
            foreach ($exams as $exam) {
                $this->generateReportForExam($exam, $reportPath);
                $bar->advance();
            }
            
            $bar->finish();
            $this->newLine(2);
        }
        
        $this->info("Reports generated successfully in storage/{$reportPath}");
        return 0;
    }
    
    /**
     * Generate analytics report for a single exam
     */
    private function generateReportForExam(Exam $exam, string $reportPath)
    {
        $exam->load(['questions', 'studentExams.student', 'studentExams.answers']);
        
        // Skip exams with no attempts if they don't have useful analytics
        if ($exam->studentExams->whereIn('status', ['completed', 'graded'])->isEmpty()) {
            $this->comment("Skipping exam '{$exam->title}' (ID: {$exam->id}) - No completed attempts.");
            return;
        }
        
        // Basic statistics
        $stats = [
            'exam_id' => $exam->id,
            'title' => $exam->title,
            'subject' => $exam->subject->name,
            'teacher' => $exam->teacher->user->name,
            'total_attempts' => $exam->studentExams->count(),
            'completed_attempts' => $exam->studentExams->whereIn('status', ['completed', 'graded'])->count(),
            'average_score' => $exam->getAverageScore(),
            'highest_score' => $exam->studentExams->max('score'),
            'lowest_score' => $exam->studentExams->min('score'),
            'pass_rate' => $exam->getPassRate(),
            'difficulty_level' => $exam->calculateDifficultyLevel(),
            'generated_at' => Carbon::now()->toDateTimeString(),
        ];
        
        // Generate CSV files
        $this->generateStatsCSV($exam, $stats, $reportPath);
        $this->generateQuestionAnalyticsCSV($exam, $reportPath);
        $this->generateStudentPerformanceCSV($exam, $reportPath);
        
        // Generate summary report
        $this->generateSummaryReport($exam, $stats, $reportPath);
    }
    
    /**
     * Generate CSV with basic exam statistics
     */
    private function generateStatsCSV(Exam $exam, array $stats, string $reportPath)
    {
        $filename = "exam_{$exam->id}_stats.csv";
        $headers = array_keys($stats);
        
        $csvContent = implode(',', $headers) . "\n";
        $csvContent .= implode(',', array_map(function ($value) {
            return is_string($value) ? '"' . str_replace('"', '""', $value) . '"' : $value;
        }, $stats));
        
        Storage::put("{$reportPath}/{$filename}", $csvContent);
    }
    
    /**
     * Generate CSV with question analytics
     */
    private function generateQuestionAnalyticsCSV(Exam $exam, string $reportPath)
    {
        $filename = "exam_{$exam->id}_question_analytics.csv";
        $questionAnalytics = $exam->getQuestionAnalytics();
        
        if (empty($questionAnalytics)) {
            return;
        }
        
        // Get headers from the first item
        $firstQuestion = reset($questionAnalytics);
        $headers = array_keys($firstQuestion);
        array_unshift($headers, 'question_id');
        
        $csvContent = implode(',', $headers) . "\n";
        
        foreach ($questionAnalytics as $questionId => $analytics) {
            $row = [$questionId];
            foreach ($analytics as $value) {
                $row[] = is_string($value) ? '"' . str_replace('"', '""', $value) . '"' : $value;
            }
            $csvContent .= implode(',', $row) . "\n";
        }
        
        Storage::put("{$reportPath}/{$filename}", $csvContent);
    }
    
    /**
     * Generate CSV with student performance data
     */
    private function generateStudentPerformanceCSV(Exam $exam, string $reportPath)
    {
        $filename = "exam_{$exam->id}_student_performance.csv";
        $studentPerformance = $exam->getStudentPerformance();
        
        if (empty($studentPerformance)) {
            return;
        }
        
        // Get headers from the first item
        $headers = array_keys($studentPerformance[0]);
        
        $csvContent = implode(',', $headers) . "\n";
        
        foreach ($studentPerformance as $performance) {
            $row = [];
            foreach ($performance as $value) {
                $row[] = is_string($value) ? '"' . str_replace('"', '""', $value) . '"' : $value;
            }
            $csvContent .= implode(',', $row) . "\n";
        }
        
        Storage::put("{$reportPath}/{$filename}", $csvContent);
    }
    
    /**
     * Generate a summary report in text format
     */
    private function generateSummaryReport(Exam $exam, array $stats, string $reportPath)
    {
        $filename = "exam_{$exam->id}_summary.txt";
        
        $content = "EXAM ANALYTICS SUMMARY\n";
        $content .= "=====================\n\n";
        $content .= "Exam: {$stats['title']} (ID: {$stats['exam_id']})\n";
        $content .= "Subject: {$stats['subject']}\n";
        $content .= "Teacher: {$stats['teacher']}\n";
        $content .= "Generated: {$stats['generated_at']}\n\n";
        
        $content .= "PERFORMANCE METRICS\n";
        $content .= "------------------\n";
        $content .= "Total Attempts: {$stats['total_attempts']}\n";
        $content .= "Completed Attempts: {$stats['completed_attempts']}\n";
        $content .= "Average Score: " . round($stats['average_score'], 1) . "\n";
        $content .= "Highest Score: {$stats['highest_score']}\n";
        $content .= "Lowest Score: {$stats['lowest_score']}\n";
        $content .= "Pass Rate: " . round($stats['pass_rate'], 1) . "%\n";
        $content .= "Difficulty Level: {$stats['difficulty_level']}\n\n";
        
        // Add question performance summary
        $questionAnalytics = $exam->getQuestionAnalytics();
        if (!empty($questionAnalytics)) {
            $content .= "QUESTION PERFORMANCE\n";
            $content .= "-------------------\n";
            
            $i = 1;
            foreach ($questionAnalytics as $questionId => $analytics) {
                $content .= "Q{$i}. " . substr($analytics['question_text'], 0, 50) . (strlen($analytics['question_text']) > 50 ? '...' : '') . "\n";
                $content .= "   Type: {$analytics['type']}, Difficulty: {$analytics['difficulty']}\n";
                $content .= "   Success Rate: " . round($analytics['success_rate'], 1) . "%\n";
                $content .= "   Avg Marks: " . round($analytics['average_marks'], 1) . " / {$analytics['max_marks']}\n\n";
                $i++;
            }
        }
        
        Storage::put("{$reportPath}/{$filename}", $content);
    }
}
