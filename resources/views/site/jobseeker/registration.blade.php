@php
    $email = session('email');
    $phone = session('phone_number');
@endphp

@include('site.componants.header')

<body>
    <!-- LOADING AREA START ===== -->
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
                                        <div id="step-STEP_NUM-circle"
                                            class="w-8 h-8 rounded-full border-2 flex items-center justify-center">
                                            STEP_NUM</div>
                                        <span class="text-xs mt-1 text-center">STEP_LABEL</span>
                                    </div>
                                </template>

                                <div class="flex flex-col items-center text-blue-600 cursor-pointer"
                                    onclick="showStep(1)">
                                    <div id="step-1-circle"
                                        class="w-8 h-8 rounded-full border-2 border-blue-600 bg-blue-600 text-white flex items-center justify-center">
                                        1</div>
                                    <span class="text-xs mt-1 text-center">Personal<br />information</span>
                                </div>
                                <div class="flex-1 h-px bg-gray-300 mx-2"></div>

                                <div class="flex flex-col items-center text-blue-600 cursor-pointer"
                                    onclick="showStep(2)">
                                    <div id="step-2-circle"
                                        class="w-8 h-8 rounded-full border-2 border-blue-600 flex items-center justify-center">
                                        2</div>
                                    <span class="text-xs mt-1 text-center">{{ langLabel('educational') }}<br />details</span>
                                </div>
                                <div class="flex-1 h-px bg-gray-300 mx-2"></div>

                                <div class="flex flex-col items-center text-blue-600 cursor-pointer"
                                    onclick="showStep(3)">
                                    <div id="step-3-circle"
                                        class="w-8 h-8 rounded-full border-2 border-blue-600 flex items-center justify-center">
                                        3</div>
                                    <span class="text-xs mt-1 text-center">{{ langLabel('work') }}<br />experience</span>
                                </div>
                                <div class="flex-1 h-px bg-gray-300 mx-2"></div>

                                <div class="flex flex-col items-center text-blue-600 cursor-pointer"
                                    onclick="showStep(4)">
                                    <div id="step-4-circle"
                                        class="w-8 h-8 rounded-full border-2 border-blue-600 flex items-center justify-center">
                                        4</div>
                                    <span class="text-xs mt-1 text-center">{{ langLabel('skills') }} &<br />{{ langLabel('training') }}</span>
                                </div>
                                <div class="flex-1 h-px bg-gray-300 mx-2"></div>

                                <div class="flex flex-col items-center text-blue-600 cursor-pointer"
                                    onclick="showStep(5)">
                                    <div id="step-5-circle"
                                        class="w-8 h-8 rounded-full border-2 border-blue-600 flex items-center justify-center">
                                        5</div>
                                    <span class="text-xs mt-1 text-center">{{ langLabel('additional') }}<br />information</span>
                                </div>
                            </div>

                            <!-- Steps Content -->
                            <form class="space-y-6" id="multiStepForm" action="{{ route('jobseeker.registration.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                               
                               
  <!-- Step 1: Personal Info -->
                                <div id="step-1" class="step">

                                    <div>
                                        <label class="block mb-1 text-sm font-medium">{{ langLabel('full_name') }} <span style="color: red; font-size: 17px;">*</span></label>
                                        <input type="text" name="name" class="w-full border rounded-md p-2 mt-1"
                                            placeholder="{{ langLabel('enter_full_name') }}" value="{{ old('name') }}" oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')"/>
                                        @error('name')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('email') }} <span style="color: red; font-size: 17px;">*</span></label>
                                            <input placeholder="{{ langLabel('enter_email') }}" name="email" type="email"
                                                class="w-full border rounded-md p-2 mt-1" value="{{ old('email', $email) }}"
                                                readonly />
                                            @error('email')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('gender') }} <span style="color: red; font-size: 17px;">*</span></label>
                                            <select name="gender" id="gender" class="w-full border rounded-md p-2 mt-1">
                                                <option value="">{{ langLabel('select_gender') }}</option>
                                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>{{ langLabel('male') }}
                                                </option>
                                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>
                                                    {{ langLabel('female') }}</option>
                                            </select>
                                        @error('gender')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        </div>
                                        
                                    </div>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                                <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('phone_no') }} <span style="color: red; font-size: 17px;">*</span></label>
                                                <div class="flex">
                                                <select class="w-1/3 border rounded-l-md p-2 mt-1" name="phone_code">
                                                    <option value="+966">+966</option>
                                                    <!-- <option value="+971">+971</option> -->
                                                    <!-- <option value="+973">+973</option> -->
                                                </select>
                                                <input 
                                                    placeholder="{{ langLabel('enter_phone_number') }}" 
                                                    name="phone_number" 
                                                    type="tel" 
                                                    class="w-2/3 border rounded-r-md p-2 mt-1"
                                                    value="{{ old('phone_number') }}"
                                                    maxlength="9" 
                                                    pattern="[0-9]{9}" 
                                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,9)" 
                                                    required
                                                />
                                                
                                            </div>
                                            @error('phone_number')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                          
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">{{ langLabel('dob') }} <span style="color: red; font-size: 17px;">*</span></label>
                                            <input type="date" name="dob" id="dob" class="w-full border rounded-md p-2 mt-1"
                                                value="{{ old('dob') }}" max="{{ date('Y-m-d') }}"/>
                                            @error('dob')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-6 mt-3">
                                        <div class="col-span-2">
                                            <label class="block mb-1 text-sm font-medium">{{ langLabel('national_id_number') }} <span style="color: red; font-size: 17px;">*</span></label>
                                            <!-- <span class="text-xs text-blue-600">
                                                National ID should start with 1 for male and 2 for female.
                                            </span> -->
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

                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">
                                            {{ langLabel('address') }} <span style="color: red; font-size: 17px;">*</span>
                                        </label>
                                        <textarea name="address" rows="3"
                                            class="w-full border rounded-md p-2 mt-1"
                                            placeholder="Street, Area">{{ old('address') }}</textarea>
                                        @error('address')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>


                                    <!-- Country Input -->
                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">
                                            {{ langLabel('country') }} <span style="color: red;">*</span>
                                        </label>
                                        <input type="text" id="country" name="country" 
                                            class="w-full border rounded-md p-2" 
                                            placeholder="Enter country" value="{{ old('country') }}">
                                    </div>

                                    <!-- State Input -->
                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">
                                            {{ langLabel('state') }} <span style="color: red;">*</span>
                                        </label>
                                        <input type="text" id="state" name="state" 
                                            class="w-full border rounded-md p-2" 
                                            placeholder="Enter state" value="{{ old('state') }}">
                                    </div>

                                    <!-- City Input -->
                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">
                                            {{ langLabel('city') }} <span style="color: red;">*</span>
                                        </label>
                                        <input type="text" id="city" name="city" 
                                            class="w-full border rounded-md p-2" 
                                            placeholder="Enter city" value="{{ old('city') }}">
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
                                        <button type="button" onclick="showStep(2)"
                                            class="bg-blue-700 text-white px-6 py-2 rounded-md mt-3">Next</button>
                                    </div>

                                </div>

                                <!-- Step 2: Education -->
                                <div id="step-2" class="step hidden">

                                    <!-- Container for multiple education entries -->
                                    @php
                                        $educationCount = count(old('high_education', [null]));
                                    @endphp

                                    <div id="education-container" class="col-span-2 grid grid-cols-2 gap-4">
                                        @for ($i = 0; $i < $educationCount; $i++)
                                            <div
                                                class="education-entry grid grid-cols-2 gap-4 col-span-2 p-4 rounded-md relative border border-gray-300">

                                                {{-- Highest Qualification --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                        Highest qualification <span style="color: red; font-size: 17px;">*</span>
                                                    </label>
                                                    <input type="text" name="high_education[]" 
                                                        class="w-full border border-gray-300 rounded-md p-2"
                                                        value="{{ old("high_education.$i") }}"
                                                        placeholder="Enter your highest qualification">
                                                    @error("high_education.$i")
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                {{-- Field of Study --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                        Field of study <span style="color: red; font-size: 17px;">*</span>
                                                    </label>
                                                    <input type="text" name="field_of_study[]" 
                                                        class="w-full border border-gray-300 rounded-md p-2"
                                                        value="{{ old("field_of_study.$i") }}"
                                                        placeholder="Enter your field of study">
                                                    @error("field_of_study.$i")
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                {{-- Institution Name --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                        Institution name <span style="color: red; font-size: 17px;">*</span>
                                                    </label>
                                                    <input name="institution[]" type="text"
                                                        class="w-full border border-gray-300 rounded-md p-2"
                                                        value="{{ old("institution.$i") }}"
                                                        placeholder="Enter institution name" />
                                                    @error("institution.$i")
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                {{-- Graduation Year --}}
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
                                                    style="{{ $i == 0 ? 'display:none;' : '' }}">&times;</button>
                                            </div>
                                        @endfor
                                    </div>

                                    <div class="col-span-2">
                                        <button type="button" id="add-education" class="text-green-600 text-sm mt-2 mb-2">
                                            Add education +
                                        </button>
                                    </div>

                                    <div class="col-span-2 flex justify-between">
                                        <button type="button" onclick="showStep(1, false)" class="px-4 py-2 border rounded-md">Back</button>
                                        <button type="button" onclick="showStep(3, true)" class="bg-blue-700 text-white px-6 py-2 rounded-md">Next</button>
                                    </div>
                                </div>

                             
                                <!-- Step 3: Work Experience -->
                                <div id="step-3" class="step hidden">
                                    <div id="work-container" class="col-span-2 grid grid-cols-2 gap-4">
                                        @php
                                            $workCount = count(old('job_role', [null]));
                                        @endphp

                                        @for ($i = 0; $i < $workCount; $i++)
                                            <div class="work-entry grid grid-cols-2 gap-4 col-span-2 p-4 rounded-md relative border border-gray-300">

                                                {{-- Job Title --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                        Job Title <span style="color: red;">*</span>
                                                    </label>
                                                    <input type="text" name="job_role[]" class="w-full border border-gray-300 rounded-md p-2"
                                                        value="{{ old("job_role.$i") }}" placeholder="e.g. Software Engineer">
                                                    @error("job_role.$i")
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                {{-- Organization --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                        Organization <span style="color: red;">*</span>
                                                    </label>
                                                    <input type="text" name="organization[]" class="w-full border border-gray-300 rounded-md p-2"
                                                        value="{{ old("organization.$i") }}" placeholder="e.g. ABC Corp">
                                                    @error("organization.$i")
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                {{-- Start Date --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                        Started From <span style="color: red;">*</span>
                                                    </label>
                                                    <input type="date" name="starts_from[]" class="w-full border border-gray-300 rounded-md p-2"
                                                        value="{{ old("starts_from.$i") }}" max="{{ date('Y-m-d') }}">
                                                    @error("starts_from.$i")
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                {{-- End Date + Checkboxes --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                        To <span style="color: red;">*</span>
                                                    </label>
                                                    <input type="date" name="end_to[]" class="w-full border border-gray-300 rounded-md p-2"
                                                        value="{{ old("end_to.$i") }}" max="{{ date('Y-m-d') }}">

                                                    <div class="mt-2 space-y-1">
                                                        <label class="inline-flex items-center space-x-2">
                                                            <input type="checkbox" name="currently_working[{{ $i }}]" value="1" class="currently-working-checkbox">
                                                            <span>I currently work here</span>
                                                        </label>
                                                    </div>
                                                </div>

                                                {{-- Remove Button --}}
                                                <button type="button"
                                                    class="remove-work absolute top-2 right-2 text-red-600 font-bold text-lg"
                                                    style="{{ $i == 0 ? 'display:none;' : '' }}">&times;</button>
                                            </div>
                                        @endfor
                                    </div>

                                    {{-- Add Work Button --}}
                                    <div class="col-span-2">
                                        <button type="button" id="add-work" class="text-green-600 text-sm mt-2 mb-2">Add work experience +</button>
                                    </div>

                                    {{-- Navigation --}}
                                    <div class="col-span-2 flex justify-between mt-4">
                                        <button type="button" onclick="showStep(2, false)" class="px-4 py-2 border rounded-md">Back</button>
                                        <button type="button" onclick="showStep(4, true)" class="bg-blue-700 text-white px-6 py-2 rounded-md">Next</button>
                                    </div>
                                </div>


                                <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
                                <script>
                                function workExperience() {
                                    return {
                                        workList: [
                                            @php
                                                $oldRoles = old('job_role', ['']);
                                                $oldOrgs = old('organization', ['']);
                                                $oldStarts = old('starts_from', ['']);
                                                $oldEnds = old('end_to', ['']);
                                                $oldWorking = old('currently_working', []);
                                            @endphp
                                            @foreach ($oldRoles as $i => $role)
                                            {
                                                job_role: "{{ $role }}",
                                                organization: "{{ $oldOrgs[$i] ?? '' }}",
                                                start: "{{ $oldStarts[$i] ?? '' }}",
                                                end: "{{ $oldEnds[$i] ?? '' }}",
                                                working: {{ isset($oldWorking[$i]) && $oldWorking[$i] == 'on' ? 'true' : 'false' }}
                                            },
                                            @endforeach
                                        ],

                                        addWork() {
                                            this.workList.push({ job_role:'', organization:'', start:'', end:'', working:false });
                                        },

                                        removeWork(index) {
                                            this.workList.splice(index, 1);
                                        },

                                        handleWorkingChange(index) {
                                            // Only one checkbox can be checked at a time
                                            this.workList.forEach((w, i) => {
                                                if(i !== index) w.working = false;
                                            });

                                            // Clear end date if currently working
                                            if(this.workList[index].working) this.workList[index].end = '';
                                        }
                                    }
                                }
                                </script>



                                <!-- Step 4: Skills -->
                                <div id="step-4" class="step hidden">

                                    <div>
                                        <label class="block mb-1 text-sm font-medium">Skills <span style="color: red; font-size: 17px;">*</span></label>
                                        <input type="text" name="skills" class="w-full border rounded-md p-2 mt-1"
                                            placeholder="e.g. AWS Certified, Python, Project Management"
                                            value="{{ old('skills') }}" required/>
                                        @error('skills')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">Area of Interests <span style="color: red; font-size: 17px;">*</span></label>
                                        <select class="w-full border rounded-md p-2 mt-1" name="interest">
                                            <option value="" disabled {{ old('interest') ? '' : 'selected' }}>Select an
                                                area</option>
                                            <option value="cloud-computing" {{ old('interest') == 'cloud-computing' ? 'selected' : '' }}>Cloud Computing</option>
                                            <option value="web-development" {{ old('interest') == 'web-development' ? 'selected' : '' }}>Web Development</option>
                                            <option value="data-science" {{ old('interest') == 'data-science' ? 'selected' : '' }}>Data Science</option>
                                            <option value="machine-learning" {{ old('interest') == 'machine-learning' ? 'selected' : '' }}>Machine Learning</option>
                                            <option value="cybersecurity" {{ old('interest') == 'cybersecurity' ? 'selected' : '' }}>Cybersecurity</option>
                                            <option value="digital-marketing" {{ old('interest') == 'digital-marketing' ? 'selected' : '' }}>Digital Marketing</option>
                                        </select>

                                        @error('interest')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">Job Categories <span style="color: red; font-size: 17px;">*</span></label>
                                        <input type="text" name="job_category" class="w-full border rounded-md p-2 mt-1"
                                            placeholder="e.g. Software Engineer, Data Analyst"
                                            value="{{ old('job_category') }}" required/>
                                        @error('job_category')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">Website Link <span style="color: red; font-size: 17px;">*</span></label>
                                        <input type="url" name="website_link" class="w-full border rounded-md p-2 mt-1" 
                                            placeholder="e.g. https://www.example.com"
                                            value="{{ old('website_link') }}" required/>
                                        @error('website_link')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">Portfolio Link<span style="color: red; font-size: 17px;">*</span></label>
                                        <input type="url" name="portfolio_link" class="w-full border rounded-md p-2" mt-1
                                            placeholder="e.g. https://portfolio.example.com"
                                            value="{{ old('portfolio_link') }}" required/>
                                        @error('portfolio_link')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="flex justify-between">
                                        <button type="button" onclick="showStep(3, false)" class="px-4 py-2 border rounded-md mt-3">
                                            Back
                                        </button>
                                        <button type="button" onclick="showStep(5, true)"
                                            class="bg-blue-700 text-white px-6 py-2 rounded-md mt-3">
                                            Next
                                        </button>
                                    </div>


                                </div>

                                @php
                                    $resume = App\Models\Resume::first();
                                @endphp
                                <!-- Step 5: Additional Information -->
                                <div id="step-5" class="step hidden">

                                    <!-- CV Template Download -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1">CV template
                                            <span class="text-xs text-gray-500">(Download CV template and make sure the
                                                template you upload must follow the attached template)</span>
                                        </label>
                                        <a href="{{ route('cv.template.download', 1) }}" 
                                        class="bg-blue-600 text-white px-3 py-1.5 rounded-md text-xs btn mt-2">
                                        Download CV template
                                        </a>


                                    </div>
                                    <!-- Upload Resume -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1 mt-3">
                                            Upload resume <span style="color: red;">*</span>
                                        </label>
                                        <div class="flex gap-2 items-center">
                                            <input type="file" id="resumeFile" name="resume" accept=".pdf,.doc,.docx,.txt"
                                                class="border rounded-md p-2 w-full text-sm" />
                                        </div>

                                        <!-- Resume -->
                                        <p id="resumeError" class="text-red-600 text-sm mt-1 min-h-[1.25rem]">
                                            @error('resume') {{ $message }} @enderror
                                        </p>


                                        @error('resume')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>


                                    <!-- Upload Profile Picture -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1 mt-3">
                                            Upload profile picture <span style="color: red;">*</span>
                                        </label>
                                        <div class="flex gap-2 items-center">
                                            <input type="file" id="profilePicture" name="profile_picture" accept="image/png, image/jpeg"
                                                class="border rounded-md p-2 w-full text-sm" />
                                        </div>
                                        <!-- Profile Picture -->
                                       <p id="profilePictureError" class="text-red-600 text-sm mt-1 min-h-[1.25rem]">
                                            @error('profile_picture') {{ $message }} @enderror
                                        </p>`
                                        @error('profile_picture')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="text-sm">
                                        <label class="flex items-start gap-2 mt-3">
                                            <input type="checkbox" id="termsCheckbox" name="terms" {{ old('terms') ? 'checked' : '' }}>

                                            <span>
                                                I have read and agreed to
                                                <a href="{{route('terms-and-conditions')}}" class="text-blue-600 underline">terms and conditions</a>
                                                <ul class="list-disc ml-5 mt-1 space-y-1 text-gray-700">
                                                    <li>Users must create an account to access full course materials and resources.</li>
                                                    <li>Course content is for personal learning use only and cannot be redistributed.</li>
                                                    <li>Progress and certification are based on course completion and assessment scores.</li>
                                                    <li>Platform may send notifications about new courses, updates, or promotions.</li>
                                                    <li>Refunds for paid courses are subject to platform’s refund policy.</li>
                                                </ul>
                                            </span>
                                        </label>
                                    </div>

                                    <div class="flex justify-between mt-4">
                                        <button type="button" onclick="showStep(4, false)" class="px-4 py-2 border rounded-md">Back</button>
                                        <button id="submitBtn" type="submit" disabled class="bg-blue-600 text-white px-6 py-2 rounded-md opacity-50 cursor-not-allowed">
                                            Submit
                                        </button>
                                    </div>

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


                                </div>
                            </form>
                            
                        </div>

                        


                        <!-- </div> -->
                    </div>
                </div>
            </div>


        </div>

        @include('site.jobseeker.componants.footer')

<!-- multiple Tabs - steps   -->
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
    $(document).ready(function () {
        const form = $('#multiStepForm');

        // === 1. Static field validation ===
        form.validate({
            ignore: [],
            errorElement: 'label',
            errorClass: 'text-red-600 text-sm mt-1',
            rules: {
                name: "required",
                gender: "required",
                dob: "required",
                national_id: "required",
                address: "required",
                city: "required",
                state: "required",
                country: "required",
                pin_code: "required",
                per_slot_price: "required",
                training_skills: "required",
                area_of_interest: "required",
                job_category: "required",
                resume: "required",
                profile_picture: "required",
                training_certificate: "required",
                phone_number: {
                    required: true,
                    digits: true,
                    minlength: 9,
                    maxlength: 9
                }
            },
            messages: {
                name: "Full name is required",
                gender: "Please select gender",
                dob: "Date of birth is required",
                national_id: "National ID is required",
                address: "Address is required",
                city: "City is required",
                state: "State is required",
                country: "Country is required",
                pin_code: "Pin code is required",
                per_slot_price: "Per slot price is required",
                training_skills: "Please enter your skills",
                area_of_interest: "Please select an area of interest",
                job_category: "Job category is required",
                resume: "Please upload your resume",
                profile_picture: "Please upload a profile picture",
                training_certificate: "Please upload a training certificate",
                phone_number: {
                    required: "Phone number is required",
                    digits: "Only numbers are allowed",
                    minlength: "Phone number must be exactly 9 digits",
                    maxlength: "Phone number must be exactly 9 digits"
                }
            },
            errorPlacement: function (error, element) {
                if (element.attr("type") === "file") {
                    error.insertAfter(element.closest('div'));
                } else if (element.attr("name") === "phone_number") {
                    error.insertAfter(element.closest('.flex'));
                } else {
                    error.insertAfter(element);
                }
            }
        });

        // === 2. Education validation for existing entries ===
        $('#education-container .education-entry input').each(function () {
            $(this).rules('add', {
                required: true,
                messages: { required: "This field is required" }
            });
        });

        // === 3. Work experience validation for existing entries ===
        $('#work-container .work-entry').each(function () {
            applyWorkValidation($(this));
        });

        // === 4. Add Education dynamically ===
        let educationIndex = $('#education-container .education-entry').length - 1;
        $('#add-education').click(function () {
            educationIndex++;
            const newBlock = $(`
                <div class="education-entry grid grid-cols-2 gap-4 col-span-2 p-4 rounded-md relative border border-gray-300">
                    <div>
                        <label>Highest qualification <span style="color: red;">*</span></label>
                        <input type="text" name="high_education[${educationIndex}]" class="w-full border rounded-md p-2" placeholder="Enter highest qualification">
                    </div>
                    <div>
                        <label>Field of study <span style="color: red;">*</span></label>
                        <input type="text" name="field_of_study[${educationIndex}]" class="w-full border rounded-md p-2" placeholder="Enter field of study">
                    </div>
                    <div>
                        <label>Institution name <span style="color: red;">*</span></label>
                        <input type="text" name="institution[${educationIndex}]" class="w-full border rounded-md p-2" placeholder="Enter institution name">
                    </div>
                    <div>
                        <label>Graduation year <span style="color: red;">*</span></label>
                        <input type="number" name="graduate_year[${educationIndex}]" class="w-full border rounded-md p-2" placeholder="Enter graduation year">
                    </div>
                    <button type="button" class="remove-education absolute top-2 right-2 text-red-600 font-bold">&times;</button>
                </div>
            `);

            $('#education-container').append(newBlock);

            newBlock.find('input').each(function () {
                $(this).rules('add', {
                    required: true,
                    messages: { required: "This field is required" }
                });
            });
        });

        $('#education-container').on('click', '.remove-education', function () {
            $(this).closest('.education-entry').remove();
        });

        // === 5. Add Work dynamically ===
        let workIndex = $('#work-container .work-entry').length - 1;

        $('#add-work').click(function () {
            workIndex++;
            const newBlock = $(`
                <div class="work-entry grid grid-cols-2 gap-4 col-span-2 p-4 rounded-md relative border border-gray-300">
                    <div>
                        <label>Job Title <span style="color: red;">*</span></label>
                        <input type="text" name="job_role[${workIndex}]" class="w-full border rounded-md p-2" placeholder="e.g. Software Engineer">
                    </div>
                    <div>
                        <label>Organization <span style="color: red;">*</span></label>
                        <input type="text" name="organization[${workIndex}]" class="w-full border rounded-md p-2" placeholder="e.g. ABC Corp">
                    </div>
                    <div>
                        <label>Started From <span style="color: red;">*</span></label>
                        <input type="date" name="starts_from[${workIndex}]" class="w-full border rounded-md p-2" max="${new Date().toISOString().split('T')[0]}">
                    </div>
                    <div>
                        <label>To <span style="color: red;">*</span></label>
                        <input type="date" name="end_to[${workIndex}]" class="w-full border rounded-md p-2" max="${new Date().toISOString().split('T')[0]}">
                        <div class="mt-2 space-y-1">
                            <label class="inline-flex items-center space-x-2">
                                <input type="checkbox" class="currently-working-checkbox" name="currently_working[${workIndex}]" value="1">
                                <span>I currently work here</span>
                            </label>
                        </div>
                    </div>
                    <button type="button" class="remove-work absolute top-2 right-2 text-red-600 font-bold">&times;</button>
                </div>
            `);

            $('#work-container').append(newBlock);
            applyWorkValidation(newBlock);
        });

        $('#work-container').on('click', '.remove-work', function () {
            $(this).closest('.work-entry').remove();
        });


        // === 7. Apply validation + checkbox logic for Work block ===
        function applyWorkValidation($block) {
            const $job = $block.find('input[name^="job_role"]');
            const $org = $block.find('input[name^="organization"]');
            const $start = $block.find('input[name^="starts_from"]');
            const $end = $block.find('input[name^="end_to"]');
            const $checkbox = $block.find('.currently-working-checkbox');

            $job.rules('add', { required: true, messages: { required: "Job title is required" } });
            $org.rules('add', { required: true, messages: { required: "Company name is required" } });
            $start.rules('add', { required: true, messages: { required: "Start date is required" } });

            // Default rule
            $end.rules('add', { required: true, messages: { required: "End date is required" } });

            // Checkbox change event
            $checkbox.change(function () {
                if ($(this).is(':checked')) {
                    // Disable + clear + remove validation
                    $end.prop('disabled', true).prop('readonly', true).val('');
                    $end.rules('remove', 'required');
                    $end.valid(); // 🔥 force revalidation to remove old error
                    $end.siblings('label.error').remove();
                } else {
                    // Enable + add validation
                    $end.prop('disabled', false).prop('readonly', false);
                    $end.rules('add', {
                        required: true,
                        messages: { required: "End date is required" }
                    });
                }
            });

            // When user selects an end date manually
            $end.on('input change', function () {
                if ($(this).val()) {
                    $checkbox.prop('checked', false).trigger('change'); // 🔥 uncheck if date chosen
                }
            });

            // Trigger initial state check
            $checkbox.trigger('change');
        }


        // === 8. Step navigation ===
        window.showStep = function (step, validate = true) {
            const currentStep = $('.step:visible');
            let valid = true;

            if (validate) {
                currentStep.find('input, select, textarea').each(function () {
                    if (!$(this).valid()) valid = false;
                });
                if (!valid) return;
            }

            // $('.step').addClass('hidden');
            // $('.step-circle').removeClass('bg-blue-600 text-white');
            // $(`#step-${step}`).removeClass('hidden');
            // $(`#step-${step}-circle`).addClass('bg-blue-600 text-white');
            for (let i = 1; i <= 5; i++) {
                $(`#step-${i}`).addClass('hidden');
                $(`#step-${i}-circle`).removeClass('bg-blue-600 text-white');
            }

            $(`#step-${step}`).removeClass('hidden');
            $(`#step-${step}-circle`).addClass('bg-blue-600 text-white');
        };
    });
</script>









<!-- Datapicker  -->
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
        }

        initializeDatePickers();

        $('#add-work').on('click', function () {
            
            initializeDatePickers(); 
        });
    });

    document.addEventListener('alpine:init', () => {
        Alpine.effect(() => {
            setTimeout(() => {
                $('.datepicker-end:not(:disabled)').datepicker(); // or flatpickr()
            }, 100);
        });
    });

</script>
<!-- Alpine.js v3 CDN -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    $(document).ready(function () {
        var $submitBtn = $('#submitBtn');
        var $form = $('form');
        var $checkbox = $('#termsCheckbox'); // Make sure this is the actual ID of your checkbox

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

        $('#gender').on('change', function () {
            const selectedGender = $(this).val();

            // Clear National ID field when gender is changed
            $('#national_id').val('');

            // Attach input event for validation
            $('#national_id').off('input').on('input', function () {
                validateNationalIdInput();
            });
        });
    });
</script>


<!-- Step 2: jQuery Validation Plugin -->
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>



<script src="https://unpkg.com/feather-icons"></script>
<script>
  feather.replace(); 
</script>
<style>
    input[type="file"] {
        display: block;
    }
    label.error {
        display: block;
        margin-top: 0.25rem;
    }

</style>