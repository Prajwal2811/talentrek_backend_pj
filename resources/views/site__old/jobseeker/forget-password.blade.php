@include('site.componants.header')

<body>
    <!-- LOADING AREA START ===== -->
    <div class="loading-area">
        <div class="loading-box"></div>
        <div class="loading-pic">
            <div class="wrapper">
                <div class="cssload-loader"></div>
            </div>
        </div>
    </div>

@include('site.componants.navbar')
        
            <div class="page-content">
                <div class="section-full site-bg-white">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 twm-log-reg-media-wrap">
                                <div class="twm-log-reg-media">
                                    <div class="twm-l-media">
                                        <img src="{{ asset('asset/images/login-bg.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                            <div class="container d-flex justify-content-center align-items-center min-vh-100">
                                <div class="w-full max-w-sm p-6">
                                    <h2 class="text-2xl font-semibold mb-1">Forgot password</h2>
                                    <p class="text-sm text-gray-500 mb-6">Please enter your registered mobile no. or email id</p>

                                    <form action="{{ route('submit.forget.password') }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="contact" class="block text-sm font-medium mb-1">Email / Mobile Number</label>
                                            <input type="text" name="contact" id="contact" placeholder="Enter email or mobile number"
                                                value="{{ old('contact') }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                            @error('contact')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <button type="submit"
                                            class="block w-full text-center text-sm font-medium text-white hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-100 px-4 py-2.5 bg-blue-700 hover:bg-blue-800 rounded-md transition duration-150">
                                            Send OTP
                                        </button>
                                    </form>

                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

@include('site.jobseeker.componants.footer')