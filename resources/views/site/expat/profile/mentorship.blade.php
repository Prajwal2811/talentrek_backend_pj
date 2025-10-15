
@php
    $mentorships = \App\Models\BookingSession::with([
        'mentor.reviews',
        'mentor.profilePicture',
        'mentor.experiences'
    ])->where('jobseeker_id', auth()->user('expat')->id)
        ->where('user_type', 'mentor')
        ->whereHas('mentor.profilePicture')
        ->get();
@endphp


<div x-show="tab === 'mentorship'" x-cloak>
    <h2 class="text-xl font-semibold mb-4">Mentorship</h2>
    @if ($mentorships->isEmpty())
        <p class="text-gray-500 text-sm">No mentorship sessions found.</p>
    @else
        @foreach ($mentorships as $index => $session)
            @php renderSessionCard($session->mentor, $session, $index, 'mentor'); @endphp
        @endforeach
    @endif

</div>
