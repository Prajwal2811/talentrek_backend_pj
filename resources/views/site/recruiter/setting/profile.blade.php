@php
use App\Models\AdditionalInfo;

$recruiter = auth('recruiter')->user();
$recruiterId = $recruiter->id;
$recruiterRole = $recruiter->role;
$userId = auth()->id();

// Fetch company profile documents
$profile = AdditionalInfo::where('user_id', $userId)
    ->where('doc_type', 'company_profile')
    ->first();

// Company & registration documents
$companyProfile = AdditionalInfo::where('user_id', $userId)
    ->where('doc_type', 'company_profile_picture')
    ->first();
$registrationDoc = AdditionalInfo::where('user_id', $userId)
    ->where('doc_type', 'registration_document')
    ->first();
@endphp

<div x-show="activeSection === 'profile'" x-transition class="bg-white p-6">
    <!-- Company Header -->
    <div class="flex items-center space-x-4 mb-4">
        <img id="profilePreview"
             src="{{ $profile ? asset($profile->document_path) : 'https://www.lscny.org/app/uploads/2018/05/mystery-person.png' }}"
             class="h-20 w-20 rounded-md mb-2" alt="Profile Preview" />
        <div>
            @if ($companyDetails)
                <h3 class="text-xl font-semibold">{{ $companyDetails->company_name }}</h3>
                <p class="text-gray-600">{{ $companyDetails->business_email }}</p>
            @endif
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <span id="successMessage" class="inline-flex items-center bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 gap-2">
            ✅ <span class="text-sm font-medium">{{ session('success') }}</span>
        </span>
        <script>
            setTimeout(() => {
                const el = document.getElementById('successMessage');
                if (el) { el.classList.add('opacity-0'); setTimeout(() => el.style.display = 'none', 2000); }
            }, 10000);
        </script>
    @endif

    @if(session('error'))
        <span id="errorMessage" class="inline-flex items-center bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 gap-2">
            ⚠️ <span class="text-sm font-medium">{{ session('error') }}</span>
        </span>
        <script>
            setTimeout(() => {
                const el = document.getElementById('errorMessage');
                if (el) { el.classList.add('opacity-0'); setTimeout(() => el.style.display = 'none', 2000); }
            }, 10000);
        </script>
    @endif

    
    <!-- Inner Tabs -->
    <div class="border-b mb-4">
        <nav class="flex space-x-6">
            <button @click="activeSubTab = 'company'"
                    :class="activeSubTab === 'company' ? 'pb-2 border-b-2 border-blue-600 font-medium' : 'pb-2 text-gray-500 hover:text-black'"
                    class="focus:outline-none">
                {{ langLabel('company_information') }}
            </button>
            <button @click="activeSubTab = 'documents'"
                    :class="activeSubTab === 'documents' ? 'pb-2 border-b-2 border-blue-600 font-medium' : 'pb-2 text-gray-500 hover:text-black'"
                    class="focus:outline-none">
                {{ langLabel('documents') }}
            </button>
        </nav>
    </div>

    <!-- Tab Contents -->
    <div>
        {{-- ================== Company Info Tab ================== --}}
        <div x-show="activeSubTab === 'company'" x-transition>
            {{-- Success / Error messages --}}
            <div id="success-message" class="p-2 bg-green-100 text-green-800 rounded mb-3 hidden"></div>
            <div id="error-message" class="p-2 bg-red-100 text-red-800 rounded mb-3 hidden"></div>

            <form id="company-profile-form" action="{{ route('recruiter.company.profile.update') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                @csrf
                {{-- Company Details --}}
                    {{-- Company Name --}}
                    <div>
                        <label class="block mb-1 font-medium">{{ langLabel('company_name') }} <span class="text-red-600">*</span></label>
                        <input type="text" name="company_name" value="{{ old('company_name', $companyDetails->company_name ?? '') }}"
                            class="w-full border rounded px-3 py-2" {{ $recruiterRole === 'sub_recruiter' ? 'readonly' : '' }} />
                    </div>

                    {{-- Company Website --}}
                    <div>
                        <label class="block mb-1 font-medium">{{ langLabel('company_website') }}</label>
                        <input type="text" name="company_website" value="{{ old('company_website', $companyDetails->company_website ?? '') }}"
                            class="w-full border rounded px-3 py-2" {{ $recruiterRole === 'sub_recruiter' ? 'readonly' : '' }} />
                    </div>

                    {{-- Company City --}}
                    <div>
                        <label class="block mb-1 font-medium">{{ langLabel('company_city') }}</label>
                        <input type="text" name="company_city" value="{{ old('company_city', $companyDetails->company_city ?? '') }}"
                            class="w-full border rounded px-3 py-2" {{ $recruiterRole === 'sub_recruiter' ? 'readonly' : '' }} />
                    </div>

                    {{-- Company Address --}}
                    <div>
                        <label class="block mb-1 font-medium">{{ langLabel('company_address') }}</label>
                        <textarea name="company_address" class="w-full border rounded px-3 py-2" {{ $recruiterRole === 'sub_recruiter' ? 'readonly' : '' }}>{{ old('company_address', $companyDetails->company_address ?? '') }}</textarea>
                    </div>

                    {{-- Business Email --}}
                    <div>
                        <label class="block mb-1 font-medium">{{ langLabel('business_email') }} <span class="text-red-600">*</span></label>
                        <input type="email" name="business_email" value="{{ old('business_email', $companyDetails->business_email ?? '') }}"
                            class="w-full border rounded px-3 py-2" {{ $recruiterRole === 'sub_recruiter' ? 'readonly' : '' }} />
                    </div>

                    {{-- Phone Code --}}
                    <div>
                        <label class="block mb-1 font-medium">{{ langLabel('phone_code') }}</label>
                        <select name="phone_code" 
                                class="w-1/3 border rounded-l-md p-2 mt-1" 
                                {{ $recruiterRole === 'sub_recruiter' ? 'disabled' : '' }}>
                            <option value="+966" {{ old('phone_code', $companyDetails->phone_code ?? '') == '+966' ? 'selected' : '' }}>+966</option>
                            <!-- <option value="+971" {{ old('phone_code', $companyDetails->phone_code ?? '') == '+971' ? 'selected' : '' }}>+971</option> -->
                            <!-- <option value="+973" {{ old('phone_code', $companyDetails->phone_code ?? '') == '+973' ? 'selected' : '' }}>+973</option> -->
                        </select>

                    </div>

                    {{-- Company Phone Number --}}
                    <div>
                        <label class="block mb-1 font-medium">{{ langLabel('company_phone_number') }} <span class="text-red-600">*</span></label>
                        <input type="text" name="company_phone_number" value="{{ old('company_phone_number', $companyDetails->company_phone_number ?? '') }}"
                            class="w-full border rounded px-3 py-2" {{ $recruiterRole === 'sub_recruiter' ? 'readonly' : '' }} />
                    </div>

                    {{-- Registration Number --}}
                    <div>
                        <label class="block mb-1 font-medium">{{ langLabel('registration_number') }}</label>
                        <input type="text" name="registration_number" value="{{ old('registration_number', $companyDetails->registration_number ?? '') }}"
                            class="w-full border rounded px-3 py-2" {{ $recruiterRole === 'sub_recruiter' ? 'readonly' : '' }} />
                    </div>
                <div>
                    <label class="block mb-1 font-medium">{{ langLabel('industry_type') }} <span class="text-red-600">*</span></label>
                    @php
                        $industryOptions = ['Information Technology','Healthcare','Finance','Education','Manufacturing','Retail','Hospitality','Construction','Transportation','Real Estate','Agriculture','Telecommunications','Media & Entertainment','Government','Legal'];
                        $selectedIndustry = old('industry_type', $companyDetails->industry_type ?? '');
                    @endphp
                    <select name="industry_type" class="w-full border rounded px-3 py-2" {{ $recruiterRole === 'sub_recruiter' ? 'disabled' : '' }}>
                        <option value="">{{ langLabel('select_type') }}</option>
                        @foreach ($industryOptions as $type)
                            <option value="{{ $type }}" {{ $selectedIndustry === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                    @if($recruiterRole === 'sub_recruiter')
                        <input type="hidden" name="industry_type" value="{{ $selectedIndustry }}">
                    @endif
                </div>

                {{-- Recruiter Details --}}
                <div class="col-span-2 mt-4"><h3 class="text-xl font-semibold">{{ langLabel('recruiter_details') }}</h3></div>

                @php
                    $displayRecruiters = $recruiterRole === 'sub_recruiter' 
                                        ? [$companyDetails->recruiters->where('id', $recruiterId)->first()] 
                                        : $companyDetails->recruiters;
                @endphp

                @foreach ($displayRecruiters as $index => $r)
                    @if($r) {{-- make sure recruiter exists --}}
                    <div class="border rounded-lg p-4 mb-6 shadow-sm bg-white">
                        <h4 class="text-base font-semibold mb-3">{{ langLabel('recruiter') }} {{ $index + 1 }}</h4>
                        <input type="hidden" name="recruiters[{{ $index }}][id]" value="{{ $r->id }}">

                        {{-- Name --}}
                        <div>
                            <label class="block mb-1 font-medium">{{ langLabel('recruiters_name') }} <span class="text-red-600">*</span></label>
                            <input type="text" name="recruiters[{{ $index }}][name]" value="{{ old("recruiters.$index.name", $r->name) }}"
                                class="w-full border rounded px-3 py-2" {{ ($recruiterRole === 'sub_recruiter' && $r->id !== $recruiterId) ? 'readonly' : '' }} />
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block mb-1 font-medium">{{ langLabel('recruters_email') }} <span class="text-red-600">*</span></label>
                            <input type="email" name="recruiters[{{ $index }}][email]" value="{{ old("recruiters.$index.email", $r->email) }}"
                                class="w-full border rounded px-3 py-2" {{ ($recruiterRole === 'sub_recruiter' && $r->id !== $recruiterId) ? 'readonly' : '' }} />
                        </div>

                        {{-- Gender --}}
                        <div>
                            <label class="block mb-1 text-sm font-medium">Gender <span class="text-red-600">*</span></label>
                            <select name="recruiters[{{ $index }}][gender]" class="w-full border rounded-md p-2" {{ ($recruiterRole === 'sub_recruiter' && $r->id !== $recruiterId) ? 'disabled' : '' }}>
                                <option value="">Select Gender</option>
                                @foreach(['Male','Female','Other'] as $gender)
                                    <option value="{{ $gender }}" {{ old("recruiters.$index.gender", $r->gender) == $gender ? 'selected' : '' }}>{{ $gender }}</option>
                                @endforeach
                            </select>
                            @if($recruiterRole === 'sub_recruiter' && $r->id !== $recruiterId)
                                <input type="hidden" name="recruiters[{{ $index }}][gender]" value="{{ $r->gender }}">
                            @endif
                        </div>

                        {{-- National ID --}}
                        <div>
                            <label class="block mb-1 font-medium">{{ langLabel('national_id') }} <span class="text-red-600">*</span></label>
                            <input type="text" name="recruiters[{{ $index }}][national_id]" value="{{ old("recruiters.$index.national_id", $r->national_id) }}"
                                class="w-full border rounded px-3 py-2" maxlength="15"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);"
                                {{ ($recruiterRole === 'sub_recruiter' && $r->id !== $recruiterId) ? 'readonly' : '' }} />
                        </div>

                        {{-- Mobile Number --}}
                        <div>
                            <label class="block mb-1 font-medium">{{ langLabel('mobile_number') }} <span class="text-red-600">*</span></label>
                            <input type="text" name="recruiters[{{ $index }}][mobile_number]" value="{{ old("recruiters.$index.mobile_number", $r->phone_number) }}"
                                class="w-full border rounded px-3 py-2" maxlength="9"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9);"
                                {{ ($recruiterRole === 'sub_recruiter' && $r->id !== $recruiterId) ? 'readonly' : '' }} />
                        </div>
                    </div>
                    @endif
                @endforeach

                <div class="col-span-2 mt-6 flex justify-end space-x-3">
                    <button @click.prevent="activeSubTab = 'documents'" class="border px-6 py-2 rounded hover:bg-gray-100">{{ langLabel('next') }}</button>
                    <button type="submit" id="save-company-profile" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">{{ langLabel('update') }}</button>
                </div>
            </form>

            {{-- AJAX Script --}}
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
            $(document).ready(function () {
                $('#company-profile-form').on('submit', function (e) {
                    e.preventDefault();
                    let form = $(this), url = form.attr('action'), formData = form.serialize();

                    $('#success-message').hide().text('');
                    $('#error-message').hide().text('');
                    $('.text-red-600.text-sm').remove();

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: formData,
                        beforeSend: function() {
                            $('#save-company-profile').prop('disabled', true).text('Updating...');
                        },
                        success: function(response) {
                            $('#success-message').removeClass('hidden').text(response.message ?? 'Profile updated successfully!').fadeIn();
                            $('html, body').animate({ scrollTop: 0 }, 'fast');
                        },
                        error: function(xhr) {
                            $('.text-red-600.text-sm').remove();

                            if(xhr.status === 422){
                                let errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, messages){
                                    let field = key.replace(/\.(\d+)\./g, '[$1][').replace(/\./g, ']') + ']';
                                    let input = $('[name="'+field+'"]');

                                    if(input.length > 0){
                                        input.after('<p class="text-red-600 text-sm mt-1">'+messages[0]+'</p>');
                                    } else {
                                        $('#error-message').removeClass('hidden').text(messages[0]).fadeIn();
                                    }
                                });
                            } else {
                                $('#error-message').removeClass('hidden').text('❌ Something went wrong!').fadeIn();
                            }
                        },
                        complete: function() {
                            $('#save-company-profile').prop('disabled', false).text('Update');
                        }
                    });
                });
            });
            </script>
        </div>



        {{-- ================== Documents Tab ================== --}}
        <div x-show="activeSubTab === 'documents'" x-transition>
    <form id="company-documents-form" method="POST" action="{{ route('recruiter.company.document.update') }}" enctype="multipart/form-data" class="space-y-4 text-sm">
        @csrf
        <h3 class="text-lg font-semibold mb-4">{{ langLabel('upload_documents') }}</h3>

        <!-- Company Profile Picture -->
        <div>
            <label class="block font-medium mb-1">
                {{ langLabel('company_profile_picture') }} <span class="text-red-500">*</span>
            </label>

            @if($companyProfile)
                <div class="flex items-center gap-4 mb-2">
                    <a href="{{ asset($companyProfile->document_path) }}" target="_blank" class="bg-green-600 text-white px-3 py-1.5 rounded text-xs hover:bg-green-700">
                        📄 {{ langLabel('view_image') }}
                    </a>
                </div>
            @endif

            <input type="file" name="company_profile" accept="image/*" class="w-full border rounded px-3 py-2" {{ $recruiterRole === 'sub_recruiter' ? 'disabled' : '' }} />
        </div>

        <!-- Registration Document -->
        <div>
            <label class="block font-medium mb-1">
                {{ langLabel('registration_document') }} <span class="text-red-500">*</span>
            </label>

            @if($registrationDoc)
                <div class="flex items-center gap-4 mb-2">
                    <a href="{{ asset($registrationDoc->document_path) }}" target="_blank" class="bg-blue-600 text-white px-3 py-1.5 rounded text-xs hover:bg-blue-700">
                        📄 {{ langLabel('view_document') }}
                    </a>
                </div>
            @endif

            <input type="file" name="register_document" accept=".pdf,.doc,.docx" class="w-full border rounded px-3 py-2" {{ $recruiterRole === 'sub_recruiter' ? 'disabled' : '' }} />
        </div>

        <div class="flex justify-end mt-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                {{ langLabel('update_documents') }}
            </button>
        </div>
    </form>
</div>




    </div>
</div>
