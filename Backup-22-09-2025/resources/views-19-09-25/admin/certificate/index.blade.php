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
                                <span>JustDo Certification Management,</span>
                            </div>
                            <div class="col-xl-7 col-md-7 col-sm-12 text-md-right">

                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Certification Management</h2>
                                </div>
                                @php
                                    $certficate = App\Models\CertificateTemplate::first();
                                @endphp
                                <div class="body">
                                     <form id="basic-form" method="post" novalidate action="{{ route('admin.certification.update') }}">
                                        @csrf
                                        <input type="text" value="{{ $certficate->id }}" name="id" hidden>
                                        <div class="row">
                                            <!-- RichTextEditor -->
                                                <link rel="stylesheet" href="https://richtexteditor.com/richtexteditor/rte_theme_default.css" />
                                                <script type="text/javascript" src="https://richtexteditor.com/richtexteditor/rte.js"></script>
                                                <script type="text/javascript" src="https://richtexteditor.com/richtexteditor/plugins/all_plugins.js"></script>
                                                <!-- Description -->
                                                <div class="form-group c_form_group col-md-12">
                                                    <label>Cerification Template (HTML content)</label>
                                                    <textarea id="div_editor1" name="description">{{ old('description', $certficate->template_html) }}</textarea>
                                                    @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                                                </div>
                                                <script>
                                                    var editor1 = new RichTextEditor("#div_editor1");
                                                </script>
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary theme-bg">Update Template</button>
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

        @include('admin.componants.footer')