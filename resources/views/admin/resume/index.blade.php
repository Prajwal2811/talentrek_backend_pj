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
                                <span>JustDo Resume Format Managment,</span>
                            </div>
                            <div class="col-xl-7 col-md-7 col-sm-12 text-md-right">

                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Resume Format Managment</h2>
                                </div>
                                <div class="body">
                                    @php
                                        $format = \App\Models\Resume::first();
                                    @endphp
                                    <form id="basic-form" method="post" novalidate action="{{ route('admin.resume.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $format->id ?? '' }}">
                                        <div class="row">
                                            <div class="form-group c_form_group col-md-12">
                                                <label>Resume Format</label>
                                                <input type="file" class="form-control-file" name="resume" accept=".pdf,.doc,.docx" onchange="handleFilePreview(this)">
                                                @error('resume')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                                <div class="mt-2">
                                                    @if(!empty($format->resume))
                                                        <a href="{{ asset($format->resume) }}" target="_blank" class="btn btn-sm btn-primary">
                                                            <i class="fa fa-eye" aria-hidden="true"></i> View Uploaded Format
                                                        </a>
                                                    @endif
                                                </div>
                                                <div id="file-preview" class="mt-3" style="display:none;">
                                                    <embed id="pdf-preview" type="application/pdf" width="100%" height="400px" style="display:none;" />
                                                    <div id="unsupported-preview" class="text-muted" style="display:none;">Preview not available for this file type. Please upload a PDF to preview.</div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <button type="submit" class="btn btn-primary theme-bg">Save Files</button>
                                            </div>
                                        </div>
                                    </form>

                                    <script>
                                        function handleFilePreview(input) {
                                            const file = input.files[0];
                                            const previewDiv = document.getElementById('file-preview');
                                            const pdfPreview = document.getElementById('pdf-preview');
                                            const unsupported = document.getElementById('unsupported-preview');

                                            if (!file) return;

                                            const fileType = file.type;

                                            previewDiv.style.display = 'block';

                                            if (fileType === 'application/pdf') {
                                                const reader = new FileReader();
                                                reader.onload = function (e) {
                                                    pdfPreview.src = e.target.result;
                                                    pdfPreview.style.display = 'block';
                                                    unsupported.style.display = 'none';
                                                };
                                                reader.readAsDataURL(file);
                                            } else {
                                                pdfPreview.style.display = 'none';
                                                unsupported.style.display = 'block';
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
        </div>

        @include('admin.componants.footer')