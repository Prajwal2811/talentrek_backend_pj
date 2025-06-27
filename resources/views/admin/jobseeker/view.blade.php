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
                                        <!-- Right Column for Buttons -->
                                        <button class="btn btn-default me-2" data-bs-toggle="modal"
                                            data-bs-target="#approveModal">Approve</button>
                                        <button class="btn btn-dark" data-bs-toggle="modal"
                                            data-bs-target="#rejectModal">Reject</button>
                                        <!-- Approve Confirmation Modal -->
                                        <div class="modal fade text-dark" id="approveModal" tabindex="-1"
                                            aria-labelledby="approveModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="approveModalLabel">Confirm Approval
                                                        </h5>
                                                        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                                                    </div>
                                                    <div class="modal-body ">
                                                        Are you sure you want to approve this profile?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-success">Yes,
                                                            Approve</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Reject Confirmation Modal -->
                                        <div class="modal fade text-dark" id="rejectModal" tabindex="-1"
                                            aria-labelledby="rejectModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="rejectModalLabel">Confirm Rejection
                                                        </h5>
                                                        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to reject this profile?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-danger">Yes,
                                                            Reject</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-5">
                            <div class="card">
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
                                                <a class="nav-link active" data-toggle="tab"
                                                    href="#education">Educational Details</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#work">Work Experience</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#skills">Skills &
                                                    Training</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-toggle="tab" href="#additional">Additional
                                                    Information</a>
                                            </li>
                                        </ul>

                                        <!-- Tab content -->
                                        <div class="tab-content">
                                            <!-- Education -->
                                            <div class="tab-pane show active" id="education">
                                                <form>
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
                                                </form>
                                            </div>

                                            <!-- Work Experience -->
                                            <div class="tab-pane fade" id="work">
                                                <form>
                                                    @foreach ($experiences as $experience )
                                                        <div class="row">
                                                            <div class="col-md-6 form-group">
                                                                <label>Job role</label>
                                                                <input type="text" class="form-control"
                                                                   readonly value="{{ $experience->job_role }}">
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
                                                </form>
                                            </div>

                                            <!-- Skills -->
                                            <div class="tab-pane fade" id="skills">
                                                <form>
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
                                                </form>
                                            </div>

                                            <!-- Additional Info -->
                                            <div class="tab-pane fade" id="additional">
                                                <form>
                                                    <div class="row">
                                                        @foreach($additioninfos as $info)
                                                            @if($info->doc_type == 'resume')
                                                                <div class="col-md-12 form-group d-flex align-items-center">
                                                                    <label class="w-100">Uploaded Resume</label>
                                                                    <input readonly type="text" class="form-control me-2" value="{{ $info->document_name }}" readonly>
                                                                    <a href="{{ $info->document_path }}" target="_blank" class="btn btn-danger">View</a>
                                                                </div>
                                                            @elseif($info->doc_type == 'profile_picture')
                                                                <div class="col-md-12 form-group d-flex align-items-center">
                                                                    <label class="w-100">Uploaded Profile Picture</label>
                                                                    <input readonly type="text" class="form-control me-2" value="{{ $info->document_name }}" readonly>
                                                                    <a href="{{ $info->document_path }}" target="_blank" class="btn btn-danger">View</a>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
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