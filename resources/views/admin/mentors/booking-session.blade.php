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
                                <span>JustDo Booking Sessions Management,</span>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Booking Sessions Management</h2>
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
                                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#slotModal{{$session->booking_id}}" onclick="loadSlotDetails()">View</button>
                                                            <div class="modal fade" id="slotModal{{$session->booking_id}}" tabindex="-1" aria-labelledby="slotModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="slotModalLabel">Slot Details</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>

                                                                        <div class="modal-body">
                                                                            <form>
                                                                                <div class="mb-3">
                                                                                    <label for="slotMode" class="form-label">Slot Mode</label>
                                                                                    <input type="text" value="{{ $session->slot_mode }}" class="form-control" id="slotMode" readonly>
                                                                                </div>

                                                                                <div class="mb-3">
                                                                                    <label for="slotDate" class="form-label">Slot Date</label>
                                                                                    <input type="text" value="{{ $session->slot_date }}" class="form-control" id="slotDate" readonly>
                                                                                </div>

                                                                                <div class="mb-3">
                                                                                    <label for="slotTime" class="form-label">Slot Time</label>
                                                                                    <input type="text" value="{{ $session->slot_time }}" class="form-control" id="slotTime" readonly>
                                                                                </div>

                                                                                <div class="mb-3">
                                                                                    <label for="slotStatus" class="form-label">Status</label>
                                                                                    <input type="text" value="{{ $session->status }}" class="form-control" id="slotStatus" readonly>
                                                                                </div>
                                                                                @if($session->is_postpone == '1')
                                                                                    <div class="mb-3">
                                                                                        <label for="slotStatus" class="form-label">New Date</label>
                                                                                        <input type="text" value="{{ $session->slot_date_after_postpone }}" class="form-control" id="slotStatus" readonly>
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label for="slotStatus" class="form-label">New Slot Time</label>
                                                                                        <input type="text" value="{{ $session->slot_time_after_postpone }}" class="form-control" id="slotStatus" readonly>
                                                                                    </div>
                                                                                @endif
                                                                                @if($session->cancellation_reason != NULL)
                                                                                    <div class="mb-3">
                                                                                        <label for="slotStatus" class="form-label">Cancellation Reason</label>
                                                                                        <input type="text" value="{{ $session->cancellation_reason }}" class="form-control" id="slotStatus" readonly>
                                                                                    </div>
                                                                                @endif
                                                                            </form>
                                                                        </div>


                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                                    </div>
                                                                    
                                                                    </div>
                                                                </div>
                                                            </div>

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