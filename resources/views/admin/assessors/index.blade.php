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
                                <span>JustDo Assessor's Management,</span>
                            </div>
                        </div>
                    </div>
                 <div class="row clearfix">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="header d-flex justify-content-between align-items-center mb-3">
                                <h2>Assessor Management</h2>
                            </div>

                            <!-- Bootstrap 5 JS Bundle -->
                            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

                            <!-- Table Section -->
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover dataTable js-exportable">
                                        <thead>
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>assessor Name</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                 <th>Admin Status</th>
                                                <th>Registered Date</th>
                                                <th class="sort-disable">Actions</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>assessor Name</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                 <th>Admin Status</th>
                                                <th>Registered Date</th>
                                                <th class="sort-disable">Actions</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            @foreach($assessors->unique('id') as $index => $assessor)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $assessor->name }}</td>
                                                <td>{{ $assessor->email }}</td>
                                                <td>
                                                    <label class="switch"
                                                        @if($assessor->status === 'inactive' && !empty($assessor->inactive_reason))
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="Reason: {{ $assessor->inactive_reason }}"
                                                        @endif>
                                                        <input type="checkbox"
                                                            {{ $assessor->status === 'active' ? 'checked' : '' }}
                                                            onchange="toggleStatus(this)"
                                                            data-assessor-id="{{ $assessor->id }}">
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function () {
                                                        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
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
                                                                <input type="hidden" id="modal-assessor-id">
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
                                                        const assessorId = $(checkbox).data('assessor-id');
                                                        const isChecked = checkbox.checked;

                                                        if (!isChecked) {
                                                            currentCheckbox = checkbox;
                                                            $('#modal-assessor-id').val(assessorId);
                                                            $('#inactive-reason-input').val('');
                                                            modalInstance.show();
                                                        } else {
                                                            sendStatusUpdate(assessorId, 'active');
                                                        }
                                                    }

                                                    function cancelStatusChange() {
                                                        if (currentCheckbox) currentCheckbox.checked = true;
                                                        modalInstance.hide();
                                                    }

                                                    function submitInactiveReason() {
                                                        const assessorId = $('#modal-assessor-id').val();
                                                        const reasonInput = $('#inactive-reason-input');
                                                        const reason = reasonInput.val().trim();

                                                        if (!reason) {
                                                            reasonInput.addClass('is-invalid');
                                                            return;
                                                        }

                                                        reasonInput.removeClass('is-invalid');
                                                        modalInstance.hide();
                                                        sendStatusUpdate(assessorId, 'inactive', reason);
                                                    }


                                                    function sendStatusUpdate(assessorId, status, reason = null) {
                                                        $.ajax({
                                                            url: '{{ route('admin.assessor.changeStatus') }}',
                                                            method: 'POST', 
                                                            data: {
                                                                _token: '{{ csrf_token() }}',
                                                                assessor_id: assessorId,
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
                                                    @if($assessor->admin_status == 'approved')
                                                        <span class="badge bg-success text-light">Admin Approved</span>
                                                    @elseif($assessor->admin_status == 'rejected')
                                                        <span class="badge bg-danger text-light">Admin Rejected</span>
                                                    @elseif($assessor->admin_status == 'superadmin_rejected')
                                                        <span class="badge bg-danger text-light">Super Admin Rejected</span>
                                                    @elseif($assessor->admin_status == 'superadmin_approved')
                                                        <span class="badge bg-success text-light">Super Admin Approved</span>
                                                    @else
                                                            <span class="badge bg-warning text-light">Pending</span>
                                                    @endif
                                                </td>

                                                <td>{{ \Carbon\Carbon::parse($assessor->created_at)->format('d/m/Y') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.assessor.view', $assessor->id) }}" class="btn btn-sm btn-primary">View Profile</a>
                                                    <a href="{{ route('admin.assessor.booking-session', $assessor->id) }}"  class="btn btn-sm btn-success text-light">Session History</a>
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