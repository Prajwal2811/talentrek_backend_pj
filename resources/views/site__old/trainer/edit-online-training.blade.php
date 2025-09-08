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
                    <h2 class="text-xl font-semibold mb-6">Online/Offline course</h2>
                <form action="{{ route('trainer.training.online.update.data', $training->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                            <!-- Course Title -->
                            <div class="mb-4">
                                <label class="block font-medium mb-1">Course Title</label>
                                <input type="text" name="training_title"
                                value="{{ old('training_title', $training->training_title ?? '') }}"
                                placeholder="Enter the Course Title" class="w-full border rounded-md p-2" />
                                @error('training_title')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Course Sub Title -->
                            <div class="mb-4">
                                <label class="block font-medium mb-1">Course Sub Title</label>
                                <input type="text" name="training_sub_title"
                                value="{{ old('training_sub_title', $training->training_sub_title ?? '') }}"
                                placeholder="Enter the Sub Title" class="w-full border rounded-md p-2" />
                                @error('training_sub_title')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label class="block font-medium mb-1">Training Objective</label>
                                <textarea name="training_objective"
                                    class="w-full border rounded-md p-2 h-24">{{ old('training_objective', $training->training_objective ?? '') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="block font-medium mb-1">Course Content</label>
                                <textarea name="training_descriptions"
                                class="w-full border rounded-md p-2 h-24">{{ old('training_descriptions', $training->training_descriptions ?? '') }}</textarea>
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
                                        <input type="radio" name="training_category" value="online"
                                        {{ old('training_category', $training->session_type ?? '') == 'online' ? 'checked' : '' }} /> 
                                            Online
                                    </label>
                                    <label>
                                        <input type="radio" name="training_category" value="classroom"
                                        {{ old('training_category', $training->session_type ?? '') == 'classroom' ? 'checked' : '' }} /> Classroom

                                    </label>
                                </div>
                            </div>



                            <!-- Upload Thumbnail -->
                            <div class="mb-4">
                                <label class="block font-medium mb-1">Upload Thumbnail</label>
                                <div class="flex gap-4 items-center">
                                <input type="file" name="thumbnail" class="border rounded-md p-2 flex-1" />
                                </div>
                                <!-- Show existing thumbnail (optional) -->
                                @if (!empty($training->thumbnail_file_path))
                                    <div class="mb-2">
                                        <img src="{{ $training->thumbnail_file_path }}" alt="Thumbnail" class="w-24 h-16 rounded">
                                    </div>
                                @endif
                            </div>


                            <!-- Course Price and Offer Price -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <!-- Course Price -->
                                <div>
                                    <label class="block font-medium mb-1">Course Price</label>
                                    <input type="text" name="training_price" placeholder="Enter Course Price"
                                    value="{{ old('training_price', $training->training_price) }}"
                                    class="w-full border rounded-md p-2" />
                                    @error('training_price')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Course Offer Price -->
                                <div>
                                    <label class="block font-medium mb-1">Course Offer Price</label>
                                    <input type="text" name="training_offer_price" placeholder="Enter Offer Price"
                                    value="{{ old('training_offer_price', $training->training_offer_price) }}"
                                    class="w-full border rounded-md p-2" />
                                    @error('training_offer_price')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                        

                        <div x-data="batchManager()" x-init='initializeBatches(@json($batches))' class="mt-6">
                            <!-- Batch Input Fields -->
                            <div class="mb-8">
                                <h2 class="text-xl font-semibold mb-4">Batch details</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block mb-1 font-medium">Batch No</label>
                                        <input type="text" x-model="batchNo" @input="clearError('batchNo')" placeholder="Enter batch no" class="border p-2 rounded w-full" />
                                        <span x-show="batchNoError" class="text-red-600 text-sm mt-1" x-text="batchNoError"></span>
                                    </div>
                                    <div>
                                        <label class="block mb-1 font-medium">Start Date</label>
                                        <input type="date" x-model="batchDate" @input="clearError('batchDate')" class="border p-2 rounded w-full" />
                                        <span x-show="batchDateError" class="text-red-600 text-sm mt-1" x-text="batchDateError"></span>
                                    </div>
                                    <div>
                                        <label class="block mb-1 font-medium">Start Timing</label>
                                        <input type="time" x-model="startTime" @input="clearError('startTime')" class="border p-2 rounded w-full" />
                                        <span x-show="startTimeError" class="text-red-600 text-sm mt-1" x-text="startTimeError"></span>
                                    </div>
                                    <div>
                                        <label class="block mb-1 font-medium">End Timing</label>
                                        <input type="time" x-model="endTime" @input="clearError('endTime')" class="border p-2 rounded w-full" />
                                        <span x-show="endTimeError" class="text-red-600 text-sm mt-1" x-text="endTimeError"></span>
                                    </div>
                                    <div>
                                        <label class="block font-medium mb-1">Duration Type</label>
                                        <select x-model="durationType" @input="clearError('duration')" class="border p-2 rounded w-full">
                                            <option value="day">Days</option>
                                            <option value="month">Months</option>
                                            <option value="year">Years</option>
                                        </select>
                                        <span class="text-red-600 text-sm mt-1" ></span>
                                    </div>
                                    <div>
                                        <label class="block font-medium mb-1">Duration</label>
                                        <select x-model="duration" @input="clearError('duration')" class="border p-2 rounded w-full">
                                            <option value="">Select Duration</option>
                                            <template x-for="option in getOptions()" :key="option">
                                                <option :value="option" x-text="option"></option>
                                            </template>
                                        </select>
                                        <span x-show="durationError" class="text-red-600 text-sm mt-1" x-text="durationError"></span>
                                    </div>
                                    <div>
                                        <label class="block font-medium mb-1">Candidate Strength</label>
                                        <input type="number" min="1" x-model="strength" @input="clearError('strength')" class="border p-2 rounded w-full" placeholder="Strength" />
                                        <span x-show="strengthError" class="text-red-600 text-sm mt-1" x-text="strengthError"></span>
                                    </div>
                                </div>

                                <!-- Select Days -->
                                <div class="mt-4">
                                    <label class="block font-medium mb-1">Select Days</label>
                                    <div class="flex flex-wrap gap-4">
                                        <template x-for="(day, index) in weekDays" :key="index">
                                            <label class="flex items-center space-x-2">
                                                <input type="checkbox" :value="day" x-model="selectedDays" class="form-checkbox text-blue-600" />
                                                <span x-text="day"></span>
                                            </label>
                                        </template>
                                    </div>
                                    <span x-show="daysError" class="text-red-600 text-sm mt-1" x-text="daysError"></span>
                                </div>

                                <!-- Conflict Message -->
                                <div x-show="conflict" class="text-red-600 mt-2 font-semibold">
                                    ⚠️ Selected timing conflicts with another batch.
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-4">
                                    <button type="button" x-show="!isEditing" @click="addBatch" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                                        + Add Batch
                                    </button>
                                    <button type="button" x-show="isEditing" @click="updateBatch" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                                        ✓ Update Batch
                                    </button>
                                </div>
                            </div>


                            <!-- Hidden Inputs for Submission -->
                            <!-- Inside the template loop for hidden inputs -->
                            <template x-for="(batch, index) in batches" :key="'form-' + index">
                                <div>
                                    <input type="hidden" :name="`content_sections[${index}][batch_no]`" :value="batch.batchNo">
                                    <input type="hidden" :name="`content_sections[${index}][batch_date]`" :value="batch.batchDate">
                                    <input type="hidden" :name="`content_sections[${index}][start_time]`" :value="batch.startTime">
                                    <input type="hidden" :name="`content_sections[${index}][end_time]`" :value="batch.endTime">
                                    <input type="hidden" :name="`content_sections[${index}][duration]`" :value="batch.duration">
                                    <input type="hidden" :name="`content_sections[${index}][strength]`" :value="batch.strength">
                                    <input type="hidden" :name="`content_sections[${index}][days]`" :value="JSON.stringify(batch.selectedDays)">
                                    <input type="hidden" :name="`content_sections[${index}][end_date]`" :value="calculateEndDate(batch)">
                                </div>
                            </template>


                            <!-- Batch Table -->
                            <div class="overflow-x-auto mt-6">
                                <h2 class="text-xl font-semibold mb-4">Batch List</h2>
                                <table class="min-w-full bg-white border">
                                    <thead class="bg-gray-100 text-sm font-medium text-gray-700">
                                        <tr>
                                            <th class="py-2 px-4 border-b">Sr. No.</th>
                                            <th class="py-2 px-4 border-b">Batch No</th>
                                            <th class="py-2 px-4 border-b">Date</th>
                                            <th class="py-2 px-4 border-b">Time</th>
                                            <th class="py-2 px-4 border-b">Duration</th>
                                            <th class="py-2 px-4 border-b">Days</th>
                                            <th class="py-2 px-4 border-b">Strength</th>
                                            <th class="py-2 px-4 border-b">Edit</th>
                                            <th class="py-2 px-4 border-b">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(batch, index) in batches" :key="index">
                                            <tr class="bg-gray-50 text-sm">
                                                <td class="py-2 px-4 border-b" x-text="index + 1"></td>
                                                <td class="py-2 px-4 border-b" x-text="batch.batchNo"></td>
                                                <td class="py-2 px-4 border-b" x-text="batch.batchDate"></td>
                                                <td class="py-2 px-4 border-b" x-text="`${batch.startTime} - ${batch.endTime}`"></td>
                                                <td class="py-2 px-4 border-b" x-text="batch.duration"></td>
                                                <td class="py-2 px-4 border-b" x-text="batch.selectedDays.join(', ')"></td>
                                                <td class="py-2 px-4 border-b" x-text="batch.strength"></td>
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

            

                        <!-- Submit Button -->
                        <div class="text-right mt-5">
                            <button type="submit" class="bg-blue-800 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-md font-semibold">
                            Submit
                            </button>
                        </div>
                    </form>
                </main>

                <!-- <script>
                    function batchManager() {
                        return {
                            // Form fields
                            batchNo: '',
                            batchDate: '',
                            startTime: '',
                            endTime: '',
                            durationType: 'day',
                            duration: '',
                            strength: '',
                            selectedDays: [],

                            // Static
                            weekDays: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],

                            // State
                            batches: [],
                            isEditing: false,
                            editIndex: null,
                            conflict: false,

                            initializeBatches(serverBatches) {
                                this.batches = serverBatches.map(b => ({
                                    batchNo: b.batch_no,
                                    batchDate: b.start_date,
                                    startTime: b.start_timing,
                                    endTime: b.end_timing,
                                    duration: b.duration,
                                    strength: b.strength,
                                    selectedDays: b.days ?? [],
                                }));
                            },

                            getOptions() {
                                if (this.durationType === 'day') return Array.from({ length: 60 }, (_, i) => `${i + 1} day`);
                                if (this.durationType === 'month') return Array.from({ length: 12 }, (_, i) => `${i + 1} month`);
                                if (this.durationType === 'year') return Array.from({ length: 5 }, (_, i) => `${i + 1} year`);
                                return [];
                            },

                            validateForm() {
                                if (!this.batchNo || !this.batchDate || !this.startTime || !this.endTime || !this.duration || !this.strength || this.selectedDays.length === 0) {
                                    alert("Please fill all fields and select at least one day.");
                                    return false;
                                }
                                if (this.endTime <= this.startTime) {
                                    alert("End time must be after start time.");
                                    return false;
                                }
                                return true;
                            },

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

                            editBatch(index) {
                                const batch = this.batches[index];
                                this.batchNo = batch.batchNo;
                                this.batchDate = batch.batchDate;
                                this.startTime = batch.startTime;
                                this.endTime = batch.endTime;
                                this.duration = batch.duration;
                                this.durationType = this.getDurationTypeFromString(batch.duration);
                                this.strength = batch.strength;
                                this.selectedDays = [...batch.selectedDays];
                                this.isEditing = true;
                                this.editIndex = index;
                            },

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

                            removeBatch(index) {
                                this.batches.splice(index, 1);
                                if (this.isEditing && this.editIndex === index) this.clearForm();
                            },

                            getBatchData() {
                                return {
                                    batchNo: this.batchNo,
                                    batchDate: this.batchDate,
                                    startTime: this.startTime,
                                    endTime: this.endTime,
                                    duration: this.duration,
                                    strength: this.strength,
                                    selectedDays: [...this.selectedDays]
                                };
                            },

                            getDurationTypeFromString(str) {
                                if (str.includes('day')) return 'day';
                                if (str.includes('month')) return 'month';
                                if (str.includes('year')) return 'year';
                                return 'day';
                            },

                            clearForm() {
                                this.batchNo = '';
                                this.batchDate = '';
                                this.startTime = '';
                                this.endTime = '';
                                this.duration = '';
                                this.durationType = 'day';
                                this.strength = '';
                                this.selectedDays = [];
                                this.isEditing = false;
                                this.editIndex = null;
                                this.conflict = false;
                            },

                            hasConflict() {
                                const [val, unit] = this.duration.split(' ');
                                const startDate = new Date(this.batchDate);
                                const endDate = new Date(this.batchDate);
                                const durationValue = parseInt(val);

                                if (unit.includes('day')) endDate.setDate(endDate.getDate() + durationValue - 1);
                                if (unit.includes('month')) { endDate.setMonth(endDate.getMonth() + durationValue); endDate.setDate(endDate.getDate() - 1); }
                                if (unit.includes('year')) { endDate.setFullYear(endDate.getFullYear() + durationValue); endDate.setDate(endDate.getDate() - 1); }

                                return this.batches.some((b, i) => {
                                    if (this.isEditing && this.editIndex === i) return false;

                                    const bStart = new Date(b.batchDate);
                                    const bEnd = new Date(b.batchDate);
                                    const [bVal, bUnit] = b.duration.split(' ');
                                    const bDur = parseInt(bVal);

                                    if (bUnit.includes('day')) bEnd.setDate(bEnd.getDate() + bDur - 1);
                                    if (bUnit.includes('month')) { bEnd.setMonth(bEnd.getMonth() + bDur); bEnd.setDate(bEnd.getDate() - 1); }
                                    if (bUnit.includes('year')) { bEnd.setFullYear(bEnd.getFullYear() + bDur); bEnd.setDate(bEnd.getDate() - 1); }

                                    const isDateOverlap = (startDate <= bEnd && endDate >= bStart);
                                    const isTimeOverlap = !(this.endTime <= b.startTime || this.startTime >= b.endTime);
                                    return isDateOverlap && isTimeOverlap;
                                });
                            },

                            calculateEndDate(batch) {
                                const weekdaysMap = {
                                    Sunday: 0, Monday: 1, Tuesday: 2, Wednesday: 3,
                                    Thursday: 4, Friday: 5, Saturday: 6
                                };

                                const selectedIndices = batch.selectedDays.map(day => weekdaysMap[day]);
                                const startDate = new Date(batch.batchDate);
                                const endDate = new Date(batch.batchDate);

                                const [val, unit] = batch.duration.split(' ');
                                const count = parseInt(val);

                                if (unit.includes('day')) {
                                    let i = 0;
                                    while (i < count) {
                                        if (selectedIndices.includes(endDate.getDay())) {
                                            i++;
                                        }
                                        if (i < count) endDate.setDate(endDate.getDate() + 1);
                                    }
                                } else if (unit.includes('month')) {
                                    endDate.setMonth(endDate.getMonth() + count);
                                    endDate.setDate(endDate.getDate() - 1);
                                } else if (unit.includes('year')) {
                                    endDate.setFullYear(endDate.getFullYear() + count);
                                    endDate.setDate(endDate.getDate() - 1);
                                }

                                return endDate.toISOString().split('T')[0];
                            }
                        };
                    }
                </script> -->

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
                            strength: '',
                            selectedDays: [],

                            // Error messages
                            batchNoError: '',
                            batchDateError: '',
                            startTimeError: '',
                            endTimeError: '',
                            durationError: '',
                            strengthError: '',
                            daysError: '',

                            // Static
                            weekDays: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],

                            // State
                            batches: [],
                            isEditing: false,
                            editIndex: null,
                            conflict: false,

                            initializeBatches(serverBatches) {
                                this.batches = serverBatches.map(b => ({
                                    batchNo: b.batch_no,
                                    batchDate: b.start_date,
                                    startTime: b.start_timing,
                                    endTime: b.end_timing,
                                    duration: b.duration,
                                    strength: b.strength,
                                    selectedDays: b.days ?? [],
                                }));
                            },

                            getOptions() {
                                if (this.durationType === 'day') return Array.from({ length: 60 }, (_, i) => `${i + 1} day`);
                                if (this.durationType === 'month') return Array.from({ length: 12 }, (_, i) => `${i + 1} month`);
                                if (this.durationType === 'year') return Array.from({ length: 5 }, (_, i) => `${i + 1} year`);
                                return [];
                            },

                            // Validation function
                            validateForm() {
                                let isValid = true;

                                // Clear errors
                                this.clearValidationErrors();

                                // Validate Batch No
                                if (!this.batchNo.trim()) {
                                    this.batchNoError = 'Batch No is required';
                                    isValid = false;
                                }

                                // Validate Batch Date
                                if (!this.batchDate) {
                                    this.batchDateError = 'Start Date is required';
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

                                // Validate Days Selection
                                if (this.selectedDays.length === 0) {
                                    this.daysError = 'At least one day must be selected';
                                    isValid = false;
                                }

                                return isValid;
                            },

                            // Error clearing
                            clearValidationErrors() {
                                this.batchNoError = '';
                                this.batchDateError = '';
                                this.startTimeError = '';
                                this.endTimeError = '';
                                this.durationError = '';
                                this.strengthError = '';
                                this.daysError = '';
                            },

                            // Real-time error clearing
                            clearError(field) {
                                if (field === 'batchNo') this.batchNoError = '';
                                if (field === 'batchDate') this.batchDateError = '';
                                if (field === 'startTime') this.startTimeError = '';
                                if (field === 'endTime') this.endTimeError = '';
                                if (field === 'duration') this.durationError = '';
                                if (field === 'strength') this.strengthError = '';
                                if (field === 'selectedDays') this.daysError = '';
                            },

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

                            editBatch(index) {
                                const batch = this.batches[index];
                                this.batchNo = batch.batchNo;
                                this.batchDate = batch.batchDate;
                                this.startTime = batch.startTime;
                                this.endTime = batch.endTime;
                                this.duration = batch.duration;
                                this.durationType = this.getDurationTypeFromString(batch.duration);
                                this.strength = batch.strength;
                                this.selectedDays = [...batch.selectedDays];
                                this.isEditing = true;
                                this.editIndex = index;
                            },

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

                            removeBatch(index) {
                                this.batches.splice(index, 1);
                                if (this.isEditing && this.editIndex === index) this.clearForm();
                            },

                            getBatchData() {
                                return {
                                    batchNo: this.batchNo,
                                    batchDate: this.batchDate,
                                    startTime: this.startTime,
                                    endTime: this.endTime,
                                    duration: this.duration,
                                    strength: this.strength,
                                    selectedDays: [...this.selectedDays]
                                };
                            },

                            getDurationTypeFromString(str) {
                                if (str.includes('day')) return 'day';
                                if (str.includes('month')) return 'month';
                                if (str.includes('year')) return 'year';
                                return 'day';
                            },

                            clearForm() {
                                this.batchNo = '';
                                this.batchDate = '';
                                this.startTime = '';
                                this.endTime = '';
                                this.duration = '';
                                this.durationType = 'day';
                                this.strength = '';
                                this.selectedDays = [];
                                this.isEditing = false;
                                this.editIndex = null;
                                this.conflict = false;
                            },

                            hasConflict() {
                                const [val, unit] = this.duration.split(' ');
                                const startDate = new Date(this.batchDate);
                                const endDate = new Date(this.batchDate);
                                const durationValue = parseInt(val);

                                if (unit.includes('day')) endDate.setDate(endDate.getDate() + durationValue - 1);
                                if (unit.includes('month')) { endDate.setMonth(endDate.getMonth() + durationValue); endDate.setDate(endDate.getDate() - 1); }
                                if (unit.includes('year')) { endDate.setFullYear(endDate.getFullYear() + durationValue); endDate.setDate(endDate.getDate() - 1); }

                                return this.batches.some((b, i) => {
                                    if (this.isEditing && this.editIndex === i) return false;

                                    const bStart = new Date(b.batchDate);
                                    const bEnd = new Date(b.batchDate);
                                    const [bVal, bUnit] = b.duration.split(' ');
                                    const bDur = parseInt(bVal);

                                    if (bUnit.includes('day')) bEnd.setDate(bEnd.getDate() + bDur - 1);
                                    if (bUnit.includes('month')) { bEnd.setMonth(bEnd.getMonth() + bDur); bEnd.setDate(bEnd.getDate() - 1); }
                                    if (bUnit.includes('year')) { bEnd.setFullYear(bEnd.getFullYear() + bDur); bEnd.setDate(bEnd.getDate() - 1); }

                                    const isDateOverlap = (startDate <= bEnd && endDate >= bStart);
                                    const isTimeOverlap = !(this.endTime <= b.startTime || this.startTime >= b.endTime);
                                    return isDateOverlap && isTimeOverlap;
                                });
                            }
                        };
                    }

                </script>


            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
            <!-- Include Alpine.js -->
            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
          


            </script>


          



            </div>
        </div>
    </div>
           



          
@include('site.trainer.componants.footer')