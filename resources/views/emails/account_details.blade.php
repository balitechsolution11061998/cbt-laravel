<!DOCTYPE html>
<html>
<head>
    <title>Account Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #4CAF50;
            color: #fff;
            padding: 20px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
        }
        .header svg {
            width: 40px;
            height: 40px;
            margin-bottom: 10px;
        }
        .content {
            padding: 20px;
        }
        .content p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 10px;
        }
        .footer {
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eaeaea;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                <path d="M22 5.08V19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5.08M20 5L12 13 4 5"/>
            </svg>
            <h1>Your Account Details</h1>
        </div>
        <div class="content">
            <p>Dear {{ $details['name'] }},</p>
            <p>Berikut adalah akun yang sudah terdaftar di sistem kami. Silahkan login ke sistem kami dengan akun tersebut melalui link berikut ini:</p>
            <p><a href="{{ $details['login_url'] }}">Login to Your Account</a></p>
            <p><strong>Username:</strong> {{ $details['username'] }}</p>
            <p><strong>Password:</strong> {{ $details['password'] }}</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
