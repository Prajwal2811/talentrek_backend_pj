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

	 @if($trainerNeedsSubscription)
        @include('site.trainer.subscription.index')
    @endif

    
    <div class="page-wraper">
        <div class="flex h-screen" x-data="{ sidebarOpen: true }" x-init="$watch('sidebarOpen', () => feather.replace())">
          
            @include('site.trainer.componants.sidebar')

            <div class="flex-1 flex flex-col">
                @include('site.trainer.componants.navbar')

            <main class="p-6 max-h-[900px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100">
                <h2 class="text-xl font-semibold mb-6">{{ langLabel('online') }}/{{ langLabel('offline') }} {{ langLabel('course') }}</h2>
                <form action="{{ route('trainer.training.online.save.data') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Course Title -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">{{ langLabel('course_title') }}</label>
                        <input type="text" name="training_title" class="w-full border rounded-md p-2" placeholder="{{ langLabel('enter_course_title') }}" value="{{ old('training_title') }}">
                        @error('training_title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Course Sub Title -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">{{ langLabel('course_sub_title') }}</label>
                        <input type="text" name="training_sub_title" class="w-full border rounded-md p-2" placeholder="{{ langLabel('enter_course_sub_title') }}" value="{{ old('training_sub_title') }}">
                        @error('training_sub_title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Training Objective -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">{{ langLabel('training_objective') }}</label>
                        <textarea name="training_objective" class="w-full border rounded-md p-2 h-24" placeholder="{{ langLabel('enter_training_objective') }}">{{ old('training_objective') }}</textarea>
                        @error('training_objective')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Course Content -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">{{ langLabel('course_content') }}</label>
                        <textarea name="training_descriptions" class="w-full border rounded-md p-2 h-24" placeholder="{{ langLabel('enter_course_content') }}">{{ old('training_descriptions') }}</textarea>
                        @error('training_descriptions')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Training Level -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">{{ langLabel('training_level') }}</label>
                        <select name="training_level" class="w-full border rounded-md p-2">
                            <option value="">{{ langLabel('select_level') }}</option>
                            <option value="Beginner" {{ old('training_level') == 'Beginner' ? 'selected' : '' }}>{{ langLabel('beginner') }}</option>
                            <option value="Intermediate" {{ old('training_level') == 'Intermediate' ? 'selected' : '' }}>{{ langLabel('intermediate') }}</option>
                            <option value="Advanced" {{ old('training_level') == 'Advanced' ? 'selected' : '' }}>{{ langLabel('advanced') }}</option>
                        </select>
                        @error('training_level')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Session Type -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">{{ langLabel('session_type') }}</label>
                        <div class="flex gap-4">
                            <label><input type="radio" name="training_category" value="online" {{ old('training_category') == 'online' ? 'checked' : '' }} class="mr-2"> {{ langLabel('online') }}</label>
                            <label><input type="radio" name="training_category" value="classroom" {{ old('training_category') == 'classroom' ? 'checked' : '' }} class="mr-2"> {{ langLabel('classroom') }}</label>
                        </div>
                        @error('training_category')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Upload Thumbnail -->
                    <div class="mb-4">
                        <label class="block font-medium mb-1">{{ langLabel('upload_thumbnail') }}</label>
                        <input type="file" name="thumbnail" accept="image/*" class="w-full border rounded-md p-2">
                        @error('thumbnail')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Price & Offer Price -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block font-medium mb-1">{{ langLabel('course_price') }}</label>
                            <input type="text" name="training_price" class="w-full border rounded-md p-2" value="{{ old('training_price') }}">
                            @error('training_price')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block font-medium mb-1">{{ langLabel('offer_price') }}</label>
                            <input type="text" name="training_offer_price" class="w-full border rounded-md p-2" value="{{ old('training_offer_price') }}">
                            @error('training_offer_price')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Batches -->
                    <div x-data="batchManager()" class="p-4 border rounded space-y-6">

                       <!-- Input Fields -->
                        <div>
                            <h2 class="text-xl font-semibold mb-4">{{ langLabel('batch_details') }}</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block font-medium mb-1">{{ langLabel('batch_no') }}</label>
                                    <input type="text" x-model="batchNo" @input="clearError('batchNo')" class="border p-2 rounded w-full" placeholder="{{ langLabel('batch_no') }}">
                                    <span x-show="batchNoError" class="text-red-600 text-sm mt-1" x-text="batchNoError"></span>
                                </div>

                                <div>
                                    <label class="block font-medium mb-1">{{ langLabel('batch_date') }}</label>
                                    <input type="date" x-model="batchDate" @input="clearError('batchDate')" class="border p-2 rounded w-full">
                                    <span x-show="batchDateError" class="text-red-600 text-sm mt-1" x-text="batchDateError"></span>
                                </div>

                                <div>
                                    <label class="block font-medium mb-1">{{ langLabel('start_timing') }}</label>
                                    <input type="time" x-model="startTime" @input="clearError('startTime')" class="border p-2 rounded w-full">
                                    <span x-show="startTimeError" class="text-red-600 text-sm mt-1" x-text="startTimeError"></span>
                                </div>

                                <div>
                                    <label class="block font-medium mb-1">{{ langLabel('end_timing') }}</label>
                                    <input type="time" x-model="endTime" @input="clearError('endTime')" class="border p-2 rounded w-full">
                                    <span x-show="endTimeError" class="text-red-600 text-sm mt-1" x-text="endTimeError"></span>
                                </div>

                                <div>
                                    <label class="block font-medium mb-1">{{ langLabel('duration_type') }}</label>
                                    <select x-model="durationType" @input="clearError('duration')" class="border p-2 rounded w-full">
                                        <option value="day">{{ langLabel('days') }}</option>
                                        <option value="month">{{ langLabel('months') }}</option>
                                        <option value="year">{{ langLabel('years') }}</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-medium mb-1">{{ langLabel('duration') }}</label>
                                    <select x-model="duration" @input="clearError('duration')" class="border p-2 rounded w-full">
                                        <option value="">{{ langLabel('select_duration') }}</option>
                                        <template x-for="option in getOptions()" :key="option">
                                            <option :value="option" x-text="option"></option>
                                        </template>
                                    </select>
                                    <span x-show="durationError" class="text-red-600 text-sm mt-1" x-text="durationError"></span>
                                </div>

                                <div>
                                    <label class="block font-medium mb-1">{{ langLabel('candidate') }} {{ langLabel('strength') }}</label>
                                    <input type="number" min="1" x-model="strength" @input="clearError('strength')" placeholder="{{ langLabel('strength') }}" class="border p-2 rounded w-full">
                                    <span x-show="strengthError" class="text-red-600 text-sm mt-1" x-text="strengthError"></span>
                                </div>
                            </div>

                            <!-- Days -->
                            <div class="mt-4">
                                <label class="block font-medium mb-1">{{ langLabel('select_days') }}</label>
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
                                ⚠️ {{ langLabel('this_conflict_another_batch') }}
                            </div>

                            <div class="mt-4">
                                <button type="button" @click="addBatch" x-show="!isEditing" class="bg-blue-600 text-white px-4 py-2 rounded">+ {{ langLabel('add') }} {{ langLabel('batch') }}</button>
                                <button type="button" @click="updateBatch" x-show="isEditing" class="bg-green-600 text-white px-4 py-2 rounded">✓ {{ langLabel('update') }} {{ langLabel('batch') }}</button>
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
                            <h2 class="text-xl font-semibold mb-2">{{ langLabel('added_batches') }}</h2>
                            <table class="min-w-full bg-white border text-sm">
                                <thead class="bg-gray-100 font-semibold">
                                    <tr>
                                        <th class="px-4 py-2 border">#</th>
                                        <th class="px-4 py-2 border">{{ langLabel('batch_no') }}</th>
                                        <th class="px-4 py-2 border">{{ langLabel('start_timing') }}</th>
                                        <th class="px-4 py-2 border">{{ langLabel('end_timing') }}</th>
                                        <th class="px-4 py-2 border">{{ langLabel('time') }}</th>
                                        <th class="px-4 py-2 border">{{ langLabel('duration') }}</th>
                                        <th class="px-4 py-2 border">{{ langLabel('days') }}</th>
                                        <th class="px-4 py-2 border">{{ langLabel('strength') }}</th>
                                        <th class="px-4 py-2 border">{{ langLabel('edit') }}</th>
                                        <th class="px-4 py-2 border">{{ langLabel('delete') }}</th>
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
                                                <button type="button" @click="editBatch(index)" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded">{{ langLabel('edit') }}</button>
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
                        <button type="submit" class="bg-blue-700 hover:bg-blue-600 text-white px-6 py-2 rounded-md font-semibold">{{ langLabel('submit') }}</button>
                    </div>
                </form>
            </main>

            <!-- <script>
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
            </script> -->

            <style>
                .error-message {
                    display: block;
                    color: red;
                    font-size: 14px;
                    margin-top: 0.25rem;
                    font-weight: bold; /* Optional, to make the message bold */
                }

                /* No need for border red styles anymore */
                .hidden {
                    display: none !important;
                }
            </style>

            <script>
                // Alpine.js Batch Manager
                function batchManager() {
                    return {
                        batchNo: '', batchDate: '', startTime: '', endTime: '',
                        durationType: 'day', duration: '', strength: '',
                        selectedDays: [],
                        weekDays: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                        batches: [], isEditing: false, editIndex: null, conflict: false,

                        // Error messages
                        batchNoError: '', batchDateError: '', startTimeError: '', endTimeError: '', durationError: '', strengthError: '',

                        init() {
                            // Initialize any required setup
                        },

                        getOptions() {
                            if (this.durationType === 'day') return Array.from({ length: 60 }, (_, i) => `${i + 1} day`);
                            if (this.durationType === 'month') return Array.from({ length: 12 }, (_, i) => `${i + 1} month`);
                            if (this.durationType === 'year') return Array.from({ length: 5 }, (_, i) => `${i + 1} year`);
                            return [];
                        },

                        // Clear error message when input changes
                        clearError(field) {
                            if (field === 'batchNo') this.batchNoError = '';
                            if (field === 'batchDate') this.batchDateError = '';
                            if (field === 'startTime') this.startTimeError = '';
                            if (field === 'endTime') this.endTimeError = '';
                            if (field === 'duration') this.durationError = '';
                            if (field === 'strength') this.strengthError = '';
                        },

                        addBatch() {
                            if (!this.validateForm()) return;
                            if (this.hasConflict()) { 
                                this.conflict = true; 
                                return; 
                            }

                            this.conflict = false;
                            this.batches.push(this.getBatchData());
                            this.resetForm();
                        },

                        updateBatch() {
                            if (!this.validateForm()) return;
                            if (this.hasConflict()) { 
                                this.conflict = true; 
                                return; 
                            }

                            this.conflict = false;
                            this.batches[this.editIndex] = this.getBatchData();
                            this.resetForm();
                        },

                        editBatch(index) {
                            const batch = this.batches[index];
                            this.batchNo = batch.batchNo;
                            this.batchDate = batch.batchDate;
                            this.startTime = batch.startTime;
                            this.endTime = batch.endTime;
                            this.duration = batch.duration;
                            this.strength = batch.strength;
                            this.durationType = this.getDurationTypeFromString(batch.duration);
                            this.selectedDays = [...batch.selectedDays];
                            this.editIndex = index;
                            this.isEditing = true;

                            // Clear any validation errors when editing
                            this.clearValidationErrors();
                        },

                        removeBatch(index) {
                            this.batches.splice(index, 1);
                            if (this.isEditing && this.editIndex === index) {
                                this.resetForm();
                            }
                        },

                        hasConflict() {
                            if (!this.batchDate || !this.duration) return false;

                            const [value, unit] = this.duration.split(' ');
                            const durationValue = parseInt(value);
                            const startDate = new Date(this.batchDate);
                            const endDate = new Date(startDate);

                            // Calculate end date based on duration
                            if (unit.includes('day')) {
                                endDate.setDate(startDate.getDate() + durationValue - 1);
                            } 
                            else if (unit.includes('month')) {
                                endDate.setMonth(startDate.getMonth() + durationValue);
                                endDate.setDate(startDate.getDate() - 1);
                            } 
                            else if (unit.includes('year')) {
                                endDate.setFullYear(startDate.getFullYear() + durationValue);
                                endDate.setDate(startDate.getDate() - 1);
                            }

                            // Check for overlapping batches
                            return this.batches.some((batch, i) => {
                                if (this.isEditing && i === this.editIndex) return false;

                                const batchStart = new Date(batch.batchDate);
                                const batchEnd = new Date(batch.endDate);

                                const dateOverlap = startDate <= batchEnd && endDate >= batchStart;
                                const timeOverlap = !(this.endTime <= batch.startTime || this.startTime >= batch.endTime);

                                return dateOverlap && timeOverlap;
                            });
                        },

                        validateForm() {
                            let isValid = true;

                            // Clear previous errors
                            this.clearValidationErrors();

                            // Validate Batch No
                            if (!this.batchNo.trim()) {
                                this.batchNoError = 'Batch No is required';
                                isValid = false;
                            }

                            // Validate Batch Date
                            if (!this.batchDate) {
                                this.batchDateError = 'Batch Date is required';
                                isValid = false;
                            }

                            // Validate Start Time
                            if (!this.startTime) {
                                this.startTimeError = 'Start Time is required';
                                isValid = false;
                            }

                            // Validate End Time
                            if (!this.endTime) {
                                this.endTimeError = 'End Time is required';
                                isValid = false;
                            } else if (this.startTime && this.endTime <= this.startTime) {
                                this.endTimeError = 'End Time must be after Start Time';
                                isValid = false;
                            }

                            // Validate Duration
                            if (!this.duration) {
                                this.durationError = 'Duration is required';
                                isValid = false;
                            }

                            // Validate Strength
                            if (!this.strength || parseInt(this.strength) < 1) {
                                this.strengthError = 'Valid Strength is required (minimum 1)';
                                isValid = false;
                            }

                            return isValid;
                        },

                        clearValidationErrors() {
                            this.batchNoError = '';
                            this.batchDateError = '';
                            this.startTimeError = '';
                            this.endTimeError = '';
                            this.durationError = '';
                            this.strengthError = '';
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

                        resetForm() {
                            this.batchNo = '';
                            this.batchDate = '';
                            this.startTime = '';
                            this.endTime = '';
                            this.duration = '';
                            this.strength = '';
                            this.durationType = 'day';
                            this.selectedDays = [];
                            this.isEditing = false;
                            this.editIndex = null;
                            this.conflict = false;

                            this.clearValidationErrors();
                        },

                        getDurationTypeFromString(str) {
                            if (!str) return 'day';
                            if (str.includes('day')) return 'day';
                            if (str.includes('month')) return 'month';
                            if (str.includes('year')) return 'year';
                            return 'day';
                        },

                        calculateEndDate() {
                            if (!this.batchDate || !this.duration || this.selectedDays.length === 0) return '';

                            const [value, unit] = this.duration.split(' ');
                            const durationValue = parseInt(value);
                            const startDate = new Date(this.batchDate);
                            const endDate = new Date(startDate);

                            if (unit.includes('day')) {
                                // Calculate based on selected days
                                const dayMap = {
                                    Sunday: 0, Monday: 1, Tuesday: 2, Wednesday: 3,
                                    Thursday: 4, Friday: 5, Saturday: 6
                                };
                                const selectedDayIndices = this.selectedDays.map(day => dayMap[day]);

                                let daysCount = 0;
                                while (daysCount < durationValue) {
                                    if (selectedDayIndices.includes(endDate.getDay())) {
                                        daysCount++;
                                    }
                                    if (daysCount < durationValue) {
                                        endDate.setDate(endDate.getDate() + 1);
                                    }
                                }
                            } 
                            else if (unit.includes('month')) {
                                endDate.setMonth(startDate.getMonth() + durationValue);
                                endDate.setDate(startDate.getDate() - 1);
                            } 
                            else if (unit.includes('year')) {
                                endDate.setFullYear(startDate.getFullYear() + durationValue);
                                endDate.setDate(startDate.getDate() - 1);
                            }

                            return endDate.toISOString().split('T')[0];
                        }
                    };
                }



                // Initialize when document is ready
                $(document).ready(function() {
                    // Add error message containers
                    $('.validation-container').each(function() {
                        if (!$(this).find('.error-message').length) {
                            $(this).append('<span class="error-message text-red-500 text-sm mt-1 hidden"></span>');
                        }
                    });

                    // Add days container error message
                    if (!$('.days-container').find('.error-message').length) {
                        $('.days-container').append('<span class="error-message text-red-500 text-sm mt-1 hidden"></span>');
                    }

                    // Real-time validation clearing
                    $('input, select').on('input change', function() {
                        const fieldName = $(this).attr('x-model');
                        if (fieldName) {
                            const $error = $(this).closest('div').find('.error-message');
                            $error.addClass('hidden').text('');
                            $(this).removeClass('border-red-500');
                        }
                    });

                    // Days selection validation clearing
                    $('[x-model="selectedDays"]').on('change', function() {
                        $('.days-container .error-message').addClass('hidden').text('');
                    });
                });
            </script>




            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
            <!-- Include Alpine.js -->
            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
            
            </div>
        </div>
    </div>
           



@include('site.trainer.componants.footer')