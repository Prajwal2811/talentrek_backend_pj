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
                                <span>JustDo Jobseeker's Management,</span>
                            </div>
                        </div>
                    </div>
                 <div class="row clearfix">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="header d-flex justify-content-between align-items-center mb-3">
                                <h2>Jobseeker Management</h2>
                                @php
                                    $admin = Auth::guard('admin')->user();
                                @endphp

                                @if (auth()->user()->role !== 'admin')
                                    <button 
                                        class="btn btn-sm btn-info" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#assignAdminModal">
                                        Assign Admin
                                    </button>
                                @endif


                            </div>

                            <div class="modal fade" id="assignAdminModal" aria-labelledby="assignAdminModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form id="assignAdminForm" method="POST" action="{{ route('admin.jobseeker.assignAdmin') }}">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="assignAdminModalLabel">Assign Admin</h5>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="adminSelect" class="form-label">Select Admin</label>
                                                    <select class="form-select form-control" id="adminSelect" name="admin_id" required>
                                                        <option value="">-- Choose Admin --</option>
                                                        @foreach ($admins as $admin)
                                                            <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                               <div class="mb-3">
                                                    <label class="form-label">Selected Jobseekers</label>
                                                    <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ced4da; border-radius: .25rem;">
                                                        <ul id="selectedJobseekerList" class="list-group list-group-flush mb-0"></ul>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="jobseeker_ids" id="jobseekerIdsInput">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Assign</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                              <!-- JS Logic -->
                            <script>
                                function toggleSelectAll(source) {
                                    const checkboxes = document.querySelectorAll('.row-checkbox');
                                    checkboxes.forEach(cb => cb.checked = source.checked);
                                }

                                const assignAdminModal = document.getElementById('assignAdminModal');
                                assignAdminModal.addEventListener('show.bs.modal', function () {
                                    const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
                                    const jobseekerIds = [];
                                    const list = document.getElementById('selectedJobseekerList');
                                    list.innerHTML = '';

                                    selectedCheckboxes.forEach(cb => {
                                        const id = cb.getAttribute('data-id');
                                        const name = cb.getAttribute('data-name');
                                        jobseekerIds.push(id);

                                        const li = document.createElement('li');
                                        li.className = 'list-group-item';
                                        li.textContent = `ID: ${id} | Name: ${name}`;
                                        list.appendChild(li);
                                    });

                                    document.getElementById('jobseekerIdsInput').value = jobseekerIds.join(',');
                                });
                            </script>

                            <!-- Bootstrap 5 JS Bundle -->
                            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

                            <!-- Table Section -->
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover dataTable js-exportable">
                                        <thead>
                                            <tr>
                                                <th @if (Auth()->user()->role === 'admin') 
                                                        style="display: none;" 
                                                    @endif >
                                                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                                                </th>
                                                <th>Sr. No.</th>
                                                <th>Full Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Status</th>
                                                <th>Admin Status</th>
                                                <th>Registered Date</th>
                                                <th class="sort-disable">Actions</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th @if (Auth()->user()->role === 'admin') 
                                                        style="display: none;" 
                                                    @endif >
                                                </th>
                                                <th>Sr. No.</th>
                                                <th>Full Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Status</th>
                                                <th>Admin Status</th>
                                                <th>Registered Date</th>
                                                <th class="sort-disable">Actions</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            @foreach($jobseekers->unique('id') as $index => $jobseeker)
                                            <tr>
                                                <td @if(Auth()->user()->role === 'admin') style="display: none;" @endif >
                                                    <input type="checkbox" class="row-checkbox"
                                                        data-id="{{ $jobseeker->id }}"
                                                        data-name="{{ $jobseeker->name }}"
                                                        @if ($jobseeker->assigned_admin) checked disabled @endif>
                                                </td>

                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $jobseeker->name }}</td>
                                                <td>{{ $jobseeker->email }}</td>
                                                <td>{{ $jobseeker->phone_code . '-' . $jobseeker->phone_number }}</td>
                                                <td>
                                                    <label class="switch"
                                                        @if($jobseeker->status === 'inactive' && !empty($jobseeker->inactive_reason))
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="Reason: {{ $jobseeker->inactive_reason }}"
                                                        @endif>
                                                        <input type="checkbox"
                                                            {{ $jobseeker->status === 'active' ? 'checked' : '' }}
                                                            onchange="toggleStatus(this)"
                                                            data-jobseeker-id="{{ $jobseeker->id }}">
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function () {
                                                        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                                                        tooltipTriggerList.map(function (tooltipTriggerEl) {
                                                            return new bootstrap.Tooltip(tooltipTriggerEl);
                                                        });
                                                    });
                                                </script>


                                                <!-- Inactive Reason Modal -->
                                                <div class="modal fade" id="inactiveReasonModal" tabindex="-1" aria-labelledby="reasonModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Reason for Inactivation</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                <textarea required id="inactive-reason-input" class="form-control" rows="3" placeholder="Enter reason here..."></textarea>
                                                                <div class="invalid-feedback">Reason is required.</div>
                                                                <input type="hidden" id="modal-jobseeker-id">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" onclick="cancelStatusChange()">Cancel</button>
                                                                <button type="button" class="btn btn-primary" onclick="submitInactiveReason()">Submit</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                                                <script>
                                                    let currentCheckbox = null;
                                                    let modalInstance = new bootstrap.Modal(document.getElementById('inactiveReasonModal'));

                                                    function toggleStatus(checkbox) {
                                                        const jobseekerId = $(checkbox).data('jobseeker-id');
                                                        const isChecked = checkbox.checked;

                                                        if (!isChecked) {
                                                            currentCheckbox = checkbox;
                                                            $('#modal-jobseeker-id').val(jobseekerId);
                                                            $('#inactive-reason-input').val('');
                                                            modalInstance.show();
                                                        } else {
                                                            sendStatusUpdate(jobseekerId, 'active');
                                                        }
                                                    }

                                                    function cancelStatusChange() {
                                                        if (currentCheckbox) currentCheckbox.checked = true;
                                                        modalInstance.hide();
                                                    }

                                                    function submitInactiveReason() {
                                                        const jobseekerId = $('#modal-jobseeker-id').val();
                                                        const reasonInput = $('#inactive-reason-input');
                                                        const reason = reasonInput.val().trim();

                                                        if (!reason) {
                                                            reasonInput.addClass('is-invalid');
                                                            return;
                                                        }

                                                        reasonInput.removeClass('is-invalid');
                                                        modalInstance.hide();
                                                        sendStatusUpdate(jobseekerId, 'inactive', reason);
                                                    }


                                                    function sendStatusUpdate(jobseekerId, status, reason = null) {
                                                        $.ajax({
                                                            url: '{{ route('admin.jobseeker.changeStatus') }}',
                                                            method: 'POST',
                                                            data: {
                                                                _token: '{{ csrf_token() }}',
                                                                jobseeker_id: jobseekerId,
                                                                status: status,
                                                                reason: reason
                                                            },
                                                            success: function(response) {
                                                                $('#success-message').text(response.message).fadeIn();
                                                                $('#error-message').fadeOut();
                                                                setTimeout(() => $('#success-message').fadeOut(), 3000);
                                                            },
                                                            error: function() {
                                                                $('#error-message').text('An error occurred. Please try again.').fadeIn();
                                                                $('#success-message').fadeOut();
                                                                setTimeout(() => $('#error-message').fadeOut(), 3000);
                                                            }
                                                        });
                                                    }
                                                </script>

                                                <td>
                                                    @if($jobseeker->admin_status == 'approved')
                                                        <span class="badge bg-success text-light">Admin Approved</span>
                                                    @elseif($jobseeker->admin_status == 'rejected')
                                                        <span class="badge bg-danger text-light">Admin Rejected</span>
                                                    @elseif($jobseeker->admin_status == 'superadmin_rejected')
                                                        <span class="badge bg-danger text-light">Super Admin Rejected</span>
                                                    @elseif($jobseeker->admin_status == 'superadmin_approved')
                                                        <span class="badge bg-success text-light">Super Admin Approved</span>
                                                    @else
                                                         <span class="badge bg-warning text-light">Pending</span>
                                                    @endif
                                                </td>


                                                <td>{{ \Carbon\Carbon::parse($jobseeker->created_at)->format('d/m/Y') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.jobseeker.view', $jobseeker->id) }}" class="btn btn-sm btn-primary">View Profile</a>
                                                    {{-- <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $jobseeker->id }})">Delete</button> --}}
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
    </div>

    @include('admin.componants.footer')