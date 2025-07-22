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
                            <input type="text" name="training_title" placeholder="Enter the Course Title" class="w-full border rounded-md p-2" value="{{old('training_title')}}"/>
                            @error('training_title')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Course Sub Title -->
                        <div class="mb-4">
                            <label class="block font-medium mb-1">Course Sub Title</label>
                            <input type="text" name="training_sub_title" placeholder="Enter the Sub Title" class="w-full border rounded-md p-2" value="{{old('training_sub_title')}}"/>
                            @error('training_sub_title')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Training Objective -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Training Objective</label>
                        <textarea 
                            name="training_objective" 
                            class="w-full border rounded-md p-2 h-24" 
                            placeholder="Enter the Training Objective"
                        >{{ old('training_objective') }}</textarea>
                        @error('training_objective')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Course Content -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Course Content</label>
                        <textarea 
                            name="training_descriptions" 
                            class="w-full border rounded-md p-2 h-24" 
                            placeholder="Enter the Course Content"
                        >{{ old('training_descriptions') }}</textarea>
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
                                <input type="text" name="training_price" placeholder="Enter Course Price" class="w-full border rounded-md p-2" value="{{old('training_price')}}"/>
                                @error('training_price')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Course Offer Price -->
                            <div>
                                <label class="block font-medium mb-1">Course Offer Price</label>
                                <input type="text" name="training_offer_price" placeholder="Enter Offer Price" class="w-full border rounded-md p-2" value="{{old('training_offer_price')}}"/>
                                @error('training_offer_price')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                       

                    <div x-data="batchManager()" class="p-4 border rounded space-y-6">

                        <!-- Batch Input Fields -->
                        <div>
                            <h2 class="text-xl font-semibold mb-4">Batch Details</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-1 font-medium">Batch No</label>
                                    <input type="text" x-model="batchNo" placeholder="Enter batch no" class="border p-2 rounded w-full" />
                                </div>
                                <div>
                                    <label class="block mb-1 font-medium">Start Date</label>
                                    <input type="date" x-model="batchDate" class="border p-2 rounded w-full" />
                                </div>
                                <div>
                                    <label class="block mb-1 font-medium">Start Time</label>
                                    <input type="time" x-model="startTime" class="border p-2 rounded w-full" />
                                </div>
                                <div>
                                    <label class="block mb-1 font-medium">End Time</label>
                                    <input type="time" x-model="endTime" class="border p-2 rounded w-full" />
                                </div>
                                <div>
                                    <label class="block mb-1 font-medium">Select Type</label>
                                    <select x-model="durationType" class="border p-2 rounded w-full">
                                        <option value="day">Days</option>
                                        <option value="month">Months</option>
                                        <option value="year">Years</option>
                                    </select>
                                </div>
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
                            <!-- Conflict Error Message -->
                            <div x-show="conflict" class="text-red-600 mt-2 font-semibold">
                                Selected timing already used for the given date range.
                            </div>
                            <!-- Action Buttons -->
                            <div class="mt-4">
                                <button type="button" x-show="!isEditing" @click="addBatch"
                                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                                    + Add Batch
                                </button>
                                <button type="button" x-show="isEditing" @click="updateBatch"
                                    class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                                    ✓ Update Batch
                                </button>
                            </div>
                        </div>

                        <!-- Hidden Inputs -->
                        <template x-for="(batch, index) in batches" :key="'hidden-' + index">
                            <div>
                                <input type="hidden" :name="`content_sections[${index}][batch_no]`" :value="batch.batchNo">
                                <input type="hidden" :name="`content_sections[${index}][batch_date]`" :value="batch.batchDate">
                                <input type="hidden" :name="`content_sections[${index}][start_time]`" :value="batch.startTime">
                                <input type="hidden" :name="`content_sections[${index}][end_time]`" :value="batch.endTime">
                                <input type="hidden" :name="`content_sections[${index}][duration]`" :value="batch.duration">
                            </div>
                        </template>

                        <!-- Batch Table -->
                        <div>
                            <h2 class="text-xl font-semibold mb-4">Batch List</h2>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border">
                                    <thead class="bg-gray-100 text-left text-sm font-medium text-gray-700">
                                        <tr>
                                            <th class="py-2 px-4 border-b">#</th>
                                            <th class="py-2 px-4 border-b">Batch No</th>
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
                                                    <button 
                                                        type="button" 
                                                        @click.prevent="editBatch(index)" 
                                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded">
                                                        Edit
                                                    </button>
                                                </td>
                                                <td class="py-2 px-4 border-b">
                                                    <button @click="removeBatch(index)"
                                                        class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-full">
                                                        <i class="fa fa-trash"></i>
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

                        // State
                        batches: [],
                        isEditing: false,
                        editIndex: null,
                        conflict: false,

                        // 🧮 Duration options
                        getOptions() {
                            if (this.durationType === 'day') return Array.from({ length: 60 }, (_, i) => `${i + 1} day`);
                            if (this.durationType === 'month') return Array.from({ length: 12 }, (_, i) => `${i + 1} month`);
                            if (this.durationType === 'year') return Array.from({ length: 5 }, (_, i) => `${i + 1} year`);
                            return [];
                        },

                        // ➕ Add New Batch
                        addBatch() {
                            if (!this.validateForm()) return;

                            if (this.hasConflict()) {
                                this.conflict = true;
                                return;
                            }

                            this.conflict = false;

                            this.batches.push(this.getBatchData());
                            this.clearForm();
                        },

                        // ✏️ Edit Batch
                        editBatch(index) {
                            const b = this.batches[index];
                            this.batchNo = b.batchNo;
                            this.batchDate = b.batchDate;
                            this.startTime = b.startTime;
                            this.endTime = b.endTime;
                            this.duration = b.duration;
                            this.durationType = this.getDurationTypeFromString(b.duration);

                            this.isEditing = true;
                            this.editIndex = index;
                        },

                        // 🔁 Update Existing Batch
                        updateBatch() {
                            if (!this.validateForm()) return;

                            if (this.hasConflict()) {
                                this.conflict = true;
                                return;
                            }

                            this.conflict = false;

                            this.batches[this.editIndex] = this.getBatchData();
                            this.clearForm();
                        },

                        // ❌ Remove Batch
                        removeBatch(index) {
                            this.batches.splice(index, 1);
                            if (this.isEditing && this.editIndex === index) {
                                this.clearForm();
                            }
                        },

                        // 🔍 Check Date + Time Conflict
                        hasConflict() {
                            const [val, unit] = this.duration.split(' ');
                            const startDate = new Date(this.batchDate);
                            const endDate = new Date(this.batchDate);
                            const durationValue = parseInt(val);

                            if (unit.includes('day')) {
                                endDate.setDate(endDate.getDate() + durationValue - 1);
                            } else if (unit.includes('month')) {
                                endDate.setMonth(endDate.getMonth() + durationValue);
                                endDate.setDate(endDate.getDate() - 1);
                            } else if (unit.includes('year')) {
                                endDate.setFullYear(endDate.getFullYear() + durationValue);
                                endDate.setDate(endDate.getDate() - 1);
                            }

                            return this.batches.some((b, i) => {
                                if (this.isEditing && this.editIndex === i) return false;

                                const bStart = new Date(b.batchDate);
                                const bEnd = new Date(b.batchDate);
                                const [bVal, bUnit] = b.duration.split(' ');
                                const bDur = parseInt(bVal);

                                if (bUnit.includes('day')) {
                                    bEnd.setDate(bEnd.getDate() + bDur - 1);
                                } else if (bUnit.includes('month')) {
                                    bEnd.setMonth(bEnd.getMonth() + bDur);
                                    bEnd.setDate(bEnd.getDate() - 1);
                                } else if (bUnit.includes('year')) {
                                    bEnd.setFullYear(bEnd.getFullYear() + bDur);
                                    bEnd.setDate(bEnd.getDate() - 1);
                                }

                                const isDateOverlap = (startDate <= bEnd && endDate >= bStart);
                                const isTimeOverlap = !(this.endTime <= b.startTime || this.startTime >= b.endTime);

                                return isDateOverlap && isTimeOverlap;
                            });
                        },

                        // 🧹 Clear Form
                        clearForm() {
                            this.batchNo = '';
                            this.batchDate = '';
                            this.startTime = '';
                            this.endTime = '';
                            this.duration = '';
                            this.durationType = 'day';
                            this.isEditing = false;
                            this.editIndex = null;
                            this.conflict = false;
                        },

                        // 📦 Get Current Batch Object
                        getBatchData() {
                            return {
                                batchNo: this.batchNo,
                                batchDate: this.batchDate,
                                startTime: this.startTime,
                                endTime: this.endTime,
                                duration: this.duration
                            };
                        },

                        // 📌 Validate Inputs
                        validateForm() {
                            if (!this.batchNo || !this.batchDate || !this.startTime || !this.endTime || !this.duration) {
                                alert("Please fill all fields.");
                                return false;
                            }
                            if (this.endTime <= this.startTime) {
                                alert("End time must be after start time.");
                                return false;
                            }
                            return true;
                        },

                        // 🧠 Get Type from "5 day" string
                        getDurationTypeFromString(str) {
                            if (str.includes('day')) return 'day';
                            if (str.includes('month')) return 'month';
                            if (str.includes('year')) return 'year';
                            return 'day';
                        }
                    };
                }
            </script>
            </div>
        </div>
    </div>
           



@include('site.trainer.componants.footer')