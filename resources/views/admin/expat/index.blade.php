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
                                <span>JustDo Expat's Management,</span>
                            </div>
                        </div>
                    </div>
                 <div class="row clearfix">
                    <div class="col-lg-12">
                        <div class="card">
                           <div class="header d-flex justify-content-between align-items-center mb-3">
                                <h2>Expat Management</h2>

                                <!-- Assign Admin Button (only for non-admins) -->
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
                                    <form id="assignAdminForm" method="POST" action="{{ route('admin.expat.assignAdmin') }}">
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
                                                    <label class="form-label">Selected Expats</label>
                                                    <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ced4da; border-radius: .25rem;">
                                                        <ul id="selectedExpatList" class="list-group list-group-flush mb-0"></ul>
                                                    </div>
                                                </div>

                                                <!-- Hidden inputs to pass IDs and type -->
                                                <input type="hidden" name="jobseeker_ids" id="expatIdsInput">
                                                <input type="hidden" name="user_type" value="expat">
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
                                    const expatIds = [];
                                    const list = document.getElementById('selectedExpatList');
                                    list.innerHTML = '';

                                    selectedCheckboxes.forEach(cb => {
                                        // Skip if checkbox is disabled (already assigned)
                                        if (cb.disabled) return;

                                        const id = cb.getAttribute('data-id');
                                        const name = cb.getAttribute('data-name');
                                        expatIds.push(id);

                                        const li = document.createElement('li');
                                        li.className = 'list-group-item';
                                        li.textContent = `ID: ${id} | Name: ${name}`;
                                        list.appendChild(li);
                                    });

                                    document.getElementById('expatIdsInput').value = expatIds.join(',');
                                });
                            </script>



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
                                                <th>Tracking ID</th>
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
                                                <th>Tracking ID</th>
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
                                            @foreach($expats->unique('id') as $index => $expat)
                                            <tr>
                                                <td @if(Auth()->user()->role === 'admin') style="display: none;" @endif >
                                                    <input type="checkbox" class="row-checkbox"
                                                        data-id="{{ $expat->id }}"
                                                        data-name="{{ $expat->name }}"
                                                        @if ($expat->assigned_admin) checked disabled @endif>
                                                </td>
                                                <td>{{ $index + 1 }}</td>
                                                @php
                                                    $year = now()->year;
                                                    $serialNumber = str_pad($expat->id, 3, '0', STR_PAD_LEFT);
                                                    $trackingNumber = 'TT-' . $year . $serialNumber;
                                                @endphp

                                                <td>{{ $trackingNumber }}</td>


                                                <td>{{ $expat->name }}</td>
                                                <td>{{ $expat->email }}</td>
                                                <td>{{ $expat->phone_code . '-' . $expat->phone_number }}</td>
                                                <td>
                                                    <label class="switch"
                                                        @if($expat->status === 'inactive' && !empty($expat->inactive_reason))
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="Reason: {{ $expat->inactive_reason }}"
                                                        @endif>
                                                        <input type="checkbox"
                                                            {{ $expat->status === 'active' ? 'checked' : '' }}
                                                            onchange="toggleStatus(this)"
                                                            data-expat-id="{{ $expat->id }}">
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
                                                                <input type="hidden" id="modal-expat-id">
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
                                                        const expatId = $(checkbox).data('expat-id');
                                                        const isChecked = checkbox.checked;

                                                        if (!isChecked) {
                                                            currentCheckbox = checkbox;
                                                            $('#modal-expat-id').val(expatId);
                                                            $('#inactive-reason-input').val('');
                                                            modalInstance.show();
                                                        } else {
                                                            sendStatusUpdate(expatId, 'active');
                                                        }
                                                    }

                                                    function cancelStatusChange() {
                                                        if (currentCheckbox) currentCheckbox.checked = true;
                                                        modalInstance.hide();
                                                    }

                                                    function submitInactiveReason() {
                                                        const expatId = $('#modal-expat-id').val();
                                                        const reasonInput = $('#inactive-reason-input');
                                                        const reason = reasonInput.val().trim();

                                                        if (!reason) {
                                                            reasonInput.addClass('is-invalid');
                                                            return;
                                                        }

                                                        reasonInput.removeClass('is-invalid');
                                                        modalInstance.hide();
                                                        sendStatusUpdate(expatId, 'inactive', reason);
                                                    }


                                                    function sendStatusUpdate(expatId, status, reason = null) {
                                                        $.ajax({
                                                            url: '{{ route('admin.expat.changeStatus') }}',
                                                            method: 'POST',
                                                            data: {
                                                                _token: '{{ csrf_token() }}',
                                                                expat_id: expatId,
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
                                                    @switch($expat->admin_status)
                                                        @case('approved')
                                                            <span class="badge" style="background-color: #28a745; color: #fff;">Admin Approved</span>
                                                            @break
                                                        @case('rejected')
                                                            <span class="badge" style="background-color: #dc3545; color: #fff;">Admin Rejected</span>
                                                            @break
                                                        @case('superadmin_approved')
                                                            <span class="badge" style="background-color: #007bff; color: #fff;">Super Admin Approved</span>
                                                            @break
                                                        @case('superadmin_rejected')
                                                            <span class="badge" style="background-color: #ff4d4d; color: #fff;">Super Admin Rejected</span>
                                                            @break
                                                        @default
                                                            <span class="badge" style="background-color: #ffc107; color: #000;">Pending</span>
                                                    @endswitch
                                                </td>



                                                <td>{{ \Carbon\Carbon::parse($expat->created_at)->format('d/m/Y') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.expat.view', $expat->id) }}" class="btn btn-sm btn-primary">View Profile</a>
                                                    {{-- <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $expat->id }})">Delete</button> --}}
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