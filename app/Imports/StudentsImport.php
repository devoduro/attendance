<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Student([
            'index_number'           => $row['index_number'],
            'full_name'              => $row['full_name'],
            'date_of_birth'          => $this->transformDate($row['date_of_birth']),
            'gender'                 => $row['gender'],
            'programme_id'           => $row['programme_id'],
            'email'                  => $row['email'] ?? null,
            'phone'                  => $row['phone'] ?? null,
            'address'                => $row['address'] ?? null,
            'emergency_contact_name' => $row['emergency_contact_name'] ?? null,
            'emergency_contact_phone'=> $row['emergency_contact_phone'] ?? null,
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'index_number' => [
                'required',
                'string',
                Rule::unique('students', 'index_number'),
            ],
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required',
            'gender' => 'required|in:Male,Female,Other',
            'programme_id' => 'required|exists:programmes,id',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ];
    }

    /**
     * Transform a date value from the Excel file.
     *
     * @param mixed $value
     * @return string
     */
    private function transformDate($value)
    {
        try {
            // Check if the value is numeric (Excel date format)
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
            
            // If it's already a string in a date format, just return it
            if (is_string($value) && strtotime($value)) {
                return date('Y-m-d', strtotime($value));
            }
            
            // Default fallback
            return date('Y-m-d');
        } catch (\Exception $e) {
            // If any error occurs, return today's date as fallback
            return date('Y-m-d');
        }
    }
}
