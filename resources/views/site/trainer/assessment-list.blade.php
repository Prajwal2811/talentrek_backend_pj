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
                    <h2 class="text-2xl font-semibold mb-6">Assessment List</h2>
                    <div class="overflow-x-auto bg-white rounded-lg shadow relative">
                        <table class="min-w-full text-sm text-left">
                            <thead class="bg-gray-100 text-gray-700">
                                <tr>
                                    <th class="px-6 py-3">Sr. No.</th>
                                    <th class="px-6 py-3">Assessment Title</th>
                                    <th class="px-6 py-3">Questions</th>
                                    <th class="px-6 py-3">Passing Percentage</th>
                                    <th class="px-6 py-3">Level</th>
                                    <th class="px-6 py-3">Assign Course</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(course, index) in paginatedCourses" :key="index">
                                    <tr class="border-t">
                                        <td class="px-6 py-4" x-text="startIndex + index + 1"></td>
                                        <td class="px-6 py-4" x-text="course.title"></td>
                                        <td class="px-6 py-4" x-text="course.duration + ' Qs'"></td>
                                        <td class="px-6 py-4" x-text="course.passing + '%'"></td>
                                        <td class="px-6 py-4" x-text="course.level"></td>
                                        <td class="px-6 py-4">
                                            <select class="text-xs px-4 py-1 rounded hover:bg-white-600">
                                                <option selected disabled>Assign Course</option>
                                                <option>Java Basics</option>
                                                <option>React JS</option>
                                                <option>Python Programming</option>
                                                <option>Data Structures</option>
                                                <option>Node.js Fundamentals</option>
                                            </select>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>

                        <!-- Pagination -->
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
                </main>

                <script>
                    function trainingDashboard() {
                        return {
                            courses: [
                                { title: 'Java Basics', duration: 10, passing: 60, level: 'Beginner' },
                                { title: 'React JS', duration: 15, passing: 70, level: 'Intermediate' },
                                { title: 'Data Structures', duration: 20, passing: 80, level: 'Advanced' },
                                { title: 'Python Programming', duration: 18, passing: 65, level: 'Intermediate' },
                                { title: 'Node.js Fundamentals', duration: 12, passing: 70, level: 'Intermediate' },
                                { title: 'Database Management', duration: 14, passing: 75, level: 'Advanced' },
                                { title: 'Cybersecurity Essentials', duration: 11, passing: 60, level: 'Beginner' },
                                { title: 'UI/UX Design', duration: 16, passing: 85, level: 'Intermediate' },
                                { title: 'Machine Learning', duration: 22, passing: 80, level: 'Advanced' },
                                { title: 'Agile Methodologies', duration: 8, passing: 55, level: 'Beginner' },
                                { title: 'Cloud Computing', duration: 17, passing: 78, level: 'Advanced' }
                            ],
                            currentPage: 1,
                            perPage: 10,
                            get totalPages() {
                                return Math.ceil(this.courses.length / this.perPage);
                            },
                            get paginatedCourses() {
                                const start = (this.currentPage - 1) * this.perPage;
                                return this.courses.slice(start, start + this.perPage);
                            },
                            get startIndex() {
                                return (this.currentPage - 1) * this.perPage;
                            },
                            nextPage() {
                                if (this.currentPage < this.totalPages) this.currentPage++;
                            },
                            prevPage() {
                                if (this.currentPage > 1) this.currentPage--;
                            }
                        }
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
