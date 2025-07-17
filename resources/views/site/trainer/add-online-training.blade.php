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
        <div class="flex h-screen" x-data="{ sidebarOpen: true }" x-init="$watch('sidebarOpen', () => feather.replace())">
          
            @include('site.trainer.componants.sidebar')

            <div class="flex-1 flex flex-col">
                @include('site.trainer.componants.navbar')

            <main class="p-6 max-h-[900px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100"">
                <h2 class="text-xl font-semibold mb-6">Online/Offline course</h2>
                <form action="{{ route('trainer.training.online.save.data') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- Course Title -->
                        <div class="mb-4">
                            <label class="block font-medium mb-1">Course Title</label>
                            <input type="text" name="training_title" placeholder="Enter the Course Title" class="w-full border rounded-md p-2" />
                            @error('training_title')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Course Sub Title -->
                        <div class="mb-4">
                            <label class="block font-medium mb-1">Course Sub Title</label>
                            <input type="text" name="training_sub_title" placeholder="Enter the Sub Title" class="w-full border rounded-md p-2" />
                            @error('training_sub_title')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label class="block font-medium mb-1">Training Objective</label>
                            <textarea placeholder="Enter the Training Objective" name="training_objective" class="w-full border rounded-md p-2 h-24"></textarea>
                            @error('training_objective')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium mb-1">Course Content</label>
                            <textarea placeholder="Enter the Course Content" name="training_descriptions" class="w-full border rounded-md p-2 h-24"></textarea>
                            @error('training_descriptions')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Training Level -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block font-medium mb-1">Training Level</label>
                                <select name="training_level" class="w-full border rounded-md p-2">
                                    <option value="">Select Training Level</option>
                                    <option value="Beginner" {{ old('training_level', $training->training_level ?? '') == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="Intermediate" {{ old('training_level', $training->training_level ?? '') == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="Advanced" {{ old('training_level', $training->training_level ?? '') == 'Advanced' ? 'selected' : '' }}>Advanced</option>
                                </select>
                                @error('training_level')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <label class="block font-medium mb-2">Session Type</label>
                            <div class="flex flex-wrap gap-4">
                                <label>
                                    <input type="radio" name="training_category" value="Online" class="mr-2" /> Online
                                </label>
                                <label>
                                    <input type="radio" name="training_category" value="Classroom" class="mr-2" /> Classroom
                                </label>
                            </div>
                            @error('training_category')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>



                        <!-- Upload Thumbnail -->
                        <div class="mb-4">
                            <label class="block font-medium mb-1">Upload Thumbnail</label>
                            <div class="flex gap-4 items-center">
                            <input type="file" accept="image/*" name="thumbnail" class="border rounded-md p-2 flex-1" />

                            </div>
                        </div>

                        

                        <!-- Course Price and Offer Price -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <!-- Course Price -->
                            <div>
                                <label class="block font-medium mb-1">Course Price</label>
                                <input type="text" name="training_price" placeholder="Enter Course Price" class="w-full border rounded-md p-2" />
                                @error('training_price')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Course Offer Price -->
                            <div>
                                <label class="block font-medium mb-1">Course Offer Price</label>
                                <input type="text" name="training_offer_price" placeholder="Enter Offer Price" class="w-full border rounded-md p-2" />
                                @error('training_offer_price')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                       

                    <div x-data="batchManager()">
                        <!-- Batch Input Fields -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold mb-4">Batch details</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-1 font-medium">Batch No</label>
                                    <input type="text" x-model="batchNo" placeholder="Enter batch no"
                                        class="border p-2 rounded w-full" />
                                </div>
                                <div>
                                    <label class="block mb-1 font-medium">Start Date</label>
                                    <input type="date" x-model="batchDate" class="border p-2 rounded w-full" />
                                </div>
                                <div>
                                    <label class="block mb-1 font-medium">Start Timing</label>
                                    <input type="time" x-model="startTime" class="border p-2 rounded w-full" />
                                </div>
                                <div>
                                    <label class="block mb-1 font-medium">End Timing</label>
                                    <input type="time" x-model="endTime" class="border p-2 rounded w-full" />
                                </div>


                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Type Selection -->
                                    <div>
                                        <label class="block mb-1 font-medium">Select Type</label>
                                        <select x-model="durationType" class="border p-2 rounded w-full">
                                            <option value="day">Days</option>
                                            <option value="month">Months</option>
                                            <option value="year">Years</option>
                                        </select>
                                    </div>

                                    <!-- Duration Options Based on Type -->
                                    <div>
                                        <label class="block mb-1 font-medium">Duration</label>
                                        <select x-model="duration" class="border p-2 rounded w-full">
                                            <option value="">Select duration</option>
                                            <template x-for="option in getOptions()" :key="option">
                                                <option :value="option" x-text="option"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>



                            </div>

                            <!-- Action Buttons -->
                            <button type="button" x-show="!isEditing" @click="addBatch"
                                    class="bg-blue-600 text-white px-6 py-2 rounded mt-4 hover:bg-blue-700">
                                + Add batch
                            </button>
                            <button type="button" x-show="isEditing" @click="updateBatch"
                                    class="bg-green-600 text-white px-6 py-2 rounded mt-4 hover:bg-green-700">
                                ✓ Update batch
                            </button>
                        </div>

                        <!-- Hidden Inputs to Submit -->
                        <template x-for="(batch, index) in batches" :key="'form-' + index">
                            <div>
                                <input type="hidden" :name="`content_sections[${index}][batch_no]`" :value="batch.batchNo">
                                <input type="hidden" :name="`content_sections[${index}][batch_date]`" :value="batch.batchDate">
                                <input type="hidden" :name="`content_sections[${index}][start_time]`" :value="batch.startTime">
                                <input type="hidden" :name="`content_sections[${index}][end_time]`" :value="batch.endTime">
                                <input type="hidden" :name="`content_sections[${index}][duration]`" :value="batch.duration">
                            </div>
                        </template>

                        <!-- Batch List Table -->
                        <div>
                            <h2 class="text-xl font-semibold mb-4">Batch list</h2>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border">
                                    <thead class="bg-gray-100 text-left text-sm font-medium text-gray-700">
                                        <tr>
                                            <th class="py-2 px-4 border-b">Sr. No.</th>
                                            <th class="py-2 px-4 border-b">Batch Title</th>
                                            <th class="py-2 px-4 border-b">Date</th>
                                            <th class="py-2 px-4 border-b">Time</th>
                                            <th class="py-2 px-4 border-b">Duration</th>
                                            <th class="py-2 px-4 border-b">Edit</th>
                                            <th class="py-2 px-4 border-b">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(batch, index) in batches" :key="index">
                                            <tr class="bg-gray-50">
                                                <td class="py-2 px-4 border-b" x-text="index + 1"></td>
                                                <td class="py-2 px-4 border-b" x-text="batch.batchNo"></td>
                                                <td class="py-2 px-4 border-b" x-text="batch.batchDate"></td>
                                                <td class="py-2 px-4 border-b" x-text="`${batch.startTime} - ${batch.endTime}`"></td>
                                                <td class="py-2 px-4 border-b" x-text="batch.duration"></td>
                                                <td class="py-2 px-4 border-b">
                                                    <button type="button" @click="editBatch(index)" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded">Edit</button>
                                                </td>
                                                <td class="py-2 px-4 border-b">
                                                    <button type="button" @click="removeBatch(index)" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
          

                    <!-- Submit Button -->
                    <div class="text-right mt-5">
                        <button type="submit" class="bg-blue-800 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-md font-semibold">
                        Submit
                        </button>
                    </div>
                </form>
            </main>



            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
            <!-- Include Alpine.js -->
            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
            <script>
                function batchManager() {
                    return {
                        // Form fields
                        batchNo: '',
                        batchDate: '',
                        startTime: '',
                        endTime: '',
                        durationType: 'day',
                        duration: '',

                        // List of batches
                        batches: [],
                        isEditing: false,
                        editIndex: null,

                        // Duration dropdown options
                        getOptions() {
                            if (this.durationType === 'day') return Array.from({ length: 30 }, (_, i) => `${i + 1} days`);
                            if (this.durationType === 'month') return Array.from({ length: 12 }, (_, i) => `${i + 1} months`);
                            if (this.durationType === 'year') return Array.from({ length: 5 }, (_, i) => `${i + 1} years`);
                            return [];
                        },

                        // Add new batch
                        addBatch() {
                            if (this.batchNo && this.batchDate && this.startTime && this.endTime && this.duration) {
                                this.batches.push({
                                    batchNo: this.batchNo,
                                    batchDate: this.batchDate,
                                    startTime: this.startTime,
                                    endTime: this.endTime,
                                    duration: this.duration,
                                });
                                this.clearForm();
                            } else {
                                alert("Please fill all fields.");
                            }
                        },

                        // Edit existing batch
                        editBatch(index) {
                            const batch = this.batches[index];
                            this.batchNo = batch.batchNo;
                            this.batchDate = batch.batchDate;
                            this.startTime = batch.startTime;
                            this.endTime = batch.endTime;

                            // Parse duration and set durationType
                            const parts = batch.duration.split(' ');
                            const unit = parts[1]?.toLowerCase();

                            if (unit?.startsWith('day')) {
                                this.durationType = 'day';
                            } else if (unit?.startsWith('month')) {
                                this.durationType = 'month';
                            } else if (unit?.startsWith('year')) {
                                this.durationType = 'year';
                            } else {
                                this.durationType = 'day';
                            }

                            // Wait for options to be available before setting duration
                            this.$nextTick(() => {
                                this.duration = batch.duration;
                            });

                            this.editIndex = index;
                            this.isEditing = true;
                        },

                        // Update batch
                        updateBatch() {
                            if (this.editIndex !== null) {
                                this.batches[this.editIndex] = {
                                    batchNo: this.batchNo,
                                    batchDate: this.batchDate,
                                    startTime: this.startTime,
                                    endTime: this.endTime,
                                    duration: this.duration,
                                };
                                this.clearForm();
                            }
                        },

                        // Delete batch
                        removeBatch(index) {
                            this.batches.splice(index, 1);
                            if (this.isEditing && this.editIndex === index) {
                                this.clearForm();
                            }
                        },

                        // Reset form
                        clearForm() {
                            this.batchNo = '';
                            this.batchDate = '';
                            this.startTime = '';
                            this.endTime = '';
                            this.duration = '';
                            this.durationType = 'day';
                            this.editIndex = null;
                            this.isEditing = false;
                        }
                    }
                }
            </script>


            

          



            </div>
        </div>
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
