<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Happy Birthday!</title>
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
            text-align: center;
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
        .birthday-message {
            font-size: 1.2em;
            margin: 20px 0;
        }
        .cake {
            font-size: 3em;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Happy Birthday!</h1>
        </div>
        
        <div class="content">
            <div class="cake">🎂</div>
            
            <h2>Happy Birthday to {{ $student->user->name }}!</h2>
            
            <div class="birthday-message">
                <p>Dear Parent/Guardian,</p>
                
                <p>Today is a special day as we celebrate {{ $student->user->name }}'s birthday!</p>
                
                <p>We would like to extend our warmest wishes to {{ $student->user->name }} on this joyous occasion.</p>
                
                <p>May this year bring lots of happiness, success, and wonderful moments!</p>
                
                <p>🎉 🎁 🎈</p>
            </div>
            
            <p>Best wishes,<br>
            The School Administration</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} School Attendance System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
