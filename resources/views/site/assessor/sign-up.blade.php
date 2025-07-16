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
                                    <form class="space-y-4" action="{{ route('assessor.register.post') }}" method="POST">
                                        @csrf 
                                        <div>
                                            <label class="block text-sm mb-1">Email</label>
                                            <input type="email" name="email" placeholder="Email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" value="{{ old('email') }}">
                                            @error('email')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm mb-1">Mobile Number</label>
                                            <input type="text" name="phone_number" placeholder="Mobile Number" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" value="{{ old('phone_number') }}">
                                            @error('phone_number')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Password Field -->
                                        <div class="mb-3">
                                            <label for="password" class="block text-sm font-medium mb-1">Password</label>
                                            <div class="relative w-full">
                                                <input type="password" name="password" id="password" placeholder="Password"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10"/>

                                                <!-- Eye Toggle Button -->
                                                <button type="button" onclick="togglePassword('password', 'eye-icon')"
                                                    class="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-blue-600 focus:outline-none">
                                                    <i data-feather="eye" id="eye-icon" class="w-5 h-5"></i>
                                                </button>
                                                @error('password')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Confirm Password Field -->
                                        <div class="mb-3">
                                            <label for="confirm_password" class="block text-sm font-medium mb-1">Confirm Password</label>
                                            <div class="relative w-full">
                                                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10"/>

                                                <!-- Eye Toggle Button -->
                                                <button type="button" onclick="toggleConfirmPassword('confirm_password', 'eye-icon-confirm')"
                                                    class="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-blue-600 focus:outline-none">
                                                    <i data-feather="eye" id="eye-icon-confirm" class="w-5 h-5"></i>
                                                </button>
                                                @error('confirm_password')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="flex items-start">
                                            <input type="checkbox" class="mt-1 mr-2" id="terms">
                                            <label for="terms" class="text-sm text-gray-600">
                                            I have read and agreed to
                                            <a href="#" class="text-blue-600 hover:underline">terms and conditions</a>
                                            </label>
                                        </div>

                                        <button id="registerBtn" type="submit" class="w-full bg-blue-700 text-white py-2 rounded-md hover:bg-blue-800 transition">
                                            Register
                                        </button>


                                        <button type="button" class="w-full flex items-center justify-center gap-2 border border-gray-300 py-2 rounded-md hover:bg-gray-100">
                                            <img src="{{ asset('asset/images/google-icon.png') }}" alt="Google" class="w-5 h-5">
                                            <span>Sign in with Google</span>
                                        </button>

                                        <div class="text-center text-gray-500 text-sm mt-4">- or -</div>

                                        <div class="text-center text-sm mt-2">
                                            I have an account?
                                            <a href="{{ route('assessor.login')}}" class="text-blue-600 hover:underline">Sign in</a>
                                        </div>
                                    </form>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                const termsCheckbox = document.getElementById('terms');
                                                const registerBtn = document.getElementById('registerBtn');

                                                // Initial disable
                                                registerBtn.disabled = true;
                                                registerBtn.classList.add('opacity-50', 'cursor-not-allowed');

                                                termsCheckbox.addEventListener('change', function () {
                                                    if (this.checked) {
                                                        registerBtn.disabled = false;
                                                        registerBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                                                    } else {
                                                        registerBtn.disabled = true;
                                                        registerBtn.classList.add('opacity-50', 'cursor-not-allowed');
                                                    }
                                                });
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

    function toggleConfirmPassword() {
        const passwordInput = document.getElementById("confirm_password");
        const eyeIcon = document.getElementById("eye-icon-confirm");

        const isPassword = passwordInput.type === "password";
        passwordInput.type = isPassword ? "text" : "password";

        // Toggle icon
        eyeIcon.setAttribute("data-feather", isPassword ? "eye-off" : "eye");
        feather.replace(); // Refresh the icon
    }
</script>