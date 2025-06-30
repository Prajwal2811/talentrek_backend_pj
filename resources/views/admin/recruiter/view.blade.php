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
                                            <!-- <p class="mb-0"><span>Posts: <strong>321</strong></span> <span>Followers: <strong>4,230</strong></span> <span>Following: <strong>560</strong></span></p> -->
                                        </div>
                                    </div>
                                    <div>
                                        <!-- Status Badge -->
                                        @php
                                            $status = $recruiter->admin_status;
                                            $userRole = auth()->user()->role;
                                        @endphp

                                        {{-- Admin Actions --}}
                                        @if(!$status && $userRole === 'admin')
                                            <!-- Admin Action Buttons -->
                                            <button class="btn btn-default me-2" data-bs-toggle="modal" data-bs-target="#approveModal">Approve</button>
                                            <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject</button>

                                        {{-- Superadmin Actions --}}
                                        @elseif($userRole === 'superadmin' && !Str::startsWith($status, 'superadmin_'))
                                            {{-- Show Admin status if available --}}
                                            @if($status)
                                                <span class="badge bg-info me-2">Admin: {{ ucfirst($status) }}</span>
                                            @else
                                                <span class="badge bg-warning me-2">Admin has not yet responded</span>
                                            @endif

                                            <!-- Superadmin Final Decision Buttons -->
                                            <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#approveModal">Super Approve</button>
                                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">Super Reject</button>

                                        {{-- Final Superadmin Decision --}}
                                        @elseif(Str::startsWith($status, 'superadmin_'))
                                            @php
                                                $badgeClass = Str::contains($status, 'approved') ? 'success' : 'danger';
                                                $statusLabel = ucfirst(str_replace('_', ' ', $status));
                                            @endphp
                                            <span class="badge bg-{{ $badgeClass }}">{{ $statusLabel }}</span>

                                        {{-- Admin sees previously set status --}}
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($status) }}</span>
                                        @endif


                                        <!-- Approve Modal -->
                                        <div class="modal fade text-dark" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirm Approval</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to approve this profile?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button class="btn btn-success" onclick="updateStatus({{ $recruiter->id }}, 'approved')">Yes, Approve</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Reject Modal -->
                                        <div class="modal fade text-dark" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirm Rejection</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to reject this profile?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button class="btn btn-danger" onclick="updateStatus({{ $recruiter->id }}, 'rejected')">Yes, Reject</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                        <script>
                                            function updateStatus(recruiterId, status) {
                                                $.ajax({
                                                    url: '{{ route("admin.recruiter.updateStatus") }}',
                                                    method: 'POST',
                                                    data: {
                                                        _token: '{{ csrf_token() }}',
                                                        recruiter_id: recruiterId,
                                                        status: status
                                                    },
                                                    success: function (response) {
                                                        // alert(response.message);
                                                        // Close the modals manually
                                                        $('.modal').modal('hide');
                                                        // Optional: Reload or update status in the UI
                                                        location.reload();
                                                    },
                                                    error: function () {
                                                        alert('An error occurred while updating status.');
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
                                                                <input readonly type="text" class="form-control" value="{{ $company->company_name }}">
                                                            </div>

                                                            <div class="col-md-6 form-group">
                                                                <label>Company website</label>
                                                                <input readonly type="text" class="form-control" value="{{ $company->company_website }}">
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label>Company location</label>
                                                                <input readonly type="text" class="form-control" value="{{ $company->company_city }}">
                                                            </div>

                                                            <div class="col-md-12 form-group">
                                                                <label>Company address</label>
                                                                <input readonly type="text" class="form-control" value="{{ $company->company_address }}">
                                                            </div>

                                                            <div class="col-md-6 form-group">
                                                                <label>Business email</label>
                                                                <input readonly type="text" class="form-control" value="{{ $company->business_email }}">
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label>Company phone number</label>
                                                                <div class="input-group">
                                                                    <input readonly type="text" class="form-control" value="{{ $company->phone_code }}">
                                                                    <input readonly type="text" class="form-control" value="{{ $company->company_phone_number }}">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6 form-group">
                                                                <label>Number of employees</label>
                                                                <input readonly type="text" class="form-control" value="{{ $company->no_of_employee }}">
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <label>Industry type</label>
                                                                <input readonly type="text" class="form-control" value="{{ $company->industry_type }}">
                                                            </div>

                                                            <div class="col-md-12 form-group">
                                                                <label>CR number (Company registration number)</label>
                                                                <input readonly type="text" class="form-control" value="{{ $company->registration_number }}">
                                                            </div>
                                                        </div>
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
                            {{-- <div class="card">
                                <div class="header">
                                    <h2>Jobseeker Trainings</h2>
                                </div>
                                <div class="body">
                                    <div class="col-lg-12">
                                        <!-- Course Card 1 -->
                                        <div class="card d-flex flex-row p-3 mb-3">
                                            <img src="../images/gallery/finished-quiz.png" alt="Graphic Design"
                                                class="img-fluid rounded" style="width: 200px; object-fit: cover;">
                                            <div class="ps-4 d-flex flex-column justify-content-between flex-grow-1">
                                                <div>
                                                    <h5 class="fw-bold mb-1">Graphic design - Advance level</h5>
                                                    <p class="text-muted mb-2">Lorem ipsum dolor sit amet, consectetur
                                                        adipiscing elit.</p>
                                                    <div class="d-flex align-items-center mb-2">
                                                        <span class="text-warning me-1">★ ★ ★ ★ ☆</span>
                                                        <span class="text-muted">(4/5) Rating</span>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between mt-3">
                                                    <div class="d-flex align-items-center">
                                                        <img src="../images/blog/post-author.jpg" alt="Julia"
                                                            class="rounded-circle me-2" style="width: 35px;">
                                                        <span class="fw-semibold">Julia Maccarthy</span>
                                                    </div>
                                                    <div class="d-flex align-items-center text-muted gap-3">
                                                        <div><i data-feather="book-open" class="me-1"></i>6 lessons
                                                        </div>
                                                        <div><i data-feather="clock" class="me-1"></i>20hrs</div>
                                                        <div><i data-feather="bar-chart-2" class="me-1"></i>Advance
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Hidden More Courses -->
                                        <div id="moreCourses" class="d-none">

                                            <!-- Course Card 2 -->
                                            <div class="card d-flex flex-row p-3 mb-3">
                                                <img src="../images/gallery/graphic-design.png" alt="Web Design"
                                                    class="img-fluid rounded" style="width: 200px; object-fit: cover;">
                                                <div
                                                    class="ps-4 d-flex flex-column justify-content-between flex-grow-1">
                                                    <div>
                                                        <h5 class="fw-bold mb-1">Web Design Essentials</h5>
                                                        <p class="text-muted mb-2">Learn HTML, CSS, and responsive
                                                            design techniques.</p>
                                                        <div class="d-flex align-items-center mb-2">
                                                            <span class="text-warning me-1">★ ★ ★ ★ ★</span>
                                                            <span class="text-muted">(5/5) Rating</span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between mt-3">
                                                        <div class="d-flex align-items-center">
                                                            <img src="../images/blog/post-author.jpg" alt="Julia"
                                                                class="rounded-circle me-2" style="width: 35px;">
                                                            <span class="fw-semibold">John Smith</span>
                                                        </div>
                                                        <div class="d-flex align-items-center text-muted gap-3">
                                                            <div><i data-feather="book-open" class="me-1"></i>8 lessons
                                                            </div>
                                                            <div><i data-feather="clock" class="me-1"></i>25hrs</div>
                                                            <div><i data-feather="bar-chart-2"
                                                                    class="me-1"></i>Intermediate</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Course Card 3 -->
                                            <div class="card d-flex flex-row p-3 mb-3">
                                                <img src="../images/gallery/ui-ux.png" alt="UI/UX Design"
                                                    class="img-fluid rounded" style="width: 200px; object-fit: cover;">
                                                <div
                                                    class="ps-4 d-flex flex-column justify-content-between flex-grow-1">
                                                    <div>
                                                        <h5 class="fw-bold mb-1">UI/UX Design Fundamentals</h5>
                                                        <p class="text-muted mb-2">Understand user-centric design
                                                            principles and wireframing.</p>
                                                        <div class="d-flex align-items-center mb-2">
                                                            <span class="text-warning me-1">★ ★ ★ ★ ☆</span>
                                                            <span class="text-muted">(4.5/5) Rating</span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between mt-3">
                                                        <div class="d-flex align-items-center">
                                                            <img src="../images/blog/post-author.jpg" alt="Julia"
                                                                class="rounded-circle me-2" style="width: 35px;">
                                                            <span class="fw-semibold">Sara Lee</span>
                                                        </div>
                                                        <div class="d-flex align-items-center text-muted gap-3">
                                                            <div><i data-feather="book-open" class="me-1"></i>5 lessons
                                                            </div>
                                                            <div><i data-feather="clock" class="me-1"></i>15hrs</div>
                                                            <div><i data-feather="bar-chart-2" class="me-1"></i>Beginner
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <!-- View More Button -->
                                        <div class="text-center mt-4">
                                            <button class="btn btn-primary" onclick="toggleCourses()">View More</button>
                                        </div>

                                    </div>
                                </div>
                                <script>
                                    function toggleCourses() {
                                        const moreCourses = document.getElementById('moreCourses');
                                        const btn = event.target;

                                        if (moreCourses.classList.contains('d-none')) {
                                            moreCourses.classList.remove('d-none');
                                            btn.textContent = 'View Less';
                                        } else {
                                            moreCourses.classList.add('d-none');
                                            btn.textContent = 'View More';
                                        }
                                    }
                                </script>
                                <script>feather.replace()</script>
                            </div>



                            <div class="card">
                                <div class="header">
                                    <h2>Jobseeker Mentorship</h2>
                                </div>
                                <div class="body">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-lg-12">

                                                <!-- Mentorship Card 1 -->
                                                <div class="card d-flex flex-row align-items-start p-3 mb-4 shadow-sm">
                                                    <img src="../images/gallery/finished-quiz.png" alt="Mentor Image"
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

                                                <!-- Hidden More Mentors -->
                                                <div id="moreMentors" class="d-none">

                                                    <!-- Mentorship Card 2 -->
                                                    <div
                                                        class="card d-flex flex-row align-items-start p-3 mb-4 shadow-sm">
                                                        <img src="../images/gallery/graphic-design.png"
                                                            alt="Mentor Image" class="img-fluid rounded"
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

                                                    <!-- Mentorship Card 3 -->
                                                    <div
                                                        class="card d-flex flex-row align-items-start p-3 mb-4 shadow-sm">
                                                        <img src="../images/gallery/ui-ux.png" alt="Mentor Image"
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
                                                    <button class="btn btn-primary" id="toggleButton"
                                                        onclick="toggleMentors()">View More</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    function toggleMentors() {
                                        const moreMentors = document.getElementById("moreMentors");
                                        const button = document.getElementById("toggleButton");

                                        if (moreMentors.classList.contains("d-none")) {
                                            moreMentors.classList.remove("d-none");
                                            button.textContent = "View Less";
                                        } else {
                                            moreMentors.classList.add("d-none");
                                            button.textContent = "View More";
                                        }
                                    }
                                </script>
                            </div>


                            <div class="card">
                                <div class="header">
                                    <h2>Jobseeker Assessments</h2>
                                </div>
                                <div class="body">
                                    <div class="col-lg-12">

                                        <!-- Quiz Card 1 -->
                                        <div class="card d-flex flex-row p-3 mb-3 shadow-sm">
                                            <img src="../images/gallery/finished-quiz.png" alt="Quiz Thumbnail"
                                                class="img-fluid rounded" style="width: 200px; object-fit: cover;">
                                            <div class="ps-4 d-flex flex-column justify-content-between flex-grow-1">
                                                <div>
                                                    <h5 class="fw-bold mb-1">Graphic Design Advanced Quiz</h5>
                                                    <p class="text-muted mb-2">Test your skills in layout, typography,
                                                        and color theory.</p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between mt-3">
                                                    <div class="d-flex align-items-center">
                                                        <img src="../images/blog/post-author.jpg" alt="Julia"
                                                            class="rounded-circle me-2" style="width: 35px;">
                                                        <div>
                                                            <div class="fw-semibold">Julia Maccarthy</div>
                                                            <small class="text-muted">Quiz By</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Hidden More Quizzes -->
                                        <div id="moreAssessments" class="d-none">

                                            <!-- Quiz Card 2 -->
                                            <div class="card d-flex flex-row p-3 mb-3 shadow-sm">
                                                <img src="../images/gallery/graphic-design.png" alt="Quiz Thumbnail"
                                                    class="img-fluid rounded" style="width: 200px; object-fit: cover;">
                                                <div
                                                    class="ps-4 d-flex flex-column justify-content-between flex-grow-1">
                                                    <div>
                                                        <h5 class="fw-bold mb-1">Web Design Basics Quiz</h5>
                                                        <p class="text-muted mb-2">Evaluate your understanding of HTML,
                                                            CSS, and responsiveness.</p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between mt-3">
                                                        <div class="d-flex align-items-center">
                                                            <img src="../images/blog/post-author.jpg" alt="John"
                                                                class="rounded-circle me-2" style="width: 35px;">
                                                            <div>
                                                                <div class="fw-semibold">John Smith</div>
                                                                <small class="text-muted">Quiz By</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Quiz Card 3 -->
                                            <div class="card d-flex flex-row p-3 mb-3 shadow-sm">
                                                <img src="../images/gallery/ui-ux.png" alt="Quiz Thumbnail"
                                                    class="img-fluid rounded" style="width: 200px; object-fit: cover;">
                                                <div
                                                    class="ps-4 d-flex flex-column justify-content-between flex-grow-1">
                                                    <div>
                                                        <h5 class="fw-bold mb-1">UI/UX Fundamentals Quiz</h5>
                                                        <p class="text-muted mb-2">Check your knowledge on user flow,
                                                            wireframes, and design thinking.</p>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between mt-3">
                                                        <div class="d-flex align-items-center">
                                                            <img src="../images/blog/post-author.jpg" alt="Sara"
                                                                class="rounded-circle me-2" style="width: 35px;">
                                                            <div>
                                                                <div class="fw-semibold">Sara Lee</div>
                                                                <small class="text-muted">Quiz By</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <!-- View More Button -->
                                        <div class="text-center mt-4">
                                            <button class="btn btn-primary" onclick="toggleAssessments()"
                                                id="toggleButton">View More</button>
                                        </div>

                                    </div>
                                </div>
                                <!-- Toggle Script -->
                                <script>
                                    function toggleAssessments() {
                                        const moreAssessments = document.getElementById('moreAssessments');
                                        const btn = document.getElementById('toggleButton');

                                        if (moreAssessments.classList.contains('d-none')) {
                                            moreAssessments.classList.remove('d-none');
                                            btn.textContent = 'View Less';
                                        } else {
                                            moreAssessments.classList.add('d-none');
                                            btn.textContent = 'View More';
                                        }
                                    }
                                </script>
                            </div>



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
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    @include('admin.componants.footer')