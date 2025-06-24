<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ResultsExport implements FromArray, WithHeadings, WithStyles
{
    /**
     * @return array
     */
    public function array(): array
    {
        // Return sample data for the template
        return [
            ['STU12345', 'A', 35, 55],
            ['STU67890', 'B+', 30, 50],
            ['STU24680', '75', 32, 43],
        ];
    }
    
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'index_number',
            'grade',
            'assessment_score',
            'exam_score',
        ];
    }
    
    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E9E9E9'],
            ],
        ]);
        
        // Add notes in cell A5
        $sheet->setCellValue('A5', 'Notes:');
        $sheet->setCellValue('A6', '1. index_number: Student index number (required)');
        $sheet->setCellValue('A7', '2. grade: Either a letter grade (A, B+, etc.) or a numeric score (0-100) (required)');
        $sheet->setCellValue('A8', '3. assessment_score: Continuous assessment score (0-40) (optional)');
        $sheet->setCellValue('A9', '4. exam_score: Examination score (0-60) (optional)');
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(15);
        
        return $sheet;
    }
}
