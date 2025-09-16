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
                    <div id="message-container"></div>

                    @include('admin.errors')
                    <div class="block-header">
                        <div class="row clearfix">
                            <div class="col-xl-5 col-md-5 col-sm-12">
                                <h1>Hi, {{  Auth()->user()->name }}!</h1>
                                <span>JustDo Admin Management,</span>
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
                                        <style>
                                            td.disabled {
                                                pointer-events: none;
                                                opacity: 0.6;
                                                user-select: none;
                                            }
                                        </style>

                                        <table id="mainTable" class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Code</th>
                                                    <th>English</th>
                                                    <th>Arabic</th>
                                                    <th>Action</th> {{-- Added column for Save button --}}
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Code</th>
                                                    <th>English</th>
                                                    <th>Arabic</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                @foreach($language as $lang)
                                                    <tr data-id="{{ $lang->id }}">
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td class="disabled">
                                                            <input type="text" name="code" value="{{ $lang->code }}" class="form-control" readonly>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="english" value="{{ $lang->english }}" class="form-control english-input">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="arabic" value="{{ $lang->arabic }}" class="form-control arabic-input">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-primary save-btn">Save</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                            <script>
                                            $(document).ready(function () {
                                                $('.save-btn').on('click', function () {
                                                    const row = $(this).closest('tr');
                                                    const id = row.data('id');
                                                    const english = row.find('.english-input').val();
                                                    const arabic = row.find('.arabic-input').val();

                                                    // Remove any existing message row next to it
                                                    row.next('.message-row').remove();

                                                    $.ajax({
                                                        url: "{{ route('admin.language.update') }}",
                                                        method: "POST",
                                                        data: {
                                                            _token: "{{ csrf_token() }}",
                                                            id: id,
                                                            english: english,
                                                            arabic: arabic
                                                        },
                                                        success: function (response) {
                                                            row.after(`
                                                                <tr class="message-row">
                                                                    <td colspan="5">
                                                                        <div class="alert alert-success alert-dismissible fade show py-1 px-2 mb-0 text-center w-100" role="alert">
                                                                            ${response.message}
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            `);

                                                            setTimeout(() => {
                                                                row.next('.message-row').find('.alert').alert('close');
                                                                row.next('.message-row').remove();
                                                            }, 3000);
                                                        },
                                                        error: function (xhr) {
                                                            let errorMsg = 'Something went wrong.';
                                                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                                                errorMsg = xhr.responseJSON.message;
                                                            }

                                                            row.after(`
                                                                <tr class="message-row">
                                                                    <td colspan="5">
                                                                        <div class="alert alert-danger alert-dismissible fade show py-1 px-2 mb-0" role="alert">
                                                                            ${errorMsg}
                                                                            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            `);

                                                            setTimeout(() => {
                                                                row.next('.message-row').find('.alert').alert('close');
                                                                row.next('.message-row').remove();
                                                            }, 3000);
                                                        }
                                                    });
                                                });
                                            });
                                            </script>





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