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
                    <div class="block-header">
                        <div class="row clearfix">
                            <div class="col-xl-5 col-md-5 col-sm-12">
                                <h1>Hi, {{  Auth()->user()->name }}!</h1>
                                <span>JustDo Recruiter's Jobseekers Management,</span>
                            </div>
                            <div class="col-xl-7 col-md-7 col-sm-12 text-md-right">
                                <!-- Optional action buttons -->
                            </div>
                        </div>
                    </div>

                 <div class="row clearfix">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="header d-flex justify-content-between align-items-center mb-3">
                                <h2>Jobseeker Management</h2>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#assignAdminModal">
                                    Assign Admin
                                </button>
                            </div>

                            <!-- Assign Admin Modal -->
                            <div class="modal fade" id="assignAdminModal" tabindex="-1" aria-labelledby="assignAdminModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form id="assignAdminForm" method="POST" action="">
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

                            <!-- Table Section -->
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover dataTable js-exportable">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)"></th>
                                                <th>Sr. No.</th>
                                                <th>Full Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Status</th>
                                                <th>Registered Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th>Sr. No.</th>
                                                <th>Full Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Status</th>
                                                <th>Registered Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            @foreach($jobseekers->unique('id') as $index => $jobseeker)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="row-checkbox"
                                                        data-id="{{ $jobseeker->id }}"
                                                        data-name="{{ $jobseeker->name }}">
                                                </td>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $jobseeker->name }}</td>
                                                <td>{{ $jobseeker->email }}</td>
                                                <td>{{ $jobseeker->phone_code . '-' . $jobseeker->phone_number }}</td>
                                                <td>
                                                    <label class="switch">
                                                        <input type="checkbox"
                                                            id="statusCheckbox_{{ $jobseeker->id }}"
                                                            {{ $jobseeker->status === 'active' ? 'checked' : '' }}
                                                            onchange="toggleStatus(this, {{ $jobseeker->id }})">
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($jobseeker->created_at)->format('Y/m/d') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.jobseeker.view', $jobseeker->id) }}" class="btn btn-sm btn-primary">View</a>
                                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $jobseeker->id }})">Delete</button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
                    </div>
                </div>

                </div>
            </div>
        </div>
    </div>

    @include('admin.componants.footer')