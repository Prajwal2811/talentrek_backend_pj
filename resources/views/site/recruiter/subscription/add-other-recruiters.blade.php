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
    @php
        $recruiter = auth()->user('recruiter');
        $subscriptionType = App\Models\RecruiterCompany::where('recruiter_id', $recruiter->id)->first();

        if ($subscriptionType) {
            $RecruiterSubscription = $subscriptionType->active_subscription_plan_slug;

            if ($RecruiterSubscription == 'corporate_3_recruiters') {
                $totalRecruiters = 3; // including current recruiter
            } else {
                $totalRecruiters = 6; // including current recruiter
            }

            // One recruiter is current user, so subtract 1 for the extra inputs
            $extraRecruiters = $totalRecruiters - 1;
            $companyId = $subscriptionType->id;
            $slug = $subscriptionType->active_subscription_plan_slug;
            $subscriptionPlan = App\Models\SubscriptionPlan::where('slug', $slug)->first();
            $subscriptionID = $subscriptionPlan ? $subscriptionPlan->id : null;

            $otherRecruiters = App\Models\Recruiters::where('recruiter_of', $recruiter->id)->get();
        } else {
            // fallback values if recruiter has no company/subscription yet
            $totalRecruiters = 1;
            $extraRecruiters = 0;
            $companyId = null;
            $subscriptionID = null;
            $otherRecruiters = collect(); // empty collection
        }
    @endphp

    <!-- Subscription Modal -->
    <div id="subscriptionModal" class="fixed inset-0 bg-gray-200 bg-opacity-80 flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-6xl p-6 rounded-lg shadow-lg relative">
            <h3 class="text-xl font-semibold mb-6">Add Other Recruiters</h3>

            <form id="addRecruitersForm" method="POST" action="{{ route('recruiter.add.others') }}">
                @csrf
                <input type="text" name="main_recruiter_id" value="{{ auth()->user('recruiter')->id }}" hidden>
                <input type="text" name="company_id" value="{{ $companyId }}" hidden>
                <input type="text" name="subscription_id" value="{{ $subscriptionID }}" hidden>

               <div id="recruiterInputs">
                    @php
                        // Always show fields for (total recruiters - 1), since main recruiter already exists
                        $loopCount = $totalRecruiters - 1;
                    @endphp

                    @for ($i = 0; $i < $loopCount; $i++)
                        @php
                            $r = $otherRecruiters[$i] ?? null; // recruiter if exists, else null
                        @endphp
                        <div class="mb-3 flex space-x-2 recruiter-row">
                            @if($r)
                                {{-- Hidden ID field so Laravel knows it’s an update --}}
                                <input type="hidden" name="recruiters[{{ $i }}][id]" value="{{ $r->id }}">
                            @endif

                            <div class="w-1/3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                <input type="text" name="recruiters[{{ $i }}][name]"
                                    value="{{ old('recruiters.' . $i . '.name', $r->name ?? '') }}"
                                    placeholder="Enter Name"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2">
                                <span class="error text-red-500 text-sm" data-error="recruiters.{{ $i }}.name"></span>
                            </div>

                            <div class="w-1/3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="recruiters[{{ $i }}][email]"
                                    value="{{ old('recruiters.' . $i . '.email', $r->email ?? '') }}"
                                    placeholder="Enter Email"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2">
                                <span class="error text-red-500 text-sm" data-error="recruiters.{{ $i }}.email"></span>
                            </div>

                            <div class="w-1/3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">National ID</label>
                                <input type="text" name="recruiters[{{ $i }}][national_id]"
                                    value="{{ old('recruiters.' . $i . '.national_id', $r->national_id ?? '') }}"
                                    maxlength="15"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);"
                                    placeholder="Enter National ID"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2">
                                <span class="error text-red-500 text-sm" data-error="recruiters.{{ $i }}.national_id"></span>
                            </div>
                        </div>
                    @endfor
                </div>



                <div id="successMessage" class="hidden bg-green-100 text-green-800 px-4 py-2 rounded mb-3"></div>

                <button type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition text-sm">
                    Save Recruiters
                </button>
            </form>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                $('#addRecruitersForm').on('submit', function (e) {
                    e.preventDefault();

                    let form = $(this);
                    let actionUrl = form.attr('action');
                    let formData = form.serialize();
                    let submitBtn = form.find('button[type="submit"]');

                    // Clear old errors
                    $('.error').text('');
                    // Disable submit button
                    submitBtn.prop('disabled', true).text('Saving...');

                    $.ajax({
                        url: actionUrl,
                        type: 'POST',
                        data: formData,
                        success: function (response) {
                            form.trigger('reset'); // clear inputs

                            Swal.fire({
                                title: 'Success!',
                                text: response.message || 'Recruiters added successfully.',
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function (xhr) {
                            if (xhr.status === 422) {
                                let response = xhr.responseJSON;

                                // ✅ If we got a duplicate error from controller
                                if (response.message) {
                                    Swal.fire({
                                        title: 'Duplicate Entry',
                                        text: response.message,
                                        icon: 'warning',
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'OK'
                                    });
                                } else {
                                    // ✅ Laravel field errors
                                    let errors = xhr.responseJSON.errors;
                                    $.each(errors, function (field, messages) {
                                        $(`[data-error="${field}"]`).text(messages[0]);
                                    });
                                }

                                submitBtn.prop('disabled', false).text('Save Recruiters');
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Something went wrong. Please try again.',
                                    icon: 'error'
                                });
                                submitBtn.prop('disabled', false).text('Save Recruiters');
                            }
                        }

                    });
                });
            </script>


        </div>
    </div>