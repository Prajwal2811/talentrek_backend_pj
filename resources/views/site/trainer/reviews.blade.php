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

           <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="reviewsDashboard()">
            <h2 class="text-2xl font-semibold mb-6">Reviews</h2>
            <div class="bg-white p-4">
                <h3 class="text-md font-semibold mb-4">Jobseeker reviews</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border-collapse">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3">Sr. No.</th>
                                <th class="px-4 py-3">Course</th>
                                <th class="px-4 py-3">Jobseeker name</th>
                                <th class="px-4 py-3">Review</th>
                                <th class="px-4 py-3">Rating</th>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Delete</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <template x-for="(review, index) in paginatedReviews()" :key="index">
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3" x-text="(currentPage - 1) * perPage + index + 1"></td>
                                    <td class="px-4 py-3">
                                        <span class="font-medium block" x-text="review.course"></span>
                                        <span class="text-gray-500 text-xs block" x-text="review.level"></span>
                                    </td>
                                    <td class="px-4 py-3" x-text="review.name"></td>
                                    <td class="px-4 py-3 max-w-xs">
                                        <span x-text="review.text.substring(0, 50) + '...'"></span>
                                        <a href="#" class="text-blue-500 text-xs">Read more</a>
                                    </td>
                                    <td class="px-4 py-3" x-text="review.rating"></td>
                                    <td class="px-4 py-3" x-text="review.date"></td>
                                    <td class="px-4 py-3">
                                        <button class="bg-red-600 hover:bg-red-800 text-white rounded-full p-2">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </td>

                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Aligned Right -->
                <div class="flex justify-end mt-6">
                    <div class="flex space-x-1">
                        <button class="px-3 py-1 bg-white border rounded text-gray-600 hover:bg-gray-100"
                            @click="prevPage()" :disabled="currentPage === 1">&lt;</button>

                        <template x-for="page in totalPages()" :key="page">
                            <button
                                class="px-3 py-1 border rounded"
                                :class="currentPage === page ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100'"
                                @click="goToPage(page)" x-text="page">
                            </button>
                        </template>

                        <button class="px-3 py-1 bg-white border rounded text-gray-600 hover:bg-gray-100"
                            @click="nextPage()" :disabled="currentPage === totalPages()">&gt;</button>
                    </div>
                </div>
            </div>
        </main>


        <script>
            function reviewsDashboard() {
                return {
                    reviews: [
                        { course: 'UI UX Design course', level: '- Advance level', name: 'James Anderson', text: 'Duis non metus vel augue luctus vestibulum. Curabitur venenatis dolor sed suscipit egestas.', rating: '4/5', date: '12/02/2025' },
                        { course: 'Python Basics', level: '- Beginner', name: 'Rita Patel', text: 'Great course! Helped me land an internship.', rating: '5/5', date: '15/02/2025' },
                        { course: 'React Development', level: '- Intermediate', name: 'Alex Johnson', text: 'I found the project-based learning really effective.', rating: '4.5/5', date: '18/02/2025' },
                        { course: 'Data Structures', level: '- Advanced', name: 'Mohit Sharma', text: 'The mentor was really great and guided us thoroughly.', rating: '4.8/5', date: '21/02/2025' },
                        { course: 'Node.js Essentials', level: '- Beginner', name: 'Samantha Singh', text: 'Would recommend this to backend developers.', rating: '4.2/5', date: '22/02/2025' },
                        { course: 'Frontend Fundamentals', level: '- Intermediate', name: 'Vikas Kumar', text: 'Well-structured content and helpful exercises.', rating: '4/5', date: '23/02/2025' },
                        { course: 'Fullstack Bootcamp', level: '- Masterclass', name: 'Emily Watson', text: 'Perfect for people aiming for job switch.', rating: '5/5', date: '24/02/2025' },
                        { course: 'DevOps Intro', level: '- Basics', name: 'Pradeep Yadav', text: 'Decent course, could use more real-world examples.', rating: '3.5/5', date: '25/02/2025' },
                        { course: 'AI with Python', level: '- Advanced', name: 'Sarah Nair', text: 'Explained difficult concepts in simple ways.', rating: '4.7/5', date: '26/02/2025' },
                        { course: 'Java Programming', level: '- Beginner', name: 'Rohan Desai', text: 'Good for freshers, very beginner-friendly.', rating: '4/5', date: '27/02/2025' },
                    ],
                    perPage: 5,
                    currentPage: 1,

                    paginatedReviews() {
                        const start = (this.currentPage - 1) * this.perPage;
                        return this.reviews.slice(start, start + this.perPage);
                    },
                    totalPages() {
                        return Math.ceil(this.reviews.length / this.perPage);
                    },
                    goToPage(page) {
                        if (page >= 1 && page <= this.totalPages()) {
                            this.currentPage = page;
                        }
                    },
                    nextPage() {
                        if (this.currentPage < this.totalPages()) {
                            this.currentPage++;
                        }
                    },
                    prevPage() {
                        if (this.currentPage > 1) {
                            this.currentPage--;
                        }
                    },
                    deleteReview(index) {
                        const globalIndex = (this.currentPage - 1) * this.perPage + index;
                        this.reviews.splice(globalIndex, 1);
                        if (this.currentPage > this.totalPages()) this.currentPage--;
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
