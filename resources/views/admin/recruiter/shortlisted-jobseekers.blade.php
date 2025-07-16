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
                                                        $status = $shortlistJobseeker->admin_status_rjs;

                                                        $adminStatusOnly = in_array($status, ['approved', 'rejected']) ? $status : (Illuminate\Support\Str::startsWith($status, 'superadmin_') ? 'approved' : 'pending');
                                                        $superadminStatusOnly = Illuminate\Support\Str::startsWith($status, 'superadmin_') ? Illuminate\Support\Str::after($status, 'superadmin_') : 'pending';

                                                        $isAdmin = auth()->user()->role === 'admin';
                                                        $isSuperadmin = auth()->user()->role === 'superadmin';
                                                    @endphp

                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $shortlistJobseeker->name }}</td>
                                                        <td>{{ $shortlistJobseeker->email }}</td>
                                                        <td>
                                                            Admin: {{ ucfirst($adminStatusOnly) }}<br>
                                                            Superadmin: {{ ucfirst($superadminStatusOnly) }}
                                                        </td>
                                                        <td>
                                                            @if($isAdmin)
                                                                @if($adminStatusOnly === 'pending')
                                                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adminModal{{ $shortlistJobseeker->id }}">
                                                                        Change Status
                                                                    </button>
                                                                @elseif($adminStatusOnly === 'rejected')
                                                                    <span class="badge bg-secondary">Rejected</span>
                                                                @elseif($superadminStatusOnly === 'rejected')
                                                                    <span class="badge bg-danger text-light">Superadmin Rejected</span>
                                                                @else
                                                                    <span class="badge bg-info">Already {{ ucfirst($adminStatusOnly) }}</span>
                                                                @endif
                                                            @elseif($isSuperadmin)
                                                                @if($superadminStatusOnly === 'pending' && $adminStatusOnly === 'approved')
                                                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#superadminModal{{ $shortlistJobseeker->id }}">
                                                                        Final Decision
                                                                    </button>
                                                                @elseif($superadminStatusOnly === 'approved')
                                                                    <span class="badge bg-success text-light">Superadmin Approved</span>
                                                                @elseif($superadminStatusOnly === 'rejected')
                                                                    <span class="badge bg-danger text-light">Superadmin Rejected</span>
                                                                @elseif($adminStatusOnly === 'rejected')
                                                                    <span class="badge bg-secondary text-light">Admin Rejected</span>
                                                                @else
                                                                    <span class="badge bg-dark">Admin has not yet responded</span>
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
                                                                        <select name="status" class="form-select status-select form-control" data-target="#adminReason{{ $shortlistJobseeker->id }}">
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
                                              @if($isSuperadmin && $adminStatusOnly === 'approved')
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
                                                                    <select name="status" class="form-select status-select form-control" data-target="#superadminReason{{ $shortlistJobseeker->id }}">
                                                                        <option selected disabled>Select status</option>
                                                                        <option value="approved">Final Approve</option>
                                                                        <option value="rejected">Final Reject</option>
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
                                                            if (this.value === 'rejected') {
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