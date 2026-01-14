<!-- Subscription Section (with internal tabs) -->
                                <div x-show="activeSection === 'profile'" x-transition>
                                    <!-- Company Header -->
                                    <div class="flex items-center space-x-4 mb-4">
                                        @php
                                            $user = auth()->user();
                                            $userId = $user->id;
                                            $profile = \App\Models\AdditionalInfo::where('user_id', $userId)->where('user_type', 'coach')
                                            ->where('doc_type', 'coach_profile_picture')
                                            ->first();
                                            
                                        @endphp

                                        @if($profile && $profile->document_path)
                                            <img src="{{ $profile->document_path }}" alt="Profile" class="w-20 h-20 rounded-lg object-cover" />
                                        @else
                                            <img src="{{ asset('images/default-profile.png') }}" alt="Default" class="w-20 h-20 rounded-lg object-cover" />
                                        @endif

                                        <div>
                                            <h3 class="text-xl font-semibold">{{$user->name}}</h3>
                                            <p class="text-gray-600">{{$user->email}}</p>
                                            <p class="text-gray-600">{{$user->phone_number}}</p>
                                        </div>
                                    </div>
                                    <div x-data="{ activeSubTab: 'personal' }">
                                        <!-- Inner Tabs -->
                                        <div class="border-b mb-4">
                                            <nav class="flex space-x-6">
                                                <button
                                                    @click="activeSubTab = 'personal'"
                                                    :class="activeSubTab === 'personal' ? 'pb-2 border-b-2 border-blue-600 font-medium' : 'pb-2 text-gray-500 hover:text-black'"
                                                    class="focus:outline-none"
                                                >
                                                    Personal Information
                                                </button>
                                                <button
                                                    @click="activeSubTab = 'education'"
                                                    :class="activeSubTab === 'education' ? 'pb-2 border-b-2 border-blue-600 font-medium' : 'pb-2 text-gray-500 hover:text-black'"
                                                    class="focus:outline-none"
                                                >
                                                    Educational Details
                                                </button>
                                                <button
                                                    @click="activeSubTab = 'work'"
                                                    :class="activeSubTab === 'work' ? 'pb-2 border-b-2 border-blue-600 font-medium' : 'pb-2 text-gray-500 hover:text-black'"
                                                    class="focus:outline-none"
                                                >
                                                    Work Experience
                                                </button>
                                                <button
                                                    @click="activeSubTab = 'skills'"
                                                    :class="activeSubTab === 'skills' ? 'pb-2 border-b-2 border-blue-600 font-medium' : 'pb-2 text-gray-500 hover:text-black'"
                                                    class="focus:outline-none"
                                                >
                                                    Skills & Training
                                                </button>
                                                <button
                                                    @click="activeSubTab = 'additional'"
                                                    :class="activeSubTab === 'additional' ? 'pb-2 border-b-2 border-blue-600 font-medium' : 'pb-2 text-gray-500 hover:text-black'"
                                                    class="focus:outline-none"
                                                >
                                                    Additional Information
                                                </button>
                                            </nav>

                                        </div>

                                        <!-- Inner Tab Contents -->
                                        <div >
                                        <!-- Personal Information Tab -->
                                            <div x-show="activeSubTab === 'personal'" x-transition>
                                                <!-- Success Message -->
                                                <div id="coach-info-success" class="alert alert-success text-center" style="display: none;">
                                                    <strong>Success!</strong> <span class="message-text"></span>
                                                </div>

                                                <!-- coach Info Form -->
                                                <form id="coach-info-form" action="{{ route('coach.profile.update') }}" method="POST">
                                                    @csrf
                                                    
                                                    <!-- Name -->
                                                    <div>
                                                        <label class="block mb-1 font-medium">Full Name</label>
                                                        <input type="text" name="name" value="{{ $coach->name }}" class="w-full border rounded px-3 py-2" />
                                                    </div>

                                                    <div class="grid grid-cols-2 gap-6 mt-3">
                                                        <!-- Email -->
                                                        <div>
                                                            <label class="block mb-1 font-medium">Email</label>
                                                            <input type="email" name="email" value="{{ $coach->email }}" class="w-full border rounded px-3 py-2" />
                                                        </div>
                                                        <div>
                                                            <label class="block mb-1 text-sm font-medium mt-3">Gender <span style="color: red; font-size: 17px;">*</span></label>
                                                            <select name="gender" id="gender" class="w-full border rounded-md p-2 mt-1">
                                                                <option value="">Select Gender</option>
                                                                <option value="Male" {{ old('gender', $coach->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                                                <option value="Female" {{ old('gender', $coach->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                                                            </select>
                                                            @error('gender')
                                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                    </div>

                                                    <div class="grid grid-cols-2 gap-6 mt-3">
                                                        <!-- Phone (FIXED) -->
                                                        <div>
                                                            <label class="block mb-1 font-medium">Phone Number</label>
                                                            <input type="text" name="phone" value="{{ $coach->phone_number }}" class="w-full border rounded px-3 py-2" />
                                                        </div>

                                                        <!-- Date of Birth -->
                                                        <div>
                                                            <label class="block mb-1 font-medium">Date of Birth</label>
                                                            <input 
                                                                type="date" 
                                                                name="dob" 
                                                                value="{{ old('dob', \Carbon\Carbon::parse($coach->date_of_birth)->format('Y-m-d')) }}" 
                                                                class="w-full border rounded px-3 py-2" 
                                                            />

                                                        </div>
                                                    </div>

                                                    <!-- National ID -->
                                                    <div class="mt-3">
                                                        <label class="block font-medium mb-1">National ID Number</label>
                                                        <input type="text" name="national_id" maxlength="15"
                                                            value="{{ $coach->national_id }}"
                                                            class="w-full border rounded px-3 py-2"
                                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);" />
                                                    </div>
                                                    
                                                    <!-- Address -->
                                                    <div class="mt-3">
                                                        <label class="block font-medium mb-1">Address</label>
                                                        <input type="text" name="address" value="{{ $coach->address }}" class="w-full border rounded px-3 py-2" />
                                                        @error('address')
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <!-- City -->
                                                    <div class="mt-3">
                                                        <label class="block font-medium mb-1">City</label>
                                                        <input type="text" name="city" value="{{ $coach->city }}" class="w-full border rounded px-3 py-2" />
                                                        @error('city')
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <!-- State -->
                                                    <div class="mt-3">
                                                        <label class="block font-medium mb-1">State</label>
                                                        <input type="text" name="state" value="{{ $coach->state }}" class="w-full border rounded px-3 py-2" />
                                                        @error('state')
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <!-- Country -->
                                                    <div class="mt-3">
                                                        <label class="block font-medium mb-1">Country</label>
                                                        <input type="text" name="country" value="{{ $coach->country }}" class="w-full border rounded px-3 py-2" />
                                                        @error('country')
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <!-- Pin code -->
                                                    <div class="mt-3">
                                                        <label class="block font-medium mb-1">Pin code</label>
                                                        <input type="text" name="pin_code" value="{{ $coach->pin_code }}" class="w-full border rounded px-3 py-2" />
                                                        @error('pin_code')
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- About Coach -->
                                                    <div class="mt-3">
                                                        <label class="block font-medium mb-1">About coach</label>
                                                        <textarea name="about_coach" class="w-full border rounded px-3 py-2 h-24">{{ $coach->about_coach }}</textarea>
                                                    </div>
                                                    <!-- <h3 class="text-lg font-semibold mt-5">Session Price</h3> -->
                                                    <h3 class="text-2xl font-bold mt-5">Session Price</h3>

                                                        <div class="grid grid-cols-2 gap-6 mt-3">
                                                            <!-- Price Per Session -->
                                                            <div>
                                                                <label class="block mb-1 font-medium">Price Per Session</label>
                                                                <input type="text" name="per_slot_price" value="{{ $coach->per_slot_price }}" class="w-full border rounded px-3 py-2" />
                                                            </div>
                                                        </div>
                                                    <!-- Buttons -->
                                                    <div class="mt-6 flex justify-end gap-4">
                                                        <button @click.prevent="activeSubTab = 'education'" class="border px-6 py-2 rounded hover:bg-gray-100">Next</button>
                                                        <button type="button" id="save-coach-info" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save</button>
                                                    </div>
                                                </form>


                                            </div>

                                            <!-- AJAX Submission Script -->
                                            <script>
                                            document.getElementById('save-coach-info').addEventListener('click', function () {
                                                const form = document.getElementById('coach-info-form');
                                                const formData = new FormData(form);
                                                const successBox = document.getElementById('coach-info-success');
                                                const successText = successBox.querySelector('.message-text');

                                                fetch(form.action, {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                                                        'Accept': 'application/json'
                                                    },
                                                    body: formData
                                                })
                                                .then(response => {
                                                    if (!response.ok) return response.json().then(err => Promise.reject(err));
                                                    return response.json();
                                                })
                                                .then(data => {
                                                    successText.textContent = data.message;
                                                    successBox.style.display = 'block';
                                                    setTimeout(() => {
                                                        successBox.style.display = 'none';
                                                    }, 3000);
                                                    activeSubTab = 'education';
                                                })
                                                .catch(error => {
                                                    const errors = error.errors || {};
                                                    Object.keys(errors).forEach(field => {
                                                        const input = form.querySelector(`[name="${field}"]`);
                                                        if (input) {
                                                            const errorElem = document.createElement('p');
                                                            errorElem.className = 'text-red-600 text-sm mt-1';
                                                            errorElem.textContent = errors[field][0];
                                                            input.insertAdjacentElement('afterend', errorElem);
                                                        }
                                                    });
                                                });
                                            });
                                            </script>

                                        

                                            <!-- Educational Details Tab -->
                                            <div x-show="activeSubTab === 'education'" x-transition>
                                                <!-- Success Message -->
                                                <div id="education-success" class="alert alert-success text-center" style="display: none;">
                                                    <strong>Success!</strong> <span class="message-text"></span>
                                                </div>

                                                <!-- Education Form -->
                                                <form id="education-info-form" method="POST" action="{{ route('coach.education.update') }}" class="space-y-6">
                                                    @csrf
                                                    <div id="education-container" class="space-y-6">
                                                        @foreach($educationDetails as $education)
                                                        <div class="education-entry border border-gray-300 rounded-md p-4 space-y-4 relative">
                                                            <input type="hidden" name="education_id[]" value="{{ $education->id }}">

                                                            <div class="grid grid-cols-2 gap-4">
                                                                <!-- Qualification -->
                                                                <div>
                                                                    <label class="block mb-1 font-medium">Highest Qualification</label>
                                                                    <input type="text" name="high_education[]" 
                                                                        value="{{ ucfirst(str_replace('_', ' ', $education->high_education)) }}" 
                                                                        class="w-full border rounded px-3 py-2" 
                                                                        placeholder="e.g., Bachelor's Degree" />
                                                                </div>

                                                                <!-- Field -->
                                                                <div>
                                                                    <label class="block mb-1 font-medium">Field of Study</label>
                                                                    <input type="text" name="field_of_study[]" 
                                                                        value="{{ ucfirst($education->field_of_study) }}" 
                                                                        class="w-full border rounded px-3 py-2" 
                                                                        placeholder="e.g., Computer Science" />
                                                                </div>
                                                            </div>

                                                            <div class="grid grid-cols-2 gap-4">
                                                                <!-- Institution -->
                                                                <div>
                                                                    <label class="block mb-1 font-medium">Institution Name</label>
                                                                    <input type="text" name="institution[]" 
                                                                        value="{{ $education->institution }}" 
                                                                        class="w-full border rounded px-3 py-2" 
                                                                        placeholder="e.g., ABC University" />
                                                                </div>

                                                                <!-- Year -->
                                                                <div>
                                                                    <label class="block mb-1 font-medium">Graduation Year</label>
                                                                    <input type="number" name="graduate_year[]" 
                                                                        value="{{ $education->graduate_year }}" 
                                                                        class="w-full border rounded px-3 py-2" 
                                                                        placeholder="e.g., 2023" 
                                                                        min="1900" max="2099" 
                                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                                                </div>
                                                            </div>

                                                            <!-- Remove Button -->
                                                            <button type="button" class="remove-education absolute top-2 right-2 text-red-600 font-bold text-lg" style="{{ $loop->first ? 'display:none;' : '' }}">&times;</button>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </form>

                                                <!-- Add More Button -->
                                                <div class="col-span-2 mt-2">
                                                    <button type="button" id="add-education" class="text-green-600 text-sm">+ Add education</button>
                                                </div>

                                                <!-- Submit Buttons -->
                                                <div class="mt-6 flex justify-end space-x-3">
                                                    <button @click.prevent="activeSubTab = 'work'" class="border px-6 py-2 rounded hover:bg-gray-100">Next</button>
                                                    <button type="button" id="save-education-info" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save</button>
                                                </div>
                                            </div>

                                            <!-- AJAX & Dynamic Handling -->
                                            <!-- JavaScript for AJAX and Dynamic Entries -->
                                            <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                const educationContainer = document.getElementById('education-container');
                                                const addEducationBtn = document.getElementById('add-education');
                                                const saveBtn = document.getElementById('save-education-info');
                                                const form = document.getElementById('education-info-form');
                                                const successBox = document.getElementById('education-success');
                                                const successText = successBox.querySelector('.message-text');

                                                // Add More Education Entry
                                                addEducationBtn.addEventListener('click', () => {
                                                    const firstEntry = educationContainer.querySelector('.education-entry');
                                                    const clone = firstEntry.cloneNode(true);

                                                    // Remove hidden ID
                                                    const hiddenInput = clone.querySelector('input[name="education_id[]"]');
                                                    if (hiddenInput) hiddenInput.remove();

                                                    // Clear all input fields
                                                    clone.querySelectorAll('input').forEach(input => input.value = '');

                                                    // Remove errors
                                                    clone.querySelectorAll('p.text-red-600').forEach(p => p.remove());

                                                    // Show remove button
                                                    const removeBtn = clone.querySelector('.remove-education');
                                                    if (removeBtn) removeBtn.style.display = 'block';

                                                    educationContainer.appendChild(clone);
                                                });

                                                // Remove Entry
                                                educationContainer.addEventListener('click', function (e) {
                                                    if (e.target.classList.contains('remove-education')) {
                                                        const entry = e.target.closest('.education-entry');
                                                        const entries = educationContainer.querySelectorAll('.education-entry');
                                                        if (entries.length > 1) entry.remove();
                                                    }
                                                });

                                                // Save via AJAX
                                                saveBtn.addEventListener('click', function () {
                                                    const formData = new FormData(form);

                                                    // Remove errors
                                                    form.querySelectorAll('p.text-red-600').forEach(e => e.remove());

                                                    fetch(form.action, {
                                                        method: 'POST',
                                                        headers: {
                                                            'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                                                            'Accept': 'application/json'
                                                        },
                                                        body: formData
                                                    })
                                                    .then(response => {
                                                        if (!response.ok) return response.json().then(err => Promise.reject(err));
                                                        return response.json();
                                                    })
                                                    .then(data => {
                                                        successText.textContent = data.message || 'Saved successfully!';
                                                        successBox.style.display = 'block';

                                                        setTimeout(() => {
                                                            successBox.style.display = 'none';
                                                            successText.textContent = '';
                                                        }, 3000);

                                                        activeSubTab = 'work';
                                                    })
                                                    .catch(error => {
                                                        const errors = error.errors || {};
                                                        Object.keys(errors).forEach(fieldName => {
                                                            const match = fieldName.match(/(\w+)\.(\d+)/);
                                                            if (match) {
                                                                const baseField = match[1];
                                                                const index = parseInt(match[2]);
                                                                const inputList = form.querySelectorAll(`[name="${baseField}[]"]`);
                                                                const input = inputList[index];
                                                                if (input) {
                                                                    const errorElem = document.createElement('p');
                                                                    errorElem.className = 'text-red-600 text-sm mt-1';
                                                                    errorElem.textContent = errors[fieldName][0];
                                                                    input.insertAdjacentElement('afterend', errorElem);
                                                                }
                                                            }
                                                        });
                                                    });
                                                });
                                            });
                                            </script>
                                            
                                            <!-- Work Experience Tab -->
                                            <div x-show="activeSubTab === 'work'" x-transition>
                                                <!-- Success Message -->
                                                <div id="work-success" class="col-12 ml-auto mr-auto text-center alert alert-success alert-dismissible fade show" style="display: none;">
                                                    <strong>Success!</strong> <span class="message-text"></span>
                                                </div>

                                                <form id="work-info-form" method="POST" action="{{ route('coach.workexprience.update') }}" class="space-y-6">
                                                    @csrf

                                                    <div id="work-container" class="space-y-6">
                                                        @foreach($workExperiences as $work)
                                                        <div class="work-entry border border-gray-300 rounded-md p-4 space-y-4 relative">
                                                            <input type="hidden" name="work_id[]" value="{{ $work->id }}">

                                                            <div class="grid grid-cols-2 gap-4">
                                                                <div>
                                                                    <label class="block mb-1 font-medium">Job Role</label>
                                                                    <input type="text" name="job_role[]" value="{{ $work->job_role }}" class="w-full border rounded px-3 py-2" placeholder="e.g., Software Engineer" />
                                                                </div>
                                                                <div>
                                                                    <label class="block mb-1 font-medium">Organization</label>
                                                                    <input type="text" name="organization[]" value="{{ $work->organization }}" class="w-full border rounded px-3 py-2" placeholder="e.g., XYZ Corp" />
                                                                </div>
                                                            </div>

                                                            <div class="grid grid-cols-2 gap-4">
                                                                <div>
                                                                    <label class="block mb-1 font-medium">Started From</label>
                                                                    <input type="date" name="starts_from[]" value="{{ \Carbon\Carbon::parse($work->starts_from)->format('Y-m-d') }}" class="w-full border rounded px-3 py-2" />
                                                                </div>
                                                                <div>
                                                                    <label class="block mb-1 font-medium">To</label>
                                                                    <input 
                                                                        type="date" 
                                                                        name="end_to[]" 
                                                                        value="{{ !is_null($work->end_to) && $work->end_to !== 'Work here' ? \Carbon\Carbon::parse($work->end_to)->format('Y-m-d') : '' }}" 
                                                                        class="w-full border rounded px-3 py-2" 
                                                                        {{ is_null($work->end_to) || $work->end_to === 'Work here' ? 'disabled' : '' }} 
                                                                    />

                                                                    <label class="inline-flex items-center mt-2">
                                                                        <input 
                                                                            type="checkbox" 
                                                                            name="currently_working[]" 
                                                                            onchange="toggleEndDate(this)" 
                                                                            {{ is_null($work->end_to) || $work->end_to === 'Work here' ? 'checked' : '' }} 
                                                                        />
                                                                        <span class="ml-2">I currently work here</span>
                                                                    </label>
                                                                </div>
                                                            </div>

                                                            <button type="button" class="remove-work absolute top-2 right-2 text-red-600 font-bold text-lg" style="{{ $loop->first ? 'display:none;' : '' }}">&times;</button>
                                                        </div>
                                                        @endforeach
                                                    </div>

                                                    <div class="text-left">
                                                        <button type="button" id="add-work" class="text-green-600 text-sm">+ Add Experience</button>
                                                    </div>

                                                    <div class="mt-6 flex justify-end space-x-3">
                                                        <button @click.prevent="activeSubTab = 'skills'" class="border px-6 py-2 rounded hover:bg-gray-100">Next</button>
                                                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    const workContainer = document.getElementById('work-container');
                                                    const addWorkBtn = document.getElementById('add-work');
                                                    const workForm = document.getElementById('work-info-form');
                                                    const successBox = document.getElementById('work-success');
                                                    const successText = successBox.querySelector('.message-text');

                                                    // Add Work Experience Entry
                                                    addWorkBtn.addEventListener('click', () => {
                                                        const firstEntry = workContainer.querySelector('.work-entry');
                                                        const clone = firstEntry.cloneNode(true);

                                                        // Remove hidden input
                                                        const hiddenInput = clone.querySelector('input[name="work_id[]"]');
                                                        if (hiddenInput) hiddenInput.remove();

                                                        // Clear all inputs
                                                        clone.querySelectorAll('input').forEach(input => {
                                                            if (input.type === 'text' || input.type === 'date') {
                                                                input.value = '';
                                                            }
                                                            if (input.type === 'checkbox') {
                                                                input.checked = false;
                                                            }
                                                        });

                                                        // Enable end date input
                                                        const endDateInput = clone.querySelector('input[name="end_to[]"]');
                                                        if (endDateInput) endDateInput.disabled = false;

                                                        // Remove error messages
                                                        clone.querySelectorAll('p.text-red-600').forEach(p => p.remove());

                                                        // Show remove button
                                                        const removeBtn = clone.querySelector('.remove-work');
                                                        if (removeBtn) removeBtn.style.display = 'block';

                                                        workContainer.appendChild(clone);
                                                    });

                                                    // Remove Work Experience Entry
                                                    workContainer.addEventListener('click', function (e) {
                                                        if (e.target.classList.contains('remove-work')) {
                                                            const entry = e.target.closest('.work-entry');
                                                            const entries = workContainer.querySelectorAll('.work-entry');
                                                            if (entries.length > 1) {
                                                                entry.remove();
                                                            }
                                                        }
                                                    });

                                                    // Toggle End Date when checkbox clicked
                                                    window.toggleEndDate = function (checkbox) {
                                                        const container = checkbox.closest('div');
                                                        const endDateInput = container.querySelector('input[name="end_to[]"]');
                                                        if (checkbox.checked) {
                                                            endDateInput.value = '';
                                                            endDateInput.disabled = true;
                                                        } else {
                                                            endDateInput.disabled = false;
                                                        }
                                                    };

                                                    // AJAX Form Submission
                                                    workForm.addEventListener('submit', function (e) {
                                                        e.preventDefault();
                                                        const formData = new FormData(workForm);

                                                        // Clear previous errors
                                                        workForm.querySelectorAll('p.text-red-600').forEach(e => e.remove());

                                                        fetch(workForm.action, {
                                                            method: 'POST',
                                                            headers: {
                                                                'X-CSRF-TOKEN': workForm.querySelector('[name="_token"]').value,
                                                                'Accept': 'application/json'
                                                            },
                                                            body: formData
                                                        })
                                                        .then(response => {
                                                            if (!response.ok) return response.json().then(err => Promise.reject(err));
                                                            return response.json();
                                                        })
                                                        .then(data => {
                                                            successText.textContent = data.message || 'Work experience saved successfully!';
                                                            successBox.style.display = 'block';

                                                            setTimeout(() => {
                                                                successBox.style.display = 'none';
                                                                successText.textContent = '';
                                                            }, 3000);
                                                        })
                                                        .catch(error => {
                                                            const errors = error.errors || {};

                                                            Object.keys(errors).forEach(field => {
                                                                const match = field.match(/(\w+)\.(\d+)/);
                                                                if (match) {
                                                                    const baseField = match[1];
                                                                    const index = parseInt(match[2]);
                                                                    const inputList = workForm.querySelectorAll(`[name="${baseField}[]"]`);
                                                                    const input = inputList[index];

                                                                    if (input) {
                                                                        const errorElem = document.createElement('p');
                                                                        errorElem.className = 'text-red-600 text-sm mt-1';
                                                                        errorElem.textContent = errors[field][0];
                                                                        input.insertAdjacentElement('afterend', errorElem);
                                                                    }
                                                                }
                                                            });
                                                        });
                                                    });
                                                });
                                            </script>

        




                                            
                                            <!-- Skills & Training Tab -->
                                            <div x-show="activeSubTab === 'skills'" x-transition x-cloak>
                                                <!-- Skills Success Message -->
                                                <div id="trainer-skills-success" class="col-12 ml-auto mr-auto text-center alert alert-success alert-dismissible fade show" style="display: none;">
                                                    <strong>Success!</strong> <span class="message-text"></span>
                                                </div>

                                                <!-- Skills & Training Form -->
                                                <form id="trainer-skills-info-form" method="POST" action="{{ route('coach.skills.update') }}">
                                                    @csrf
                                                    <div class="space-y-4">
                                                        <!-- Skills -->
                                                        <div>
                                                            <label class="block text-sm font-medium mb-1">Skills</label>
                                                            <input type="text" name="training_skills" placeholder="E.g., JavaScript, UI/UX, Communication"
                                                                class="w-full border rounded px-3 py-2"
                                                                value="{{ old('training_skills', $coachDetails->training_skills ?? '') }}" />
                                                            @error('training_skills')
                                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- Area Of Interest (Dropdown) -->
                                                        <div>
                                                            <label class="block mb-1 text-sm font-medium">Area Of Interest</label>
                                                            <select name="area_of_interest" class="w-full border rounded-md p-2">
                                                                <option value="">-- Select Area of Interest --</option>
                                                                @foreach($categories as $category)
                                                                    <option value="{{ $category->category }}"
                                                                        {{ old('area_of_interest', $coachDetails->area_of_interest ?? '') == $category->category ? 'selected' : '' }}>
                                                                        {{ $category->category }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('area_of_interest')
                                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- Job Category (Text Input) -->
                                                        <div>
                                                            <label class="block mb-1 text-sm font-medium">Job Category</label>
                                                            <input type="text" name="job_category" class="w-full border rounded-md p-2"
                                                                placeholder="e.g. Communication, Leadership, Python, Cloud Computing"
                                                                value="{{ old('job_category', $coachDetails->job_category ?? '') }}" />
                                                            @error('job_category')
                                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- Website Link -->
                                                        <div>
                                                            <label class="block text-sm font-medium mb-1">Website Link</label>
                                                            <input type="url" name="website_link" placeholder="https://example.com"
                                                                class="w-full border rounded px-3 py-2"
                                                                value="{{ old('website_link', $coachDetails->website_link ?? '') }}" />
                                                            @error('website_link')
                                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- Portfolio Link -->
                                                        <div>
                                                            <label class="block text-sm font-medium mb-1">Portfolio Link</label>
                                                            <input type="url" name="portfolio_link" placeholder="https://portfolio.com"
                                                                class="w-full border rounded px-3 py-2"
                                                                value="{{ old('portfolio_link', $coachDetails->portfolio_link ?? '') }}" />
                                                            @error('portfolio_link')
                                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                            @enderror
                                                        </div>

                                                        <!-- Buttons -->
                                                        <div class="flex justify-end gap-4 mt-6">
                                                            <button type="button" class="border rounded px-6 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                                @click="nextTab">
                                                                Next
                                                            </button>
                                                            <button type="submit" id="save-trainer-skills-info"
                                                                class="bg-blue-700 text-white px-6 py-2 rounded text-sm hover:bg-blue-800">Save</button>
                                                        </div>
                                                    </div>
                                                </form>


                                            </div>

                                            <script>
                                                document.getElementById('save-trainer-skills-info').addEventListener('click', function (e) {
                                                e.preventDefault();
                                                    const form = document.getElementById('trainer-skills-info-form');
                                                    const formData = new FormData(form);
                                                    const successBox = document.getElementById('trainer-skills-success');
                                                    const successText = successBox.querySelector('.message-text');

                                                    // Clear previous error messages
                                                    form.querySelectorAll('.text-red-600').forEach(el => el.remove());

                                                    fetch(form.action, {
                                                        method: 'POST',
                                                        headers: {
                                                            'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                                                            'Accept': 'application/json'
                                                        },
                                                        body: formData
                                                    })
                                                    .then(response => {
                                                        if (!response.ok) return response.json().then(err => Promise.reject(err));
                                                        return response.json();
                                                    })
                                                    .then(data => {
                                                        successText.textContent = data.message;
                                                        successBox.style.display = 'block';
                                                        setTimeout(() => {
                                                            successBox.style.display = 'none';
                                                            successText.textContent = '';
                                                        }, 3000);

                                                        if (typeof nextTab === "function") nextTab();
                                                    })
                                                    .catch(error => {
                                                        const errors = error.errors || {};
                                                        Object.keys(errors).forEach(field => {
                                                            const input = form.querySelector(`[name="${field}"]`);
                                                            if (input) {
                                                                const errorElem = document.createElement('p');
                                                                errorElem.className = 'text-red-600 text-sm mt-1';
                                                                errorElem.textContent = errors[field][0];
                                                                input.insertAdjacentElement('afterend', errorElem);
                                                            }
                                                        });
                                                    });
                                                });
                                            </script>



                                            <!-- Additional Information Tab -->
                                            <div x-show="activeSubTab === 'additional'" x-transition>
                                                <h3 class="text-lg font-semibold mb-4">Upload Documents</h3>
                                                @php
                                                    $userId = auth()->id();

                                                    $resume = \App\Models\AdditionalInfo::where([
                                                        'user_id' => $userId,
                                                        'user_type' => 'coach',
                                                        'doc_type' => 'coach_resume'
                                                    ])->first();

                                                    $profile = \App\Models\AdditionalInfo::where([
                                                        'user_id' => $userId,
                                                        'user_type' => 'coach',
                                                        'doc_type' => 'coach_profile_picture'
                                                    ])->first();

                                                    $certificate = \App\Models\AdditionalInfo::where([
                                                        'user_id' => $userId,
                                                        'user_type' => 'coach',
                                                        'doc_type' => 'coach_training_certificate'
                                                    ])->first();
                                                @endphp


                                                <!-- Success Message -->
                                                <div id="trainer-additional-success" class="alert alert-success text-center" style="display: none;">
                                                    <strong>Success!</strong> <span class="message-text"></span>
                                                </div>

                                                <!-- Trainer Additional Info Form -->
                                                <form id="trainer-additional-info-form" method="POST" action="{{ route('coach.additional.update') }}" enctype="multipart/form-data">
                                                    @csrf
                                                    <div x-show="activeSubTab === 'additional'" x-cloak class="space-y-4 text-sm">

                                                        <!-- Resume -->
                                                        <div>
                                                            <label class="font-medium mb-1 block">Upload Resume</label>
                                                            <div class="flex flex-col gap-2">
                                                                @if($resume)
                                                                    <div class="flex items-center gap-4">
                                                                        <a href="{{ asset($resume->document_path) }}" target="_blank" class="bg-blue-600 text-white px-3 py-1.5 text-xs rounded hover:bg-blue-700">📄 View Resume</a>
                                                                        <button type="button" class="delete-file text-red-600 text-sm" data-type="resume">Delete</button>
                                                                    </div>
                                                                @endif
                                                                <input type="file" name="resume" accept=".pdf,.doc,.docx,.txt" class="border rounded p-2 w-full" />
                                                            </div>
                                                        </div>

                                                        <!-- Profile Picture -->
                                                        <div>
                                                            <label class="font-medium mb-1 block">Upload Profile Picture</label>
                                                            <div class="flex flex-col gap-2">
                                                                @if($profile)
                                                                    <div class="flex items-center gap-4">
                                                                        <a href="{{ asset($profile->document_path) }}" target="_blank" class="bg-green-600 text-white px-3 py-1.5 text-xs rounded hover:bg-green-700">📄 View Profile</a>
                                                                        <button type="button" class="delete-file text-red-600 text-sm" data-type="profile">Delete</button>
                                                                    </div>
                                                                @endif
                                                                <input type="file" name="profile" accept="image/*" class="border rounded p-2 w-full" />
                                                            </div>
                                                        </div>

                                                        <!-- Training Certificate -->
                                                        <div>
                                                            <label class="font-medium mb-1 block">Upload Training Certificate</label>
                                                            <div class="flex flex-col gap-2">
                                                                @if($certificate)
                                                                    <div class="flex items-center gap-4">
                                                                        <a href="{{ asset($certificate->document_path) }}" target="_blank" class="bg-purple-600 text-white px-3 py-1.5 text-xs rounded hover:bg-purple-700">📄 View Certificate</a>
                                                                        <button type="button" class="delete-file text-red-600 text-sm" data-type="training_certificate">Delete</button>
                                                                    </div>
                                                                @endif
                                                                <input type="file" name="training_certificate" accept=".pdf,.jpg,.jpeg,.png" class="border rounded p-2 w-full" />
                                                            </div>
                                                        </div>
                                                        <!-- Submit Button -->
                                                        <div class="flex justify-end mt-6">
                                                            <button type="button" id="save-trainer-additional-info" class="bg-blue-700 text-white px-6 py-2 rounded hover:bg-blue-800">Save</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- Delete Confirmation Modal -->
                                            <div id="trainerDeleteConfirmModal" class="fixed inset-0 bg-gray-100 bg-opacity-90 flex items-center justify-center z-50 hidden">
                                                <div class="bg-white rounded p-6 w-full max-w-sm shadow-lg">
                                                    <h2 class="text-lg font-semibold mb-4">Confirm Delete</h2>
                                                    <p class="text-gray-700 mb-6">Are you sure you want to delete <span id="trainer-delete-file-type" class="font-semibold"></span>?</p>
                                                    <div class="flex justify-end gap-4">
                                                        <button type="button" id="trainerCancelDeleteBtn" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">Cancel</button>
                                                        <button type="button" id="trainerConfirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded">Yes, Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Script -->
                                            <script>
                                                // Save AJAX
                                                document.getElementById('save-trainer-additional-info').addEventListener('click', function () {
                                                    const form = document.getElementById('trainer-additional-info-form');
                                                    const formData = new FormData(form);
                                                    const successBox = document.getElementById('trainer-additional-success');
                                                    const successText = successBox.querySelector('.message-text');

                                                    form.querySelectorAll('.text-red-600').forEach(e => e.remove());

                                                    fetch(form.action, {
                                                        method: 'POST',
                                                        headers: {
                                                            'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                                                            'Accept': 'application/json'
                                                        },
                                                        body: formData
                                                    })
                                                    .then(response => {
                                                        if (!response.ok) return response.json().then(err => Promise.reject(err));
                                                        return response.json();
                                                    })
                                                    .then(data => {
                                                        successText.textContent = data.message;
                                                        successBox.style.display = 'block';
                                                        setTimeout(() => {
                                                            successBox.style.display = 'none';
                                                            successText.textContent = '';
                                                            location.reload(); // 🔄 Reload after success
                                                        }, 3000);
                                                    })
                                                    .catch(error => {
                                                        const errors = error.errors || {};
                                                        Object.keys(errors).forEach(field => {
                                                            const input = form.querySelector(`[name="${field}"]`);
                                                            if (input) {
                                                                const errorElem = document.createElement('p');
                                                                errorElem.className = 'text-red-600 text-sm mt-1';
                                                                errorElem.textContent = errors[field][0];
                                                                input.insertAdjacentElement('afterend', errorElem);
                                                            }
                                                        });
                                                    });
                                                });

                                                // Delete logic
                                                let trainerSelectedFileType = null;

                                                document.querySelectorAll('.delete-file').forEach(btn => {
                                                    btn.addEventListener('click', function () {
                                                        trainerSelectedFileType = this.dataset.type;
                                                        document.getElementById('trainer-delete-file-type').textContent = trainerSelectedFileType.replace('_', ' ');
                                                        document.getElementById('trainerDeleteConfirmModal').classList.remove('hidden');
                                                    });
                                                });

                                                document.getElementById('trainerCancelDeleteBtn').addEventListener('click', function () {
                                                    document.getElementById('trainerDeleteConfirmModal').classList.add('hidden');
                                                    trainerSelectedFileType = null;
                                                });

                                                document.getElementById('trainerConfirmDeleteBtn').addEventListener('click', function () {
                                                    if (!trainerSelectedFileType) return;

                                                    fetch(`{{ route('coach.additional.delete', ':type') }}`.replace(':type', trainerSelectedFileType), {
                                                        method: 'DELETE',
                                                        headers: {
                                                            'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                                                            'Accept': 'application/json'
                                                        }
                                                    })
                                                    .then(res => res.json())
                                                    .then(data => {
                                                        document.getElementById('trainerDeleteConfirmModal').classList.add('hidden');
                                                        trainerSelectedFileType = null;

                                                        const successBox = document.getElementById('trainer-additional-success');
                                                        const successText = successBox.querySelector('.message-text');
                                                        successText.textContent = data.message;
                                                        successBox.style.display = 'block';

                                                        setTimeout(() => {
                                                            successBox.style.display = 'none';
                                                            successText.textContent = '';
                                                            location.reload(); // 🔄 Reload after delete
                                                        }, 3000);
                                                    })
                                                    .catch(() => {
                                                        alert('Delete failed.');
                                                        document.getElementById('trainerDeleteConfirmModal').classList.add('hidden');
                                                    });
                                                });
                                            </script>


                                        </div>
                                    </div>
                                </div>