@php
    $brandColor = '#5f5fcd';
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - Admin | {{ config('app.name') }}</title>
</head>

<body style="background: #f8fafc; font-family: 'Segoe UI', 'Helvetica Neue', Arial, 'sans-serif'; margin:0; padding:0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background: #f8fafc; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0"
                    style="max-width: 480px; background: #fff; border-radius: 16px; box-shadow: 0 2px 16px rgba(95,95,205,0.08); padding: 32px; border: 1px solid #e5e7eb;">
                    <tr>
                        <td align="center" style="padding-bottom: 24px;">
                            <div
                                style="font-size: 28px; font-weight: bold; color: {{ $brandColor }}; letter-spacing: 1px; margin-bottom: 8px;">
                                {{ config('app.name') }}</div>
                            <div style="font-size: 16px; color: #2d5a27; font-weight: 500;">Admin Panel Password Reset
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 16px; color: #222; padding-bottom: 24px; text-align: center;">
                            You are receiving this email because we received a password reset request for your admin
                            account.<br><br>
                            <a href="{{ $resetUrl }}"
                                style="display:inline-block; background: {{ $brandColor }}; color: #fff; font-weight: 600; text-decoration: none; padding: 12px 32px; border-radius: 8px; margin: 16px 0; font-size: 16px;">Reset
                                Password</a>
                            <br>
                            <span style="font-size: 13px; color: #6b7280;">This password reset link will expire in
                                {{ $count }} minutes.</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 14px; color: #6b7280; text-align: center; padding-bottom: 8px;">
                            If you did not request a password reset, no further action is required.
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px; color: #b91c1c; text-align: center; padding-top: 16px;">
                            For security, never share your password with anyone.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>