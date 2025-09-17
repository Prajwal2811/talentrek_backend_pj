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
                    <div class="block-header">
                        <div class="row clearfix align-items-center">
                            <!-- Left Column -->
                            <div class="col-xl-10 col-md-10 col-sm-12">
                                <h1>Hi, {{  Auth()->user()->name }}!</h1>
                                <span>JustDo Recruiter's Profile</span>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card social theme-bg">
                                <div class="profile-header d-flex justify-content-between justify-content-center">
                                    <div class="d-flex">
                                        <div class="mr-3">
                                            <img src="../assets/images/user.png" class="rounded" alt="">
                                        </div>
                                        <div class="details">
                                            <h4 class="mb-0">{{ $recruiter->name }}</h4>
                                            <span class="text-light">{{ $recruiter->city }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        @php
                                            $status = App\Models\Recruiters::with('company')->where('id', $recruiter->id)->first()->admin_status;
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
                                                <div class="d-flex flex-column align-items-end text-end w-100">
                                                    <span class="badge bg-danger mb-2">Admin Rejected</span>
                                                    <p class="text-light m-0">
                                                        <strong>Superadmin action not allowed</strong> because the profile was rejected by Admin.
                                                    </p>
                                                </div>

                                            {{-- Admin approved — Superadmin can act --}}
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
                                                        <button class="btn btn-success" onclick="updateStatus({{ $recruiter->id }}, 'approved', this)">Yes, Approve</button>
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
                                                            <textarea id="adminRejectionReason" class="form-control" rows="3" placeholder="Enter reason..."></textarea>
                                                            <small id="adminRejectionReasonError" class="text-danger d-none"></small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button class="btn btn-danger" onclick="submitRejection({{ $recruiter->id }}, 'rejected', 'adminRejectionReason', this)">Yes, Reject</button>
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
                                                        <button class="btn btn-success" onclick="updateStatus({{ $recruiter->id }}, 'superadmin_approved', this)">Yes, Approve</button>
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
                                                            <textarea id="superRejectionReason" class="form-control" rows="3" placeholder="Enter reason..."></textarea>
                                                            <small id="superRejectionReasonError" class="text-danger d-none"></small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button class="btn btn-danger" onclick="submitRejection({{ $recruiter->id }}, 'superadmin_rejected', 'superRejectionReason', this)">Yes, Reject</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- jQuery + AJAX -->
                                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                        <script>
                                            function updateStatus(recruiterCompanyId, status, btn) {
                                                const originalText = btn.innerHTML;
                                                btn.disabled = true;
                                                btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Processing...`;

                                                $.post('{{ route("admin.recruiter.updateStatus") }}', {
                                                    _token: '{{ csrf_token() }}',
                                                    company_id: recruiterCompanyId,
                                                    status: status
                                                }).done(() => {
                                                    $('.modal').modal('hide');
                                                    location.reload();
                                                }).fail(() => {
                                                    btn.disabled = false;
                                                    btn.innerHTML = originalText;
                                                    alert('Something went wrong.');
                                                });
                                            }

                                            function submitRejection(recruiterCompanyId, status, reasonId, btn) {
                                                const reason = document.getElementById(reasonId).value.trim();
                                                const errorDiv = document.getElementById(reasonId + "Error");

                                                if (!reason) {
                                                    errorDiv.textContent = "Reason is required.";
                                                    errorDiv.classList.remove("d-none");
                                                    return;
                                                } else {
                                                    errorDiv.classList.add("d-none");
                                                }

                                                const originalText = btn.innerHTML;
                                                btn.disabled = true;
                                                btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Processing...`;

                                                $.post('{{ route("admin.recruiter.updateStatus") }}', {
                                                    _token: '{{ csrf_token() }}',
                                                    company_id: recruiterCompanyId,
                                                    status: status,
                                                    reason: reason
                                                }).done(() => {
                                                    $('.modal').modal('hide');
                                                    location.reload();
                                                }).fail((xhr) => {
                                                    btn.disabled = false;
                                                    btn.innerHTML = originalText;

                                                    if (xhr.responseJSON?.message) {
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
                            <div class="card">
                                <div class="header">
                                    <h2>Recruiter's Information</h2>
                                </div>
                                <div class="body">
                                    <form>
                                        <div class="row">
                                            <div class="col-md-12 form-group">
                                                <label>Name</label>
                                                <input readonly type="text" class="form-control" value="{{ $recruiter->name }}">
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <label>Email address</label>
                                                <input readonly type="text" class="form-control" value="{{ $recruiter->email }}">
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <label>National ID</label>
                                                <input readonly type="text" class="form-control" value="{{ $recruiter->national_id }}">
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <label>Phone Number</label>
                                                <input readonly type="text" class="form-control" value="{{ $recruiter->phone }}">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-8 col-lg-8 col-md-7">
                            <div class="card">
                                <div class="header">
                                    <h2>Company Information</h2>
                                        <div class="body">
                                            <!-- Tab navigation -->
                                            <ul class="nav nav-tabs2 mb-4" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-toggle="tab" href="#company">Compay Details</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-toggle="tab" href="#additional">Additional Information</a>
                                                </li>
                                            </ul>

                                            <!-- Tab content -->
                                            <div class="tab-content">

                                                <!-- Education -->
                                               <div class="tab-pane show active" id="company">
                                                    <form>
                                                        @if (is_null($recruiter->company)) 
                                                            <p class="text-muted">No company details found.</p>
                                                        @else
                                                            <div class="row">
                                                                <div class="col-md-12 form-group">
                                                                    <label>Company name</label>
                                                                    <input readonly type="text" class="form-control" value="{{ $recruiter->company->company_name }}">
                                                                </div>

                                                                <div class="col-md-6 form-group">
                                                                    <label>Company website</label>
                                                                    <input readonly type="text" class="form-control" value="{{ $recruiter->company->company_website }}">
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Company location</label>
                                                                    <input readonly type="text" class="form-control" value="{{ $recruiter->company->company_city }}">
                                                                </div>

                                                                <div class="col-md-12 form-group">
                                                                    <label>Company address</label>
                                                                    <input readonly type="text" class="form-control" value="{{ $recruiter->company->company_address }}">
                                                                </div>

                                                                <div class="col-md-6 form-group">
                                                                    <label>Business email</label>
                                                                    <input readonly type="text" class="form-control" value="{{ $recruiter->company->business_email }}">
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Company phone number</label>
                                                                    <div class="input-group">
                                                                        <input readonly type="text" class="form-control" value="{{ $recruiter->company->phone_code }}">
                                                                        <input readonly type="text" class="form-control" value="{{ $recruiter->company->company_phone_number }}">
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6 form-group">
                                                                    <label>Number of employees</label>
                                                                    <input readonly type="text" class="form-control" value="{{ $recruiter->company->no_of_employee }}">
                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <label>Industry type</label>
                                                                    <input readonly type="text" class="form-control" value="{{ $recruiter->company->industry_type }}">
                                                                </div>

                                                                <div class="col-md-12 form-group">
                                                                    <label>CR number (Company registration number)</label>
                                                                    <input readonly type="text" class="form-control" value="{{ $recruiter->company->registration_number }}">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </form>

                                                    {{-- ✅ Show sub-recruiters under this recruiter --}}
                                                    <div class="mt-4">
                                                        <h5>Sub Recruiters</h5>
                                                        @php
                                                            $subRecruiters = \App\Models\Recruiters::where('recruiter_of', $recruiter->id)->get();
                                                        @endphp

                                                        @if ($subRecruiters->isEmpty())
                                                            <p class="text-muted">No sub-recruiters found under this recruiter.</p>
                                                        @else
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Name</th>
                                                                        <th>Email</th>
                                                                        <th>Role</th>
                                                                        <th>Mobile No.</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($subRecruiters as $index => $sub)
                                                                        <tr>
                                                                            <td>{{ $loop->iteration }}</td>
                                                                            <td>{{ $sub->name }}</td>
                                                                            <td>{{ $sub->email }}</td>
                                                                            <td>Sub Recruiter</td>
                                                                            <td>
                                                                                @if ($sub->mobile_no)
                                                                                    {{ $sub->mobile_no }}
                                                                                @else
                                                                                    <span class="text-muted">Not provided</span>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        @endif
                                                    </div>

                                                </div>


                                            <!-- Additional Info -->
                                            <div class="tab-pane fade" id="additional">
                                                <form>
                                                    @if ($additioninfos->isEmpty())
                                                        <p class="text-muted">No additional documents found.</p>
                                                    @else
                                                        <div class="row">
                                                            @foreach($additioninfos as $info)
                                                                @if($info->doc_type == 'company_reg_document')
                                                                    <div class="col-md-12 form-group d-flex align-items-center">
                                                                        <label class="w-100">Uploaded Registration document</label>
                                                                        <input readonly type="text" class="form-control me-2" value="{{ $info->document_name }}">
                                                                        <a href="{{ $info->document_path }}" target="_blank" class="btn btn-danger">View</a>
                                                                    </div>
                                                                @elseif($info->doc_type == 'company_profile_picture')
                                                                    <div class="col-md-12 form-group d-flex align-items-center">
                                                                        <label class="w-100">Uploaded Company Profile Picture</label>
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
                                    <h2>Recruiter Subscriptions</h2>
                                </div>
                                <div class="body">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-lg-12">

                                                @if(count($subscriptionPlans) > 0)
                                                    @foreach ($subscriptionPlans as $index => $plan)
                                                        @php
                                                            $isExpired = \Carbon\Carbon::parse($plan->end_date)->isPast();
                                                            $isActive  = $plan->active_subscription_plan_id == $plan->id && !$isExpired;
                                                        @endphp

                                                        <div class="card p-3 mb-4 shadow-sm {{ $index > 0 ? 'd-none extra-subscription' : '' }} 
                                                            {{ $isActive ? 'border-success' : ($isExpired ? 'border-danger' : '') }}">
                                                            
                                                            <div class="d-flex flex-column">
                                                                <h5 class="fw-bold mb-1">
                                                                    {{ $plan->title }}
                                                                    @if($isActive)
                                                                        <span class="badge bg-success ms-2 text-light">Active</span>
                                                                    @elseif($isExpired)
                                                                        <span class="badge bg-danger ms-2 text-light">Expired</span>
                                                                    @endif
                                                                </h5>
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