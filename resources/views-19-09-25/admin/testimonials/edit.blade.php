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
                                <form id="testimonial-form" method="POST" action="{{ route('admin.testimonials.update', $testimonial->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <!-- Name -->
                                        <div class="form-group c_form_group col-md-6">
                                            <label>Name</label>
                                            <input type="text" class="form-control" name="name" placeholder="Enter name" value="{{ old('name', $testimonial->name) }}" required>
                                            @error('name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Designation -->
                                        <div class="form-group c_form_group col-md-6">
                                            <label>Designation</label>
                                            <input type="text" class="form-control" name="designation" placeholder="Enter designation" value="{{ old('designation', $testimonial->designation) }}">
                                            @error('designation')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Message -->
                                        <div class="form-group c_form_group col-md-12">
                                            <label>Testimonial Message</label>
                                            <textarea class="form-control" name="message" rows="4" placeholder="Write the testimonial message here..." required>{{ old('message', $testimonial->message) }}</textarea>
                                            @error('message')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Existing Image -->
                                        @if ($testimonial->file_path)
                                            <div class="form-group col-md-12">
                                                <label>Current Profile Picture</label><br>
                                                <img src="{{ asset($testimonial->file_path) }}" alt="Current Image" class="img-thumbnail" width="150">
                                            </div>
                                        @endif

                                        <!-- Image Upload -->
                                        <div class="form-group c_form_group col-md-12">
                                            <label>Change Profile Picture (optional)</label>
                                            <input type="file" accept="image/*" class="form-control" name="profile_picture">
                                            @error('profile_picture')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary theme-bg">Update Testimonial</button>
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