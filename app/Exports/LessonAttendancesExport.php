<?php

namespace App\Exports;

use App\Models\LessonAttendance;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LessonAttendancesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Date',
            'Student',
            'Student ID',
            'Class',
            'Section',
            'Teacher',
            'Status',
            'Comments',
            'Created At'
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->id,
            $attendance->attendance_date->format('Y-m-d'),
            $attendance->student->user->name ?? 'N/A',
            $attendance->student->student_id ?? 'N/A',
            $attendance->lessonSchedule->class->name ?? 'N/A',
            $attendance->lessonSchedule->lessonSection->name ?? 'N/A',
            $attendance->lessonSchedule->teacher->user->name ?? 'N/A',
            ucfirst($attendance->status),
            $attendance->comments,
            $attendance->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
