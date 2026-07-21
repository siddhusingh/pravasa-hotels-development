<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Your Password</title>
</head>
<body style="margin:0;background:#f4f5fb;font-family:Arial,sans-serif;color:#17213d;">
    <div style="max-width:620px;margin:0 auto;padding:32px 16px;">
        <div style="background:#ffffff;border-radius:12px;padding:32px;border:1px solid #e7e9f2;">
            <h1 style="margin:0 0 20px;font-size:24px;">Reset your password</h1>
            <p style="line-height:1.6;">Hello <?= html_escape($name ?: 'Super Admin') ?>,</p>
            <p style="line-height:1.6;">We received a request to reset the password for your Super Admin account.</p>
            <p style="margin:28px 0;">
                <a href="<?= html_escape($reset_url) ?>" style="display:inline-block;background:#9575e8;color:#ffffff;text-decoration:none;padding:12px 22px;border-radius:6px;font-weight:bold;">Reset Password</a>
            </p>
            <p style="line-height:1.6;">This link expires in one hour. If you did not request a password reset, you can ignore this email.</p>
        </div>
    </div>
</body>
</html>
