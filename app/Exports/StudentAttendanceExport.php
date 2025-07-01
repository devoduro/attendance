<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentAttendanceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->data['attendances']);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Date',
            'Subject',
            'Teacher',
            'Centre',
            'Section',
            'Status',
            'Remarks'
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->attendance_date,
            $row->lessonSchedule->subject->name ?? 'N/A',
            $row->lessonSchedule->teacher->user->name ?? 'N/A',
            $row->lessonSchedule->centre->name ?? 'N/A',
            $row->lessonSchedule->lessonSection->name ?? 'N/A',
            ucfirst($row->status),
            $row->remarks ?? ''
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return void
     */
    public function styles(Worksheet $sheet)
    {
        // Add student information at the top
        $student = $this->data['student'];
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'Attendance Report for: ' . $student->user->name);
        
        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', 'Student ID: ' . $student->student_id);
        
        $sheet->mergeCells('A3:G3');
        $sheet->setCellValue('A3', 'Attendance Rate: ' . number_format($this->data['attendanceRate'], 1) . '%');
        
        $sheet->mergeCells('A4:G4');
        $sheet->setCellValue('A4', 'Present: ' . $this->data['presentCount'] . ' | Absent: ' . $this->data['absentCount'] . ' | Total: ' . $this->data['totalAttendances']);
        
        // Style the headers
        $sheet->getStyle('A1:G1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2:G4')->getFont()->setBold(true);
        $sheet->getStyle('A6:G6')->getFont()->setBold(true);
        $sheet->getStyle('A6:G6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCCC');
        
        // Add borders to the data
        $lastRow = count($this->data['attendances']) + 6;
        $sheet->getStyle('A6:G' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    }
}
