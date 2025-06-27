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
                                        <span class="text-xs mt-1 text-center">Skills &<br />training</span>
                                    </div>
                                    <div class="flex-1 h-px bg-gray-300 mx-2"></div>

                                    <div class="flex flex-col items-center text-blue-600 cursor-pointer" onclick="showStep(5)">
                                        <div id="step-5-circle" class="w-8 h-8 rounded-full border-2 border-blue-600 flex items-center justify-center">5</div>
                                        <span class="text-xs mt-1 text-center">Additional<br />information</span>
                                    </div>
                                    </div>

                                    <!-- Steps Content -->

                                    <!-- Step 1: Personal Info -->
                                    <div id="step-1" class="">
                                    <form class="space-y-6">
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Full name</label>
                                            <input type="text" class="w-full border rounded-md p-2" placeholder="Enter full name" />
                                        </div>
                                        <div class="grid grid-cols-2 gap-6">
                                            <div>
                                                <label class="block mb-1 text-sm font-medium">Email</label>
                                                <input placeholder="Enter email" type="email" class="w-full border rounded-md p-2" />
                                            </div>
                                            <div>
                                                <label class="block mb-1 text-sm font-medium">Gender</label>
                                                <select class="w-full border rounded-md p-2"><option>Select gender</option></select>
                                            </div>
                                        </div>
                                            <div class="grid grid-cols-2 gap-6">
                                                <div>
                                                    <label class="block mb-1 text-sm font-medium">Phone number</label>
                                                    <div class="flex">
                                                    <select class="w-1/3 border rounded-l-md p-2"><option>+91</option></select>
                                                    <input placeholder="Enter Phone number" type="tel" class="w-2/3 border rounded-r-md p-2" />
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block mb-1 text-sm font-medium">Date of birth</label>
                                                <input type="date" class="w-full border rounded-md p-2" />
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Location</label>
                                            <input type="text" class="w-full border rounded-md p-2" placeholder="City or State" />
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Address</label>
                                            <input type="text" class="w-full border rounded-md p-2" placeholder="Street, Area, ZIP" />
                                        </div>
                                        <div class="flex justify-end">
                                            <button type="button" onclick="showStep(2)" class="bg-blue-700 text-white px-6 py-2 rounded-md">Next</button>
                                        </div>
                                    </form>
                                    </div>

                                <!-- Step 2: Education -->
                                    <div id="step-2" class="hidden">
                                        <form id="education-form" class="grid grid-cols-2 gap-4">
                                            <!-- Container for multiple education entries -->
                                            <div id="education-container" class="col-span-2 grid grid-cols-2 gap-4">
                                                <div class="education-entry grid grid-cols-2 gap-4 col-span-2 p-4 rounded-md relative">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Highest qualification</label>
                                                    <select class="w-full border border-gray-300 rounded-md p-2">
                                                    <option>Select highest qualification</option>
                                                    <option value="high_school">High School</option>
                                                    <option value="diploma">Diploma</option>
                                                    <option value="bachelor">Bachelor's Degree</option>
                                                    <option value="master">Master's Degree</option>
                                                    <option value="phd">Ph.D.</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Field of study</label>
                                                    <select class="w-full border border-gray-300 rounded-md p-2">
                                                    <option>Select field of study</option>
                                                    <option value="engineering">Engineering</option>
                                                    <option value="science">Science</option>
                                                    <option value="commerce">Commerce</option>
                                                    <option value="arts">Arts</option>
                                                    <option value="medicine">Medicine</option>
                                                    <option value="law">Law</option>
                                                    <option value="education">Education</option>
                                                    <option value="management">Management</option>
                                                    <option value="other">Other</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Institution name</label>
                                                    <input placeholder="Enter Institution name" type="text" class="w-full border border-gray-300 rounded-md p-2" />
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Graduation year</label>
                                                    <select class="w-full border border-gray-300 rounded-md p-2">
                                                    <option>Select year of passing</option>
                                                    <!-- You can dynamically generate these using JS for more scalability -->
                                                    <option>2025</option>
                                                    <option>2024</option>
                                                    <option>2023</option>
                                                    <option>2022</option>
                                                    <option>2021</option>
                                                    <option>2020</option>
                                                    <option>2019</option>
                                                    <option>2018</option>
                                                    <option>2017</option>
                                                    <option>2016</option>
                                                    <option>2015</option>
                                                    <option>2010-2014</option>
                                                    <option>Before 2010</option>
                                                    </select>
                                                </div>
                                                <!-- Remove button -->
                                                <button type="button" class="remove-education absolute top-2 right-2 text-red-600 font-bold text-lg" style="display:none;">&times;</button>
                                                </div>
                                            </div>

                                            <div class="col-span-2">
                                                <button type="button" id="add-education" class="text-green-600 text-sm">Add education +</button>
                                            </div>

                                            <div class="col-span-2 flex justify-between">
                                                <button type="button" onclick="showStep(1)" class="px-4 py-2 border rounded-md">Back</button>
                                                <button type="button" onclick="showStep(3)" class="bg-blue-700 text-white px-6 py-2 rounded-md">Next</button>
                                            </div>
                                            </form>

                                    </div>

                                    <!-- Step 3: Work Experience -->
                                    <div id="step-3" class="hidden">
                                    <form id="work-form" class="grid grid-cols-2 gap-4">
                                        <!-- Container for multiple work experience entries -->
                                        <div id="work-container" class="col-span-2 grid grid-cols-2 gap-4">
                                        <div class="work-entry grid grid-cols-2 gap-4 col-span-2 p-4 rounded-md relative">
                                            <div>
                                            <label class="block mb-1 text-sm font-medium">Job title</label>
                                            <input type="text" class="w-full border rounded-md p-2" placeholder="e.g. Software Engineer" />
                                            </div>
                                            <div>
                                            <label class="block mb-1 text-sm font-medium">Organization</label>
                                            <input placeholder="Enter Organization" type="text" class="w-full border rounded-md p-2" />
                                            </div>
                                            <div>
                                            <label class="block mb-1 text-sm font-medium">Started from</label>
                                            <input type="date" class="w-full border rounded-md p-2" />
                                            </div>
                                            <div>
                                            <label class="block mb-1 text-sm font-medium">To</label>
                                            <input type="date" class="w-full border rounded-md p-2" />
                                            </div>
                                            <!-- Remove button -->
                                            <button type="button" class="remove-work absolute top-2 right-2 text-red-600 font-bold text-lg" style="display:none;">&times;</button>
                                        </div>
                                        </div>

                                        <div class="col-span-2">
                                        <button type="button" id="add-work" class="text-green-600 text-sm">Add work experience +</button>
                                        </div>

                                        <div class="col-span-2 flex justify-between">
                                        <button type="button" onclick="showStep(2)" class="px-4 py-2 border rounded-md">Back</button>
                                        <button type="button" onclick="showStep(4)" class="bg-blue-700 text-white px-6 py-2 rounded-md">Next</button>
                                        </div>
                                    </form>
                                    </div>

                                    <script>
                                    // Function to handle education add/remove
                                    const educationContainer = document.getElementById('education-container');
                                    const addEducationBtn = document.getElementById('add-education');

                                    addEducationBtn.addEventListener('click', () => {
                                        // Clone the first education-entry
                                        const firstEntry = educationContainer.querySelector('.education-entry');
                                        const clone = firstEntry.cloneNode(true);

                                        // Clear input/select values in cloned node
                                        clone.querySelectorAll('input').forEach(input => input.value = '');
                                        clone.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

                                        // Show remove button on cloned entries
                                        clone.querySelector('.remove-education').style.display = 'block';

                                        educationContainer.appendChild(clone);
                                    });

                                    educationContainer.addEventListener('click', (e) => {
                                        if (e.target.classList.contains('remove-education')) {
                                        const entry = e.target.closest('.education-entry');
                                        entry.remove();
                                        }
                                    });

                                    // Function to handle work experience add/remove
                                    const workContainer = document.getElementById('work-container');
                                    const addWorkBtn = document.getElementById('add-work');

                                    addWorkBtn.addEventListener('click', () => {
                                        const firstEntry = workContainer.querySelector('.work-entry');
                                        const clone = firstEntry.cloneNode(true);

                                        // Clear input values in cloned node
                                        clone.querySelectorAll('input').forEach(input => input.value = '');

                                        // Show remove button on cloned entries
                                        clone.querySelector('.remove-work').style.display = 'block';

                                        workContainer.appendChild(clone);
                                    });

                                    workContainer.addEventListener('click', (e) => {
                                        if (e.target.classList.contains('remove-work')) {
                                        const entry = e.target.closest('.work-entry');
                                        entry.remove();
                                        }
                                    });
                                    </script>


                                    <!-- Step 4: Skills -->
                                    <div id="step-4" class="hidden">
                                    <form class="space-y-6">
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Skills</label>
                                            <input type="text" class="w-full border rounded-md p-2" placeholder="e.g. AWS Certified, Python, Project Management" />
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Area of Interests</label>
                                            <select class="w-full border rounded-md p-2">
                                                <option value="" disabled selected>Select an area</option>
                                                <option value="cloud-computing">Cloud Computing</option>
                                                <option value="web-development">Web Development</option>
                                                <option value="data-science">Data Science</option>
                                                <option value="machine-learning">Machine Learning</option>
                                                <option value="cybersecurity">Cybersecurity</option>
                                                <option value="digital-marketing">Digital Marketing</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Job Categories</label>
                                            <input type="text" class="w-full border rounded-md p-2" placeholder="e.g. Software Engineer, Data Analyst" />
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Website Link</label>
                                            <input type="url" class="w-full border rounded-md p-2" placeholder="e.g. https://www.example.com" />
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-sm font-medium">Portfolio Link</label>
                                            <input type="url" class="w-full border rounded-md p-2" placeholder="e.g. https://portfolio.example.com" />
                                        </div>
                                        <div class="flex justify-between">
                                            <button type="button" onclick="showStep(3)" class="px-4 py-2 border rounded-md" >
                                            Back
                                            </button>
                                            <button type="button" onclick="showStep(5)" class="bg-blue-700 text-white px-6 py-2 rounded-md" >
                                            Next
                                            </button>
                                        </div>
                                        </form>

                                    </div>

                                    <!-- Step 5: Additional Information -->
                                    <div id="step-5" class="hidden">
                                    <form class="space-y-6">
                                        <!-- CV Template Download -->
                                        <div>
                                            <label class="block text-sm font-medium mb-1">CV template 
                                                <span class="text-xs text-gray-500">(Download CV template and make sure the template you upload must follow the attached template)</span>
                                            </label>
                                                <button class="bg-blue-600 text-white px-3 py-1.5 rounded-md text-xs btn">
                                                    Download CV template
                                                </button>

                                        </div>

                                        <!-- Upload Resume -->
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Upload resume</label>
                                            <div class="flex gap-2 items-center">
                                                <input type="file" class="border rounded-md p-2 w-full text-sm" />
                                                <button
                                                class="bg-green-500 text-white px-4 py-2 rounded-md text-sm whitespace-nowrap w-38"
                                                type="button"
                                                >
                                                Upload document
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Upload Profile Picture -->
                                        <div>
                                        <label class="block text-sm font-medium mb-1">Upload profile picture</label>
                                        <div class="flex gap-2 items-center">
                                            <input type="file" class="border rounded-md p-2 w-full text-sm" />
                                            <button
                                            class="bg-green-500 text-white px-4 py-2 rounded-md text-sm whitespace-nowrap w-38"
                                            type="button"
                                            >
                                            Upload image
                                            </button>
                                        </div>
                                        </div>

                                        <div class="text-sm">
                                            <label class="flex items-start gap-2">
                                            <input type="checkbox" class="mt-1" />
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
                                        <div class="flex justify-between">
                                        <button type="button" onclick="showStep(4)" class="px-4 py-2 border rounded-md">Back</button>
                                        <a href="success-registration.html" class="bg-blue-600 text-white px-6 py-2 rounded-md">Submit</a>
                                        </div>
                                    </form>
                                    </div>
                                </div>

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

@include('site.jobseeker.componants.footer')