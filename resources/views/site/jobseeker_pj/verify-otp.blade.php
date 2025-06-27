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
                                <h2 class="text-2xl font-semibold mb-1">Verify OTP</h2>
                                <p class="text-sm text-gray-500 mb-6">Enter OTP sent on registered email or mobile no.</p>

                                <form>
                                <label class="block text-sm font-medium mb-1">OTP</label>
                                <div class="flex justify-between gap-2 mb-4">
                                    <input type="text" inputmode="numeric" maxlength="1" class="otp-input w-10 h-12 text-center border border-gray-300 rounded-md bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    <input type="text" inputmode="numeric" maxlength="1" class="otp-input w-10 h-12 text-center border border-gray-300 rounded-md bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    <input type="text" inputmode="numeric" maxlength="1" class="otp-input w-10 h-12 text-center border border-gray-300 rounded-md bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    <input type="text" inputmode="numeric" maxlength="1" class="otp-input w-10 h-12 text-center border border-gray-300 rounded-md bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    <input type="text" inputmode="numeric" maxlength="1" class="otp-input w-10 h-12 text-center border border-gray-300 rounded-md bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    <input type="text" inputmode="numeric" maxlength="1" class="otp-input w-10 h-12 text-center border border-gray-300 rounded-md bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                </div>

                                <div class="text-sm text-gray-600 mb-4">
                                    Didn't receive OTP? <a href="#" class="text-blue-600 font-medium">Resend OTP</a>
                                </div>


                                <a href="{{ route('jobseeker.reset-password')}}" class="block text-center text-sm font-medium text-white hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-100 px-4 py-2.5 bg-blue-700 hover:bg-blue-800 rounded-md transition duration-150">
                                    Verify OTP
                                </a>

                               
                                </form>

                                <script>
                                    const inputs = document.querySelectorAll(".otp-input");

                                    inputs.forEach((input, index) => {
                                        input.addEventListener("input", (e) => {
                                        let value = e.target.value;
                                        if (!/^\d$/.test(value)) {
                                            e.target.value = "";
                                            return;
                                        }
                                        if (value && index < inputs.length - 1) {
                                            inputs[index + 1].focus();
                                        }
                                        });

                                        input.addEventListener("keydown", (e) => {
                                        if (e.key === "Backspace" && input.value === "" && index > 0) {
                                            inputs[index - 1].focus();
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