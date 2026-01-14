<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Zoom Meeting Link</title>
</head>
<body>
    <p>Hi {{ $booking->jobseeker->name }},</p>

    <p>Your session has been confirmed successfully.</p>

    <p><strong>Session Details:</strong></p>
    <ul>
        <li>Date: {{ \Carbon\Carbon::parse($booking->slot_date)->format('d M Y') }}</li>
        <li>Time: {{ $booking->slot_time }}</li>
        <li>Mode: {{ ucfirst($booking->slot_mode) }}</li>
    </ul>

    @if($booking->slot_mode === 'online' && $booking->zoom_join_url)
        <p><strong>Join Zoom Meeting:</strong> <a href="{{ $booking->zoom_join_url }}">{{ $booking->zoom_join_url }}</a></p>
    @endif

    <p>Thank you for booking with us!</p>
</body>
</html>
mentor_zoom.blade.php