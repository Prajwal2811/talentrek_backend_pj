@include('site.componants.header')

<body>

    <div class="loading-area">
        <div class="loading-box"></div>
        <div class="loading-pic">
            <div class="wrapper">
                <div class="cssload-loader"></div>
            </div>
        </div>
    </div>

    @if($recruiterNeedsSubscription)
        @include('site.recruiter.subscription.index')
    @endif
        @if($otherRecruiterSubscription)
        @include('site.recruiter.subscription.add-other-recruiters')
    @endif

    <div class="page-wraper">
        <div class="flex h-screen" x-data="{ sidebarOpen: true }" x-init="$watch('sidebarOpen', () => feather.replace())">
            <!-- Sidebar -->
            @include('site.recruiter.componants.sidebar')	
            <div class="flex-1 flex flex-col">
                @include('site.recruiter.componants.navbar')	
                <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="dashboard()">
                <h2 class="text-2xl font-semibold mb-6">Dashboard</h2>

                <!-- Stat Cards -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <p class="text-xl text-black-500">Jobseeker Shortlisted</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $totalShortlisted}}</h3>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <p class="text-xl text-black-500">Interviews scheduled</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $totalScheduled }}</h3>
                    </div>
                </div>


                @php
                    
                @endphp
                <!-- Jobseekers contacted -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-xl font-semibold mb-4">Scheduled Interviews</h3>
                     <hr class="border-t border-gray-300 mb-4">
                        @foreach($scheduled_jobseekers->unique('jobseeker_id') as $scheduled_jobseeker)
                            @php
                                $isApproved = $scheduled_jobseeker->shortlist_admin_status === 'superadmin_approved';
                                $jobseekerId = $scheduled_jobseeker->id;

                                $interviewDateTime = null;
                                if ($scheduled_jobseeker->interview_date && $scheduled_jobseeker->interview_time) {
                                    $interviewDateTime = Carbon\Carbon::parse(
                                        $scheduled_jobseeker->interview_date . ' ' . $scheduled_jobseeker->interview_time
                                    );
                                }

                                $status = strtolower($scheduled_jobseeker->interview_status ?? 'pending');
                                $statusLabel = ucfirst($status);
                                $statusClass = 'text-yellow-600';
                                $joinDisabled = true; // default: disabled

                                if ($status === 'completed') {
                                    $statusLabel = 'Completed';
                                    $statusClass = 'text-green-600 font-semibold';
                                } elseif ($status === 'cancelled') {
                                    $statusLabel = 'Cancelled';
                                    $statusClass = 'text-red-600 font-semibold';
                                } elseif ($status === 'scheduled' && $interviewDateTime) {
                                    // Expired after 1 hour of scheduled time
                                    if (now()->greaterThan($interviewDateTime->copy()->addHour())) {
                                        $statusLabel = 'Expired';
                                        $statusClass = 'text-red-600 font-semibold';
                                        $status = 'expired';
                                    }

                                    // Join button active only between -10min and +10min
                                    if (
                                        now()->between(
                                            $interviewDateTime->copy()->subMinutes(10),
                                            $interviewDateTime->copy()->addMinutes(10)
                                        )
                                    ) {
                                        $joinDisabled = false;
                                    }
                                }
                            @endphp

                            <div class="jobseeker-shortlisted flex justify-between items-center py-4">
                                <!-- Profile -->
                                <div class="flex items-center space-x-4 w-1/3">
                                    <img src="{{ $scheduled_jobseeker->profile_image ?? 'https://i.pravatar.cc/100' }}"
                                        class="w-12 h-12 rounded-full object-cover"
                                        alt="{{ $scheduled_jobseeker->name }}" />
                                    <div>
                                        <h4 class="font-semibold text-sm">{{ $scheduled_jobseeker->name }}</h4>
                                        <p class="text-sm text-gray-500">
                                            {{ $scheduled_jobseeker->experiences->pluck('job_role')->filter()->join(', ') ?: 'Not provided' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Interview Info -->
                                <div class="w-40 text-sm">
                                    <p class="font-semibold">Interview Date/Time</p>
                                    <p>
                                        @if($interviewDateTime)
                                            {{ $interviewDateTime->format('d M Y, h:i A') }}
                                        @else
                                            Not Scheduled
                                        @endif
                                    </p>
                                </div>

                                <!-- Status -->
                                <div class="w-32 text-sm">
                                    <p class="font-semibold">Interview Status</p>
                                    <p class="{{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </p>
                                </div>

                                <!-- Actions -->
                                <div class="ml-4 flex space-x-2 items-center">
                                    @if($status === 'completed')
                                        <!-- Show Interview Result -->
                                        <div class="w-32 text-sm mr-4">
                                            <p class="font-semibold">Interview Result</p>
                                            <p class="{{ $scheduled_jobseeker->interview_result === 'pass' ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold' }}">
                                                {{ $scheduled_jobseeker->interview_result ? ucfirst($scheduled_jobseeker->interview_result) : 'Not Provided' }}
                                            </p>
                                        </div>

                                        <!-- Share Feedback Button -->
                                        <button type="button"
                                                class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-2 py-1 rounded"
                                                data-bs-toggle="modal"
                                                data-bs-target="#feedbackModal-{{ $jobseekerId }}">
                                            Share Feedback
                                        </button>

                                        <!-- Feedback Modal -->
                                        <div class="modal fade" id="feedbackModal-{{ $jobseekerId }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    {{-- Modal Header --}}
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Share interview feedback</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    {{-- Modal Body --}}
                                                    <form action="{{ route('recruiter.feedback.reply') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="jobseeker_id" value="{{ $jobseekerId }}">

                                                        <div class="modal-body">
                                                            {{-- Feedback Textarea --}}
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Feedback</label>
                                                                <textarea name="feedback" rows="3" class="form-control" placeholder="Write here...">{{ old('feedback', $scheduled_jobseeker->feedback ?? '') }}</textarea>
                                                            </div>

                                                            {{-- Interview Result Dropdown --}}
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">Select interview status</label>
                                                                <select name="interview_result" class="form-select" required>
                                                                    <option value="">Select option</option>
                                                                    <option value="pass" @if($scheduled_jobseeker->interview_result === 'pass') selected @endif>Pass</option>
                                                                    <option value="fail" @if($scheduled_jobseeker->interview_result === 'fail') selected @endif>Fail</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        {{-- Modal Footer --}}
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    @elseif($status !== 'expired' && $status !== 'cancelled')
                                        <!-- Update Status Form -->
                                        <form action="{{ route('recruiter.interview.updateStatus') }}" method="POST" class="flex items-center space-x-2">
                                            @csrf
                                            <input type="hidden" name="jobseeker_id" value="{{ $jobseekerId }}">

                                            <select name="status" class="border rounded px-2 py-1 text-sm">
                                                <option value="" disabled>Update Status</option>
                                                <option value="cancelled" @if ($status === 'cancelled') selected @endif>Cancelled</option>
                                                <option value="scheduled" @if ($status === 'scheduled') selected @endif>Scheduled</option>
                                                <option value="completed"
                                                    @if ($status === 'completed') selected @endif
                                                    @if (!$interviewDateTime || now()->lessThan($interviewDateTime) || now()->greaterThan($interviewDateTime->copy()->addHour())) disabled @endif>
                                                    Completed
                                                </option>
                                            </select>

                                            <button type="submit" class="bg-gray-700 text-white text-xs px-2 py-1 rounded">
                                                Save
                                            </button>
                                        </form>

                                        <!-- Join Button -->
                                        <a href="{{ !$joinDisabled && $isApproved ? $scheduled_jobseeker->zoom_join_url : '#' }}"
                                        target="_blank"
                                        class="text-white text-xs px-2 py-1 rounded inline-block 
                                                {{ $joinDisabled || !$isApproved ? 'bg-gray-600 cursor-not-allowed' : 'bg-blue-500 hover:bg-blue-600' }}"
                                        {{ $joinDisabled || !$isApproved ? 'onclick=event.preventDefault()' : '' }}>
                                        Join
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach





                </div>
                </main>

            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
           <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>




            </div>
        </div>

        <!-- Feather Icons -->
        <script>
            feather.replace()
        </script>


    </div>
           



@include('site.recruiter.componants.footer')