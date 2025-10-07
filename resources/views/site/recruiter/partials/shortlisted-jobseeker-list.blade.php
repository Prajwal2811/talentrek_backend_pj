@foreach($shortlisted_jobseekers->unique('jobseeker_id') as $shortlisted_jobseeker)
    <div class="jobseeker-shortlisted  flex justify-between items-center py-4">
        <!-- Profile Image & Name -->
        <div class="flex items-center space-x-4 w-1/3">
            <img 
                src="{{ $shortlisted_jobseeker->profile_image }}" 
                class="w-12 h-12 rounded-full object-cover blur-sm" 
                alt="{{ $shortlisted_jobseeker->name }}"
            />
            <div>
                <h4 class="font-semibold text-sm blur-sm">{{ $shortlisted_jobseeker->name }}</h4>
                <p class="text-sm text-gray-500">
                    {{ $shortlisted_jobseeker->experiences->pluck('job_role')->filter()->join(', ') ?: langLabel('not_provided') }}
                </p>
            </div>
        </div>

        <!-- Experience Years -->
        <div class="w-32 text-sm">
            <p class="font-semibold">{{ langLabel('experience') }}</p>
            <p>{{ $shortlisted_jobseeker->total_experience }}</p>
        </div>

        <!-- Skills -->
        <div class="text-sm flex-1">
            <p class="font-semibold">{{ langLabel('skills') }}</p>
            <p>
                @if($shortlisted_jobseeker->skills && $shortlisted_jobseeker->skills->count())
                    {{ $shortlisted_jobseeker->skills->pluck('skills')->filter()->join(', ') }}
                @else
                    {{ langLabel('not_provided') }}
                @endif
            </p>
        </div>

        <!-- Shortlist Button -->
        <div class="ml-4 flex space-x-2">
            @php
                $isApproved = $shortlisted_jobseeker->shortlist_admin_status === 'superadmin_approved';
                $interviewRequested = strtolower($shortlisted_jobseeker->interview_request ?? '') === 'yes';
                $jobseekerId = $shortlisted_jobseeker->jobseeker_id;
            @endphp

            <!-- Status Label -->
            <span class="border text-xs px-2 py-1 rounded 
                        {{ $isApproved ? 'border-green-500 text-green-500' : 'border-red-500 text-red-500' }}">
                {{ $isApproved ? langLabel('approved') : langLabel('pending') }}
            </span>


            <!-- View Profile -->
            <a href="{{ $isApproved ? route('recruiter.jobseeker.details', ['jobseeker_id' => $jobseekerId]) : '#' }}"
            class="text-white text-xs px-2 py-1 rounded inline-block
            {{ $isApproved ? 'bg-blue-500 hover:bg-blue-600' : 'bg-gray-600 cursor-not-allowed' }}"
            {{ $isApproved ? '' : 'onclick=event.preventDefault()' }}>
                {{ langLabel('view_profile') }}
            </a>

            <!-- Interview Request Button -->
            {{-- @if ($interviewRequested || !$isApproved)
                <span class="inline-block text-white text-xs px-2 py-1 rounded 
                            {{ $interviewRequested ? 'bg-gray-400' : 'bg-gray-600' }}">
                    {{ $interviewRequested ? langLabel('interview_requested') : langLabel('not_approved') }}
                </span>
            @else
                <button
                    id="interview-btn-{{ $jobseekerId }}"
                    onclick="confirmInterviewRequest({{ $jobseekerId }}, true, false)"
                    class="text-white text-xs px-2 py-1 rounded bg-purple-500 hover:bg-purple-600"
                >
                    {{ langLabel('interview_request') }}
                </button>
            @endif --}}

        </div>
    </div>
@endforeach