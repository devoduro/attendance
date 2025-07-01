<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Attendance Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .student-info {
            margin-bottom: 20px;
        }
        .student-info p {
            margin: 5px 0;
        }
        .stats {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        .stats-item {
            display: inline-block;
            margin-right: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
            color: #28a745;
        }
        .absent {
            color: #dc3545;
        }
        .late {
            color: #ffc107;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Student Attendance Report</h1>
        <p>Generated on: {{ now()->format('d M Y, H:i') }}</p>
    </div>

    <div class="student-info">
        <p><strong>Name:</strong> {{ $student->user->name }}</p>
        <p><strong>Student ID:</strong> {{ $student->student_id }}</p>
        <p><strong>Class:</strong> {{ $student->class->name ?? 'N/A' }}</p>
        <p><strong>Contact:</strong> {{ $student->user->phone ?? 'N/A' }}</p>
    </div>

    <div class="stats">
        <div class="stats-item">
            <strong>Total Attendances:</strong> {{ $totalAttendances }}
        </div>
        <div class="stats-item">
            <strong>Present:</strong> {{ $presentCount }}
        </div>
        <div class="stats-item">
            <strong>Absent:</strong> {{ $absentCount }}
        </div>
        <div class="stats-item">
            <strong>Attendance Rate:</strong> {{ number_format($attendanceRate, 1) }}%
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Subject</th>
                <th>Teacher</th>
                <th>Centre</th>
                <th>Section</th>
                <th>Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->attendance_date }}</td>
                    <td>{{ $attendance->lessonSchedule->subject->name ?? 'N/A' }}</td>
                    <td>{{ $attendance->lessonSchedule->teacher->user->name ?? 'N/A' }}</td>
                    <td>{{ $attendance->lessonSchedule->centre->name ?? 'N/A' }}</td>
                    <td>{{ $attendance->lessonSchedule->lessonSection->name ?? 'N/A' }}</td>
                    <td class="{{ $attendance->status }}">{{ ucfirst($attendance->status) }}</td>
                    <td>{{ $attendance->remarks ?? '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">No attendance records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>This is an automatically generated report. Please contact the school administration for any queries.</p>
    </div>
</body>
</html>
