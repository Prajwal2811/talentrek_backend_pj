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

    @if($recruiterNeedsSubscription)
        @include('site.recruiter.subscription.index')
    @endif
        @if($otherRecruiterSubscription)
        @include('site.recruiter.subscription.add-other-recruiters')
    @endif

    <div class="page-wraper">
        <div class="flex h-screen" x-data="{ sidebarOpen: true }" x-init="$watch('sidebarOpen', () => feather.replace())">
            <!-- Sidebar -->
            @include('site.recruiter.componants.sidebar')	
            <div class="flex-1 flex flex-col">
                @include('site.recruiter.componants.navbar')	
                

            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
             <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="{ activeSection: 'profile', activeSubTab: 'company' }">
                    <h2 class="text-2xl font-semibold mb-6">Settings</h2>

                    <div class="flex">
                        <!-- Sidebar -->
                        <aside class="w-60 bg-white p-4 border-r mt-4 shadow rounded-lg">
                            <ul class="space-y-2">
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'profile'; activeSubTab = 'company'"
                                    :class="activeSection === 'profile' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                    >Profile</a>

                                </li>
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'notifications'"
                                    :class="activeSection === 'notifications' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >Notifications</a>
                                </li>
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'subscription'; activeSubTab = 'subscription'"
                                    :class="activeSection === 'subscription' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >Subscription</a>
                                </li>
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'privacy'"
                                    :class="activeSection === 'privacy' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >Privacy policy</a>
                                </li>
                               
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'delete'"
                                    :class="activeSection === 'delete' ? 'bg-red-100 text-red-700 rounded px-2 py-2 block' : 'block px-2 py-2 text-red-600 hover:bg-red-100 rounded'"
                                >Delete account</a>
                                </li>
                            </ul>
                        </aside>

                        <!-- Main Content -->
                        <section class="flex-1 p-6">
                            <div class="bg-white rounded-lg shadow p-6">
                                <!-- Profile Section -->
                                @php
                                    use App\Models\AdditionalInfo;

                                    $recruiter   = auth('recruiter')->user();
                                    $recruiterId = $recruiter->id;
                                    $recruiterRole = $recruiter->role;

                                    $userId = auth()->id();

                                    $profile = AdditionalInfo::where('user_id', $userId)
                                                ->where('doc_type', 'company_profile')
                                                ->first();
                                @endphp

                                <!-- Profile Section -->
                                <div x-show="activeSection === 'profile'" x-transition>
                                    <!-- Company Header -->
                                    <div class="flex items-center space-x-4 mb-4">
                                        <!-- Image Preview -->
                                        <img id="profilePreview"
                                            src="{{ $profile ? asset($profile->document_path) : 'https://www.lscny.org/app/uploads/2018/05/mystery-person.png' }}"
                                            class="h-20 w-20 rounded-md mb-2"
                                            alt="Profile Preview" />
                                        <div>
                                            @if ($companyDetails)
                                                <h3 class="text-xl font-semibold">{{ $companyDetails->company_name }}</h3>
                                                <p class="text-gray-600">{{ $companyDetails->business_email }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Flash Messages --}}
                                    @if(session('success'))
                                        <span id="successMessage"
                                            class="inline-flex items-center bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 gap-2">
                                            ✅ <span class="text-sm font-medium">{{ session('success') }}</span>
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

                                    @if(session('error'))
                                        <span id="errorMessage"
                                            class="inline-flex items-center bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 gap-2">
                                            ⚠️ <span class="text-sm font-medium">{{ session('error') }}</span>
                                        </span>
                                        <script>
                                            setTimeout(() => {
                                                const el = document.getElementById('errorMessage');
                                                if (el) {
                                                    el.classList.add('opacity-0');
                                                    setTimeout(() => el.style.display = 'none', 2000);
                                                }
                                            }, 10000);
                                        </script>
                                    @endif

                                    <!-- Inner Tabs -->
                                    <div class="border-b mb-4">
                                        <nav class="flex space-x-6">
                                            <button @click="activeSubTab = 'company'"
                                                    :class="activeSubTab === 'company' ? 'pb-2 border-b-2 border-blue-600 font-medium' : 'pb-2 text-gray-500 hover:text-black'"
                                                    class="focus:outline-none">
                                                Company Information
                                            </button>
                                            <button @click="activeSubTab = 'documents'"
                                                    :class="activeSubTab === 'documents' ? 'pb-2 border-b-2 border-blue-600 font-medium' : 'pb-2 text-gray-500 hover:text-black'"
                                                    class="focus:outline-none">
                                                Documents
                                            </button>
                                        </nav>
                                    </div>

                                    <!-- Tab Contents -->
                                    <div>
                                        {{-- ================== Company Info Tab ================== --}}
                                        <div x-show="activeSubTab === 'company'" x-transition>
                                            <div id="company-profile-success"
                                                class="alert alert-success text-center"
                                                style="display: none;">
                                                <strong>Success!</strong> <span class="message-text"></span>
                                            </div>

                                            <div id="success-message" class="p-2 bg-green-100 text-green-800 rounded mb-3 hidden"></div>
                                            <div id="error-message" class="p-2 bg-red-100 text-red-800 rounded mb-3 hidden"></div>

                                            <form id="company-profile-form"
                                                action="{{ route('recruiter.company.profile.update') }}"
                                                method="POST"
                                                class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                                @csrf

                                                {{-- Company Details --}}
                                                <div>
                                                    <label class="block mb-1 font-medium">Company Name <span class="text-red-600">*</span></label>
                                                    <input type="text" name="company_name"
                                                        value="{{ old('company_name', $companyDetails->company_name ?? '') }}"
                                                        class="w-full border rounded px-3 py-2" />
                                                    @error("company_name") <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>

                                                <div>
                                                    <label class="block mb-1 font-medium">Company Email <span class="text-red-600">*</span></label>
                                                    <input type="email" name="business_email"
                                                        value="{{ old('business_email', $companyDetails->business_email ?? '') }}"
                                                        class="w-full border rounded px-3 py-2" />
                                                    @error("business_email") <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>

                                                <div>
                                                    <label class="block mb-1 font-medium">Phone Number <span class="text-red-600">*</span></label>
                                                    <input type="text" name="company_phone_number"
                                                        value="{{ old('company_phone_number', $companyDetails->company_phone_number ?? '') }}"
                                                        class="w-full border rounded px-3 py-2" />
                                                    @error("company_phone_number") <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>

                                                <div>
                                                    <label class="block mb-1 font-medium">Industry Type <span class="text-red-600">*</span></label>
                                                    <select name="industry_type" class="w-full border rounded px-3 py-2">
                                                        <option value="">Select Industry</option>
                                                        <option value="Information technology" {{ old('industry_type', $companyDetails->industry_type ?? '') == 'Information technology' ? 'selected' : '' }}>Information technology</option>
                                                        <option value="Healthcare" {{ old('industry_type', $companyDetails->industry_type ?? '') == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                                                        <option value="Finance" {{ old('industry_type', $companyDetails->industry_type ?? '') == 'Finance' ? 'selected' : '' }}>Finance</option>
                                                    </select>
                                                    @error("industry_type") <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>

                                                <div>
                                                    <label class="block mb-1 font-medium">Establishment Date <span class="text-red-600">*</span></label>
                                                    <input type="date" name="establishment_date"
                                                        value="{{ old('establishment_date', $companyDetails->establishment_date ?? '') }}"
                                                        class="w-full border rounded px-3 py-2" />
                                                    @error("establishment_date") <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>

                                                <div>
                                                    <label class="block mb-1 font-medium">Company Website <span class="text-red-600">*</span></label>
                                                    <input type="text" name="company_website"
                                                        value="{{ old('company_website', $companyDetails->company_website ?? '') }}"
                                                        class="w-full border rounded px-3 py-2" />
                                                    @error("company_website") <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>

                                                {{-- Recruiter Details --}}
                                                <div class="col-span-2 mt-4">
                                                    <h3 class="text-xl font-semibold">Recruiter Details</h3>
                                                </div>

                                                {{-- Loop recruiters --}}
                                                @foreach ($companyDetails->recruiters as $index => $r)
                                                    {{-- Hidden ID field --}}
                                                    <input type="hidden" name="recruiters[{{ $index }}][id]" value="{{ $r->id }}">

                                                    <div>
                                                        <label class="block mb-1 font-medium">Recruiter Name <span class="text-red-600">*</span></label>
                                                        <input type="text" name="recruiters[{{ $index }}][name]"
                                                            value="{{ old("recruiters.$index.name", $r->name) }}"
                                                            class="w-full border rounded px-3 py-2"
                                                            {{ $recruiterRole === 'sub_recruiter' && $r->id !== $recruiterId ? 'readonly' : '' }} />
                                                    </div>

                                                    <div>
                                                        <label class="block mb-1 font-medium">Recruiter Email <span class="text-red-600">*</span></label>
                                                        <input type="email" name="recruiters[{{ $index }}][email]"
                                                            value="{{ old("recruiters.$index.email", $r->email) }}"
                                                            class="w-full border rounded px-3 py-2"
                                                            {{ $recruiterRole === 'sub_recruiter' && $r->id !== $recruiterId ? 'readonly' : '' }} />
                                                    </div>

                                                    <div>
                                                        <label class="block mb-1 font-medium">National ID <span class="text-red-600">*</span></label>
                                                        <input type="text" name="recruiters[{{ $index }}][national_id]"
                                                            value="{{ old("recruiters.$index.national_id", $r->national_id) }}"
                                                            class="w-full border rounded px-3 py-2"  maxlength="15"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);" 
                                                            {{ $recruiterRole === 'sub_recruiter' && $r->id !== $recruiterId ? 'readonly' : '' }} />
                                                    </div>

                                                    {{-- ✅ Recruiter Mobile Number --}}
                                                    <div>
                                                        <label class="block mb-1 font-medium">Mobile Number <span class="text-red-600">*</span></label>
                                                        <input type="text" name="recruiters[{{ $index }}][mobile]"
                                                            value="{{ old("recruiters.$index.mobile", $r->mobile) }}"
                                                            class="w-full border rounded px-3 py-2"  maxlength="9"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9);" 
                                                            {{ $recruiterRole === 'sub_recruiter' && $r->id !== $recruiterId ? 'readonly' : '' }} />
                                                    </div>
                                                @endforeach



                                                <div class="col-span-2 mt-6 flex justify-end space-x-3">
                                                    <button @click.prevent="activeSubTab = 'documents'"
                                                            class="border px-6 py-2 rounded hover:bg-gray-100">
                                                        Next
                                                    </button>
                                                    <button type="submit"
                                                            id="save-company-profile"
                                                            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                                                        Update
                                                    </button>
                                                </div>
                                            </form>
                                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                            <script>
                                            $(document).ready(function () {
                                                $('#company-profile-form').on('submit', function (e) {
                                                    e.preventDefault();

                                                    let form = $(this);
                                                    let url = form.attr('action');
                                                    let formData = form.serialize();

                                                    // Clear messages
                                                    $('#success-message').hide().text('');
                                                    $('#error-message').hide().text('');
                                                    $('.text-red-600.text-sm').remove();

                                                    $.ajax({
                                                        type: "POST",
                                                        url: url,
                                                        data: formData,
                                                        beforeSend: function () {
                                                            $('#save-company-profile').prop('disabled', true).text('Updating...');
                                                        },
                                                        success: function (response) {
                                                            $('#success-message')
                                                                .removeClass('hidden')
                                                                .text(response.message ?? 'Profile updated successfully!')
                                                                .fadeIn();

                                                            $('html, body').animate({ scrollTop: 0 }, 'fast'); // scroll to top
                                                        },
                                                        error: function (xhr) {
                                                            if (xhr.status === 422) {
                                                                let errors = xhr.responseJSON.errors;
                                                                $.each(errors, function (key, messages) {
                                                                    let fieldName = key.replace(/\.(\d+)\./g, '[$1][');
                                                                    fieldName = fieldName.replace(/\./g, '][');
                                                                    fieldName = fieldName + ']';
                                                                    let field = $('[name="' + key + '"], [name="' + fieldName + '"]');
                                                                    if (field.length > 0) {
                                                                        field.after('<p class="text-red-600 text-sm mt-1">' + messages[0] + '</p>');
                                                                    }
                                                                });
                                                            } else {
                                                                $('#error-message')
                                                                    .removeClass('hidden')
                                                                    .text('❌ Something went wrong! Please try again.')
                                                                    .fadeIn();
                                                            }
                                                        },

                                                        complete: function () {
                                                            $('#save-company-profile').prop('disabled', false).text('Update');
                                                        }
                                                    });
                                                });
                                            });
                                            </script>



                                        </div>

                                        {{-- ================== Documents Tab ================== --}}
                                        <!-- Documents Tab -->
                                        <div x-show="activeSubTab === 'documents'" x-transition>
                                            <!-- Success Alert -->
                                            <div id="company-docs-success" class="alert alert-success mt-4 text-center hidden">
                                                <span class="message-text font-semibold"></span>
                                            </div>

                                            <form id="company-documents-form" method="POST" action="{{ route('recruiter.company.document.update') }}" enctype="multipart/form-data" class="space-y-4 text-sm">
                                                @csrf
                                                <h3 class="text-lg font-semibold mb-4">Upload Documents</h3>

                                                <!-- Company Profile Picture -->
                                                <div>
                                                    <label class="block font-medium mb-1">Company Profile Picture <span style="color: red; font-size: 17px;">*</span></label>
                                                    @if($companyProfile)
                                                        <div class="flex items-center gap-4">
                                                            <a href="{{ asset($companyProfile->document_path) }}" target="_blank" class="bg-green-600 text-white px-3 py-1.5 rounded text-xs hover:bg-green-700">📄 View Image</a>
                                                            <button type="button" class="delete-doc text-red-600 text-sm" data-type="company_profile">Delete</button>
                                                        </div>
                                                    @endif
                                                    <input type="file" name="company_profile" accept="image/*" class="w-full border rounded px-3 py-2" />
                                                </div>

                                                <!-- Registration Document -->
                                                <div>
                                                    <label class="block font-medium mb-1">Registration Document <span style="color: red; font-size: 17px;">*</span></label>
                                                    @if($registrationDoc)
                                                        <div class="flex items-center gap-4">
                                                            <a href="{{ asset($registrationDoc->document_path) }}" target="_blank" class="bg-blue-600 text-white px-3 py-1.5 rounded text-xs hover:bg-blue-700">📄 View Document</a>
                                                            <button type="button" class="delete-doc text-red-600 text-sm" data-type="register_document">Delete</button>
                                                        </div>
                                                    @endif
                                                    <input type="file" name="register_document" accept=".pdf,.doc,.docx" class="w-full border rounded px-3 py-2" />
                                                </div>

                                                <div class="flex justify-end mt-4">
                                                    <button type="button" id="save-company-docs" class="bg-blue-700 text-white px-6 py-2 rounded hover:bg-blue-800">Update</button>
                                                </div>
                                            </form>

                                            <!-- Delete Modal -->
                                            <div id="deleteCompanyDocModal" class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50 hidden">
                                                <div class="bg-white p-6 rounded shadow-lg w-full max-w-sm">
                                                    <h2 class="text-lg font-semibold mb-4">Confirm Delete</h2>
                                                    <p class="mb-4 text-gray-700">Are you sure you want to delete <span id="delete-doc-type" class="font-bold"></span>?</p>
                                                    <div class="flex justify-end gap-4">
                                                        <button type="button" id="cancelCompanyDocDelete" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
                                                        <button type="button" id="confirmCompanyDocDelete" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            let selectedCompanyDocType = null;

                                            document.getElementById('save-company-docs').addEventListener('click', function () {
                                                const form = document.getElementById('company-documents-form');
                                                const formData = new FormData(form);
                                                const successBox = document.getElementById('company-docs-success');
                                                const successText = successBox.querySelector('.message-text');

                                                fetch(form.action, {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                                                        'Accept': 'application/json'
                                                    },
                                                    body: formData
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    successText.textContent = data.message;
                                                    successBox.classList.remove('hidden');
                                                    setTimeout(() => location.reload(), 2000); // Reload after success
                                                })
                                                .catch(error => {
                                                    alert('Failed to upload documents.');
                                                });
                                            });

                                            document.querySelectorAll('.delete-doc').forEach(btn => {
                                                btn.addEventListener('click', () => {
                                                    selectedCompanyDocType = btn.dataset.type;
                                                    document.getElementById('delete-doc-type').textContent = selectedCompanyDocType.replace(/_/g, ' ');
                                                    document.getElementById('deleteCompanyDocModal').classList.remove('hidden');
                                                });
                                            });

                                            document.getElementById('cancelCompanyDocDelete').addEventListener('click', () => {
                                                selectedCompanyDocType = null;
                                                document.getElementById('deleteCompanyDocModal').classList.add('hidden');
                                            });

                                            document.getElementById('confirmCompanyDocDelete').addEventListener('click', () => {
                                                if (!selectedCompanyDocType) return;

                                                fetch(`{{ route('recruiter.company.document.delete', ':type') }}`.replace(':type', selectedCompanyDocType), {
                                                    method: 'DELETE',
                                                    headers: {
                                                        'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                                                        'Accept': 'application/json'
                                                    }
                                                })
                                                .then(res => res.json())
                                                .then(data => {
                                                    document.getElementById('deleteCompanyDocModal').classList.add('hidden');
                                                    selectedCompanyDocType = null;

                                                    const successBox = document.getElementById('company-docs-success');
                                                    const successText = successBox.querySelector('.message-text');
                                                    successText.textContent = data.message;
                                                    successBox.classList.remove('hidden');
                                                    setTimeout(() => location.reload(), 2000);
                                                })
                                                .catch(() => {
                                                    alert('Delete failed.');
                                                    document.getElementById('deleteCompanyDocModal').classList.add('hidden');
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>


                                

                                <!-- Notifications Section -->
                                <div x-show="activeSection === 'notifications'" x-transition class="bg-white p-6 ">
                                <h3 class="text-xl font-semibold mb-4 border-b pb-2">Notifications</h3>

                                <!-- Scrollable notification list -->
                                <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                                    <!-- Notification Items -->
                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> You have meeting on <span class="font-medium">12:30 pm</span></p>
                                        <p class="text-sm text-gray-500 mt-1">4 Minute ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Your task deadline is <span class="font-medium">3:00 pm</span></p>
                                        <p class="text-sm text-gray-500 mt-1">10 Minute ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Sent you a file at <span class="font-medium">11:45 am</span></p>
                                        <p class="text-sm text-gray-500 mt-1">30 Minute ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Meeting rescheduled to <span class="font-medium">1:30 pm</span></p>
                                        <p class="text-sm text-gray-500 mt-1">1 Hour ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Approved your request</p>
                                        <p class="text-sm text-gray-500 mt-1">2 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> You have call scheduled at <span class="font-medium">4:00 pm</span></p>
                                        <p class="text-sm text-gray-500 mt-1">3 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> New comment on your post</p>
                                        <p class="text-sm text-gray-500 mt-1">3 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> You have meeting on <span class="font-medium">5:30 pm</span></p>
                                        <p class="text-sm text-gray-500 mt-1">4 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Sent a reminder for report submission</p>
                                        <p class="text-sm text-gray-500 mt-1">5 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Task completed: <span class="font-medium">UI Design</span></p>
                                        <p class="text-sm text-gray-500 mt-1">6 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> You have meeting on <span class="font-medium">10:30 am</span> tomorrow</p>
                                        <p class="text-sm text-gray-500 mt-1">Yesterday</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Reminder: Submit your weekly report</p>
                                        <p class="text-sm text-gray-500 mt-1">Yesterday</p>
                                    </div>
                                    </div>
                                </div>
                                </div>

                               <div x-show="activeSection === 'subscription'" x-transition class="bg-white p-6">
                                    <h3 class="text-xl font-semibold mb-4 border-b pb-2">Subscription</h3>
                                    @php
                                        $userId = auth()->user('recruiter')->id;
                                        $userRole = auth()->user('recruiter')->role;
                                        $companyId = auth()->user('recruiter')->company_id;
                                        // Fetch available plans for this user type
                                        $subscriptions = App\Models\SubscriptionPlan::where('user_type', 'recruiter')->get();

                                        // Fetch purchased subscriptions for current user
                                        $purchasedSubscriptions = App\Models\PurchasedSubscription::select('subscription_plans.*', 'purchased_subscriptions.*')
                                                                ->join('subscription_plans', 'purchased_subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
                                                                ->where('subscription_plans.user_type', 'recruiter')
                                                                // ->where('purchased_subscriptions.user_id', $userId)
                                                                ->where('purchased_subscriptions.company_id', $companyId)
                                                                ->orderBy('purchased_subscriptions.created_at', 'desc')
                                                                ->get();

                                        $showPlansModal = false;

                                        if ($purchasedSubscriptions->count() > 0) {
                                            $latest = $purchasedSubscriptions->first();
                                            $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($latest->end_date), false);

                                            if ($daysLeft > 0 && $daysLeft <= 30) {
                                                $showPlansModal = true;
                                            }
                                        }
                                    @endphp

                                    <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        @if($showPlansModal)
                                            document.getElementById('plansModal').classList.remove('hidden');
                                        @endif
                                    });
                                    </script>

                                    @if(auth()->user('recruiter')->role === 'main')
                                        <!-- Subscription Card -->
                                        <div class="bg-gray-100 p-6 rounded-md flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                                            <div>
                                                <h4 class="text-lg font-semibold mb-1">Subscription Plans</h4>
                                                <p class="text-gray-600 text-sm">Purchase subscription to get access to premium features</p>
                                            </div>
                                            <button onclick="document.getElementById('plansModal').classList.remove('hidden')"
                                                class="mt-4 md:mt-0 bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                                                View Plans
                                            </button>
                                        </div>

                                        <!-- Plans Modal -->
                                        <div id="plansModal" class="fixed inset-0 bg-gray-200 bg-opacity-80 flex items-center justify-center z-50 hidden">
                                            <div class="bg-white w-full max-w-6xl p-6 rounded-lg shadow-lg relative">
                                                <button onclick="document.getElementById('plansModal').classList.add('hidden')"
                                                    class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-lg">✕</button>

                                                <h3 class="text-xl font-semibold mb-6">Available Subscription Plans</h3>

                                                <div class="grid grid-cols-3 sm:grid-cols-3 lg:grid-cols-3 gap-4">
                                                    @foreach($subscriptions as $plan)
                                                        <div class="border rounded-lg p-4 shadow-sm text-center">
                                                            <div class="flex flex-col items-center">
                                                                <div class="w-12 h-12 bg-gray-300 rounded-full mb-2"></div>
                                                                <h4 class="font-semibold">{{ $plan->title }}</h4>
                                                                <p class="font-bold text-lg mt-1">AED {{ $plan->price }}</p>
                                                            </div>
                                                            <p class="text-sm text-gray-500 mt-2 mb-3">{{ $plan->description }}</p>
                                                            @php
                                                                $features = is_array($plan->features) ? $plan->features : explode(',', $plan->features);
                                                            @endphp
                                                            <ul class="list-disc list-outside pl-5 text-sm text-gray-700 mb-4">
                                                                @foreach($features as $feature)
                                                                    <li>{{ trim($feature) }}</li>
                                                                @endforeach
                                                            </ul>
                                                            <button type="button"
                                                                class="bg-orange-500 hover:bg-orange-600 text-white w-full py-2 rounded-md text-sm font-medium buy-subscription-btn"
                                                                data-plan-id="{{ $plan->id }}">
                                                                Buy subscription
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <div id="paymentModal" class="fixed inset-0 bg-gray-200 bg-opacity-80 z-50 hidden flex items-center justify-center">
                                            <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg relative">
                                                <h3 class="text-xl font-semibold mb-4 text-center">Payment</h3>
                                                <p class="mb-6 text-gray-600 text-center">Enter your card details to continue</p>

                                                <form id="paymentForm">
                                                    @csrf
                                                    <input type="hidden" name="plan_id" id="selectedPlanId">

                                                    <div class="mb-4">
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Card Number</label>
                                                        <input type="text" name="card_number" value="4242424242424242"
                                                            class="w-full border border-gray-300 rounded-md px-4 py-2">
                                                    </div>

                                                    <div class="mb-4 flex space-x-2">
                                                        <div class="w-1/2">
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">Expiry</label>
                                                            <input type="text" name="expiry" value="12/30"
                                                                class="w-full border border-gray-300 rounded-md px-4 py-2">
                                                        </div>
                                                        <div class="w-1/2">
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                                                            <input type="text" name="cvv" value="123"
                                                                class="w-full border border-gray-300 rounded-md px-4 py-2">
                                                        </div>
                                                    </div>
                                                    <button type="submit"
                                                        class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 transition">
                                                        Pay Now
                                                    </button>
                                                </form>
                                                <div id="paymentMessage" class="mt-3 text-center text-sm"></div>
                                                <button onclick="closePaymentModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl font-bold">
                                                    ×
                                                </button>
                                            </div>
                                        </div>

                                    @endif
                                    <h4 class="text-lg font-semibold mb-3">Subscription History</h4>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full border border-gray-200 text-sm">
                                            <thead class="bg-gray-100 text-left">
                                                <tr>
                                                    <th class="px-4 py-2 font-medium text-gray-700">Sr. No.</th>
                                                    <th class="px-4 py-2 font-medium text-gray-700">Subscription</th>
                                                    <th class="px-4 py-2 font-medium text-gray-700">Duration</th>
                                                    <th class="px-4 py-2 font-medium text-gray-700">Purchased on</th>
                                                    <th class="px-4 py-2 font-medium text-gray-700">Expired on</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($purchasedSubscriptions as $index => $subscription)
                                                    <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                                                        <td class="px-4 py-3">{{ $index + 1 }}.</td>
                                                        <td class="px-4 py-3">{{ $subscription->title }}</td>
                                                        <td class="px-4 py-3">{{ $subscription->duration_days }} {{ Str::plural('days', $subscription->duration_days) }}</td>
                                                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($subscription->start_date)->format('d/m/Y') }}</td>
                                                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($subscription->end_date)->format('d/m/Y') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                     </div>
                                </div>

<!-- Include SweetAlert2 -->
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                                <script>
                                    function openPaymentModal(planId) {
                                        document.getElementById('selectedPlanId').value = planId;
                                        document.getElementById('paymentModal').classList.remove('hidden');
                                    }

                                    function closePaymentModal() {
                                        document.getElementById('paymentModal').classList.add('hidden');
                                    }

                                    document.addEventListener('DOMContentLoaded', () => {
                                        document.querySelectorAll('.buy-subscription-btn').forEach(button => {
                                            button.addEventListener('click', function () {
                                                openPaymentModal(this.getAttribute('data-plan-id'));
                                            });
                                        });

                                        document.addEventListener('keydown', function (e) {
                                            if (e.key === 'Escape') closePaymentModal();
                                        });

                                        document.getElementById('paymentForm').addEventListener('submit', function (e) {
                                            e.preventDefault();
                                            let formData = new FormData(this);

                                            fetch("{{ route('recruiter.subscription.payment') }}", {
                                                method: "POST",
                                                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                                                body: formData
                                            })
                                            .then(async response => {
                                                let data = await response.json();
                                                if (!response.ok) throw data;
                                                return data;
                                            })
                                            .then(data => {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Payment Successful',
                                                    text: data.message,
                                                    confirmButtonColor: '#3085d6',
                                                    timer: 2000,
                                                    timerProgressBar: true
                                                }).then(() => {
                                                    closePaymentModal();
                                                    location.reload();
                                                });
                                            })
                                            .catch(error => {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Payment Failed',
                                                    text: error.message || "Something went wrong!",
                                                    confirmButtonColor: '#d33'
                                                });
                                            });
                                        });
                                    });
                                </script>


                                <!-- Privacy Policy Section -->
                              <div x-show="activeSection === 'privacy'" x-transition>
                                <h3 class="text-xl font-semibold mb-4">Privacy Policy</h3>
                                
                                <p class="mb-4">At XYZ Infotech, we are committed to protecting your privacy. This Privacy Policy outlines how we collect, use, and safeguard your personal information.</p>

                                <h4 class="text-lg font-semibold mb-2">1. Information We Collect</h4>
                                <ul class="list-disc list-inside mb-4 text-sm text-gray-700">
                                    <li>Personal information such as name, email address, phone number, and address.</li>
                                    <li>Usage data including pages visited, time spent, and actions taken on our platform.</li>
                                    <li>Device and browser information for improving user experience.</li>
                                </ul>

                                <h4 class="text-lg font-semibold mb-2">2. How We Use Your Information</h4>
                                <ul class="list-disc list-inside mb-4 text-sm text-gray-700">
                                    <li>To provide and maintain our services.</li>
                                    <li>To improve customer support and experience.</li>
                                    <li>To send transactional and promotional communications.</li>
                                    <li>To comply with legal obligations.</li>
                                </ul>

                                <h4 class="text-lg font-semibold mb-2">3. Data Security</h4>
                                <p class="mb-4 text-sm text-gray-700">We implement a variety of security measures to maintain the safety of your personal information. However, no method of transmission over the Internet is 100% secure.</p>

                                <h4 class="text-lg font-semibold mb-2">4. Third-Party Services</h4>
                                <p class="mb-4 text-sm text-gray-700">We may use third-party services (e.g., analytics providers, payment gateways) that collect, monitor, and analyze data. These services have their own privacy policies.</p>

                                <h4 class="text-lg font-semibold mb-2">5. Your Rights</h4>
                                <ul class="list-disc list-inside mb-4 text-sm text-gray-700">
                                    <li>Access and update your personal information.</li>
                                    <li>Request deletion of your data.</li>
                                    <li>Opt out of marketing communications.</li>
                                </ul>

                                <h4 class="text-lg font-semibold mb-2">6. Changes to This Policy</h4>
                                <p class="mb-4 text-sm text-gray-700">We may update this Privacy Policy periodically. We will notify you of any significant changes via email or on our platform.</p>

                                <h4 class="text-lg font-semibold mb-2">7. Contact Us</h4>
                                <p class="text-sm text-gray-700">If you have any questions or concerns about this policy, please contact us at <a href="mailto:support@xyzinfotech.com" class="text-blue-600 underline">support@xyzinfotech.com</a>.</p>
                                </div>


                                <!-- Log Out Section -->
                                <div x-show="activeSection === 'logout'" x-transition>
                                <h3 class="text-xl font-semibold mb-4">Log Out</h3>
                                <p>Are you sure you want to log out?</p>
                                <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Log Out</button>
                                </div>

                                <!-- Delete Account Section -->
                                <div x-show="activeSection === 'delete'" x-transition>
                                    <h3 class="text-xl font-semibold mb-4 text-red-600">Delete Account</h3>
                                    <p>This action is irreversible. Are you sure you want to delete your account?</p>
                                    <!-- <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete Account</button> -->
                                    <form id="deleteAccountForm" action="{{ route('recruiter.destroy') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" id="deleteAccountBtn" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                            Delete Account
                                        </button>
                                    </form>

                                        <!-- SweetAlert2 CDN -->
                                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                        <script>
                                          document.getElementById('deleteAccountBtn').addEventListener('click', function () {
                                                Swal.fire({
                                                    title: 'Are you sure?',
                                                    text: "This action is irreversible. Delete your account?",
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#d33',
                                                    cancelButtonColor: '#3085d6',
                                                    confirmButtonText: 'Yes, delete it!',
                                                    cancelButtonText: 'No',
                                                    width: '350px', // Make it smaller
                                                    padding: '1em',
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        document.getElementById('deleteAccountForm').submit();
                                                    }
                                                });
                                            });
                                        </script>
                                </div>
                            </div>
                        </section>
                    </div>
                </main>



            </div>
        </div>

        <!-- Feather Icons -->
        <script>
            feather.replace()
        </script>


    </div>
           



@include('site.recruiter.componants.footer')