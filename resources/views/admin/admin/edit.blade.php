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
                                <form id="basic-form" method="POST" novalidate action="{{ route('admin.update', $admin->id) }}">
                                    @csrf
                                    <div class="row">
                                        <!-- Full Name -->
                                        <div class="form-group c_form_group col-md-6">
                                            <label>Full Name</label>
                                            <input type="text" class="form-control" name="full_name"
                                                value="{{ old('full_name', $admin->name) }}" placeholder="Enter full name" required>
                                            @error('full_name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Email Address -->
                                        <div class="form-group c_form_group col-md-6">
                                            <label>Email Address</label>
                                            <input type="email" class="form-control" name="email"
                                                value="{{ old('email', $admin->email) }}" placeholder="Enter email address" required>
                                            @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
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

                                   <!-- Module Permissions -->
                                    <div class="form-group c_form_group col-md-12">
                                        <label>Module Permissions</label>
                                        <div class="row">

                                            <!-- Select All -->
                                            <div class="col-md-12 mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox" id="selectAllPermissions" class="form-check-input">
                                                    <label class="form-check-label" for="selectAllPermissions"><strong>Select All</strong></label>
                                                </div>
                                            </div>

                                            @php
                                                $modules = [
                                                    'Dashboard',
                                                    'User',
                                                    'Jobseekers',
                                                    'Expat',
                                                    'Recruiters',
                                                    'Trainers',
                                                    'Assessors',
                                                    'Coach',
                                                    'Mentors',
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
                                                    'Logs'
                                                ];
                                                $adminPermissions = $admin->permissions ?? [];
                                            @endphp

                                            @foreach($modules as $module)
                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input type="checkbox"
                                                            name="permissions[]"
                                                            value="{{ $module }}"
                                                            class="form-check-input permission-checkbox"
                                                            id="perm-{{ Str::slug($module, '-') }}"
                                                            {{ in_array($module, $adminPermissions) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="perm-{{ Str::slug($module, '-') }}">{{ $module }}</label>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>

                                        @error('permissions')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            const selectAll = document.getElementById('selectAllPermissions');
                                            const checkboxes = document.querySelectorAll('.permission-checkbox');

                                            // Set initial state of "Select All" if all are already checked
                                            function updateSelectAllState() {
                                                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                                                selectAll.checked = allChecked;
                                            }

                                            updateSelectAllState();

                                            // On Select All toggle
                                            selectAll.addEventListener('change', function () {
                                                checkboxes.forEach(cb => cb.checked = this.checked);
                                            });

                                            // Update Select All when individual checkbox is changed
                                            checkboxes.forEach(cb => {
                                                cb.addEventListener('change', updateSelectAllState);
                                            });
                                        });
                                    </script>


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