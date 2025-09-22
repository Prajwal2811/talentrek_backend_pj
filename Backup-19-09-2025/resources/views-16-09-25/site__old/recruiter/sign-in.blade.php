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
                                @if(session('success'))
                                    <span id="successMessage" class="inline-flex items-center bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 gap-2">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <span class="text-sm font-medium">{{ session('success') }}</span>
                                    </span>
                                    <script>
                                        setTimeout(() => {
                                            const el = document.getElementById('successMessage');
                                            if (el) {
                                                el.classList.add('opacity-0'); 
                                                setTimeout(() => el.style.display = 'none', 2000); 
                                            }
                                        }, 10000); 
                                    </script>
                                @endif
                                <h2 class="text-2xl font-semibold mb-1">Sign in</h2>
                                <p class="text-sm text-gray-500 mb-6">Please enter your details to continue</p>
                                @if(session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('recruiter.login.submit') }}">
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
                                        <div class="relative w-full">
                                            <input type="password" name="password" id="password" placeholder="Password"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10"/>

                                            <!-- Eye Toggle Button -->
                                            <button type="button" onclick="togglePassword()"
                                                class="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-blue-600 focus:outline-none">
                                                <i data-feather="eye" id="eye-icon" class="w-5 h-5"></i>
                                            </button>
                                        </div>

                                            
                                        @error('password')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        <div class="text-right mt-1">
                                            <a href="{{ route('recruiter.forget.password')}}" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
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

                                    <button type="submit" id="submit-btn" disabled
                                        class="block text-center w-full py-2.5 text-white bg-blue-400 rounded-md font-medium transition duration-150 mb-3 cursor-not-allowed">
                                        Sign in
                                    </button>

                                    <a href="{{ route('google.redirect', ['role' => 'recruiter']) }}" type="button" class="w-full flex items-center justify-center gap-2 border border-gray-300 py-2 rounded-md hover:bg-gray-100">
                                        <img src="{{ asset('asset/images/google-icon.png') }}" alt="Google" class="w-5 h-5">
                                        <span>Sign in with Google</span>
                                    </a>

                                    <div class="text-center text-gray-400 text-sm mb-3">- or -</div>

                                    <div class="text-center text-sm">
                                        Don’t have an account?
                                        <a href="{{ route('recruiter.signup')}}" class="text-blue-600 hover:underline">Sign up</a>
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

<script src="https://unpkg.com/feather-icons"></script>
<script>
    feather.replace();
</script>
<script>
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.setAttribute('data-feather', 'eye-off');
        } else {
            passwordField.type = 'password';
            eyeIcon.setAttribute('data-feather', 'eye');
        }

        feather.replace(); // re-render the icon
    }
</script>
<script>
    const checkbox = document.getElementById('terms');
    const submitBtn = document.getElementById('submit-btn');

    checkbox.addEventListener('change', function () {
        if (this.checked) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-blue-400', 'cursor-not-allowed');
            submitBtn.classList.add('bg-blue-700', 'hover:bg-blue-800');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('bg-blue-400', 'cursor-not-allowed');
            submitBtn.classList.remove('bg-blue-700', 'hover:bg-blue-800');
        }
    });
</script>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success_popup'))
<script>
    $(function () {
        Swal.fire({
            iconHtml: `
                <div style="border: 3px solid #3B82F6; border-radius: 50%; width: 70px; height: 70px; display: flex; align-items: center; justify-content: center; margin: auto;">
                    <span style="font-size: 36px; color: #3B82F6;">✔</span>
                </div>
            `,
            title: `<div style="font-size: 20px; font-weight: 600; color: #1F2937;">Profile Submitted!</div>`,
            html: `
                <div style="font-size: 14px; color: #6B7280; margin-top: 8px;">
                    Your profile has been submitted successfully and is awaiting approval.<br>
                    You’ll be notified via email once it’s approved.
                </div>
            `,
            showConfirmButton: true,
            confirmButtonText: 'OK',
            confirmButtonColor: '#3B82F6',
            background: '#ffffff',
            allowOutsideClick: false,
            customClass: {
                popup: 'swal-modern'
            }
        });
    });
</script>
@endif


<style>
.swal2-popup.swal-modern {
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    font-family: 'Segoe UI', sans-serif;
}
</style>
