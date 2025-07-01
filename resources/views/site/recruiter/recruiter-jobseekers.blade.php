<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from thewebmax.org/jobzilla/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 May 2025 07:17:45 GMT -->
<head>

	<!-- META -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <meta name="robots" content="" />    
    <meta name="description" content="" />
    
    <!-- FAVICONS ICON -->
    <link rel="icon" href="images/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />
    
    <!-- PAGE TITLE HERE -->
    <title>Talentrek</title>
    
    <!-- MOBILE SPECIFIC -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

   <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/feather.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/magnific-popup.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/lc_lightbox.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/select.bootstrap5.min.css') }}"> 
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/dropzone.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/scrollbar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/datepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/flaticon.css') }}"> 
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/swiper-bundle.min.css') }}">

    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/style.css') }}"> -->


    <link rel="stylesheet" class="skin" type="text/css" href="css/skins-type/skin-6.css">
    <link rel="stylesheet" type="text/css" href="css/switcher.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">


    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
<?php
    // echo "<pre>";
    // print_r( $jobseekers->links());exit;
    // echo "</pre>";
?>

    <div class="loading-area">
        <div class="loading-box"></div>
        <div class="loading-pic">
            <div class="wrapper">
                <div class="cssload-loader"></div>
            </div>
        </div>
    </div>


	
    <div class="page-wraper">
        <div class="flex h-screen">
           <aside class="w-64 bg-blue-900 text-white flex flex-col py-8 px-4">
                <div class="text-2xl font-bold mb-10">
                    <span class="text-white">Talentre</span><span class="text-blue-400">k</span>
                </div>
                <nav class="flex flex-col gap-4">
                    <a href="recruiter-dashboard.html" class="flex items-center px-4 py-2 text-white rounded-md hover:text-white transition-colors duration-200">
                        <i data-feather="grid" class="mr-3"></i> Dashboard
                    </a>
                    <a href="recruiter-jobseekers.html" class="flex items-center px-4 py-2 bg-white text-blue-700 rounded-md">
                        <i data-feather="users" class="mr-3"></i> Jobseekers
                    </a>
                    <a href="admin-support.html" class="flex items-center px-4 py-2 text-white rounded-md hover:text-white transition-colors duration-200">
                        <i data-feather="headphones" class="mr-3"></i> Admin support
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-white rounded-md hover:text-white transition-colors duration-200">
                        <i data-feather="settings" class="mr-3"></i> Settings
                    </a>
                    </nav>
                <script src="https://unpkg.com/feather-icons"></script>
                <script>
                feather.replace();
                </script>

                <style>
                    .no-hover:hover {
                    background-color: transparent !important;
                    color: inherit !important;
                    cursor: pointer; /* optional */
                    }
                </style>
                </aside>

            <div class="flex-1 flex flex-col">
                <nav class="bg-white shadow-md px-6 py-3 flex items-center justify-between">
                    <div class="flex items-center space-x-6 w-1/2">
                    <div class="text-xl font-bold text-blue-900 block lg:hidden">
                        Talent<span class="text-blue-500">rek</span>
                    </div>
                    <div class="relative w-full">
                        <input type="text" placeholder="Search for talent" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
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

                <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="jobseekerDashboard()">
                    <h2 class="text-2xl font-semibold mb-6">Jobseekers</h2>
                    <div class="flex space-x-6">

                        <!-- Sidebar Filters -->
                        <div class="bg-white p-4 rounded shadow w-64 space-y-4">
                        <h2 class="font-semibold mb-2">Filters</h2>
                        <!-- Example filter block -->
                        <div>
                            <p class="font-semibold mb-1">Experience</p>
                            <label class="block text-sm"><input type="checkbox" class="mr-2" />Fresher</label>
                            <label class="block text-sm"><input type="checkbox" class="mr-2" />3+ years</label>
                        </div>
                        </div>

                        <!-- Main Panel -->
                        <div class="flex-1 bg-white p-4 rounded shadow">
                        <!-- Tabs -->
                        <div class="flex justify-between border-b pb-2 mb-4">
                            <div class="space-x-6 font-medium text-sm">
                            <button data-tab="jobseekers" class="pb-1 border-b-2">Jobseekers</button>
                            <button data-tab="shortlisted" class="text-gray-500 pb-1">Shortlisted</button>
                            <button data-tab="contacted" class="text-gray-500 pb-1">Contacted</button>
                            </div>
                            <div class="text-sm font-semibold text-gray-600">
                            Results: <span>{{$jobseekers->count();}}</span>
                            </div>
                        </div>

                        <!-- Jobseekers Tab -->
                        <div data-tab-content="jobseekers" class="divide-y">
                            @foreach($jobseekers as $jobseeker)
                                <div class=" jobseeker-entry flex justify-between items-center py-4">
                                    <!-- Profile Image & Name -->
                                    <div class="flex items-center space-x-4 w-1/3">
                                        <img 
                                            src="{{ $jobseeker->profile_image ?? 'https://i.pravatar.cc/100' }}" 
                                            class="w-12 h-12 rounded-full object-cover" 
                                            alt="{{ $jobseeker->name }}"
                                        />
                                        <div>
                                            <h4 class="font-semibold text-sm">{{ $jobseeker->name }}</h4>
                                            <p class="text-sm text-gray-500">
                                                {{ $jobseeker->experiences->pluck('job_role')->filter()->join(', ') ?: 'Not provided' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Experience Years -->
                                    <div class="w-32 text-sm">
                                        <p class="font-semibold">Experience</p>
                                        <p>{{ $jobseeker->experience ?? 'N/A' }} years</p>
                                    </div>

                                    <!-- Skills -->
                                    <div class="text-sm flex-1">
                                        <p class="font-semibold">Skills</p>
                                        <p>
                                            @if($jobseeker->skills && $jobseeker->skills->count())
                                                {{ $jobseeker->skills->pluck('skills')->filter()->join(', ') }}
                                            @else
                                                Not provided
                                            @endif
                                        </p>
                                    </div>

                                    <!-- Shortlist Button -->
                                    <div class="ml-4">
                                        <button class="bg-blue-600 text-white text-sm px-4 py-1.5 rounded hover:bg-blue-700">
                                            Shortlist
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                            <!-- Pagination Controls -->
                            <div id="pagination" class="mt-6 flex justify-center space-x-2"></div>
                        </div>

                            


                            <!-- Shortlisted Tab -->
                            <div data-tab-content="shortlisted" style="display: none;">
                                <div class="text-center py-6 text-gray-500">No shortlisted jobseekers.</div>
                            </div>

                            <!-- Contacted Tab -->
                            <div data-tab-content="contacted" style="display: none;">
                                <div class="text-center py-6 text-gray-500">No contacted jobseekers.</div>
                            </div>
                        </div>
                    </div> 
                </main>
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
            


<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.tailwindcss.com"></script>







            </div>
        </div>

      


    </div>
           



          


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


</body>


<!-- Mirrored from thewebmax.org/jobzilla/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 May 2025 07:18:30 GMT -->
</html>
