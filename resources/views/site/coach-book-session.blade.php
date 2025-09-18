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
                    <h2 class="text-5xl font-bold text-white ml-[10%]">{{ langLabel('coaching') }}</h2>
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
                @php
                    // coach session fee
                    $sessionFee = $coachDetails->per_slot_price ?? ($coach->per_slot_price ?? 0);

                    // Tax model for coachs (always percentage)
                    $taxation = App\Models\Setting::first();
                    $taxRate = floatval($taxation->coachTax ?? 0);

                    // Calculate tax as percentage
                    $taxAmount = round(($sessionFee * $taxRate) / 100, 2);

                    $discount = 0;
                    $grandTotal = round($sessionFee + $taxAmount - $discount, 2);
                @endphp
                <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">
                    <!-- Left/Main Content -->
                    <div class="flex-1">
                        <!-- coach Header -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between p-6 rounded-lg bg-white">
                            <div class="flex items-center gap-6">
                                @php
                                    $profilePicture = optional($coach->profilePicture)->document_path ?? asset('default.jpg');
                                    $avgRating = number_format($coach->reviews->avg('ratings') ?: 0, 1);
                                    $reviewStars = floor($avgRating);
                                @endphp

                                <img src="{{ $profilePicture }}" alt="{{ $coach->name }}" class="w-28 h-28 rounded-full object-cover border" />

                                <div>
                                    <h1 class="text-xl font-semibold text-gray-900">{{ $coach->name }}</h1>
                                    <p class="text-sm text-gray-700 mt-0.5">
                                        {{ $coach->total_experience ?? '0 years' }} of experience
                                    </p>

                                    <div class="flex items-center mt-1 text-sm">
                                        <div class="flex text-[#f59e0b]">
                                            @for ($i = 0; $i < 5; $i++)
                                                @if ($i < $reviewStars)
                                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><polygon points="9.9,1.1 7.6,6.9 1.4,7.6 6.1,11.8 4.8,18 9.9,14.9 15,18 13.7,11.8 18.4,7.6 12.2,6.9"/></svg>
                                                @else
                                                    <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20"><polygon points="9.9,1.1 7.6,6.9 1.4,7.6 6.1,11.8 4.8,18 9.9,14.9 15,18 13.7,11.8 18.4,7.6 12.2,6.9"/></svg>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-gray-600">({{ $avgRating }}/5) <span class="text-xs">{{ langLabel('rating') }}</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alerts -->
                        <div class="row mt-4">
                            <div class="col-12 ml-auto mr-auto text-center" style="margin: auto">
                                @if (session()->has('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" id="errorAlert">
                                        <strong>Oops!</strong> {{ session('error') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <script>
                            // Hide alerts after 10s
                            setTimeout(() => {
                                const successAlert = document.getElementById('successAlert');
                                const errorAlert = document.getElementById('errorAlert');
                                if (successAlert) successAlert.remove();
                                if (errorAlert) errorAlert.remove();
                            }, 10000);
                        </script>

                        <!-- Booking Form -->
                        <section class="max-w-7xl mx-auto p-4">
                            <form method="POST" action="{{ route('coach-booking-submit') }}" x-data="{ paymentMethod: '' }" id="coachshipBookingForm">
                                @csrf

                                <div class="max-w-6xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                                    <div class="lg:col-span-2 space-y-6">
                                        <!-- coachship mode & Date -->
                                        <div class="grid grid-cols-2 gap-6 mb-6">
                                            <div>
                                                <input type="hidden" name="user_type"  value="coach" />
                                                <input type="hidden" name="user_id" id="coachIdInput" value="{{ $coachDetails->coach_id }}" />
                                                <input type="hidden" name="jobseeker_id" value="{{ optional(auth('jobseeker')->user())->id }}" />

                                                <label class="block font-semibold mb-2">{{ langLabel('coachship_mode') }}</label>
                                                <select id="modeSelect" name="mode" class="w-full border rounded px-4 py-2" required>
                                                    <option value="">{{ langLabel('select_mode') }}</option>
                                                    <option value="online">{{ langLabel('online') }}</option>
                                                    <option value="offline">{{ langLabel('offline') }}</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block font-semibold mb-2">{{ langLabel('select_date') }}</label>
                                                <input type="date" id="dateInput" name="date" class="w-full border rounded px-4 py-2" required />
                                            </div>
                                        </div>

                                        <!-- Time Slot Section -->
                                        <div class="mb-4">
                                            <label class="block font-semibold mb-4">{{ langLabel('select_time') }}</label>
                                            <div id="slotContainer" class="grid grid-cols-4 gap-4 text-center text-sm">
                                                <!-- Slots will be dynamically loaded here -->
                                            </div>
                                            <input type="text" name="slot_id" id="selectedSlotId" required />
                                            <input type="text" name="slot_time" id="selectedSlotTime" required />
                                        </div>
                                    </div>

                                    <!-- Right column: coupon + billing -->
                                    <div x-data="{ paymentMethod: 'card' }" class="space-y-4">
                                        <!-- Apply Promocode Section -->
                                        <div>
                                            <h3 class="text-sm font-medium mb-2">{{ langLabel('apply_promocode') }}:</h3>
                                            <div class="flex space-x-2">
                                                <input type="text" id="coupon_input" name="coupon_text" placeholder="{{ langLabel('enter_promocode_discount') }}"
                                                    class="w-full border rounded px-3 py-2 text-sm" />
                                                <button type="button" id="apply_coupon_btn" class="bg-blue-600 text-white px-4 py-2 rounded text-sm">{{ langLabel('apply') }}</button>
                                            </div>
                                            <small id="coupon_message" class="text-red-500 mt-1 block"></small>
                                        </div>

                                        <!-- Billing Information -->
                                        <div class="border rounded p-4 space-y-2 mt-4 bg-white">
                                            <h3 class="text-sm font-medium border-b pb-2">{{ langLabel('coach_session_billing') }}</h3>

                                            <div class="flex justify-between text-sm">
                                                <span>{{ langLabel('session_fee') }}</span>
                                                <span data-billing="course_total">SAR {{ number_format($sessionFee, 2) }}</span>
                                            </div>

                                            <div class="flex justify-between text-sm">
                                                <span>{{ langLabel('discount') }}</span>
                                                <span data-billing="saved_amount">SAR {{ number_format(0, 2) }}</span>
                                            </div>

                                            <div class="flex justify-between text-sm">
                                                <span>{{ langLabel('tax') }} ({{ $taxRate }}%)</span>
                                                <span data-billing="tax">SAR {{ number_format($taxAmount, 2) }}</span>
                                            </div>


                                            <div class="flex justify-between text-base font-semibold pt-2 border-t">
                                                <span>{{ langLabel('total_payable') }}</span>
                                                <span data-billing="total">SAR {{ number_format($grandTotal, 2) }}</span>
                                            </div>

                                            <!-- Hidden inputs to submit amounts -->
                                            <input type="hidden" id="original_price" name="original_price" value="{{ number_format($sessionFee, 2, '.', '') }}">
                                            <input type="hidden" id="tax_rate" name="tax_rate" value="{{ $taxRate }}">
                                            <input type="hidden" name="coupon_type" id="coupon_type" value="">
                                            <input type="hidden" name="coupon_code" id="coupon_code_hidden" value="">
                                            <input type="hidden" name="coupon_amount" id="coupon_amount" value="0">
                                            <input type="hidden" name="amount_paid" id="amount_paid" value="{{ number_format($grandTotal, 2, '.', '') }}">

                                            <button id="bookBtn" type="button"
                                                class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded mt-4 text-sm font-medium">
                                                {{ langLabel('proceed_checkout') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <!-- jQuery (used for validation) -->
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                            <script>
                            $(function () {
                                // Book button validation and submit
                                $('#bookBtn').on('click', function(e) {
                                    e.preventDefault();

                                    let mode = $('#modeSelect').val();
                                    let date = $('#dateInput').val();
                                    let slotId = $('#selectedSlotId').val();
                                    let slotTime = $('#selectedSlotTime').val();

                                    $('.error-message').remove();
                                    let isValid = true;

                                    if (!mode) {
                                        $('#modeSelect').after('<span class="text-red-500 error-message">Please select coachship mode</span>');
                                        isValid = false;
                                    }
                                    if (!date) {
                                        $('#dateInput').after('<span class="text-red-500 error-message">Please select a date</span>');
                                        isValid = false;
                                    }
                                    if (!slotId || !slotTime) {
                                        $('#slotContainer').after('<span class="text-red-500 error-message">Please select a time slot</span>');
                                        isValid = false;
                                    }

                                    if (isValid) {
                                        $('#coachshipBookingForm').submit();
                                    }
                                });

                                // Clear error on change
                                $('#modeSelect, #dateInput').on('change input', function () {
                                    $(this).next('.error-message').remove();
                                });
                                $('#slotContainer').on('click', 'button', function () {
                                    $('#slotContainer').next('.error-message').remove();
                                });
                            });

                            // Fetch available slots when mode or date changes
                            document.getElementById('modeSelect').addEventListener('change', fetchSlots);
                            document.getElementById('dateInput').addEventListener('change', fetchSlots);

                            function fetchSlots() {
                                const mode = document.getElementById('modeSelect').value;
                                const date = document.getElementById('dateInput').value;
                                const coachId = document.getElementById('coachIdInput').value;
                                const container = document.getElementById('slotContainer');

                                if (!mode || !date || !coachId) {
                                    container.innerHTML = '';
                                    return;
                                }

                                const url = `{{ route('get-available-slots') }}?mode=${encodeURIComponent(mode)}&date=${encodeURIComponent(date)}&coach_id=${encodeURIComponent(coachId)}`;

                                fetch(url)
                                    .then(response => response.json())
                                    .then(data => {
                                        container.innerHTML = '';

                                        if (!data.status || !data.slots || data.slots.length === 0) {
                                            container.innerHTML = `<p class="col-span-4 text-center text-gray-500">No slots available</p>`;
                                            return;
                                        }

                                        const bookedSlotIds = data.booked_slot_ids || [];
                                        data.slots.forEach(slot => {
                                            const isUnavailable = slot.is_unavailable;
                                            const isBooked = bookedSlotIds.includes(slot.id);

                                            const btn = document.createElement('button');
                                            btn.type = 'button';

                                            let baseClass = 'border rounded py-2 px-3 w-full transition';
                                            if (isUnavailable) {
                                                baseClass += ' text-red-600 border-red-500 cursor-not-allowed bg-red-50';
                                            } else if (isBooked) {
                                                baseClass += ' bg-yellow-200 border-yellow-500 text-yellow-800 cursor-not-allowed';
                                            } else {
                                                baseClass += ' text-gray-900 hover:bg-blue-400';
                                            }

                                            btn.className = baseClass;
                                            btn.disabled = isUnavailable || isBooked;

                                            btn.innerHTML = `
                                                <p class="font-medium slot-time">${slot.start_time} - ${slot.end_time}</p>
                                                <p class="text-xs ${isUnavailable ? 'text-red-600' : isBooked ? 'text-yellow-700' : 'text-green-600'}">
                                                    ${isUnavailable ? 'Unavailable' : isBooked ? 'Already Booked' : 'Available'}
                                                </p>
                                            `;

                                            if (!isUnavailable && !isBooked) {
                                                btn.dataset.available = 'true';
                                                btn.dataset.slotId = slot.id;
                                                btn.addEventListener('click', function () {
                                                    selectTimeSlot(this);
                                                });
                                            }

                                            container.appendChild(btn);
                                        });
                                    })
                                    .catch(() => {
                                        container.innerHTML = `<p class="col-span-4 text-center text-red-600">Error loading slots</p>`;
                                    });
                            }

                            function selectTimeSlot(selectedButton) {
                                document.querySelectorAll('#slotContainer button[data-available="true"]').forEach(btn => {
                                    btn.classList.remove('bg-blue-500', 'text-white');
                                    btn.classList.add('text-gray-900');
                                });

                                selectedButton.classList.add('bg-blue-500', 'text-white');
                                selectedButton.classList.remove('text-gray-900');

                                document.getElementById('selectedSlotId').value = selectedButton.dataset.slotId;
                                // read the time from the inner .slot-time
                                const timeText = selectedButton.querySelector('.slot-time') ? selectedButton.querySelector('.slot-time').innerText : '';
                                document.getElementById('selectedSlotTime').value = timeText;
                            }

                            // Coupon apply logic (POST to apply.coupon)
                            document.getElementById('apply_coupon_btn').addEventListener('click', function () {
                                const code = document.getElementById('coupon_input').value.trim();
                                const originalPrice = parseFloat(document.getElementById('original_price').value) || 0;
                                const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
                                const msgEl = document.getElementById('coupon_message');

                                if (!code) {
                                    msgEl.textContent = "Please enter a coupon code.";
                                    msgEl.classList.remove('text-green-500');
                                    msgEl.classList.add('text-red-500');
                                    return;
                                }

                                fetch("{{ route('apply.coupon') }}", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                    },
                                    body: JSON.stringify({ code: code })
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (!data.success) {
                                        msgEl.textContent = data.message || 'Coupon is invalid';
                                        msgEl.classList.remove('text-green-500');
                                        msgEl.classList.add('text-red-500');
                                        return;
                                    }

                                    let discount = 0;
                                    if (data.discount_type === 'fixed') {
                                        discount = parseFloat(data.discount_value) || 0;
                                    } else if (data.discount_type === 'percentage') {
                                        discount = (originalPrice * (parseFloat(data.discount_value) || 0)) / 100;
                                    }

                                    // Reject coupon if discount >= price
                                    if (discount >= originalPrice) {
                                        msgEl.textContent = "This coupon is not valid (discount cannot be 100% or more).";
                                        msgEl.classList.remove('text-green-500');
                                        msgEl.classList.add('text-red-500');
                                        return;
                                    }

                                    const discountedPrice = originalPrice - discount;
                                    const tax = (discountedPrice * taxRate) / 100;
                                    const total = discountedPrice + tax;

                                    document.querySelector('[data-billing="course_total"]').textContent = `SAR ${discountedPrice.toFixed(2)}`;
                                    document.querySelector('[data-billing="saved_amount"]').textContent = `SAR ${discount.toFixed(2)}`;
                                    document.querySelector('[data-billing="tax"]').textContent = `SAR ${tax.toFixed(2)}`;
                                    document.querySelector('[data-billing="total"]').textContent = `SAR ${total.toFixed(2)}`;

                                    // set hidden inputs
                                    document.getElementById("coupon_type").value = data.discount_type;
                                    document.getElementById("coupon_code_hidden").value = code;
                                    document.getElementById("coupon_amount").value = discount.toFixed(2);
                                    document.getElementById("amount_paid").value = total.toFixed(2);

                                    msgEl.textContent = `Coupon applied: ${data.discount_type === 'fixed' ? 'SAR ' + parseFloat(data.discount_value).toFixed(2) + ' off' : (parseFloat(data.discount_value) + '% off')}`;
                                    msgEl.classList.remove('text-red-500');
                                    msgEl.classList.add('text-green-500');
                                })
                                .catch(() => {
                                    msgEl.textContent = "Error applying coupon. Try again.";
                                    msgEl.classList.remove('text-green-500');
                                    msgEl.classList.add('text-red-500');
                                });
                            });
                            </script>
                        </section>
                    </div>
                </div>
            </main>
    <style>
        .active-tab {
            border-bottom-color: #2563eb;
            /* Tailwind blue-600 */
            color: #2563eb;
        }
    </style>
    </div>

    @include('site.componants.footer')