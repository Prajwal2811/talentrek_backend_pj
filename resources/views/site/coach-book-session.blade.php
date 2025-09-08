@include('site.componants.header')

<body>
    <div class="loading-area">
        <div class="loading-box"></div>
        <div class="loading-pic">
            <div class="wrapper">
                <div class="cssload-loader"></div>
            </div>
        </div>
    </div>
    <style>
        .site-header.header-style-3.mobile-sider-drawer-menu {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: white;
        }
    </style>

    @include('site.componants.navbar')

    <div class="page-content">
        <div class="relative bg-center bg-cover h-[400px] flex items-center"
            style="background-image: url('{{ asset('asset/images/banner/Coaching.png') }}');">
            <div class="absolute inset-0 bg-white bg-opacity-10"></div>
            <div class="relative z-10 container mx-auto px-4">
                <div class="space-y-2">
                    <h2 class="text-5xl font-bold text-white ml-[10%]">Coaching</h2>
                </div>
            </div>
        </div>
    </div>


    <script>
        function toggleSection(id, iconId) {
            const section = document.getElementById(id);
            const icon = document.getElementById(iconId);
            section.classList.toggle('hidden');
            if (icon.classList.contains('rotate-180')) {
                icon.classList.remove('rotate-180');
            } else {
                icon.classList.add('rotate-180');
            }
        }
    </script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                Swal.fire({
                    icon: 'success',
                    title: '{{ session('success') }}',
                    html: `
                            <section class="flex flex-col items-center justify-center text-center">
                                <h2 class="text-xl font-medium mb-1">Your Coaching Session is Booked!</h2>
                                <p class="text-sm text-gray-600 mb-1">Booking ID: <span class="font-semibold">#{{ session('booking_id') }}</span></p>

                                <div class="text-sm text-gray-600 mb-4">
                                    <p>{{ \Carbon\Carbon::parse(session('slot_date'))->format('F d, Y') }}</p>
                                    <p>{{ session('slot_time') }}</p>
                                </div>

                                @if(session('coach_address'))
                                    <div class="text-sm text-gray-700 mt-2">
                                        <p class="font-semibold text-gray-800">Location:</p>
                                        <p>{{ session('coach_address') }}</p>
                                    </div>
                                @endif

                                @if(session('zoom_link'))
                                    <div class="flex items-center w-full max-w-md bg-gray-100 rounded-md px-4 py-2 shadow mt-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2H4m16 4V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2z" />
                                        </svg>
                                        <input type="text" readonly value="{{ session('zoom_link') }}" class="flex-1 bg-transparent text-sm outline-none" />
                                        <button onclick="navigator.clipboard.writeText('{{ session('zoom_link') }}')" class="bg-blue-700 text-white text-sm font-medium px-4 py-1 rounded hover:bg-blue-800 ml-2">
                                            Copy
                                        </button>
                                    </div>
                                @endif
                            </section>
                        `,
                    confirmButtonColor: '#3085d6',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('jobseeker.profile') }}';
                    }
                });
            });
        </script>
    @endif




    <main class="w-11/12 mx-auto py-8">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">
            <!-- Left/Main Content -->
            <div class="flex-1">


                <div class="flex flex-col md:flex-row md:items-center justify-between p-6 rounded-lg">
                    <div class="flex items-center gap-6">
                        @php
                            $profilePicture = optional($coach->profilePicture)->document_path ?? asset('default.jpg');
                            $avgRating = number_format($coach->reviews->avg('ratings'), 1);
                            $reviewStars = floor($avgRating);
                        @endphp

                        <img src="{{ $profilePicture }}" alt="{{ $coach->name }}"
                            class="w-28 h-28 rounded-full object-cover border" />

                        <div>
                            <h1 class="text-xl font-semibold text-gray-900">{{ $coach->name }}</h1>
                            <!-- <p class="text-sm text-gray-600 mt-1">{{ $coach->additionalInfo->designation ?? 'coach' }}</p> -->
                            <p class="text-sm text-gray-700 mt-0.5">
                                {{ $coach->total_experience ?? '0 years 0 months 0 days' }} of experience
                            </p>

                            <div class="flex items-center mt-1 text-sm">
                                <div class="flex text-[#f59e0b]">
                                    @for ($i = 0; $i < 5; $i++)
                                        @if ($i < $reviewStars)
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                <polygon
                                                    points="9.9,1.1 7.6,6.9 1.4,7.6 6.1,11.8 4.8,18 9.9,14.9 15,18 13.7,11.8 18.4,7.6 12.2,6.9" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                <polygon
                                                    points="9.9,1.1 7.6,6.9 1.4,7.6 6.1,11.8 4.8,18 9.9,14.9 15,18 13.7,11.8 18.4,7.6 12.2,6.9" />
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                                <span class="ml-2 text-gray-600">({{ $avgRating }}/5) <span
                                        class="text-xs">Rating</span></span>
                            </div>
                        </div>

                    </div>
                    <!-- <p class="text-sm text-gray-800 font-semibold mt-1">
                            SAR 89 <span class="text-xs text-gray-500">per coach session</span>
                        </p> -->
                </div>
            </div>
        </div>



        <div class="row">
            <div class="col-6 ml-auto mr-auto text-center" style="margin: auto">
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
            }, 10000);
        </script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

       <section class="max-w-7xl mx-auto p-4">
    <form method="POST" action="{{ route('coach-booking-submit') }}">
        @csrf
        <div class="max-w-6xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- coach mode & Date -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <input type="hidden" name="coach_id" id="coachName"
                            value="{{ $coachDetails->coach_id }}" />
                        <input type="hidden" name="jobseeker_id"
                            value="{{ optional(auth('jobseeker')->user())->id }}" />

                        <label class="block font-semibold mb-2">Assessment mode</label>
                        <select id="modeSelect" name="mode" class="w-full border rounded px-4 py-2" required>
                            <option value="">Online/Offline</option>
                            <option value="online">Online</option>
                            <option value="offline">Offline</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold mb-2">Select the Date</label>
                        <input type="date" id="dateInput" name="date"
                            class="w-full border rounded px-4 py-2" required />
                    </div>
                </div>

                <!-- Time Slot Section -->
                <div class="mb-4">
                    <label class="block font-semibold mb-4">Select the Time</label>
                    <div id="slotContainer" class="grid grid-cols-4 gap-4 text-center text-sm">
                        <!-- Slots will be dynamically loaded here -->
                    </div>
                    <input type="hidden" name="slot_id" id="selectedSlotId" required />
                    <input type="hidden" name="slot_time" id="selectedSlotTime" required />
                </div>
            </div>

            <!-- Right Column (Payment + Billing) -->
            <div x-data="{ paymentMethod: 'card' }" class="space-y-4">
                <!-- Payment Method -->
                <div>
                    <h3 class="text-sm font-medium mb-2">Select Payment Method:</h3>
                    <div class="space-y-2">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="payment_method" value="card" x-model="paymentMethod"
                                class="form-radio text-blue-600">
                            <span class="text-sm">Credit / Debit Card</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="payment_method" value="upi" x-model="paymentMethod"
                                class="form-radio text-blue-600">
                            <span class="text-sm">UPI / Online Payment</span>
                        </label>
                    </div>
                </div>

                <!-- Card Section -->
                <div x-show="paymentMethod === 'card'" class="space-y-2 border p-4 rounded bg-gray-50">
                    <h4 class="text-sm font-medium mb-1">Enter Card Details:</h4>
                    <input type="text" placeholder="Cardholder Name" class="w-full border rounded px-3 py-2 text-sm">
                    <input type="text" placeholder="Card Number" maxlength="19"
                        class="w-full border rounded px-3 py-2 text-sm">
                    <div class="flex space-x-2">
                        <input type="text" placeholder="MM/YY" class="w-1/2 border rounded px-3 py-2 text-sm">
                        <input type="text" placeholder="CVV" maxlength="4"
                            class="w-1/2 border rounded px-3 py-2 text-sm">
                    </div>
                </div>

                <!-- UPI Section -->
                <div x-show="paymentMethod === 'upi'" class="space-y-2 border p-4 rounded bg-gray-50">
                    <h4 class="text-sm font-medium mb-1">Enter UPI ID:</h4>
                    <input type="text" placeholder="yourname@upi" class="w-full border rounded px-3 py-2 text-sm">
                </div>

                <!-- Apply Promocode -->
                <div>
                    <h3 class="text-sm font-medium mb-2">Apply Promocode:</h3>
                    <div class="flex space-x-2">
                        <input type="text" id="couponCode" placeholder="Enter promocode for discount"
                            class="w-full border rounded px-3 py-2 text-sm">
                        <button type="button" id="applyCouponBtn"
                            class="bg-blue-600 text-white px-4 py-2 rounded text-sm">Apply</button>
                    </div>
                    <p id="couponMessage" class="text-sm mt-1"></p>
                    <input type="hidden" name="applied_coupon" id="appliedCoupon">
                    <input type="hidden" name="discount_amount" id="discountAmountInput">
                </div>

                @php
                    $slotPrice = $coachDetails->per_slot_price;
                    $taxModel = App\Models\Taxation::where('user_type', 'coach')->where('is_active', 1)->first();
                    $taxRate = $taxModel ? $taxModel->rate : 0;
                    $taxType = $taxModel ? $taxModel->type : 'percentage';
                    $taxAmount = $taxType === 'percentage' ? round(($slotPrice * $taxRate) / 100, 2) : round($taxRate, 2);
                    $grandTotal = $slotPrice + $taxAmount;
                @endphp

                <!-- Billing -->
                <div class="border rounded p-4 space-y-2 mt-4">
                    <h3 class="text-sm font-medium border-b pb-2">Coach Session Billing</h3>

                    <div class="flex justify-between text-sm">
                        <span>Session Fee</span>
                        <span>SAR {{ number_format($slotPrice, 2) }}</span>
                    </div>

                    <div id="discountRow" class="flex justify-between text-sm hidden">
                        <span>Discount</span>
                        <span id="discountAmount">- SAR 0.00</span>
                    </div>

                    <div class="flex justify-between text-sm">
                        <span>Tax @if($taxType === 'percentage') ({{ $taxRate }}%) @endif</span>
                        <span>SAR {{ number_format($taxAmount, 2) }}</span>
                    </div>

                    <div class="flex justify-between text-base font-semibold pt-2 border-t">
                        <span>Total Payable</span>
                        <span id="grandTotal">SAR {{ number_format($grandTotal, 2) }}</span>
                    </div>

                    <button id="bookBtn" type="submit"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded mt-4 text-sm font-medium">
                        Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function () {
        $('#applyCouponBtn').on('click', function () {
            let code = $('#couponCode').val();
            let sessionFee = {{ $slotPrice }};
            let taxAmount = {{ $taxAmount }};

            if (!code) {
                $('#couponMessage').text('Please enter a coupon code').removeClass('text-green-600').addClass('text-red-500');
                return;
            }

            $.ajax({
                url: "{{ route('jobseeker.coach.apply-coupon') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    coupon_code: code,
                    session_fee: sessionFee
                },
                success: function (response) {
                    if (response.status) {
                        let discount = response.discount;
                        let finalTotal = (sessionFee + taxAmount - discount).toFixed(2);

                        $('#couponMessage').text(response.message).removeClass('text-red-500').addClass('text-green-600');

                        $('#discountRow').removeClass('hidden');
                        $('#discountAmount').text("- SAR " + discount.toFixed(2));
                        $('#grandTotal').text("SAR " + finalTotal);

                        $('#appliedCoupon').val(code);
                        $('#discountAmountInput').val(discount);

                    } else {
                        $('#couponMessage').text(response.message).removeClass('text-green-600').addClass('text-red-500');
                        $('#discountRow').addClass('hidden');
                        $('#appliedCoupon').val('');
                        $('#discountAmountInput').val('');
                        $('#grandTotal').text("SAR " + (sessionFee + taxAmount).toFixed(2));
                    }
                },
                error: function () {
                    $('#couponMessage').text('Error applying coupon').removeClass('text-green-600').addClass('text-red-500');
                }
            });
        });
    });
    </script>
</section>


        </div>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tabs = document.querySelectorAll('.tab-link');
            const contents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Remove active class from all tabs
                    tabs.forEach(t => {
                        t.classList.remove('active-tab', 'border-blue-600', 'text-blue-600');
                        t.classList.add('text-gray-600', 'border-transparent');
                    });

                    // Hide all contents
                    contents.forEach(content => content.classList.add('hidden'));

                    // Add active class to clicked tab
                    tab.classList.add('active-tab', 'border-blue-600', 'text-blue-600');
                    tab.classList.remove('text-gray-600', 'border-transparent');

                    // Show corresponding content
                    const tabName = tab.getAttribute('data-tab');
                    const activeContent = document.querySelector(`.tab-content[data-tab-content="${tabName}"]`);
                    if (activeContent) activeContent.classList.remove('hidden');
                });
            });
        });
    </script>
    <style>
        .active-tab {
            border-bottom-color: #2563eb;
            /* Tailwind blue-600 */
            color: #2563eb;
        }
    </style>
    </div>

    @include('site.componants.footer')