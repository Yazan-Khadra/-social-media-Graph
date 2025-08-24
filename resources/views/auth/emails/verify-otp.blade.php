<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="otp-container">
        <!-- Logo -->
        <div class="logo">
            <img src="{{ asset('images/app_logo.svg') }}" alt="Logo">
            <span>Graph</span>
        </div>

        <h2>Email Verification</h2>
        <p>Please enter the 6-digit OTP we sent to your email.</p>

       

        {{-- رسالة نجاح --}}
        @if (session('success'))
            <div class="success-box">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('email.verify.otp') }}">
            @csrf
            <input type="text" name="otp" placeholder="Enter OTP" maxlength="6" required>
            <button type="submit">Verify</button>
        </form>
    </div>
</body>
</html>
