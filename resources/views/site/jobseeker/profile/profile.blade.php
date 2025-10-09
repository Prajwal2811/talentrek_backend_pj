<div x-show="tab === 'profile'" x-cloak x-data="{
                                profileTab: 'personal',
                                tabs: ['personal', 'education', 'work', 'skills', 'additional'],
                                nextTab() {
                                    const currentIndex = this.tabs.indexOf(this.profileTab);
                                    if (currentIndex < this.tabs.length - 1) {
                                        this.profileTab = this.tabs[currentIndex + 1];
                                    }
                                }
                            }">
    <h2 class="text-xl font-semibold mb-4">{{ langLabel('my_profile') }}</h2>
    @if(session('success'))
        <span id="successMessage"
            class="inline-flex items-center bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 gap-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round" />
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


    <div class="border-b flex space-x-6 text-sm font-medium">
        <button @click="profileTab = 'personal'"
            :class="profileTab === 'personal' ? 'border-b-2 border-black text-black' : 'text-gray-600'"
            class="pb-2">{{ langLabel('personal_information') }}</button>
        <button @click="profileTab = 'education'"
            :class="profileTab === 'education' ? 'border-b-2 border-black text-black' : 'text-gray-600'"
            class="pb-2">{{ langLabel('educational_details') }}</button>
        <button @click="profileTab = 'work'"
            :class="profileTab === 'work' ? 'border-b-2 border-black text-black' : 'text-gray-600'"
            class="pb-2">{{ langLabel('work_experience') }}</button>
        <button @click="profileTab = 'skills'"
            :class="profileTab === 'skills' ? 'border-b-2 border-black text-black' : 'text-gray-600'"
            class="pb-2">{{ langLabel('skills_and_training') }}</button>
        <button @click="profileTab = 'additional'"
            :class="profileTab === 'additional' ? 'border-b-2 border-black text-black' : 'text-gray-600'"
            class="pb-2">{{ langLabel('additional_information') }}</button>
    </div>
    <div class="space-y-4 mt-4">
        <input type="hidden" name="id" value="{{ $user->id }}">
        <div id="personal-info-success"
            class="col-12 ml-auto mr-auto text-center alert alert-success alert-dismissible fade show"
            style="display: none;">
            <strong>{{ langLabel('success') }}</strong> <span class="message-text"></span>
        </div>
        <!-- Personal Info -->
        <form id="personal-info-form" action="{{ route('jobseeker.profile.update') }}" method="POST">
            @csrf

            <div x-show="profileTab === 'personal'" x-cloak class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">{{ langLabel('name') }} <span
                            style="color: red; font-size: 17px;">*</span></label>
                    <input type="text" name="name" placeholder="{{ langLabel('name') }}"
                        class="w-full border rounded px-3 py-2" value="{{ Auth()->user()->name ?? '' }}" />
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ langLabel('email') }} <span
                                style="color: red; font-size: 17px;">*</span></label>
                        <input type="email" name="email" placeholder="{{ langLabel('email') }}"
                            class="w-full border rounded px-3 py-2" value="{{ Auth()->user()->email ?? '' }}" />
                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ langLabel('gender') }} <span
                                style="color: red; font-size: 17px;">*</span></label>
                        <select class="w-full border rounded px-3 py-2" name="gender" id="gender">
                            <option value="">{{ langLabel('select') }} {{ langLabel('gender') }}</option>
                            <option value="Male" {{ Auth()->user()->gender == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ Auth()->user()->gender == 'Female' ? 'selected' : '' }}>Female
                            </option>
                        </select>

                        @error('gender')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ langLabel('phone_number') }} <span
                                style="color: red; font-size: 17px;">*</span></label>
                        <input type="tel" placeholder="{{ langLabel('phone_number') }}" name="phone_number"
                            class="w-full border rounded px-3 py-2" value="{{ Auth()->user()->phone_number ?? '' }}" />
                        @error('phone_number')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">{{ langLabel('dob') }} <span
                                style="color: red; font-size: 17px;">*</span></label>
                        <input type="date" name="dob" id="dob" class="w-full border rounded px-3 py-2"
                            value="{{ optional(Auth()->user())->date_of_birth ? date('Y-m-d', strtotime(Auth()->user()->date_of_birth)) : '' }}" />
                        @error('dob')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium">{{ langLabel('national_id_number') }} <span
                            style="color: red; font-size: 17px;">*</span></label>
                    <span class="text-xs text-blue-600">
                        National ID should start with 1 for male and 2 for female.
                    </span>
                    <input type="text" name="national_id" id="national_id" class="w-full border rounded-md p-2 mt-1"
                        placeholder="Enter national id number" value="{{ Auth()->user()->national_id ?? '' }}"
                        maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);" />
                    @error('national_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">{{ langLabel('address') }} <span
                            style="color: red; font-size: 17px;">*</span></label>
                    <input type="text" placeholder="{{ langLabel('address') }}" name="address"
                        class="w-full border rounded px-3 py-2" value="{{ Auth()->user()->address ?? '' }}" />
                    @error('address')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">{{ langLabel('city') }} <span
                            style="color: red; font-size: 17px;">*</span></label>
                    <input type="text" name="city" placeholder="{{ langLabel('select_city') }}"
                        class="w-full border rounded px-3 py-2" value="{{ Auth()->user()->city ?? '' }}" />
                    @error('city')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- State -->
                <div class="mt-3">
                    <label class="block font-medium mb-1">{{ langLabel('state') }}</label>
                    <input type="text" name="state" placeholder="{{ langLabel('select_state') }}"
                        value="{{ Auth()->user()->state ?? '' }}" class="w-full border rounded px-3 py-2" />
                    @error('state')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">{{ langLabel('country') }} <span
                            style="color: red; font-size: 17px;">*</span></label>
                    <input type="text" placeholder="{{ langLabel('select_country') }}" name="country"
                        class="w-full border rounded px-3 py-2" value="{{ Auth()->user()->country ?? '' }}" />
                    @error('country')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">{{ langLabel('pin_code') }} <span
                            style="color: red; font-size: 17px;">*</span></label>
                    <input type="text" name="pin_code" placeholder="{{ langLabel('enter_pin_code') }}"
                        class="w-full border rounded px-3 py-2" value="{{ Auth()->user()->pin_code ?? '' }}" />
                    @error('pin_code')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2 flex justify-end gap-4 mt-4">
                    <button type="button" class="border rounded px-6 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        @click="nextTab" x-show="profileTab !== 'additional'">
                        {{ langLabel('next') }}
                    </button>
                    <button type="button" id="save-personal-info"
                        class="bg-blue-700 text-white px-6 py-2 rounded text-sm hover:bg-blue-800">
                        {{ langLabel('save') }}
                    </button>
                </div>
            </div>
        </form>
        <script>
            document.getElementById('save-personal-info').addEventListener('click', function () {
                const form = document.getElementById('personal-info-form');
                const formData = new FormData(form);
                const successBox = document.getElementById('personal-info-success');
                const successText = successBox.querySelector('.message-text');

                // Clear previous error messages
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
                        // Show success alert
                        successText.textContent = data.message;
                        successBox.style.display = 'block';

                        // Hide after 3 seconds
                        setTimeout(() => {
                            successBox.style.display = 'none';
                            successText.textContent = '';
                        }, 3000);

                        // Optional: switch to next tab
                        if (typeof nextTab === "function") nextTab();
                    })
                    .catch(error => {
                        const errors = error.errors || {};
                        Object.keys(errors).forEach(field => {
                            const message = errors[field][0];
                            const input = form.querySelector(`[name="${field}"]`);
                            if (input) {
                                let existingError = input.parentNode.querySelector('.text-red-600');
                                if (existingError) existingError.remove();

                                const errorElem = document.createElement('p');
                                errorElem.className = 'text-red-600 text-sm mt-1';
                                errorElem.textContent = message;
                                input.insertAdjacentElement('afterend', errorElem);

                                input.addEventListener('input', function handler() {
                                    let errMsg = input.parentNode.querySelector('.text-red-600');
                                    if (errMsg) errMsg.remove();
                                    input.removeEventListener('input', handler);
                                });
                            }
                        });
                    });

            });
        </script>
        <!-- Success Message -->
        <div id="education-success"
            class="col-12 ml-auto mr-auto text-center alert alert-success alert-dismissible fade show"
            style="display: none;">
            <strong>{{ langLabel('success') }}</strong> <span class="message-text"></span>
        </div>

        <!-- Education Form -->
        <form id="education-info-form" method="POST" action="{{ route('jobseeker.education.update') }}">
            @csrf
            <div x-show="profileTab === 'education'" x-cloak class="space-y-4">
                @php
                    $educations = old('high_education') ? [] : $edu;
                    $educationCount = count(old('high_education', $educations ?? [null]));
                @endphp

                <!-- Education Container -->
                <div id="education-container" class="col-span-2 grid grid-cols-2 gap-4">
                    @for ($i = 0; $i < $educationCount; $i++)
                        @php $data = old("high_education.$i") ? null : ($educations[$i] ?? null); @endphp
                        <div
                            class="education-entry grid grid-cols-2 gap-4 col-span-2 p-4 rounded-md relative border border-gray-300">
                            @if ($data && isset($data->id))
                                <input type="hidden" name="education_id[]" value="{{ $data->id }}">
                            @endif

                            <!-- Highest Qualification -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Highest qualification <span class="text-red-600">*</span>
                                </label>
                                <input type="text" name="high_education[]"
                                    value="{{ old("high_education.$i", $data->high_education ?? '') }}"
                                    class="w-full border border-gray-300 rounded-md p-2"
                                    placeholder="e.g., Bachelor's Degree" />
                            </div>

                            <!-- Field of Study -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Field of study <span class="text-red-600">*</span>
                                </label>
                                <input type="text" name="field_of_study[]"
                                    value="{{ old("field_of_study.$i", $data->field_of_study ?? '') }}"
                                    class="w-full border border-gray-300 rounded-md p-2"
                                    placeholder="e.g., Computer Science" />
                            </div>

                            <!-- Institution -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Institution name <span class="text-red-600">*</span>
                                </label>
                                <input type="text" name="institution[]"
                                    value="{{ old("institution.$i", $data->institution ?? '') }}"
                                    class="w-full border border-gray-300 rounded-md p-2"
                                    placeholder="Enter institution name" />
                            </div>

                            <!-- Graduation Year -->
                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Graduation year <span class="text-red-600">*</span>
                                </label>
                                <input type="number" name="graduate_year[]"
                                    value="{{ old("graduate_year.$i", $data->graduate_year ?? '') }}"
                                    class="w-full border border-gray-300 rounded-md p-2" placeholder="e.g., 2023" min="1900"
                                    max="{{ date('Y') + 5 }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                            </div>

                            <!-- Remove Button -->
                            <button type="button"
                                class="remove-education absolute top-2 right-2 text-red-600 font-bold text-lg"
                                style="{{ $i == 0 ? 'display:none;' : '' }}">
                                &times;
                            </button>
                        </div>
                    @endfor
                </div>

                <!-- Add More Button -->
                <div class="col-span-2">
                    <button type="button" id="add-education"
                        class="text-green-600 text-sm">{{ langLabel('add_education') }} +</button>
                </div>

                <!-- Submit Buttons -->
                <div class="md:col-span-2 flex justify-end gap-4 mt-4">
                    <button type="button" class="border rounded px-6 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        @click="nextTab" x-show="profileTab !== 'additional'">Next</button>
                    <button type="button" id="save-education-info"
                        class="bg-blue-700 text-white px-6 py-2 rounded text-sm hover:bg-blue-800">
                        Save
                    </button>

                </div>
            </div>
        </form>


        <!-- JavaScript -->
        <script>
            // Add More Education Entry
            const educationContainer = document.getElementById('education-container');
            const addEducationBtn = document.getElementById('add-education');

            addEducationBtn.addEventListener('click', () => {
                const firstEntry = educationContainer.querySelector('.education-entry');
                const clone = firstEntry.cloneNode(true);

                clone.querySelectorAll('input').forEach(input => {
                    if (input.type === 'hidden') input.remove();
                    else input.value = '';
                });

                clone.querySelectorAll('select').forEach(select => {
                    select.selectedIndex = 0;
                });

                clone.querySelectorAll('p.text-red-600').forEach(error => error.remove());

                clone.querySelector('.remove-education').style.display = 'block';

                educationContainer.appendChild(clone);
            });

            // Remove Education Entry
            educationContainer.addEventListener('click', (e) => {
                if (e.target.classList.contains('remove-education')) {
                    const entry = e.target.closest('.education-entry');
                    entry.remove();
                }
            });

            // AJAX Save Education
            document.getElementById('save-education-info').addEventListener('click', function () {
                const form = document.getElementById('education-info-form');
                const formData = new FormData(form);
                const successBox = document.getElementById('education-success');
                const successText = successBox.querySelector('.message-text');

                // Clear previous errors
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
                        }, 3000);

                        if (typeof nextTab === "function") nextTab();
                    })
                    .catch(error => {
                        const errors = error.errors || {};
                        Object.keys(errors).forEach(fieldName => {
                            const [baseField, index] = fieldName.split('.');
                            const inputList = form.querySelectorAll(`[name="${baseField}[]"]`);
                            const input = inputList[parseInt(index)];

                            if (input) {
                                let existingError = input.parentNode.querySelector('.text-red-600');
                                if (existingError) existingError.remove();

                                const errorElem = document.createElement('p');
                                errorElem.className = 'text-red-600 text-sm mt-1';
                                errorElem.textContent = errors[fieldName][0];
                                input.insertAdjacentElement('afterend', errorElem);

                                input.addEventListener('input', function handler() {
                                    let errMsg = input.parentNode.querySelector('.text-red-600');
                                    if (errMsg) errMsg.remove();
                                    input.removeEventListener('input', handler);
                                });
                            }
                        });
                    });

            });
        </script>





        <!-- Work Experience Success Message -->
        <div id="work-success"
            class="col-12 ml-auto mr-auto text-center alert alert-success alert-dismissible fade show"
            style="display: none;">
            <strong>{{ langLabel('success') }}</strong> <span class="message-text"></span>
        </div>

        <!-- Work Experience Form -->
        <form id="work-info-form" method="POST" action="{{ route('jobseeker.workexprience.update') }}">
            @csrf
            <div x-show="profileTab === 'work'" x-cloak class="space-y-4">
                @php
                    $experiences = old('job_role') ? [] : $work;
                    $experienceCount = count(old('job_role', $experiences ?? [null]));
                @endphp

                <div id="work-container" class="col-span-2 grid grid-cols-2 gap-4">
                    @for ($i = 0; $i < $experienceCount; $i++)
                        @php $data = old("job_role.$i") ? null : ($experiences[$i] ?? null); @endphp

                        <div
                            class="work-entry grid grid-cols-2 gap-4 col-span-2 p-4 rounded-md relative border border-gray-300">
                            @if ($data && isset($data->id))
                                <input type="hidden" name="work_id[]" value="{{ $data->id }}">
                            @endif

                            <!-- Job Role -->
                            <div>
                                <label class="block text-sm font-medium mb-1">{{ langLabel('job_role') }} <span
                                        style="color: red; font-size: 17px;">*</span></label>
                                <input type="text" name="job_role[]" class="w-full border rounded px-3 py-2"
                                    value="{{ old("job_role.$i", $data->job_role ?? '') }}" placeholder="Enter Job Role" />
                            </div>

                            <!-- Organization -->
                            <div>
                                <label class="block text-sm font-medium mb-1">{{ langLabel('organization') }} <span
                                        style="color: red; font-size: 17px;">*</span></label>
                                <input type="text" name="organization[]" class="w-full border rounded px-3 py-2"
                                    value="{{ old("organization.$i", $data->organization ?? '') }}"
                                    placeholder="Enter Organization" />
                            </div>

                            <!-- Started From -->
                            <div>
                                <label class="block text-sm font-medium mb-1">{{ langLabel('started_from') }} <span
                                        style="color: red; font-size: 17px;">*</span></label>
                                <input type="date" name="starts_from[]"
                                    class="datepicker-start w-full border rounded px-3 py-2"
                                    value="{{ old("starts_from.$i", isset($data->starts_from) ? \Carbon\Carbon::parse($data->starts_from)->format('Y-m-d') : '') }}"
                                    max="{{ date('Y-m-d') }}" />
                            </div>

                            <!-- End To & Checkbox -->
                            @php
                                $isWorking = old('currently_working') ? in_array($i, old('currently_working', [])) :
                                    (isset($data->end_to) && $data->end_to === 'work here');
                                $defaultDate = old("end_to.$i", isset($data->end_to) && $data->end_to !== 'work here' ? \Carbon\Carbon::parse($data->end_to)->format('Y-m-d') : '');
                            @endphp

                            <div class="end-date-container">
                                <label class="block text-sm font-medium mb-1">
                                    To <span style="color: red; font-size: 17px;">*</span>
                                </label>

                                <input type="date" name="end_to[]" class="datepicker-end w-full border rounded px-3 py-2"
                                    value="{{ $defaultDate }}" data-default="{{ $defaultDate }}" {{ $isWorking ? 'disabled' : '' }} max="{{ date('Y-m-d') }}" />

                                <label class="inline-flex items-center space-x-2 mt-2">
                                    <input type="checkbox" name="currently_working[]" value="{{ $i }}" {{ $isWorking ? 'checked' : '' }} class="currently-working-checkbox" />
                                    <span>I currently work here</span>
                                </label>
                            </div>

                            <!-- Remove Button -->
                            <button type="button" class="remove-work absolute top-2 right-2 text-red-600 font-bold text-lg"
                                style="{{ $i == 0 ? 'display:none;' : '' }}">&times;</button>
                        </div>
                    @endfor
                </div>

                <!-- Add Button -->
                <div class="col-span-2">
                    <button type="button" id="add-work" class="text-green-600 text-sm">+
                        {{ langLabel('add_experience') }}</button>
                </div>

                <!-- Success Message -->
                <div id="work-success" class="p-3 bg-green-100 text-green-800 rounded mt-2" style="display:none;">
                    <span class="message-text"></span>
                </div>

                <!-- Submit Buttons -->
                <div class="md:col-span-2 flex justify-end gap-4 mt-4">
                    <button type="button" class="border rounded px-6 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        @click="nextTab" x-show="profileTab !== 'additional'">{{ langLabel('next') }}</button>
                    <button type="button" id="save-work-info"
                        class="bg-blue-700 text-white px-6 py-2 rounded text-sm hover:bg-blue-800">{{ langLabel('save') }}</button>
                </div>
            </div>
        </form>

        <script>
            const workContainer = document.getElementById('work-container');
            const addWorkBtn = document.getElementById('add-work');

            // Initialize checkbox logic for an entry
            function initWorkEntry(entry) {
                const checkbox = entry.querySelector('.currently-working-checkbox');
                const endInput = entry.querySelector('.datepicker-end');
                const defaultDate = endInput.dataset.default || '';

                endInput.dataset.defaultDate = defaultDate;

                checkbox.addEventListener('change', () => {
                    if (checkbox.checked) {
                        // Uncheck all other checkboxes
                        workContainer.querySelectorAll('.currently-working-checkbox').forEach(cb => {
                            if (cb !== checkbox) {
                                cb.checked = false;
                                const otherEnd = cb.closest('.work-entry').querySelector('.datepicker-end');
                                otherEnd.disabled = false;
                                otherEnd.value = otherEnd.dataset.defaultDate;
                            }
                        });

                        endInput.value = '';
                        endInput.disabled = true;
                    } else {
                        endInput.disabled = false;
                        endInput.value = endInput.dataset.defaultDate;
                    }
                });
            }

            // Initialize existing entries
            workContainer.querySelectorAll('.work-entry').forEach(initWorkEntry);

            // Add new work entry
            addWorkBtn.addEventListener('click', () => {
                const firstEntry = workContainer.querySelector('.work-entry');
                const clone = firstEntry.cloneNode(true);

                clone.querySelectorAll('input').forEach(input => {
                    if (input.type === 'hidden') input.remove();
                    else if (input.type === 'checkbox') input.checked = false;
                    else {
                        input.value = '';
                        input.disabled = false;
                    }
                });

                clone.querySelector('.remove-work').style.display = 'block';

                workContainer.appendChild(clone);
                initWorkEntry(clone); // initialize checkbox logic
            });

            // Remove work entry
            workContainer.addEventListener('click', e => {
                if (e.target.classList.contains('remove-work')) {
                    const entry = e.target.closest('.work-entry');
                    entry.remove();
                }
            });

            // Save work info (AJAX)
            document.getElementById('save-work-info').addEventListener('click', function () {
                const form = document.getElementById('work-info-form');
                const formData = new FormData(form);
                const successBox = document.getElementById('work-success');
                const successText = successBox?.querySelector('.message-text');

                // Clear previous errors
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
                        if (successBox && successText) {
                            successText.textContent = data.message;
                            successBox.style.display = 'block';
                            setTimeout(() => {
                                successBox.style.display = 'none';
                                successText.textContent = '';
                            }, 3000);
                        }
                        if (typeof nextTab === "function") nextTab();
                    })
                    .catch(error => {
                        const errors = error.errors || {};
                        Object.keys(errors).forEach(fieldName => {
                            const [baseField, index] = fieldName.split('.');
                            const inputList = form.querySelectorAll(`[name="${baseField}[]"]`);
                            const input = inputList[parseInt(index)];
                            if (input) {
                                let existingError = input.parentNode.querySelector('.text-red-600');
                                if (existingError) existingError.remove();
                                const errorElem = document.createElement('p');
                                errorElem.className = 'text-red-600 text-sm mt-1';
                                errorElem.textContent = errors[fieldName][0];
                                input.insertAdjacentElement('afterend', errorElem);

                                input.addEventListener('input', function handler() {
                                    let errMsg = input.parentNode.querySelector('.text-red-600');
                                    if (errMsg) errMsg.remove();
                                    input.removeEventListener('input', handler);
                                });
                            }
                        });
                    });
            });
        </script>




        <!-- Skills Success Message -->
        <div id="skills-success"
            class="col-12 ml-auto mr-auto text-center alert alert-success alert-dismissible fade show"
            style="display: none;">
            <strong>{{ langLabel('success') }}</strong> <span class="message-text"></span>
        </div>

        <!-- Skills & Training Form -->
        <form id="skills-info-form" method="POST" action="{{ route('jobseeker.skill.update') }}">
            @csrf
            <div x-show="profileTab === 'skills'" x-cloak class="space-y-4">

                <!-- Skills -->
                <div>
                    <label class="block text-sm font-medium mb-1">{{ langLabel('skills') }} <span
                            style="color: red; font-size: 17px;">*</span></label>
                    <input type="text" name="skills" placeholder="E.g., JavaScript, Excel, Marketing"
                        class="w-full border rounded px-3 py-2" value="{{ old('skills', $skills->skills ?? '') }}" />
                </div>

                <!-- Area of Interests -->
                <div>
                    <label class="block text-sm font-medium mb-1">{{ langLabel('area_of_intrest') }} <span
                            style="color: red; font-size: 17px;">*</span></label>
                    <input type="text" name="interest" placeholder="E.g., Data Science, Graphic Design"
                        class="w-full border rounded px-3 py-2"
                        value="{{ old('interest', $skills->interest ?? '') }}" />
                </div>

                <!-- Job Categories -->

                <div>
                    <label class="block text-sm font-medium mb-1">{{ langLabel('job_category') }} <span
                            style="color: red; font-size: 17px;">*</span></label>
                    <select class="w-full border rounded px-3 py-2" name="job_category">
                        <option value="">{{ langLabel('select') }} {{ langLabel('job_category') }}</option>
                        @php
                            $categories = [
                                'IT & Software',
                                'Sales & Marketing',
                                'Design & Creative',
                                'Finance & Accounting',
                                'Education & Training',
                                'Healthcare',
                                'Other'
                            ];
                            // Agar DB ki value list me nahi hai to usko append kar do
                            if (!empty($skills->job_category) && !in_array($skills->job_category, $categories)) {
                                $categories[] = $skills->job_category;
                            }
                        @endphp

                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ old('job_category', $skills->job_category ?? '') === $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>

                </div>

                <!-- Website Link -->
                <div>
                    <label class="block text-sm font-medium mb-1">{{ langLabel('website_link') }}</label>
                    <input type="url" name="website_link" placeholder="https://yourwebsite.com"
                        class="w-full border rounded px-3 py-2"
                        value="{{ old('website_link', $skills->website_link ?? '') }}" />
                </div>

                <!-- Portfolio Link -->
                <div>
                    <label class="block text-sm font-medium mb-1">{{ langLabel('portfolio_link') }}</label>
                    <input type="url" name="portfolio_link" placeholder="https://yourportfolio.com"
                        class="w-full border rounded px-3 py-2"
                        value="{{ old('portfolio_link', $skills->portfolio_link ?? '') }}" />
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" class="border rounded px-6 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        @click="nextTab" x-show="profileTab !== 'additional'">
                        {{ langLabel('next') }}
                    </button>
                    <button type="button" id="save-skills-info"
                        class="bg-blue-700 text-white px-6 py-2 rounded text-sm hover:bg-blue-800">{{ langLabel('save') }}</button>
                </div>
            </div>
        </form>

        <!-- JS: AJAX Skills Save -->
        <script>
            document.getElementById('save-skills-info').addEventListener('click', function () {
                const form = document.getElementById('skills-info-form');
                const formData = new FormData(form);
                const successBox = document.getElementById('skills-success');
                const successText = successBox.querySelector('.message-text');

                // Remove previous error messages
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
                                let existingError = input.parentNode.querySelector('.text-red-600');
                                if (existingError) existingError.remove();

                                const errorElem = document.createElement('p');
                                errorElem.className = 'text-red-600 text-sm mt-1';
                                errorElem.textContent = errors[field][0];
                                input.insertAdjacentElement('afterend', errorElem);

                                input.addEventListener('input', function handler() {
                                    let errMsg = input.parentNode.querySelector('.text-red-600');
                                    if (errMsg) errMsg.remove();
                                    input.removeEventListener('input', handler);
                                });
                            }
                        });
                    });

            });
        </script>


        @php
            $userId = auth()->id();
            $resume = App\Models\AdditionalInfo::where('user_id', $userId)->where('doc_type', 'resume')->first();
            $profile = App\Models\AdditionalInfo::where('user_id', $userId)->where('doc_type', 'profile_picture')->first();
        @endphp

        <!-- Success Message -->
        <div id="additional-success"
            class="col-12 ml-auto mr-auto text-center alert alert-success alert-dismissible fade show"
            style="display: none;">
            <strong>Success!</strong> <span class="message-text"></span>
        </div>

        <!-- Additional Info Form -->
        <form id="additional-info-form" method="POST" action="{{ route('jobseeker.additional.update') }}"
            enctype="multipart/form-data">
            @csrf
            <div x-show="profileTab === 'additional'" x-cloak class="space-y-4">

                <!-- Resume Upload -->
                <div>
                    <label class="block text-sm font-medium mb-1">{{ langLabel('upload_resume') }} <span
                            style="color: red; font-size: 17px;">*</span></label>
                    <div class="flex flex-col gap-2">
                        @if($resume)
                            <div class="flex items-center gap-4">
                                <a href="{{ asset($resume->document_path) }}" target="_blank"
                                    class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition duration-200">
                                    📄 {{ langLabel('view_resume') }}
                                </a>

                                <button type="button" class="delete-file text-red-600 text-sm"
                                    data-type="resume">{{ langLabel('delete') }}</button>
                            </div>
                        @endif
                        <div class="flex gap-2 items-center">
                            <input type="file" name="resume" class="border rounded-md p-2 w-full text-sm"
                                accept=".pdf,.doc,.docx,.txt" />
                            {{-- <button type="button"
                                class="remove-upload bg-red-500 text-white px-4 py-2 rounded-md text-sm">{{
                                langLabel('remove') }}</button> --}}
                        </div>
                    </div>
                </div>

                <!-- Profile Upload -->
                <!-- Upload Field -->
                <div>
                    <label class="block text-sm font-medium mb-1">{{ langLabel('upload_profile') }} <span
                            style="color: red; font-size: 17px;">*</span></label>
                    <div class="flex flex-col gap-2">
                        @if($profile)
                            <div class="flex items-center gap-4">
                                <a href="{{ asset($profile->document_path) }}" target="_blank"
                                    class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition duration-200">
                                    📄 {{ langLabel('view_profile') }}
                                </a>
                                <button type="button" class="delete-file text-red-600 text-sm"
                                    data-type="profile_picture">{{ langLabel('delete') }}</button>
                            </div>
                        @endif
                        <div class="flex gap-2 items-center">
                            <input accept="image/png, image/jpeg" type="file" name="profile" id="profileInput"
                                accept="image/*" class="border rounded-md p-2 w-full text-sm" />
                        </div>
                    </div>
                </div>

                <script>
                    document.getElementById('profileInput').addEventListener('change', function (e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function (event) {
                                document.getElementById('profilePreview').src = event.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                </script>


                <!-- Submit Button -->
                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" id="save-additional-info"
                        class="bg-blue-700 text-white px-6 py-2 rounded text-sm hover:bg-blue-800">{{ langLabel('save') }}</button>
                </div>
            </div>
        </form>

        <!-- Confirmation Modal -->
        <div id="deleteConfirmModal"
            class="fixed inset-0 bg-gray-100 bg-opacity-90 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-md p-6 w-full max-w-sm shadow-lg">
                <h2 class="text-lg font-semibold mb-4">{{ langLabel('confirm_delete') }}</h2>
                <p class="text-gray-700 mb-6">{{ langLabel('are_you_sure_you_want_to_delete') }} <span
                        id="delete-file-type" class="font-semibold"></span>?</p>
                <div class="flex justify-end gap-4">
                    <button type="button" id="cancelDeleteBtn"
                        class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">{{ langLabel('cancel') }}</button>
                    <button type="button" id="confirmDeleteBtn"
                        class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">{{ langLabel('yes') }},
                        {{ langLabel('delete') }}</button>
                </div>
            </div>
        </div>

        <!-- Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // 1. Reset file input
                document.querySelectorAll('.remove-upload').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const input = this.closest('.flex').querySelector('input[type="file"]');
                        if (input) input.value = '';
                    });
                });

                // 2. AJAX Save
                document.getElementById('save-additional-info')?.addEventListener('click', function () {
                    const form = document.getElementById('additional-info-form');
                    const formData = new FormData(form);
                    const successBox = document.getElementById('additional-success');
                    const successText = successBox.querySelector('.message-text');

                    // Clear previous validation messages
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
                                location.reload();
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

                // 3. Delete logic
                let selectedFileType = null;

                document.querySelectorAll('.delete-file').forEach(btn => {
                    btn.addEventListener('click', function () {
                        selectedFileType = this.dataset.type; // e.g. resume, profile_picture
                        document.getElementById('delete-file-type').textContent = selectedFileType.replace(/_/g, ' ');
                        document.getElementById('deleteConfirmModal').classList.remove('hidden');
                    });
                });

                document.getElementById('cancelDeleteBtn')?.addEventListener('click', function () {
                    document.getElementById('deleteConfirmModal').classList.add('hidden');
                    selectedFileType = null;
                });

                document.getElementById('confirmDeleteBtn')?.addEventListener('click', function () {
                    if (!selectedFileType) return;

                    const url = `{{ route('jobseeker.additional.delete', ':type') }}`.replace(':type', selectedFileType);

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                            'Accept': 'application/json'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            document.getElementById('deleteConfirmModal').classList.add('hidden');
                            selectedFileType = null;

                            if (data.status === 'success') {
                                // Use the returned type to find and remove the correct block
                                const block = document.querySelector(`[data-type="${data.message.toLowerCase().includes('profile') ? 'profile_picture' : 'resume'}"]`)?.closest('.flex.flex-col');
                                block?.querySelector('a')?.remove();
                                block?.querySelector('.delete-file')?.remove();

                                const successBox = document.getElementById('additional-success');
                                const successText = successBox.querySelector('.message-text');
                                successText.textContent = data.message;
                                successBox.style.display = 'block';

                                setTimeout(() => {
                                    successBox.style.display = 'none';
                                    successText.textContent = '';
                                }, 3000);
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(() => {
                            alert('Delete failed.');
                            document.getElementById('deleteConfirmModal').classList.add('hidden');
                        });
                });
            });
        </script>
    </div>
</div>
