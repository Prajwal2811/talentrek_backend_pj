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
                                <h2 class="text-2xl font-semibold mb-1">Sign in</h2>
                                <p class="text-sm text-gray-500 mb-6">Please enter your details to continue</p>
                                @if(session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('jobseeker.login.submit') }}">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="email" class="block text-sm font-medium mb-1">Email</label>
                                        <input type="email" name="email" id="email" placeholder="Email"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            />
                                            @error('email')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="block text-sm font-medium mb-1">Password</label>
                                        <input type="password" name="password" id="password" placeholder="Password"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            />
                                        @error('password')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        <div class="text-right mt-1">
                                            <a href="{{ route('jobseeker.forget-password')}}" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
                                        </div>
                                    </div>

                                    <div class="flex items-start mb-4">
                                        <input id="terms" type="checkbox"
                                            class="mt-1 mr-2 border-gray-300 rounded text-blue-600 focus:ring-blue-500" />
                                        <label for="terms" class="text-sm text-gray-600">
                                        I have read and agreed to
                                        <a href="#" class="text-blue-600 hover:underline">terms and conditions</a>
                                        </label>
                                    </div>

                                    <button type="submit" class="block text-center w-full py-2.5 text-white bg-blue-700 hover:bg-blue-800 rounded-md font-medium transition duration-150 mb-3">
                                        Sign in
                                    </button>

                                    <button type="button"
                                            class="w-full flex items-center justify-center py-2.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 mb-3">
                                        <img src="{{ asset('asset/images/google-icon.png') }}" alt="Google" class="w-5 h-5 mr-2">
                                        Sign in with Google
                                    </button>

                                    <div class="text-center text-gray-400 text-sm mb-3">- or -</div>

                                    <div class="text-center text-sm">
                                        Don’t have an account?
                                        <a href="{{ route('jobseeker.sign-up')}}" class="text-blue-600 hover:underline">Sign up</a>
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


<!-- 

@if(session('success_popup'))
<script>
    $(function () {
        Swal.fire({
            iconHtml: `
                <div style="border: 4px solid #4CAF50; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: auto;">
                    <span style="font-size: 48px; color: #4CAF50;">✔</span>
                </div>
            `,
            title: `
                <div style="font-size: 18px; font-weight: bold;">
                    Your profile has been submitted successfully<br>& waiting for approval!
                </div>
            `,
            html: `
                <div style="font-size: 14px; color: #555; margin-top: 10px;">
                    Once your profile is approved you will be notified via email, Thank you.
                </div>
            `,
            showConfirmButton: true,
            confirmButtonText: 'OK',
            allowOutsideClick: false,
            customClass: {
                popup: 'swal-wide'
            }
        });
    });
</script>
@endif

<style>
.swal-wide {
    width: 500px !important;
}
</style> -->