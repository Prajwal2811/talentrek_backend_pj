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
                <h2 class="text-2xl font-semibold mb-6">Batch List</h2>
                <div class="overflow-x-auto bg-white rounded-lg shadow relative">
                    <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                        <th class="px-6 py-3">Sr. No.</th>
                        <th class="px-6 py-3">Batch Name</th>
                        <th class="px-6 py-3">Course name</th>
                        <th class="px-6 py-3">Enrolled Student</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Time</th>
                        <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(batch, index) in paginatedBatches" :key="batch.id">
                        <tr class="border-t">
                            <td class="px-6 py-4" x-text="startIndex + index + 1"></td>
                            <td class="px-6 py-4" x-text="batch.batchName"></td>
                            <td class="px-6 py-4" x-text="batch.courseName"></td>
                            <td class="px-6 py-4" x-text="batch.enrolledStudents"></td>
                            <td class="px-6 py-4" x-text="batch.date"></td>
                            <td class="px-6 py-4" x-text="batch.time"></td>
                            <td class="px-6 py-4 flex space-x-4">
                            <!-- Edit Icon Button with circular blue background -->
                            <button @click="editBatch(batch.id)"
                                class="bg-blue-500 text-white hover:bg-blue-700 p-2 rounded-full w-8 h-8 flex items-center justify-center"
                                aria-label="Edit Batch">
                                <i class="fa fa-pen" aria-hidden="true"></i>
                            </button>

                            <!-- Delete Icon Button with circular blue background -->
                            <button @click="deleteBatch(batch.id)"
                                class="bg-red-500 text-white hover:bg-red-700 p-2 rounded-full w-8 h-8 flex items-center justify-center"
                                aria-label="Delete Batch">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </button>

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
                    batches: [
                        { id: 1, batchName: 'Batch A', courseName: 'Java Basics', enrolledStudents: 30, date: '2025-06-10', time: '10:00 AM - 12:00 PM' },
                        { id: 2, batchName: 'Batch B', courseName: 'React JS', enrolledStudents: 25, date: '2025-06-12', time: '02:00 PM - 04:00 PM' },
                        { id: 3, batchName: 'Batch C', courseName: 'Python Programming', enrolledStudents: 28, date: '2025-06-15', time: '11:00 AM - 01:00 PM' },
                        { id: 4, batchName: 'Batch D', courseName: 'Data Structures', enrolledStudents: 20, date: '2025-06-18', time: '03:00 PM - 05:00 PM' },
                        { id: 5, batchName: 'Batch E', courseName: 'Node.js Fundamentals', enrolledStudents: 22, date: '2025-06-20', time: '09:00 AM - 11:00 AM' },
                        { id: 6, batchName: 'Batch F', courseName: 'UI/UX Design', enrolledStudents: 18, date: '2025-06-22', time: '01:00 PM - 03:00 PM' },
                        { id: 7, batchName: 'Batch G', courseName: 'Machine Learning', enrolledStudents: 15, date: '2025-06-25', time: '10:00 AM - 12:00 PM' },
                        { id: 8, batchName: 'Batch H', courseName: 'Cloud Computing', enrolledStudents: 27, date: '2025-06-27', time: '02:00 PM - 04:00 PM' },
                        { id: 9, batchName: 'Batch I', courseName: 'Cybersecurity Essentials', enrolledStudents: 16, date: '2025-06-30', time: '11:00 AM - 01:00 PM' },
                        { id: 10, batchName: 'Batch J', courseName: 'Agile Methodologies', enrolledStudents: 19, date: '2025-07-02', time: '03:00 PM - 05:00 PM' },
                        { id: 11, batchName: 'Batch K', courseName: 'Database Management', enrolledStudents: 23, date: '2025-07-05', time: '09:00 AM - 11:00 AM' }
                    ],
                    currentPage: 1,
                    perPage: 5,
                    get totalPages() {
                        return Math.ceil(this.batches.length / this.perPage);
                    },
                    get paginatedBatches() {
                        const start = (this.currentPage - 1) * this.perPage;
                        return this.batches.slice(start, start + this.perPage);
                    },
                    get startIndex() {
                        return (this.currentPage - 1) * this.perPage;
                    },
                    nextPage() {
                        if (this.currentPage < this.totalPages) this.currentPage++;
                    },
                    prevPage() {
                        if (this.currentPage > 1) this.currentPage--;
                    },
                    editBatch(id) {
                        alert('Edit batch with ID: ' + id);
                        // Add your edit logic here
                    },
                    deleteBatch(id) {
                        if (confirm('Are you sure you want to delete batch with ID: ' + id + '?')) {
                        this.batches = this.batches.filter(batch => batch.id !== id);
                        if (this.currentPage > this.totalPages) this.currentPage = this.totalPages;
                        }
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
