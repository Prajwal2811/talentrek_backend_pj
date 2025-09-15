@include('site.componants.header')
<?php
// dd($mentorReview);exit;
?>
<body>

    <div class="loading-area">
        <div class="loading-box"></div>
        <div class="loading-pic">
            <div class="wrapper">
                <div class="cssload-loader"></div>
            </div>
        </div>
    </div>

	@if($assessorNeedsSubscription)
        @include('site.assessor.subscription.index')
    @endif
    <div class="page-wraper">
        <div class="flex h-screen">
          
            @include('site.assessor.componants.sidebar')

            <div class="flex-1 flex flex-col">

                @include('site.assessor.componants.navbar')

           <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="reviewsDashboard()">
            <h2 class="text-2xl font-semibold mb-6">{{ langLabel('reviews') }}</h2>
            <div class="bg-white p-4">
                <h3 class="text-md font-semibold mb-4">{{ langLabel('jobseeker') }} {{ langLabel('reviews') }}</h3>
                @if(session('success'))
                    <span id="successMessage" class="inline-flex items-center bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </span>

                    <script>
                        setTimeout(() => {
                            const el = document.getElementById('successMessage');
                            if (el) {
                                el.classList.add('opacity-0'); 
                                setTimeout(() => el.style.display = 'none', 2000); 
                            }
                        }, 10000); 
                    </script>
                @endif

                <div class="overflow-x-auto">
                    <div x-data="mentorReviews()" x-init="fetchReviews()" class="overflow-x-auto ">
                        <table class="min-w-full text-sm text-left border-collapse">
                            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-3">{{ langLabel('sr_no') }}</th>
                                    <!-- <th class="px-4 py-3">Course</th> -->
                                    <th class="px-4 py-3">{{ langLabel('jobseeker') }} {{ langLabel('name') }}</th>
                                    <th class="px-4 py-3">{{ langLabel('review') }}</th>
                                    <th class="px-4 py-3">{{ langLabel('rating') }}</th>
                                    <th class="px-4 py-3">{{ langLabel('date') }}</th>
                                    <th class="px-4 py-3">{{ langLabel('delete') }}</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @foreach($reviews as $index => $review)
                                    <tr class="review-row border-b hover:bg-gray-50">
                                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                                        <!-- <td class="px-4 py-3">{{ $review->course_title ?? 'N/A' }}</td> -->
                                        <td class="px-4 py-3">{{ $review->jobseeker_name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 max-w-xs">{{ \Illuminate\Support\Str::limit($review->reviews, 50) }}</td>
                                        <td class="px-4 py-3">{{ $review->ratings }}/5</td>
                                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($review->created_at)->format('d-m-Y') }}</td>
                                        <td class="px-4 py-3">
                                            <form action="{{ route('assessor.review.delete', $review->id) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 hover:bg-red-800 text-white rounded-full p-2">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="reviewsPagination" class="mt-6 flex justify-center space-x-2"></div>
            </div>
        </main>
       
        <script>
            $(document).ready(function () {
                const itemsPerPage = 10;
                const $rows = $('.review-row');
                const totalItems = $rows.length;
                const totalPages = Math.ceil(totalItems / itemsPerPage);
                let currentPage = 1;

                function showPage(page) {
                    const start = (page - 1) * itemsPerPage;
                    const end = start + itemsPerPage;
                    $rows.hide().slice(start, end).show();
                    currentPage = page;
                    updatePagination();
                }

                function updatePagination() {
                    $('.page-btn').removeClass('bg-blue-500 text-white').addClass('bg-gray-200 text-black');
                    $(`.page-btn[data-page="${currentPage}"]`).addClass('bg-blue-500 text-white').removeClass('bg-gray-200 text-black');

                    $('#prev-btn').prop('disabled', currentPage === 1);
                    $('#next-btn').prop('disabled', currentPage === totalPages);
                }

                function createPagination() {
                    $('#reviewsPagination').empty();

                    // Prev Button
                    $('#reviewsPagination').append(`
                        <button id="prev-btn" class="px-3 py-1 text-sm rounded bg-gray-200 hover:bg-blue-200 transition">&lt;</button>
                    `);

                    // Page Buttons
                    for (let i = 1; i <= totalPages; i++) {
                        $('#reviewsPagination').append(`
                            <button class="page-btn px-3 py-1 text-sm rounded bg-gray-200 hover:bg-blue-200 transition" data-page="${i}">${i}</button>
                        `);
                    }

                    // Next Button
                    $('#reviewsPagination').append(`
                        <button id="next-btn" class="px-3 py-1 text-sm rounded bg-gray-200 hover:bg-blue-200 transition">&gt;</button>
                    `);

                    // Event Handlers
                    $('.page-btn').on('click', function () {
                        const page = $(this).data('page');
                        showPage(page);
                    });

                    $('#prev-btn').on('click', function () {
                        if (currentPage > 1) showPage(currentPage - 1);
                    });

                    $('#next-btn').on('click', function () {
                        if (currentPage < totalPages) showPage(currentPage + 1);
                    });
                }

                if (totalItems > 0) {
                    createPagination();
                    showPage(1);
                } else {
                    $('#reviewsPagination').html('<p class="text-center text-gray-500">No reviews found.</p>');
                }
            });
        </script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(document).ready(function () {
                $('.delete-form').on('submit', function (e) {
                    e.preventDefault(); // prevent immediate submit
                    const form = this;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Are you sure you want to delete this jobseeker review?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // submit the form if confirmed
                        }
                    });
                });
            });
        </script>



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
