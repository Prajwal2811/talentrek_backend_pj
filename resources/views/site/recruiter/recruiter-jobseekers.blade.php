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

                <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="">
                    <h2 class="text-2xl font-semibold mb-6">Jobseekers</h2>
                    <div class="flex space-x-6">

                        <!-- Sidebar Filters -->
                        <div class="bg-white p-4 rounded shadow w-64 space-y-4">
                            <h2 class="font-semibold mb-2">Filters</h2>

                            <!-- Candidates experience -->
                            <div>
                                <p class="font-semibold text-sm mb-1">Candidates experience</p>
                                <label class="block text-sm"><input type="checkbox" name="experience[]" value="fresher" class="mr-2 filter-checkbox" />Fresher (0-3 years)</label>
                                <label class="block text-sm"><input type="checkbox" name="experience[]" value="experienced" class="mr-2 filter-checkbox" />3+ years</label>
                                <label class="block text-sm"><input type="checkbox" name="experience[]" value="all" class="mr-2 filter-checkbox" />All</label>
                            </div>

                            @php $educations = \App\Models\EducationDetails::all(); @endphp

                            <!-- Education -->
                            <div>
                                <p class="font-semibold text-sm mb-1">Education</p>
                                @foreach ($educations->unique('high_education') as $education)
                                    <label class="block text-sm">
                                        <input type="checkbox" name="education[]" value="{{ $education->high_education }}" class="mr-2 filter-checkbox" />{{ $education->high_education }}
                                    </label>
                                @endforeach
                            </div>

                            <!-- Gender -->
                            <div>
                                <p class="font-semibold text-sm mb-1">Gender</p>
                                <label class="block text-sm"><input type="checkbox" name="gender[]" value="male" class="mr-2 filter-checkbox" />Male</label>
                                <label class="block text-sm"><input type="checkbox" name="gender[]" value="female" class="mr-2 filter-checkbox" />Female</label>
                                <label class="block text-sm"><input type="checkbox" name="gender[]" value="all" class="mr-2 filter-checkbox" />All</label>
                            </div>

                            <!-- Certificate -->
                            <div>
                                <p class="font-semibold text-sm mb-1">Certificate</p>
                                <label class="block text-sm"><input type="checkbox" name="certificate[]" value="0-5" class="mr-2 filter-checkbox" />Certificate (0-5)</label>
                                <label class="block text-sm"><input type="checkbox" name="certificate[]" value="5+" class="mr-2 filter-checkbox" />Certificate 5+</label>
                                <label class="block text-sm"><input type="checkbox" name="certificate[]" value="not-certified" class="mr-2 filter-checkbox" />Not certified</label>
                                <label class="block text-sm"><input type="checkbox" name="certificate[]" value="all" class="mr-2 filter-checkbox" />All</label>
                            </div>
                        </div>

                        <!-- Main Panel -->
                        <div class="flex-1 bg-white p-4 rounded shadow">
                            <!-- Tabs -->
                            <!-- Tabs -->
                            <div class="flex justify-between border-b pb-2 mb-4">
                                <div class="space-x-6 font-medium text-sm">
                                    <button data-tab="jobseekers" class="tab-btn pb-1 border-b-2 text-black">Jobseekers</button>
                                    <button data-tab="shortlisted" class="tab-btn pb-1 text-gray-500">Shortlisted</button>

                                </div>
                            </div>

                           
                            <!-- Jobseekers Tab -->
                            <div id="jobseekerList" data-tab-content="jobseekers" class="divide-y">
                                @include('site.recruiter.partials.jobseeker-list', ['jobseekers' => $jobseekers])
                                <div id="pagination" class="mt-6 flex justify-center space-x-2"></div>
                            </div>
                            
                            <!-- Shortlisted Tab -->
                            <div id="shortlistedList" data-tab-content="shortlisted" class="divide-y hidden">
                                @foreach($shortlisted_jobseekers as $shortlisted_jobseeker)
                                        <div class="jobseeker-shortlisted  flex justify-between items-center py-4">
                                            <!-- Profile Image & Name -->
                                            <div class="flex items-center space-x-4 w-1/3">
                                                <img 
                                                    src="{{ $shortlisted_jobseeker->profile_image ?? 'https://i.pravatar.cc/100' }}" 
                                                    class="w-12 h-12 rounded-full object-cover blur-sm" 
                                                    alt="{{ $shortlisted_jobseeker->name }}"
                                                />
                                                <div>
                                                    <h4 class="font-semibold text-sm blur-sm">{{ $shortlisted_jobseeker->name }}</h4>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $shortlisted_jobseeker->experiences->pluck('job_role')->filter()->join(', ') ?: 'Not provided' }}
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Experience Years -->
                                            <div class="w-32 text-sm">
                                                <p class="font-semibold">Experience</p>
                                                <p>{{ $shortlisted_jobseeker->total_experience }}</p>
                                            </div>

                                            <!-- Skills -->
                                            <div class="text-sm flex-1">
                                                <p class="font-semibold">Skills</p>
                                                <p>
                                                    @if($shortlisted_jobseeker->skills && $shortlisted_jobseeker->skills->count())
                                                        {{ $shortlisted_jobseeker->skills->pluck('skills')->filter()->join(', ') }}
                                                    @else
                                                        Not provided
                                                    @endif
                                                </p>
                                            </div>

                                            <!-- Shortlist Button -->
                                            <div class="ml-4 flex space-x-2">
                                                @php
                                                    $isApproved = $shortlisted_jobseeker->shortlist_admin_status === 'approved';
                                                    $interviewRequested = strtolower($shortlisted_jobseeker->interview_request ?? '') === 'yes';
                                                    $jobseekerId = $shortlisted_jobseeker->id;
                                                @endphp

                                                <!-- Status Label -->
                                                <button class="border text-xs px-2 py-1 rounded cursor-not-allowed
                                                    {{ $isApproved ? 'border-green-500 text-green-500' : 'border-red-500 text-red-500' }}" disabled>
                                                    {{ $isApproved ? 'Approved' : 'Pending' }}
                                                </button>

                                                <!-- View Profile -->
                                                <a href="{{ $isApproved ? route('recruiter.jobseeker.details', ['jobseeker_id' => $jobseekerId]) : '#' }}"
                                                class="text-white text-xs px-2 py-1 rounded inline-block
                                                {{ $isApproved ? 'bg-blue-500 hover:bg-blue-600' : 'bg-gray-600 cursor-not-allowed' }}"
                                                {{ $isApproved ? '' : 'onclick=event.preventDefault()' }}>
                                                    View Profile
                                                </a>

                                                <!-- Interview Request Button -->
                                                <button
                                                    id="interview-btn-{{ $jobseekerId }}"
                                                    onclick="confirmInterviewRequest({{ $jobseekerId }}, {{ $isApproved ? 'true' : 'false' }}, {{ $interviewRequested ? 'true' : 'false' }})"
                                                    class="text-white text-xs px-2 py-1 rounded
                                                        {{ $isApproved 
                                                            ? ($interviewRequested 
                                                                ? 'bg-gray-400 cursor-not-allowed' 
                                                                : 'bg-purple-500 hover:bg-purple-600') 
                                                            : 'bg-gray-600 cursor-not-allowed' }}"
                                                    {{ ($interviewRequested || !$isApproved) ? 'disabled' : '' }}
                                                >
                                                    {{ $interviewRequested ? 'Interview Requested' : 'Interview Request' }}
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @include('site.recruiter.partials.jobseeker-list', ['jobseekers' => $shortlisted_jobseekers])
                                <div id="shortlistedPagination" class="mt-6 flex justify-center space-x-2"></div>
                            </div>

                            <!-- Contacted Tab -->
                            <div data-tab-content="contacted" style="display: none;">
                                <div class="text-center py-6 text-gray-500">No contacted jobseekers.</div>
                            </div>

                        </div>
                    </div>

                    <!-- jQuery for Filters -->
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const tabButtons = document.querySelectorAll('.tab-btn');
                            const tabContents = document.querySelectorAll('[data-tab-content]');

                            // Show default tab on load
                            let defaultTab = 'jobseekers';
                            tabContents.forEach(content => {
                                if (content.getAttribute('data-tab-content') === defaultTab) {
                                    content.classList.remove('hidden');
                                } else {
                                    content.classList.add('hidden');
                                }
                            });

                            tabButtons.forEach(button => {
                                button.addEventListener('click', () => {
                                    const tab = button.getAttribute('data-tab');

                                    // Update button styles
                                    tabButtons.forEach(btn => {
                                        btn.classList.remove('border-b-2', 'text-black');
                                        btn.classList.add('text-gray-500');
                                    });
                                    button.classList.add('border-b-2', 'text-black');
                                    button.classList.remove('text-gray-500');

                                    // Show selected tab
                                    tabContents.forEach(content => {
                                        if (content.getAttribute('data-tab-content') === tab) {
                                            content.classList.remove('hidden');
                                        } else {
                                            content.classList.add('hidden');
                                        }
                                    });
                                });
                            });
                        });
                    </script>


                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const tabButtons = document.querySelectorAll('[data-tab]');
                            const tabContents = document.querySelectorAll('[data-tab-content]');

                            tabButtons.forEach(button => {
                                button.addEventListener('click', () => {
                                    const tab = button.getAttribute('data-tab');

                                    // Toggle active tab button
                                    tabButtons.forEach(btn => {
                                        btn.classList.remove('border-b-2', 'text-black');
                                        btn.classList.add('text-gray-500');
                                    });
                                    button.classList.add('border-b-2', 'text-black');
                                    button.classList.remove('text-gray-500');

                                    // Show/Hide tab content
                                    tabContents.forEach(content => {
                                        if (content.getAttribute('data-tab-content') === tab) {
                                            content.style.display = 'block';
                                        } else {
                                            content.style.display = 'none';
                                        }
                                    });
                                });
                            });
                        });
                    </script>

                    <script>
                        $('.filter-checkbox').on('change', function () {
                            filterJobseekers();
                        });

                        function getCheckedValues(name) {
                            return $("input[name='" + name + "']:checked").map(function () {
                                return this.value;
                            }).get();
                        }

                        function filterJobseekers() {
                            let filters = {
                                experience: getCheckedValues('experience[]'),
                                education: getCheckedValues('education[]'),
                                gender: getCheckedValues('gender[]'),
                                certificate: getCheckedValues('certificate[]')
                            };

                            $('#jobseekerList').html('<p class="p-4 text-gray-500">Loading...</p>');

                            $.ajax({
                                url: "{{ route('recruiter.filter.jobseekers') }}",
                                type: "GET",
                                data: filters,
                                success: function (response) {
                                    $('#jobseekerList').html(response.jobseekers_html);
                                    $('#shortlistedList').html(response.shortlisted_html);
                                },
                                error: function () {
                                    $('#jobseekerList').html('<p class="p-4 text-red-500">Failed to load filtered jobseekers.</p>');
                                }
                            });
                        }
                    </script>

                </main>  
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



                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                <script>
                    $(document).ready(function () {
                        const itemsPerPage = 10;
                        const $entries = $('.jobseeker-entry');
                        const totalItems = $entries.length;
                        const totalPages = Math.ceil(totalItems / itemsPerPage);
                        let currentPage = 1;

                        function showPage(page) {
                            $entries.hide();
                            const start = (page - 1) * itemsPerPage;
                            const end = start + itemsPerPage;
                            $entries.slice(start, end).fadeIn(200);
                            currentPage = page;
                            updatePagination();
                        }

                        function updatePagination() {
                            $('.page-btn').removeClass('bg-blue-500 text-white').addClass('bg-gray-200 text-black');
                            $(`.page-btn[data-page="${currentPage}"]`).addClass('bg-blue-500 text-white').removeClass('bg-gray-200 text-black');

                            // Disable Prev/Next if at start or end
                            $('#prev-btn').prop('disabled', currentPage === 1);
                            $('#next-btn').prop('disabled', currentPage === totalPages);
                        }

                        function createPagination() {
                            $('#pagination').empty();

                            // Prev Button
                            $('#pagination').append(`
                                <button id="prev-btn" class="px-3 py-1 text-sm rounded bg-gray-200 hover:bg-blue-200 transition">&lt;</button>
                            `);

                            // Numbered Buttons
                            for (let i = 1; i <= totalPages; i++) {
                                $('#pagination').append(`
                                    <button 
                                        class="page-btn px-3 py-1 text-sm rounded bg-gray-200 hover:bg-blue-200 transition" 
                                        data-page="${i}"
                                    >${i}</button>
                                `);
                            }

                            // Next Button
                            $('#pagination').append(`
                                <button id="next-btn" class="px-3 py-1 text-sm rounded bg-gray-200 hover:bg-blue-200 transition">&gt;</button>
                            `);

                            // Button Clicks
                            $('.page-btn').on('click', function () {
                                const page = $(this).data('page');
                                showPage(page);
                            });

                            $('#prev-btn').on('click', function () {
                                if (currentPage > 1) showPage(currentPage - 1);
                            });

                            $('#next-btn').on('click', function () {
                                if (currentPage < totalPages) showPage(currentPage + 1);
                            });
                        }

                        if (totalItems > 0) {
                            createPagination();
                            showPage(1);
                        } else {
                            $('#pagination').html('<p class="text-center text-gray-500">No jobseekers found.</p>');
                        }
                    });
                </script>
                <script>
                    $(document).ready(function () {
                        const itemsPerPage = 10;
                        const $entries = $('.jobseeker-shortlisted');
                        const totalItems = $entries.length;
                        const totalPages = Math.ceil(totalItems / itemsPerPage);
                        let currentPage = 1;

                        function showPage(page) {
                            $entries.hide();
                            const start = (page - 1) * itemsPerPage;
                            const end = start + itemsPerPage;
                            $entries.slice(start, end).fadeIn(200);
                            currentPage = page;
                            updatePagination();
                        }

                        function updatePagination() {
                            $('.page-btn').removeClass('bg-blue-500 text-white').addClass('bg-gray-200 text-black');
                            $(`.page-btn[data-page="${currentPage}"]`).addClass('bg-blue-500 text-white').removeClass('bg-gray-200 text-black');

                            // Disable Prev/Next if at start or end
                            $('#prev-btn').prop('disabled', currentPage === 1);
                            $('#next-btn').prop('disabled', currentPage === totalPages);
                        }

                        function createPagination() {
                            $('#shortlistedPagination').empty();

                            // Prev Button
                            $('#shortlistedPagination').append(`
                                <button id="prev-btn" class="px-3 py-1 text-sm rounded bg-gray-200 hover:bg-blue-200 transition">&lt;</button>
                            `);

                            // Numbered Buttons
                            for (let i = 1; i <= totalPages; i++) {
                                $('#shortlistedPagination').append(`
                                    <button 
                                        class="page-btn px-3 py-1 text-sm rounded bg-gray-200 hover:bg-blue-200 transition" 
                                        data-page="${i}"
                                    >${i}</button>
                                `);
                            }

                            // Next Button
                            $('#shortlistedPagination').append(`
                                <button id="next-btn" class="px-3 py-1 text-sm rounded bg-gray-200 hover:bg-blue-200 transition">&gt;</button>
                            `);

                            // Button Clicks
                            $('.page-btn').on('click', function () {
                                const page = $(this).data('page');
                                showPage(page);
                            });

                            $('#prev-btn').on('click', function () {
                                if (currentPage > 1) showPage(currentPage - 1);
                            });

                            $('#next-btn').on('click', function () {
                                if (currentPage < totalPages) showPage(currentPage + 1);
                            });
                        }

                        if (totalItems > 0) {
                            createPagination();
                            showPage(1);
                        } else {
                            $('#shortlistedPagination').html('<p class="text-center text-gray-500">No jobseekers found.</p>');
                        }
                    });
                </script>

                <script defer>
                    document.addEventListener("DOMContentLoaded", function () {
                    const tabs = document.querySelectorAll("[data-tab]");
                    const contents = document.querySelectorAll("[data-tab-content]");

                    tabs.forEach((tab) => {
                        tab.addEventListener("click", () => {
                        const target = tab.getAttribute("data-tab");

                        // Toggle active tab button
                        tabs.forEach((btn) => btn.classList.remove("text-blue-600", "border-blue-600", "border-b-2"));
                        tab.classList.add("text-blue-600", "border-blue-600", "border-b-2");

                        // Toggle content visibility
                        contents.forEach((content) => {
                            content.style.display = content.getAttribute("data-tab-content") === target ? "block" : "none";
                        });
                        });
                    });

                    // Show first tab by default
                    tabs[0].click();
                    });
                </script>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmShortlist(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to shortlist this jobseeker?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('shortlist-form-' + id).submit();
            }
        });
    }
</script>




<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.tailwindcss.com"></script>







            </div>
        </div>

      


    </div>
           


@include('site.recruiter.componants.footer')