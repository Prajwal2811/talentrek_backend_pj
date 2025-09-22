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
                                <span>JustDo Trainers Management,</span>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Trainers Management</h2>
                                </div>
                                    <!-- Table Section -->
                                    <div class="body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover dataTable js-exportable">
                                                <thead>
                                                    <tr>
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
                                                    @foreach($trainers->unique('id') as $index => $trainer)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $trainer->name }}</td>
                                                        <td>{{ $trainer->email }}</td>
                                                        <td>{{ $trainer->phone_code . '-' . $trainer->phone_number }}</td>
                                                        <td>
                                                            <label class="switch"
                                                                @if($trainer->status === 'inactive' && !empty($trainer->inactive_reason))
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-placement="top"
                                                                    title="Reason: {{ $trainer->inactive_reason }}"
                                                                @endif>
                                                                <input type="checkbox"
                                                                    {{ $trainer->status === 'active' ? 'checked' : '' }}
                                                                    onchange="toggleStatus(this)"
                                                                    data-trainer-id="{{ $trainer->id }}">
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
                                                                        <input type="hidden" id="modal-trainer-id">
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
                                                                const trainerId = $(checkbox).data('trainer-id');
                                                                const isChecked = checkbox.checked;

                                                                if (!isChecked) {
                                                                    currentCheckbox = checkbox;
                                                                    $('#modal-trainer-id').val(trainerId);
                                                                    $('#inactive-reason-input').val('');
                                                                    modalInstance.show();
                                                                } else {
                                                                    sendStatusUpdate(trainerId, 'active');
                                                                }
                                                            }

                                                            function cancelStatusChange() {
                                                                if (currentCheckbox) currentCheckbox.checked = true;
                                                                modalInstance.hide();
                                                            }

                                                            function submitInactiveReason() {
                                                                const trainerId = $('#modal-trainer-id').val();
                                                                const reasonInput = $('#inactive-reason-input');
                                                                const reason = reasonInput.val().trim();

                                                                if (!reason) {
                                                                    reasonInput.addClass('is-invalid');
                                                                    return;
                                                                }

                                                                reasonInput.removeClass('is-invalid');
                                                                modalInstance.hide();
                                                                sendStatusUpdate(trainerId, 'inactive', reason);
                                                            }


                                                            function sendStatusUpdate(trainerId, status, reason = null) {
                                                                $.ajax({
                                                                    url: '{{ route('admin.trainer.changeStatus') }}',
                                                                    method: 'POST',
                                                                    data: {
                                                                        _token: '{{ csrf_token() }}',
                                                                        trainer_id: trainerId,
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
                                                            @if($trainer->admin_status == 'approved')
                                                                <span class="badge bg-success text-light">Admin Approved</span>
                                                            @elseif($trainer->admin_status == 'rejected')
                                                                <span class="badge bg-danger text-light">Admin Rejected</span>
                                                            @elseif($trainer->admin_status == 'superadmin_rejected')
                                                                <span class="badge bg-danger text-light">Super Admin Rejected</span>
                                                            @elseif($trainer->admin_status == 'superadmin_approved')
                                                                <span class="badge bg-success text-light">Super Admin Approved</span>
                                                            @else
                                                                <span class="badge bg-warning text-light">Pending</span>
                                                            @endif
                                                        </td>


                                                        <td>{{ \Carbon\Carbon::parse($trainer->created_at)->format('d/m/Y') }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.trainer.view', $trainer->id) }}" class="btn btn-sm btn-success text-white">View Profile</a>
                                                            <a href="{{ route('admin.trainer.training-material', $trainer->id) }}" class="btn btn-sm btn-secondary text-white">Training Material</a>
                                                            <a href="{{ route('admin.trainer.training-assessment', $trainer->id) }}" class="btn btn-sm btn-info text-white">Training Assessment</a>
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