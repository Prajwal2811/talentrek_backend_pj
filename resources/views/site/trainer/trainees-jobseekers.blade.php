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

             <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="trainingDashboard()">
                <h2 class="text-2xl font-semibold mb-6">Jobseekers list</h2>

                <div class="bg-white rounded-md shadow-md">
                    <!-- Header -->
                    <div class="flex items-center justify-between px-6 py-4 border-b">
                        <h3 class="font-medium">Jobseekers list</h3>
                        <div class="flex space-x-4 text-sm font-medium">
                            <button 
                                @click="setActiveTab('Online')" 
                                :class="{'text-black font-semibold border-b-2 border-blue-600': activeTab === 'Online', 'text-gray-400': activeTab !== 'Online'}"
                                class="pb-1"
                            >
                                Online
                            </button>
                            <button 
                                @click="setActiveTab('Offline')" 
                                :class="{'text-black font-semibold border-b-2 border-blue-600': activeTab === 'Offline', 'text-gray-400': activeTab !== 'Offline'}"
                                class="pb-1"
                            >
                                Offline
                            </button>
                        </div>
                    </div>

                    <!-- List -->
                    <div class="divide-y">
                        <template x-for="(user, index) in paginatedBatches" :key="user.id">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between px-6 py-4 gap-4">
                                <!-- Profile Info -->
                                <div class="flex items-center gap-4">
                                    <img :src="user.avatar" alt="" class="w-14 h-14 rounded-full object-cover" />
                                    <div>
                                        <p class="font-semibold" x-text="user.name"></p>
                                        <p class="text-sm text-gray-500" x-text="user.designation"></p>
                                    </div>
                                </div>

                                <!-- Info Grid -->
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm text-gray-700 flex-1 md:ml-10">
                                    <div>
                                        <p class="font-semibold">Batch</p>
                                        <p x-text="user.batchName"></p>
                                    </div>
                                    <div>
                                        <p class="font-semibold">Course</p>
                                        <p x-text="user.courseName"></p>
                                    </div>
                                    <div>
                                        <p class="font-semibold">Enrollment no</p>
                                        <p x-text="user.enrollmentNo"></p>
                                    </div>
                                    <div>
                                        <p class="font-semibold">Mode</p>
                                        <p x-text="user.mode"></p>
                                    </div>
                                </div>

                                <!-- Chat Button -->
                                <div>
                                    <button class="border border-gray-300 px-4 py-1 rounded-md text-sm hover:bg-gray-100">
                                        Chat
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-end items-center px-6 py-4 space-x-4 border-t">
                        <button @click="prevPage" :disabled="currentPage === 1"
                            class="text-sm px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50">
                            Previous
                        </button>
                        <span class="text-sm text-gray-600">
                            Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span>
                        </span>
                        <button @click="nextPage" :disabled="currentPage >= totalPages"
                            class="text-sm px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50">
                            Next
                        </button>
                    </div>
                </div>
            </main>

            <script>
                function trainingDashboard() {
                    return {
                        currentPage: 1,
                        itemsPerPage: 10,
                        activeTab: 'Online', // default tab
                        batches: [
                            {
                                id: 1,
                                name: "Peter Parker",
                                designation: "Graphic designer",
                                batchName: "Batch 01",
                                courseName: "UI/UX design",
                                enrollmentNo: "5511165635",
                                mode: "Online",
                                avatar: "https://randomuser.me/api/portraits/men/11.jpg",
                            },
                            {
                                id: 2,
                                name: "Mohit Raina",
                                designation: "Frontend developer",
                                batchName: "Batch 02",
                                courseName: "Php development",
                                enrollmentNo: "2443835881",
                                mode: "Online",
                                avatar: "https://randomuser.me/api/portraits/men/12.jpg",
                            },
                            {
                                id: 3,
                                name: "Prabhakar Mishra",
                                designation: "Java developer",
                                batchName: "Batch 03",
                                courseName: "Full stack development",
                                enrollmentNo: "5545678987",
                                mode: "Offline",
                                avatar: "https://randomuser.me/api/portraits/men/13.jpg",
                            },
                            {
                                id: 4,
                                name: "Sohail Sheikh",
                                designation: "Logo designer",
                                batchName: "Batch 04",
                                courseName: "Motion graphics design",
                                enrollmentNo: "5555324561",
                                mode: "Online",
                                avatar: "https://randomuser.me/api/portraits/men/14.jpg",
                            },
                        ],

                        // Filter batches by activeTab (mode)
                        get filteredBatches() {
                            return this.batches.filter(user => user.mode === this.activeTab);
                        },

                        get totalPages() {
                            return Math.ceil(this.filteredBatches.length / this.itemsPerPage) || 1;
                        },

                        get paginatedBatches() {
                            const start = (this.currentPage - 1) * this.itemsPerPage;
                            return this.filteredBatches.slice(start, start + this.itemsPerPage);
                        },

                        prevPage() {
                            if (this.currentPage > 1) this.currentPage--;
                        },

                        nextPage() {
                            if (this.currentPage < this.totalPages) this.currentPage++;
                        },

                        setActiveTab(tab) {
                            if (this.activeTab !== tab) {
                                this.activeTab = tab;
                                this.currentPage = 1; // reset page on tab change
                            }
                        }
                    };
                }
            </script>




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
