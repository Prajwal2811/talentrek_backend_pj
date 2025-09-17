@foreach($jobseekers as $jobseeker)
<div class="jobseeker-entry flex justify-between items-center py-4">
    <!-- Profile Image & Name -->
    <div class="flex items-center space-x-4 w-1/3">
        <img 
            src="{{ $jobseeker->profile_image ?? 'https://i.pravatar.cc/100' }}" 
            class="w-12 h-12 rounded-full object-cover blur-sm" 
            alt="{{ $jobseeker->name }}"
        />
        <div>
            <h4 class="font-semibold text-sm blur-sm">{{ $jobseeker->name }}</h4>
            <p class="text-sm text-gray-500">
                {{ $jobseeker->experiences->pluck('job_role')->filter()->join(', ') ?: langLabel('not_provided') }}
            </p>
        </div>
    </div>

    <!-- Experience -->
    <div class="w-32 text-sm">
        <p class="font-semibold">{{ langLabel('experience') }}</p>
        <p>{{ $jobseeker->total_experience ?? '0 years' }}</p>
    </div>

    <!-- Skills -->
    <div class="text-sm flex-1">
        <p class="font-semibold">{{ langLabel('skills') }}</p>
        <p>
            {{ $jobseeker->skills->pluck('skills')->filter()->join(', ') ?: langLabel('not_provided') }}
        </p>
    </div>

    <!-- Action Buttons -->
    <div class="ml-4 flex space-x-2">
        @php
            $isShortlisted = isset($jobseeker->shortlist_admin_status);
            $isApproved = $jobseeker->shortlist_admin_status === 'approved';
            $interviewRequested = strtolower($jobseeker->interview_request ?? '') === 'yes';
            $jobseekerId = $jobseeker->id;
        @endphp

        {{-- If not shortlisted --}}
        @if(!$isShortlisted)
            <form id="shortlist-form-{{ $jobseekerId }}" action="{{ route('recruiter.shortlist.submit') }}" method="POST" onsubmit="return false;">
                @csrf
                <input type="hidden" name="jobseeker_id" value="{{ $jobseekerId }}">
                <button type="button" onclick="confirmShortlist({{ $jobseekerId }})"
                    class="bg-blue-600 text-white text-sm px-4 py-1.5 rounded hover:bg-blue-700">
                    {{ langLabel('shortlist') }}
                </button>
            </form>
        @else
            {{-- Approved or Pending --}}
            <button class="border text-xs px-2 py-1 rounded cursor-not-allowed 
                {{ $isApproved ? 'border-green-500 text-green-500' : 'border-red-500 text-red-500' }}" disabled>
                {{ $isApproved ? langLabel('approved') : langLabel('pending') }}
            </button>

            {{-- View Profile --}}
            <a href="{{ $isApproved ? route('recruiter.jobseeker.details', ['jobseeker_id' => $jobseekerId]) : '#' }}"
               class="text-white text-xs px-2 py-1 rounded inline-block 
               {{ $isApproved ? 'bg-blue-500 hover:bg-blue-600' : 'bg-gray-600 cursor-not-allowed' }}"
               {{ $isApproved ? '' : 'onclick=event.preventDefault()' }}>
                {{ langLabel('view_profile') }}
            </a>

            {{-- Interview Request --}}
            <button
                id="interview-btn-{{ $jobseekerId }}"
                onclick="confirmInterviewRequest({{ $jobseekerId }}, {{ $isApproved ? 'true' : 'false' }}, {{ $interviewRequested ? 'true' : 'false' }})"
                class="text-white text-xs px-2 py-1 rounded 
                    {{ $isApproved 
                        ? ($interviewRequested 
                            ? 'bg-gray-400 cursor-not-allowed' 
                            : 'bg-purple-500 hover:bg-purple-600') 
                        : 'bg-gray-600 cursor-not-allowed' }}"
                {{ ($interviewRequested || !$isApproved) ? 'disabled' : '' }}>
                {{ $interviewRequested ? langLabel('interview_requested') : langLabel('interview_request') }}
            </button>
        @endif
    </div>
</div>
@endforeach

@if($jobseekers->isEmpty())
<p class="p-4 text-gray-500">{{ langLabel('no_jobseekers_match') }}</p>
@endif
