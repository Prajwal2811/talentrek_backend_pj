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
                <!-- Bootstrap 5 CDN -->
                <div class="container-fluid">
                    <div class="block-header">
                        <div class="row clearfix">
                            <div class="col-xl-5 col-md-5 col-sm-12">
                                <h1>Hi, {{  Auth()->user()->name }}!</h1>
                                <span>JustDo Admin Edit,</span>
                            </div>
                        </div>
                    </div>


                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                <h2>Training Material Management</h2>
                                </div>
                                    <!-- Table Section -->
                                    <div class="body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover dataTable js-exportable">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No.</th>
                                                        <th>Title</th>
                                                        <th>Subtitle</th>
                                                        <th>Lessons</th>
                                                        <th>Duration</th>
                                                        <th>Category</th>
                                                        <th>Admin Status</th>
                                                        <th>View</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>Sr. No.</th>
                                                        <th>Title</th>
                                                        <th>Subtitle</th>
                                                        <th>Lessons</th>
                                                        <th>Duration</th>
                                                        <th>Category</th>
                                                        <th>Admin Status</th>
                                                        <th>View</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    @foreach ($materials->unique('material_id') as  $index => $course)
                                                        <tr>

                                                            <td>{{ $loop->iteration }}</td>
                                                            {{-- Title --}}
                                                            <td>{{ $course->training_title }}</td>

                                                            {{-- Subtitle --}}
                                                            <td>{{ $course->training_sub_title }}</td>

                                                            {{-- Lessons --}}
                                                            <td>40 lessons</td> {{-- You can replace this with dynamic data if available --}}

                                                            {{-- Duration --}}
                                                            <td>25 hrs</td> {{-- Replace with dynamic duration if available --}}

                                                            {{-- Category --}}
                                                            <td>{{ $course->training_category }}</td>


                                                            <td>
                                                                @if($course->admin_status == 'approved')
                                                                    <span class="badge bg-success text-light">Admin Approved</span>
                                                                @elseif($course->admin_status == 'rejected')
                                                                    <span class="badge bg-danger text-light">Admin Rejected</span>
                                                                @elseif($course->admin_status == 'superadmin_rejected')
                                                                    <span class="badge bg-danger text-light">Super Admin Rejected</span>
                                                                @elseif($course->admin_status == 'superadmin_approved')
                                                                    <span class="badge bg-success text-light">Super Admin Approved</span>
                                                                @else
                                                                    <span class="badge bg-warning text-light">Pending</span>
                                                                @endif
                                                            </td>

                                                            {{-- View Link --}}
                                                            <td>
                                                                <a href="{{ route('admin.trainer.training-material.view', [$course->trainer_id, $course->material_id]) }}" class="btn btn-sm btn-primary">
                                                                    View
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </main>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


                           
                       

@include('admin.componants.footer')