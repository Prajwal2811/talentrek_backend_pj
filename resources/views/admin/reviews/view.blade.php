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
                                    <div class="row">
                                        <!-- Reviewer Name -->
                                        <div class="form-group c_form_group col-md-12">
                                            <label>Reviewer</label>
                                            <input type="text" class="form-control" value="{{ $review->reviewer_name }}" readonly>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- Reviewee Type -->
                                        <div class="form-group c_form_group col-md-6">
                                            <label>Reviewee Type</label>
                                            <input type="text" class="form-control" value="{{ ucfirst($review->user_type) }}" readonly>
                                        </div>

                                        <!-- Reviewee Name -->
                                        <div class="form-group c_form_group col-md-6">
                                            <label>Reviewee</label>
                                            <input type="text" class="form-control" value="{{ $revieweeName }}" readonly>
                                        </div>
                                    </div>

                                    <!-- Review Text -->
                                    <div class="form-group c_form_group">
                                        <label>Review</label>
                                        <textarea class="form-control" rows="4" readonly>{{ $review->reviews }}</textarea>
                                    </div>

                                    <!-- Rating -->
                                    <div class="form-group c_form_group">
                                        <label>Rating</label>
                                        <input type="text" class="form-control" value="{{ $review->ratings }}/5" readonly>
                                    </div>
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