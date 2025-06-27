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
                                <div class="w-full max-w-md px-6">
                                    <h2 class="text-2xl font-semibold mb-1">Sign up</h2>
                                    <p class="text-gray-600 mb-6">Please enter your details to continue</p>

                                    <form class="space-y-4" action="{{ route('register.post') }}" method="POST">
                                        @csrf 
                                        <div>
                                            <label class="block text-sm mb-1">Email</label>
                                            <input type="email" name="email" placeholder="Email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" >
                                            @error('email')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm mb-1">Mobile Number</label>
                                            <input type="text" name="phone_number" placeholder="Mobile Number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" >
                                            @error('phone_number')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm mb-1">Password</label>
                                            <input type="password" name="password" placeholder="Password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" >
                                            @error('password')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm mb-1">Confirm Password</label>
                                            <input type="password" name="confirm_password" placeholder="Confirm Password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" >
                                            @error('confirm_password')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="flex items-start">
                                            <input type="checkbox" class="mt-1 mr-2" id="terms">
                                            <label for="terms" class="text-sm text-gray-600">
                                            I have read and agreed to
                                            <a href="#" class="text-blue-600 hover:underline">terms and conditions</a>
                                            </label>
                                        </div>

                                        <button type="submit" class="w-full bg-blue-700 text-white py-2 rounded-md hover:bg-blue-800 transition">Register</button>

                                        <button type="button" class="w-full flex items-center justify-center gap-2 border border-gray-300 py-2 rounded-md hover:bg-gray-100">
                                            <img src="{{ asset('asset/images/google-icon.png') }}" alt="Google" class="w-5 h-5">
                                            <span>Sign in with Google</span>
                                        </button>

                                        <div class="text-center text-gray-500 text-sm mt-4">- or -</div>

                                        <div class="text-center text-sm mt-2">
                                            Don’t have an account?
                                            <a href="{{ route('jobseeker.sign-in')}}" class="text-blue-600 hover:underline">Sign in</a>
                                        </div>
                                    </form>
                                </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

@include('site.jobseeker.componants.footer')