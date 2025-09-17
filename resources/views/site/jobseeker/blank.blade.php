@include('site.componants.header')

<body>
    <!-- LOADING AREA START ===== -->
    <div class="loading-area">
        <div class="loading-box"></div>
        <div class="loading-pic">
            <div class="wrapper">
                <div class="cssload-loader"></div>
            </div>
        </div>
    </div>

@include('site.componants.navbar')
        


@include('site.jobseeker.componants.footer')





@php
                            $mentorships = \App\Models\BookingSession::with([
                                    'mentor.reviews',
                                    'mentor.profilePicture',
                                    'mentor.experiences'
                                ])
                                ->where('jobseeker_id', auth()->user('jobseeker')->id)
                                ->where('user_type', 'mentor')
                                ->whereHas('mentor.profilePicture')
                                ->get();
                        @endphp

                        <div x-show="tab === 'mentorship'" x-cloak>
                            <h2 class="text-xl font-semibold mb-4">{{ langLabel('mentorship') }}</h2>
                            @foreach ($mentorships as $index => $mentorship)
                                @php
                                    $mentor = $mentorship->mentor;
                                    $reviews = $mentor?->reviews ?? collect();
                                    $experiences = $mentor?->experiences ?? collect();

                                    $averageRating = $reviews->avg('ratings') ?? 0;
                                    $totalReviews = $reviews->count();
                                    $roundedRating = round($averageRating);
                                    $stars = str_repeat('★', $roundedRating) . str_repeat('☆', 5 - $roundedRating);

                                    $currentExp = $experiences->firstWhere('end_to', null) ??
                                                $experiences->sortByDesc('end_to')->first();
                                    $designation = $currentExp ? ($currentExp->job_role) : 'No designation available';

                                    $zoomLink = $mentorship->zoom_join_url ?? null;
                                @endphp

                                <div class="flex items-start border-b pb-4 mb-4 space-x-4">
                                    <!-- Mentor Profile Picture -->
                                    <img src="{{ $mentor?->profilePicture?->document_path }}" alt="Mentor" class="w-24 h-24 rounded-full object-cover">

                                    <div class="flex-1">
                                        <!-- Mentor Name + Designation + Join Button in a Row -->
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <a href="mentorship-details-profile.html">
                                                    <h3 class="text-lg font-semibold text-gray-900">{{ $mentor?->name }}</h3>
                                                </a>
                                                <p class="text-sm text-gray-500 mt-1">{{ $designation }}</p>

                                                <!-- Rating -->
                                                <div class="flex items-center mt-2 text-sm space-x-2">
                                                    <div class="text-yellow-500 text-base">
                                                        {{ $stars }}
                                                    </div>
                                                    <span class="text-gray-500">({{ number_format($averageRating, 1) }}/5 from {{ $totalReviews }} reviews)</span>
                                                </div>
                                            </div>

                                            <!-- Join Meet Button -->
                                            <!-- Join Meet or View Address Button -->
                                            <div class="ml-4 shrink-0">
                                                @if ($mentorship->slot_mode === 'online' && $zoomLink)
                                                    <button type="button"
                                                            class="btn btn-primary btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#zoomModal{{ $index }}">
                                                        {{ langLabel('join_meet') }}
                                                    </button>
                                                @elseif ($mentorship->slot_mode === 'offline')
                                                    <button type="button"
                                                            class="btn btn-outline-secondary btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#addressModal{{ $index }}">
                                                        {{ langLabel('view_address') }}
                                                    </button>
                                                @else
                                                    <p class="text-red-500 text-sm">{{ langLabel('link_not_available') }}</p>
                                                @endif
                                            </div>
                                            <!-- Address Modal for Offline Slot -->
                                            @if ($mentorship->slot_mode === 'offline')
                                                <div class="modal fade" id="addressModal{{ $index }}" tabindex="-1" aria-labelledby="addressModalLabel{{ $index }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="addressModalLabel{{ $index }}">{{ langLabel('mentors_address') }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p class="text-gray-800">
                                                                    {{ $mentor?->address ?? langLabel('address_not_available') }}
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ langLabel('close') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif


                                        </div>
                                    </div>
                                </div>

                                <!-- Zoom Modal -->
                                <div class="modal fade" id="zoomModal{{ $index }}" tabindex="-1" aria-labelledby="zoomModalLabel{{ $index }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="zoomModalLabel{{ $index }}">{{ langLabel('join_zoom_meeting_with') }} {{ $mentor?->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{ langLabel('click_the_link_below') }}</p>
                                                <a href="{{ $zoomLink }}" target="_blank" class="text-blue-600 font-medium underline break-all">
                                                    {{ $zoomLink }}
                                                </a>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ langLabel('close') }}</button>
                                                <a href="{{ $zoomLink }}" target="_blank" class="btn btn-primary btn-sm">{{ langLabel('join_now') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>


                        {{-- Assessor --}}
                        @php
                            $assessments = \App\Models\BookingSession::with([
                                    'assessor.reviews',
                                    'assessor.profilePicture',
                                    'assessor.experiences'
                                ])
                                ->where('jobseeker_id', auth()->user('jobseeker')->id)
                                ->where('user_type', 'assessor') // only assessor type sessions
                                ->whereHas('assessor.profilePicture')
                                ->get();
                        @endphp

                        <div x-show="tab === 'assessment'" x-cloak>
                            <h2 class="text-xl font-semibold mb-4">{{ langLabel('assessor') }}</h2>

                            @if ($assessments->isEmpty())
                                <div class="text-gray-500 text-sm">
                                    {{ langLabel('no_assessment_session_found') }}
                                </div>
                            @else
                                @foreach ($assessments as $index => $assessment)
                                    @php
                                        $assessor = $assessment->assessor;
                                        $reviews = $assessor?->reviews ?? collect();
                                        $experiences = $assessor?->experiences ?? collect();

                                        $averageRating = $reviews->avg('ratings') ?? 0;
                                        $totalReviews = $reviews->count();
                                        $roundedRating = round($averageRating);
                                        $stars = str_repeat('★', $roundedRating) . str_repeat('☆', 5 - $roundedRating);

                                        $currentExp = $experiences->firstWhere('end_to', null) ??
                                                    $experiences->sortByDesc('end_to')->first();
                                        $designation = $currentExp ? ($currentExp->job_role) : 'No designation available';

                                        $zoomLink = $assessment->zoom_join_url ?? null;
                                    @endphp

                                    <!-- Existing card and modal code... -->
                                    <div class="flex items-start border-b pb-4 mb-4 space-x-4">
                                        <img src="{{ $assessor?->profilePicture?->document_path }}" alt="Assessor" class="w-24 h-24 rounded-full object-cover">

                                        <div class="flex-1">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <a href="#">
                                                        <h3 class="text-lg font-semibold text-gray-900">{{ $assessor?->name }}</h3>
                                                    </a>
                                                    <p class="text-sm text-gray-500 mt-1">{{ $designation }}</p>

                                                    <div class="flex items-center mt-2 text-sm space-x-2">
                                                        <div class="text-yellow-500 text-base">
                                                            {{ $stars }}
                                                        </div>
                                                        <span class="text-gray-500">({{ number_format($averageRating, 1) }}/5 from {{ $totalReviews }} reviews)</span>
                                                    </div>
                                                </div>

                                                <div class="ml-4 shrink-0">
                                                    @if ($assessment->slot_mode === 'online' && $zoomLink)
                                                        <button type="button"
                                                                class="btn btn-primary btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#zoomModal{{ $index }}">
                                                            {{ langLabel('join_meet') }}
                                                        </button>
                                                    @elseif ($assessment->slot_mode === 'offline')
                                                        <button type="button"
                                                                class="btn btn-outline-secondary btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#addressModal{{ $index }}">
                                                            {{ langLabel('view_address') }}
                                                        </button>
                                                    @else
                                                        <p class="text-red-500 text-sm">{{ langLabel('link_not_available') }}</p>
                                                    @endif
                                                </div>
                                                <!-- Address Modal for Offline Slot -->
                                                @if ($assessment->slot_mode === 'offline')
                                                    <div class="modal fade" id="addressModal{{ $index }}" tabindex="-1" aria-labelledby="addressModalLabel{{ $index }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="addressModalLabel{{ $index }}">{{ langLabel('assessors_address') }}</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p class="text-gray-800">
                                                                        {{ $assessor?->address ?? langLabel('address_not_available') }}
                                                                    </p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ langLabel('close') }}</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Zoom Modal -->
                                    <div class="modal fade" id="zoomModal{{ $index }}" tabindex="-1" aria-labelledby="zoomModalLabel{{ $index }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="zoomModalLabel{{ $index }}">{{ langLabel('join_zoom_meeting_with') }} {{ $assessor?->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>{{ langLabel('click_the_link_below') }}</p>
                                                    <a href="{{ $zoomLink }}" target="_blank" class="text-blue-600 font-medium underline break-all">
                                                        {{ $zoomLink }}
                                                    </a>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ langLabel('close') }}</button>
                                                    <a href="{{ $zoomLink }}" target="_blank" class="btn btn-primary btn-sm">{{ langLabel('join_now') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                    <!-- Coaching Tab -->
                    @php
                        $coachings = \App\Models\BookingSession::with([
                                'coach.reviews',
                                'coach.profilePicture',
                                'coach.experiences'
                            ])
                            ->where('jobseeker_id', auth()->user('jobseeker')->id)
                            ->where('user_type', 'coach') // only coach type sessions
                            ->whereHas('coach.profilePicture')
                            ->get();
                    @endphp

                    <div x-show="tab === 'coaching'" x-cloak>
                        <h2 class="text-xl font-semibold mb-4">{{ langLabel('coaching') }}</h2>

                        @if ($coachings->isEmpty())
                            <div class="text-gray-500 text-sm">
                                {{ langLabel('no_coaching_session_found') }}
                            </div>
                        @else
                            @foreach ($coachings as $index => $coaching)
                                @php
                                    $coach = $coaching->coach;
                                    $reviews = $coach?->reviews ?? collect();
                                    $experiences = $coach?->experiences ?? collect();

                                    $averageRating = $reviews->avg('ratings') ?? 0;
                                    $totalReviews = $reviews->count();
                                    $roundedRating = round($averageRating);
                                    $stars = str_repeat('★', $roundedRating) . str_repeat('☆', 5 - $roundedRating);

                                    $currentExp = $experiences->firstWhere('end_to', null) ??
                                                $experiences->sortByDesc('end_to')->first();
                                    $designation = $currentExp ? ($currentExp->job_role) : langLabel('no_destination_available');

                                    $zoomLink = $coaching->zoom_join_url ?? null;
                                @endphp

                                <div class="flex items-start border-b pb-4 mb-4 space-x-4">
                                    <img src="{{ $coach?->profilePicture?->document_path }}" alt="Coach" class="w-24 h-24 rounded-full object-cover">

                                    <div class="flex-1">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <a href="#">
                                                    <h3 class="text-lg font-semibold text-gray-900">{{ $coach?->name }}</h3>
                                                </a>
                                                <p class="text-sm text-gray-500 mt-1">{{ $designation }}</p>

                                                <div class="flex items-center mt-2 text-sm space-x-2">
                                                    <div class="text-yellow-500 text-base">
                                                        {{ $stars }}
                                                    </div>
                                                    <span class="text-gray-500">({{ number_format($averageRating, 1) }}/5 from {{ $totalReviews }} reviews)</span>
                                                </div>
                                            </div>

                                            <div class="ml-4 shrink-0">
                                                @if ($coaching->slot_mode === 'online' && $zoomLink)
                                                    <button type="button"
                                                            class="btn btn-primary btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#zoomModal{{ $index }}">
                                                        {{ langLabel('join_meet') }}
                                                    </button>
                                                @elseif ($coaching->slot_mode === 'offline')
                                                    <button type="button"
                                                            class="btn btn-outline-secondary btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#addressModal{{ $index }}">
                                                        {{ langLabel('view_address') }}
                                                    </button>
                                                @else
                                                    <p class="text-red-500 text-sm">{{ langLabel('link_not_available') }}</p>
                                                @endif
                                            </div>
                                            <!-- Address Modal for Offline Slot -->
                                            @if ($coaching->slot_mode === 'offline')
                                                <div class="modal fade" id="addressModal{{ $index }}" tabindex="-1" aria-labelledby="addressModalLabel{{ $index }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="addressModalLabel{{ $index }}">{{ langLabel('coach_address') }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p class="text-gray-800">
                                                                    {{ $coach?->address ?? langLabel('address_not_available') }}
                                                                </p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ langLabel('close') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Zoom Modal -->
                                <div class="modal fade" id="zoomModal{{ $index }}" tabindex="-1" aria-labelledby="zoomModalLabel{{ $index }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="zoomModalLabel{{ $index }}">{{ langLabel('join_zoom_meeting_with') }} {{ $coach?->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>{{ langLabel('click_the_link_below') }}</p>
                                                <a href="{{ $zoomLink }}" target="_blank" class="text-blue-600 font-medium underline break-all">
                                                    {{ $zoomLink }}
                                                </a>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ langLabel('close') }}</button>
                                                <a href="{{ $zoomLink }}" target="_blank" class="btn btn-primary btn-sm">{{ langLabel('join_now') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>