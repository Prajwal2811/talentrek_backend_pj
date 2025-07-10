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
                                <span>JustDo Admin Creation,</span>
                            </div>
                            
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Admin Creation</h2>
                                </div>
                                <div class="body">
                                    <form id="basic-form" method="post" novalidate action="{{ route('admin.store') }}">
                                        @csrf

                                        <div class="row">
                                            <!-- Full Name -->
                                            <div class="form-group c_form_group col-md-6">
                                                <label>Full Name</label>
                                                <input type="text" class="form-control" name="full_name"
                                                    placeholder="Enter full name" value="{{ old('full_name') }}" required>
                                                @error('full_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <!-- Email Address -->
                                            <div class="form-group c_form_group col-md-6">
                                                <label>Email Address</label>
                                                <input type="email" class="form-control" name="email"
                                                    placeholder="Enter email address" value="{{ old('email') }}" required>
                                                @error('email')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <!-- Password -->
                                            <div class="form-group c_form_group col-md-6 position-relative">
                                                <label>Password</label>
                                                <input type="password" class="form-control" name="password" id="password" placeholder="Enter password" required>
                                                <span class="toggle-password" onclick="togglePassword('password')" style="position:absolute; top:38px; right:15px; cursor:pointer;">
                                                    <i class="fa fa-eye" id="eye-password"></i>
                                                </span>
                                                @error('password')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <!-- Confirm Password -->
                                            <div class="form-group c_form_group col-md-6 position-relative">
                                                <label>Confirm Password</label>
                                                <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Re-enter password" required>
                                                <span class="toggle-password" onclick="togglePassword('confirm_password')" style="position:absolute; top:38px; right:15px; cursor:pointer;">
                                                    <i class="fa fa-eye" id="eye-confirm_password"></i>
                                                </span>
                                                @error('confirm_password')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <!-- Notes -->
                                            <div class="form-group c_form_group col-md-12">
                                                <label>Admin Notes (optional)</label>
                                                <textarea class="form-control" rows="4" name="notes" placeholder="Enter any admin notes (optional)">{{ old('notes') }}</textarea>
                                                @error('notes')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <!-- Module Permissions -->
                                            <div class="form-group c_form_group col-md-12">
                                                <label>Module Permissions</label>
                                                <div class="row">
                                                    @php
                                                        $modules = [
                                                            // Main
                                                            'Dashboard',

                                                            // Admin
                                                            'Admin',

                                                            // User
                                                            'User',

                                                            // User Roles
                                                            'Jobseekers',
                                                            'Expat',
                                                            'Recruiters',
                                                            'Trainers',
                                                            'Assessors',
                                                            'Coach',
                                                            'Mentors',

                                                            // Site Activity
                                                            'Reviews',
                                                            'CMS',
                                                            'Subscriptions',
                                                            'Certification Template',
                                                            'Payments',
                                                            'Languages',
                                                            'Settings',
                                                            'Contact Support',
                                                            'Resume Format',
                                                            'Training Category',
                                                            'Testimonials',

                                                            // System Log
                                                            'Logs'
                                                        ];
                                                    @endphp





                                                    @foreach($modules as $module)
                                                        <div class="col-md-4">
                                                            <div class="form-check">
                                                                <input type="checkbox" name="permissions[]" value="{{ $module }}" class="form-check-input" id="perm-{{ $module }}">
                                                                <label class="form-check-label" for="perm-{{ $module }}">{{ $module }}</label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @error('permissions')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary theme-bg">Create Admin</button>
                                            </div>
                                        </div>
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