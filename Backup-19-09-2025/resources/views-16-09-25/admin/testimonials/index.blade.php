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
                                    <h2>Testimonial Management</h2>
                                </div>
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover dataTable js-exportable">
                                            <thead>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Full Name</th>
                                                    <th>Designation</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Full Name</th>
                                                    <th>Designation</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                @foreach($testimonials as $testimonial)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $testimonial->name }}</td>
                                                        <td>{{ $testimonial->designation }}</td>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('admin.testimonials.edit', ['id' => $testimonial->id]) }}"
                                                                class="btn btn-sm btn-primary">Edit</a>
                                                           <!-- Delete Button -->
                                                            <button type="button" class="btn btn-sm btn-danger"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#confirmDeleteModal{{ $testimonial->id }}">
                                                                Delete
                                                            </button>

                                                            <!-- Delete Confirmation Modal -->
                                                            <div class="modal fade" id="confirmDeleteModal{{ $testimonial->id }}"
                                                                tabindex="-1" aria-labelledby="confirmDeleteModalLabel{{ $testimonial->id }}"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="confirmDeleteModalLabel{{ $testimonial->id }}">
                                                                                Confirm Deletion
                                                                            </h5>
                                                                        </div>

                                                                        <div class="modal-body">
                                                                            Are you sure you want to delete <strong>{{ $testimonial->name }}</strong>?
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                                                                            <form action="{{ route('admin.testimonials.delete', $testimonial->id) }}" method="POST">
                                                                                @csrf
                                                                                <button type="submit" class="btn btn-danger">
                                                                                    Yes, Delete
                                                                                </button>
                                                                            </form>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
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