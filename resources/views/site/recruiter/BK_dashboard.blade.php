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

	
    <div class="page-wraper">
        <div class="flex h-screen">
             <aside class="w-64 bg-blue-900 text-white flex flex-col py-8 px-4">
                <div class="text-2xl font-bold mb-10">
                    <span class="text-white">Talentre</span><span class="text-blue-400">k</span>
                </div>
                <nav class="flex flex-col gap-4">
                    <a href="recruiter-dashboard.html" class="flex items-center px-4 py-2 bg-white text-blue-700 rounded-md">
                        <i data-feather="grid" class="mr-3"></i> Dashboard
                    </a>
                    <a href="{{route('recruiter.dashboard.jobseeker')}}" class="flex items-center px-4 py-2 text-white rounded-md hover:text-white transition-colors duration-200">
                        <i data-feather="users" class="mr-3"></i> Jobseekers
                    </a>
                    <a href="admin-support.html" class="flex items-center px-4 py-2 text-white rounded-md hover:text-white transition-colors duration-200">
                        <i data-feather="headphones" class="mr-3"></i> Admin support
                    </a>
                    <a href="settings.html" class="flex items-center px-4 py-2 text-white rounded-md hover:text-white transition-colors duration-200">
                        <i data-feather="settings" class="mr-3"></i> Settings
                    </a>

                    <form action="{{ route('recruiter.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center px-4 py-2 text-white rounded-md hover:text-white transition-colors duration-200 w-full text-left">
                            <i data-feather="log-out" class="mr-3"></i> Logout
                        </button>
                    </form>

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

           <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="dashboard()">
            <h2 class="text-2xl font-semibold mb-6">Dashboard</h2>

              <!-- Stat Cards -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-white p-6 rounded-lg shadow">
                <p class="text-gray-500">Jobseeker Shortlisted</p>
                <h3 class="text-3xl font-bold mt-2">24</h3>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                <p class="text-gray-500">Interviews scheduled</p>
                <h3 class="text-3xl font-bold mt-2">15</h3>
                </div>
            </div>


            <!-- Jobseekers contacted -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold mb-4">Jobseekers contacted</h3>

                <template x-for="(jobseeker, index) in paginatedJobseekers()" :key="jobseeker.name">
                <div class="flex justify-between items-center border-b py-4">
                    <div class="flex items-center space-x-4">
                    <img :src="jobseeker.img" class="w-14 h-14 rounded-full object-cover" alt="Profile" />
                    <div class="w-48">
                        <h4 class="font-semibold text-sm" x-text="jobseeker.name"></h4>
                        <p class="text-sm text-gray-500" x-text="jobseeker.role"></p>
                    </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-6 w-full justify-between ml-20">
                    <div class="text-sm text-gray-700">
                        <p><strong>Experience</strong><br><span x-text="jobseeker.experience"></span></p>
                    </div>
                    <div class="text-sm text-gray-700">
                        <p><strong>Skills</strong><br><span x-text="jobseeker.skills"></span></p>
                    </div>
                    <div class="flex items-center space-x-2 mt-3 sm:mt-0">
                        <template x-if="jobseeker.feedbackGiven">
                        <button
                            @click="openFeedbackModal(jobseeker)"
                            class="border border-gray-600 px-3 py-1.5 rounded hover:bg-gray-100 text-sm"
                        >
                            Share feedback
                        </button>
                        </template>

                        <template x-if="!jobseeker.feedbackGiven">
                        <button
                            @click="requestInterview(jobseeker)"
                            class="border border-blue-600 text-blue-600 px-3 py-1.5 rounded hover:bg-blue-50 text-sm"
                        >
                            Request interview
                        </button>
                        </template>

                        <button class="bg-blue-600 text-white px-3 py-1.5 rounded hover:bg-blue-700 text-sm">
                        View profile
                        </button>
                    </div>
                    </div>
                </div>
                </template>

                <!-- Interview Confirmation Modal -->
                <div
                x-show="isModalOpen"
                x-transition
                style="display: none;"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                >
                <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full relative" @click.away="closeModal()">
                    <button @click="closeModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>
                    <h3 class="text-xl font-semibold mb-4">Request Interview</h3>
                    <p class="mb-4">Send interview request to <strong x-text="selectedJobseeker?.name"></strong>?</p>
                    <div class="flex justify-end space-x-3">
                    <button @click="closeModal()" class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
                    <button @click="submitRequest()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Confirm</button>
                    </div>
                </div>
                </div>

                <!-- Feedback Modal -->
                <div x-show="showFeedbackModal" x-transition style="display: none;" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" >
                <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full relative" @click.away="closeFeedbackModal()">
                    <button @click="closeFeedbackModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">✕</button>
                    <h3 class="text-xl font-semibold mb-4">Share Feedback for <span x-text="selectedJobseeker?.name"></span></h3>
                    <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Feedback</label>
                        <textarea x-model="feedbackText" rows="4" class="w-full border rounded p-2 text-sm" required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Interview Status</label>
                        <select x-model="interviewStatus" class="w-full border rounded p-2 text-sm">
                        <option value="" disabled>Select status</option>
                        <option value="selected">Selected</option>
                        <option value="on-hold">On Hold</option>
                        <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button @click="closeFeedbackModal()" class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
                        <button @click="submitFeedback()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Submit</button>
                    </div>
                    </div>
                </div>
                </div>

                <!-- Pagination -->
                <div class="flex justify-end mt-6 space-x-2 items-center">
                <button @click="prevPage()" :disabled="currentPage === 1"
                    class="p-1.5 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <template x-for="page in totalPages()" :key="page">
                    <button @click="currentPage = page"
                    :class="{'bg-blue-600 text-white': currentPage === page, 'bg-gray-200': currentPage !== page}"
                    class="px-3 py-1 rounded">
                    <span x-text="page"></span>
                    </button>
                </template>
                <button @click="nextPage()" :disabled="currentPage === totalPages()"
                    class="p-1.5 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                </div>
            </div>
            </main>

            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
            <script>
            function dashboard() {
                return {
                jobseekers: [
                    { name: 'Peter Parker', role: 'UI UX designer', experience: '2 years', skills: 'UX, UI, Wireframing', img: 'https://i.pravatar.cc/100?img=3', feedbackGiven: false },
                    { name: 'Mary Jane', role: 'Graphic Designer', experience: '3 years', skills: 'Photoshop, Branding', img: 'https://i.pravatar.cc/100?img=4', feedbackGiven: true },
                    { name: 'Tony Stark', role: 'Frontend Developer', experience: '5 years', skills: 'React, Vue, JS', img: 'https://i.pravatar.cc/100?img=5', feedbackGiven: false },
                    { name: 'Natasha Romanoff', role: 'UI UX designer', experience: '3 years', skills: 'User Journey, UX research', img: 'https://i.pravatar.cc/100?img=6', feedbackGiven: true },
                    { name: 'Bruce Banner', role: 'Backend Developer', experience: '4 years', skills: 'Node.js, APIs, DBs', img: 'https://i.pravatar.cc/100?img=7', feedbackGiven: false },
                    { name: 'Steve Rogers', role: 'Project Manager', experience: '6 years', skills: 'Agile, Scrum, Leadership', img: 'https://i.pravatar.cc/100?img=8', feedbackGiven: true },
                    { name: 'Clint Barton', role: 'QA Engineer', experience: '3 years', skills: 'Testing, Automation, Selenium', img: 'https://i.pravatar.cc/100?img=9', feedbackGiven: false },
                    { name: 'Wanda Maximoff', role: 'Frontend Developer', experience: '2 years', skills: 'Vue.js, HTML, CSS', img: 'https://i.pravatar.cc/100?img=10', feedbackGiven: true },
                    { name: 'Sam Wilson', role: 'DevOps Engineer', experience: '4 years', skills: 'CI/CD, Docker, Jenkins', img: 'https://i.pravatar.cc/100?img=11', feedbackGiven: false },
                    { name: 'Bucky Barnes', role: 'System Analyst', experience: '5 years', skills: 'Systems, Requirements, SQL', img: 'https://i.pravatar.cc/100?img=12', feedbackGiven: true },
                    { name: 'Stephen Strange', role: 'Software Architect', experience: '10 years', skills: 'Architecture, Design Patterns', img: 'https://i.pravatar.cc/100?img=13', feedbackGiven: true },
                    { name: 'Scott Lang', role: 'Mobile Developer', experience: '3 years', skills: 'React Native, Flutter', img: 'https://i.pravatar.cc/100?img=14', feedbackGiven: false },
                    { name: 'Hope Van Dyne', role: 'Business Analyst', experience: '4 years', skills: 'Analysis, Documentation', img: 'https://i.pravatar.cc/100?img=15', feedbackGiven: true },
                    { name: 'Nick Fury', role: 'CTO', experience: '12 years', skills: 'Leadership, Strategy, Tech Vision', img: 'https://i.pravatar.cc/100?img=16', feedbackGiven: false },
                    { name: 'Shuri', role: 'AI Engineer', experience: '2 years', skills: 'ML, AI, Python', img: 'https://i.pravatar.cc/100?img=17', feedbackGiven: true },
                    { name: 'T\'Challa', role: 'Security Analyst', experience: '4 years', skills: 'Cybersecurity, Auditing', img: 'https://i.pravatar.cc/100?img=18', feedbackGiven: false },
                    { name: 'Gamora', role: 'Database Admin', experience: '6 years', skills: 'MySQL, Oracle, Backup', img: 'https://i.pravatar.cc/100?img=19', feedbackGiven: true },
                    { name: 'Rocket Raccoon', role: 'Support Engineer', experience: '3 years', skills: 'Troubleshooting, Tech Support', img: 'https://i.pravatar.cc/100?img=20', feedbackGiven: false },
                    { name: 'Groot', role: 'Data Entry Operator', experience: '1 year', skills: 'Typing, Accuracy', img: 'https://i.pravatar.cc/100?img=21', feedbackGiven: false },
                    { name: 'Drax the Destroyer', role: 'Tech Recruiter', experience: '4 years', skills: 'Hiring, Screening', img: 'https://i.pravatar.cc/100?img=22', feedbackGiven: true }
                    ],
                // State
                currentPage: 1,
                pageSize: 4,

                showFeedbackModal: false,
                isModalOpen: false,
                selectedJobseeker: null,
                feedbackText: '',
                interviewStatus: '',

                // Pagination
                paginatedJobseekers() {
                    const start = (this.currentPage - 1) * this.pageSize;
                    return this.jobseekers.slice(start, start + this.pageSize);
                },
                totalPages() {
                    return Math.ceil(this.jobseekers.length / this.pageSize);
                },
                nextPage() {
                    if (this.currentPage < this.totalPages()) this.currentPage++;
                },
                prevPage() {
                    if (this.currentPage > 1) this.currentPage--;
                },

                // Interview
                requestInterview(jobseeker) {
                    this.selectedJobseeker = jobseeker;
                    this.isModalOpen = true;
                },
                closeModal() {
                    this.isModalOpen = false;
                    this.selectedJobseeker = null;
                },
                submitRequest() {
                    this.closeModal();
                },

                // Feedback
                openFeedbackModal(jobseeker) {
                    this.selectedJobseeker = jobseeker;
                    this.feedbackText = '';
                    this.interviewStatus = '';
                    this.showFeedbackModal = true;
                },
                closeFeedbackModal() {
                    this.showFeedbackModal = false;
                    this.selectedJobseeker = null;
                },
                submitFeedback() {
                    if (!this.feedbackText || !this.interviewStatus) {
                    return;
                    }
                    alert(`Feedback submitted for ${this.selectedJobseeker.name}`);
                    this.closeFeedbackModal();
                },
                };
            }
            </script>



            </div>
        </div>

        <!-- Feather Icons -->
        <script>
            feather.replace()
        </script>


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
