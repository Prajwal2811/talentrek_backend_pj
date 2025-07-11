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
            @include('site.mentor.componants.sidebar')

            <div class="flex-1 flex flex-col">
                @include('site.mentor.componants.navbar')

          <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="createBookingSlots()">
    <h2 class="text-2xl font-semibold mb-4">Booking slots 
        <span class="text-gray-400">> Create booking slots</span>
    </h2>

    <div class="flex gap-6">
        <!-- Left: Calendar -->
        <div class="bg-white p-4 rounded-lg shadow w-1/3">
            <h3 class="text-lg font-semibold mb-3">Date</h3>

            <div class="border p-2 rounded" x-data>
                <!-- Month Navigation -->
                <div class="flex justify-between items-center mb-2">
                    <button @click="prevMonth" class="text-sm text-gray-600 hover:text-black">&lt;</button>
                    <div class="text-center font-medium">
                        <span x-text="monthNames[currentMonth] + ' ' + currentYear"></span>
                    </div>
                    <button @click="nextMonth" class="text-sm text-gray-600 hover:text-black">&gt;</button>
                </div>

                <!-- Weekdays -->
                <div class="grid grid-cols-7 text-center text-sm text-gray-600 mb-2">
                    <template x-for="day in weekDays" :key="day">
                        <div x-text="day"></div>
                    </template>
                </div>

                <!-- Days -->
                <div class="grid grid-cols-7 text-center text-sm gap-1">
                    <template x-for="blank in blanks" :key="'b'+blank">
                        <div></div>
                    </template>
                    <template x-for="day in daysInMonth" :key="day">
                        <button
                            @click="selectedDate = day"
                            class="py-1 rounded"
                            :class="{
                                'bg-blue-100': selectedDate === day,
                                'bg-red-500 text-white': isToday(day),
                                'hover:bg-blue-200': selectedDate !== day && !isToday(day),
                                'line-through text-gray-400': isUnavailable(day)
                            }"
                            x-text="day"
                        ></button>
                    </template>
                </div>

                <button class="mt-3 w-full py-2 bg-red-100 text-red-600 rounded border border-red-300 hover:bg-red-200"
                    @click="markUnavailable">
                    Mark unavailable
                </button>
            </div>

            <p class="text-xs text-gray-500 mt-2">
                Note: To mark the date unavailable, select the date and click on "Mark unavailable".
            </p>
        </div>

        <!-- Right: Time Slots Table -->
        <div class="bg-white p-4 rounded-lg shadow w-2/3">
            <!-- Header with Mode Tabs -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Time slots</h3>
                <div>
                    <button 
                        @click="activeMode = 'online'"
                        :class="activeMode === 'online' ? 'text-blue-600 font-medium mr-2 border-b-2 border-blue-600' : 'text-gray-400 font-medium mr-2'">
                        Online mode
                    </button>
                    <button 
                        @click="activeMode = 'offline'"
                        :class="activeMode === 'offline' ? 'text-blue-600 font-medium border-b-2 border-blue-600' : 'text-gray-400 font-medium'">
                        Offline mode
                    </button>
                </div>
            </div>

            <!-- Dynamic Date Display -->
            <div class="text-sm text-gray-700 mb-2">
                Date: 
                <span class="font-medium" x-text="selectedDateFormatted()"></span>
            </div>

            <!-- Time Slots Table -->
            <table class="w-full table-auto border text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Sr. No.</th>
                        <th class="px-4 py-2 text-left">Time</th>
                        <th class="px-4 py-2 text-left">Availability</th>
                        <th class="px-4 py-2 text-left">Edit</th>
                        <th class="px-4 py-2 text-left">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(slot, index) in activeSlots" :key="index">
                        <tr class="border-b">
                            <td class="px-4 py-2" x-text="index + 1"></td>
                            <td class="px-4 py-2" x-text="slot.time"></td>
                            <td class="px-4 py-2">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" x-model="slot.available" class="toggle-checkbox" />
                                    <span :class="slot.available ? 'text-green-600' : 'text-red-600'" x-text="slot.available ? 'Available' : 'Unavailable'"></span>
                                </div>
                            </td>
                            <td class="px-4 py-2">
                                <button class="bg-blue-800 text-white hover:bg-blue-700 rounded-full p-2">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                            <td class="px-4 py-2">
                                <button class="bg-red-800 text-white hover:bg-red-700 rounded-full p-2">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
    function createBookingSlots() {
        return {
            today: new Date(),
            currentMonth: new Date().getMonth(),
            currentYear: new Date().getFullYear(),
            selectedDate: null,
            unavailableDates: [],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June',
                         'July', 'August', 'September', 'October', 'November', 'December'],
            weekDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],

            activeMode: 'online',
            onlineSlots: [
                { time: '10:00 AM - 11:00 AM', available: true },
                { time: '11:30 AM - 12:30 PM', available: false },
                { time: '2:00 PM - 3:00 PM', available: true }
            ],
            offlineSlots: [
                { time: '9:00 AM - 10:00 AM', available: true },
                { time: '1:00 PM - 2:00 PM', available: false },
                { time: '3:30 PM - 4:30 PM', available: true }
            ],

            get daysInMonth() {
                return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
            },
            get blanks() {
                return new Date(this.currentYear, this.currentMonth, 1).getDay();
            },
            isToday(day) {
                const d = new Date(this.currentYear, this.currentMonth, day);
                return d.toDateString() === this.today.toDateString();
            },
            isUnavailable(day) {
                const date = new Date(this.currentYear, this.currentMonth, day).toDateString();
                return this.unavailableDates.includes(date);
            },
            markUnavailable() {
                if (this.selectedDate) {
                    const date = new Date(this.currentYear, this.currentMonth, this.selectedDate).toDateString();
                    if (!this.unavailableDates.includes(date)) {
                        this.unavailableDates.push(date);
                        alert(`Marked ${date} as unavailable`);
                    }
                } else {
                    alert('Please select a date first');
                }
            },
            prevMonth() {
                if (this.currentMonth === 0) {
                    this.currentMonth = 11;
                    this.currentYear--;
                } else {
                    this.currentMonth--;
                }
                this.selectedDate = null;
            },
            nextMonth() {
                if (this.currentMonth === 11) {
                    this.currentMonth = 0;
                    this.currentYear++;
                } else {
                    this.currentMonth++;
                }
                this.selectedDate = null;
            },
            selectedDateFormatted() {
                if (!this.selectedDate) return 'Select a date';
                const date = new Date(this.currentYear, this.currentMonth, this.selectedDate);
                return date.toLocaleDateString('en-GB'); // DD/MM/YYYY
            },
            get activeSlots() {
                return this.activeMode === 'online' ? this.onlineSlots : this.offlineSlots;
            }
        };
    }
</script>

<style>
    .toggle-checkbox {
        appearance: none;
        width: 34px;
        height: 18px;
        background-color: #ccc;
        border-radius: 9999px;
        position: relative;
        outline: none;
        cursor: pointer;
    }
    .toggle-checkbox:checked {
        background-color: #4ade80;
    }
    .toggle-checkbox:checked::before {
        transform: translateX(16px);
    }
    .toggle-checkbox::before {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        width: 14px;
        height: 14px;
        background-color: white;
        border-radius: 9999px;
        transition: transform 0.3s ease;
    }
</style>

 
 
        



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
