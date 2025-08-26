<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
    <style>
        body {
            font-family: "Inter", "Cairo", sans-serif;
            background: #f5f6fa;
            padding: 20px;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            margin: auto;
        }
        .logo img {
            height: 50px;
            margin-bottom: 15px;
        }
        h2 {
            color: #2B61B2;
        }
        .otp {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 5px;
            color: #333;
            margin: 20px 0;
        }
        p {
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="{{ asset('images/app_logo.svg') }}" alt="Logo">
        </div>
        <h2>Email Verification</h2>
        <p>Hello {{ $user->name }},</p>
        <p>Your verification code is:</p>
        <div class="otp">{{ $otp }}</div>
        <p>This code will expire in 10 minutes.</p>
    </div>
</body>
</html>
