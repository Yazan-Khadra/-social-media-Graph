<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Technical Support Response</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            padding: 20px;
            color: #333;
        }
        .email-container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-width: 100px;
        }
        .header h1 {
            margin: 10px 0 0 0;
            font-size: 24px;
            color: #007571; /* Graph color */
        }
        .content p {
            line-height: 1.6;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header with Logo and Graph name -->
        <div class="header">
            <img src="{{ asset('images/app_logo.svg') }}" alt="Logo">
            
        </div>

        <!-- Email Content -->
        <div class="content">
            <p>Hello {{ $issue->name }},</p>
            <p>Thank you for contacting support regarding:</p>
            <p><strong>{{ $issue->issue }}</strong></p>
            <p>Our response:</p>
            <p>{{ $response }}</p>
        </div>

        <div class="footer">
            <p>Best regards,</p>
            <p>Technical Support Team</p>
        </div>
    </div>
</body>
</html>
