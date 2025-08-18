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
        <div class="flex h-screen" x-data="{ sidebarOpen: true }" x-init="$watch('sidebarOpen', () => feather.replace())">
            <!-- Sidebar -->
            @include('site.recruiter.componants.sidebar')	
            <div class="flex-1 flex flex-col">
                @include('site.recruiter.componants.navbar')	
                <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="dashboard()">
                <h2 class="text-2xl font-semibold mb-6">Dashboard</h2>

                <!-- Stat Cards -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <p class="text-xl text-black-500">Jobseeker Shortlisted</p>
                        <h3 class="text-3xl font-bold mt-2">24</h3>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <p class="text-xl text-black-500">Interviews scheduled</p>
                        <h3 class="text-3xl font-bold mt-2">15</h3>
                    </div>
                </div>



                <!-- Jobseekers contacted -->
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-xl font-semibold mb-4">Jobseekers contacted</h3>
                     <hr class="border-t border-gray-300 mb-4">
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
           



@include('site.recruiter.componants.footer')