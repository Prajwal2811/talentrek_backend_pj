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
                    <h2 class="text-2xl font-semibold mb-6">Training programs list</h2>

                    <!-- Tabs -->
                    <div class="mb-4 border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8 text-sm font-medium">
                            <button @click="switchTab('recorded')" :class="activeTab === 'recorded' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600'" class="pb-2 px-1">Recorded Lecture</button>
                            <button @click="switchTab('online')" :class="activeTab === 'online' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600'" class="pb-2 px-1">Online training</button>
                            <button @click="switchTab('offline')" :class="activeTab === 'offline' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600'" class="pb-2 px-1">Offline training</button>
                        </nav>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto bg-white rounded-lg shadow relative">
                        <table class="min-w-full text-sm text-left">
                            <thead class="bg-gray-100 text-gray-700">
                                <tr>
                                    <th class="px-6 py-3">Sr. No.</th>
                                    <th class="px-6 py-3">Course Title</th>
                                    <th class="px-6 py-3">Duration</th>
                                    <th class="px-6 py-3">Price</th>
                                    <th class="px-6 py-3">Level</th>
                                    <th class="px-6 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(course, index) in paginatedCourses" :key="index">
                                    <tr class="border-t">
                                        <td class="px-6 py-4" x-text="startIndex + index + 1"></td>
                                        <td class="px-6 py-4" x-text="course.title"></td>
                                        <td class="px-6 py-4" x-text="course.duration + ' hr'"></td>
                                        <td class="px-6 py-4" x-text="'SAR ' + course.price"></td>
                                        <td class="px-6 py-4" x-text="course.level"></td>
                                        <td class="px-6 py-4">
                                            <button class="bg-blue-500 text-white text-xs px-4 py-1 rounded">Edit</button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>

                        <!-- Pagination (Right Bottom) -->
                        <div class="flex justify-end items-center px-6 py-4 space-x-4">
                            <button @click="prevPage" :disabled="currentPage === 1"
                                class="text-sm px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50">Previous</button>
                            <span class="text-sm text-gray-600">
                                Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span>
                            </span>
                            <button @click="nextPage" :disabled="currentPage >= totalPages"
                                class="text-sm px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50">Next</button>
                        </div>
                    </div>

                    <!-- Alpine Script -->
                    <script>
                        function trainingDashboard() {
                            return {
                                activeTab: 'recorded',
                                perPage: 2,
                                currentPage: 1,

                                courses: {
                                    recorded: [
                                        { title: 'Java Development', duration: 100, price: 150, level: 'Intermediate' },
                                        { title: 'My SQL', duration: 30, price: 100, level: 'Advance' },
                                        { title: 'Graphics Designing', duration: 15, price: 60, level: 'Intermediate' },
                                        { title: 'Video Editing', duration: 10, price: 75, level: 'Beginner' },
                                    ],
                                    online: [
                                        { title: 'Python for Web', duration: 50, price: 120, level: 'Beginner' },
                                        { title: 'React & Node.js', duration: 80, price: 200, level: 'Intermediate' },
                                        { title: 'Vue.js Masterclass', duration: 60, price: 180, level: 'Advance' },
                                    ],
                                    offline: [
                                        { title: 'Digital Marketing', duration: 40, price: 110, level: 'Intermediate' },
                                        { title: 'Excel Masterclass', duration: 20, price: 90, level: 'Beginner' },
                                        { title: 'Office Productivity', duration: 25, price: 95, level: 'Beginner' },
                                    ]
                                },

                                switchTab(tab) {
                                    this.activeTab = tab;
                                    this.currentPage = 1;
                                },

                                get filteredCourses() {
                                    return this.courses[this.activeTab];
                                },

                                get totalPages() {
                                    return Math.ceil(this.filteredCourses.length / this.perPage);
                                },

                                get startIndex() {
                                    return (this.currentPage - 1) * this.perPage;
                                },

                                get paginatedCourses() {
                                    return this.filteredCourses.slice(this.startIndex, this.startIndex + this.perPage);
                                },

                                nextPage() {
                                    if (this.currentPage < this.totalPages) this.currentPage++;
                                },

                                prevPage() {
                                    if (this.currentPage > 1) this.currentPage--;
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
