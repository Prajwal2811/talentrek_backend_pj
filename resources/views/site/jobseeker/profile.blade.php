<?php
$user = Auth()->user();
// echo "<pre>";
// print_r($user);exit;
$edu = $user->educations;
$work = $user->experiences;
$skills = $user->skills->first();


?>


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

    @include('site.componants.navbar')

    @if($jobseekerNeedsSubscription)
        @include('site.jobseeker.subscription.index')
    @endif


    <div class="page-content">
        <div class="relative bg-center bg-cover h-[400px] flex items-center"
            style="background-image: url('{{ asset('asset//images/banner/service page banner.png') }}');">
            <div class="absolute inset-0 bg-white bg-opacity-10"></div>
            <div class="relative z-10 container mx-auto px-4">
                <div class="space-y-2">
                    <h2 class="text-3xl font-bold text-white ml-[10%]">{{ langLabel('profile') }}</h2>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    @include('admin.errors')
    <main class="w-11/12 mx-auto py-8" x-data="{ tab: 'personal' }">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">
            <!-- Left/Main Content -->
            <div class="flex-1">
                <!-- Header -->
                <div class="max-w-6xl mx-auto py-6 px-4">
                    <div class="flex justify-between items-start">
                        <div class="flex gap-4">
                            @php

                                $userId = auth()->id();
                                $profile = App\Models\AdditionalInfo::where('user_id', $userId)
                                    ->where('doc_type', 'profile_picture')
                                    ->first();
                            @endphp

                            <!-- Image Preview -->
                            <img id="profilePreview"
                                src="{{ $profile ? asset($profile->document_path) : 'https://www.lscny.org/app/uploads/2018/05/mystery-person.png' }}"
                                class="h-20 w-20 rounded-md mb-2" alt="Profile Preview" />


                            <div class="mt-1">
                                <h2 class="text-xl font-semibold">{{$user->name}}</h2>
                                <p class="text-sm text-gray-600">{{$user->email}}</p>
                                <p class="text-sm text-gray-600">{{$user->phone_number}}</p>
                            </div>
                        </div>
                        <form action="{{ route('jobseeker.logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="border rounded px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 flex items-center gap-2">
                                <i class="fas fa-power-off"></i>
                                {{ langLabel('logout') }}
                            </button>
                        </form>
                    </div>
                </div>


                <hr />

                <!-- Main Section -->
                <div class="max-w-6xl mx-auto flex px-4 py-6 gap-6">
                    <!-- Sidebar -->
                    <!-- <div x-data="{ tab: 'profile', profileTab: 'personal' }" class="flex max-w-5xl w-full space-x-6"> -->
                    <div x-data="{
                                tab: localStorage.getItem('activeTab') || 'profile',
                                profileTab: 'personal'
                            }" x-init="$watch('tab', value => localStorage.setItem('activeTab', value))"
                        class="flex max-w-5xl w-full space-x-6">
                        <!-- Sidebar Outer Tabs -->
                        <div class="w-1/5 space-y-2" style="background-color: rgb(238, 238, 238);">
                            <ul class="text-sm font-medium">
                                <li>
                                    <button @click="tab = 'profile'"
                                        :class="tab === 'profile' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                                        class="w-full text-left px-4 py-2 rounded">
                                        {{ langLabel('profile') }}
                                    </button>
                                </li>
                                <li>
                                    <button @click="tab = 'cart'"
                                        :class="tab === 'cart' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                                        class="w-full text-left px-4 py-2 rounded">
                                        {{ langLabel('cart') }}
                                    </button>
                                </li>
                                <li>
                                    <button @click="tab = 'training'"
                                        :class="tab === 'training' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                                        class="w-full text-left px-4 py-2 rounded">
                                        {{ langLabel('training') }}
                                    </button>
                                </li>
                                <li>
                                    <button @click="tab = 'mentorship'"
                                        :class="tab === 'mentorship' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                                        class="w-full text-left px-4 py-2 rounded">
                                        {{ langLabel('mentorship') }}
                                    </button>
                                </li>
                                <li>
                                    <button @click="tab = 'assessment'"
                                        :class="tab === 'assessment' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                                        class="w-full text-left px-4 py-2 rounded">
                                        {{ langLabel('assessment') }}
                                    </button>
                                </li>
                                <li>
                                    <button @click="tab = 'coaching'"
                                        :class="tab === 'coaching' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                                        class="w-full text-left px-4 py-2 rounded">
                                        {{ langLabel('coaching') }}
                                    </button>
                                </li>
                                <li>
                                    <button @click="tab = 'subscription'"
                                        :class="tab === 'subscription' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                                        class="w-full text-left px-4 py-2 rounded">
                                        {{ langLabel('subscription') }}
                                    </button>
                                </li>
                                <li>
                                    <button @click="tab = 'payment'"
                                        :class="tab === 'payment' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                                        class="w-full text-left px-4 py-2 rounded">
                                        {{ langLabel('payment') }}
                                    </button>
                                </li>
                                <li>
                                    <button @click="tab = 'certificates'"
                                        :class="tab === 'certificates' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                                        class="w-full text-left px-4 py-2 rounded">
                                        {{ langLabel('certificates') }}
                                    </button>
                                </li>
                                <!-- More outer tabs can be added here -->
                            </ul>
                        </div>

                        <!-- Main Content -->
                        <div class="w-4/5 space-y-6">
                            <!-- Profile Tab Content -->
                            @include('site.jobseeker.profile.profile')
                            @include('site.jobseeker.profile.cart')
                            @include('site.jobseeker.profile.training')
                            @include('site.jobseeker.profile.mentorship')
                            @include('site.jobseeker.profile.assessment')
                            @include('site.jobseeker.profile.coaching')
                            @include('site.jobseeker.profile.subscription')
                            @include('site.jobseeker.profile.payment')
                            @include('site.jobseeker.profile.certificate')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    @include('site.jobseeker.componants.footer')












    <script>
        function toggleSection(id, iconId) {
            const section = document.getElementById(id);
            const icon = document.getElementById(iconId);
            section.classList.toggle('hidden');
            if (icon.classList.contains('rotate-180')) {
                icon.classList.remove('rotate-180');
            } else {
                icon.classList.add('rotate-180');
            }
        }
    </script>
    <script>
        $(document).ready(function () {
            // $('#dob').datepicker({
            //     format: 'yyyy-mm-dd',
            //     endDate: new Date(),
            //     autoclose: true,
            //     todayHighlight: true
            // });

            // $('.datepicker-start, .datepicker-end').datepicker({
            //     format: 'yyyy-mm-dd',
            //     endDate: new Date(),
            //     autoclose: true,
            //     todayHighlight: true
            // });
        });
    </script>


    <!-- <script>
    let workIndex = document.querySelectorAll('.work-entry').length;

    const workContainer = document.getElementById('work-container');
    const addWorkBtn = document.getElementById('add-work');

    addWorkBtn.addEventListener('click', () => {
        const firstEntry = workContainer.querySelector('.work-entry');
        const clone = firstEntry.cloneNode(true);

        // Clear input values
        clone.querySelectorAll('input').forEach((input) => {
            input.value = '';

            // Update IDs for date fields
            if (input.id.includes('starts_from')) {
                input.id = `starts_from_${workIndex}`;
            }

            if (input.id.includes('end_to')) {
                input.id = `end_to_${workIndex}`;
            }
        });

        // Clear validation errors
        clone.querySelectorAll('p.text-red-600').forEach(error => error.remove());

        // Show remove button
        clone.querySelector('.remove-work').style.display = 'block';

        // Append cloned entry
        workContainer.appendChild(clone);

        // Re-initialize datepickers
        $(`#starts_from_${workIndex}`).datepicker({
            format: 'yyyy-mm-dd',
            endDate: new Date(),
            autoclose: true,
            todayHighlight: true
        });

        $(`#end_to_${workIndex}`).datepicker({
            format: 'yyyy-mm-dd',
            endDate: new Date(),
            autoclose: true,
            todayHighlight: true
        });

        workIndex++;
    });

    workContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-work')) {
            const entry = e.target.closest('.work-entry');
            entry.remove();
        }
    });
</script> -->
    <script>
        $(document).ready(function () {
            // $('#dob').datepicker({
            //     format: 'yyyy-mm-dd',
            //     endDate: new Date(),
            //     autoclose: true,
            //     todayHighlight: true
            // });
            //     function initializeDatePickers() {
            //     $('.datepicker-start, .datepicker-end').datepicker({
            //         format: 'yyyy-mm-dd',
            //         endDate: new Date(),
            //         autoclose: true,
            //         todayHighlight: true
            //     });
            // }

            // initializeDatePickers();

            // $('#add-work').on('click', function () {

            //     initializeDatePickers(); 
            // });
        });

    </script>

    <!-- Natioanl id and gender logic code -->
    <script>
        $(document).ready(function () {
            function validateNationalIdInput() {
                const gender = $('#gender').val();
                const value = $('#national_id').val();

                if (gender === 'Male') {
                    if (value && !value.startsWith('1')) {
                        $('#national_id').val('');
                    }
                } else if (gender === 'Female') {
                    if (value && !value.startsWith('2')) {
                        $('#national_id').val('');
                    }
                }
            }

            // Always attach input validation
            $('#national_id').on('input', function () {
                validateNationalIdInput();
            });

            // Initial call if gender already selected
            if ($('#gender').val()) {
                validateNationalIdInput();
            }

            // On gender change, reset national ID and validate new input
            $('#gender').on('change', function () {
                $('#national_id').val('');

                // Re-attach input validation
                $('#national_id').off('input').on('input', function () {
                    validateNationalIdInput();
                });
            });
        });
    </script>








    <!-- JAVASCRIPT  FILES ========================================= -->
    <script src="js/jquery-3.6.0.min.js"></script><!-- JQUERY.MIN JS -->
    <script src="js/popper.min.js"></script><!-- POPPER.MIN JS -->
    <script src="js/bootstrap.min.js"></script><!-- BOOTSTRAP.MIN JS -->
    <script src="js/magnific-popup.min.js"></script><!-- MAGNIFIC-POPUP JS -->
    <script src="js/waypoints.min.js"></script><!-- WAYPOINTS JS -->
    <script src="js/counterup.min.js"></script><!-- COUNTERUP JS -->
    <script src="js/waypoints-sticky.min.js"></script><!-- STICKY HEADER -->
    <script src="js/isotope.pkgd.min.js"></script><!-- MASONRY  -->
    <script src="js/imagesloaded.pkgd.min.js"></script><!-- MASONRY  -->
    <script src="js/owl.carousel.min.js"></script><!-- OWL  SLIDER  -->
    <script src="js/theia-sticky-sidebar.js"></script><!-- STICKY SIDEBAR  -->
    <script src="js/lc_lightbox.lite.js"></script><!-- IMAGE POPUP -->
    <script src="js/bootstrap-select.min.js"></script><!-- Form js -->
    <script src="js/dropzone.js"></script><!-- IMAGE UPLOAD  -->
    <script src="js/jquery.scrollbar.js"></script><!-- scroller -->
    <script src="js/bootstrap-datepicker.js"></script><!-- scroller -->
    <script src="js/jquery.dataTables.min.js"></script><!-- Datatable -->
    <script src="js/dataTables.bootstrap5.min.js"></script><!-- Datatable -->
    <script src="js/chart.js"></script><!-- Chart -->
    <script src="js/bootstrap-slider.min.js"></script><!-- Price range slider -->
    <script src="js/swiper-bundle.min.js"></script><!-- Swiper JS -->
    <script src="js/custom.js"></script><!-- CUSTOM FUCTIONS  -->
    <script src="js/switcher.js"></script><!-- SHORTCODE FUCTIONS  -->
    <!-- Add Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>