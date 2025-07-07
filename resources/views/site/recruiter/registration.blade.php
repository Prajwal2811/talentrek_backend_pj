@include('site.componants.header')

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

	@include('site.componants.navbar')
     
        
            <div class="page-content">
                <div class="section-full site-bg-white">
                    <div class="container-fluid mt-3">
                        <div class="row">
                            <div class="col-12 ml-auto mr-auto text-center" style="margin: auto">
                                @if (session()->has('success'))
                                    <div class="alert alert-success alert-dismissible fade show" id="successAlert">
                                        <strong>Success!</strong> {{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="col-12 ml-auto mr-auto text-center" style="margin: auto">
                                @if (session()->has('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" id="errorAlert">
                                        <strong>Oops!</strong> {{ session('error') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="col-12 ml-auto mr-auto text-center" style="margin: auto">
                                @if ($errors->has('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" id="errorAlert">
                                        <strong>Oops!</strong> {{ $errors->first('error') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        </button>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Success message alert -->
                            <div id="success-message" class="col-12 ml-auto mr-auto text-center alert alert-success alert-dismissible fade show" style="display: none;">
                                <strong>Success!</strong> <span class="message-text"></span>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <!-- Error message alert -->
                            <div id="error-message" class="col-12 ml-auto mr-auto text-center alert alert-danger alert-dismissible fade show" style="display: none;">
                                <strong>Oops!</strong> <span class="message-text"></span>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>

                        <script>
                            // Automatically hide alerts after 3 seconds
                            setTimeout(() => {
                                const successAlert = document.getElementById('successAlert');
                                const errorAlert = document.getElementById('errorAlert');

                                if (successAlert) {
                                    successAlert.classList.add('fade');
                                    setTimeout(() => successAlert.remove(), 500); // Remove from DOM after fade
                                }
                                if (errorAlert) {
                                    errorAlert.classList.add('fade');
                                    setTimeout(() => errorAlert.remove(), 500); // Remove from DOM after fade
                                }
                            }, 3000);
                        </script>
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
                            <form class="space-y-6" action="{{ route('recruitment.registration.store') }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                <input type="hidden" name="company_id" value="{{ session('company_id') }}">

                                <!-- Step 1: Company Information -->
                                <div id="step-1">
                                    <!-- <form class="space-y-6"> -->
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Company name</label>
                                            <input type="text" name="company_name" class="w-full border rounded-md p-2 mt-1" placeholder="Enter company name" value="{{old('company_name')}}"/>
                                            @error('company_name')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block mb-1 text-sm font-medium mt-3">Company website</label>
                                                <input type="url" name="company_website" class="w-full border rounded-md p-2 mt-1" placeholder="Paste website link" value="{{old('company_website')}}"/>
                                                @error('company_website')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="block mb-1 text-sm font-medium mt-3">Company location</label>
                                                <input type="text" name="company_city" class="w-full border rounded-md p-2 mt-1" placeholder="Enter location" value="{{old('company_city')}}"/>
                                                @error('company_city')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">Company address</label>
                                            <input type="text" name="company_address" class="w-full border rounded-md p-2 mt-1" placeholder="Enter the address" value="{{old('company_address')}}"/>
                                            @error('company_address')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block mb-1 text-sm font-medium mt-3">Business email</label>
                                                <input type="email"  name="business_email"  class="w-full border rounded-md p-2 mt-1" placeholder="Enter email id" value="{{ old('business_email', $business_email) }}"/>
                                                @error('business_email')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="block mb-1 text-sm font-medium mt-3">Company phone number</label>
                                                <div class="flex">
                                                    <select class="w-1/3 border rounded-l-md p-2 mt-1" name="phone_code" required>
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

                                                    <input type="tel" name="company_phone_number" class="w-2/3 border rounded-r-md p-2 mt-1" placeholder="Enter phone number"  value="{{ old('company_phone_number', $company_phone_number) }}"/>
                                                    @error('company_phone_number')
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block mb-1 text-sm font-medium mt-3">Number of employees</label>
                                                <input type="number" name="no_of_employee" class="w-full border rounded-md p-2 mt-1" placeholder="Enter number of employees" value="{{old('no_of_employee')}}"/>
                                                @error('no_of_employee')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="block mb-1 text-sm font-medium mt-3">Industry type</label>
                                                <select class="w-full border rounded-md p-2 mt-1" name="industry_type" required>
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
                                            <label class="block mb-1 text-sm font-medium mt-3">CR number (Company registration number)</label>
                                            <input type="text" name="registration_number"  class="w-full border rounded-md p-2 mt-1" placeholder="Enter CR number" value="{{old('registration_number')}}"/>
                                            @error('registration_number')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="flex justify-end">
                                            <button type="button" onclick="showStep(2)" class="bg-blue-700 text-white px-6 py-2 rounded-md mt-3">
                                                Next
                                            </button>
                                        </div>
                                    <!-- </form> -->
                                </div>

                                <!-- Step 2: Additional Information -->
                                <div id="step-2" class="hidden">
                                    <!-- <form class="space-y-6"> -->
                                        <div>
                                            <h2 class="font-semibold mb-2">Recruiter details:</h2>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div>
                                                    <label class="block mb-1 text-sm font-medium mt-3">Recruiter's name</label>
                                                    <input type="text" name="name" class="w-full border rounded-md p-2 mt-1" placeholder="Enter recruiter's name" value="{{old('name')}}"/>
                                                    @error('name')
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label class="block mb-1 text-sm font-medium mt-3">Recruiter's email</label>
                                                    <input type="email" name="email" class="w-full border rounded-md p-2 mt-1" placeholder="Enter recruiter's email" value="{{ old('email') }}"/>
                                                    @error('email')
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <h2 class="font-semibold mb-2 mt-3">Documents:</h2>
                                            <div class="flex flex-col gap-4">
                                                <!-- Company Profile Upload -->
                                                <div class="flex flex-col gap-2">
                                                    <label class="block mb-1 text-sm font-medium mt-3">Company Profile Picuture</label>
                                                    <div class="flex items-center gap-4">

                                                        <input type="file" name="company_profile" accept=".png,.jpg,.jpeg" class="w-full border rounded-md p-2" />

                                                    </div>
                                                    @error('company_profile')
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Registration Documents Upload -->
                                                <div class="flex flex-col gap-2">
                                                    <label class="block mb-1 text-sm font-medium mt-3">Company Registration Documents</label>
                                                    <div class="flex items-center gap-4">
                                                        <input type="file" name="registration_documents[]" accept=".png, .jpg, .jpeg, .pdf, .doc, .docx" class="w-full border rounded-md p-2" multiple />
                                                    </div>
                                                    @if ($errors->has('registration_documents'))
                                                        @foreach ($errors->get('registration_documents') as $message)
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @endforeach
                                                    @endif

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


<script src="{{ asset('asset/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('asset/js/popper.min.js') }}"></script>
<script src="{{ asset('asset/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('asset/js/magnific-popup.min.js') }}"></script>
<script src="{{ asset('asset/js/waypoints.min.js') }}"></script>
<script src="{{ asset('asset/js/counterup.min.js') }}"></script>
<script src="{{ asset('asset/js/waypoints-sticky.min.js') }}"></script>
<script src="{{ asset('asset/js/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('asset/js/imagesloaded.pkgd.min.js') }}"></script>
<script src="{{ asset('asset/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('asset/js/theia-sticky-sidebar.js') }}"></script>
<script src="{{ asset('asset/js/lc_lightbox.lite.js') }}"></script>
<script src="{{ asset('asset/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('asset/js/dropzone.js') }}"></script>
<script src="{{ asset('asset/js/jquery.scrollbar.js') }}"></script>
<script src="{{ asset('asset/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('asset/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('asset/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('asset/js/chart.js') }}"></script>
<script src="{{ asset('asset/js/bootstrap-slider.min.js') }}"></script>
<script src="{{ asset('asset/js/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('asset/js/custom.js') }}"></script>
<script src="{{ asset('asset/js/switcher.js') }}"></script>

</body>
</html>
