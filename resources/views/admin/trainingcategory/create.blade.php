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
                                <span>JustDo Traning Category Management,</span>
                            </div>
                            
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Traning Category Management</h2>
                                </div>
                                <div class="body">
                                   <form id="basic-form" method="post" action="{{ route('admin.trainingcategory.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <!-- Category Name -->
                                            <div class="form-group c_form_group col-md-6">
                                                <label>Category</label>
                                                <input type="text" class="form-control" name="category_name"
                                                    placeholder="Enter category name"
                                                    value="{{ old('category_name') }}">
                                                @error('category_name')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <!-- Category Icon -->
                                            <div class="form-group c_form_group col-md-6">
                                                <label>Category Icon</label>
                                                <input type="file" class="form-control" name="category_icon" accept="image/*">
                                                @error('category_icon')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary theme-bg">Add Category</button>
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