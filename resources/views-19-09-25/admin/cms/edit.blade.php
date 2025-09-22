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
                                <span>JustDo Content Managment System,</span>
                            </div>
                        </div>
                    </div>

                    @if ($cms->slug === 'banner' || $cms->slug === "join-talentrek" || $cms->slug === 'web_banner')
                         <div class="row clearfix">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="header">
                                        <h2>Banner Section</h2>
                                    </div>
                                    <div class="body">
                                       <form id="banner-form" method="POST" action="{{ route('admin.cms.banner.update') }}" enctype="multipart/form-data" novalidate>
                                            @csrf
                                            <input type="hidden" class="form-control" name="slug" value="{{ $cms->slug }}" readonly>
                                            <div class="row">
                                               <!-- Section (Read-only) -->
                                            <div class="form-group c_form_group col-md-12">
                                                <label>Section</label>
                                                <input type="text" class="form-control" name="section" value="{{ old('section', $cms->section ) }}" readonly>
                                                @error('section') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>

                                            <!-- Section (Read-only) - Arabic -->
                                            <div class="form-group c_form_group col-md-12">
                                                <label>Section (Arabic)</label>
                                                <input type="text" class="form-control" name="ar_section" value="{{ old('ar_section', $cms->ar_section ) }}" readonly>
                                                @error('ar_section') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>

                                            <!-- Heading -->
                                            <div class="form-group c_form_group col-md-12">
                                                <label>Heading</label>
                                                <input type="text" class="form-control" name="heading" placeholder="Enter heading" value="{{ old('heading', $cms->heading ) }}" required>
                                                @error('heading') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>

                                            <!-- Heading - Arabic -->
                                            <div class="form-group c_form_group col-md-12">
                                                <label>Heading (Arabic)</label>
                                                <input type="text" class="form-control" name="ar_heading" placeholder="Enter heading in Arabic" value="{{ old('ar_heading', $cms->ar_heading ) }}" required>
                                                @error('ar_heading') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>

                                            <!-- Description -->
                                            <div class="form-group c_form_group col-md-12">
                                                <label>Description (HTML content)</label>
                                                <textarea id="div_editor1" name="description">{{ old('description', $cms->description) }}</textarea>
                                                @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>

                                            <!-- Description - Arabic -->
                                            <div class="form-group c_form_group col-md-12">
                                                <label>Description (HTML content - Arabic)</label>
                                                <textarea id="div_editor1_ar" name="ar_description">{{ old('ar_description', $cms->ar_description) }}</textarea>
                                                @error('ar_description') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>

                                            <!-- RichTextEditor scripts -->
                                            <link rel="stylesheet" href="https://richtexteditor.com/richtexteditor/rte_theme_default.css" />
                                            <script type="text/javascript" src="https://richtexteditor.com/richtexteditor/rte.js"></script>
                                            <script type="text/javascript" src="https://richtexteditor.com/richtexteditor/plugins/all_plugins.js"></script>

                                            <script>
                                                var editor1 = new RichTextEditor("#div_editor1");
                                                var editor1_ar = new RichTextEditor("#div_editor1_ar");
                                            </script>



                                                <!-- Banner Image Upload Only -->
                                                <div class="form-group c_form_group col-md-12">
                                                    @if ($cms->slug === 'banner' ) 
                                                        <label>Upload Banner Image</label>
                                                    @elseif($cms->slug === 'join-talentrek' )
                                                        <label>Upload Image</label>
                                                     @endif
                                                    <input type="file" class="form-control" name="banner_image" accept="image/*" onchange="previewUploadedImage(event)">
                                                    @error('banner_image') <small class="text-danger">{{ $message }}</small> @enderror

                                                    <!-- Show currently saved image -->
                                                    @if(!empty($cms->file_path))
                                                        <div class="mt-2">
                                                            <label>Current Uploaded Image:</label><br>
                                                            <img src="{{ $cms->file_path }}" alt="Banner Image" class="img-fluid" style="max-height: 200px;">
                                                        </div>
                                                    @endif

                                                    <!-- Preview newly selected image -->
                                                    <div id="uploadedPreview" class="mt-2" style="display:none;">
                                                        <label>Preview of Selected File:</label><br>
                                                        <img id="previewImage" class="img-fluid" style="max-height: 200px;">
                                                    </div>
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="col-md-12 mt-3">
                                                    <a  href="{{ route('admin.cms') }}" class="btn btn-dark theme-bg">Back</a>
                                                    @if ($cms->slug === 'banner' || $cms->slug === 'web_banner')
                                                        <button type="submit" class="btn btn-primary theme-bg">Update Banner</button>
                                                    @elseif($cms->slug === 'join-talentrek' )
                                                        <button type="submit" class="btn btn-primary theme-bg">Update Section</button>
                                                     @endif
                                                </div>
                                            </div>
                                        </form>

                                        <script>
                                            function previewUploadedImage(event) {
                                                const file = event.target.files[0];
                                                const previewContainer = document.getElementById('uploadedPreview');
                                                const previewImage = document.getElementById('previewImage');

                                                if (file) {
                                                    previewContainer.style.display = 'block';
                                                    previewImage.src = URL.createObjectURL(file);
                                                } else {
                                                    previewContainer.style.display = 'none';
                                                }
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($cms->slug === "countings")
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="header">
                                        <h2>Counting Section</h2>
                                    </div>
                                    <div class="body">
                                       <form id="banner-form" method="POST" action="{{ route('admin.cms.banner.update') }}" enctype="multipart/form-data" novalidate>
                                            @csrf
                                            <input type="hidden" class="form-control" name="slug" value="{{ $cms->slug }}" readonly>
                                            <div class="row">
                                                <!-- Section (Read-only) -->
                                                <div class="form-group c_form_group col-md-12">
                                                    <label>Section</label>
                                                    <input type="text" class="form-control" name="section" value="{{ old('section', $cms->section ) }}" readonly>
                                                    @error('section') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>

                                                <!-- Section (Read-only) - Arabic -->
                                                <div class="form-group c_form_group col-md-12">
                                                    <label>Section (Arabic)</label>
                                                    <input type="text" class="form-control" name="ar_section" value="{{ old('ar_section', $cms->ar_section ) }}" readonly>
                                                    @error('ar_section') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>

                                                <!-- RichTextEditor CSS/JS -->
                                                <link rel="stylesheet" href="https://richtexteditor.com/richtexteditor/rte_theme_default.css" />
                                                <script type="text/javascript" src="https://richtexteditor.com/richtexteditor/rte.js"></script>
                                                <script type="text/javascript" src="https://richtexteditor.com/richtexteditor/plugins/all_plugins.js"></script>

                                                <!-- Description -->
                                                <div class="form-group c_form_group col-md-12">
                                                    <label>Description (HTML content)</label>
                                                    <textarea id="div_editor1" name="description">{{ old('description', $cms->description) }}</textarea>
                                                    @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>

                                                <!-- Description - Arabic -->
                                                <div class="form-group c_form_group col-md-12">
                                                    <label>Description (HTML content - Arabic)</label>
                                                    <textarea id="div_editor1_ar" name="ar_description">{{ old('ar_description', $cms->ar_description) }}</textarea>
                                                    @error('ar_description') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>

                                                <script>
                                                    var editor1 = new RichTextEditor("#div_editor1");
                                                    var editor1_ar = new RichTextEditor("#div_editor1_ar");
                                                </script>


                                                <!-- Submit Button -->
                                                <div class="col-md-12 mt-3">
                                                    <a  href="{{ route('admin.cms') }}" class="btn btn-dark theme-bg">Back</a>
                                                    @if ($cms->slug === 'banner' ) 
                                                        <button type="submit" class="btn btn-primary theme-bg">Update Banner</button>
                                                    @elseif($cms->slug === 'join-talentrek' || $cms->slug === 'countings')
                                                        <button type="submit" class="btn btn-primary theme-bg">Update Section</button>
                                                     @endif
                                                </div>
                                            </div>
                                        </form>

                                        <script>
                                            function previewUploadedImage(event) {
                                                const file = event.target.files[0];
                                                const previewContainer = document.getElementById('uploadedPreview');
                                                const previewImage = document.getElementById('previewImage');

                                                if (file) {
                                                    previewContainer.style.display = 'block';
                                                    previewImage.src = URL.createObjectURL(file);
                                                } else {
                                                    previewContainer.style.display = 'none';
                                                }
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($cms->slug === "course-overview" || $cms->slug === "benefits-of-training" || $cms->slug === "mentorship-overview" || $cms->slug === "benefits-of-mentorship" || $cms->slug === "terms-and-conditions" || $cms->slug === "privacy-policy")
                        <div class="row clearfix">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="header">
                                        <h2>Content Managment System</h2>
                                    </div>
                                    <div class="body">
                                       <form id="banner-form" method="POST" action="{{ route('admin.cms.banner.update') }}" enctype="multipart/form-data" novalidate>
                                            @csrf
                                            <input type="hidden" class="form-control" name="slug" value="{{ $cms->slug }}" readonly>
                                            <div class="row">
                                                <!-- Section (Read-only) -->
                                                <div class="form-group c_form_group col-md-12">
                                                    <label>Section</label>
                                                    <input type="text" class="form-control" name="section" value="{{ old('section', $cms->section ) }}" readonly>
                                                    @error('section') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>

                                                <!-- Section (Read-only) - Arabic -->
                                                <div class="form-group c_form_group col-md-12">
                                                    <label>Section (Arabic)</label>
                                                    <input type="text" class="form-control" name="ar_section" value="{{ old('ar_section', $cms->ar_section ) }}" readonly>
                                                    @error('ar_section') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>

                                                <!-- Heading -->
                                                <div class="form-group c_form_group col-md-12">
                                                    <label>Heading</label>
                                                    <input type="text" class="form-control" name="heading" placeholder="Enter heading" value="{{ old('heading', $cms->heading ) }}" required>
                                                    @error('heading') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>

                                                <!-- Heading - Arabic -->
                                                <div class="form-group c_form_group col-md-12">
                                                    <label>Heading (Arabic)</label>
                                                    <input type="text" class="form-control" name="ar_heading" placeholder="Enter heading in Arabic" value="{{ old('ar_heading', $cms->ar_heading ) }}" required>
                                                    @error('ar_heading') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>

                                                <!-- RichTextEditor CSS/JS -->
                                                <link rel="stylesheet" href="https://richtexteditor.com/richtexteditor/rte_theme_default.css" />
                                                <script type="text/javascript" src="https://richtexteditor.com/richtexteditor/rte.js"></script>
                                                <script type="text/javascript" src="https://richtexteditor.com/richtexteditor/plugins/all_plugins.js"></script>

                                                <!-- Description -->
                                                <div class="form-group c_form_group col-md-12">
                                                    <label>Description (HTML content)</label>
                                                    <textarea id="div_editor1" name="description">{{ old('description', $cms->description) }}</textarea>
                                                    @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>

                                                <!-- Description - Arabic -->
                                                <div class="form-group c_form_group col-md-12">
                                                    <label>Description (HTML content - Arabic)</label>
                                                    <textarea id="div_editor1_ar" name="ar_description">{{ old('ar_description', $cms->ar_description) }}</textarea>
                                                    @error('ar_description') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>

                                                <script>
                                                    var editor1 = new RichTextEditor("#div_editor1");
                                                    var editor1_ar = new RichTextEditor("#div_editor1_ar");
                                                </script>


                                                <!-- Submit Button -->
                                                <div class="col-md-12 mt-3">
                                                    <a  href="{{ route('admin.cms') }}" class="btn btn-dark theme-bg">Back</a>
                                                    @if ($cms->slug === 'banner' ) 
                                                        <button type="submit" class="btn btn-primary theme-bg">Update Banner</button>
                                                    @elseif($cms->slug === 'join-talentrek' || $cms->slug === 'countings' || $cms->slug === 'course-overview' || $cms->slug === 'benefits-of-training' || $cms->slug === "mentorship-overview" || $cms->slug === "benefits-of-mentorship" || $cms->slug === "terms-and-conditions" || $cms->slug === "privacy-policy")
                                                        <button type="submit" class="btn btn-primary theme-bg">Update Section</button>
                                                     @endif
                                                </div>
                                            </div>
                                        </form>

                                        <script>
                                            function previewUploadedImage(event) {
                                                const file = event.target.files[0];
                                                const previewContainer = document.getElementById('uploadedPreview');
                                                const previewImage = document.getElementById('previewImage');

                                                if (file) {
                                                    previewContainer.style.display = 'block';
                                                    previewImage.src = URL.createObjectURL(file);
                                                } else {
                                                    previewContainer.style.display = 'none';
                                                }
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                   
                </div>
            </div>
        </div>
    </div>

    @include('admin.componants.footer')