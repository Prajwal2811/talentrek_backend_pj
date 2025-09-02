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
                                <span>JustDo Notification Management,</span>
                            </div>
                            <div class="col-xl-7 col-md-7 col-sm-12 text-md-right">

                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Notification Management</h2>
                                </div>
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover dataTable js-exportable">
                                            <thead>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Created Date/Time</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Full Name</th>
                                                    <th>Email</th>
                                                    <th>Created Date</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                @php $i = 1; @endphp
                                                @foreach($notifications as $notification)
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        <td>{{ $notification->name }}</td>
                                                        <td>{{ $notification->email }}</td>
                                                        
                                                        <td>{{ \Carbon\Carbon::parse($notification->created_at)->format('d M Y, h:i A') }}
                                                        </td>
                                                        <td>
                                                            @php
                                                                $status = strtolower($notification->is_read);
                                                                if($status == 1){
                                                                    $stu = 'Read';
                                                                }else{
                                                                     $stu = 'Unread';
                                                                }
                                                                $badgeClass = match($status) {
                                                                    '1' => 'success',
                                                                    '0' => 'danger',
                                                                };
                                                            @endphp
                                                            <span class="badge bg-{{ $badgeClass }} text-light">
                                                                {{ ucfirst($stu) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                           
                                                            <a href="{{ route('admin.notifications.view',['id' => $notification->id]) }}" class="btn btn-primary">
                                                                View
                                                            </a>

                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.componants.footer')