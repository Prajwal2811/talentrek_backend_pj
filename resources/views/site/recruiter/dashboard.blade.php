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

    @if($recruiterNeedsSubscription && auth()->user('recruiter')->role != "sub_recruiter")
        @include('site.recruiter.subscription.index')
    @endif
     @if($otherRecruiterSubscription && auth()->user('recruiter')->role != "sub_recruiter")
        @include('site.recruiter.subscription.add-other-recruiters')
    @endif

    <div class="page-wraper">
        <div class="flex h-screen" x-data="{ sidebarOpen: true }" x-init="$watch('sidebarOpen', () => feather.replace())">
            <!-- Sidebar -->
            @include('site.recruiter.componants.sidebar')	
            <div class="flex-1 flex flex-col">
                @include('site.recruiter.componants.navbar')	
                <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="dashboard()">
                    <h2 class="text-2xl font-semibold mb-6">{{ langLabel('dashboard') }}</h2>

                    <!-- Stat Cards -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-white p-6 rounded-lg shadow">
                            <p class="text-xl text-black-500">{{ langLabel('jobseeker') }} {{ langLabel('shortlisted') }}</p>
                            <h3 class="text-3xl font-bold mt-2">{{ $totalShortlisted}}</h3>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow">
                            <p class="text-xl text-black-500">{{ langLabel('interviews_scheduled') }}</p>
                            <h3 class="text-3xl font-bold mt-2">{{ $totalScheduled }}</h3>
                        </div>
                    </div>


                    @php
                        
                    @endphp
                    <!-- Jobseekers contacted -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-xl font-semibold mb-4">{{ langLabel('scheduled_interviews') }}</h3>
                        <hr class="border-t border-gray-300 mb-4">
                            @foreach($scheduled_jobseekers->unique('jobseeker_id') as $scheduled_jobseeker)
                            @php
                                $isApproved = $scheduled_jobseeker->shortlist_admin_status === 'superadmin_approved';
                                $jobseekerId = $scheduled_jobseeker->jobseeker_id;

                                // --- INTERVIEW DATETIME ---
                                $interviewDateTime = null;
                                if ($scheduled_jobseeker->interview_date && $scheduled_jobseeker->interview_time) {
                                    $date = $scheduled_jobseeker->interview_date;
                                    $rawTime = trim($scheduled_jobseeker->interview_time);
                                    $normalizedTime = str_replace('.', ':', $rawTime);
                                    [$hour, $minute] = explode(':', $normalizedTime) + [0,0];
                                    $hour = (int)$hour;
                                    $minute = (int)$minute;
                                    if ($hour < 9) $hour += 12;
                                    $interviewDateTime = \Carbon\Carbon::createFromFormat(
                                        'Y-m-d H:i',
                                        sprintf('%s %02d:%02d', $date, $hour, $minute),
                                        'Asia/Kolkata'
                                    );
                                }

                                // --- STATUS LOGIC ---
                                $status = strtolower($scheduled_jobseeker->interview_status ?? 'pending');
                                $statusLabel = ucfirst($status);
                                $statusClass = 'text-yellow-600';
                                $joinDisabled = true;
                                $now = \Carbon\Carbon::now('Asia/Kolkata');

                                if ($status === 'completed') {
                                    $statusLabel = 'Completed';
                                    $statusClass = 'text-green-600 font-semibold';
                                } elseif ($status === 'cancelled') {
                                    $statusLabel = 'Cancelled';
                                    $statusClass = 'text-red-600 font-semibold';
                                } elseif ($status === 'scheduled' && $interviewDateTime) {
                                    if ($now->greaterThan($interviewDateTime->copy()->addHour())) {
                                        $statusLabel = 'Expired';
                                        $statusClass = 'text-red-600 font-semibold';
                                        $status = 'expired';
                                    }
                                    if ($now->between(
                                        $interviewDateTime->copy()->subMinutes(10),
                                        $interviewDateTime->copy()->addMinutes(10)
                                    )) {
                                        $joinDisabled = false;
                                    }
                                }
                            @endphp

                            <div class="jobseeker-shortlisted flex justify-between items-center py-4 border-b">
                                <!-- Profile -->
                                <div class="flex items-center space-x-4 w-1/3">
                                    <img src="{{ $scheduled_jobseeker->profile_image }}" class="w-12 h-12 rounded-full object-cover" alt="{{ $scheduled_jobseeker->name }}" />
                                    <div>
                                        <h4 class="font-semibold text-sm">{{ $scheduled_jobseeker->name }}</h4>
                                        <p class="text-sm text-gray-500">
                                            {{ $scheduled_jobseeker->experiences->pluck('job_role')->filter()->join(', ') ?: 'Not provided' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Interview Info -->
                                <div class="w-40 text-sm">
                                    <p class="font-semibold">{{ langLabel('interview_date_time') }}</p>
                                    <p>
                                        @if($interviewDateTime)
                                            {{ $interviewDateTime->format('d M Y, h:i A') }}
                                        @else
                                            {{ langLabel('not_scheduled') }}
                                        @endif
                                    </p>
                                </div>

                                <!-- Status -->
                                <div class="w-32 text-sm">
                                    <p class="font-semibold">{{ langLabel('interview_status') }}</p>
                                    <p class="{{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </p>
                                </div>

                                <!-- Actions -->
                                <div class="ml-4 flex space-x-2 items-center">
                                    @if($status === 'completed')
                                        <div class="w-32 text-sm mr-4">
                                            <p class="font-semibold">{{ langLabel('interview_result') }}</p>
                                            <p class="{{ $scheduled_jobseeker->interview_result === 'pass' ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold' }}">
                                                {{ $scheduled_jobseeker->interview_result ? ucfirst($scheduled_jobseeker->interview_result) : 'Not Provided' }}
                                            </p>
                                        </div>

                                        @php
                                            $isSubmitFeedback = App\Models\Feedback::where('sender_id', auth()->user('recruiter')->id)
                                                                ->where('to_id', $jobseekerId)
                                                                ->exists(); // returns true if record exists
                                        @endphp

                                        <!-- Feedback Modal Button -->
                                        @if(!$isSubmitFeedback)
                                            <button type="button"
                                                    class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-2 py-1 rounded"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#feedbackModal-{{ $jobseekerId }}">
                                                {{ langLabel('share_feedback') }}
                                            </button>
                                        @endif


                                        <div class="modal fade" id="feedbackModal-{{ $jobseekerId }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ langLabel('share_interview_feedback') }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form id="feedbackForm" action="{{ route('recruiter.feedback.reply') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="jobseeker_id" value="{{ $jobseekerId }}">
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">{{ langLabel('feedback') }} <span class="text-danger">*</span> </label>
                                                                <textarea name="feedback" rows="3" class="form-control" placeholder="{{ langLabel('write_here') }}...">{{ old('feedback', $scheduled_jobseeker->feedback ?? '') }}</textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label fw-semibold">{{ langLabel('select_interview_status') }} <span class="text-danger">*</span></label>
                                                                <select name="interview_result" class="form-select" required>
                                                                    <option value="">{{ langLabel('select_option') }}</option>
                                                                    <option value="pass" @if($scheduled_jobseeker->interview_result === 'pass') selected @endif>{{ langLabel('pass') }}</option>
                                                                    <option value="fail" @if($scheduled_jobseeker->interview_result === 'fail') selected @endif>{{ langLabel('fail') }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">{{ langLabel('save') }}</button>
                                                        </div>
                                                    </form>
                                                    <div id="feedbackMessage"></div> <!-- for AJAX success/error -->
                                                    <script>
                                                        $(document).ready(function() {
                                                            $('#feedbackForm').on('submit', function(e) {
                                                                e.preventDefault(); // prevent normal form submission

                                                                let form = $(this);
                                                                let url = "{{ route('recruiter.feedback.reply') }}";

                                                                $.ajax({
                                                                    type: "POST",
                                                                    url: url,
                                                                    data: form.serialize(),
                                                                    dataType: "json",
                                                                    beforeSend: function() {
                                                                        form.find('button[type="submit"]').prop('disabled', true).text('Saving...');
                                                                        $('#feedbackMessage').html('');
                                                                    },
                                                                    success: function(response) {
                                                                        // Show success message
                                                                        $('#feedbackMessage').html('<div class="alert alert-success">'+ response.message +'</div>');

                                                                        // Optional: wait 1.5 seconds before redirect
                                                                        setTimeout(function() {
                                                                            window.location.href = "{{ url()->previous() }}"; // redirect back
                                                                        }, 1500);

                                                                        form.find('button[type="submit"]').prop('disabled', false).text('{{ langLabel('save') }}');
                                                                    },
                                                                    error: function(xhr) {
                                                                        let errors = xhr.responseJSON.errors;
                                                                        let errorHtml = '<div class="alert alert-danger"><ul>';
                                                                        $.each(errors, function(key, value) {
                                                                            errorHtml += '<li>'+ value[0] +'</li>';
                                                                        });
                                                                        errorHtml += '</ul></div>';
                                                                        $('#feedbackMessage').html(errorHtml);
                                                                        form.find('button[type="submit"]').prop('disabled', false).text('{{ langLabel('save') }}');
                                                                    }
                                                                });
                                                            });
                                                        });
                                                        </script>


                                                </div>
                                            </div>
                                        </div>

                                    @elseif(!in_array($status, ['expired','cancelled']))
                                        <!-- AJAX Update Form -->
                                        <form class="update-status-form" data-jobseeker-id="{{ $jobseekerId }}" onsubmit="return false;">
                                            @csrf
                                            <select name="status" class="border rounded px-2 py-1 text-sm">
                                                <option value="" disabled selected>{{ langLabel('update_status') }}</option>
                                                <option value="scheduled" @if ($status === 'scheduled') selected @endif>{{ langLabel('scheduled') }}</option>
                                                <option value="completed" @if ($status === 'completed') selected @endif
                                                    @if (!$interviewDateTime || $now->lessThan($interviewDateTime) || $now->greaterThan($interviewDateTime->copy()->addHour())) disabled @endif>
                                                    {{ langLabel('completed') }}
                                                </option>
                                                <option value="cancelled" @if ($status === 'cancelled') selected @endif>{{ langLabel('cancelled') }}</option>
                                            </select>
                                            <button type="submit" class="bg-gray-700 text-white text-xs px-2 py-1 rounded">{{ langLabel('save') }}</button>
                                        </form>
                                        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
                                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                                        <script>
                                        $(document).ready(function() {
                                            $('.update-status-form').on('submit', function(e) {
                                                e.preventDefault();

                                                let form = $(this);
                                                let jobseekerId = form.data('jobseeker-id');
                                                let status = form.find('select[name="status"]').val();

                                                if (!status) {
                                                    Swal.fire({ icon: 'warning', title: 'Warning', text: 'Please select a status.' });
                                                    return;
                                                }

                                                $.ajax({
                                                    url: "{{ route('recruiter.interview.updateStatus') }}",
                                                    method: "POST",
                                                    data: {
                                                        _token: "{{ csrf_token() }}",
                                                        jobseeker_id: jobseekerId,
                                                        status: status
                                                    },
                                                    beforeSend: function() {
                                                        form.find('button').prop('disabled', true).text('Saving...');
                                                    },
                                                    success: function(response) {
                                                        form.find('button').prop('disabled', false).text('{{ langLabel('save') }}');
                                                        Swal.fire({
                                                            icon: 'success',
                                                            title: 'Updated!',
                                                            text: response.message || 'Interview status updated successfully.',
                                                            timer: 1500,
                                                            showConfirmButton: false
                                                        });

                                                        // Update status text without reload
                                                        let statusEl = form.closest('.jobseeker-shortlisted').find('[class*="status"]');
                                                        statusEl.text(status.charAt(0).toUpperCase() + status.slice(1));
                                                    },
                                                    error: function(xhr) {
                                                        form.find('button').prop('disabled', false).text('{{ langLabel('save') }}');
                                                        let msg = 'Something went wrong.';
                                                        if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                                                        Swal.fire({ icon: 'error', title: 'Error!', text: msg });
                                                    }
                                                });
                                            });
                                        });
                                        </script>

                                        <!-- Join Button -->
                                        <a href="{{ !$joinDisabled && $isApproved ? $scheduled_jobseeker->zoom_join_url : '#' }}"
                                            target="_blank"
                                            class="text-white text-xs px-2 py-1 rounded inline-block {{ $joinDisabled || !$isApproved ? 'bg-gray-600 cursor-not-allowed' : 'bg-blue-500 hover:bg-blue-600' }}"
                                            {{ $joinDisabled || !$isApproved ? 'onclick=event.preventDefault()' : '' }}>
                                            {{ langLabel('join') }}
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