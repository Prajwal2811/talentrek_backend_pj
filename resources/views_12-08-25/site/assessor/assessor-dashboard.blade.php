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

	
 <!-- Wrapper -->
<!-- Add this script at the end of your body or inside a script tag -->
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('dashboard', () => ({
      init() {
        feather.replace();
      }
    }))
  });

  // Run feather.replace() on page load
  document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
  });
</script>

<div class="flex h-screen" x-data="{ sidebarOpen: true }" x-init="$watch('sidebarOpen', () => feather.replace())">
<!-- Sidebar -->
@include('site.assessor.componants.sidebar')
<style>
    /* Center icons when sidebar is collapsed */
    aside.w-16 nav a {
    justify-content: center;
    }
</style>

  <!-- Main Content -->
  <div class="flex-1 flex flex-col">
    <!-- Navbar -->
    @include('site.assessor.componants.navbar')

    <!-- Main -->
    <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="dashboard()">
      <h2 class="text-2xl font-semibold mb-6">Dashboard</h2>
       <!-- Stat Cards -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-white p-6 rounded-lg shadow">
                <p class="text-gray-500">Total upcoming sessions</p>
                <h3 class="text-3xl font-bold mt-2">24</h3>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                <p class="text-gray-500">Today’s sessions</p>
                <h3 class="text-3xl font-bold mt-2">15</h3>
                </div>
            </div>

            <!-- Jobseekers contacted -->
            <div x-data="sessionManager()" class="bg-white p-6 rounded-lg shadow relative">
                <!-- Tabs -->
                <div class="flex space-x-6 border-b mb-4 text-sm font-medium text-gray-600">
                    <button @click="switchTab('upcoming')" :class="tabClass('upcoming')">Upcoming sessions</button>
                    <button @click="switchTab('cancelled')" :class="tabClass('cancelled')">Cancelled sessions</button>
                    <button @click="switchTab('completed')" :class="tabClass('completed')">Completed sessions</button>
                </div>
                <!-- Session List -->
                <template x-for="(session, index) in paginatedSessions()" :key="index">
                    <div class="flex justify-between items-center border-b py-4">
                        <div class="flex items-center space-x-4">
                            <img :src="session.img" class="w-12 h-12 rounded-full object-cover" alt="Profile" />
                            <div>
                                <h4 class="font-semibold text-sm" x-text="session.name"></h4>
                                <p class="text-sm text-gray-500" x-text="session.role"></p>
                            </div>
                        </div>

                        <div class="flex flex-1 justify-between ml-10 text-sm text-gray-700">
                            <div>
                                <p class="font-semibold">Date</p>
                                <p x-text="session.date"></p>
                            </div>
                            <div>
                                <p class="font-semibold">Time</p>
                                <p x-text="session.time"></p>
                            </div>
                            <div>
                                <p class="font-semibold">Agenda</p>
                                <p x-text="session.agenda"></p>
                            </div>
                            <div>
                                <p class="font-semibold">Mode</p>
                                <p x-text="session.mode"></p>
                            </div>
                        </div>

                        <div class="flex space-x-2">
                            <template x-if="currentTab === 'upcoming'">
                                <div class="flex space-x-2">
                                    <button class="border border-red-500 text-red-500 px-4 py-1.5 rounded hover:bg-red-50 text-sm"
                                        @click="openCancelModal(session)">Cancel</button>
                                    <button class="bg-blue-600 text-white px-4 py-1.5 rounded hover:bg-blue-700 text-sm"
                                        @click="joinSession(session)">Join</button>
                                </div>
                            </template>

                            <template x-if="currentTab === 'completed'">
                                <button class="bg-green-600 text-white px-4 py-1.5 rounded hover:bg-green-700 text-sm"
                                    @click="openFeedbackModal(session)">View Feedback</button>
                            </template>

                            <template x-if="currentTab === 'cancelled'">
                                <span class="text-sm text-gray-400 italic">Cancelled</span>
                            </template>
                        </div>
                    </div>
                </template>

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

                <!-- Feedback Modal -->
                <div x-show="showFeedbackModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 relative">
                        <button @click="closeFeedbackModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
                        <h3 class="text-center text-lg font-semibold text-gray-800 mb-6">Jobseekers feedback</h3>
                        <div class="flex items-center justify-center flex-col mb-4 text-center">
                            <img :src="selectedFeedback?.img" class="w-16 h-16 rounded-full object-cover mb-2" alt="Profile" />
                            <h4 class="font-semibold text-base text-gray-800" x-text="selectedFeedback?.name"></h4>
                            <p class="text-sm text-gray-500" x-text="selectedFeedback?.role"></p>
                        </div>
                        <p class="text-sm text-gray-700 text-center leading-relaxed" x-text="selectedFeedback?.feedback"></p>
                        <div class="flex justify-center mt-6">
                            <button class="px-6 py-2 bg-blue-700 text-white rounded-md hover:bg-blue-800 text-sm">Reply</button>
                        </div>
                    </div>
                </div>

                <!-- Cancel Modal -->
                <div x-show="showCancelModal" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-6 relative">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-md font-semibold text-gray-800">
                                Cancel this session - 
                                <span class="font-normal text-gray-600" x-text="cancelledSession?.name + ' / Date: ' + cancelledSession?.date + ' / Time: ' + cancelledSession?.time"></span>
                            </h3>
                            <button @click="closeCancelModal" class="text-gray-500 hover:text-gray-700 text-xl leading-none">&times;</button>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reason for cancellation</label>
                            <textarea x-model="cancelReason" class="w-full border border-gray-300 rounded-md p-2 text-sm resize-none" rows="3" placeholder="Write here..."></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Postpone session</label>
                                <select x-model="postponeSession" class="w-full border border-gray-300 rounded-md p-2 text-sm">
                                    <option>No</option>
                                    <option>Yes</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Select session date</label>
                                <input type="date" x-model="newDate" class="w-full border border-gray-300 rounded-md p-2 text-sm" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Select session time</label>
                                <select x-model="newTime" class="w-full border border-gray-300 rounded-md p-2 text-sm">
                                    <option>Select time</option>
                                    <option>02:00 PM - 03:00 PM</option>
                                    <option>03:00 PM - 04:00 PM</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button @click="confirmCancel" class="px-6 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700">Cancel Session</button>
                            <button @click="confirmReschedule" class="px-6 py-2 bg-gray-700 text-white rounded-md text-sm hover:bg-gray-800">Reschedule</button>
                        </div>
                    </div>
                </div>

            </div>

            <script>
            function sessionManager() {
                return {
                    currentTab: 'upcoming',
                    currentPage: 1,
                    perPage: 4,
                    cancelledSession: null,
                    showCancelModal: false,
                    showFeedbackModal: false,
                    selectedFeedback: null,

                    cancelReason: '',
                    postponeSession: 'No',
                    newDate: '',
                    newTime: '',

                    sessions: {
                        upcoming: [
                            { name: 'Peter Parker', role: 'UI UX designer', date: '01/05/2025', time: '02:00 – 03:00 am', agenda: 'UI design', mode: 'Online', img: 'https://randomuser.me/api/portraits/men/1.jpg' },
                            { name: 'Mohit Raina', role: 'UI UX designer', date: '01/05/2025', time: '03:00 – 04:00 am', agenda: 'UI design', mode: 'Online', img: 'https://randomuser.me/api/portraits/men/2.jpg' },
                            { name: 'Prabhakar Mishra', role: 'UI UX designer', date: '24/04/2025', time: '02:00 – 03:00 am', agenda: 'UI design', mode: 'Online', img: 'https://randomuser.me/api/portraits/men/3.jpg' },
                            { name: 'Sohail Sheikh', role: 'UI UX designer', date: '23/04/2025', time: '02:00 – 03:00 am', agenda: 'UX design', mode: 'Online', img: 'https://randomuser.me/api/portraits/men/4.jpg' }
                        ],
                        cancelled: [
                            { name: 'Arjun Singh', role: 'UX designer', date: '22/04/2025', time: '02:00 – 03:00 am', agenda: 'UX review', mode: 'Online', img: 'https://randomuser.me/api/portraits/men/5.jpg' }
                        ],
                        completed: [
                            { name: 'Neha Sharma', role: 'UI/UX Lead', date: '20/04/2025', time: '01:00 – 02:00 am', agenda: 'UX Audit', mode: 'Online', img: 'https://randomuser.me/api/portraits/women/6.jpg', feedback: 'Great session, very insightful.' }
                        ]
                    },

                    switchTab(tab) {
                        this.currentTab = tab;
                        this.currentPage = 1;
                    },
                    tabClass(tab) {
                        return this.currentTab === tab
                            ? 'text-blue-600 border-b-2 border-blue-600 pb-2'
                            : 'pb-2 hover:text-blue-600';
                    },
                    paginatedSessions() {
                        const list = this.sessions[this.currentTab] || [];
                        const start = (this.currentPage - 1) * this.perPage;
                        return list.slice(start, start + this.perPage);
                    },
                    totalPages() {
                        const list = this.sessions[this.currentTab] || [];
                        return Math.ceil(list.length / this.perPage) || 1;
                    },
                    prevPage() {
                        if (this.currentPage > 1) this.currentPage--;
                    },
                    nextPage() {
                        if (this.currentPage < this.totalPages()) this.currentPage++;
                    },
                    joinSession(session) {
                        alert(`Joining session with ${session.name}`);
                    },
                    openCancelModal(session) {
                        this.cancelledSession = session;
                        this.showCancelModal = true;
                    },
                    closeCancelModal() {
                        this.cancelledSession = null;
                        this.showCancelModal = false;
                        this.cancelReason = '';
                        this.postponeSession = 'No';
                        this.newDate = '';
                        this.newTime = '';
                    },
                    confirmCancel() {
                        this.sessions.upcoming = this.sessions.upcoming.filter(s => s !== this.cancelledSession);
                        this.sessions.cancelled.push(this.cancelledSession);
                        this.closeCancelModal();
                    },
                    confirmReschedule() {
                        alert(`Rescheduling ${this.cancelledSession.name} to ${this.newDate} ${this.newTime}`);
                        this.closeCancelModal();
                    },
                    openFeedbackModal(session) {
                        this.selectedFeedback = session;
                        this.showFeedbackModal = true;
                    },
                    closeFeedbackModal() {
                        this.selectedFeedback = null;
                        this.showFeedbackModal = false;
                    }
                }
            }
            </script>
    </main>
  </div>
</div>

<!-- Feather Icons -->
<script src="https://unpkg.com/feather-icons"></script>
<script>feather.replace();</script>




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
        <!-- Feather Icons -->
        <script>
            feather.replace()
        </script>
          
@include('site.assessor.componants.footer')