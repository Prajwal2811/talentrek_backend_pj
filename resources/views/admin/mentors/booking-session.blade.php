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
                        <div class="row clearfix">
                            <div class="col-xl-5 col-md-5 col-sm-12">
                                <h1>Hi, {{  Auth()->user()->name }}!</h1>
                                <span>JustDo Shortlisted Jobseekers Management,</span>
                            </div>
                            <div class="col-xl-7 col-md-7 col-sm-12 text-md-right">

                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Shortlisted Jobseekers Management</h2>
                                </div>
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover dataTable js-exportable">
                                            <thead>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Jobseeker Name</th>
                                                    <th>Mode</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Jobseeker Name</th>
                                                    <th>Mode</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                @foreach($bookingSessions as $session)
                                                    @php
                                                        $status = $session->admin_status_rjs;

                                                        $adminStatusOnly = in_array($status, ['approved', 'rejected']) ? $status : (Illuminate\Support\Str::startsWith($status, 'superadmin_') ? 'approved' : 'pending');
                                                        $superadminStatusOnly = Illuminate\Support\Str::startsWith($status, 'superadmin_') ? Illuminate\Support\Str::after($status, 'superadmin_') : 'pending';

                                                        $isAdmin = auth()->user()->role === 'admin';
                                                        $isSuperadmin = auth()->user()->role === 'superadmin';
                                                    @endphp

                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $session->jobseeker_name }}</td>
                                                        <td>{{ $session->slot_mode == 'offline' ? 'Offline' : 'Online' }}</td>
                                                        @php
                                                            switch ($session->booking_status) {
                                                                case 'confirmed':
                                                                    $statusText = 'Confirmed';
                                                                    break;
                                                                case 'cancelled':
                                                                    $statusText = 'Cancelled';
                                                                    break;
                                                                case 'postponed':
                                                                    $statusText = 'Postponed';
                                                                    break;
                                                                default:
                                                                    $statusText = 'Pending';
                                                            }
                                                        @endphp
                                                        <td>{{ $statusText }}</td>
                                                        <td>
                                                            <a href="" class="btn btn-sm btn-primary">View</a>
                                                        </td>


                                                        

                                                    </tr>


                                               

                                            

                                            </td>
                                        </tr>
                                                @endforeach


                                        </table>
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