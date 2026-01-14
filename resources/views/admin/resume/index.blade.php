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
                                                <!-- RichTextEditor -->
                                                <link rel="stylesheet" href="https://richtexteditor.com/richtexteditor/rte_theme_default.css" />
                                                <script type="text/javascript" src="https://richtexteditor.com/richtexteditor/rte.js"></script>
                                                <script type="text/javascript" src="https://richtexteditor.com/richtexteditor/plugins/all_plugins.js"></script>
                                                <!-- Description -->
                                                <div class="form-group c_form_group col-md-12">
                                                    <label>Resume Format (HTML content)</label>
                                                    <textarea id="div_editor1" name="resume">{{ old('resume', $format->resume) }}</textarea>
                                                    @error('resume') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                                <script>
                                                    var editor1 = new RichTextEditor("#div_editor1");
                                                </script>
                                                @error('resume')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="d-flex align-items-center gap-3">
                                                <h2 class="mb-0">Resume Template CV</h2>

                                                @if(!empty($resume->resume_file_path))
                                                    <a href="{{ asset($resume->resume_file_path) }}" 
                                                    target="_blank" 
                                                    class="btn btn-secondary btn-sm"
                                                    style="font-size: 12px; padding: 3px 10px; border-radius: 6px;">
                                                        View Resume Template CV
                                                    </a>
                                                @endif
                                            </div>





                                            <div class="form-group c_form_group col-md-12 mt-3">
                                                <label for="resume_file">Upload Resume File (PDF/DOC/DOCX)</label>
                                                <input type="file" class="form-control" name="resume_file" id="resume_file" 
                                                    accept=".pdf,.doc,.docx">
                                                @error('resume_file') 
                                                    <small class="text-danger">{{ $message }}</small> 
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-12 mt-3">
                                                <button type="submit" class="btn btn-primary theme-bg">Save Files</button>
                                            </div>
                                        </div>
                                          <!-- File Upload -->
                                        
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