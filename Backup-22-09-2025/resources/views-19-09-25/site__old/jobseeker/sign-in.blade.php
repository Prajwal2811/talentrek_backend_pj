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
                                <div class="row">
                                    <div class="col-12 ml-auto mr-auto text-center" style="margin: auto">
                                        @if (session()->has('success'))
                                            <div class="alert alert-success alert-dismissible fade show" id="successAlert">
                                                <strong>Success!</strong> {{ session('success') }}
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-12 ml-auto mr-auto text-center" style="margin: auto">
                                        @if (session()->has('error'))
                                            <div class="alert alert-danger alert-dismissible fade show" id="errorAlert">
                                                <strong>Oops!</strong> {{ session('error') }}
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <script>
                                    // Automatically hide alerts after 3 seconds
                                    setTimeout(() => {
                                        const successAlert = document.getElementById('successAlert');
                                        const errorAlert = document.getElementById('errorAlert');

                                        if (successAlert) {
                                            successAlert.classList.add('fade');
                                            setTimeout(() => successAlert.remove(), 500); // Remove from DOM after fade
                                        }
                                        if (errorAlert) {
                                            errorAlert.classList.add('fade');
                                            setTimeout(() => errorAlert.remove(), 500); // Remove from DOM after fade
                                        }
                                    }, 3000);
                                </script>
                                <h2 class="text-2xl font-semibold mb-1">Sign in</h2>
                                <p class="text-sm text-gray-500 mb-6">Please enter your details to continue</p>
                                <form method="POST" action="{{ route('jobseeker.login.submit') }}">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="email" class="block text-sm font-medium mb-1">Email</label>
                                        <input type="email" name="email" id="email" placeholder="Email"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
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

                                    <button type="submit" id="submit-btn" disabled
                                        class="block text-center w-full py-2.5 text-white bg-blue-400 rounded-md font-medium transition duration-150 mb-3 cursor-not-allowed">
                                        Sign in
                                    </button>

                                    <a href="{{ route('google.redirect', ['role' => 'jobseeker']) }}" class="w-full flex items-center justify-center py-2.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 mb-3">
                                        <img src="{{ asset('asset/images/google-icon.png') }}" alt="Google" class="w-5 h-5 mr-2">
                                        Sign in with Google
                                    </a>


                                    <div class="text-center text-gray-400 text-sm mb-3">- or -</div>

                                    <div class="text-center text-sm">
                                        Don’t have an account?
                                        <a href="{{ route('signup.form')}}" class="text-blue-600 hover:underline">Sign up</a>
                                    </div>
                                </form>

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

                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


@include('site.jobseeker.componants.footer')




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

