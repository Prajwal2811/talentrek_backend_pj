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
                                <span>JustDo Setting Managment,</span>
                            </div>
                            <div class="col-xl-7 col-md-7 col-sm-12 text-md-right">

                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Logo Managment</h2>
                                </div>
                                <div class="body">
                                    @php
                                        $settings = \App\Models\Setting::first();
                                    @endphp
                                    <form id="basic-form" method="post" novalidate action="{{ route('admin.settings.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $settings->id ?? '' }}">

                                        <div class="row">
                                            <!-- Header Logo -->
                                            <div class="form-group c_form_group col-md-4">
                                                <label>Header Logo</label>
                                                <input type="file" class="form-control-file" name="header_logo" accept="image/*" onchange="previewImage(this, 'header_preview')">
                                                @error('header_logo')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror

                                                <div class="mt-2">
                                                    @if(!empty($settings->header_logo))
                                                        <img id="header_preview" src="{{ asset($settings->header_logo) }}" alt="Header Logo" style="max-height: 80px;">
                                                    @else
                                                        <img id="header_preview" src="#" alt="Preview" style="display:none; max-height: 80px;">
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Footer Logo -->
                                            <div class="form-group c_form_group col-md-4">
                                                <label>Footer Logo</label>
                                                <input type="file" class="form-control-file" name="footer_logo" accept="image/*" onchange="previewImage(this, 'footer_preview')">
                                                @error('footer_logo')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror

                                                <div class="mt-2">
                                                    @if(!empty($settings->footer_logo))
                                                        <img id="footer_preview" src="{{ asset($settings->footer_logo) }}" alt="Footer Logo" style="max-height: 80px;">
                                                    @else
                                                        <img id="footer_preview" src="#" alt="Preview" style="display:none; max-height: 80px;">
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Favicon -->
                                            <div class="form-group c_form_group col-md-4">
                                                <label>Favicon Icon</label>
                                                <input type="file" class="form-control-file" name="favicon_icon" accept="image/*" onchange="previewImage(this, 'favicon_preview')">
                                                @error('favicon_icon')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror

                                                <div class="mt-2">
                                                    @if(!empty($settings->favicon))
                                                        <img id="favicon_preview" src="{{ asset($settings->favicon) }}" alt="Favicon" style="max-height: 50px;">
                                                    @else
                                                        <img id="favicon_preview" src="#" alt="Preview" style="display:none; max-height: 50px;">
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <button type="submit" class="btn btn-primary theme-bg">Save Files</button>
                                            </div>
                                        </div>
                                    </form>

                                    <script>
                                            function previewImage(input, previewId) {
                                                const preview = document.getElementById(previewId);
                                                if (input.files && input.files[0]) {
                                                    const reader = new FileReader();
                                                    reader.onload = function(e) {
                                                        preview.src = e.target.result;
                                                        preview.style.display = 'block';
                                                    }
                                                    reader.readAsDataURL(input.files[0]);
                                                }
                                            }
                                        </script>


                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Social Media Management</h2>
                                </div>
                                <div class="body">
                                    @php
                                        $medias = \App\Models\SocialMedia::get();
                                    @endphp

                                    <form id="basic-form" method="post" novalidate action="{{ route('admin.settings.store-media') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="d-block mb-2">Social Media Links</label>
                                                <div class="media-group-wrapper">

                                                    @forelse($medias as $media)
                                                        <div class="row media-group align-items-end">
                                                            <div class="form-group col-md-4">
                                                                <input type="text" class="form-control" name="media_name[]" placeholder="e.g. Facebook"
                                                                    value="{{ old('media_name.' . $loop->index, $media->media_name) }}">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <input type="text" class="form-control" name="icon_class[]" placeholder="e.g. fab fa-facebook"
                                                                    value="{{ old('icon_class.' . $loop->index, $media->icon_class) }}">
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <input type="url" class="form-control" name="media_link[]" placeholder="e.g. https://facebook.com/yourpage"
                                                                    value="{{ old('media_link.' . $loop->index, $media->media_link) }}">
                                                            </div>
                                                            <div class="form-group col-md-1 text-right">
                                                                <button type="button" class="btn btn-danger btn-sm remove-btn" onclick="removeMediaGroup(this)">×</button>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div class="row media-group align-items-end">
                                                            <div class="form-group col-md-4">
                                                                <input type="text" class="form-control" name="media_name[]" placeholder="e.g. Facebook" value="">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <input type="text" class="form-control" name="icon_class[]" placeholder="e.g. fab fa-facebook" value="">
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <input type="url" class="form-control" name="media_link[]" placeholder="e.g. https://facebook.com/yourpage" value="">
                                                            </div>
                                                            <div class="form-group col-md-1 text-right">
                                                                <button type="button" class="btn btn-danger btn-sm remove-btn" onclick="removeMediaGroup(this)">×</button>
                                                            </div>
                                                        </div>
                                                    @endforelse

                                                </div>

                                                <button type="button" class="btn btn-sm btn-success mt-2" onclick="addMoreMedia()">+ Add More</button>

                                                <!-- HTML Message Placeholder -->
                                                <div id="media-warning" class="text-danger mt-2" style="display: none;">
                                                    At least one social media entry is required.
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <button type="submit" class="btn btn-primary theme-bg">Save Social Links</button>
                                            </div>
                                        </div>
                                    </form>

                                    <script>
                                        function addMoreMedia() {
                                            const wrapper = document.querySelector('.media-group-wrapper');
                                            const lastGroup = wrapper.querySelector('.media-group:last-child');
                                            const newGroup = lastGroup.cloneNode(true);

                                            // Clear input values
                                            newGroup.querySelectorAll('input').forEach(input => input.value = '');

                                            wrapper.appendChild(newGroup);

                                            // Hide warning if shown
                                            document.getElementById('media-warning').style.display = 'none';
                                        }

                                        function removeMediaGroup(button) {
                                            const group = button.closest('.media-group');
                                            const wrapper = document.querySelectorAll('.media-group');

                                            if (wrapper.length > 1) {
                                                group.remove();
                                                document.getElementById('media-warning').style.display = 'none';
                                            } else {
                                                document.getElementById('media-warning').style.display = 'block';
                                            }
                                        }
                                    </script>


                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        @include('admin.componants.footer')