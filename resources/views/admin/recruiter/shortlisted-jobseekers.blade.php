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
                                                    <th>Full Name</th>
                                                    <th>Email</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Full Name</th>
                                                    <th>Email</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                @foreach($shortlistJobseekers as $shortlistJobseeker)
                                                    @php
                                                        $adminStatus = $shortlistJobseeker->admin_status_rjs;
                                                        $superadminStatus = $shortlistJobseeker->admin_status_rjs ?? 'pending';
                                                        $isAdmin = auth()->user()->role === 'admin';
                                                        $isSuperadmin = auth()->user()->role === 'superadmin';
                                                    @endphp

                                                  <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $shortlistJobseeker->name }}</td>
                                                    <td>{{ $shortlistJobseeker->email }}</td>
                                                    <td>Admin: {{ $adminStatus }}<br>Superadmin: {{ $superadminStatus }}</td>
                                                    <td>
                                                        @if($isAdmin)
                                                            @if($adminStatus === null || $adminStatus === 'pending')
                                                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adminModal{{ $shortlistJobseeker->id }}">Change Status</button>
                                                            @else
                                                                <span class="text-muted">Already {{ $adminStatus }}</span>
                                                            @endif
                                                        @elseif($isSuperadmin)
                                                            @if($adminStatus === 'approved')
                                                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#superadminModal{{ $shortlistJobseeker->id }}">Final Decision</button>
                                                            @else
                                                                <span class="text-dark">Admin has not yet responded</span>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>

                                                <!-- Admin Modal -->
                                                @if($isAdmin)
                                                <div class="modal fade" id="adminModal{{ $shortlistJobseeker->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <form method="POST" action="{{ route('admin.shortlist.updateStatus') }}">
                                                            @csrf
                                                            <input type="hidden" name="jobseeker_id" value="{{ $shortlistJobseeker->id }}">
                                                            <input type="hidden" name="role" value="admin">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Admin - Change Status</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <label>Status</label>
                                                                    <select name="status" class="form-select status-select" data-target="#adminReason{{ $shortlistJobseeker->id }}">
                                                                        <option selected disabled>Select status</option>
                                                                        <option value="approved">Approve</option>
                                                                        <option value="rejected">Reject</option>
                                                                    </select>
                                                                    <div class="mt-3 d-none reason-field" id="adminReason{{ $shortlistJobseeker->id }}">
                                                                        <label>Reason for Rejection</label>
                                                                        <textarea name="reason" class="form-control" placeholder="Enter reason"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button class="btn btn-success">Submit</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                @endif

                                                <!-- Superadmin Modal -->
                                                @if($isSuperadmin && $adminStatus === 'approved')
                                                <div class="modal fade" id="superadminModal{{ $shortlistJobseeker->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <form method="POST" action="{{ route('admin.shortlist.updateStatus') }}">
                                                            @csrf
                                                            <input type="hidden" name="jobseeker_id" value="{{ $shortlistJobseeker->id }}">
                                                            <input type="hidden" name="role" value="superadmin">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Superadmin - Final Decision</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <label>Status</label>
                                                                    <select name="status" class="form-select status-select" data-target="#superadminReason{{ $shortlistJobseeker->id }}">
                                                                        <option selected disabled>Select status</option>
                                                                        <option value="Final Approved">Final Approve</option>
                                                                        <option value="Final Rejected">Final Reject</option>
                                                                    </select>
                                                                    <div class="mt-3 d-none reason-field" id="superadminReason{{ $shortlistJobseeker->id }}">
                                                                        <label>Reason for Rejection</label>
                                                                        <textarea name="reason" class="form-control" placeholder="Enter reason"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button class="btn btn-success">Submit</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                @endif
                                                                                            <script>
                                                document.addEventListener("DOMContentLoaded", function () {
                                                    document.querySelectorAll('.status-select').forEach(function (select) {
                                                        select.addEventListener('change', function () {
                                                            const target = document.querySelector(this.getAttribute('data-target'));
                                                            if (this.value.includes('Reject')) {
                                                                target.classList.remove('d-none');
                                                            } else {
                                                                target.classList.add('d-none');
                                                            }
                                                        });
                                                    });
                                                });
                                                </script>
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