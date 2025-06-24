<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Attendance Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #0062cc, #001a33);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
        }
        .footer {
            background-color: #f1f1f1;
            padding: 15px;
            text-align: center;
            font-size: 0.8em;
            color: #666;
            border-radius: 0 0 5px 5px;
            border: 1px solid #ddd;
        }
        .present {
            color: #28a745;
            font-weight: bold;
        }
        .absent {
            color: #dc3545;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Attendance Notification</h1>
        </div>
        
        <div class="content">
            <p>Dear Parent/Guardian of <strong>{{ $student->user->name }}</strong>,</p>
            
            <p>This is to inform you that your child was marked as 
                <span class="{{ $attendance->status === 'present' ? 'present' : 'absent' }}">
                    {{ $attendance->status === 'present' ? 'PRESENT' : 'ABSENT' }}
                </span> 
                for today's lesson.
            </p>
            
            <table>
                <tr>
                    <th>Date</th>
                    <td>{{ $attendance->attendance_date->format('l, F j, Y') }}</td>
                </tr>
                @if($attendance->status === 'present')
                <tr>
                    <th>Check-in Time</th>
                    <td>{{ $attendance->check_in_time->format('h:i A') }}</td>
                </tr>
                @endif
                <tr>
                    <th>Centre</th>
                    <td>{{ $centre->name }}</td>
                </tr>
                <tr>
                    <th>Lesson Section</th>
                    <td>{{ $lessonSection->name }} ({{ $lessonSection->start_time->format('h:i A') }} - {{ $lessonSection->end_time->format('h:i A') }})</td>
                </tr>
                <tr>
                    <th>Day</th>
                    <td>{{ $lessonSchedule->day_of_week }}</td>
                </tr>
            </table>
            
            @if($attendance->status === 'absent')
            <p>If you believe this is an error or if your child will be absent for future lessons, please contact us.</p>
            @endif
            
            <p>Thank you for your continued support.</p>
            
            <p>Best regards,<br>
            The School Administration</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} School Attendance System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
