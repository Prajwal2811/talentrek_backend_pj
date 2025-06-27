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
                                <span>JustDo Admin Management,</span>
                            </div>
                            <div class="col-xl-7 col-md-7 col-sm-12 text-md-right">

                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Admin Management</h2>
                                </div>
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover dataTable js-exportable">
                                            <thead>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Full Name</th>
                                                    <th>Email</th>
                                                    {{-- <th>Status</th> --}}
                                                    <th>Created Date</th>
                                                    <th>Actions</th>
                                                    <th>Assign jobseekers</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Full Name</th>
                                                    <th>Email</th>
                                                    {{-- <th>Status</th> --}}
                                                    <th>Created Date</th>
                                                    <th>Actions</th>
                                                    <th>Assign jobseekers</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                @foreach($admins as $admin)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $admin->name }}</td>
                                                        <td>{{ $admin->email }}</td>
                                                        {{-- <td>
                                                            <label class="switch">
                                                                <input type="checkbox"
                                                                    {{ $admin->status === 'active' ? 'checked' : '' }}
                                                                    onchange="toggleStatus(this)"
                                                                    data-admin-id="{{ $admin->id }}">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </td> --}}

                                                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                                        <script>
                                                            function toggleStatus(checkbox) {
                                                                const adminId = $(checkbox).data('admin-id');
                                                                const status = checkbox.checked ? 'active' : 'inactive';

                                                                $.ajax({
                                                                    url: '{{ route('admin.changeStatus') }}',
                                                                    method: 'POST',
                                                                    data: {
                                                                        _token: '{{ csrf_token() }}',
                                                                        admin_id: adminId,
                                                                        status: status
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


                                                        <td>{{ \Carbon\Carbon::parse($admin->created_at)->format('d M Y, h:i A') }}
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('admin.edit', ['id' => $admin->id]) }}"
                                                                class="btn btn-sm btn-primary">Edit</a>
                                                           <!-- Delete Button -->
                                                            <button type="button" class="btn btn-sm btn-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#confirmDeleteModal{{ $admin->id }}">
                                                                Delete
                                                            </button>

                                                            <!-- Delete Confirmation Modal -->
                                                            <div class="modal fade" id="confirmDeleteModal{{ $admin->id }}"
                                                                tabindex="-1" aria-labelledby="confirmDeleteModalLabel{{ $admin->id }}"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="confirmDeleteModalLabel{{ $admin->id }}">
                                                                                Confirm Deletion
                                                                            </h5>
                                                                        </div>

                                                                        <div class="modal-body">
                                                                            Are you sure you want to delete <strong>{{ $admin->name }}</strong>?
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                                                                            <form action="{{ route('admin.destroy', $admin->id) }}" method="POST">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="btn btn-danger">
                                                                                    Yes, Delete
                                                                                </button>
                                                                            </form>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <!-- Button trigger modal -->
                                                            <!-- Button to trigger modal -->
                                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#jobseekerModal{{ $admin->id }}">
                                                                View Jobseekers
                                                            </button>

                                                            <!-- Modal -->
                                                          <div class="modal fade" id="jobseekerModal{{ $admin->id }}" tabindex="-1" aria-labelledby="jobseekerModalLabel{{ $admin->id }}" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="jobseekerModalLabel{{ $admin->id }}">Jobseekers List</h5>
                                                                    </div>

                                                                    @php
                                                                        $jobseekers = App\Models\Jobseekers::where('assigned_admin', $admin->id)->orderBy('id','desc')->get();
                                                                    @endphp

                                                                    <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                                                                        <!-- Success message placeholder -->
                                                                        <div id="jobseeker-success-msg-{{ $admin->id }}" class="alert alert-success d-none"></div>

                                                                        @if($jobseekers->isNotEmpty())
                                                                            <table class="table table-bordered">
                                                                                <thead class="table-light">
                                                                                    <tr>
                                                                                        <th>#</th>
                                                                                        <th>Name</th>
                                                                                        <th>Email</th>
                                                                                        <th>Action</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($jobseekers as $index => $jobseeker)
                                                                                        <tr id="row-{{ $jobseeker->id }}">
                                                                                            <td>{{ $index + 1 }}</td>
                                                                                            <td>{{ $jobseeker->name }}</td>
                                                                                            <td>{{ $jobseeker->email }}</td>
                                                                                            <td>
                                                                                                <button class="btn btn-danger btn-sm remove-jobseeker-btn" data-id="{{ $jobseeker->id }}" data-admin="{{ $admin->id }}">
                                                                                                    Remove
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        @else
                                                                            <p>No jobseekers found.</p>
                                                                        @endif
                                                                    </div>

                                                                    <!-- Modal Footer with Close Button -->
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>


                                                            <!-- AJAX Script -->
                                                            <script>
                                                                $(document).on('click', '.remove-jobseeker-btn', function () {
                                                                    const jobseekerId = $(this).data('id');
                                                                    const adminId = $(this).data('admin'); // get admin id
                                                                    const rowSelector = '#row-' + jobseekerId;
                                                                    const messageSelector = '#jobseeker-success-msg-' + adminId;

                                                                    $.ajax({
                                                                        url: '{{ route("admin.jobseekers.unassign") }}',
                                                                        method: 'POST',
                                                                        data: {
                                                                            _token: '{{ csrf_token() }}',
                                                                            id: jobseekerId
                                                                        },
                                                                        success: function (response) {
                                                                            if (response.success) {
                                                                                $(rowSelector).remove();
                                                                                $(messageSelector)
                                                                                    .text('Jobseeker unassigned successfully.')
                                                                                    .removeClass('d-none');

                                                                                // Hide message after 3 seconds
                                                                                setTimeout(() => {
                                                                                    $(messageSelector).addClass('d-none');
                                                                                }, 3000);
                                                                            } else {
                                                                                alert('Failed to unassign jobseeker.');
                                                                            }
                                                                        },
                                                                        error: function () {
                                                                            alert('An error occurred while unassigning jobseeker.');
                                                                        }
                                                                    });
                                                                });
                                                            </script>



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