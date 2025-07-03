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
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/magnific-popup.min.css') }}"><!-- MAGNIFIC POPUP STYLE SHEET -->
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/lc_lightbox.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/dataTables.bootstrap5.min.css') }}"><!-- DATA table STYLE SHEET  -->
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/select.bootstrap5.min.css') }}"> 
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/dropzone.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/scrollbar.css') }}"><!-- CUSTOM SCROLL BAR STYLE SHEET -->
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/datepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/flaticon.css') }}"> 
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/swiper-bundle.min.css') }}">

   <!-- MAIN STYLE SHEET -->

     <link rel="stylesheet" class="skin" type="text/css" href="css/skins-type/skin-6.css">
        <link rel="stylesheet" type="text/css" href="css/switcher.css">

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>

    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('asset/css/style.css') }}"> -->
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>


@php
    $business_email = session('business_email');
    $company_phone_number = session('company_phone_number');
@endphp
   

    <div class="loading-area">
        <div class="loading-box"></div>
        <div class="loading-pic">
            <div class="wrapper">
                <div class="cssload-loader"></div>
            </div>
        </div>
    </div>

	
     
        <header class="site-header header-style-3 mobile-sider-drawer-menu">
            <div class="sticky top-0 bg-white shadow-md z-50">
                <div class="container mx-auto px-4 flex flex-wrap items-center justify-between">
                
                <!-- Logo -->
                <div class="logo-header">
                    <div class="logo-header-inner logo-header-one">
                        <a href="index.html" class="inline-block">
                            <img src="images/logo.png" alt="" class="h-10 object-contain" />
                        </a>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <button id="mobile-side-drawer" data-target=".header-nav" data-toggle="collapse" type="button" 
                    class="navbar-toggler collapsed md:hidden flex flex-col space-y-1.5 focus:outline-none" aria-label="Toggle navigation">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="block w-6 h-0.5 bg-gray-800"></span>
                    <span class="block w-6 h-0.5 bg-gray-800"></span>
                    <span class="block w-6 h-0.5 bg-gray-800"></span>
                </button>

                <!-- Navigation -->
                <nav class="header-nav hidden md:flex space-x-10 justify-center flex-grow">
                    <ul class="flex space-x-6 text-gray-800 font-semibold">
                        <li><a href="training.html" class="hover:text-blue-600 cursor-pointer">Training</a></li>
                        <li><a href="mentorship.html" class="hover:text-blue-600 cursor-pointer">Mentorship</a></li>
                        <li><a href="assessment.html" class="hover:text-blue-600 cursor-pointer">Assessment</a></li>
                        <li><a href="coaching.html" class="hover:text-blue-600 cursor-pointer">Coaching</a></li>
                       
                    </ul>
                </nav>

               <div class="flex items-center space-x-4">
                <!-- Notification -->
                <div class="relative">
                <button aria-label="Notifications" class="text-gray-700 hover:text-blue-600 focus:outline-none relative">
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-600 text-white">
                    <i class="feather-bell text-xl"></i>
                    </span>
                    <span class="absolute top-0 right-0 inline-block w-2.5 h-2.5 bg-red-600 rounded-full border-2 border-white"></span>
                </button>
                </div>

                <!-- Language Selector -->
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
                <!-- Sign In -->
               <div>
                    <a href="sign-in.html" role="button"
                        class="inline-flex items-center space-x-1 border border-blue-600 bg-blue-600 text-white rounded-md px-3 py-1.5 transition">
                        <i class="feather-log-in"></i>
                        <span>Sign In/Sign Up</span>
                    </a>
                </div>

                </div>

                </div>

                <!-- SITE Search -->
                <div id="search" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                <span class="close absolute top-4 right-6 cursor-pointer text-white text-3xl">&times;</span>
                <form role="search" id="searchform" action="https://thewebmax.org/search" method="get" class="bg-white rounded-xl p-6 w-full max-w-md flex items-center space-x-2">
                    <input class="form-control flex-grow border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600" name="q" type="search" placeholder="Type to search" />
                    <button type="submit" class="bg-blue-600 text-white rounded-md px-4 py-2 hover:bg-blue-700 transition">
                    <i class="fa fa-paper-plane"></i>
                    </button>
                </form>
                </div>
            </div>
            </header>
            <div class="page-content">
                <div class="section-full site-bg-white">
                    <div class="container-fluid mt-3">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                        <div class="max-w-4xl mx-auto p-8 mt-5">
                            <!-- Stepper -->
                            <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-8 mb-10">
                            <!-- Step 1 -->
                            <div class="flex flex-col items-center cursor-pointer" onclick="showStep(1)">
                                <div id="step-1-circle" class="w-8 h-8 rounded-full border-2 flex items-center justify-center">1</div>
                                <span class="text-xs mt-1 text-center">Company<br />Information</span>
                            </div>
                            <div class="hidden sm:block h-px bg-gray-300 w-20"></div>
                            <!-- Step 2 -->
                            <div class="flex flex-col items-center cursor-pointer" onclick="showStep(2)">
                                <div id="step-2-circle" class="w-8 h-8 rounded-full border-2 flex items-center justify-center">2</div>
                                <span class="text-xs mt-1 text-center">Additional<br />Information</span>
                            </div>
                            </div>
                        <form class="space-y-6" action="{{ route('recruitment.registration.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="company_id" value="{{ session('company_id') }}">


                            <!-- Step 1: Company Information -->
                            <div id="step-1">
                                <!-- <form class="space-y-6"> -->
                                    <div>
                                        <label class="block mb-1 text-sm font-medium">Company name</label>
                                        <input type="text" name="company_name" class="w-full border rounded-md p-2" placeholder="Enter company name" value="{{old('company_name')}}"/>
                                        @error('company_name')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Company website</label>
                                            <input type="url" name="company_website" class="w-full border rounded-md p-2" placeholder="Paste website link" value="{{old('company_website')}}"/>
                                            @error('company_website')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Company location</label>
                                            <input type="text" name="company_city" class="w-full border rounded-md p-2" placeholder="Enter location" value="{{old('company_city')}}"/>
                                            @error('company_city')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block mb-1 text-sm font-medium">Company address</label>
                                        <input type="text" name="company_address" class="w-full border rounded-md p-2" placeholder="Enter the address" value="{{old('company_address')}}"/>
                                        @error('company_address')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Business email</label>
                                            <input type="email"  name="business_email"  class="w-full border rounded-md p-2" placeholder="Enter email id" value="{{ old('business_email', $business_email) }}"/>
                                            @error('business_email')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Company phone number</label>
                                            <div class="flex">
                                                <select class="w-1/3 border rounded-l-md p-2" name="phone_code" required>
                                                    <option value="">Country</option>
                                                    <option value="+1">🇺🇸 United States (+1)</option>
                                                    <option value="+91">🇮🇳 India (+91)</option>
                                                    <option value="+44">🇬🇧 United Kingdom (+44)</option>
                                                    <option value="+61">🇦🇺 Australia (+61)</option>
                                                    <option value="+81">🇯🇵 Japan (+81)</option>
                                                    <option value="+49">🇩🇪 Germany (+49)</option>
                                                    <option value="+33">🇫🇷 France (+33)</option>
                                                    <option value="+86">🇨🇳 China (+86)</option>
                                                    <option value="+971">🇦🇪 UAE (+971)</option>
                                                    <option value="+92">🇵🇰 Pakistan (+92)</option>
                                                    <option value="+880">🇧🇩 Bangladesh (+880)</option>
                                                    <option value="+94">🇱🇰 Sri Lanka (+94)</option>
                                                    <option value="+966">🇸🇦 Saudi Arabia (+966)</option>
                                                    <option value="+7">🇷🇺 Russia (+7)</option>
                                                </select>

                                                <input type="tel" name="company_phone_number" class="w-2/3 border rounded-r-md p-2" placeholder="Enter phone number"  value="{{ old('company_phone_number', $company_phone_number) }}"/>
                                                @error('company_phone_number')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Number of employees</label>
                                            <input type="number" name="no_of_employee" class="w-full border rounded-md p-2" placeholder="Enter number of employees" value="{{old('no_of_employee')}}"/>
                                            @error('no_of_employee')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Industry type</label>
                                            <select class="w-full border rounded-md p-2" name="industry_type" required>
                                                <option value="">Select type</option>
                                                <option value="it">Information Technology</option>
                                                <option value="healthcare">Healthcare</option>
                                                <option value="finance">Finance</option>
                                                <option value="education">Education</option>
                                                <option value="manufacturing">Manufacturing</option>
                                                <option value="retail">Retail</option>
                                                <option value="hospitality">Hospitality</option>
                                                <option value="construction">Construction</option>
                                                <option value="transportation">Transportation</option>
                                                <option value="real_estate">Real Estate</option>
                                                <option value="agriculture">Agriculture</option>
                                                <option value="telecom">Telecommunications</option>
                                                <option value="media">Media & Entertainment</option>
                                                <option value="government">Government</option>
                                                <option value="legal">Legal</option>
                                            </select>
                                            @error('industry_type')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block mb-1 text-sm font-medium">CR number (Company registration number)</label>
                                        <input type="text" name="registration_number"  class="w-full border rounded-md p-2" placeholder="Enter CR number" value="{{old('registration_number')}}"/>
                                        @error('registration_number')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="button" onclick="showStep(2)" class="bg-blue-700 text-white px-6 py-2 rounded-md">
                                            Next
                                        </button>
                                    </div>
                                <!-- </form> -->
                            </div>

                            <!-- Step 2: Additional Information -->
                            <div id="step-2" class="hidden">
                                <!-- <form class="space-y-6"> -->
                                    <div>
                                        <h2 class="font-semibold mb-2">Recruiters details:</h2>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block mb-1 text-sm font-medium">Recruiter's name</label>
                                                <input type="text" name="name" class="w-full border rounded-md p-2" placeholder="Enter recruiter's name" value="{{old('name')}}"/>
                                                @error('name')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="block mb-1 text-sm font-medium">Recruiter's email</label>
                                                <input type="email" name="email" class="w-full border rounded-md p-2" placeholder="Enter recruiter's email" value="{{ old('email') }}"/>
                                                @error('email')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <h2 class="font-semibold mb-2">Documents:</h2>
                                        <div class="flex flex-col gap-4">
                                            <div class="flex items-center gap-4">
                                                <input type="file" class="w-full border rounded-md p-2" />
                                                <button type="button" class="bg-green-600 text-white px-4 py-2 rounded-md whitespace-nowrap">Upload image</button>
                                            </div>

                                            <div class="flex items-center gap-4">
                                                <input type="file" class="w-full border rounded-md p-2" />
                                                <button type="button" class="bg-green-600 text-white px-4 py-2 rounded-md whitespace-nowrap">Upload document</button>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="flex justify-between pt-4">
                                        <button type="button" onclick="showStep(1)" class="text-gray-700 border border-gray-400 px-6 py-2 rounded-md">Back</button>
                                        <button type="submit" class="inline-block bg-blue-700 text-white px-8 py-2 rounded-md hover:bg-blue-800 transition">
                                            Register
                                        </button>

                                    </div>
                                <!-- </form> -->
                            </div>
                        </form>
                            <!-- JavaScript for Step Navigation -->
                            <script>
                            function showStep(step) {
                                const steps = [1, 2];
                                steps.forEach((s) => {
                                const circle = document.getElementById(`step-${s}-circle`);
                                const content = document.getElementById(`step-${s}`);
                                if (s === step) {
                                    circle.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
                                    circle.classList.remove('bg-white', 'text-blue-600');
                                    content.classList.remove('hidden');
                                } else {
                                    circle.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                                    circle.classList.add('bg-white', 'text-blue-600');
                                    content.classList.add('hidden');
                                }
                                });
                            }

                            document.addEventListener('DOMContentLoaded', () => showStep(1));
                            </script>
                        </div>
                        </div>
                    </div>
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
