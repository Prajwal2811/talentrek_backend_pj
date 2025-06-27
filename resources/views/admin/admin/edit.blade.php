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
                                <span>JustDo Admin Edit,</span>
                            </div>
                            
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Admin Edit</h2>
                                </div>
                                <div class="body">
                                 <form id="basic-form" method="post" novalidate action="{{ route('admin.update', $admin->id) }}">
                                    @csrf
                                    <!-- Full Name -->
                                    <div class="form-group c_form_group">
                                        <label>Full Name</label>
                                        <input type="text" class="form-control" name="full_name"
                                            placeholder="Enter full name"
                                            value="{{ old('full_name', $admin->name) }}" required>
                                        @error('full_name')
                                            <small class="text-danger">{{ $message }}</small>   
                                        @enderror
                                    </div>

                                    <!-- Email Address -->
                                    <div class="form-group c_form_group">
                                        <label>Email Address</label>
                                        <input type="email" class="form-control" name="email"
                                            placeholder="Enter email address"
                                            value="{{ old('email', $admin->email) }}" required>
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>   
                                        @enderror
                                    </div>

                                    <!-- Notes -->
                                    <div class="form-group c_form_group">
                                        <label>Admin Notes (optional)</label>
                                        <textarea class="form-control" rows="4" name="notes"
                                            placeholder="Enter any admin notes (optional)">{{ old('notes', $admin->notes) }}</textarea>
                                        @error('notes')
                                            <small class="text-danger">{{ $message }}</small>   
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary theme-bg">Update Admin</button>
                                </form>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById('eye-' + fieldId);

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>


    @include('admin.componants.footer')