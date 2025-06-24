<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\Question;
use App\Models\StudentExam;
use App\Models\StudentAnswer;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;

class ExamAnalyticsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $teacher;
    protected $admin;
    protected $student;
    protected $exam;
    protected $subject;

    public function setUp(): void
    {
        parent::setUp();

        // Create a teacher user
        $this->teacher = User::factory()->create(['role' => 'teacher']);
        $teacherProfile = Teacher::factory()->create(['user_id' => $this->teacher->id]);
        
        // Create an admin user
        $this->admin = User::factory()->create(['role' => 'admin']);
        
        // Create a student user
        $this->student = User::factory()->create(['role' => 'student']);
        $studentProfile = Student::factory()->create(['user_id' => $this->student->id]);
        
        // Create a subject
        $this->subject = Subject::factory()->create([
            'teacher_id' => $teacherProfile->id
        ]);
        
        // Create an exam
        $this->exam = Exam::factory()->create([
            'subject_id' => $this->subject->id,
            'teacher_id' => $teacherProfile->id,
            'title' => 'Test Exam for Analytics',
            'description' => 'This is a test exam for analytics',
            'duration' => 60,
            'passing_score' => 60,
            'status' => 'published'
        ]);
        
        // Create questions for the exam
        $questions = Question::factory()->count(10)->create([
            'subject_id' => $this->subject->id,
            'teacher_id' => $teacherProfile->id,
            'marks' => 10
        ]);
        
        // Attach questions to the exam
        $this->exam->questions()->attach($questions->pluck('id'));
        
        // Create student exams and answers
        $this->createStudentExamsWithAnswers();
    }
    
    /**
     * Create sample student exams and answers for testing
     */
    private function createStudentExamsWithAnswers()
    {
        // Create 5 student exams with different scores
        $scores = [40, 55, 70, 85, 95];
        $students = Student::factory()->count(5)->create();
        
        foreach ($students as $index => $student) {
            $startTime = Carbon::now()->subHours(2);
            $endTime = $startTime->copy()->addMinutes(rand(30, 55));
            
            $studentExam = StudentExam::create([
                'student_id' => $student->id,
                'exam_id' => $this->exam->id,
                'score' => $scores[$index],
                'status' => 'completed',
                'start_time' => $startTime,
                'end_time' => $endTime
            ]);
            
            // Create answers for each question
            foreach ($this->exam->questions as $question) {
                // Determine if the answer is correct based on the student's score
                $isCorrect = rand(0, 100) <= $scores[$index];
                
                StudentAnswer::create([
                    'student_exam_id' => $studentExam->id,
                    'question_id' => $question->id,
                    'answer_text' => $this->faker->sentence,
                    'marks_awarded' => $isCorrect ? $question->marks : 0
                ]);
            }
        }
    }

    /**
     * Test that a teacher can view analytics for their own exam
     */
    public function test_teacher_can_view_analytics_for_own_exam()
    {
        $response = $this->actingAs($this->teacher)
                         ->get(route('exams.analytics', $this->exam));
        
        $response->assertStatus(200);
        $response->assertViewIs('exams.analytics');
        $response->assertViewHas('exam');
        $response->assertViewHas('stats');
        $response->assertViewHas('questionAnalytics');
        $response->assertViewHas('scoreDistribution');
        $response->assertViewHas('timeDistribution');
        $response->assertViewHas('studentPerformance');
    }
    
    /**
     * Test that an admin can view analytics for any exam
     */
    public function test_admin_can_view_analytics_for_any_exam()
    {
        $response = $this->actingAs($this->admin)
                         ->get(route('exams.analytics', $this->exam));
        
        $response->assertStatus(200);
        $response->assertViewIs('exams.analytics');
    }
    
    /**
     * Test that a student cannot view analytics
     */
    public function test_student_cannot_view_analytics()
    {
        $response = $this->actingAs($this->student)
                         ->get(route('exams.analytics', $this->exam));
        
        $response->assertStatus(403); // Forbidden
    }
    
    /**
     * Test that another teacher cannot view analytics for an exam they don't own
     */
    public function test_other_teacher_cannot_view_analytics()
    {
        $otherTeacher = User::factory()->create(['role' => 'teacher']);
        
        $response = $this->actingAs($otherTeacher)
                         ->get(route('exams.analytics', $this->exam));
        
        $response->assertStatus(403); // Forbidden
    }
    
    /**
     * Test the exam analytics data
     */
    public function test_exam_analytics_data()
    {
        $this->exam->load(['questions', 'studentExams.student', 'studentExams.answers']);
        
        // Test average score
        $this->assertEquals(69, round($this->exam->getAverageScore()));
        
        // Test pass rate (passing score is 60)
        $this->assertEquals(60, round($this->exam->getPassRate()));
        
        // Test score distribution
        $scoreDistribution = $this->exam->getScoreDistribution();
        $this->assertCount(10, $scoreDistribution); // 0-10, 11-20, ..., 91-100
        
        // Test time distribution
        $timeDistribution = $this->exam->getTimeDistribution();
        $this->assertIsArray($timeDistribution);
        
        // Test question analytics
        $questionAnalytics = $this->exam->getQuestionAnalytics();
        $this->assertCount(10, $questionAnalytics); // 10 questions
        
        // Test student performance
        $studentPerformance = $this->exam->getStudentPerformance();
        $this->assertCount(5, $studentPerformance); // 5 students
    }
    
    /**
     * Test exporting analytics data
     */
    public function test_export_analytics()
    {
        $response = $this->actingAs($this->teacher)
                         ->get(route('exams.analytics.export', $this->exam));
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
        $response->assertHeader('Content-Disposition', 'attachment; filename="' . $this->exam->title . '_analytics.csv"');
    }
}
