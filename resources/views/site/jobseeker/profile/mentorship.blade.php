{{-- Mentorship --}}
                        @php
                            $mentorships = \App\Models\BookingSession::with([
                                'mentor.reviews', 'mentor.profilePicture', 'mentor.experiences'
                            ])->where('jobseeker_id', auth()->user('jobseeker')->id)
                            ->where('user_type', 'mentor')
                            ->whereHas('mentor.profilePicture')
                            ->get();
                        @endphp


                        <div x-show="tab === 'mentorship'" x-cloak>
                            <h2 class="text-xl font-semibold mb-4">Mentorship</h2>
                            @foreach ($mentorships as $index => $session)
                                @php renderSessionCard($session->mentor, $session, $index, 'mentor'); @endphp
                            @endforeach
                        </div>