<?php

namespace App\Mail;

use App\Models\LessonAttendance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AttendanceNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The attendance instance.
     *
     * @var \App\Models\LessonAttendance
     */
    public $attendance;

    /**
     * Create a new message instance.
     */
    public function __construct(LessonAttendance $attendance)
    {
        $this->attendance = $attendance;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $status = $this->attendance->status === 'present' ? 'Present' : 'Absent';
        $studentName = $this->attendance->student->user->name;
        
        return new Envelope(
            subject: "Attendance Notification: {$studentName} is {$status}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $student = $this->attendance->student;
        $lessonSchedule = $this->attendance->lessonSchedule;
        $lessonSection = $lessonSchedule->lessonSection;
        $centre = $lessonSchedule->centre;
        
        return new Content(
            view: 'emails.attendance-notification',
            with: [
                'attendance' => $this->attendance,
                'student' => $student,
                'lessonSchedule' => $lessonSchedule,
                'lessonSection' => $lessonSection,
                'centre' => $centre,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
