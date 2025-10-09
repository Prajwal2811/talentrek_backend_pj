{{-- Coaching --}}
                        @php
                            $coachings = \App\Models\BookingSession::with([
                                'coach.reviews', 'coach.profilePicture', 'coach.experiences'
                            ])->where('jobseeker_id', auth()->user('jobseeker')->id)
                            ->where('user_type', 'coach')
                            ->whereHas('coach.profilePicture')
                            ->get();
                        @endphp

                        <div x-show="tab === 'coaching'" x-cloak>
                            <h2 class="text-xl font-semibold mb-4">Coaching</h2>
                            @if ($coachings->isEmpty())
                                <p class="text-gray-500 text-sm">No coaching sessions found.</p>
                            @else
                                @foreach ($coachings as $index => $session)
                                    @php renderSessionCard($session->coach, $session, $index, 'coach'); @endphp
                                @endforeach
                            @endif
                        </div>