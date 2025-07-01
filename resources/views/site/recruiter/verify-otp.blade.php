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
                                <h2 class="text-2xl font-semibold mb-1">Verify OTP</h2>
                                <p class="text-sm text-gray-500 mb-6">Enter OTP sent on registered email or mobile no.</p>

                                <form action="{{ route('recruiter.verify-otp.submit') }}" method="POST" id="otp-form">
                                    @csrf

                                    <input type="hidden" name="contact" value="{{ session('otp_value') }}">
                                    <input type="hidden" name="otp" id="otp-value"> <!-- Final OTP -->

                                    <label class="block text-sm font-medium mb-1">OTP</label>
                                    <div class="flex justify-between gap-2 mb-1">
                                        @for ($i = 0; $i < 6; $i++)
                                            <input type="text" inputmode="numeric" pattern="\d*" maxlength="1"
                                                oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                                class="otp-input w-10 h-12 text-center border border-gray-300 rounded-md bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                        @endfor
                                    </div>

                                    @error('otp')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror

                                    <div class="text-sm text-gray-600 mb-4">
                                        Didn't receive OTP? <a href="#" class="text-blue-600 font-medium">Resend OTP</a>
                                    </div>

                                    <button type="submit"
                                        class="block w-full text-center text-sm font-medium text-white hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-100 px-4 py-2.5 bg-blue-700 hover:bg-blue-800 rounded-md transition duration-150">
                                        Verify OTP
                                    </button>
                                </form>


                                <script>
                                    const otpInputs = document.querySelectorAll('.otp-input');
                                    const otpValue = document.getElementById('otp-value');

                                    otpInputs.forEach((input, index) => {
                                        input.addEventListener('input', () => {
                                            // Auto-move to next input
                                            if (input.value.length === 1 && index < otpInputs.length - 1) {
                                                otpInputs[index + 1].focus();
                                            }

                                            // Combine all inputs into hidden field
                                            let otp = '';
                                            otpInputs.forEach(i => otp += i.value);
                                            otpValue.value = otp;
                                        });

                                        // Move to previous on backspace
                                        input.addEventListener('keydown', (e) => {
                                            if (e.key === 'Backspace' && input.value === '' && index > 0) {
                                                otpInputs[index - 1].focus();
                                            }
                                        });
                                    });
                                </script>




                                <!-- <script>
                                    const otpInputs = document.querySelectorAll(".otp-input");
                                    const otpValueInput = document.getElementById("otp-value");
                                    const form = document.getElementById("otp-form");

                                    otpInputs.forEach((input, index) => {
                                        input.addEventListener("input", () => {
                                            if (input.value.length === 1 && index < otpInputs.length - 1) {
                                                otpInputs[index + 1].focus();
                                            }
                                        });
                                    });

                                    form.addEventListener("submit", (e) => {
                                        let otp = "";
                                        otpInputs.forEach(input => otp += input.value);
                                        otpValueInput.value = otp;
                                    });
                                </script> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('site.jobseeker.componants.footer')