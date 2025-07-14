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

                                <form class="space-y-6" action="{{ route('mentor.registration.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <!-- Step 1: Personal Info -->
                                    <div id="step-1" class="">
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Full name</label>
                                            <input type="text" name="name" class="w-full border rounded-md p-2" placeholder="Enter full name" value="{{old('name')}}"/>
                                            @error('name')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Email</label>
                                            <input placeholder="Enter email" name="email" type="email" class="w-full border rounded-md p-2" value="{{old('email', $email)}}" readonly/>
                                            @error('email')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-6">
                                            <div>
                                                <label class="block mb-1 text-sm font-medium">Phone number</label>
                                                <div class="flex">
                                                <select class="w-1/3 border rounded-l-md p-2"><option>+91</option></select>
                                                <input placeholder="Enter Phone number" name="phone_number" type="tel" class="w-2/3 border rounded-r-md p-2" value="{{old('phone_number', $phone)}}" readonly/>
                                                
                                            </div>
                                            @error('phone_number')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Date of birth</label>
                                            <input type="date" name="dob" class="w-full border rounded-md p-2" value="{{old('dob')}}"/>
                                            @error('dob')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-6 mt-3">
                                            <div>
                                                <label class="block mb-1 text-sm font-medium">National ID Number</label>
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
                                            <label class="block mb-1 text-sm font-medium">Location</label>
                                            <input type="text" name="city" class="w-full border rounded-md p-2" placeholder="City or State" value="{{old('city')}}"/>
                                            @error('city')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="flex justify-end">
                                            <button type="button" onclick="showStep(2)" class="bg-blue-700 text-white px-6 py-2 rounded-md">Next</button>
                                        </div>
                                    
                                    </div>

                                    <!-- Step 2: Education -->
                                    <div id="step-2" class="hidden">
                                        @php
                                            $educationData = old('high_education') ?? [null];
                                        @endphp

                                        <div id="education-container" class="col-span-2 grid grid-cols-2 gap-4">
                                            @foreach($educationData as $i => $value)
                                            <div class="education-entry grid grid-cols-2 gap-4 col-span-2 p-4 rounded-md relative bg-gray-100">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Highest qualification</label>
                                                    <select name="high_education[]" class="w-full border border-gray-300 rounded-md p-2">
                                                        <option value="">Select highest qualification</option>
                                                        @foreach(['high_school', 'diploma', 'bachelor', 'master', 'phd'] as $option)
                                                        <option value="{{ $option }}" {{ old("high_education.$i") == $option ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $option)) }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error("high_education.$i")
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Field of study</label>
                                                    <select name="field_of_study[]" class="w-full border border-gray-300 rounded-md p-2">
                                                        <option value="">Select field of study</option>
                                                        @foreach(['engineering', 'science', 'commerce', 'arts', 'medicine', 'law', 'education', 'management', 'other'] as $field)
                                                        <option value="{{ $field }}" {{ old("field_of_study.$i") == $field ? 'selected' : '' }}>{{ ucfirst($field) }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error("field_of_study.$i")
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Institution name</label>
                                                    <input type="text" name="institution[]" class="w-full border border-gray-300 rounded-md p-2" value="{{ old("institution.$i") }}" placeholder="Enter Institution name">
                                                    @error("institution.$i")
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Graduation year</label>
                                                    <select name="graduate_year[]" class="w-full border border-gray-300 rounded-md p-2">
                                                        <option value="">Select year of passing</option>
                                                        @for($year = now()->year; $year >= 2000; $year--)
                                                        <option value="{{ $year }}" {{ old("graduate_year.$i") == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                        @endfor
                                                        <option value="before_2000" {{ old("graduate_year.$i") == 'before_2000' ? 'selected' : '' }}>Before 2000</option>
                                                    </select>
                                                    @error("graduate_year.$i")
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <button type="button" class="remove-education absolute top-2 right-2 text-red-600 font-bold text-lg" style="{{ $i == 0 ? 'display:none;' : 'display:block;' }}">×</button>
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
                                    <div id="step-3" class="hidden">
                                        @php
                                            $workData = old('job_role') ?? [null];
                                        @endphp

                                        <div id="work-container" class="col-span-2 grid grid-cols-2 gap-4">
                                            @foreach($workData as $i => $val)
                                            <div class="work-entry grid grid-cols-2 gap-4 col-span-2 p-4 rounded-md relative bg-gray-100">
                                                <div>
                                                    <label class="block mb-1 text-sm font-medium">Job title</label>
                                                    <input type="text" name="job_role[]" value="{{ old("job_role.$i") }}" class="w-full border rounded-md p-2" placeholder="e.g. Software Engineer">
                                                    @error("job_role.$i")
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="block mb-1 text-sm font-medium">Organization</label>
                                                    <input type="text" name="organization[]" value="{{ old("organization.$i") }}" class="w-full border rounded-md p-2" placeholder="Enter Organization">
                                                    @error("organization.$i")
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="block mb-1 text-sm font-medium">Started from</label>
                                                    <input type="date" name="starts_from[]" value="{{ old("starts_from.$i") }}" class="w-full border rounded-md p-2">
                                                    @error("starts_from.$i")
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <label class="block mb-1 text-sm font-medium">To</label>
                                                    <input type="date" name="end_to[]" value="{{ old("end_to.$i") }}" class="w-full border rounded-md p-2">
                                                    @error("end_to.$i")
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <button type="button" class="remove-work absolute top-2 right-2 text-red-600 font-bold text-lg" style="{{ $i == 0 ? 'display:none;' : 'display:block;' }}">×</button>
                                            </div>
                                            @endforeach
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
                                    <div id="step-4" class="hidden">
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Skills</label>
                                            <input type="text" name="training_skills" class="w-full border rounded-md p-2" placeholder="e.g. Communication, Leadership, Python, Cloud Computing" value="{{old('training_skills')}}"/>
                                            @error('training_skills')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Website Link</label>
                                            <input type="url" name="website_link" class="w-full border rounded-md p-2" placeholder="e.g. https://www.example.com" value="{{old('website_link')}}"/>
                                            @error('website_link')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Portfolio Link</label>
                                            <input type="url" name="portfolio_link" class="w-full border rounded-md p-2" placeholder="e.g. https://portfolio.example.com" value="{{old('portfolio_link')}}"/>
                                            @error('portfolio_link')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="flex justify-between">
                                            <button type="button" onclick="showStep(3)" class="px-4 py-2 border rounded-md">
                                                Back
                                            </button>
                                            <button type="button" onclick="showStep(5)" class="bg-blue-700 text-white px-6 py-2 rounded-md">
                                                Next
                                            </button>
                                        </div>
                                   
                                    </div>


                                    <!-- Step 5: Additional Information -->
                                    <div id="step-5" class="hidden">
                                   
                                        <!-- Upload Resume -->
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Upload resume</label>
                                            <div class="flex gap-2 items-center">
                                                <input type="file" name="resume" accept="application/pdf"  class="border rounded-md p-2 w-full text-sm" />
                                            </div>
                                            @error('resume')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Upload Profile Picture -->
                                        <div>
                                        <label class="block text-sm font-medium mb-1">Upload profile picture</label>
                                        <div class="flex gap-2 items-center">
                                            <input type="file" name="profile_picture"  class="border rounded-md p-2 w-full text-sm" />
                                        </div>
                                        @error('profile_picture')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        </div>

                                        <div>
                                        <label class="block text-sm font-medium mb-1">Upload training certificate</label>
                                        <div class="flex gap-2 items-center">
                                            <input type="file" name="training_certificate" class="border rounded-md p-2 w-full text-sm" />
                                        </div>
                                        @error('training_certificate')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        </div>

                                        <div class="text-sm">
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
                                        <button type="button" onclick="showStep(4)" class="px-4 py-2 border rounded-md">Back</button>
                                        <button type="submit" id="submitBtn" class="bg-blue-600 text-white px-6 py-2 rounded-md">
                                            Submit
                                        </button>

                                    
                                    </div>
                                </form> 
                            </div>
                                <script>
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

                                <!-- </div> -->
                            </div>
                        </div>
                    </div>

                    
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
