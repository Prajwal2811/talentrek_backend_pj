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
                

                <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="">
                    @include('admin.errors')	

                    <h2 class="text-2xl font-semibold mb-6">{{ langLabel('jobseeker') }}</h2>
                    <div class="flex space-x-6">

                        <!-- Sidebar Filters -->
                        <div class="bg-white p-4 rounded shadow w-64 space-y-4">
                            <h2 class="font-semibold mb-2">{{ langLabel('filter') }}</h2>

                            <!-- Candidates experience -->
                            <div>
                                <p class="font-semibold text-sm mb-1">{{ langLabel('candidates_experience') }}</p>
                                <label class="block text-sm"><input type="checkbox" name="experience[]" value="fresher" class="mr-2 filter-checkbox" />{{ langLabel('fresher') }} (0-3 years)</label>
                                <label class="block text-sm"><input type="checkbox" name="experience[]" value="experienced" class="mr-2 filter-checkbox" />3+ years</label>
                                <label class="block text-sm"><input type="checkbox" name="experience[]" value="all" class="mr-2 filter-checkbox" />{{ langLabel('all') }}</label>
                            </div>

                            @php $educations = \App\Models\EducationDetails::all(); @endphp

                            <!-- Education -->
                            <div>
                                <p class="font-semibold text-sm mb-1">{{ langLabel('education') }}</p>
                                @foreach ($educations->unique('high_education') as $education)
                                    <label class="block text-sm">
                                        <input type="checkbox" name="education[]" value="{{ $education->high_education }}" class="mr-2 filter-checkbox" />{{ $education->high_education }}
                                    </label>
                                @endforeach
                            </div>

                            <!-- Gender -->
                            <div>
                                <p class="font-semibold text-sm mb-1">{{ langLabel('gender') }}</p>
                                <label class="block text-sm"><input type="checkbox" name="gender[]" value="male" class="mr-2 filter-checkbox" />{{ langLabel('male') }}</label>
                                <label class="block text-sm"><input type="checkbox" name="gender[]" value="female" class="mr-2 filter-checkbox" />{{ langLabel('female') }}</label>
                                <label class="block text-sm"><input type="checkbox" name="gender[]" value="all" class="mr-2 filter-checkbox" />{{ langLabel('all') }}</label>
                            </div>

                            <!-- Certificate -->
                            <div>
                                <p class="font-semibold text-sm mb-1">{{ langLabel('certificate') }}</p>
                                <label class="block text-sm"><input type="checkbox" name="certificate[]" value="0-5" class="mr-2 filter-checkbox" />{{ langLabel('certificate') }} (0-5)</label>
                                <label class="block text-sm"><input type="checkbox" name="certificate[]" value="5+" class="mr-2 filter-checkbox" />{{ langLabel('certificate') }} 5+</label>
                                <label class="block text-sm"><input type="checkbox" name="certificate[]" value="not-certified" class="mr-2 filter-checkbox" />{{ langLabel('not_certified') }}</label>
                                <label class="block text-sm"><input type="checkbox" name="certificate[]" value="all" class="mr-2 filter-checkbox" />{{ langLabel('all') }}</label>
                            </div>
                        </div>

                        <!-- Main Panel -->
                        <div class="flex-1 bg-white p-4 rounded shadow">
                            <!-- Tabs -->
                            <!-- Tabs -->
                            <div class="flex justify-between border-b pb-2 mb-4">
                                <div class="space-x-6 font-medium text-sm">
                                    <!-- <button data-tab="jobseekers" class="tab-btn pb-1 border-b-2 text-black">{{ langLabel('jobseeker') }}</button> -->
                                    <button data-tab="jobseekers" class="tab-btn pb-1 border-b-2 text-black">{{ langLabel('jobseeker_expat') }}</button>
                                    <button data-tab="shortlisted" class="tab-btn pb-1 text-gray-500">{{ langLabel('shortlisted') }}</button>
                                    <button data-tab="scheduled" class="tab-btn pb-1 text-gray-500">{{ langLabel('scheduled_interview') }}</button>
                                </div>
                            </div>

                            <!-- Jobseekers Tab -->
                            <div id="jobseekerList" data-tab-content="jobseekers" class="divide-y">
                                @include('site.recruiter.partials.jobseeker-list', ['jobseekers' => $jobseekers])
                                <div id="pagination" class="mt-6 flex justify-center space-x-2"></div>
                            </div>
                            
                            <!-- Shortlisted Tab -->
                            <div id="shortlistedList" data-tab-content="shortlisted" class="divide-y hidden">
                                @include('site.recruiter.partials.shortlisted-jobseeker-list', ['shortlisted_jobseekers' => $shortlisted_jobseekers])
                                {{-- @include('site.recruiter.partials.jobseeker-list', ['jobseekers' => $shortlisted_jobseekers]) --}}
                                <div id="shortlistedPagination" class="mt-6 flex justify-center space-x-2"></div>
                            </div>

                            <!-- Contacted Tab -->
                            <div id="scheduledList" data-tab-content="scheduled" class="divide-y hidden">
                                @include('site.recruiter.partials.scheduled-jobseeker-list', ['shortlisted_jobseekers' => $shortlisted_jobseekers])
                                <div id="scheduledPagination" class="mt-6 flex justify-center space-x-2"></div>
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
<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

<!-- <script>
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
</script> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmShortlist(id, role) {
        const formId = `shortlist-form-${role}-${id}`;
        // alert(formId);
        Swal.fire({
            title: 'Are you sure?',
            text: `Do you want to shortlist this ${role}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
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