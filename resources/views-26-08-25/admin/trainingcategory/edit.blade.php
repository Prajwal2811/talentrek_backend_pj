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
                                <span>JustDo Training Category Edit,</span>
                            </div>
                            
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Training Category Edit</h2>
                                </div>
                              <div class="body">
                                <form id="basic-form" method="post" action="{{ route('admin.trainingcategory.update', $trainingCategory->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <!-- Category Name -->
                                        <div class="form-group c_form_group col-md-6">
                                            <label>Category</label>
                                            <input type="text" class="form-control" name="category_name"
                                                placeholder="Enter category name"
                                                value="{{ old('category_name', $trainingCategory->category) }}" required>
                                            @error('category_name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Category Icon Upload -->
                                        <div class="form-group c_form_group col-md-6">
                                            <label>Category Icon</label>
                                            <input type="file" class="form-control" name="category_icon" accept="image/*">
                                            @error('category_icon')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror

                                            @if($trainingCategory->image_path)
                                                <div class="mt-2">
                                                    <p>Current Icon:</p>
                                                    <img src="{{ asset($trainingCategory->image_path) }}" alt="Icon" width="60" height="60">
                                                </div>
                                            @endif
                                        </div>

                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary theme-bg">Update Category</button>
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



    @include('admin.componants.footer')