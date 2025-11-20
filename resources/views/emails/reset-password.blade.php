<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 30px;
            border: 1px solid #e0e0e0;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #ff69b4;
            margin: 0;
        }
        .content {
            background-color: white;
            padding: 25px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #ff69b4;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #ff1493;
        }
        .token-box {
            background-color: #f5f5f5;
            padding: 15px;
            border-left: 4px solid #ff69b4;
            margin: 15px 0;
            font-family: monospace;
            word-break: break-all;
        }
        .footer {
            text-align: center;
            color: #888;
            font-size: 12px;
            margin-top: 20px;
        }
        .warning {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🐹 PinkCapy</h1>
            <p>Password Reset Request</p>
        </div>

        <div class="content">
            <p>Hello <strong>{{ $userName }}</strong>,</p>

            <p>We received a request to reset your password. Click the button below to reset it:</p>

            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">Reset Password</a>
            </div>

            <p>Or copy and paste this link into your browser:</p>
            <div class="token-box">
                {{ $resetUrl }}
            </div>

            <p><strong>Your reset token:</strong></p>
            <div class="token-box">
                {{ $resetToken }}
            </div>

            <p class="warning">
                ⚠️ This link will expire in 60 minutes. If you didn't request a password reset, please ignore this email.
            </p>

            <p>For security reasons, never share this token with anyone.</p>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} PinkCapy. All rights reserved.</p>
            <p>This is an automated email. Please do not reply.</p>
        </div>
    </div>
</body>
</html>
