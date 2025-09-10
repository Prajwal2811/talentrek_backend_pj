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
                <div class="container-fluid py-4">
                    <div class="block-header">
                        <div class="row clearfix">
                            <div class="col-xl-5 col-md-5 col-sm-12">
                                <h1>Hi, {{  Auth()->user()->name }}!</h1>
                                <span>JustSee Training Material Detail,</span>
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
                                        {{-- <div class="details">
                                            <h4 class="mb-0">{{ $course->name }}</h4>
                                            <span class="text-light">{{ $course->city }}</span>
                                            <!-- <p class="mb-0"><span>Posts: <strong>321</strong></span> <span>Followers: <strong>4,230</strong></span> <span>Following: <strong>560</strong></span></p> -->
                                        </div> --}}
                                    </div>
                                    <div>
                                        @php
                                            $status = $course->admin_status;
                                            $userRole = auth()->user()->role;
                                        @endphp

                                    <!-- Status Buttons -->
                                    @if(!$status && $userRole === 'admin')
                                        <button class="btn btn-default me-2" data-bs-toggle="modal" data-bs-target="#approveModal">Approve</button>
                                        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject</button>

                                    @elseif($userRole === 'superadmin')
                                        @if(!$status)
                                            <span class="badge bg-warning">Admin has not yet responded</span>
                                        @elseif($status === 'rejected')
                                            <div class="text-end">
                                                <span class="badge bg-danger mb-2">Admin Rejected</span>
                                                <p class="text-light m-0"><strong>Superadmin cannot override rejection.</strong></p>
                                            </div>
                                        @elseif($status === 'approved')
                                            <span class="badge bg-info me-2">Admin Approved</span>
                                            <button class="btn btn-default me-2" data-bs-toggle="modal" data-bs-target="#superApproveModal">Super Approve</button>
                                            <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#superRejectModal">Super Reject</button>
                                        @elseif(Str::startsWith($status, 'superadmin_'))
                                            @php
                                                $badgeClass = Str::contains($status, 'approved') ? 'success' : 'danger';
                                            @endphp
                                            <span class="badge bg-{{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                                        @endif

                                    @elseif(Str::startsWith($status, 'superadmin_'))
                                        @php
                                            $badgeClass = Str::contains($status, 'approved') ? 'success' : 'danger';
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>

                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($status) }}</span>
                                    @endif
                                    <!-- Admin Approve Modal -->
                                    <div class="modal fade" id="approveModal" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content text-dark">
                                        <div class="modal-header"><h5 class="modal-title">Confirm Approval</h5></div>
                                        <div class="modal-body">Are you sure you want to approve this profile?</div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button class="btn btn-success" onclick="updateStatus({{ $course->id }}, 'approved', this)">Yes, Approve</button>
                                        </div>
                                        </div>
                                    </div>
                                    </div>

                                    <!-- Admin Reject Modal -->
                                    <div class="modal fade" id="rejectModal" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content text-dark">
                                        <div class="modal-header"><h5 class="modal-title">Confirm Rejection</h5></div>
                                        <div class="modal-body">
                                            <label>Reason:</label>
                                            <textarea id="adminRejectionReason" class="form-control" rows="3"></textarea>
                                            <small id="adminRejectionReasonError" class="text-danger d-none"></small>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button class="btn btn-danger" onclick="submitRejection({{ $course->id }}, 'rejected', 'adminRejectionReason', this)">Yes, Reject</button>
                                        </div>
                                        </div>
                                    </div>
                                    </div>

                                    <!-- Superadmin Approve Modal -->
                                    <div class="modal fade" id="superApproveModal" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content text-dark">
                                        <div class="modal-header"><h5 class="modal-title">Confirm Super Approval</h5></div>
                                        <div class="modal-body">Are you sure you want to super approve this profile?</div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button class="btn btn-success" onclick="updateStatus({{ $course->id }}, 'superadmin_approved', this)">Yes, Approve</button>
                                        </div>
                                        </div>
                                    </div>
                                    </div>

                                    <!-- Superadmin Reject Modal -->
                                    <div class="modal fade" id="superRejectModal" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content text-dark">
                                        <div class="modal-header"><h5 class="modal-title">Confirm Super Rejection</h5></div>
                                        <div class="modal-body">
                                            <label>Reason:</label>
                                            <textarea id="superRejectionReason" class="form-control" rows="3"></textarea>
                                            <small id="superRejectionReasonError" class="text-danger d-none"></small>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button class="btn btn-danger" onclick="submitRejection({{ $course->id }}, 'superadmin_rejected', 'superRejectionReason', this)">Yes, Reject</button>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                    <script>
                                    function updateStatus(courseId, status, btn) {
                                        const originalText = btn.innerHTML;
                                        btn.disabled = true;
                                        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Processing...`;

                                        $.post('{{ route("admin.trainer.material.updateStatus") }}', {
                                            _token: '{{ csrf_token() }}',
                                            course_id: courseId,
                                            status: status
                                        }).done(() => {
                                            $('.modal').modal('hide');
                                            location.reload();
                                        }).fail(() => {
                                            btn.disabled = false;
                                            btn.innerHTML = originalText;
                                        });
                                    }

                                    function submitRejection(courseId, status, reasonId, btn) {
                                        const reason = document.getElementById(reasonId).value.trim();
                                        const errorDiv = document.getElementById(reasonId + "Error");

                                        if (!reason) {
                                            errorDiv.textContent = "Reason is required.";
                                            errorDiv.classList.remove("d-none");
                                            return;
                                        }

                                        errorDiv.classList.add("d-none");
                                        const originalText = btn.innerHTML;
                                        btn.disabled = true;
                                        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Processing...`;

                                        $.post('{{ route("admin.trainer.material.updateStatus") }}', {
                                            _token: '{{ csrf_token() }}',
                                            course_id: courseId,
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
                    </div>

                    
                    @if ($course->training_type  === 'online')
                        <div class="card p-4">
                            <!-- Course Header -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <img src="{{ $course->thumbnail_file_path }}" class="img-fluid rounded" alt="Course Image">
                                </div>
                                <div class="col-md-8">
                                    <h2 class="fw-bold">{{ $course->training_title }}</h2>
                                    <div class="d-flex flex-wrap small text-muted mb-2 align-items-center">
                                        <div class="me-2">{{ $course->lessons_count }} lessons</div>
                                        <div class="mx-2">|</div>
                                        <div class="me-2">{{ $course->duration }}</div>
                                        <div class="mx-2">|</div>
                                        <div class="me-2">{{ ucfirst($course->session_type) }}</div>
                                        <div class="mx-2">|</div>
                                        <div class="me-2">{{ $course->level }}</div>
                                        <div class="mx-2">|</div>
                                        <div class="fw-semibold">{{ $course->training_category }}</div>
                                    </div>
                                    <p class="text-muted">{{ $course->training_sub_title }}</p>
                                </div>
                            </div>

                            <!-- Tabs -->
                            <ul class="nav nav-tabs mb-4" id="courseTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview"
                                        type="button" role="tab">Overview</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="batches-tab" data-bs-toggle="tab" data-bs-target="#batches"
                                        type="button" role="tab">Batches</button>
                                </li>
                            </ul>

                            <div class="tab-content" id="courseTabsContent">
                               <!-- Overview Tab -->
                                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                                    <div class="border-0 rounded-4 p-4">
                                        <div class="">
                                            <h4 class="fw-bold mb-4 text-primary">
                                                <i class="bi bi-journal-bookmark-fill me-2"></i> Course Overview
                                            </h4>

                                            <div class="mb-3 d-flex align-items-start">
                                                <i class="bi bi-bullseye text-success fs-4 me-3"></i>
                                                <div>
                                                    <h6 class="fw-semibold mb-1">Objective</h6>
                                                    <p class="mb-0 text-muted">{{ $course->training_objective }}</p>
                                                </div>
                                            </div>

                                            <div class="mb-3 d-flex align-items-start">
                                                <i class="bi bi-file-earmark-text text-info fs-4 me-3"></i>
                                                <div>
                                                    <h6 class="fw-semibold mb-1">Description</h6>
                                                    <p class="mb-0 text-muted">{{ $course->training_descriptions }}</p>
                                                </div>
                                            </div>

                                            <div class="mb-3 d-flex align-items-start">
                                                <i class="bi bi-people-fill text-warning fs-4 me-3"></i>
                                                <div>
                                                    <h6 class="fw-semibold mb-1">Session Type</h6>
                                                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill shadow-sm">
                                                        {{ ucfirst($course->session_type) }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="mb-2 d-flex align-items-start">
                                                <i class="bi bi-currency-rupee text-danger fs-4 me-3"></i>
                                                <div>
                                                    <h6 class="fw-semibold mb-1">Price</h6>
                                                    <p class="h5 fw-bold text-success">₹{{ number_format($course->training_price) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Batch List Tab -->
                                <div class="tab-pane fade" id="batches" role="tabpanel">
                                    <h5 class="fw-bold">Batch List</h5>
                                    <table class="table table-hover table-striped align-middle shadow-sm">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>#</th>
                                                <th>Batch No</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Timing</th>
                                                <th>Duration</th>
                                                <th>Strength</th>
                                                <th>Days</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($batches as $index => $batch)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td><span class="badge bg-info text-dark">{{ $batch->batch_no }}</span></td>
                                                    <td>{{ \Carbon\Carbon::parse($batch->start_date)->format('d/m/Y') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($batch->end_date)->format('d/m/Y') }}</td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($batch->start_timing)->format('h:i A') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($batch->end_timing)->format('h:i A') }}
                                                    </td>
                                                    <td>{{ $batch->duration }}</td>
                                                    <td>{{ $batch->strength }}</td>
                                                    <td>
                                                        @php
                                                            $days = is_array($batch->days) ? $batch->days : json_decode($batch->days, true);
                                                        @endphp
                                                        @if(!empty($days))
                                                            @foreach($days as $day)
                                                                <span class="badge bg-light text-dark border">{{ $day }}</span>
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted">No batches available.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                    @elseif($course->training_type  === 'recorded')
                        <div class="card p-4">
                            <!-- Course Header -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <img src="{{ $course->thumbnail_file_path ?? 'https://dummyimage.com/600x400/000/fff' }}" class="img-fluid rounded" alt="Course Image">
                                </div>
                                <div class="col-md-8">
                                    <h2 class="fw-bold">{{ $course->training_title }}</h2>
                                    <div class="d-flex flex-wrap small text-muted mb-2 align-items-center">
                                        <div class="me-2">{{ $course->lessons_count }} lessons</div>
                                        <div class="mx-2">|</div>
                                        <div class="me-2">{{ $course->duration }}</div>
                                        <div class="mx-2">|</div>
                                        <div class="me-2">{{ ucfirst($course->session_type) }}</div>
                                        <div class="mx-2">|</div>
                                        <div class="me-2">{{ $course->level }}</div>
                                        <div class="mx-2">|</div>
                                        <div class="fw-semibold">{{ $course->training_category }}</div>
                                    </div>
                                    <p class="text-muted">{{ $course->training_sub_title }}.</p>
                                </div>
                            </div>

                            <!-- Tabs -->
                            <ul class="nav nav-tabs mb-4" id="courseTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview"
                                        type="button" role="tab">Overview</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="video-tab" data-bs-toggle="tab" data-bs-target="#video"
                                        type="button" role="tab">Course Video</button>
                                </li>
                            </ul>

                            <div class="tab-content" id="courseTabsContent">
                                <!-- Overview Tab -->
                                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                                    <h5 class="fw-bold">Course Overview</h5>
                                    <p>{{ $course->training_descriptions }}</p>
                                </div>

                                <!-- Video Tab -->
                                <div class="tab-pane fade" id="video" role="tabpanel" aria-labelledby="video-tab">
                                    <h5 class="fw-bold mb-4">Course Video</h5>

                                    @php
                                        $videoExtensions = ['mp4', 'webm', 'ogg'];
                                        $hasVideo = false;
                                    @endphp

                                    @if ($courseDocuments->isNotEmpty())
                                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                                            @foreach ($courseDocuments as $courseDocument)
                                                @php
                                                    $extension = pathinfo($courseDocument->file_path, PATHINFO_EXTENSION);
                                                @endphp

                                                @if (in_array(strtolower($extension), $videoExtensions))
                                                    @php $hasVideo = true; @endphp

                                                    <div class="col d-flex flex-column">
                                                        <div class="ratio ratio-16x9 mb-2">
                                                            <video controls>
                                                                <source src="{{ asset($courseDocument->file_path) }}" type="video/{{ strtolower($extension) }}">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                        </div>
                                                        <p class="fw-semibold small text-center mb-0">{{ $courseDocument->training_title }}</p>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>

                                        @unless($hasVideo)
                                            <p class="text-muted mt-3">No course video available.</p>
                                        @endunless
                                    @else
                                        <p class="text-muted">No course video available.</p>
                                    @endif
                                </div>


                            </div>
                        </div>

                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('admin.componants.footer')