<!DOCTYPE html>
<html>
<head>
    <title>Attendance Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .present {
            color: green;
        }
        .absent {
            color: red;
        }
        .late {
            color: orange;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Attendance Records</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Student</th>
                <th>Student ID</th>
                <th>Class</th>
                <th>Section</th>
                <th>Teacher</th>
                <th>Status</th>
                <th>Comments</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->id }}</td>
                    <td>{{ $attendance->attendance_date->format('Y-m-d') }}</td>
                    <td>{{ $attendance->student->user->name ?? 'N/A' }}</td>
                    <td>{{ $attendance->student->student_id ?? 'N/A' }}</td>
                    <td>{{ $attendance->lessonSchedule->class->name ?? 'N/A' }}</td>
                    <td>{{ $attendance->lessonSchedule->lessonSection->name ?? 'N/A' }}</td>
                    <td>{{ $attendance->lessonSchedule->teacher->user->name ?? 'N/A' }}</td>
                    <td class="{{ $attendance->status }}">{{ ucfirst($attendance->status) }}</td>
                    <td>{{ $attendance->comments }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">No attendance records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>
