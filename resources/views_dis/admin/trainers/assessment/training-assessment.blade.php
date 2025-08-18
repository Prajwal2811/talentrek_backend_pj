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
                                <span>JustDo Training Material,</span>
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
                                                        <th>Level</th>
                                                        <th>View</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>Sr. No.</th>
                                                        <th>Title</th>
                                                        <th>Level</th>
                                                        <th>View</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                        @foreach ($assessments as $index => $course)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>

                                                                <td>{{ $course->assessment_title }}</td>
                                                                <td>{{ $course->assessment_level }}</td>
                                                                <td>
                                                                    <a href="{{ route('admin.trainer.training-assessment.view', [$course->trainer_id, $course->id]) }}" class="btn btn-sm btn-primary">
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