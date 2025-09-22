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
                                <span>JustDo Testimonial Creation,</span>
                            </div>
                            
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Testimonial Creation</h2>
                                </div>
                                <div class="body">
                                   <form id="testimonial-form" method="POST" action="{{ route('admin.testimonials.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <!-- Name -->
                                            <div class="form-group c_form_group col-md-6">
                                                <label>Name</label>
                                                <input type="text" class="form-control" name="name" placeholder="Enter name" value="{{ old('name') }}" >
                                                @error('name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <!-- Designation -->
                                            <div class="form-group c_form_group col-md-6">
                                                <label>Designation</label>
                                                <input type="text" class="form-control" name="designation" placeholder="Enter designation" value="{{ old('designation') }}">
                                                @error('designation')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <!-- Message -->
                                            <div class="form-group c_form_group col-md-12">
                                                <label>Testimonial Message</label>
                                                <textarea class="form-control" name="message" rows="4" placeholder="Write the testimonial message here..." >{{ old('message') }}</textarea>
                                                @error('message')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <!-- Image Upload -->
                                            <div class="form-group c_form_group col-md-12">
                                                <label>Upload Profile Picture (optional)</label>
                                                <input type="file" accept="image/*" class="form-control" name="profile_picture">
                                                @error('profile_picture')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary theme-bg">Add Testimonial</button>
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