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
	
    <div class="page-wraper">
        <div class="flex h-screen" x-data="{ sidebarOpen: true }" x-init="$watch('sidebarOpen', () => feather.replace())">
            <!-- Sidebar -->
            @include('site.recruiter.componants.sidebar')	

            <div class="flex-1 flex flex-col">
                <nav class="bg-white shadow-md px-6 py-3 flex items-center justify-between">
                    <div class="flex items-center space-x-6 w-1/2">
                        <button 
                                @click="sidebarOpen = !sidebarOpen" 
                                class="text-gray-700 hover:text-blue-600 focus:outline-none"
                                title="Toggle Sidebar"
                                aria-label="Toggle Sidebar"
                                type="button"
                                >
                                <i data-feather="menu" class="w-6 h-6"></i>
                            </button>
                        <!-- <div class="relative w-full">
                            <input type="text" placeholder="Search for talent" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                <i class="fas fa-search"></i>
                            </button>
                        </div> -->
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                        <button aria-label="Notifications" class="text-gray-700 hover:text-blue-600 focus:outline-none relative">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-600 text-white">
                            <i class="feather-bell text-xl"></i>
                            </span>
                            <span class="absolute top-0 right-0 inline-block w-2.5 h-2.5 bg-red-600 rounded-full border-2 border-white"></span>
                        </button>
                        </div>
                        <div class="relative inline-block">
                        <select aria-label="Select Language" 
                                class="appearance-none border border-gray-300 rounded-md px-10 py-1 text-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="en" selected>English</option>
                            <option value="es">Spanish</option>
                            <option value="fr">French</option>
                            <!-- add more languages as needed -->
                        </select>
                        <span class="pointer-events-none absolute left-2 top-1/2 transform -translate-y-1/2 inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-600 text-white">
                            <i class="feather-globe"></i>
                        </span>
                        </div>
                    <div>
                        <a href="#" role="button"
                            class="inline-flex items-center space-x-1 border border-blue-600 bg-blue-600 text-white rounded-md px-3 py-1.5 transition">
                        <i class="fa fa-user-circle" aria-hidden="true"></i>
                            <span> Profile</span>
                        </a>
                    </div>
                    </div>
                </nav>

                <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="{ activeTab: 'personal' }">
                    <nav aria-label="breadcrumb" class="mb-6">
                        <ol class="flex text-2xl font-semibold">
                            <li>
                            <a href="{{route('recruiter.jobseeker')}}" class="text-blue-600 hover:underline">Jobseekers</a>
                            </li>
                            <li><span class="mx-2 text-black">></span></li>
                            <li>Jobseeker Details</li>
                        </ol>
                    </nav>
                    <div class="bg-white p-6 rounded shadow flex flex-col">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center space-x-4">
                            <img src="https://i.pravatar.cc/100?img=3" alt="Avatar" class="w-16 h-16 rounded-full object-cover" />
                            <div>
                            <h3 class="text-lg font-semibold">{{ $jobseeker->name}} </h3>
                            <p class="text-sm text-gray-500">{{ $jobseeker->experiences->pluck('job_role')->filter()->join(', ') ?: 'Not provided' }}</p>
                            </div>
                            
                        </div>
                        <!-- <button class="border border-blue-600 text-blue-600 text-sm px-4 py-1.5 rounded hover:bg-blue-50 transition" >
                            Request interview
                        </button> -->
                        @foreach ($shortlisted_jobseeker as $jobseeker)
                            @php
                                $jobseekerId = $jobseeker->id;
                                $isApproved = $jobseeker->shortlist_admin_status === 'approved';
                                $interviewRequested = strtolower($jobseeker->interview_request ?? '') === 'yes';
                            @endphp

                            <!-- Interview Request Button -->
                            <button
                                id="interview-btn-{{ $jobseekerId }}"
                                onclick="confirmInterviewRequest({{ $jobseekerId }}, {{ $isApproved ? 'true' : 'false' }}, {{ $interviewRequested ? 'true' : 'false' }})"
                                class="text-white text-base px-4 py-1.5 rounded
                                    {{ $isApproved 
                                        ? ($interviewRequested 
                                            ? 'bg-gray-400 cursor-not-allowed' 
                                            : 'bg-purple-500 hover:bg-purple-600') 
                                        : 'bg-gray-600 cursor-not-allowed' }}"
                                {{ ($interviewRequested || !$isApproved) ? 'disabled' : '' }}
                            >
                                {{ $interviewRequested ? 'Interview Requested' : 'Interview Request' }}
                            </button>

                        @endforeach

                        </div>
                        <hr>

                        <!-- Tabs -->
                        <div class="flex mb-4 text-sm font-medium mt-4 justify-between space-x-4">
                            <button
                                class="flex-1"
                                :class="activeTab === 'personal' ? 'text-blue-600 border-b-2 border-blue-600 pb-2' : 'text-gray-600 pb-2'"
                                @click="activeTab = 'personal'">Personal information</button>
                            
                            <button
                                class="flex-1"
                                :class="activeTab === 'education' ? 'text-blue-600 border-b-2 border-blue-600 pb-2' : 'text-gray-600 pb-2'"
                                @click="activeTab = 'education'">Educational details</button>
                            
                            <button
                                class="flex-1"
                                :class="activeTab === 'work' ? 'text-blue-600 border-b-2 border-blue-600 pb-2' : 'text-gray-600 pb-2'"
                                @click="activeTab = 'work'">Work experience</button>
                            
                            <button
                                class="flex-1"
                                :class="activeTab === 'skills' ? 'text-blue-600 border-b-2 border-blue-600 pb-2' : 'text-gray-600 pb-2'"
                                @click="activeTab = 'skills'">Skills and training</button>
                            
                            <button
                                class="flex-1"
                                :class="activeTab === 'additional' ? 'text-blue-600 border-b-2 border-blue-600 pb-2' : 'text-gray-600 pb-2'"
                                @click="activeTab = 'additional'">Additional information</button>
                        </div>


                        <!-- Tab Content -->
                        <div class="mt-6">
                            <!-- Personal Information -->
                            <div x-show="activeTab === 'personal'" x-transition class="space-y-6 text-sm">
                                <!-- Row 1: Gender, Birth date, Email, Phone -->
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                    <p><strong>Gender:</strong> {{ $jobseeker->gender}} </p>
                                    <p>
                                    <strong>Birth date:</strong> 
                                    <span style="filter: blur(3px);"> {{ $jobseeker->date_of_birth}} </span>
                                    </p>
                                    <p>
                                    <strong>Email address:</strong> 
                                    <span style="filter: blur(3px);">{{ $jobseeker->email }}</span>
                                    </p>
                                    <p>
                                    <strong>Phone number:</strong> 
                                    <span style="filter: blur(3px);">{{ $jobseeker->phone_number }}</span>
                                    </p>
                                </div>

                                <!-- Row 2: Address, City, Country + empty column -->
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                    <div>
                                        <p><strong>Address:</strong></p>
                                        <p class="text-gray-600">
                                            <span style="filter: blur(3px);">
                                                {{ $jobseeker->address }}
                                            </span>
                                        </p>
                                    </div>
                                    <p><strong>City:</strong> {{ $jobseeker->city }}</p>
                                    <!-- <p><strong>Country:</strong> Saudi Arabia</p> -->
                                    <div></div> <!-- empty 4th column -->
                                </div>
                            </div>


                            <!-- Educational details -->
                            <div x-show="activeTab === 'education'" x-transition class="text-sm text-gray-700 space-y-6">
                                @foreach($jobseeker->educations as $education)
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 border p-4 rounded-lg bg-gray-50">
                                        <div>
                                            <p><strong>Highest Qualification:</strong></p>
                                            <p>{{ $education->high_education }}</p>
                                        </div>
                                        <div>
                                            <p><strong>Field of Study:</strong></p>
                                            <p>{{ $education->field_of_study }}</p>
                                        </div>
                                        <div>
                                            <p><strong>Institution Name:</strong></p>
                                            <p>{{ $education->institution }}</p>
                                        </div>
                                        <div>
                                            <p><strong>Graduation Year:</strong></p>
                                            <p>{{ $education->graduate_year }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>



                            <!-- Work experience -->
                            <div x-show="activeTab === 'work'" x-transition class="text-sm text-gray-700 space-y-6">
                                @foreach($jobseeker->experiences as $experience)
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 border p-4 rounded-lg bg-gray-50">
                                        <div>
                                            <p><strong>Job Role:</strong></p>
                                            <p>{{ $experience->job_role }}</p>
                                        </div>
                                        <div>
                                            <p><strong>Organization:</strong></p>
                                            <p>{{ $experience->organization }}</p>
                                        </div>
                                        <div>
                                            <p><strong>Started From:</strong></p>
                                            <p>{{ $experience->starts_from }}</p>
                                        </div>
                                        <div>
                                            <p><strong>Ended In:</strong></p>
                                            <p>
                                                {{ $experience->end_to }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>



                            <!-- Skills and training -->
                            <div x-show="activeTab === 'skills'" x-transition class="text-sm text-gray-700 space-y-6">
                                <!-- First Row: 4 Columns -->
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                    <div>
                                        <p><strong>Skills:</strong></p>
                                        <p>{{  $skill->skills }}</p>
                                    </div>
                                    <div>
                                        <p><strong>Areas of Interest:</strong></p>
                                        <p>{{ $skill->interest ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p><strong>Job Categories:</strong></p>
                                        <p>{{ $skill->job_category ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p><strong>Website Link:</strong></p>
                                        @if(!empty($skill->website_link))
                                            <a href="{{ $skill->website_link }}" class="text-blue-600 hover:underline" target="_blank">
                                                {{ $skill->website_link }}
                                            </a>
                                        @else
                                            <p>-</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Second Row: Portfolio Link full width -->
                                <div class="grid grid-cols-1">
                                    <div>
                                        <p><strong>Portfolio Link:</strong></p>
                                        @if(!empty($skill->portfolio_link))
                                            <a href="{{ $skill->portfolio_link }}" class="text-blue-600 hover:underline" target="_blank">
                                                {{ $skill->portfolio_link }}
                                            </a>
                                        @else
                                            <p>-</p>
                                        @endif
                                    </div>
                                </div>
                            </div>




                            <!-- Additional information -->
                            <div x-show="activeTab === 'additional'" x-transition class="text-sm text-gray-700 space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                                    <!-- CV Name -->
                                    <div class="flex items-center space-x-2">
                                        <p class="font-semibold">CV Name:</p>
                                        <p class="text-gray-600">
                                            {{ $additional->document_name ?? 'N/A' }}
                                        </p>
                                    </div>

                                    <!-- Download Link -->
                                    <div class="flex items-center space-x-2">
                                        <p class="font-semibold">Download Candidate CV:</p>
                                        <a 
                                            href="https://example.com/cv-peter-parker.pdf" 
                                            class="text-blue-600 hover:underline"
                                            target="_blank"
                                            download
                                        >
                                            {{ $additional->document_name ?? 'Download CV' }}
                                        </a>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </main>

                <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
                <script src="https://cdn.tailwindcss.com"></script>

            </div>
        </div>
    </div>


<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .swal2-popup-sm {
        font-size: 15px;
        border-radius: 10px;
        padding: 1.2em;
    }

    .swal2-title-sm {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .swal2-text-sm {
        font-size: 15px;
        color: #333;
    }

    .swal2-confirm-sm,
    .swal2-cancel-sm {
        font-size: 14px !important;
        padding: 8px 20px !important;
        border-radius: 6px !important;
    }
</style>
<script>
    function confirmInterviewRequest(jobseekerId, isApproved, interviewRequested) {
        if (!isApproved || interviewRequested) return;

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to send interview request?",
            icon: 'question',
            width: '400px',
            padding: '1.2em',
            showCancelButton: true,
            confirmButtonColor: '#4CAF50',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, send it!',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'swal2-popup-sm',
                title: 'swal2-title-sm',
                htmlContainer: 'swal2-text-sm',
                confirmButton: 'swal2-confirm-sm',
                cancelButton: 'swal2-cancel-sm'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                sendInterviewRequest(jobseekerId);
            }
        });
    }

    function sendInterviewRequest(jobseekerId) {
        const btn = document.getElementById('interview-btn-' + jobseekerId);

        // Optimistically disable button
        btn.disabled = true;
        btn.classList.remove('bg-purple-500', 'hover:bg-purple-600');
        btn.classList.add('bg-gray-400', 'cursor-not-allowed');
        btn.innerText = 'Interview Requested';

        fetch("{{ route('recruiter.interview.request.submit') }}", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ jobseeker_id: jobseekerId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Interview request sent successfully.',
                    icon: 'success',
                    width: '400px',
                    customClass: {
                        popup: 'swal2-popup-sm',
                        title: 'swal2-title-sm',
                        htmlContainer: 'swal2-text-sm',
                        confirmButton: 'swal2-confirm-sm'
                    }
                });
            } else {
                handleRequestFailure(btn, data.message || 'Something went wrong.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            handleRequestFailure(btn, 'Failed to send request.');
        });
    }

    function handleRequestFailure(btn, errorMessage) {
        Swal.fire({
            title: 'Error!',
            text: errorMessage,
            icon: 'error',
            width: '400px',
            customClass: {
                popup: 'swal2-popup-sm',
                title: 'swal2-title-sm',
                htmlContainer: 'swal2-text-sm',
                confirmButton: 'swal2-confirm-sm'
            }
        });

        // Re-enable button if request failed
        btn.disabled = false;
        btn.classList.remove('bg-gray-400', 'cursor-not-allowed');
        btn.classList.add('bg-purple-500', 'hover:bg-purple-600');
        btn.innerText = 'Interview Request';
    }
</script>    

<script  src="js/jquery-3.6.0.min.js"></script><!-- JQUERY.MIN JS -->
<script  src="js/popper.min.js"></script><!-- POPPER.MIN JS -->
<script  src="js/bootstrap.min.js"></script><!-- BOOTSTRAP.MIN JS -->
<script  src="js/magnific-popup.min.js"></script><!-- MAGNIFIC-POPUP JS -->
<script  src="js/waypoints.min.js"></script><!-- WAYPOINTS JS -->
<script  src="js/counterup.min.js"></script><!-- COUNTERUP JS -->
<script  src="js/waypoints-sticky.min.js"></script><!-- STICKY HEADER -->
<script  src="js/isotope.pkgd.min.js"></script><!-- MASONRY  -->
<script  src="js/imagesloaded.pkgd.min.js"></script><!-- MASONRY  -->
<script  src="js/owl.carousel.min.js"></script><!-- OWL  SLIDER  -->
<script  src="js/theia-sticky-sidebar.js"></script><!-- STICKY SIDEBAR  -->
<script  src="js/lc_lightbox.lite.js" ></script><!-- IMAGE POPUP -->
<script  src="js/bootstrap-select.min.js"></script><!-- Form js -->
<script  src="js/dropzone.js"></script><!-- IMAGE UPLOAD  -->
<script  src="js/jquery.scrollbar.js"></script><!-- scroller -->
<script  src="js/bootstrap-datepicker.js"></script><!-- scroller -->
<script  src="js/jquery.dataTables.min.js"></script><!-- Datatable -->
<script  src="js/dataTables.bootstrap5.min.js"></script><!-- Datatable -->
<script  src="js/chart.js"></script><!-- Chart -->
<script  src="js/bootstrap-slider.min.js"></script><!-- Price range slider -->
<script  src="js/swiper-bundle.min.js"></script><!-- Swiper JS -->
<script  src="js/custom.js"></script><!-- CUSTOM FUCTIONS  -->
<script  src="js/switcher.js"></script><!-- SHORTCODE FUCTIONS  -->
<script src="https://unpkg.com/feather-icons"></script>
<script>
    feather.replace();
</script>

</body>


<!-- Mirrored from thewebmax.org/jobzilla/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 May 2025 07:18:30 GMT -->
</html>
