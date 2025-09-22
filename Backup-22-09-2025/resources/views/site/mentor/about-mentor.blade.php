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
@if($mentorNeedsSubscription)
    @include('site.mentor.subscription.index')
@endif
	
    <!-- Wrapper -->
<div class="flex h-screen" x-data="{ sidebarOpen: true }" x-init="$watch('sidebarOpen', () => feather.replace())">
 @include('site.mentor.componants.sidebar')

  <div class="flex-1 flex flex-col">
      @include('site.mentor.componants.navbar')
    

    <!-- Page Content Here -->
    <main class="p-6 bg-gray-100 flex-1 overflow-y-auto">
       <h2 class="text-2xl font-semibold mb-6">{{ langLabel('about_mentor') }}</h2>

                <div class="bg-white shadow rounded-lg p-6 space-y-6">
                    <!-- mentor Information -->
                    <h3 class="text-lg font-medium text-gray-800">{{ langLabel('mentor_information') }}</h3>

                    <!-- mentor Overview -->
                    <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ langLabel('mentor_overview') }} / {{ langLabel('about_mentor') }}</label>
                    <div class="border border-gray-300 rounded-md overflow-hidden">
                        <!-- Replace this div with your WYSIWYG component -->
                        <textarea class="w-full p-3 text-sm h-28 resize-none focus:outline-none" placeholder="{{ langLabel('write_about_mentor') }}..."></textarea>
                    </div>
                    </div>

                    <!-- Assessment Benefits -->
                    <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ langLabel('assessment_benefits') }}</label>
                    <div class="border border-gray-300 rounded-md overflow-hidden">
                        <!-- Replace this div with your WYSIWYG component -->
                        <textarea class="w-full p-3 text-sm h-28 resize-none focus:outline-none" placeholder="{{ langLabel('write_assessment_benefits') }}..."></textarea>
                    </div>
                    </div>

                    <!-- Work Experience -->
                    <div>
                    <h4 class="text-md font-semibold text-gray-800 mb-3">{{ langLabel('work_experience') }}:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ langLabel('your_prefession') }}</label>
                        <input type="text" placeholder="{{ langLabel('enter_your_prefession') }}" class="w-full border border-gray-300 rounded-md p-2 text-sm" />
                        </div>
                        <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ langLabel('years_of_experience') }}</label>
                        <input type="text" placeholder="Ex: 2 years" class="w-full border border-gray-300 rounded-md p-2 text-sm" />
                        </div>
                    </div>
                    </div>

                    <!-- Qualification -->
                    <div>
                    <h4 class="text-md font-semibold text-gray-800 mb-3">{{ langLabel('qualification') }}:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ langLabel('highest_qualification') }}</label>
                        <select class="w-full border border-gray-300 rounded-md p-2 text-sm">
                            <option>{{ langLabel('select_highest_qualification') }}</option>
                            <option>Bachelor's</option>
                            <option>Master's</option>
                            <option>Ph.D</option>
                            <option>Diploma</option>
                        </select>
                        </div>
                        <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ langLabel('field_of_study') }}</label>
                        <input type="text" placeholder="Ex: Design, Science..." class="w-full border border-gray-300 rounded-md p-2 text-sm" />
                        </div>
                    </div>
                    </div>

                    <!-- Institution Name -->
                    <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ langLabel('institution_name') }}</label>
                    <input type="text" placeholder="{{ langLabel('enter_institution_name') }}" class="w-full border border-gray-300 rounded-md p-2 text-sm" />
                    </div>

                    <!-- Price per Session -->
                    <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ langLabel('price_per_session') }}</label>
                    <input type="text" placeholder="{{ langLabel('enter_price_per_session') }}" class="w-full border border-gray-300 rounded-md p-2 text-sm" />
                    </div>

                    <!-- Save Button -->
                    <div class="flex justify-end">
                    <button class="bg-blue-600 text-white px-6 py-2 rounded-md text-sm hover:bg-blue-700">{{ langLabel('save_details') }}</button>
                    </div>
                </div>
    </main>
  </div>
</div>

<!-- Feather Icons and Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('dashboard', () => ({
      init() {
        feather.replace();
      }
    }));
  });

  document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
  });
</script>




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
