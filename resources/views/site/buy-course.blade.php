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
            style="background-image: url('{{ asset('asset/images/banner/Training.png') }}');">
            <div class="absolute inset-0 bg-white bg-opacity-10"></div>
            <div class="relative z-10 container mx-auto px-4">
                <div class="space-y-2">
                    <h2 class="text-5xl font-bold text-white ml-[10%]">{{ langLabel('training') }}</h2>
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


    <main class="w-11/12 mx-auto py-8">

        @php
            use App\Models\Setting;
            use App\Models\Coupon;

            // Taxation info
            $taxation = Setting::first();

            $actualPrice = floatval($material->training_price);
            $offerPrice = floatval($material->training_offer_price ?? $actualPrice);
            $savedPrice = $actualPrice - $offerPrice;
            $taxRate = floatval($taxation->trainingMaterialTax ?? 0);
            $tax = round($offerPrice * ($taxRate / 100), 2);
            $total = round($offerPrice + $tax, 2);
        @endphp

        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">
            <div class="flex-1">

                <!-- Alerts -->
                <div class="row">
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
                    setTimeout(() => {
                        const successAlert = document.getElementById('successAlert');
                        const errorAlert = document.getElementById('errorAlert');
                        if (successAlert) successAlert.remove();
                        if (errorAlert) errorAlert.remove();
                    }, 3000);
                </script>

                <form accept="multipart/form-data" action="{{ route('jobseeker.purchase-course') }}" method="POST">
                    @csrf
                    <input type="hidden" name="material_id" value="{{ $material->id }}">
                    <input type="hidden" name="training_type" value="{{ $material->training_type }}">

                    <div class="max-w-6xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

                        <!-- Course Info & Batches -->
                        <div class="lg:col-span-2 space-y-6">
                            @if($material->training_type === "online" || $material->training_type === "classroom")
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1">{{ langLabel('training_mode') }}</label>
                                    <input type="hidden" name="session_type" value="{{ $material->training_type }}">
                                    <select class="w-64 border border-gray-300 rounded px-3 py-2 text-sm" disabled>
                                        <option value="" disabled>{{ langLabel('select_training_mode') }}</option>
                                        <option value="online" @if($material->training_type === 'online') selected @endif>
                                            Online</option>
                                        <option value="classroom" @if($material->training_type === 'classroom') selected
                                        @endif>Classroom</option>
                                    </select>
                                </div>

                                <h3 class="text-sm font-medium mb-2">{{ langLabel('select_batch') }}</h3>
                                <table class="w-full table-auto border border-gray-200 rounded text-sm text-center">
                                    <thead class="bg-gray-100 text-gray-700">
                                        <tr>
                                            <th class="px-4 py-2">{{ langLabel('select') }}</th>
                                            <th class="px-4 py-2">{{ langLabel('batch_no') }}</th>
                                            <th class="px-4 py-2">{{ langLabel('start_date') }}</th>
                                            <th class="px-4 py-2">{{ langLabel('timings') }}</th>
                                            <th class="px-4 py-2">{{ langLabel('duration') }}</th>
                                            <th class="px-4 py-2">{{ langLabel('strength') }}</th>
                                            <th class="px-4 py-2">{{ langLabel('days') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($material->batches as $batch)
                                            @php
                                                $start = \Carbon\Carbon::parse($batch->start_date);
                                                $now = \Carbon\Carbon::now();

                                                preg_match('/\d+/', strtolower($batch->duration), $m);
                                                $end = match (true) {
                                                    str_contains(strtolower($batch->duration), 'day') => $start->copy()->addDays($m[0] ?? 0),
                                                    str_contains(strtolower($batch->duration), 'month') => $start->copy()->addMonths($m[0] ?? 0),
                                                    str_contains(strtolower($batch->duration), 'year') => $start->copy()->addYears($m[0] ?? 0),
                                                    default => $start
                                                };

                                                $ended = $end->isPast();
                                                $started = $start->isPast() && !$ended;

                                                $strength = $batch->strength;
                                                $enrolled = App\Models\JobseekerTrainingMaterialPurchase::where('batch_id', $batch->id)
                                                    ->where('material_id', $material->id)
                                                    ->count();
                                                $availableStrength = $strength - $enrolled;
                                                $isFull = $availableStrength <= 0;
                                                $days = is_array(json_decode($batch->days)) ? implode(', ', json_decode($batch->days)) : $batch->days;
                                            @endphp
                                            <tr class="border-t {{ $ended || $isFull ? 'bg-gray-200 text-gray-500' : 'cursor-pointer hover:bg-gray-50' }}"
                                                onclick="{{ ($ended || $isFull) ? '' : 'selectRadio(' . $batch->id . ')' }}">
                                                <td class="px-4 py-2">
                                                    <input type="radio" name="batch" value="{{ $batch->id }}" class="form-radio"
                                                        id="batch-radio-{{ $batch->id }}" {{ ($ended || $isFull) ? 'disabled' : '' }}>
                                                </td>
                                                <td class="px-4 py-2">{{ $batch->batch_no }}</td>
                                                <td class="px-4 py-2">
                                                    {{ $start->format('d M Y') }}
                                                    @if($started)
                                                        <div class="text-xs text-orange-600 mt-1 font-semibold">
                                                            {{ langLabel('batch_has_started') }}</div>
                                                    @elseif($ended)
                                                        <div class="text-xs text-red-600 mt-1 font-semibold">
                                                            {{ langLabel('batch_ended') }}</div>
                                                    @elseif($isFull)
                                                        <div class="text-xs text-red-600 mt-1 font-semibold">
                                                            {{ langLabel('batch_full') }}</div>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2">
                                                    {{ \Carbon\Carbon::parse($batch->start_timing)->format('h:i A') }} -
                                                    {{ \Carbon\Carbon::parse($batch->end_timing)->format('h:i A') }}</td>
                                                <td class="px-4 py-2">{{ $batch->duration }}</td>
                                                <td class="px-4 py-2">{{ $batch->strength }}</td>
                                                <td class="px-4 py-2">{{ $days }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="px-4 py-2 text-gray-500">
                                                    {{ langLabel('no_batched_available') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <script>
                                    function selectRadio(id) {
                                        const radio = document.getElementById('batch-radio-' + id);
                                        if (radio) radio.checked = true;
                                    }
                                </script>
                            @endif
                            <!-- Course Card -->
                            <div class="flex border rounded p-4 space-x-4">
                                <img src="{{ $material->thumbnail ?? asset('asset/images/gallery/pic-4.png') }}"
                                    alt="Course" class="w-28 h-20 object-cover rounded">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-sm">
                                        {{ $material->training_title . ' (' . $material->training_type . ')' }}</h4>
                                    <p class="text-xs text-gray-600 mt-1">
                                        {{ Str::limit($material->training_descriptions, 80) }}</p>
                                    <div class="flex items-center text-yellow-500">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= floor($average)) ★
                                            @elseif ($i - $average < 1) ☆
                                            @else ☆
                                            @endif
                                        @endfor
                                        <span class="ml-1 text-gray-500 text-xs">({{ $average }}/5)</span>
                                    </div>
                                    <div class="flex items-center space-x-2 mt-2">
                                        <span class="text-gray-400 line-through text-sm">SAR
                                            {{ $material->training_price }}</span>
                                        <span class="text-sm font-bold text-blue-600">SAR
                                            {{ $material->training_offer_price }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment & Coupon Section -->
                        <div x-data="{ paymentMethod: 'card' }" class="space-y-4">

                            <!-- Coupon Section -->
                            <div>
                                <h3 class="text-sm font-medium mb-2">{{ langLabel('apply_promocode') }}:</h3>
                                <div class="flex space-x-2">
                                    <input type="text" id="coupon_code" placeholder="Enter promocode for discount"
                                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                    <button type="button" id="apply_coupon"
                                        class="bg-blue-600 text-white px-4 py-2 rounded text-sm">{{ langLabel('apply') }}</button>
                                </div>
                                <small id="coupon_message" class="text-red-500 mt-1 block"></small>

                                <input type="hidden" id="original_price" value="{{ $offerPrice }}">
                                <input type="hidden" id="tax_rate" value="{{ $taxRate }}">
                            </div>

                            <!-- Billing Information -->
                            <div class="border rounded p-4 space-y-2">
                                <h3 class="text-sm font-medium border-b pb-2">{{ langLabel('billing_information') }}
                                </h3>

                                <div class="flex justify-between text-sm">
                                    <span>{{ langLabel('course_total') }}</span>
                                    <span data-billing="course_total">SAR {{ number_format($offerPrice, 2) }}</span>
                                </div>

                                <div class="flex justify-between text-sm">
                                    <span>{{ langLabel('saved_amount') }}</span>
                                    <span data-billing="saved_amount">SAR {{ number_format($savedPrice, 2) }}</span>
                                </div>

                                {{-- ✅ Applied Coupon Row --}}
                                @if(session('applied_coupon'))
                                    <div class="flex justify-between text-sm text-green-600">
                                        <span>
                                            {{ langLabel('applied_coupon') }} ({{ session('applied_coupon.code') }})
                                        </span>
                                        <span data-billing="coupon_discount">
                                            - SAR {{ number_format(session('applied_coupon.discount_amount'), 2) }}
                                        </span>
                                    </div>
                                @endif

                                <div class="flex justify-between text-sm">
                                    <span>{{ langLabel('tax') }} ({{ $taxRate }}%)</span>
                                    <span data-billing="tax">SAR {{ number_format($tax, 2) }}</span>
                                </div>

                                <div class="flex justify-between text-base font-semibold pt-2 border-t">
                                    <span>{{ langLabel('total') }}</span>
                                    <span data-billing="total">SAR {{ number_format($total, 2) }}</span>
                                </div>

                                @auth('jobseeker')
                                    <!-- Show checkout button if logged in as jobseeker -->
                                    <button type="submit"
                                        class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded mt-4 text-sm font-medium">
                                        {{ langLabel('proceed_checkout') }}
                                    </button>
                                @else
                                    <div class="alert alert-danger alert-dismissible fade show mt-4 text-center" role="alert" style="text-align: justify;">
                                        <strong>Please log in as a Jobseeker</strong> to purchase a course.
                                    </div>

                                @endauth


                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        document.querySelector('form').addEventListener('submit', function (e) {
            const trainingType = "{{ $material->training_type }}";
            if (trainingType === "online") {
                const batchSelected = document.querySelector('input[name="batch"]:checked');
                if (!batchSelected) {
                    alert("Please select a batch before proceeding.");
                    e.preventDefault();
                }
            }
        });

        // AJAX Coupon Apply
        // AJAX Coupon Apply
        document.getElementById('apply_coupon').addEventListener('click', function () {
            const code = document.getElementById('coupon_code').value.trim();
            const originalPrice = parseFloat(document.getElementById('original_price').value);
            const taxRate = parseFloat(document.getElementById('tax_rate').value);
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
                        msgEl.textContent = data.message;
                        msgEl.classList.remove('text-green-500');
                        msgEl.classList.add('text-red-500');
                        return;
                    }

                    let discount = 0;
                    let discountMsg = '';

                    if (data.discount_type === 'fixed') {
                        discount = data.discount_value;
                        discountMsg = `SAR ${discount.toFixed(2)} off`;
                    } else if (data.discount_type === 'percentage') {
                        discount = (originalPrice * data.discount_value) / 100;
                        discountMsg = `${data.discount_value}% off`;
                    }

                    // ❌ Reject coupon if discount >= course price
                    if (discount >= originalPrice) {
                        msgEl.textContent = "This coupon is not valid (discount cannot be 100% or more).";
                        msgEl.classList.remove('text-green-500');
                        msgEl.classList.add('text-red-500');
                        return;
                    }

                    const discountedPrice = originalPrice - discount;
                    const tax = discountedPrice * (taxRate / 100);
                    const total = discountedPrice + tax;

                    document.querySelector('[data-billing="course_total"]').textContent = `SAR ${discountedPrice.toFixed(2)}`;
                    document.querySelector('[data-billing="saved_amount"]').textContent = `SAR ${discount.toFixed(2)}`;
                    document.querySelector('[data-billing="tax"]').textContent = `SAR ${tax.toFixed(2)}`;
                    document.querySelector('[data-billing="total"]').textContent = `SAR ${total.toFixed(2)}`;

                    msgEl.textContent = `Coupon applied: ${discountMsg}`;
                    msgEl.classList.remove('text-red-500');
                    msgEl.classList.add('text-green-500');
                })
                .catch(err => console.error(err));
        });



    </script>

    <style>
        .active-tab {
            border-bottom-color: #2563eb;
            color: #2563eb;
        }
    </style>
    @include('site.componants.footer')