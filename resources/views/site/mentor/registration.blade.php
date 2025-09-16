@php
    $email = session('email');
    $phone = session('phone_number');
@endphp

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
            <div class="page-content">
                <div class="section-full site-bg-white">
                    <div class="container-fluid mt-3">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12">
                                <!-- <div class="container d-flex justify-content-center align-items-center min-vh-100"> -->
                                <div class="max-w-4xl mx-auto p-8 mt-5">
                                    <!-- Stepper -->
                                    <div class="flex items-center justify-between mb-10">
                                    <!-- Step Buttons -->
                                    <template id="stepper-template">
                                        <div class="flex flex-col items-center cursor-pointer" onclick="showStep(STEP_NUM)">
                                        <div id="step-STEP_NUM-circle" class="w-8 h-8 rounded-full border-2 flex items-center justify-center">STEP_NUM</div>
                                        <span class="text-xs mt-1 text-center">STEP_LABEL</span>
                                        </div>
                                    </template>

                                    <div class="flex flex-col items-center text-blue-600 cursor-pointer" onclick="showStep(1)">
                                        <div id="step-1-circle" class="w-8 h-8 rounded-full border-2 border-blue-600 bg-blue-600 text-white flex items-center justify-center">1</div>
                                        <span class="text-xs mt-1 text-center">Personal<br />information</span>
                                    </div>
                                    <div class="flex-1 h-px bg-gray-300 mx-2"></div>

                                    <div class="flex flex-col items-center text-blue-600 cursor-pointer" onclick="showStep(2)">
                                        <div id="step-2-circle" class="w-8 h-8 rounded-full border-2 border-blue-600 flex items-center justify-center">2</div>
                                        <span class="text-xs mt-1 text-center">Educational<br />details</span>
                                    </div>
                                    <div class="flex-1 h-px bg-gray-300 mx-2"></div>

                                    <div class="flex flex-col items-center text-blue-600 cursor-pointer" onclick="showStep(3)">
                                        <div id="step-3-circle" class="w-8 h-8 rounded-full border-2 border-blue-600 flex items-center justify-center">3</div>
                                        <span class="text-xs mt-1 text-center">Work<br />experience</span>
                                    </div>
                                    <div class="flex-1 h-px bg-gray-300 mx-2"></div>

                                    <div class="flex flex-col items-center text-blue-600 cursor-pointer" onclick="showStep(4)">
                                        <div id="step-4-circle" class="w-8 h-8 rounded-full border-2 border-blue-600 flex items-center justify-center">4</div>
                                        <span class="text-xs mt-1 text-center">Training Experience <br />& Skills</span>
                                    </div>
                                    <div class="flex-1 h-px bg-gray-300 mx-2"></div>

                                    <div class="flex flex-col items-center text-blue-600 cursor-pointer" onclick="showStep(5)">
                                        <div id="step-5-circle" class="w-8 h-8 rounded-full border-2 border-blue-600 flex items-center justify-center">5</div>
                                        <span class="text-xs mt-1 text-center">Additional<br />information</span>
                                    </div>
                                    </div>

                                    <!-- Steps Content -->

                                <form class="space-y-6" id="multiStepForm" action="{{ route('mentor.registration.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <!-- Step 1: Personal Info -->
                                    <div id="step-1" class="step">
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Full name <span style="color: red; font-size: 17px;">*</span></label>
                                            <input type="text" name="name" class="w-full border rounded-md p-2 mt-1" placeholder="Enter full name" value="{{old('name')}}"/>
                                            @error('name')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">Email <span style="color: red; font-size: 17px;">*</span></label>
                                            <input placeholder="Enter email" name="email" type="email" class="w-full border rounded-md p-2 mt-1" value="{{old('email', $email)}}" readonly/>
                                            @error('email')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-6">
                                            <div>
                                                <label class="block mb-1 text-sm font-medium mt-3">Phone number</label>
                                                <div class="flex">
                                                <select class="w-1/3 border rounded-l-md p-2 mt-1" name="phone_code">
                                                    <option value="+966">+966</option>
                                                    <option value="+971">+971</option>
                                                </select>
                                              <input 
                                                    placeholder="Enter Phone number" 
                                                    name="phone_number" 
                                                    type="tel" 
                                                    class="w-2/3 border rounded-r-md p-2 mt-1" 
                                                    value="{{ old('phone_number') }}"
                                                    maxlength="9"
                                                    pattern="[0-9]{9}"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,9);"
                                                    
                                                />
                                                
                                            </div>
                                            @error('phone_number')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">Date of birth <span style="color: red; font-size: 17px;">*</span></label>
                                            <input type="date" name="dob" class="w-full border rounded-md p-2 mt-1" value="{{old('dob')}}"/>
                                            @error('dob')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        </div>
                                        
                                            <div>
                                                <label class="block mb-1 text-sm font-medium">National ID Number <span style="color: red; font-size: 17px;">*</span></label>
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
                                                        pattern="^[1-2][0-9]{14}$"
                                                        oninput="
                                                            this.value = this.value.replace(/[^0-9]/g, ''); 
                                                            if(this.value.length > 15) this.value = this.value.slice(0,15);
                                                            if(this.value.length > 0 && !/^[12]/.test(this.value)) this.value = '';
                                                        "
                                                        required
                                                    />
                                                    @error('national_id')
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                            </div>
                                        
                                      <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">
                                                Address <span style="color: red; font-size: 17px;">*</span>
                                            </label>
                                            <textarea name="address" class="w-full border rounded-md p-2 mt-1" placeholder="Enter address">{{ old('address') }}</textarea>
                                            @error('address')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">City <span style="color: red; font-size: 17px;">*</span></label>
                                            <input type="text" name="city" class="w-full border rounded-md p-2 mt-1"
                                                placeholder="Select city" value="{{ old('city') }}" />
                                            @error('city')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">State <span style="color: red; font-size: 17px;">*</span></label>
                                            <input type="text" name="state" class="w-full border rounded-md p-2 mt-1"
                                                placeholder="Select state" value="{{ old('state') }}" />
                                            @error('state')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">Country <span style="color: red; font-size: 17px;">*</span></label>
                                            <input type="text" name="country" class="w-full border rounded-md p-2 mt-1"
                                                placeholder="Select country" value="{{ old('country') }}" />
                                            @error('country')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">Pin Code <span style="color: red; font-size: 17px;">*</span></label>
                                            <input type="text" name="pin_code"
                                                class="w-full border rounded-md p-2 mt-1"
                                                placeholder="Enter pin code"
                                                value="{{ old('pin_code') }}"
                                                maxlength="5"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                inputmode="numeric" />
                                            @error('pin_code')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="flex justify-end">
                                            <button type="button" onclick="showStep(2)" class="bg-blue-700 text-white px-6 py-2 rounded-md mt-3">Next</button>
                                        </div>
                                    
                                    </div>

                                    <!-- Step 2: Education -->
                                    <div id="step-2" class="step hidden">
                                        @php
                                            $educationData = old('high_education') ?? [null];
                                        @endphp

                                        <div id="education-container" class="col-span-2 grid grid-cols-2 gap-4">
                                            @foreach($educationData as $i => $value)
                                            <div class="education-entry grid grid-cols-2 gap-4 col-span-2 p-4 rounded-md relative border border-gray-300">
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                        Highest qualification <span style="color: red; font-size: 17px;">*</span>
                                                    </label>
                                                    <input type="text" name="high_education[]" 
                                                        class="w-full border border-gray-300 rounded-md p-2"
                                                        value="{{ old("high_education.$i") }}" 
                                                        placeholder="Enter highest qualification">
                                                    @error("high_education.$i")
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                        Field of study <span style="color: red; font-size: 17px;">*</span>
                                                    </label>
                                                    <input type="text" name="field_of_study[]" 
                                                        class="w-full border border-gray-300 rounded-md p-2"
                                                        value="{{ old("field_of_study.$i") }}" 
                                                        placeholder="Enter field of study">
                                                    @error("field_of_study.$i")
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                        Institution name <span style="color: red; font-size: 17px;">*</span>
                                                    </label>
                                                    <input type="text" name="institution[]" 
                                                        class="w-full border border-gray-300 rounded-md p-2"
                                                        value="{{ old("institution.$i") }}" 
                                                        placeholder="Enter institution name">
                                                    @error("institution.$i")
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                        Graduation year <span style="color: red; font-size: 17px;">*</span>
                                                    </label>
                                                    <input type="number" name="graduate_year[]" class="w-full border border-gray-300 rounded-md p-2"
                                                        value="{{ old("graduate_year.$i") }}" placeholder="Enter graduation year (e.g. 2022 / Before 2000)">
                                                    @error("graduate_year.$i")
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <button type="button" 
                                                        class="remove-education absolute top-2 right-2 text-red-600 font-bold text-lg" 
                                                        style="{{ $i == 0 ? 'display:none;' : 'display:block;' }}">×</button>
                                            </div>
                                            @endforeach
                                        </div>

                                        <div class="col-span-2">
                                            <button type="button" id="add-education" class="text-green-600 text-sm mt-3">+ Add Education</button>
                                        </div>

                                        <div class="col-span-2 flex justify-between mt-4">
                                            <button type="button" onclick="showStep(1)" class="px-4 py-2 border rounded-md">Back</button>
                                            <button type="button" onclick="showStep(3)" class="bg-blue-700 text-white px-6 py-2 rounded-md">Next</button>
                                        </div>
                                    </div>


                                    <!-- Step 3: Work Experience -->
                                    <div id="step-3" class="step hidden">
                                       @php
                                        $workCount = count(old('job_role', [null]));
                                        @endphp

                                        <div id="work-container" class="col-span-2 grid grid-cols-2 gap-4">
                                            @for ($i = 0; $i < $workCount; $i++)
                                                @php
                                                    $isWorking = old("currently_working.$i") == 'on';
                                                @endphp
                                                <div
                                                    class="work-entry grid grid-cols-2 gap-4 col-span-2 p-4 rounded-md relative border border-gray-300"
                                                    x-data="{
                                                        working: {{ $isWorking ? 'true' : 'false' }},
                                                        index: {{ $i }},
                                                        init() {
                                                            this.$watch('working', value => {
                                                                const entries = document.querySelectorAll('.work-entry');
                                                                entries.forEach((entry, idx) => {
                                                                    const checkbox = entry.querySelector('.currently-working-checkbox');
                                                                    const endInput = entry.querySelector('.datepicker-end');

                                                                    if (value) {
                                                                        // If this checkbox is checked, disable all others
                                                                        if (idx !== this.index) {
                                                                            checkbox.disabled = true;
                                                                            checkbox.checked = false;
                                                                            if (entry.__x) entry.__x.$data.working = false;
                                                                        } else {
                                                                            endInput.value = '';
                                                                            endInput.readOnly = true;
                                                                            endInput.disabled = true;
                                                                        }
                                                                    } else {
                                                                        // Enable all checkboxes and date inputs
                                                                        checkbox.disabled = false;
                                                                        endInput.readOnly = false;
                                                                        endInput.disabled = false;
                                                                    }
                                                                });
                                                            });
                                                        }
                                                    }"
                                                    x-init="init()"
                                                >

                                                    {{-- Job Role --}}
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Job Title <span style="color: red; font-size: 17px;">*</span><span style="color: red; font-size: 17px;">*</span></label>
                                                        <input type="text" name="job_role[]" class="w-full border rounded-md p-2"
                                                            placeholder="e.g. Software Engineer" value="{{ old("job_role.$i") }}" />
                                                        @error("job_role.$i")
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    {{-- Organization --}}
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Organization <span style="color: red; font-size: 17px;">*</span></label>
                                                        <input type="text" name="organization[]" class="w-full border rounded-md p-2"
                                                            placeholder="e.g. ABC Corp" value="{{ old("organization.$i") }}" />
                                                        @error("organization.$i")
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    {{-- Start Date --}}
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                                            Started From <span style="color: red; font-size: 17px;">*</span>
                                                        </label>
                                                        <input 
                                                            type="date" 
                                                            name="starts_from[]" 
                                                            class="w-full border rounded-md p-2" 
                                                            value="{{ old('starts_from.' . $i) }}" 
                                                        />
                                                        @error("starts_from.$i")
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <!-- Alpine.js CDN -->
                                                    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

                                                    {{-- End Date & Checkbox --}}
                                                    <div x-data="{ working: {{ $isWorking ? 'true' : 'false' }}, endDate: '{{ old("end_to.$i") }}' }">
                                                        <!-- Label -->
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                                            To <span style="color: red; font-size: 17px;">*</span>
                                                        </label>

                                                        <!-- Date Input -->
                                                        <input type="date"
                                                            name="end_to[]"
                                                            class="w-full border rounded-md p-2 datepicker-end"
                                                            x-bind:disabled="working"
                                                            x-bind:readonly="working"
                                                            x-bind:value="working ? '' : endDate"
                                                            x-on:change="endDate = $event.target.value">

                                                        <!-- Error Message -->
                                                        @error("end_to.$i")
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror

                                                        <!-- Checkbox -->
                                                        <label class="inline-flex items-center mt-2 space-x-2">
                                                            <input type="checkbox"
                                                                name="currently_working[{{ $i }}]"
                                                                class="currently-working-checkbox"
                                                                x-model="working">
                                                            <span>I currently work here</span>
                                                        </label>
                                                    </div>


                                                    {{-- Remove Button --}}
                                                    <button type="button"
                                                        class="remove-work absolute top-2 right-2 text-red-600 font-bold text-lg"
                                                        style="{{ $i == 0 ? 'display:none;' : '' }}">&times;</button>
                                                </div>
                                            @endfor
                                        </div>

                                       
                                        <div class="col-span-2">
                                            <button type="button" id="add-work" class="text-green-600 text-sm mt-3">+ Add Work Experience</button>
                                        </div>

                                        <div class="col-span-2 flex justify-between mt-4">
                                            <button type="button" onclick="showStep(2)" class="px-4 py-2 border rounded-md">Back</button>
                                            <button type="button" onclick="showStep(4)" class="bg-blue-700 text-white px-6 py-2 rounded-md">Next</button>
                                        </div>
                                    </div>
                                    
                                    <!-- Step 4: Skills -->
                                    <div id="step-4" class="step hidden">
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Skills <span style="color: red; font-size: 17px;">*</span></label>
                                            <input type="text" name="training_skills" class="w-full border rounded-md p-2 mt-1" placeholder="e.g. Communication, Leadership, Python, Cloud Computing" value="{{old('training_skills')}}"/>
                                            @error('training_skills')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">Area Of Interest <span style="color: red; font-size: 17px;">*</span></label>
                                            <select name="area_of_interest" class="w-full border rounded-md p-2 mt-1">
                                                <option value="">-- Select Area of Interest --</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->category }}" {{ old('area_of_interest') == $category->category ? 'selected' : '' }}>
                                                        {{ $category->category }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('area_of_interest')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">Job Category <span style="color: red; font-size: 17px;">*</span></label>
                                            <input type="text" name="job_category" class="w-full border rounded-md p-2 mt-1"
                                                placeholder="e.g. Communication, Leadership, Python, Cloud Computing" value="{{ old('job_category') }}" />
                                            @error('job_category')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>


                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">Website Link</label>
                                            <input type="url" name="website_link" class="w-full border rounded-md p-2 mt-1" placeholder="e.g. https://www.example.com" value="{{old('website_link')}}"/>
                                            @error('website_link')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">Portfolio Link</label>
                                            <input type="url" name="portfolio_link" class="w-full border rounded-md p-2 mt-1" placeholder="e.g. https://portfolio.example.com" value="{{old('portfolio_link')}}"/>
                                            @error('portfolio_link')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="flex justify-between">
                                            <button type="button" onclick="showStep(3)" class="px-4 py-2 border rounded-md mt-3">
                                                Back
                                            </button>
                                            <button type="button" onclick="showStep(5)" class="bg-blue-700 text-white px-6 py-2 rounded-md mt-3">
                                                Next
                                            </button>
                                        </div>
                                   
                                    </div>

                                    <!-- Step 5: Additional Information -->
                                    <div id="step-5" class="step hidden">
                                   
                                        {{-- <div>
                                            <label class="block text-sm font-medium mb-1">Price Per Slot <span style="color: red; font-size: 17px;">*</span></label>
                                            <div class="flex gap-2 items-center">
                                                <input type="file" name="resume" accept="application/pdf"  class="border rounded-md p-2 w-full text-sm mt-1" />
                                            </div>
                                            @error('resume')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div> --}}
                                        <!-- Upload Resume -->
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Upload resume <span style="color: red; font-size: 17px;">*</span></label>
                                            <div class="flex gap-2 items-center">
                                                <input type="file" name="resume" accept="application/pdf"  class="border rounded-md p-2 w-full text-sm mt-1" />
                                            </div>
                                            @error('resume')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Upload Profile Picture -->
                                        <div>
                                        <label class="block text-sm font-medium mb-1 mt-3">Upload profile picture <span style="color: red; font-size: 17px;">*</span></label>
                                        <div class="flex gap-2 items-center">
                                            <input type="file" name="profile_picture"  class="border rounded-md p-2 w-full text-sm mt-1" />
                                        </div>
                                        @error('profile_picture')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        </div>

                                        <div>
                                        <label class="block text-sm font-medium mb-1 mt-3">Upload training certificate <span style="color: red; font-size: 17px;">*</span></label>
                                        <div class="flex gap-2 items-center">
                                            <input type="file" name="training_certificate" class="border rounded-md p-2 w-full text-sm mt-1" />
                                        </div>
                                        @error('training_certificate')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        </div>

                                        <div class="text-sm mt-3">
                                            <label class="flex items-start gap-2">
                                            <input type="checkbox" id="termsCheckbox" name="terms" {{ old('terms') ? 'checked' : '' }}></input>
                                            <span>
                                                I have read and agreed to 
                                                <a href="#" class="text-blue-600 underline">terms and conditions</a>
                                                <ul class="list-disc ml-5 mt-1 space-y-1 text-gray-700">
                                                    <li>Mentors must create an account to publish courses.</li>
                                                    <li>Uploaded content must be original or properly licensed.</li>
                                                    <li>Mentors are responsible for course quality and accuracy.</li>
                                                    <li>Earnings depend on enrollments and platform policies.</li>
                                                    <li>Platform may send updates or promotional notifications.</li>
                                                    <li>Courses must comply with content and refund policies.</li>
                                                </ul>
                                            </span>

                                            </label>
                                        </div>
                                        <div class="flex justify-between">
                                        <button type="button" onclick="showStep(4)" class="px-4 py-2 border rounded-md mt-3">Back</button>
                                        <button type="submit" id="submitBtn" class="bg-blue-600 text-white px-6 py-2 rounded-md mt-3">
                                            Submit
                                        </button>

                                    
                                    </div>
                                </form> 
                            </div>
                            <!-- </div> -->
                            </div>
                        </div>
                    </div>
 	        </div>


<!-- <script>
    // === Education Section ===
    const educationContainer = document.getElementById('education-container');
    const addEducationBtn = document.getElementById('add-education');

    addEducationBtn.addEventListener('click', () => {
        const firstEntry = educationContainer.querySelector('.education-entry');
        if (!firstEntry) return;

        const clone = firstEntry.cloneNode(true);

        // Clear all inputs and selects
        clone.querySelectorAll('input').forEach(input => input.value = '');
        clone.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

        // Show remove button
        const removeBtn = clone.querySelector('.remove-education');
        if (removeBtn) removeBtn.style.display = 'block';

        educationContainer.appendChild(clone);
    });

    educationContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-education')) {
            const entry = e.target.closest('.education-entry');
            if (entry) entry.remove();
        }
    });

    // === Work Experience Section ===
    const workContainer = document.getElementById('work-container');
    const addWorkBtn = document.getElementById('add-work');

    addWorkBtn.addEventListener('click', () => {
        const firstEntry = workContainer.querySelector('.work-entry');
        if (!firstEntry) return;

        const clone = firstEntry.cloneNode(true);

        // Clear all inputs
        clone.querySelectorAll('input').forEach(input => input.value = '');

        // Show remove button
        const removeBtn = clone.querySelector('.remove-work');
        if (removeBtn) removeBtn.style.display = 'block';

        workContainer.appendChild(clone);
    });

    workContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-work')) {
            const entry = e.target.closest('.work-entry');
            if (entry) entry.remove();
        }
    });
</script> -->

<!-- multiple Education -->
<script>
    const educationContainer = document.getElementById('education-container');

    function updateQualificationOptions() {
        // Get all selected values
        const selectedValues = Array.from(educationContainer.querySelectorAll('select[name="high_education[]"]'))
            .map(select => select.value)
            .filter(val => val !== '');

        // Get all qualification selects
        const allSelects = educationContainer.querySelectorAll('select[name="high_education[]"]');

        allSelects.forEach(select => {
            const currentValue = select.value;

            // Show all options first
            Array.from(select.options).forEach(option => {
                if (option.value !== "") {
                    option.style.display = "block";
                }
            });

            // Now hide already selected values in other selects
            selectedValues.forEach(value => {
                if (value !== currentValue) {
                    const optionToHide = select.querySelector(`option[value="${value}"]`);
                    if (optionToHide) optionToHide.style.display = "none";
                }
            });
        });
    }

    // Initial call
    updateQualificationOptions();

    // Add change event listener to update dropdowns when any value is selected
    educationContainer.addEventListener('change', (e) => {
        if (e.target.name === "high_education[]") {
            updateQualificationOptions();
        }
    });

    // When you add new education entry, make sure to call updateQualificationOptions after adding it
    const addEducationBtn = document.getElementById('add-education');
    addEducationBtn.addEventListener('click', () => {
        const firstEntry = educationContainer.querySelector('.education-entry');
        const clone = firstEntry.cloneNode(true);

        // Clear values
        clone.querySelectorAll('input').forEach(input => input.value = '');
        clone.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
        clone.querySelectorAll('p.text-red-600').forEach(error => error.remove());

        // Show remove button
        clone.querySelector('.remove-education').style.display = 'block';

        // Append clone
        educationContainer.appendChild(clone);

        // Apply validation rules if needed
        setTimeout(() => {
            const container = $(clone);
            container.find('select[name="high_education[]"]').rules('add', {
                required: true,
                messages: { required: "Please select qualification" }
            });
            container.find('select[name="field_of_study[]"]').rules('add', {
                required: true,
                messages: { required: "Please select field of study" }
            });
            container.find('input[name="institution[]"]').rules('add', {
                required: true,
                messages: { required: "Institution name is required" }
            });
            container.find('select[name="graduate_year[]"]').rules('add', {
                required: true,
                messages: { required: "Graduation year is required" }
            });

            updateQualificationOptions(); // <=== Important
        }, 100);
    });

    // Remove entry
    educationContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-education')) {
            const entry = e.target.closest('.education-entry');
            entry.remove();
            updateQualificationOptions(); // <=== Important
        }
    });
</script>
<!-- Multiple exprience  -->    
<script>
    const workContainer = document.getElementById('work-container');
    const addWorkBtn = document.getElementById('add-work');

    addWorkBtn.addEventListener('click', () => {
        const firstEntry = workContainer.querySelector('.work-entry');
        const clone = firstEntry.cloneNode(true);

        // Reset fields
        clone.querySelectorAll('input').forEach(input => {
            if (input.type === 'checkbox') {
                input.checked = false;
                input.disabled = false;
            } else {
                input.value = '';
                input.readOnly = false;
                input.disabled = false;
            }
        });

        // Remove old validation errors
        clone.querySelectorAll('p.text-red-600').forEach(error => error.remove());
        clone.querySelector('.remove-work').style.display = 'block';

        workContainer.appendChild(clone);
        Alpine.initTree(clone);

        // Add validation rules
        setTimeout(() => {
            const container = $(clone);

            container.find('input[name="job_title[]"]').rules('add', {
                required: true,
                messages: { required: "Job title is required" }
            });
            container.find('input[name="company_name[]"]').rules('add', {
                required: true,
                messages: { required: "Company name is required" }
            });
            container.find('input[name="start_date[]"]').rules('add', {
                required: true,
                messages: { required: "Start date is required" }
            });
            container.find('input[name="end_date[]"]').rules('add', {
                required: true,
                messages: { required: "End date is required" }
            });
        }, 100);
    });

    workContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-work')) {
            const entry = e.target.closest('.work-entry');
            entry.remove();
        }
    });

</script>


<script>
    function showStep(step) {
    for (let i = 1; i <= 5; i++) {
        document.getElementById(`step-${i}`).classList.add('hidden');
        document.getElementById(`step-${i}-circle`).classList.remove('bg-blue-600', 'text-white');
    }
    document.getElementById(`step-${step}`).classList.remove('hidden');
    document.getElementById(`step-${step}-circle`).classList.add('bg-blue-600', 'text-white');
    }
</script>


 <script>
    const checkbox = document.getElementById('termsCheckbox');
    const submitBtn = document.getElementById('submitBtn');

    checkbox.addEventListener('change', function () {
        if (this.checked) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    });
</script>                                   
  <script>
    $(document).ready(function () {
        var $submitBtn = $('#submitBtn');
        var $form = $('form');
        var $checkbox = $('#termsCheckbox'); 

        function toggleSubmitButton() {
            if ($checkbox.is(':checked')) {
                $submitBtn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
            } else {
                $submitBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
            }
        }

        // Always run this on page load (including error reloads)
        toggleSubmitButton();

        // On checkbox click, enable/disable submit
        $checkbox.on('change', function () {
            toggleSubmitButton();
        });

        // Prevent multiple submits
        $form.on('submit', function (e) {
            if ($submitBtn.prop('disabled')) {
                e.preventDefault();
                return;
            }

            $submitBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
        });
    });
</script>          
<script>
    $(document).ready(function () {
        // Initialize single DOB datepicker
        $('#dob').datepicker({
            format: 'yyyy-mm-dd',
            endDate: new Date(),
            autoclose: true,
            todayHighlight: true
        });

        // Function to initialize all start/end datepickers
        function initializeDatePickers() {
            $('.datepicker-start, .datepicker-end').each(function () {
                // Destroy any previously attached datepicker instance
                $(this).datepicker('destroy').datepicker({
                    format: 'yyyy-mm-dd',
                    endDate: new Date(),
                    autoclose: true,
                    todayHighlight: true
                });
            });
        }

        // Initial run
        initializeDatePickers();

        // On dynamically adding work sections
        $('#add-work').on('click', function () {
            // Delay to ensure DOM is updated before initializing
            setTimeout(() => {
                initializeDatePickers();
            }, 100);
        });
    });

    // Alpine.js integration
    document.addEventListener('alpine:init', () => {
        Alpine.effect(() => {
            setTimeout(() => {
                // Initialize all active datepickers Alpine might have added
                $('.datepicker-start, .datepicker-end').each(function () {
                    $(this).datepicker('destroy').datepicker({
                        format: 'yyyy-mm-dd',
                        endDate: new Date(),
                        autoclose: true,
                        todayHighlight: true
                    });
                });
            }, 100);
        });
    });
</script>


<!-- Step 2: jQuery Validation Plugin -->
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<!-- all step field click on next button-->
<script>
    $(document).ready(function () {
        const form = $('#multiStepForm');

        form.validate({
            ignore: [],
            rules: {
                // Step 1 - Personal Info
                name: "required",
                gender: "required",
                dob: "required",
                national_id: "required",
                address: "required",
                city: "required",
                state: "required",
                country: "required",
                pin_code: "required",

                // Step 2 - Education
                'high_education[]': { required: true },
                'field_of_study[]': { required: true },
                'institution[]': { required: true },
                'graduate_year[]': { required: true },

                // Step 3 - Work Experience
                'job_role[]': { required: true },
                'organization[]': { required: true },
                'starts_from[]': { required: true },
                'end_to[]': {
                    required: function (element) {
                        const parent = $(element).closest('.work-entry');
                        const isWorking = parent.find('.currently-working-checkbox').prop('checked');
                        return !isWorking;
                    }
                },

                // Step 4 - Skills & Interest
                training_skills: "required",
                area_of_interest: "required",
                job_category: "required",
                

                // Step 5 - Uploads & Terms
                resume: "required",
                profile_picture: "required",
                training_certificate: "required",
                
            },

            messages: {
                // Step 1
                name: "Full name is required",
                gender: "Please select gender",
                dob: "Date of birth is required",
                national_id: "National ID is required",
                address: "Address is required",
                city: "City is required",
                state: "State is required",
                country: "Country is required",
                pin_code: "Pin code is required",

                // Step 2
                'high_education[]': "Please select qualification",
                'field_of_study[]': "Please select field of study",
                'institution[]': "Institution name is required",
                'graduate_year[]': "Graduation year is required",

                // Step 3
                'job_role[]': "Job title is required",
                'organization[]': "Organization is required",
                'starts_from[]': "Start date is required",
                'end_to[]': "End date is required unless currently working",

                // Step 4
                training_skills: "Please enter your skills",
                area_of_interest: "Please select an area of interest",
                job_category: "Job category is required",
                

                // Step 5
                resume: "Please upload your resume",
                profile_picture: "Please upload a profile picture",
                training_certificate: "Please upload a training certificate",
            
            },

            errorElement: 'p',
            errorPlacement: function (error, element) {
                error.addClass('text-red-600 text-sm mt-1');
                error.insertAfter(element);
            }
        });

        // Step navigation with validation check
        window.showStep = function (step) {
            const currentStep = $('.step:visible');
            let valid = true;

            currentStep.find('input, select, textarea').each(function () {
                if (!$(this).valid()) {
                    valid = false;
                }
            });

            if (!valid) return;

            for (let i = 1; i <= 5; i++) {
                $(`#step-${i}`).addClass('hidden');
                $(`#step-${i}-circle`).removeClass('bg-blue-600 text-white');
            }

            $(`#step-${step}`).removeClass('hidden');
            $(`#step-${step}-circle`).addClass('bg-blue-600 text-white');
        };

    });
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


</body>


<!-- Mirrored from thewebmax.org/jobzilla/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 May 2025 07:18:30 GMT -->
</html>
