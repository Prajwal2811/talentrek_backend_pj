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
                                <span>JustDo Jobseeker's Profile</span>
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
                                            <h4 class="mb-0">{{ $jobseeker->name }}</h4>
                                            <span class="text-light">{{ $jobseeker->city }}</span>
                                            <!-- <p class="mb-0"><span>Posts: <strong>321</strong></span> <span>Followers: <strong>4,230</strong></span> <span>Following: <strong>560</strong></span></p> -->
                                        </div>
                                    </div>
                                    <div>
                                        @php
                                            $status = $jobseeker->admin_status;
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
                                                        <button class="btn btn-success" onclick="updateStatus({{ $jobseeker->id }}, 'approved', this)">Yes, Approve</button>
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
                                                        <button class="btn btn-danger" onclick="submitRejection({{ $jobseeker->id }}, 'rejected', 'adminRejectionReason', this)">Yes, Reject</button>
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
                                                        <button class="btn btn-success" onclick="updateStatus({{ $jobseeker->id }}, 'superadmin_approved', this)">Yes, Approve</button>
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
                                                        <button class="btn btn-danger" onclick="submitRejection({{ $jobseeker->id }}, 'superadmin_rejected', 'superRejectionReason', this)">Yes, Reject</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- JS -->
                                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                        <script>
                                            function updateStatus(jobseekerId, status, btn) {
                                                const originalText = btn.innerHTML;
                                                btn.disabled = true;
                                                btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...`;

                                                $.post('{{ route("admin.jobseeker.updateStatus") }}', {
                                                    _token: '{{ csrf_token() }}',
                                                    jobseeker_id: jobseekerId,
                                                    status: status
                                                }).done(() => {
                                                    $('.modal').modal('hide');
                                                    location.reload();
                                                }).fail(() => {
                                                    btn.disabled = false;
                                                    btn.innerHTML = originalText;
                                                });
                                            }

                                            function submitRejection(jobseekerId, status, reasonId, btn) {
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
                                                    jobseeker_id: jobseekerId,
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
                                                <input readonly type="text" class="form-control" value="{{ $jobseeker->name }}">
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <label>Gender</label>
                                                <input readonly type="text" class="form-control" value="{{ $jobseeker->gender }}">
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <label>Email address</label>
                                                <input readonly type="text" class="form-control" value="{{ $jobseeker->email }}">
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <label>Date of birth</label>
                                                @php
                                                    $date = \Carbon\Carbon::parse($jobseeker->date_of_birth);
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
                                                <input readonly type="text" class="form-control" value="{{ $jobseeker->phone_code ? $jobseeker->phone_code . '-' . $jobseeker->phone_number : $jobseeker->phone_number }}">
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <label>Address</label>
                                                <textarea readonly type="text" class="form-control">{{ $jobseeker->address }}</textarea>
                                            </div>
                                            <div class="col-md-12 form-group">
                                                <label>Location</label>
                                                <input readonly type="text" class="form-control" value="{{ $jobseeker->city }}">
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
                                                    <a class="nav-link" data-toggle="tab" href="#skills">Skills & Training</a>
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
                                                <div class="tab-pane fade" id="skills">
                                                    <form>
                                                        @if ($skills->isEmpty())
                                                            <p class="text-muted">No skills or training details found.</p>
                                                        @else
                                                            @foreach ($skills as $skill)
                                                                <div class="row">
                                                                    <div class="col-md-6 form-group">
                                                                        <label>Skills</label>
                                                                        <input readonly type="text" class="form-control" value="{{ $skill->skills }}">
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


                            @php
                                use App\Models\JobseekerTrainingMaterialPurchase;

                                $courses = JobseekerTrainingMaterialPurchase::with(['material.reviews'])
                                                                            ->where('jobseeker_id', $jobseeker->id)
                                                                            ->get();

                                $trainerImage = App\Models\AdditionalInfo::where('doc_type', 'profile_picture')
                                                                        ->where('user_id', $jobseeker->id)
                                                                        ->first();
                            @endphp

                            <div class="card">
                                <div class="header">
                                    <h2>Jobseeker Trainings</h2>
                                </div>
                                <div class="body">
                                    <div class="col-lg-12">
                                        @forelse($courses as $index => $purchase)
                                            @php
                                                $material = $purchase->material;
                                                $reviews = $material->reviews ?? collect();
                                                $trainerName = App\Models\Trainers::where('id', $material->trainer_id)->value('name');
                                                $lessons = $material->lesson_count;
                                                $duration = $material->duration .'hrs';
                                                $level = $material->level;
                                                $rating = $material->rating ?? 4;
                                                $img = $material->thumbnail_file_path;
                                                $assingBatch = \App\Models\TrainingBatch::where('training_material_id', $material->id)
                                                                                        ->where('id', $purchase->batch_id)
                                                                                        ->first();
                                            @endphp

                                            <div class="card d-flex flex-row p-3 mb-3 {{ $index >= 1 ? 'd-none extra-course' : '' }}">
                                                <img src="{{ asset($img) }}" alt="{{ $material->training_title }}"
                                                    class="img-fluid rounded" style="width: 200px; object-fit: cover;">
                                                    <div class="ps-4 d-flex flex-column justify-content-between flex-grow-1">
                                                        <div>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                            <h5 class="fw-bold mb-1 mb-0">{{ $material->training_title }}</h5>
                                                            @php
                                                                $batchStatus = strtolower($purchase->batchStatus); // e.g., 'pending', 'completed', 'in process'
                                                                $badgeClass = match($batchStatus) {
                                                                    'completed' => 'bg-success text-white',
                                                                    'pending' => 'bg-warning text-dark',
                                                                    'in process' => 'bg-info text-white',
                                                                    default => 'bg-secondary'
                                                                };
                                                            @endphp
                                                            <span class="badge {{ $badgeClass }} text-capitalize">
                                                                {{ $batchStatus }}
                                                            </span>
                                                        </div>
                                                            
                                                        <p class="text-muted mb-2">{{ $material->training_sub_title ?? 'No description available.' }}</p>
                                                        @php
                                                            $averageRating = $reviews->avg('ratings');
                                                            $roundedRating = round($averageRating);
                                                        @endphp


                                                        <div class="d-flex align-items-center mb-2">
                                                            <span class="text-warning me-1">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($i <= $roundedRating)
                                                                        ★
                                                                    @else
                                                                        ☆
                                                                    @endif
                                                                @endfor
                                                            </span>
                                                            <span class="text-muted">
                                                                ({{ number_format($averageRating, 1) }}/5 from {{ $reviews->count() }} review{{ $reviews->count() === 1 ? '' : 's' }})
                                                            </span>
                                                        </div>
                                                    </div>
                                                        <div class="d-flex align-items-center justify-content-between mt-3">
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ $trainerImage->document_path }}" alt="{{ $trainerName }}"
                                                                    class="rounded-circle me-2" style="width: 35px;">
                                                                <span class="fw-semibold">{{ $trainerName }}</span>
                                                            </div>
                                                            <div class="d-flex align-items-center text-muted gap-3">
                                                            <div>
                                                                    <i data-feather="book-open" class="me-1"></i>
                                                                   @if(in_array($material->training_type, ['online', 'classroom']))
                                                                        @if($assingBatch)
                                                                            {{ $assingBatch->batch_no . ' (' . \Carbon\Carbon::parse($assingBatch->start_date)->format('d M, Y') . ')' }}
                                                                        @else
                                                                            <span class="text-danger">No Batch Assigned</span>
                                                                        @endif
                                                                    @else
                                                                        {{ $lessons }} lesson{{ $lessons != 1 ? 's' : '' }}
                                                                    @endif

                                                                </div>

                                                                <div><i data-feather="clock" class="me-1"></i>{{ $purchase->training_type }}</div>
                                                                <div><i data-feather="bar-chart-2" class="me-1"></i>{{ $level }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>
                                        @empty
                                            <div class="alert alert-warning">No trainings purchased yet.</div>
                                        @endforelse

                                        @if($courses->count() > 1)
                                            <div class="text-center mt-4">
                                                <button class="btn btn-primary" onclick="toggleCourses(this)">View More</button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>


                            <script>
                                function toggleCourses(button) {
                                    const extraCourses = document.querySelectorAll('.extra-course');
                                    extraCourses.forEach(el => el.classList.toggle('d-none'));
                                    button.textContent = button.textContent === 'View More' ? 'View Less' : 'View More';
                                }
                            </script>
                            <script>feather.replace();</script>



                            

                        @php
                            $mentorships = \App\Models\BookingSession::with([
                                'mentor.reviews', 'mentor.profilePicture', 'mentor.experiences'
                            ])
                            ->where('jobseeker_id', auth()->user('jobseeker')->id)
                            ->where('user_type', 'mentor')
                            ->whereHas('mentor.profilePicture')
                            ->get();
                        @endphp

                        <div class="card">
                            <div class="header">
                                <h2>Jobseeker Mentorship</h2>
                            </div>
                            <div class="body">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-lg-12">

                                            @foreach ($mentorships as $index => $session)
                                                @php
                                                    $mentor = $session->mentor;
                                                    $reviews = $mentor?->reviews ?? collect();
                                                    $experiences = $mentor?->experiences ?? collect();
                                                    $averageRating = $reviews->avg('ratings') ?? 0;
                                                    $totalReviews = $reviews->count();
                                                    $roundedRating = round($averageRating);
                                                    $stars = str_repeat('★ ', $roundedRating) . str_repeat('☆ ', 5 - $roundedRating);
                                                    $currentExp = $experiences->firstWhere('end_to', null) ?? $experiences->sortByDesc('end_to')->first();
                                                    $designation = $currentExp?->job_role ?? 'No designation available';
                                                    $zoomLink = $session->zoom_join_url ?? null;
                                                    $slotMode = $session->slot_mode;
                                                    $address = $mentor?->address ?? 'Address not available';
                                                    $image = $mentor?->profilePicture?->document_path ?? asset('default-avatar.png');
                                                @endphp

                                                <div class="card d-flex flex-row align-items-start p-3 mb-4 shadow-sm">
                                                    <img src="{{ $image }}" alt="Mentor Image"
                                                        class="img-fluid rounded"
                                                        style="width: 200px; height: 140px; object-fit: cover;">
                                                    <div class="ps-4 d-flex flex-column flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <h5 class="fw-bold mb-1">{{ $mentor?->name }}</h5>
                                                                <p class="text-muted mb-2">{{ $designation }}</p>
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <span class="text-warning me-2">{!! $stars !!}</span>
                                                                    <span class="text-muted">({{ number_format($averageRating, 1) }}/5 from {{ $totalReviews }} reviews)</span>
                                                                </div>
                                                            </div>
                                                            <div class="text-end">
                                                                @if ($slotMode === 'online' && $zoomLink)
                                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#zoomModal{{ $index }}">Join Meet</button>
                                                                @elseif ($slotMode === 'offline')
                                                                    <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addressModal{{ $index }}">View Address</button>
                                                                @else
                                                                    <p class="text-danger small mt-1">Link not available</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                {{-- Zoom Modal --}}
                                                @if ($slotMode === 'online' && $zoomLink)
                                                    <div class="modal fade" id="zoomModal{{ $index }}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Join Zoom Meeting with {{ $mentor->name }}</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Click the link below to join:</p>
                                                                    <a href="{{ $zoomLink }}" target="_blank" class="text-primary text-break">{{ $zoomLink }}</a>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                                    <a href="{{ $zoomLink }}" target="_blank" class="btn btn-primary btn-sm">Join Now</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                {{-- Address Modal --}}
                                                @if ($slotMode === 'offline')
                                                    <div class="modal fade" id="addressModal{{ $index }}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">{{ $mentor->name }}'s Address</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p class="text-dark">{{ $address }}</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                            @php
                                $assessments = \App\Models\BookingSession::with([
                                            'assessor.reviews', 'assessor.profilePicture', 'assessor.experiences'
                                        ])->where('jobseeker_id', $jobseeker->id)
                                        ->where('user_type', 'assessor')
                                        ->get();
                                // echo "<pre>"; print_r($assessments); 
                            @endphp

                            <div class="card">
                                <div class="header">
                                    <h2>Jobseeker Assessments</h2>
                                </div>
                                <div class="body">
                                    <div class="col-lg-12">
                                        @foreach ($assessments as $index => $session)
                                            @php
                                                $assessor = $session->assessor;
                                                $reviews = $assessor?->reviews ?? collect();
                                                $experiences = $assessor?->experiences ?? collect();
                                                $averageRating = $reviews->avg('ratings') ?? 0;
                                                $totalReviews = $reviews->count();
                                                $roundedRating = round($averageRating);
                                                $stars = str_repeat('★ ', $roundedRating) . str_repeat('☆ ', 5 - $roundedRating);
                                                $currentExp = $experiences->firstWhere('end_to', null) ?? $experiences->sortByDesc('end_to')->first();
                                                $designation = $currentExp?->job_role ?? 'No designation available';
                                                $zoomLink = $session->zoom_join_url ?? null;
                                                $slotMode = $session->slot_mode;
                                                $address = $assessor?->address ?? 'Address not available';
                                                $image = $assessor?->profilePicture?->document_path ?? asset('default-avatar.png');
                                            @endphp

                                            <div class="card d-flex flex-row align-items-start p-3 mb-4 shadow-sm">
                                                <img src="{{ $image }}" alt="Assessor Image"
                                                    class="img-fluid rounded"
                                                    style="width: 200px; height: 140px; object-fit: cover;">
                                                <div class="ps-4 d-flex flex-column flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h5 class="fw-bold mb-1">{{ $session->name }}</h5>
                                                            <p class="text-muted mb-2">{{ $designation }}</p>
                                                            <div class="d-flex align-items-center mb-2">
                                                                <span class="text-warning me-2">{!! $stars !!}</span>
                                                                <span class="text-muted">({{ number_format($averageRating, 1) }}/5 from {{ $totalReviews }} reviews)</span>
                                                            </div>
                                                        </div>
                                                        <div class="text-end">
                                                            @if ($slotMode === 'online' && $zoomLink)
                                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#zoomModal{{ $index }}">Join Meet</button>
                                                            @elseif ($slotMode === 'offline')
                                                                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addressModal{{ $index }}">View Address</button>
                                                            @else
                                                                <p class="text-danger small mt-1">Link not available</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Zoom Modal --}}
                                            @if ($slotMode === 'online' && $zoomLink)
                                                <div class="modal fade" id="zoomModal{{ $index }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Join Zoom Meeting with {{ $assessor->name }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Click the link below to join:</p>
                                                                <a href="{{ $zoomLink }}" target="_blank" class="text-primary text-break">{{ $zoomLink }}</a>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                                <a href="{{ $zoomLink }}" target="_blank" class="btn btn-primary btn-sm">Join Now</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Address Modal --}}
                                            @if ($slotMode === 'offline')
                                                <div class="modal fade" id="addressModal{{ $index }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">{{ $assessor->name }}'s Address</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p class="text-dark">{{ $address }}</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach



                                        @if ($assessments->count() > 1)
                                            <div class="text-center mt-4">
                                                <button class="btn btn-primary" onclick="toggleAssessments()" id="toggleButton">View More</button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Toggle Script -->
                            <script>
                                function toggleAssessments() {
                                    const items = document.querySelectorAll('.extra-assessment');
                                    const btn = document.getElementById('toggleButton');

                                    items.forEach(el => el.classList.toggle('d-none'));

                                    btn.textContent = btn.textContent === 'View More' ? 'View Less' : 'View More';
                                }
                            </script>




                            <div class="card">
                                <div class="header">
                                    <h2>Jobseeker Coaching</h2>
                                </div>
                                <div class="body">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-lg-12">

                                                <!-- Coach Card 1 -->
                                                <div class="card d-flex flex-row align-items-start p-3 mb-4 shadow-sm">
                                                    <img src="../images/gallery/finished-quiz.png" alt="Coach Image"
                                                        class="img-fluid rounded"
                                                        style="width: 200px; height: 140px; object-fit: cover;">
                                                    <div
                                                        class="ps-4 d-flex flex-column justify-content-center flex-grow-1">
                                                        <h5 class="fw-bold mb-1">Julia Maccarthy</h5>
                                                        <p class="text-muted mb-2">Career Coach</p>
                                                        <div class="d-flex align-items-center">
                                                            <span class="text-warning me-2">★ ★ ★ ★ ☆</span>
                                                            <span class="text-muted">(4/5 Rating)</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Hidden More Coaches -->
                                                <div id="moreCoaches" class="d-none">

                                                    <!-- Coach Card 2 -->
                                                    <div
                                                        class="card d-flex flex-row align-items-start p-3 mb-4 shadow-sm">
                                                        <img src="../images/gallery/graphic-design.png"
                                                            alt="Coach Image" class="img-fluid rounded"
                                                            style="width: 200px; height: 140px; object-fit: cover;">
                                                        <div
                                                            class="ps-4 d-flex flex-column justify-content-center flex-grow-1">
                                                            <h5 class="fw-bold mb-1">John Smith</h5>
                                                            <p class="text-muted mb-2">Leadership Consultant</p>
                                                            <div class="d-flex align-items-center">
                                                                <span class="text-warning me-2">★ ★ ★ ★ ★</span>
                                                                <span class="text-muted">(5/5 Rating)</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Coach Card 3 -->
                                                    <div
                                                        class="card d-flex flex-row align-items-start p-3 mb-4 shadow-sm">
                                                        <img src="../images/gallery/ui-ux.png" alt="Coach Image"
                                                            class="img-fluid rounded"
                                                            style="width: 200px; height: 140px; object-fit: cover;">
                                                        <div
                                                            class="ps-4 d-flex flex-column justify-content-center flex-grow-1">
                                                            <h5 class="fw-bold mb-1">Sara Lee</h5>
                                                            <p class="text-muted mb-2">Startup Advisor</p>
                                                            <div class="d-flex align-items-center">
                                                                <span class="text-warning me-2">★ ★ ★ ★ ☆</span>
                                                                <span class="text-muted">(4.5/5 Rating)</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <!-- View More Button -->
                                                <div class="text-center mt-4">
                                                    <button class="btn btn-primary" id="toggleCoachButton"
                                                        onclick="toggleCoaches()">View More</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    function toggleCoaches() {
                                        const moreCoaches = document.getElementById("moreCoaches");
                                        const button = document.getElementById("toggleCoachButton");

                                        if (moreCoaches.classList.contains("d-none")) {
                                            moreCoaches.classList.remove("d-none");
                                            button.textContent = "View Less";
                                        } else {
                                            moreCoaches.classList.add("d-none");
                                            button.textContent = "View More";
                                        }
                                    }
                                </script>
                            </div>



                            <div class="card">
                                <div class="header">
                                    <h2>Jobseeker Subscriptions</h2>
                                </div>
                                <div class="body">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-lg-12">

                                                <!-- Subscription Card 1 -->
                                                <div class="card p-3 mb-4 shadow-sm">
                                                    <div class="d-flex flex-column">
                                                        <h5 class="fw-bold mb-1">Basic Plan</h5>
                                                        <p class="text-muted mb-2">Access limited job listings and
                                                            profile views</p>
                                                        <div class="mb-2">
                                                            <strong>Duration:</strong> 1 Month<br>
                                                            <strong>Purchased On:</strong> 01 June 2025<br>
                                                            <strong>Expired On:</strong> 30 June 2025
                                                        </div>
                                                        <span
                                                            class="badge bg-secondary text-white small align-self-start px-2 py-1">₹499/month</span>
                                                    </div>
                                                </div>

                                                <!-- Hidden More Subscriptions -->
                                                <div id="moreSubscriptions" class="d-none">

                                                    <!-- Subscription Card 2 -->
                                                    <div class="card p-3 mb-4 shadow-sm">
                                                        <div class="d-flex flex-column">
                                                            <h5 class="fw-bold mb-1">Premium Plan</h5>
                                                            <p class="text-muted mb-2">Full job access, coaching
                                                                sessions & resume support</p>
                                                            <div class="mb-2">
                                                                <strong>Duration:</strong> 3 Months<br>
                                                                <strong>Purchased On:</strong> 15 May 2025<br>
                                                                <strong>Expired On:</strong> 14 August 2025
                                                            </div>
                                                            <span
                                                                class="badge bg-success text-white small align-self-start px-2 py-1">₹1,499/month</span>
                                                        </div>
                                                    </div>

                                                    <!-- Subscription Card 3 -->
                                                    <div class="card p-3 mb-4 shadow-sm">
                                                        <div class="d-flex flex-column">
                                                            <h5 class="fw-bold mb-1">Enterprise Plan</h5>
                                                            <p class="text-muted mb-2">Advanced hiring tools and support
                                                                for startups</p>
                                                            <div class="mb-2">
                                                                <strong>Duration:</strong> 1 Year<br>
                                                                <strong>Purchased On:</strong> 01 January 2025<br>
                                                                <strong>Expired On:</strong> 31 December 2025
                                                            </div>
                                                            <span
                                                                class="badge bg-warning text-dark small align-self-start px-2 py-1">₹4,999/month</span>
                                                        </div>
                                                    </div>

                                                </div>

                                                <!-- View More Button -->
                                                <div class="text-center mt-4">
                                                    <button class="btn btn-primary" id="toggleButtonSub"
                                                        onclick="toggleSubscriptions()">View More</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- JS Toggle -->
                                <script>
                                    function toggleSubscriptions() {
                                        const moreSubscriptions = document.getElementById("moreSubscriptions");
                                        const button = document.getElementById("toggleButtonSub");

                                        if (moreSubscriptions.classList.contains("d-none")) {
                                            moreSubscriptions.classList.remove("d-none");
                                            button.textContent = "View Less";
                                        } else {
                                            moreSubscriptions.classList.add("d-none");
                                            button.textContent = "View More";
                                        }
                                    }
                                </script>
                            </div>



                            <div class="card">
                                <div class="header">
                                    <h2>Jobseeker Payments</h2>
                                </div>
                                <div class="body">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-lg-12">

                                                <!-- Payment Card 1 -->
                                                <div class="card p-3 mb-4 shadow-sm">
                                                    <div class="d-flex flex-column">
                                                        <h5 class="fw-bold mb-2">Payment #001</h5>
                                                        <div class="mb-2">
                                                            <strong>Paid To Date:</strong> 10 June 2025<br>
                                                            <strong>Amount:</strong> ₹1,499<br>
                                                            <strong>Payment Status:</strong>
                                                            <span
                                                                class="badge bg-success text-white small px-2 py-1">Paid</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Hidden More Payments -->
                                                <div id="morePayments" class="d-none">

                                                    <!-- Payment Card 2 -->
                                                    <div class="card p-3 mb-4 shadow-sm">
                                                        <div class="d-flex flex-column">
                                                            <h5 class="fw-bold mb-2">Payment #002</h5>
                                                            <div class="mb-2">
                                                                <strong>Paid To Date:</strong> 01 May 2025<br>
                                                                <strong>Amount:</strong> ₹499<br>
                                                                <strong>Payment Status:</strong>
                                                                <span
                                                                    class="badge bg-warning text-dark small px-2 py-1">Pending</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Payment Card 3 -->
                                                    <div class="card p-3 mb-4 shadow-sm">
                                                        <div class="d-flex flex-column">
                                                            <h5 class="fw-bold mb-2">Payment #003</h5>
                                                            <div class="mb-2">
                                                                <strong>Paid To Date:</strong> 25 April 2025<br>
                                                                <strong>Amount:</strong> ₹999<br>
                                                                <strong>Payment Status:</strong>
                                                                <span
                                                                    class="badge bg-danger text-white small px-2 py-1">Failed</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                <!-- View More Button -->
                                                <div class="text-center mt-4">
                                                    <button class="btn btn-primary" id="toggleButtonPay"
                                                        onclick="togglePayments()">View More</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- JS Toggle -->
                                <script>
                                    function togglePayments() {
                                        const morePayments = document.getElementById("morePayments");
                                        const button = document.getElementById("toggleButtonPay");

                                        if (morePayments.classList.contains("d-none")) {
                                            morePayments.classList.remove("d-none");
                                            button.textContent = "View Less";
                                        } else {
                                            morePayments.classList.add("d-none");
                                            button.textContent = "View More";
                                        }
                                    }
                                </script>
                            </div>


                            <div class="card">
                                <div class="header">
                                    <h2>Jobseeker Certifications</h2>
                                </div>
                                <div class="body">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-lg-12">

                                                <!-- Certification Card 1 -->
                                                <div class="card p-3 mb-3 shadow-sm">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="fw-bold mb-1">Full Stack Web Development</h6>
                                                            <p class="text-muted mb-0"><strong>Issued On:</strong> 15
                                                                April 2025</p>
                                                        </div>
                                                        <button class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#certModal1">View</button>
                                                    </div>
                                                </div>

                                                <!-- Certification Card 2 -->
                                                <div class="card p-3 mb-3 shadow-sm">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="fw-bold mb-1">UI/UX Design Specialization</h6>
                                                            <p class="text-muted mb-0"><strong>Issued On:</strong> 01
                                                                March 2025</p>
                                                        </div>
                                                        <button class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#certModal2">View</button>
                                                    </div>
                                                </div>

                                                <!-- Certification Card 3 -->
                                                <div class="card p-3 mb-3 shadow-sm">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="fw-bold mb-1">Advanced Data Analytics</h6>
                                                            <p class="text-muted mb-0"><strong>Issued On:</strong> 10
                                                                January 2024</p>
                                                        </div>
                                                        <button class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#certModal3">View</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modals for Certificates -->

                                <!-- Modal 1 -->
                                <div class="modal fade" id="certModal1" tabindex="-1" aria-labelledby="certModal1Label"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="certModal1Label">Full Stack Web Development
                                                </h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="https://udemy-certificate.s3.amazonaws.com/image/UC-c2013095-ec1b-4b2b-b77e-07a330160cb8.jpg?v=1719901769000"
                                                    alt="Certificate" class="img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal 2 -->
                                <div class="modal fade" id="certModal2" tabindex="-1" aria-labelledby="certModal2Label"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="certModal2Label">UI/UX Design Specialization
                                                </h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="https://udemy-certificate.s3.amazonaws.com/image/UC-c2013095-ec1b-4b2b-b77e-07a330160cb8.jpg?v=1719901769000"
                                                    alt="Certificate" class="img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal 3 -->
                                <div class="modal fade" id="certModal3" tabindex="-1" aria-labelledby="certModal3Label"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="certModal3Label">Advanced Data Analytics
                                                </h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="https://udemy-certificate.s3.amazonaws.com/image/UC-c2013095-ec1b-4b2b-b77e-07a330160cb8.jpg?v=1719901769000"
                                                    alt="Certificate" class="img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    @include('admin.componants.footer')