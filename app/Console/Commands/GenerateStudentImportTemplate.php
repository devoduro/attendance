<?php

namespace App\Console\Commands;

use App\Models\Programme;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GenerateStudentImportTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:student-template';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a template Excel file for importing students';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating student import template...');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = [
            'index_number', 
            'full_name', 
            'date_of_birth', 
            'gender', 
            'programme_id',
            'email',
            'phone',
            'address',
            'emergency_contact_name',
            'emergency_contact_phone'
        ];

        foreach ($headers as $key => $header) {
            $column = chr(65 + $key); // Convert to column letter (A, B, C, etc.)
            $sheet->setCellValue($column . '1', $header);
            $sheet->getStyle($column . '1')->getFont()->setBold(true);
        }

        // Add sample data
        $sampleData = [
            ['STU001', 'John Doe', '2000-01-15', 'Male', '1', 'john.doe@example.com', '+233 50 123 4567', '123 Main St, Accra', 'Jane Doe', '+233 50 765 4321'],
            ['STU002', 'Jane Smith', '2001-05-20', 'Female', '2', 'jane.smith@example.com', '+233 55 987 6543', '456 Park Ave, Kumasi', 'John Smith', '+233 55 345 6789'],
        ];

        $row = 2;
        foreach ($sampleData as $data) {
            foreach ($data as $key => $value) {
                $column = chr(65 + $key);
                $sheet->setCellValue($column . $row, $value);
            }
            $row++;
        }

        // Add notes about gender and programme_id
        $row = 5;
        $sheet->setCellValue('A' . $row, 'Notes:');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        
        $row++;
        $sheet->setCellValue('A' . $row, '- Gender must be one of: Male, Female, Other');
        
        $row++;
        $sheet->setCellValue('A' . $row, '- Available Programme IDs:');
        
        // Get all programmes and add them to the notes
        $programmes = Programme::all();
        foreach ($programmes as $programme) {
            $row++;
            $sheet->setCellValue('A' . $row, "  * ID: {$programme->id} - {$programme->name}");
        }
        
        $row += 2;
        $sheet->setCellValue('A' . $row, '- Date format should be YYYY-MM-DD');

        // Auto size columns
        foreach (range('A', 'J') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Save the spreadsheet
        $writer = new Xlsx($spreadsheet);
        $filePath = storage_path('app/templates/students_import_template.xlsx');
        $writer->save($filePath);

        $this->info('Template generated successfully at: ' . $filePath);
        
        return 0;
    }
}
