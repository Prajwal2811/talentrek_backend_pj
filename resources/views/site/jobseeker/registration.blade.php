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
                                    <span class="text-xs mt-1 text-center">Educational<br />details</span>
                                </div>
                                <div class="flex-1 h-px bg-gray-300 mx-2"></div>

                                <div class="flex flex-col items-center text-blue-600 cursor-pointer"
                                    onclick="showStep(3)">
                                    <div id="step-3-circle"
                                        class="w-8 h-8 rounded-full border-2 border-blue-600 flex items-center justify-center">
                                        3</div>
                                    <span class="text-xs mt-1 text-center">Work<br />experience</span>
                                </div>
                                <div class="flex-1 h-px bg-gray-300 mx-2"></div>

                                <div class="flex flex-col items-center text-blue-600 cursor-pointer"
                                    onclick="showStep(4)">
                                    <div id="step-4-circle"
                                        class="w-8 h-8 rounded-full border-2 border-blue-600 flex items-center justify-center">
                                        4</div>
                                    <span class="text-xs mt-1 text-center">Skills &<br />training</span>
                                </div>
                                <div class="flex-1 h-px bg-gray-300 mx-2"></div>

                                <div class="flex flex-col items-center text-blue-600 cursor-pointer"
                                    onclick="showStep(5)">
                                    <div id="step-5-circle"
                                        class="w-8 h-8 rounded-full border-2 border-blue-600 flex items-center justify-center">
                                        5</div>
                                    <span class="text-xs mt-1 text-center">Additional<br />information</span>
                                </div>
                            </div>

                            <!-- Steps Content -->
                            <form class="space-y-6" id="multiStepForm" action="{{ route('jobseeker.registration.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <!-- Step 1: Personal Info -->
                                <div id="step-1" class="">

                                    <div>
                                        <label class="block mb-1 text-sm font-medium">Full name <span style="color: red; font-size: 17px;">*</span></label>
                                        <input type="text" name="name" class="w-full border rounded-md p-2 mt-1"
                                            placeholder="Enter full name" value="{{ old('name') }}" />
                                        @error('name')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">Email <span style="color: red; font-size: 17px;">*</span></label>
                                            <input placeholder="Enter email" name="email" type="email"
                                                class="w-full border rounded-md p-2 mt-1" value="{{ old('email', $email) }}"
                                                readonly />
                                            @error('email')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">Gender <span style="color: red; font-size: 17px;">*</span></label>
                                            <select name="gender" id="gender" class="w-full border rounded-md p-2 mt-1">
                                                <option value="">Select gender</option>
                                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male
                                                </option>
                                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>
                                                    Female</option>
                                            </select>
                                        @error('gender')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        </div>
                                        
                                    </div>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">Phone number <span style="color: red; font-size: 17px;">*</span></label>
                                            <div class="flex">
                                                <select name="phone_code" class="w-1/3 border rounded-l-md p-2 mt-1">
                                                    <option value="+966">+966</option>
                                                    <option value="+971">+971</option>
                                                    <!-- Add more country codes if needed -->
                                                </select>
                                                <input name="phone_number" placeholder="Enter Phone number" type="tel"
                                                    class="w-2/3 border rounded-r-md p-2 mt-1"
                                                    value="{{ old('phone_number', $phone) }}" readonly />
                                            </div>
                                            @error('phone_number')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium mt-3">Date of birth <span style="color: red; font-size: 17px;">*</span></label>
                                            <input name="dob" id="dob" class="w-full border rounded-md p-2 mt-1"
                                                value="{{ old('dob') }}"/>
                                            @error('dob')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-6 mt-3">
                                        <div class="col-span-2">
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
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);" 
                                            />
                                            @error('national_id')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">Address <span style="color: red; font-size: 17px;">*</span></label>
                                        <input type="text" name="address" class="w-full border rounded-md p-2 mt-1"
                                            placeholder="Street, Area, ZIP" value="{{ old('address') }}" />
                                        @error('address')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">City <span style="color: red; font-size: 17px;">*</span></label>
                                        <input type="text" name="city" class="w-full border rounded-md p-2 mt-1"
                                            placeholder="City or State" value="{{ old('city') }}" />
                                        @error('city')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">Country <span style="color: red; font-size: 17px;">*</span></label>
                                        <input type="text" name="country" class="w-full border rounded-md p-2 mt-1"
                                            placeholder="Select state" value="{{ old('country') }}" />
                                        @error('country')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">Pin Code <span style="color: red; font-size: 17px;">*</span></label>
                                        <input type="text" name="pin_code" class="w-full border rounded-md p-2 mt-1"
                                            placeholder="Enter pin code" value="{{ old('pin_code') }}" />
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
                                <div id="step-2" class="hidden">

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
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Highest
                                                        qualification <span style="color: red; font-size: 17px;">*</span></label>
                                                    <select name="high_education[]"
                                                        class="w-full border border-gray-300 rounded-md p-2">
                                                        <option value="">Select highest qualification</option>
                                                        <option value="high_school" {{ old("high_education.$i") == 'high_school' ? 'selected' : '' }}>
                                                            High School</option>
                                                        <option value="diploma" {{ old("high_education.$i") == 'diploma' ? 'selected' : '' }}>Diploma</option>
                                                        <option value="bachelor" {{ old("high_education.$i") == 'bachelor' ? 'selected' : '' }}>Bachelor's Degree</option>
                                                        <option value="master" {{ old("high_education.$i") == 'master' ? 'selected' : '' }}>Master's Degree</option>
                                                        <option value="phd" {{ old("high_education.$i") == 'phd' ? 'selected' : '' }}>Ph.D.</option>
                                                    </select>
                                                    @error("high_education.$i")
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                {{-- Field of Study --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Field of
                                                        study <span style="color: red; font-size: 17px;">*</span></label>
                                                    <select name="field_of_study[]"
                                                        class="w-full border border-gray-300 rounded-md p-2">
                                                        <option value="">Select field of study</option>
                                                        <option value="engineering" {{ old("field_of_study.$i") == 'engineering' ? 'selected' : '' }}>
                                                            Engineering</option>
                                                        <option value="science" {{ old("field_of_study.$i") == 'science' ? 'selected' : '' }}>Science</option>
                                                        <option value="commerce" {{ old("field_of_study.$i") == 'commerce' ? 'selected' : '' }}>Commerce</option>
                                                        <option value="arts" {{ old("field_of_study.$i") == 'arts' ? 'selected' : '' }}>Arts</option>
                                                        <option value="medicine" {{ old("field_of_study.$i") == 'medicine' ? 'selected' : '' }}>Medicine</option>
                                                        <option value="law" {{ old("field_of_study.$i") == 'law' ? 'selected' : '' }}>Law</option>
                                                        <option value="education" {{ old("field_of_study.$i") == 'education' ? 'selected' : '' }}>Education</option>
                                                        <option value="management" {{ old("field_of_study.$i") == 'management' ? 'selected' : '' }}>Management</option>
                                                        <option value="other" {{ old("field_of_study.$i") == 'other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                    @error("field_of_study.$i")
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                {{-- Institution Name --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Institution
                                                        name <span style="color: red; font-size: 17px;">*</span></label>
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
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Graduation
                                                        year <span style="color: red; font-size: 17px;">*</span></label>
                                                    <select name="graduate_year[]"
                                                        class="w-full border border-gray-300 rounded-md p-2">
                                                        <option value="">Select year of passing</option>
                                                        @foreach(range(date('Y'), 2010) as $year)
                                                            <option value="{{ $year }}" {{ old("graduate_year.$i") == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                        @endforeach
                                                        <option value="2010-2014" {{ old("graduate_year.$i") == '2010-2014' ? 'selected' : '' }}>2010-2014</option>
                                                        <option value="2010" {{ old("graduate_year.$i") == '    2010' ? 'selected' : '' }}>
                                                            Before 2010</option>
                                                    </select>
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
                                        <button type="button" id="add-education" class="text-green-600 text-sm mt-2 mb-2">Add
                                            education +</button>
                                    </div>

                                    <div class="col-span-2 flex justify-between">
                                        <button type="button" onclick="showStep(1)"
                                            class="px-4 py-2 border rounded-md">Back</button>
                                        <button type="button" onclick="showStep(3)"
                                            class="bg-blue-700 text-white px-6 py-2 rounded-md">Next</button>
                                    </div>


                                </div>

                                <!-- Step 3: Work Experience -->
                                <div id="step-3" class="hidden">

                                    <!-- Container for multiple work experience entries -->
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
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Job Title <span style="color: red; font-size: 17px;">*</span></label>
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
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Started From <span style="color: red; font-size: 17px;">*</span></label>
                                                    <input name="starts_from[]" class="datepicker-start w-full border rounded-md p-2"
                                                        value="{{ old("starts_from.$i") }}" readonly />
                                                    @error("starts_from.$i")
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                {{-- End Date & Checkbox --}}
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">To <span style="color: red; font-size: 17px;">*</span></label>
                                                    <input type="text" name="end_to[]" class="w-full border rounded-md p-2 datepicker-end"
                                                        :disabled="working" :readonly="working"
                                                        :value="working ? '' : '{{ old("end_to.$i") }}'" />
                                                    @error("end_to.$i")
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror

                                                    <label class="inline-flex items-center mt-2 space-x-2">
                                                        <input type="checkbox" class="currently-working-checkbox"
                                                            name="currently_working[{{ $i }}]" x-model="working"
                                                            {{ $isWorking ? 'checked' : '' }}>
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
                                        <button type="button" id="add-work" class="text-green-600 text-sm mt-2 mb-2">Add work experience +</button>
                                    </div>

                                    <script>
                                        const workContainer = document.getElementById('work-container');
                                        const addWorkBtn = document.getElementById('add-work');

                                        addWorkBtn.addEventListener('click', () => {
                                            const firstEntry = workContainer.querySelector('.work-entry');
                                            const clone = firstEntry.cloneNode(true);

                                            // Clear inputs and errors
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

                                            clone.querySelectorAll('p.text-red-600').forEach(error => error.remove());
                                            clone.querySelector('.remove-work').style.display = 'block';

                                            workContainer.appendChild(clone);
                                            Alpine.initTree(clone);
                                        });

                                        workContainer.addEventListener('click', (e) => {
                                            if (e.target.classList.contains('remove-work')) {
                                                const entry = e.target.closest('.work-entry');
                                                entry.remove();
                                            }
                                        });
                                    </script>


                                    <div class="col-span-2 flex justify-between">
                                        <button type="button" onclick="showStep(2)"
                                            class="px-4 py-2 border rounded-md">Back</button>
                                        <button type="button" onclick="showStep(4)"
                                            class="bg-blue-700 text-white px-6 py-2 rounded-md">Next</button>
                                    </div>

                                </div>


                                <!-- Step 4: Skills -->
                                <div id="step-4" class="hidden">

                                    <div>
                                        <label class="block mb-1 text-sm font-medium">Skills <span style="color: red; font-size: 17px;">*</span></label>
                                        <input type="text" name="skills" class="w-full border rounded-md p-2 mt-1"
                                            placeholder="e.g. AWS Certified, Python, Project Management"
                                            value="{{ old('skills') }}" />
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
                                            value="{{ old('job_category') }}" />
                                        @error('job_category')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">Website Link </label>
                                        <input type="url" name="website_link" class="w-full border rounded-md p-2 mt-1" 
                                            placeholder="e.g. https://www.example.com"
                                            value="{{ old('website_link') }}" />
                                        @error('website_link')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-sm font-medium mt-3">Portfolio Link</label>
                                        <input type="url" name="portfolio_link" class="w-full border rounded-md p-2" mt-1
                                            placeholder="e.g. https://portfolio.example.com"
                                            value="{{ old('portfolio_link') }}" />
                                        @error('portfolio_link')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="flex justify-between">
                                        <button type="button" onclick="showStep(3)" class="px-4 py-2 border rounded-md mt-3">
                                            Back
                                        </button>
                                        <button type="button" onclick="showStep(5)"
                                            class="bg-blue-700 text-white px-6 py-2 rounded-md mt-3">
                                            Next
                                        </button>
                                    </div>


                                </div>

                                <!-- Step 5: Additional Information -->
                                <div id="step-5" class="hidden">

                                    <!-- CV Template Download -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1">CV template
                                            <span class="text-xs text-gray-500">(Download CV template and make sure the
                                                template you upload must follow the attached template)</span>
                                        </label>
                                        <button class="bg-blue-600 text-white px-3 py-1.5 rounded-md text-xs btn mt-2">
                                            Download CV template
                                        </button>

                                    </div>
                                    <!-- Upload Resume -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1 mt-3">
                                            Upload resume <span style="color: red;">*</span>
                                        </label>
                                        <div class="flex gap-2 items-center">
                                            <input type="file" name="resume" accept=".pdf,.doc,.docx,.txt"
                                                class="border rounded-md p-2 w-full text-sm" />
                                        </div>

                                        {{-- Show old selected file name here --}}
                                        @if(session('old_resume_name'))
                                            <p class="text-green-600 text-sm mt-1">
                                                Previously selected: {{ session('old_resume_name') }}
                                            </p>
                                        @endif

                                        {{-- Show validation error --}}
                                        @error('resume')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <script>
                                        const resumeInput = document.getElementById('resumeFile');
                                        const resumeFilenameDisplay = document.getElementById('resumeFilename');

                                        // Show filename when user selects a file
                                        resumeInput.addEventListener('change', function () {
                                            if (this.files.length > 0) {
                                                const fileName = this.files[0].name;
                                                resumeFilenameDisplay.textContent = "Selected: " + fileName;
                                                sessionStorage.setItem('resumeFileName', fileName); // store temporarily
                                            }
                                        });

                                        // On page load, restore filename if available
                                        document.addEventListener('DOMContentLoaded', function () {
                                            const savedFileName = sessionStorage.getItem('resumeFileName');
                                            if (savedFileName) {
                                                resumeFilenameDisplay.textContent = "Previously selected: " + savedFileName;
                                            }
                                        });
                                    </script>



                                    <!-- Upload Profile Picture -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1 mt-3">Upload profile picture <span style="color: red; font-size: 17px;">*</span></label>
                                        <div class="flex gap-2 items-center">
                                            <input type="file" name="profile_picture" accept="image/png, image/jpeg"
                                                class="border rounded-md p-2 w-full text-sm" />
                                        </div>
                                            @error('profile_picture')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                    </div>

                                    <div class="text-sm">
                                        <label class="flex items-start gap-2 mt-3">
                                            <input type="checkbox" id="termsCheckbox" name="terms" {{ old('terms') ? 'checked' : '' }}>

                                            <span>
                                                I have read and agreed to
                                                <a href="#" class="text-blue-600 underline">terms and conditions</a>
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
                                        <button type="button" onclick="showStep(4)" class="px-4 py-2 border rounded-md">Back</button>
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
 


        <script>
            // Function to handle education add/remove
            const educationContainer = document.getElementById('education-container');
            const addEducationBtn = document.getElementById('add-education');

            addEducationBtn.addEventListener('click', () => {
                const firstEntry = educationContainer.querySelector('.education-entry');
                const clone = firstEntry.cloneNode(true);

                clone.querySelectorAll('input').forEach(input => input.value = '');
                clone.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

                clone.querySelectorAll('p.text-red-600').forEach(error => error.remove());

                clone.querySelector('.remove-education').style.display = 'block';

                educationContainer.appendChild(clone);
            });

            educationContainer.addEventListener('click', (e) => {
                if (e.target.classList.contains('remove-education')) {
                    const entry = e.target.closest('.education-entry');
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
            $(document).ready(function () {
                $('#dob').datepicker({
                    format: 'yyyy-mm-dd',
                    endDate: new Date(),
                    autoclose: true,
                    todayHighlight: true
                });
                    function initializeDatePickers() {
                    $('.datepicker-start, .datepicker-end').datepicker({
                        format: 'yyyy-mm-dd',
                        endDate: new Date(),
                        autoclose: true,
                        todayHighlight: true
                    });
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

<!-- Step 3: Your custom script -->
<script>
    $(document).ready(function () {
        const form = $('#multiStepForm');

        form.validate({
            rules: {
                name: "required",
                gender: "required",
                dob: "required",
                national_id: "required",
                address: "required",
                city: "required",
                country: "required",
                pin_code: "required"
            },
            messages: {
                name: "Full name is required",
                gender: "Please select gender",
                dob: "Date of birth is required",
                national_id: "National ID is required",
                address: "Address is required",
                city: "City is required",
                country: "Country is required",
                pin_code: "Pin code is required"
            },
            errorElement: 'p',
            errorPlacement: function (error, element) {
                error.addClass('text-red-600 text-sm mt-1');
                error.insertAfter(element);
            }
        });

        window.showStep = function (step) {
            if (!form.valid()) return;

            for (let i = 1; i <= 5; i++) {
                $(`#step-${i}`).addClass('hidden');
                $(`#step-${i}-circle`).removeClass('bg-blue-600 text-white');
            }

            $(`#step-${step}`).removeClass('hidden');
            $(`#step-${step}-circle`).addClass('bg-blue-600 text-white');
        }
    });
</script>