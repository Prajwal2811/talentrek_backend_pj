@include('admin.componants.header')

<body data-theme="light">
    <div id="body" class="theme-cyan">
        <div class="themesetting">
        </div>
        <div class="overlay"></div>
        <div id="wrapper">
            @include('admin.componants.navbar')
            @include('admin.componants.sidebar')
            <div id="main-content">
                <div class="container-fluid">
                    @include('admin.errors')
                    <div class="block-header">
                        <div class="row clearfix align-items-center">
                            <!-- Left Column -->
                            <div class="col-xl-10 col-md-10 col-sm-12">
                                <h1>Hi, {{  Auth()->user()->name }}!</h1>
                                <span>JustDo Mentor's Profile</span>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card social theme-bg">
                                <div class="profile-header d-flex justify-content-between justify-content-center">
                                    <div class="d-flex">
                                        {{-- <div class="mr-3">
                                            <img src="../assets/images/user.png" class="rounded" alt="">
                                        </div> --}}
                                        <div class="details">
                                            <h4 class="mb-0">{{ $mentor->name }}</h4>
                                            <span class="text-light">{{ $mentor->city }}</span>
                                            <!-- <p class="mb-0"><span>Posts: <strong>321</strong></span> <span>Followers: <strong>4,230</strong></span> <span>Following: <strong>560</strong></span></p> -->
                                        </div>
                                    </div>
                                    <div>
                                        @php
                                            $status = $mentor->admin_status;
                                            $userRole = auth()->user()->role;
                                        @endphp

                                        {{-- Admin Actions --}}
                                        @if(!$status && $userRole === 'admin')
                                            <button class="btn btn-default me-2" data-bs-toggle="modal" data-bs-target="#approveModal">Approve</button>
                                            <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject</button>

                                        {{-- Superadmin Actions --}}
                                        @elseif($userRole === 'superadmin')

                                            {{-- Admin has not yet acted --}}
                                            @if(!$status)
                                                <span class="badge bg-warning me-2">Admin has not yet responded</span>

                                            {{-- Admin has rejected — Superadmin cannot override --}}
                                            @elseif($status === 'rejected')
                                                <div class="d-flex flex-column align-items-end text-end">
                                                    <span class="badge bg-danger mb-2">Admin Rejected</span>
                                                    <p class="text-light m-0">
                                                        <strong>Superadmin action not allowed</strong> because the profile was rejected by Admin.
                                                    </p>
                                                </div>


                                            {{-- Admin approved — Superadmin can act if not already acted --}}
                                            @elseif($status === 'approved')
                                                <span class="badge bg-info me-2">Admin Approved</span>
                                                <button class="btn btn-default me-2" data-bs-toggle="modal" data-bs-target="#superApproveModal">Super Approve</button>
                                                <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#superRejectModal">Super Reject</button>

                                            {{-- Superadmin already acted --}}
                                            @elseif(Str::startsWith($status, 'superadmin_'))
                                                @php
                                                    $badgeClass = Str::contains($status, 'approved') ? 'success' : 'danger';
                                                    $statusLabel = ucfirst(str_replace('_', ' ', $status));
                                                @endphp
                                                <span class="badge bg-{{ $badgeClass }}">{{ $statusLabel }}</span>
                                            @endif

                                        {{-- Final decision already made --}}
                                        @elseif(Str::startsWith($status, 'superadmin_'))
                                            @php
                                                $badgeClass = Str::contains($status, 'approved') ? 'success' : 'danger';
                                                $statusLabel = ucfirst(str_replace('_', ' ', $status));
                                            @endphp
                                            <span class="badge bg-{{ $badgeClass }}">{{ $statusLabel }}</span>

                                        {{-- Admin view-only state --}}
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($status) }}</span>
                                        @endif


                                        <!-- Admin Approve Modal -->
                                        <div class="modal fade text-dark" id="approveModal" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header"><h5 class="modal-title">Confirm Approval</h5></div>
                                                    <div class="modal-body">Are you sure you want to approve this profile?</div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button class="btn btn-success" onclick="updateStatus({{ $mentor->id }}, 'approved', this)">Yes, Approve</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Admin Reject Modal -->
                                        <div class="modal fade text-dark" id="rejectModal" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header"><h5 class="modal-title">Confirm Rejection</h5></div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to reject this profile?</p>
                                                        <div class="form-group">
                                                            <label for="adminRejectionReason">Reason:</label>
                                                            <textarea required id="adminRejectionReason" class="form-control" rows="3" placeholder="Enter reason..."></textarea>
                                                            <small id="adminRejectionReasonError" class="text-danger d-none"></small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button class="btn btn-danger" onclick="submitRejection({{ $mentor->id }}, 'rejected', 'adminRejectionReason', this)">Yes, Reject</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Superadmin Approve Modal -->
                                        <div class="modal fade text-dark" id="superApproveModal" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header"><h5 class="modal-title">Confirm Super Approval</h5></div>
                                                    <div class="modal-body">Are you sure you want to super approve this profile?</div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button class="btn btn-success" onclick="updateStatus({{ $mentor->id }}, 'superadmin_approved', this)">Yes, Approve</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Superadmin Reject Modal -->
                                        <div class="modal fade text-dark" id="superRejectModal" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header"><h5 class="modal-title">Confirm Super Rejection</h5></div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to super reject this profile?</p>
                                                        <div class="form-group">
                                                            <label for="superRejectionReason">Reason:</label>
                                                            <textarea required id="superRejectionReason" class="form-control" rows="3" placeholder="Enter reason..."></textarea>
                                                            <small id="superRejectionReasonError" class="text-danger d-none"></small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button class="btn btn-danger" onclick="submitRejection({{ $mentor->id }}, 'superadmin_rejected', 'superRejectionReason', this)">Yes, Reject</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- JS -->
                                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                        <script>
                                            function updateStatus(mentorId, status, btn) {
                                                const originalText = btn.innerHTML;
                                                btn.disabled = true;
                                                btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...`;

                                                $.post('{{ route("admin.mentor.updateStatus") }}', {
                                                    _token: '{{ csrf_token() }}',
                                                    mentor_id: mentorId,
                                                    status: status
                                                }).done(() => {
                                                    $('.modal').modal('hide');
                                                    location.reload();
                                                }).fail(() => {
                                                    btn.disabled = false;
                                                    btn.innerHTML = originalText;
                                                });
                                            }

                                            function submitRejection(mentorId, status, reasonId, btn) {
                                                const reason = document.getElementById(reasonId).value.trim();
                                                const errorDiv = document.getElementById(reasonId + "Error");

                                                if (!reason) {
                                                    if (errorDiv) {
                                                        errorDiv.textContent = "Reason is required for rejection.";
                                                        errorDiv.classList.remove("d-none");
                                                    }
                                                    return;
                                                } else {
                                                    if (errorDiv) errorDiv.classList.add("d-none");
                                                }

                                                const originalText = btn.innerHTML;
                                                btn.disabled = true;
                                                btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...`;

                                                $.post('{{ route("admin.jobseeker.updateStatus") }}', {
                                                    _token: '{{ csrf_token() }}',
                                                    mentor_id: mentorId,
                                                    status: status,
                                                    reason: reason
                                                }).done(() => {
                                                    $('.modal').modal('hide');
                                                    location.reload();
                                                }).fail((xhr) => {
                                                    btn.disabled = false;
                                                    btn.innerHTML = originalText;

                                                    if (errorDiv && xhr.responseJSON?.message) {
                                                        errorDiv.textContent = xhr.responseJSON.message;
                                                        errorDiv.classList.remove("d-none");
                                                    }
                                                });
                                            }
                                        </script>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-5">
                            <div class="card" style="position: sticky; top: 100px;">
                                <div class="header">
                                    <h2>Basic Information</h2>
                                </div>
                                <div class="body">
                                    <form>
                                        <div class="row">
                                            <div class="col-md-12 form-group">
                                                <label>Name</label>
                                                <input readonly type="text" class="form-control" value="{{ $mentor->name }}">
                                            </div>

                                            <div class="col-md-12 form-group">
                                                <label>Email address</label>
                                                <input readonly type="text" class="form-control" value="{{ $mentor->email }}">
                                            </div>

                                            <div class="col-md-12 form-group">
                                                <label>Date of birth</label>
                                                @php
                                                    $date = \Carbon\Carbon::parse($mentor->date_of_birth);
                                                    $day = $date->format('j');
                                                    $suffix = match (true) {
                                                        $day % 100 >= 11 && $day % 100 <= 13 => 'th',
                                                        $day % 10 == 1 => 'st',
                                                        $day % 10 == 2 => 'nd',
                                                        $day % 10 == 3 => 'rd',
                                                        default => 'th',
                                                    };
                                                    $formattedDate = $day . $suffix . ' ' . $date->format('F Y');
                                                @endphp
                                                <input readonly type="text" class="form-control" value="{{ $formattedDate }}">
                                            </div>

                                            <div class="col-md-12 form-group">
                                                <label>Phone number</label>
                                                <input readonly type="text" class="form-control" value="{{ $mentor->phone_code ? $mentor->phone_code . '-' . $mentor->phone_number : $mentor->phone_number }}">
                                            </div>

                                            <div class="col-md-12 form-group">
                                                <label>Gender</label>
                                                <input readonly type="text" class="form-control" value="{{ ucfirst($mentor->gender) }}">
                                            </div>

                                            <div class="col-md-12 form-group">
                                                <label>City</label>
                                                <input readonly type="text" class="form-control" value="{{ $mentor->city }}">
                                            </div>

                                            <div class="col-md-12 form-group">
                                                <label>State</label>
                                                <input readonly type="text" class="form-control" value="{{ $mentor->state }}">
                                            </div>

                                            <div class="col-md-12 form-group">
                                                <label>Country</label>
                                                <input readonly type="text" class="form-control" value="{{ $mentor->country }}">
                                            </div>

                                            <div class="col-md-12 form-group">
                                                <label>Address</label>
                                                <input readonly type="text" class="form-control" value="{{ $mentor->address }}">
                                            </div>

                                            <div class="col-md-12 form-group">
                                                <label>PIN Code</label>
                                                <input readonly type="text" class="form-control" value="{{ $mentor->pin_code }}">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-8 col-lg-8 col-md-7">
                            <div class="card">
                                <div class="header">
                                    <h2>Personal Information</h2>
                                        <div class="body">
                                            <!-- Tab navigation -->
                                            <ul class="nav nav-tabs2 mb-4" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-toggle="tab" href="#education">Educational Details</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#work">Work Experience</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#trainingexperience">Skills & Training</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#additional">Additional Information</a>
                                                </li>
                                            </ul>

                                            <!-- Tab content -->
                                            <div class="tab-content">

                                                <!-- Education -->
                                                <div class="tab-pane show active" id="education">
                                                    <form>
                                                        @if ($educations->isEmpty())
                                                            <p class="text-muted">No educational details found.</p>
                                                        @else
                                                            @foreach ($educations as $index => $education)
                                                                <div class="row">
                                                                    <div class="col-md-6 form-group">
                                                                        <label>Highest qualification</label>
                                                                        <input readonly type="text" class="form-control" value="{{ $education->high_education }}">
                                                                    </div>
                                                                    <div class="col-md-6 form-group">
                                                                        <label>Field of study</label>
                                                                        <input readonly type="text" class="form-control" value="{{ $education->field_of_study }}">
                                                                    </div>
                                                                    <div class="col-md-6 form-group">
                                                                        <label>Institution name</label>
                                                                        <input readonly type="text" class="form-control" value="{{ $education->institution }}">
                                                                    </div>
                                                                    <div class="col-md-6 form-group">
                                                                        <label>Graduation year</label>
                                                                        <input readonly type="text" class="form-control" value="{{ $education->graduate_year }}">
                                                                    </div>
                                                                </div>
                                                                @if (!$loop->last)
                                                                    <hr>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </form>
                                                </div>

                                                <!-- Work Experience -->
                                                <div class="tab-pane fade" id="work">
                                                    <form>
                                                        @if ($experiences->isEmpty())
                                                            <p class="text-muted">No work experience found.</p>
                                                        @else
                                                            @foreach ($experiences as $experience)
                                                                <div class="row">
                                                                    <div class="col-md-6 form-group">
                                                                        <label>Job role</label>
                                                                        <input type="text" class="form-control" readonly value="{{ $experience->job_role }}">
                                                                    </div>
                                                                    <div class="col-md-6 form-group">
                                                                        <label>Organization</label>
                                                                        <input readonly type="text" class="form-control" value="{{ $experience->organization }}">
                                                                    </div>
                                                                    <div class="col-md-6 form-group">
                                                                        <label>Started from</label>
                                                                        <input readonly type="text" class="form-control" value="{{ \Carbon\Carbon::parse($experience->starts_from)->format('jS F Y') }}">
                                                                    </div>
                                                                    <div class="col-md-6 form-group">
                                                                        <label>To</label>
                                                                        <input readonly type="text" class="form-control" value="{{ $experience->end_to === 'work Here' ? 'Work Here' : \Carbon\Carbon::parse($experience->end_to)->format('jS F Y') }}">
                                                                    </div>
                                                                </div>
                                                                @if (!$loop->last)
                                                                    <hr>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </form>
                                                </div>

                                                <!-- Skills -->
                                                <div class="tab-pane fade" id="trainingexperience">
                                                    <form>
                                                        @if ($trainingexperience->isEmpty())
                                                            <p class="text-muted">No trainingexperience or training details found.</p>
                                                        @else
                                                            @foreach ($trainingexperience as $skill)
                                                                <div class="row">
                                                                    <div class="col-md-6 form-group">
                                                                        <label>Skills</label>
                                                                        <input readonly type="text" class="form-control" value="{{ $skill->trainingexperience }}">
                                                                    </div>
                                                                    <div class="col-md-6 form-group">
                                                                        <label>Area of interests</label>
                                                                        <input readonly type="text" class="form-control" value="{{ $skill->interest }}">
                                                                    </div>
                                                                    <div class="col-md-6 form-group">
                                                                        <label>Job categories</label>
                                                                        <input readonly type="text" class="form-control" value="{{ $skill->job_category }}">
                                                                    </div>
                                                                    <div class="col-md-6 form-group">
                                                                        <label>Website link</label>
                                                                        <input readonly type="url" class="form-control" value="{{ $skill->website_link }}">
                                                                    </div>
                                                                    <div class="col-md-6 form-group">
                                                                        <label>Portfolio link</label>
                                                                        <input readonly type="url" class="form-control" value="{{ $skill->portfolio_link }}">
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </form>
                                                </div>

                                                <!-- Additional Info -->
                                                <div class="tab-pane fade" id="additional">
                                                    <form>
                                                        @if ($additioninfos->isEmpty())
                                                            <p class="text-muted">No additional documents found.</p>
                                                        @else
                                                            <div class="row">
                                                                @foreach($additioninfos as $info)
                                                                    @if($info->doc_type == 'resume')
                                                                        <div class="col-md-12 form-group d-flex align-items-center">
                                                                            <label class="w-100">Uploaded Resume</label>
                                                                            <input readonly type="text" class="form-control me-2" value="{{ $info->document_name }}">
                                                                            <a href="{{ $info->document_path }}" target="_blank" class="btn btn-danger">View</a>
                                                                        </div>
                                                                    @elseif($info->doc_type == 'profile_picture')
                                                                        <div class="col-md-12 form-group d-flex align-items-center">
                                                                            <label class="w-100">Uploaded Profile Picture</label>
                                                                            <input readonly type="text" class="form-control me-2" value="{{ $info->document_name }}">
                                                                            <a href="{{ $info->document_path }}" target="_blank" class="btn btn-danger">View</a>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="card">
                                <div class="header">
                                    <h2>Booking Slots Details</h2>
                                </div>
                                @php
                                    $bookingSlots = \App\Models\BookingSlot::with('unavailableDates')
                                                ->where('user_id', $mentor->id)
                                                ->where('user_type', 'mentor')
                                                ->get();
                                @endphp

                                <div class="body">
                                    <div class="col-lg-12">
                                        <div class="card-body">
                                            <p class="card-text">Here you can view the booking slots for this mentor.</p>
                                                <div class="table-responsive mt-3">
                                                    <table class="table table-hover js-basic-example dataTable table-custom spacing5">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr. No</th>
                                                            <th>Slot Mode</th>
                                                            <th>Start Time</th>
                                                            <th>End Time</th>
                                                            <th>Unavailable Dates</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if($bookingSlots->isEmpty())
                                                            <tr>
                                                                <td colspan="6" class="text-center">No booking slots available.</td>
                                                            </tr>
                                                        @else
                                                            @foreach($bookingSlots as $index => $slot)
                                                                <tr>
                                                                    <td>{{ $index + 1 }}</td>
                                                                    <td>{{ ucfirst($slot->slot_mode) }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}</td>
                                                                    <td>
                                                                        @if($slot->unavailableDates->isNotEmpty())
                                                                            <ul class="list-disc list-inside">
                                                                                @foreach($slot->unavailableDates as $date)
                                                                                    <li class="text-red-600 font-semibold">
                                                                                        {{ \Carbon\Carbon::parse($date->unavailable_date)->format('jS M Y') }}
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        @else
                                                                            <span class="text-gray-500">None</span>
                                                                        @endif
                                                                    </td>

                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>

                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="header">
                                    <h2>Mentor Subscriptions</h2>
                                </div>
                                <div class="body">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-lg-12">

                                                @if(count($subscriptionPlans) > 0)
                                                    @foreach ($subscriptionPlans as $index => $plan)
                                                        <div class="card p-3 mb-4 shadow-sm {{ $index > 0 ? 'd-none extra-subscription' : '' }}">
                                                            <div class="d-flex flex-column">
                                                                <h5 class="fw-bold mb-1">{{ $plan->title }}</h5>
                                                                <p class="text-muted mb-2">{{ $plan->description }}</p>
                                                                <div class="mb-2">
                                                                    <strong>Duration:</strong> {{ $plan->duration_days }} Days<br>
                                                                    <strong>Purchased On:</strong> 
                                                                        {{ \Carbon\Carbon::parse($plan->start_date)->format('d M Y') }}<br>
                                                                    <strong>Expired On:</strong> 
                                                                        {{ \Carbon\Carbon::parse($plan->end_date)->format('d M Y') }}
                                                                </div>
                                                                <span class="badge bg-secondary text-white small align-self-start px-2 py-1">
                                                                    ₹{{ $plan->price }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    @endforeach

                                                    <!-- View More Button (only show if more than 1 subscription exists) -->
                                                    @if(count($subscriptionPlans) > 1)
                                                        <div class="text-center mt-4">
                                                            <button class="btn btn-primary" id="toggleButtonSub"
                                                                onclick="toggleSubscriptions()">View More</button>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="alert alert-info text-center p-3">
                                                        No subscriptions found.
                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Toggle Script -->
                            <script>
                                function toggleSubscriptions() {
                                    const extraSubs = document.querySelectorAll('.extra-subscription');
                                    const button = document.getElementById("toggleButtonSub");

                                    let hidden = [...extraSubs].some(el => el.classList.contains("d-none"));

                                    extraSubs.forEach(el => el.classList.toggle("d-none", !hidden));
                                    button.textContent = hidden ? "View Less" : "View More";
                                }
                            </script>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    @include('admin.componants.footer')