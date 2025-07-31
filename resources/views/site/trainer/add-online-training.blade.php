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

            <main class="p-6 max-h-[900px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100">
                <h2 class="text-xl font-semibold mb-6">Online/Offline Course</h2>
                <form action="{{ route('trainer.training.online.save.data') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Course Title -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Course Title</label>
                        <input type="text" name="training_title" class="w-full border rounded-md p-2" placeholder="Enter the Course Title" value="{{ old('training_title') }}">
                        @error('training_title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Course Sub Title -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Course Sub Title</label>
                        <input type="text" name="training_sub_title" class="w-full border rounded-md p-2" placeholder="Enter the Sub Title" value="{{ old('training_sub_title') }}">
                        @error('training_sub_title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Training Objective -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Training Objective</label>
                        <textarea name="training_objective" class="w-full border rounded-md p-2 h-24" placeholder="Enter the Training Objective">{{ old('training_objective') }}</textarea>
                        @error('training_objective')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Course Content -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Course Content</label>
                        <textarea name="training_descriptions" class="w-full border rounded-md p-2 h-24" placeholder="Enter the Course Content">{{ old('training_descriptions') }}</textarea>
                        @error('training_descriptions')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Training Level -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Training Level</label>
                        <select name="training_level" class="w-full border rounded-md p-2">
                            <option value="">Select Level</option>
                            <option value="Beginner" {{ old('training_level') == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="Intermediate" {{ old('training_level') == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="Advanced" {{ old('training_level') == 'Advanced' ? 'selected' : '' }}>Advanced</option>
                        </select>
                        @error('training_level')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Session Type -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Session Type</label>
                        <div class="flex gap-4">
                            <label><input type="radio" name="training_category" value="online" {{ old('training_category') == 'online' ? 'checked' : '' }} class="mr-2"> Online</label>
                            <label><input type="radio" name="training_category" value="classroom" {{ old('training_category') == 'classroom' ? 'checked' : '' }} class="mr-2"> Classroom</label>
                        </div>
                        @error('training_category')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Upload Thumbnail -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Upload Thumbnail</label>
                        <input type="file" name="thumbnail" accept="image/*" class="w-full border rounded-md p-2">
                        @error('thumbnail')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Price & Offer Price -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block font-medium mb-1">Course Price</label>
                            <input type="text" name="training_price" class="w-full border rounded-md p-2" value="{{ old('training_price') }}">
                            @error('training_price')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Offer Price</label>
                            <input type="text" name="training_offer_price" class="w-full border rounded-md p-2" value="{{ old('training_offer_price') }}">
                            @error('training_offer_price')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Batches -->
                    <div x-data="batchManager()" class="p-4 border rounded space-y-6">

                        <!-- Input Fields -->
                        <div>
                            <h2 class="text-xl font-semibold mb-4">Batch Details</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-medium mb-1">Batch No</label>
                                    <input type="text" x-model="batchNo" class="border p-2 rounded w-full" placeholder="Batch No">
                                </div>

                                <div>
                                    <label class="block font-medium mb-1">Batch Date</label>
                                    <input type="date" x-model="batchDate" class="border p-2 rounded w-full">
                                </div>

                                <div>
                                    <label class="block font-medium mb-1">Start Timing</label>
                                    <input type="time" x-model="startTime" class="border p-2 rounded w-full">
                                </div>

                                <div>
                                    <label class="block font-medium mb-1">End Timing</label>
                                    <input type="time" x-model="endTime" class="border p-2 rounded w-full">
                                </div>

                                <div>
                                    <label class="block font-medium mb-1">Duration Type</label>
                                    <select x-model="durationType" class="border p-2 rounded w-full">
                                        <option value="day">Days</option>
                                        <option value="month">Months</option>
                                        <option value="year">Years</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-medium mb-1">Duration</label>
                                    <select x-model="duration" class="border p-2 rounded w-full">
                                        <option value="">Select Duration</option>
                                        <template x-for="option in getOptions()" :key="option">
                                            <option :value="option" x-text="option"></option>
                                        </template>
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-medium mb-1">Candidate Strength</label>
                                    <input type="number" min="1" x-model="strength" placeholder="Strength" class="border p-2 rounded w-full">
                                </div>
                            </div>


                            <!-- Days -->
                            <div class="mt-4">
                                <label class="block font-medium mb-1">Select Days</label>
                                <div class="flex flex-wrap gap-4">
                                    <template x-for="(day, idx) in weekDays" :key="idx">
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" :value="day" x-model="selectedDays" class="form-checkbox text-blue-600">
                                            <span x-text="day"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>

                            <div x-show="conflict" class="text-red-600 font-semibold mt-2">
                                ⚠️ Time conflict with another batch.
                            </div>

                            <div class="mt-4">
                                <button type="button" @click="addBatch" x-show="!isEditing" class="bg-blue-600 text-white px-4 py-2 rounded">+ Add Batch</button>
                                <button type="button" @click="updateBatch" x-show="isEditing" class="bg-green-600 text-white px-4 py-2 rounded">✓ Update Batch</button>
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
                                <input type="hidden" :name="`content_sections[${index}][strength]`" :value="batch.strength">
                                <input type="hidden" :name="`content_sections[${index}][days]`" :value="JSON.stringify(batch.selectedDays)">
                                <input type="hidden" :name="`content_sections[${index}][end_date]`" :value="batch.endDate"> <!-- Add this line -->
                            </div>
                        </template>


                        <!-- Batch List Table -->
                        <div>
                            <h2 class="text-xl font-semibold mb-2">Added Batches</h2>
                            <table class="min-w-full bg-white border text-sm">
                                <thead class="bg-gray-100 font-semibold">
                                    <tr>
                                        <th class="px-4 py-2 border">#</th>
                                        <th class="px-4 py-2 border">Batch No</th>
                                        <th class="px-4 py-2 border">Start Date</th>
                                        <th class="px-4 py-2 border">End Date</th>
                                        <th class="px-4 py-2 border">Time</th>
                                        <th class="px-4 py-2 border">Duration</th>
                                        <th class="px-4 py-2 border">Days</th>
                                        <th class="px-4 py-2 border">Strength</th>
                                        <th class="px-4 py-2 border">Edit</th>
                                        <th class="px-4 py-2 border">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(batch, index) in batches" :key="index">
                                        <tr class="text-center">
                                            <td class="border px-2 py-1" x-text="index + 1"></td>
                                            <td class="border px-2 py-1" x-text="batch.batchNo"></td>
                                            <td class="border px-2 py-1" x-text="new Date(batch.batchDate).toLocaleDateString()"></td>
                                            <td class="border px-2 py-1" x-text="new Date(batch.endDate).toLocaleDateString()"></td>
                                            <td class="border px-2 py-1" x-text="`${batch.startTime} - ${batch.endTime}`"></td>
                                            <td class="border px-2 py-1" x-text="batch.duration"></td>
                                            <td class="border px-2 py-1" x-text="batch.selectedDays.join(', ')"></td>
                                            <td class="border px-2 py-1" x-text="batch.strength"></td>
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

                    <!-- Submit -->
                    <div class="text-right mt-6">
                        <button type="submit" class="bg-blue-700 hover:bg-blue-600 text-white px-6 py-2 rounded-md font-semibold">Submit</button>
                    </div>
                </form>
            </main>

            <script>
                function batchManager() {
                    return {
                        batchNo: '', batchDate: '', startTime: '', endTime: '',
                        durationType: 'day', duration: '', strength: '',
                        selectedDays: [],
                        weekDays: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                        batches: [], isEditing: false, editIndex: null, conflict: false,

                        getOptions() {
                            if (this.durationType === 'day') return Array.from({ length: 60 }, (_, i) => `${i + 1} day`);
                            if (this.durationType === 'month') return Array.from({ length: 12 }, (_, i) => `${i + 1} month`);
                            if (this.durationType === 'year') return Array.from({ length: 5 }, (_, i) => `${i + 1} year`);
                            return [];
                        },

                        addBatch() {
                            if (!this.validateForm()) return;
                            if (this.hasConflict()) { this.conflict = true; return; }
                            this.conflict = false;
                            this.batches.push(this.getBatchData());
                            this.clearForm();
                        },

                        updateBatch() {
                            if (!this.validateForm()) return;
                            if (this.hasConflict()) { this.conflict = true; return; }
                            this.conflict = false;
                            this.batches[this.editIndex] = this.getBatchData();
                            this.clearForm();
                        },

                        editBatch(index) {
                            const b = this.batches[index];
                            this.batchNo = b.batchNo; this.batchDate = b.batchDate;
                            this.startTime = b.startTime; this.endTime = b.endTime;
                            this.duration = b.duration; this.strength = b.strength;
                            this.durationType = this.getDurationTypeFromString(b.duration);
                            this.selectedDays = [...b.selectedDays];
                            this.editIndex = index; this.isEditing = true;
                        },

                        removeBatch(index) {
                            this.batches.splice(index, 1);
                            if (this.isEditing && this.editIndex === index) this.clearForm();
                        },

                        hasConflict() {
                            const [val, unit] = this.duration.split(' ');
                            const startDate = new Date(this.batchDate);
                            const endDate = new Date(this.batchDate);
                            const dur = parseInt(val);
                            if (unit.includes('day')) endDate.setDate(endDate.getDate() + dur - 1);
                            else if (unit.includes('month')) { endDate.setMonth(endDate.getMonth() + dur); endDate.setDate(endDate.getDate() - 1); }
                            else if (unit.includes('year')) { endDate.setFullYear(endDate.getFullYear() + dur); endDate.setDate(endDate.getDate() - 1); }

                            return this.batches.some((b, i) => {
                                if (this.isEditing && this.editIndex === i) return false;
                                const bStart = new Date(b.batchDate); const bEnd = new Date(b.endDate);
                                const dateOverlap = startDate <= bEnd && endDate >= bStart;
                                const timeOverlap = !(this.endTime <= b.startTime || this.startTime >= b.endTime);
                                return dateOverlap && timeOverlap;
                            });
                        },

                        validateForm() {
                            return this.batchNo && this.batchDate && this.startTime && this.endTime && this.duration && this.strength && this.selectedDays.length > 0 && this.endTime > this.startTime;
                        },

                        getBatchData() {
                            return {
                                batchNo: this.batchNo,
                                batchDate: this.batchDate,
                                startTime: this.startTime,
                                endTime: this.endTime,
                                duration: this.duration,
                                strength: this.strength,
                                selectedDays: [...this.selectedDays],
                                endDate: this.calculateEndDate()
                            };
                        },

                        clearForm() {
                            this.batchNo = ''; this.batchDate = ''; this.startTime = ''; this.endTime = '';
                            this.duration = ''; this.strength = ''; this.durationType = 'day';
                            this.selectedDays = []; this.isEditing = false; this.editIndex = null; this.conflict = false;
                        },

                        getDurationTypeFromString(str) {
                            if (str.includes('day')) return 'day';
                            if (str.includes('month')) return 'month';
                            if (str.includes('year')) return 'year';
                            return 'day';
                        },

                        calculateEndDate() {
                            if (!this.batchDate || !this.duration || !this.durationType || this.selectedDays.length === 0) return '';

                            const weekdaysMap = {
                                Sunday: 0, Monday: 1, Tuesday: 2, Wednesday: 3,
                                Thursday: 4, Friday: 5, Saturday: 6
                            };

                            const selectedIndices = this.selectedDays.map(day => weekdaysMap[day]);
                            const startDate = new Date(this.batchDate);
                            const endDate = new Date(startDate);

                            if (this.durationType === 'day') {
                                let count = 0;
                                while (count < parseInt(this.duration)) {
                                    if (selectedIndices.includes(endDate.getDay())) {
                                        count++;
                                    }
                                    if (count < parseInt(this.duration)) {
                                        endDate.setDate(endDate.getDate() + 1);
                                    }
                                }
                            } else if (this.durationType === 'month') {
                                endDate.setMonth(endDate.getMonth() + parseInt(this.duration));
                                endDate.setDate(endDate.getDate() - 1);
                            } else if (this.durationType === 'year') {
                                endDate.setFullYear(endDate.getFullYear() + parseInt(this.duration));
                                endDate.setDate(endDate.getDate() - 1);
                            }

                            return endDate.toISOString().split('T')[0];
                        }
                    };
                }
            </script>





            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
            <!-- Include Alpine.js -->
            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
            
            </div>
        </div>
    </div>
           



@include('site.trainer.componants.footer')