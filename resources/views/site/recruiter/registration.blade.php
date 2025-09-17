@include('site.componants.header')

<body>


    @php
        $email = session('email');
        $phone_number = session('phone_number');
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
                                <span class="text-xs mt-1 text-center">{{ langLabel('additional') }}<br />Information</span>
                            </div>
                            </div>
                            <form class="space-y-6" id="multiStepForm"  action="{{ route('recruitment.registration.store') }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                <input type="hidden" name="recruiter_id" value="{{ session('recruiter_id') }}">

                                <!-- Step 1: Company Information -->
                                <div id="step-1" class="step">
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">{{ langLabel('company_name') }} <span style="color: red; font-size: 17px;">*</span></label>
                                            <input type="text" name="company_name" class="w-full border rounded-md p-2 mt-1" placeholder="{{ langLabel('enter_company_name') }}" value="{{old('company_name')}}"/>
                                            @error('company_name')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('company_website') }} <span style="color: red; font-size: 17px;">*</span></label>
                                                <input type="url" name="company_website" class="w-full border rounded-md p-2 mt-1" placeholder="{{ langLabel('enter_company_website') }}" value="{{old('company_website')}}"/>
                                                @error('company_website')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('company_location') }} <span style="color: red; font-size: 17px;">*</span></label>
                                                <input type="text" name="company_city" class="w-full border rounded-md p-2 mt-1" placeholder="{{ langLabel('enter_company_location') }}" value="{{old('company_city')}}"/>
                                                @error('company_city')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('company_address') }} <span style="color: red; font-size: 17px;">*</span></label>
                                            <input type="text" name="company_address" class="w-full border rounded-md p-2 mt-1" placeholder="{{ langLabel('enter_company_address') }}" value="{{old('company_address')}}"/>
                                            @error('company_address')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('business_email') }} <span style="color: red; font-size: 17px;">*</span></label>
                                                <input type="email"  name="business_email"  class="w-full border rounded-md p-2 mt-1" placeholder="{{ langLabel('enter_email_id') }}" value="{{old('company_address')}}"/>
                                                @error('business_email')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('company_phone_number') }} <span style="color: red; font-size: 17px;">*</span></label>
                                                <div class="flex">
                                                    <select class="w-1/3 border rounded-l-md p-2 mt-1" name="phone_code" required>
                                                        <option value="+966">(+966) 🇸🇦 Saudi Arabia</option>
                                                        <option value="+1">(+1) 🇺🇸 United States</option>
                                                        <option value="+91">(+91) 🇮🇳 India</option>
                                                        <option value="+44">(+44) 🇬🇧 United Kingdom</option>
                                                        <option value="+61">(+61) 🇦🇺 Australia</option>
                                                        <option value="+81">(+81) 🇯🇵 Japan</option>
                                                        <option value="+49">(+49) 🇩🇪 Germany</option>
                                                        <option value="+33">(+33) 🇫🇷 France</option>
                                                        <option value="+86">(+86) 🇨🇳 China</option>
                                                        <option value="+971">(+971) 🇦🇪 UAE</option>
                                                        <option value="+92">(+92) 🇵🇰 Pakistan</option>
                                                        <option value="+880">(+880) 🇧🇩 Bangladesh</option>
                                                        <option value="+94">(+94) 🇱🇰 Sri Lanka</option>
                                                        <option value="+7">(+7) 🇷🇺 Russia</option>
                                                    </select>

                                                    <input type="tel" name="company_phone_number" class="w-2/3 border rounded-r-md p-2 mt-1" placeholder="{{ langLabel('enter_phone_number') }}"  value="{{ old('company_phone_number') }}"/>
                                                    @error('company_phone_number')
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('number_of_employees') }} <span style="color: red; font-size: 17px;">*</span></label>
                                                <input type="number" name="no_of_employee" class="w-full border rounded-md p-2 mt-1" placeholder="{{ langLabel('enter_number_of_employees') }}" value="{{old('no_of_employee')}}"/>
                                                @error('no_of_employee')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('industry_type') }} <span style="color: red; font-size: 17px;">*</span></label>
                                                <select class="w-full border rounded-md p-2 mt-1" name="industry_type">
                                                    <option value="">{{ langLabel('select_type') }}</option>
                                                    <option value="it" {{ old('industry_type') == 'it' ? 'selected' : '' }}>Information Technology</option>
                                                    <option value="healthcare" {{ old('industry_type') == 'healthcare' ? 'selected' : '' }}>Healthcare</option>
                                                    <option value="finance" {{ old('industry_type') == 'finance' ? 'selected' : '' }}>Finance</option>
                                                    <option value="education" {{ old('industry_type') == 'education' ? 'selected' : '' }}>Education</option>
                                                    <option value="manufacturing" {{ old('industry_type') == 'manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                                                    <option value="retail" {{ old('industry_type') == 'retail' ? 'selected' : '' }}>Retail</option>
                                                    <option value="hospitality" {{ old('industry_type') == 'hospitality' ? 'selected' : '' }}>Hospitality</option>
                                                    <option value="construction" {{ old('industry_type') == 'construction' ? 'selected' : '' }}>Construction</option>
                                                    <option value="transportation" {{ old('industry_type') == 'transportation' ? 'selected' : '' }}>Transportation</option>
                                                    <option value="real_estate" {{ old('industry_type') == 'real_estate' ? 'selected' : '' }}>Real Estate</option>
                                                    <option value="agriculture" {{ old('industry_type') == 'agriculture' ? 'selected' : '' }}>Agriculture</option>
                                                    <option value="telecom" {{ old('industry_type') == 'telecom' ? 'selected' : '' }}>Telecommunications</option>
                                                    <option value="media" {{ old('industry_type') == 'media' ? 'selected' : '' }}>Media & Entertainment</option>
                                                    <option value="government" {{ old('industry_type') == 'government' ? 'selected' : '' }}>Government</option>
                                                    <option value="legal" {{ old('industry_type') == 'legal' ? 'selected' : '' }}>Legal</option>
                                                </select>
                                                @error('industry_type')
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>

                                        </div>

                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('cr_number') }} ({{ langLabel('company_registration_number') }}) <span style="color: red; font-size: 17px;">*</span></label>
                                            <input type="text" name="registration_number"  class="w-full border rounded-md p-2 mt-1" placeholder="Enter CR number" value="{{old('registration_number')}}"/>
                                            @error('registration_number')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="flex justify-end">
                                            <button type="button" onclick="showStep(2)" class="bg-blue-700 text-white px-6 py-2 rounded-md mt-3">
                                                {{ langLabel('next') }}
                                            </button>
                                        </div>
                                
                                </div>

                                <!-- Step 2: Additional Information -->
                                <div id="step-2" class="step hidden">
                                        <div>
                                            <h2 class="font-semibold mb-2">{{ langLabel('recruiter_details') }}:</h2>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div>
                                                    <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('recruiters_name') }} <span style="color: red; font-size: 17px;">*</span></label>
                                                    <input type="text" name="name" class="w-full border rounded-md p-2 mt-1" placeholder="{{ langLabel('enter_recruiters_name') }}" value="{{old('name')}}"/>
                                                    @error('name')
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('recruiters_email') }} <span style="color: red; font-size: 17px;">*</span></label>
                                                    <input type="email" name="email" class="w-full border rounded-md p-2 mt-1" placeholder="{{ langLabel('enter_recruiters') }}"  value="{{ old('email', $email) }}" readonly/>
                                                    @error('email')
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-3">
                                                <div>
                                                    <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('recruiters_phone_number') }} <span style="color: red; font-size: 17px;">*</span></label>
                                                    <input type="text" name="phone_number" class="w-full border rounded-md p-2 mt-1" placeholder="{{ langLabel('enter_recruiters_phone_number') }}"  value="{{ old('phone_number') }}" />
                                                    @error('phone_number')
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label class="block mb-1 text-sm font-medium">{{ langLabel('national_id_number') }} <span style="color: red; font-size: 17px;">*</span></label>
                                                    <span class="text-xs text-blue-600">
                                                        National ID should start with 1 for male and 2 for female.
                                                    </span>
                                                    <input 
                                                        type="text" 
                                                        name="national_id" 
                                                        id="national_id" 
                                                        class="w-full border rounded-md p-2 mt-1" 
                                                        placeholder="Enter national id number" 
                                                        value="{{ old('national_id') }}" 
                                                        maxlength="15"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);" 
                                                    />
                                                    @error('national_id')
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <h2 class="font-semibold mb-2 mt-3">{{ langLabel('documents') }}:</h2>
                                            <div class="flex flex-col gap-4">
                                                <!-- Company Profile Upload -->
                                                <div class="flex flex-col gap-2">
                                                    <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('company_profile_picture') }} <span style="color: red; font-size: 17px;">*</span></label>
                                                    <div class="flex items-center gap-4">

                                                        <input type="file" name="company_profile" accept=".png,.jpg,.jpeg" class="w-full border rounded-md p-2" />

                                                    </div>
                                                    @error('company_profile')
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Registration Documents Upload -->
                                                <div class="flex flex-col gap-2">
                                                    <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('company_registration_documents') }} <span style="color: red; font-size: 17px;">*</span></label>
                                                    <div class="flex items-center gap-4">
                                                        <input type="file" name="registration_documents" accept=".png, .jpg, .jpeg, .pdf, .doc, .docx" class="w-full border rounded-md p-2" multiple />
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
                                            <button type="button" onclick="showStep(1)" class="text-gray-700 border border-gray-400 px-6 py-2 rounded-md">{{ langLabel('back') }}</button>
                                            
                                            <button type="submit" class="inline-block bg-blue-700 text-white px-8 py-2 rounded-md hover:bg-blue-800 transition">
                                                {{ langLabel('register') }}
                                            </button>
                                        </div>
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


    <!-- jQuery Validate -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <!-- all step field click on next button-->
    <script>
        $(document).ready(function () {
            const form = $('#multiStepForm');

            // Initialize validation
            form.validate({
                ignore: [],
                rules: {
                    // Step 1
                    company_name: "required",
                    company_website: "required",
                    company_city: "required",
                    company_address: "required",
                    business_email: "required",
                    company_phone_number: "required",
                    no_of_employee: "required",
                    industry_type: "required",
                    registration_number: "required",

                    // Step 2
                    name: "required",
                    email: "required",
                    phone_number: "required",
                    national_id: "required",
                    company_profile: "required",
                    registration_documents: "required"
                },
                messages: {
                    // Step 1
                    company_name: "Company name is required",
                    company_website: "Company website is required",
                    company_city: "Company city is required",
                    company_address: "Company address is required",
                    business_email: "Business email is required",
                    company_phone_number: "Company phone number is required",
                    no_of_employee: "Number of employees is required",
                    industry_type: "Industry type is required",
                    registration_number: "Registration number is required",

                    // Step 2
                    name: "Recruiter's name is required",
                    email: "Recruiter's email is required",
                    phone_number: "Recruiter's phone number is required",
                    national_id: "National ID is required",
                    company_profile: "Upload company profile",
                    registration_documents: "Upload registration documents"
                },
                errorElement: 'p',
                errorPlacement: function (error, element) {
                    error.addClass('text-red-600 text-sm mt-1');
                    error.insertAfter(element);
                }
            });

            // Step show function with validation
            window.showStep = function (step) {
                const currentStep = $('.step:visible');
                let valid = true;

                currentStep.find('input, select, textarea').each(function () {
                    if (!$(this).valid()) {
                        valid = false;
                    }
                });

                if (!valid) return;

                // Hide all steps and show current step
                for (let i = 1; i <= 2; i++) {
                    $(`#step-${i}`).addClass('hidden');
                    $(`#step-${i}-circle`).removeClass('bg-blue-600 text-white border-blue-600');
                    $(`#step-${i}-circle`).addClass('bg-white text-blue-600');
                }

                $(`#step-${step}`).removeClass('hidden');
                $(`#step-${step}-circle`).addClass('bg-blue-600 text-white border-blue-600');
                $(`#step-${step}-circle`).removeClass('bg-white text-blue-600');
            };

            // Handle next button click
            $('.next-btn').on('click', function () {
                const nextStep = parseInt($(this).data('next-step'));
                showStep(nextStep);
            });

            // Handle previous button click (optional)
            $('.prev-btn').on('click', function () {
                const prevStep = parseInt($(this).data('prev-step'));
                showStep(prevStep);
            });

        });
    </script>
</body>
</html>
