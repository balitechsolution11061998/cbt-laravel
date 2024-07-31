<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login QR Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        p {
            color: #555;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .qr-code img {
            width: 300px;
            height: auto;
        }
        .login-url {
            word-break: break-all;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login QR Code</h1>
        <p>Hello,</p>
        <p>You can log in to the ENV website using the following QR code:</p>
        <div class="qr-code">
            <img src="{{ $qrCodeUrl }}" alt="QR Code">
        </div>
        <p>Use this QR code to access your account:</p>
        <a href="{{ $loginUrl }}" class="login-url">{{ $loginUrl }}</a>
        <p>Best regards,<br>Your Team</p>
    </div>
</body>
</html>
