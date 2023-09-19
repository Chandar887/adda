<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f4f4f4">
        <tr>
            <td>
                <table align="center" cellpadding="0" cellspacing="0" border="0" width="600"
                    style="border-collapse: collapse; background-color: #ffffff; margin: 20px auto;">
                    <tr>
                        <td style="text-align: center; padding: 20px;">
                            <img src="https://example.com/logo.png" alt="Company Logo" width="150">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px; font-size: 16px; line-height: 1.6; text-align: left;">
                            <p>Dear {{ $userName }},</p>
                            <p>We received a request to reset your password. To proceed, use the token below:</p>
                            <p style="text-align: center;">
                                {{$token}}
                            </p>
                            <p>If you did not request this password reset, please ignore this email, and your password
                                will remain unchanged.</p>
                            <p>Thank you for using our services.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px; font-size: 12px; color: #999999; text-align: center;">
                            &copy; {{ date('Y') }} Company Name. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>