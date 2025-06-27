<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $studentIds;

    public function __construct($studentIds = null)
    {
        $this->studentIds = $studentIds;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Student::query()->with(['user', 'class', 'house', 'centre']);
        
        if ($this->studentIds) {
            $query->whereIn('id', $this->studentIds);
        }
        
        return $query;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Enrollment Code',
            'Name',
            'Email',
            'Class',
            'House',
            'Centre',
            'Mobile Phone',
            'Date of Birth',
            'Guardian Name',
            'Parent Contact',
            'Admission Date',
            'Status'
        ];
    }

    /**
     * @param mixed $student
     * @return array
     */
    public function map($student): array
    {
        return [
            $student->id,
            $student->enrollment_code,
            $student->user->name ?? 'N/A',
            $student->email,
            $student->class->name ?? 'Not Assigned',
            $student->house->name ?? 'Not Assigned',
            $student->centre->name ?? 'Not Assigned',
            $student->mobile_phone ?? 'N/A',
            $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : 'N/A',
            $student->guardians_name ?? 'N/A',
            $student->parent_contact_number1 ?? 'N/A',
            $student->admission_date ? $student->admission_date->format('Y-m-d') : 'N/A',
            ucfirst($student->status ?? 'N/A')
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
}
