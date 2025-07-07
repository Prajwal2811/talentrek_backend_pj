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
          
            @include('site.trainer.componants.sidebar')

            <div class="flex-1 flex flex-col">
                <nav class="bg-white shadow-md px-6 py-3 flex items-center justify-between">
                    <div class="flex items-center space-x-6 w-1/2">
                    <div class="text-xl font-bold text-blue-900 block lg:hidden">
                        Talent<span class="text-blue-500">rek</span>
                    </div>
                    <!-- <div class="relative w-full">
                        <input type="text" placeholder="Search for talent" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                            <i class="fas fa-search"></i>
                        </button>
                    </div> -->
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
                <div class="grid grid-cols-12 gap-4 mb-6">
                    <!-- First two cards side by side in col-span-6 -->
                    <div class="col-span-6 grid grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-lg shadow text-sm">
                            <p class="text-gray-700">Total courses</p>
                            <h3 class="text-3xl font-semibold mt-1">24</h3>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow text-sm">
                            <p class="text-gray-700">Enrolled jobseekers</p>
                            <h3 class="text-3xl font-semibold mt-1">310</h3>
                        </div>
                    </div>

                    <!-- Third card with counts inline (col-span-6) -->
                    <div class="col-span-6">
                        <div class="bg-white p-4 rounded-lg shadow text-sm flex justify-between items-start">
                            <div>
                                <p class="text-gray-700">Total upcoming sessions</p>
                                <h3 class="text-3xl font-semibold mt-1">5</h3>
                            </div>
                            <div class="mt-6 space-x-4 text-sm mt-5">
                                <span class="text-green-600 font-medium">Completed: <span class="font-bold">2</span></span>
                                <span class="text-red-500 font-medium">Pending: <span class="font-bold">3</span></span>
                            </div>
                        </div>
                    </div>
                </div>


                <div x-data="dashboard()" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Enrolled Jobseekers -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-base font-semibold mb-3">Enrolled jobseekers</h3>
                        <div class="divide-y">
                            <template x-for="jobseeker in visibleJobseekers" :key="jobseeker.enrollment">
                                <div class="flex justify-between items-center py-3">
                                    <div class="flex items-center space-x-3">
                                        <img :src="jobseeker.img" class="w-10 h-10 rounded-full object-cover" alt="Profile" />
                                        <div>
                                            <h4 class="font-medium text-sm" x-text="jobseeker.name"></h4>
                                            <p class="text-xs text-gray-500" x-text="jobseeker.role"></p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600" x-text="`Enrollment No:${jobseeker.enrollment}`"></p>
                                </div>
                            </template>
                        </div>
                        <div class="pt-4 text-sm text-blue-600 text-right">
                            <button x-show="visibleJobseekers.length < jobseekers.length" @click="loadMoreJobseekers()" class="hover:underline">See all</button>
                        </div>
                    </div>

                    <!-- Today's Sessions -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-base font-semibold mb-3">Today's sessions</h3>
                        <div class="divide-y">
                            <template x-for="session in visibleSessions" :key="session.title + session.time">
                                <div class="flex justify-between items-center py-3">
                                    <div>
                                        <h4 class="text-sm font-medium" x-text="session.title"></h4>
                                        <p class="text-xs text-gray-500">
                                            Time: <span x-text="session.time"></span> &nbsp;&nbsp;
                                            Batch: <span x-text="session.batch"></span>
                                        </p>
                                    </div>
                                    <button class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700">Join</button>
                                </div>
                            </template>
                        </div>
                        <div class="pt-4 text-sm text-blue-600 text-right">
                            <button x-show="visibleSessions.length < sessions.length" @click="loadMoreSessions()" class="hover:underline">See all</button>
                        </div>
                    </div>
                </div>

                <script>
                    function dashboard() {
                        return {
                            // Full data lists
                            jobseekers: [
                                { name: 'Ravi Kumar', role: 'UI UX Designer', enrollment: '373857', img: 'https://randomuser.me/api/portraits/men/11.jpg' },
                                { name: 'Anjali Sharma', role: 'Frontend Dev', enrollment: '374122', img: 'https://randomuser.me/api/portraits/women/12.jpg' },
                                { name: 'Vikram Singh', role: 'Backend Dev', enrollment: '374890', img: 'https://randomuser.me/api/portraits/men/13.jpg' },
                                { name: 'Neha Verma', role: 'QA Engineer', enrollment: '372398', img: 'https://randomuser.me/api/portraits/women/14.jpg' },
                                { name: 'Amit Patel', role: 'Data Analyst', enrollment: '375671', img: 'https://randomuser.me/api/portraits/men/15.jpg' },
                                { name: 'Kiran Joshi', role: 'DevOps Engineer', enrollment: '375999', img: 'https://randomuser.me/api/portraits/women/16.jpg' },
                                { name: 'Rahul Mehta', role: 'React Developer', enrollment: '376321', img: 'https://randomuser.me/api/portraits/men/17.jpg' },
                            ],
                            sessions: [
                                { title: 'UI UX design - Basic', time: '03:15 pm', batch: 'Batch 01' },
                                { title: 'UI UX design - Basic', time: '05:15 pm', batch: 'Batch 02' },
                                { title: 'Graphic design - Basic', time: '01:15 pm', batch: 'Batch 01' },
                                { title: 'Graphic design - Basic', time: '03:15 pm', batch: 'Batch 02' },
                                { title: 'Web design - Advanced', time: '10:15 am', batch: 'Batch 03' },
                                { title: 'Digital Marketing', time: '04:00 pm', batch: 'Batch 01' },
                            ],

                            // Pagination control
                            visibleJobseekers: [],
                            visibleSessions: [],
                            limit: 5,

                            // On init
                            init() {
                                this.visibleJobseekers = this.jobseekers.slice(0, this.limit);
                                this.visibleSessions = this.sessions.slice(0, this.limit);
                            },

                            // Load more
                            loadMoreJobseekers() {
                                const next = this.visibleJobseekers.length + this.limit;
                                this.visibleJobseekers = this.jobseekers.slice(0, next);
                            },
                            loadMoreSessions() {
                                const next = this.visibleSessions.length + this.limit;
                                this.visibleSessions = this.sessions.slice(0, next);
                            }
                        };
                    }

                </script>


            
            </main>

            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
          



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
