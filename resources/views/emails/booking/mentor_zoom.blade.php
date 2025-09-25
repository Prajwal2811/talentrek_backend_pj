<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Zoom Meeting Link</title>
</head>
<body>
    <p>Hi {{ $booking->user->name ?? 'Mentor' }},</p>

    <p>A new consultation session has been booked.</p>

    <p><strong>Session Details:</strong></p>
    <ul>
        <li>Jobseeker: {{ $booking->jobseeker->name }}</li>
        <li>Date: {{ \Carbon\Carbon::parse($booking->slot_date)->format('d M Y') }}</li>
        <li>Time: {{ $booking->slot_time }}</li>
        <li>Mode: {{ ucfirst($booking->slot_mode) }}</li>
    </ul>

    @if($booking->slot_mode === 'online' && $booking->zoom_start_url)
        <p><strong>Start Zoom Meeting:</strong> <a href="{{ $booking->zoom_start_url }}">{{ $booking->zoom_start_url }}</a></p>
        <p><strong>Join Zoom Meeting (for Jobseeker):</strong> <a href="{{ $booking->zoom_join_url }}">{{ $booking->zoom_join_url }}</a></p>
    @endif

    @if($booking->slot_mode === 'offline' && $booking->offline_address)
        <p><strong>Offline Address:</strong> {{ $booking->offline_address }}</p>
    @endif

    <p>Thank you!</p>
</body>
</html>
