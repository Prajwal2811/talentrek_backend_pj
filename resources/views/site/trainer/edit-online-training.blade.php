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
                    <form action="{{ route('trainer.training.online.update.data', $training->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                            <!-- Course Title -->
                            <div class="mb-4">
                                <label class="block font-medium mb-1">{{ langLabel('course_title') }}</label>
                                <input type="text" name="training_title"
                                value="{{ old('training_title', $training->training_title ?? '') }}"
                                placeholder="{{ langLabel('enter_course_title') }}" class="w-full border rounded-md p-2" />
                                @error('training_title')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Course Sub Title -->
                            <div class="mb-4">
                                <label class="block font-medium mb-1">{{ langLabel('course_sub_title') }}</label>
                                <input type="text" name="training_sub_title"
                                value="{{ old('training_sub_title', $training->training_sub_title ?? '') }}"
                                placeholder="{{ langLabel('enter_course_sub_title') }}" class="w-full border rounded-md p-2" />
                                @error('training_sub_title')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label class="block font-medium mb-1">{{ langLabel('training_objective') }}</label>
                                <textarea name="training_objective"
                                    class="w-full border rounded-md p-2 h-24">{{ old('training_objective', $training->training_objective ?? '') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="block font-medium mb-1">{{ langLabel('course_content') }}</label>
                                <textarea id="trainingDescriptionsEditor" name="training_descriptions">
                                    {{ old('training_descriptions', $training->training_descriptions ?? '') }}
                                </textarea>
                                @error('training_descriptions')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Rich Text Editor CSS & JS (include only once per page) -->
                             <link rel="stylesheet" href="{{ asset('asset/richtexteditor/richtexteditor/rte_theme_default.css')}}" />
                    <script type="text/javascript" src="{{ asset('asset/richtexteditor/richtexteditor/plugins/all_plugins.js')}}"></script>
                    <script type="text/javascript" src="{{ asset('asset/richtexteditor/richtexteditor/rte.js')}}"></script>

                            <script>
                                var trainingDescriptionsEditor = new RichTextEditor("#trainingDescriptionsEditor");
                            </script>


                            <!-- Training Level -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block font-medium mb-1">{{ langLabel('training_level') }}</label>
                                    <select name="training_level" class="w-full border rounded-md p-2">
                                        <option value="">{{ langLabel('select_training_level') }}</option>
                                        <option value="Beginner" {{ old('training_level', $training->training_level ?? '') == 'Beginner' ? 'selected' : '' }}>{{ langLabel('beginner') }}</option>
                                        <option value="Intermediate" {{ old('training_level', $training->training_level ?? '') == 'Intermediate' ? 'selected' : '' }}>{{ langLabel('intermediate') }}</option>
                                        <option value="Advanced" {{ old('training_level', $training->training_level ?? '') == 'Advanced' ? 'selected' : '' }}>{{ langLabel('advanced') }}</option>
                                    </select>
                                    @error('training_level')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                            <!-- Category -->
                            <div class="mb-4">
                                <label class="block font-medium mb-2">{{ langLabel('session_type') }}</label>
                                <div class="flex flex-wrap gap-4">
                                    <label>
                                        <input type="radio" name="training_category" value="online"
                                        {{ old('training_category', $training->session_type ?? '') == 'online' ? 'checked' : '' }} /> 
                                            {{ langLabel('online') }}
                                    </label>
                                    <label>
                                        <input type="radio" name="training_category" value="classroom"
                                        {{ old('training_category', $training->session_type ?? '') == 'classroom' ? 'checked' : '' }} /> {{ langLabel('classroom') }}

                                    </label>
                                </div>
                            </div>



                            <!-- Upload Thumbnail -->
                            <div class="mb-4">
                                <label class="block font-medium mb-1">{{ langLabel('upload_thumbnail') }}</label>
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
                                    <label class="block font-medium mb-1">{{ langLabel('course_price') }}</label>
                                    <input type="text" name="training_price" placeholder="Enter Course Price"
                                    value="{{ old('training_price', $training->training_price) }}"
                                    class="w-full border rounded-md p-2" />
                                    @error('training_price')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Course Offer Price -->
                                <div>
                                    <label class="block font-medium mb-1">{{ langLabel('offer_price') }}</label>
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
                                <h2 class="text-xl font-semibold mb-4">{{ langLabel('batch_details') }}</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block mb-1 font-medium">{{ langLabel('batch_no') }}</label>
                                        <input type="text" x-model="batchNo" @input="clearError('batchNo')" placeholder="{{ langLabel('enter_batch_no') }}" class="border p-2 rounded w-full" />
                                        <span x-show="batchNoError" class="text-red-600 text-sm mt-1" x-text="batchNoError"></span>
                                    </div>
                                    <div>
                                        <label class="block mb-1 font-medium">{{ langLabel('start_date') }}</label>
                                        <input type="date" x-model="batchDate" @input="clearError('batchDate')" class="border p-2 rounded w-full" />
                                        <span x-show="batchDateError" class="text-red-600 text-sm mt-1" x-text="batchDateError"></span>
                                    </div>
                                    <div>
                                        <label class="block mb-1 font-medium">{{ langLabel('start_timing') }}</label>
                                        <input type="time" x-model="startTime" @input="clearError('startTime')" class="border p-2 rounded w-full" />
                                        <span x-show="startTimeError" class="text-red-600 text-sm mt-1" x-text="startTimeError"></span>
                                    </div>
                                    <div>
                                        <label class="block mb-1 font-medium">{{ langLabel('end_timing') }}</label>
                                        <input type="time" x-model="endTime" @input="clearError('endTime')" class="border p-2 rounded w-full" />
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
                                        <input type="number" min="1" x-model="strength" @input="clearError('strength')" class="border p-2 rounded w-full" placeholder="Strength" />
                                        <span x-show="strengthError" class="text-red-600 text-sm mt-1" x-text="strengthError"></span>
                                    </div>
                                </div>

                                <!-- Select Days -->
                                <div class="mt-4">
                                    <label class="block font-medium mb-1">{{ langLabel('select_days') }}</label>
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
                                        + {{ langLabel('add_batch') }}
                                    </button>
                                    <button type="button" x-show="isEditing" @click="updateBatch" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                                        ✓ {{ langLabel('update_batch') }}
                                    </button>
                                </div>
                            </div>

                            <!-- Hidden Inputs for Submission -->
                            <template x-for="(batch, index) in batches" :key="'form-' + index">
                                <div>
                                    <input type="hidden" :name="`content_sections[${index}][batch_no]`" :value="batch.batchNo">
                                    <input type="hidden" :name="`content_sections[${index}][batch_date]`" :value="batch.batchDate">
                                    <input type="hidden" :name="`content_sections[${index}][start_time]`" :value="batch.startTime">
                                    <input type="hidden" :name="`content_sections[${index}][end_time]`" :value="batch.endTime">
                                    <input type="hidden" :name="`content_sections[${index}][duration]`" :value="batch.duration">
                                    <input type="hidden" :name="`content_sections[${index}][strength]`" :value="batch.strength">
                                    <input type="hidden" :name="`content_sections[${index}][days]`" :value="JSON.stringify(batch.selectedDays)">
                                    <input type="hidden" :name="`content_sections[${index}][end_date]`" :value="batch.endDate">
                                </div>
                            </template>

                            <!-- Batch Table -->
                            <div class="overflow-x-auto mt-6">
                                <h2 class="text-xl font-semibold mb-4">{{ langLabel('batch_list') }}</h2>
                                <table class="min-w-full bg-white border">
                                    <thead class="bg-gray-100 text-sm font-medium text-gray-700">
                                        <tr>
                                            <th class="py-2 px-4 border-b">{{ langLabel('sr_no') }}</th>
                                            <th class="py-2 px-4 border-b">{{ langLabel('batch_no') }}</th>
                                            <th class="py-2 px-4 border-b">{{ langLabel('date') }}</th>
                                            <th class="py-2 px-4 border-b">{{ langLabel('time') }}</th>
                                            <th class="py-2 px-4 border-b">{{ langLabel('duration') }}</th>
                                            <th class="py-2 px-4 border-b">{{ langLabel('days') }}</th>
                                            <th class="py-2 px-4 border-b">{{ langLabel('strength') }}</th>
                                            <th class="py-2 px-4 border-b">{{ langLabel('edit') }}</th>
                                            <th class="py-2 px-4 border-b">{{ langLabel('delete') }}</th>
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

                        <script>
                        function batchManager() {
                            return {
                                batchNo: '', batchDate: '', startTime: '', endTime: '',
                                durationType: 'day', duration: '', strength: '',
                                selectedDays: [],
                                weekDays: ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'],
                                batches: [], isEditing: false, editIndex: null, conflict: false,

                                // Errors
                                batchNoError:'', batchDateError:'', startTimeError:'', endTimeError:'', durationError:'', strengthError:'',

                                initializeBatches(existingBatches) {
                                    if (!existingBatches || !existingBatches.length) return;

                                    this.batches = existingBatches.map(batch => ({
                                        batchNo: batch.batch_no,
                                        batchDate: batch.start_date,
                                        startTime: batch.start_timing,
                                        endTime: batch.end_timing,
                                        duration: batch.duration,
                                        strength: batch.strength,
                                        selectedDays: batch.days || [],
                                        endDate: batch.end_date
                                    }));
                                },

                                getOptions() {
                                    if(this.durationType==='day') return Array.from({length:60}, (_,i)=>`${i+1} day`);
                                    if(this.durationType==='month') return Array.from({length:12}, (_,i)=>`${i+1} month`);
                                    if(this.durationType==='year') return Array.from({length:5}, (_,i)=>`${i+1} year`);
                                    return [];
                                },

                                clearError(field){
                                    if(field==='batchNo') this.batchNoError='';
                                    if(field==='batchDate') this.batchDateError='';
                                    if(field==='startTime') this.startTimeError='';
                                    if(field==='endTime') this.endTimeError='';
                                    if(field==='duration') this.durationError='';
                                    if(field==='strength') this.strengthError='';
                                },

                                addBatch() {
                                    if(!this.validateForm()) return;
                                    if(this.hasConflict()){ this.conflict=true; return; }

                                    this.conflict=false;
                                    this.batches.push(this.getBatchData());
                                    this.resetForm();
                                },

                                updateBatch(){
                                    if(!this.validateForm()) return;
                                    if(this.hasConflict()){ this.conflict=true; return; }

                                    this.conflict=false;
                                    this.batches[this.editIndex]=this.getBatchData();
                                    this.resetForm();
                                },

                                editBatch(index){
                                    const batch=this.batches[index];
                                    this.batchNo=batch.batchNo;
                                    this.batchDate=batch.batchDate;
                                    this.startTime=batch.startTime;
                                    this.endTime=batch.endTime;
                                    this.duration=batch.duration;
                                    this.strength=batch.strength;
                                    this.durationType=this.getDurationTypeFromString(batch.duration);
                                    this.selectedDays=[...batch.selectedDays];
                                    this.editIndex=index;
                                    this.isEditing=true;
                                    this.clearValidationErrors();
                                },

                                removeBatch(index){
                                    this.batches.splice(index,1);
                                    if(this.isEditing && this.editIndex===index) this.resetForm();
                                },

                                hasConflict(){
                                    if(!this.batchDate || !this.duration) return false;
                                    const [value,unit]=this.duration.split(' ');
                                    const durationValue=parseInt(value);
                                    const startDate=new Date(this.batchDate);
                                    const endDate=new Date(startDate);

                                    if(unit.includes('day')) endDate.setDate(startDate.getDate()+durationValue-1);
                                    else if(unit.includes('month')) { endDate.setMonth(startDate.getMonth()+durationValue); endDate.setDate(startDate.getDate()-1); }
                                    else if(unit.includes('year')) { endDate.setFullYear(startDate.getFullYear()+durationValue); endDate.setDate(startDate.getDate()-1); }

                                    return this.batches.some((batch,i)=>{
                                        if(this.isEditing && i===this.editIndex) return false;
                                        const batchStart=new Date(batch.batchDate);
                                        const batchEnd=new Date(batch.endDate);
                                        const dateOverlap=startDate<=batchEnd && endDate>=batchStart;
                                        const timeOverlap=!(this.endTime<=batch.startTime || this.startTime>=batch.endTime);
                                        return dateOverlap && timeOverlap;
                                    });
                                },

                                validateForm(){
                                    let isValid=true;
                                    this.clearValidationErrors();
                                    if(!this.batchNo.trim()){ this.batchNoError='Batch No is required'; isValid=false; }
                                    if(!this.batchDate){ this.batchDateError='Batch Date is required'; isValid=false; }
                                    if(!this.startTime){ this.startTimeError='Start Time is required'; isValid=false; }
                                    if(!this.endTime){ this.endTimeError='End Time is required'; isValid=false; }
                                    else if(this.startTime && this.endTime<=this.startTime){ this.endTimeError='End Time must be after Start Time'; isValid=false; }
                                    if(!this.duration){ this.durationError='Duration is required'; isValid=false; }
                                    if(!this.strength || parseInt(this.strength)<1){ this.strengthError='Valid Strength is required (minimum 1)'; isValid=false; }
                                    return isValid;
                                },

                                clearValidationErrors(){
                                    this.batchNoError=''; this.batchDateError=''; this.startTimeError=''; this.endTimeError='';
                                    this.durationError=''; this.strengthError='';
                                },

                                getBatchData(){
                                    return {
                                        batchNo:this.batchNo,
                                        batchDate:this.batchDate,
                                        startTime:this.startTime,
                                        endTime:this.endTime,
                                        duration:this.duration,
                                        strength:this.strength,
                                        selectedDays:[...this.selectedDays],
                                        endDate:this.calculateEndDate()
                                    };
                                },

                                resetForm(){
                                    this.batchNo=''; this.batchDate=''; this.startTime=''; this.endTime='';
                                    this.duration=''; this.strength=''; this.durationType='day';
                                    this.selectedDays=[]; this.isEditing=false; this.editIndex=null; this.conflict=false;
                                    this.clearValidationErrors();
                                },

                                getDurationTypeFromString(str){
                                    if(!str) return 'day';
                                    if(str.includes('day')) return 'day';
                                    if(str.includes('month')) return 'month';
                                    if(str.includes('year')) return 'year';
                                    return 'day';
                                },

                                calculateEndDate(){
                                    if(!this.batchDate || !this.duration || this.selectedDays.length===0) return '';
                                    const [value,unit]=this.duration.split(' ');
                                    const durationValue=parseInt(value);
                                    const startDate=new Date(this.batchDate);
                                    const endDate=new Date(startDate);

                                    if(unit.includes('day')){
                                        const dayMap={Sunday:0,Monday:1,Tuesday:2,Wednesday:3,Thursday:4,Friday:5,Saturday:6};
                                        const selectedDayIndices=this.selectedDays.map(d=>dayMap[d]);
                                        let daysCount=0;
                                        while(daysCount<durationValue){
                                            if(selectedDayIndices.includes(endDate.getDay())) daysCount++;
                                            if(daysCount<durationValue) endDate.setDate(endDate.getDate()+1);
                                        }
                                    }
                                    else if(unit.includes('month')){ endDate.setMonth(startDate.getMonth()+durationValue); endDate.setDate(startDate.getDate()-1); }
                                    else if(unit.includes('year')){ endDate.setFullYear(startDate.getFullYear()+durationValue); endDate.setDate(startDate.getDate()-1); }

                                    return endDate.toISOString().split('T')[0];
                                }
                            };
                        }
                        </script>

            

                        <!-- Submit Button -->
                        <div class="text-right mt-5">
                            <button type="submit" class="bg-blue-800 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-md font-semibold">
                            {{ langLabel('submit') }}
                            </button>
                        </div>
                    </form>
                </main>


                


            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
            <!-- Include Alpine.js -->
            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
          


            </script>


          



            </div>
        </div>
    </div>
           



          
@include('site.trainer.componants.footer')