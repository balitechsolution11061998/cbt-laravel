<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
</head>
<body>
    <p>Hello {{ $user->name }},</p>
    <p>Your password has been reset. Here is your new password:</p>
    <p><strong>{{ $newPassword }}</strong></p>
    <p>Please change your password after logging in for security reasons.</p>
    <p>Thank you.</p>
</body>
</html>
