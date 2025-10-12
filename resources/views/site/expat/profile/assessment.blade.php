
{{-- Assessment --}}
@php
    $assessments = \App\Models\BookingSession::with([
        'assessor.reviews',
        'assessor.profilePicture',
        'assessor.experiences'
    ])->where('jobseeker_id', auth()->user('expat')->id)
        ->where('user_type', 'assessor')
        ->whereHas('assessor.profilePicture')
        ->get();
@endphp

<div x-show="tab === 'assessment'" x-cloak>
    <h2 class="text-xl font-semibold mb-4">Assessment</h2>
    @if ($assessments->isEmpty())
        <p class="text-gray-500 text-sm">No assessment sessions found.</p>
    @else
        @foreach ($assessments as $index => $session)
            @php renderSessionCard($session->assessor, $session, $index, 'assessor'); @endphp
        @endforeach
    @endif
</div>
