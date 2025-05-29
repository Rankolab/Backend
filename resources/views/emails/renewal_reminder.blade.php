<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>License Expiry Notice</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background-color: white; padding: 20px; border-radius: 8px;">
        <h2 style="color: #f0ad4e;">License Renewal Reminder</h2>
        <p>{{ $messageText }}</p>
        <p>To continue enjoying Rankolab services without interruption, please renew your license:</p>
        <a href="{{ url('/user/licenses') }}" style="background-color: #007bff; color: white; padding: 10px 16px; text-decoration: none; border-radius: 5px;">Renew Now</a>
        <hr>
        <small>This is an automated reminder from Rankolab.</small>
    </div>
</body>
</html>
