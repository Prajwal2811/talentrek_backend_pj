
@php
    use App\Models\Setting;
    use App\Models\JobseekerCartItem;

    $cartItems = JobseekerCartItem::with([
        'material.reviews',
        'material.profilePicture'
    ])
    ->where('jobseeker_id', auth('jobseeker')->id())
    ->get();

    $taxation = Setting::first();
    $taxRate = floatval($taxation->trainingMaterialTax ?? 0);

    $courseTotal = 0.0;
    $savedTotal = 0.0;
    $hasEndedBatch = false;

    foreach ($cartItems as $item) {
        $material = $item->material;
        $actualPrice = floatval($material->training_price ?? 0);
        $offerPrice = floatval($material->training_offer_price ?? $actualPrice);
        $courseTotal += $offerPrice;
        $savedTotal += ($actualPrice - $offerPrice);

        $batchId = $item->batch_id;
        $selectedBatch = \App\Models\TrainingBatch::where('training_material_id', $item->material_id)
                            ->where('id', $batchId)
                            ->first();

        if($selectedBatch) {
            $end = isset($selectedBatch->end_date) ? \Carbon\Carbon::parse($selectedBatch->end_date) : \Carbon\Carbon::parse($selectedBatch->start_date);
            if($end->isPast()) $hasEndedBatch = true;
        }
    }

    $tax = round($courseTotal * ($taxRate / 100), 2);
    $total = round($courseTotal + $tax, 2);
@endphp
<div x-data="{ tab: 'cart' }">
            <div x-show="tab === 'cart'" x-cloak>
                <h2 class="text-xl font-semibold mb-4">My Cart</h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left: Course List -->
                        <div class="lg:col-span-2 space-y-4">
                            @forelse($cartItems as $item)
                                @php 
                                    $material = $item->material; 
                                    $batchId = $item->batch_id;
                                    $selectedBatch = \App\Models\TrainingBatch::where('training_material_id', $item->material_id)
                                                        ->where('id', $batchId)
                                                        ->first();

                                    if($selectedBatch) {
                                        $start = \Carbon\Carbon::parse($selectedBatch->start_date);
                                        $end = isset($selectedBatch->end_date) ? \Carbon\Carbon::parse($selectedBatch->end_date) : $start;
                                        $strength = $selectedBatch->strength;
                                        $enrolled = \App\Models\JobseekerTrainingMaterialPurchase::where('batch_id', $selectedBatch->id)
                                                        ->where('material_id', $item->material_id)
                                                        ->count();
                                        $availableSeats = $strength - $enrolled;
                                        $days = is_array(json_decode($selectedBatch->days)) ? implode(', ', json_decode($selectedBatch->days)) : $selectedBatch->days;
                                        $started = $start->isPast() && !$end->isPast();
                                        $ended = $end->isPast();
                                    }
                                @endphp

                                <!-- Hidden Inputs for Cart Submission -->
                                <input type="hidden" name="material_ids[]" value="{{ $material->id }}">
                                <input type="hidden" name="batch_ids[]" value="{{ $selectedBatch->id ?? '' }}">
                                <input type="hidden" name="training_types[]" value="{{ $material->training_type }}">
                                <input type="hidden" name="offer_prices[]" value="{{ $material->training_offer_price }}">
                                <input type="hidden" name="tax_rates[]" value="{{ $taxRate }}">
                                <input type="hidden" name="coupon_codes[]" value="">
                                <input type="hidden" name="coupon_types[]" value="">
                                <input type="hidden" name="coupon_amounts[]" value="0">

                                <div class="cart-item flex flex-col border rounded-lg p-4 gap-4">
                                    <div class="flex gap-4">
                                        <!-- Image & Remove Button -->
                                        <div class="flex flex-col items-start gap-2 w-48">
                                            <img src="{{ $material->thumbnail_file_path }}" alt="Course" class="w-48 h-48 object-cover rounded" />
                                            <button type="button" class="text-red-500 text-sm hover:underline mt-2 remove-item" data-id="{{ $item->id }}">
                                                Remove
                                            </button>
                                        </div>

                                        <!-- Course Info -->
                                        <div class="flex-1 space-y-3">
                                            <h4 class="font-semibold text-base">{{ $material->training_title }}</h4>
                                            <p class="text-sm text-gray-600">{{ Str::limit($material->training_sub_title, 150) }}</p>

                                            <!-- Rating -->
                                            <div class="flex items-center text-yellow-500 text-sm">
                                                @php
                                                    $reviews = $material->reviews;
                                                    $rating = $reviews->avg('ratings') ?? 0;
                                                    $ratingRounded = round($rating);
                                                    $reviewCount = $reviews->count();
                                                @endphp
                                                @for ($i = 1; $i <= 5; $i++)
                                                    {!! $i <= $ratingRounded ? '★' : '☆' !!}
                                                @endfor
                                                <span class="ml-2 text-gray-500 text-xs">({{ number_format($rating,1) }}/5 — {{ $reviewCount }} reviews)</span>
                                            </div>

                                            <!-- Price -->
                                            <div class="flex items-center gap-2 mt-2">
                                                @if(($material->training_price ?? 0) > ($material->training_offer_price ?? 0))
                                                    <span class="line-through text-sm text-gray-400">SAR {{ number_format($material->training_price,2) }}</span>
                                                @endif
                                                <span class="text-base font-semibold text-gray-800">SAR {{ number_format($material->training_offer_price ?? $material->training_price,2) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Selected Batch -->
                                    @if(isset($selectedBatch))
                                        <div class="border rounded-lg p-4 bg-white shadow-md w-full relative">
                                            @if($ended)
                                                <span class="absolute top-2 right-2 bg-red-100 text-red-700 text-xs font-semibold px-2 py-1 rounded">Ended</span>
                                            @elseif($started)
                                                <span class="absolute top-2 right-2 bg-yellow-100 text-yellow-700 text-xs font-semibold px-2 py-1 rounded">Ongoing</span>
                                            @else
                                                <span class="absolute top-2 right-2 bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded">Upcoming</span>
                                            @endif

                                            <h4 class="font-semibold text-gray-800">{{ $selectedBatch->batch_no }}</h4>
                                            <div class="grid grid-cols-2 gap-4 mt-2">
                                                <div>
                                                    <p class="text-gray-600 text-sm">
                                                        <strong>Start:</strong> {{ $start->format('d M Y') }} <br>
                                                        <strong>End:</strong> {{ $end->format('d M Y') }} <br>
                                                        <strong>Timing:</strong> {{ \Carbon\Carbon::parse($selectedBatch->start_timing)->format('h:i A') }} - {{ \Carbon\Carbon::parse($selectedBatch->end_timing)->format('h:i A') }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <p class="text-gray-600 text-sm">
                                                        <strong>Duration:</strong> {{ $selectedBatch->duration }} <br>
                                                        <strong>Days:</strong> {{ $days }} <br>
                                                        <strong>Seats Left:</strong>
                                                        @if($availableSeats <= 0)
                                                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded">Full</span>
                                                        @else
                                                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded">{{ $availableSeats }}</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-gray-500 text-center">Your cart is empty.</p>
                            @endforelse
                        </div>

                        <!-- Right: Promo + Billing -->
                        <div class="space-y-6">
                            <!-- Coupon Section -->
                            <div>
                                <h3 class="text-sm font-medium mb-2">{{ langLabel('apply_promocode') }}:</h3>
                                <div class="flex space-x-2">
                                    <input type="text" id="coupon_code" placeholder="Enter promocode for discount" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                    <button type="button" id="apply_coupon" class="bg-blue-600 text-white px-4 py-2 rounded text-sm">{{ langLabel('apply') }}</button>
                                </div>
                                <small id="coupon_message" class="text-red-500 mt-1 block"></small>

                                <input type="hidden" id="original_price" value="{{ number_format($courseTotal, 2, '.', '') }}">
                                <input type="hidden" id="tax_rate" value="{{ number_format($taxRate, 2, '.', '') }}">
                                <input type="hidden" id="saved_total" value="{{ number_format($savedTotal, 2, '.', '') }}">
                                <input type="hidden" name="coupon_type" id="coupon_type" value="">
                                <input type="hidden" name="coupon_code" id="coupon_code_hidden" value="">
                                <input type="hidden" name="coupon_amount" id="coupon_amount" value="">
                            </div>

                            <!-- Billing Information -->
                            <div class="border rounded p-4 space-y-2">
                                <h3 class="text-sm font-medium border-b pb-2">{{ langLabel('billing_information') }}</h3>

                                <div class="flex justify-between text-sm">
                                    <span>{{ langLabel('course_total') }}</span>
                                    <span data-billing="course_total">SAR {{ number_format($courseTotal, 2) }}</span>
                                </div>

                                <div class="flex justify-between text-sm">
                                    <span>{{ langLabel('saved_amount') }}</span>
                                    <span data-billing="saved_amount">SAR {{ number_format($savedTotal, 2) }}</span>
                                </div>

                                <div class="flex justify-between text-sm text-green-600 hidden" id="coupon_row">
                                    <span>{{ langLabel('applied_coupon') }}</span>
                                    <span data-billing="coupon_discount">- SAR 0.00</span>
                                </div>

                                <div class="flex justify-between text-sm">
                                    <span>{{ langLabel('tax') }} ({{ $taxRate }}%)</span>
                                    <span data-billing="tax">SAR {{ number_format($tax, 2) }}</span>
                                </div>

                                <div class="flex justify-between text-base font-semibold pt-2 border-t">
                                    <span>{{ langLabel('total') }}</span>
                                    <span data-billing="total">SAR {{ number_format($total, 2) }}</span>
                                </div>

                                <input type="hidden" name="amount_paid" value="{{ number_format($total, 2, '.', '') }}" id="input_total">

                                @auth('jobseeker')
                                    <button 
                                        type="submit" 
                                        class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded mt-4 text-sm font-medium" 
                                        id="checkoutBtn"
                                        @if($hasEndedBatch) disabled @endif
                                    >
                                        {{ langLabel('proceed_checkout') }}
                                    </button>

                                    @if($hasEndedBatch)
                                        <p class="text-red-500 text-sm mt-2">
                                            ⚠️ One or more batches in your cart have already ended. Please remove them to proceed.
                                        </p>
                                    @endif
                                @else
                                    <div class="alert alert-danger text-center mt-4">
                                        <strong>Please log in as a Jobseeker</strong> to purchase a course.
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Alpine.js & jQuery -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Coupon JS -->
        <script>
        document.getElementById('apply_coupon').addEventListener('click', async function () {
            const code = document.getElementById('coupon_code').value.trim();
            const originalPrice = parseFloat(document.getElementById('original_price').value) || 0;
            const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
            const originalSaved = parseFloat(document.getElementById('saved_total').value) || 0;
            const msgEl = document.getElementById('coupon_message');
            const inputTotal = document.getElementById('input_total');

            msgEl.textContent = '';
            msgEl.classList.remove('text-green-500', 'text-red-500');

            if (!code) {
                msgEl.textContent = "Please enter a coupon code.";
                msgEl.classList.add('text-red-500');
                return;
            }

            try {
                const res = await fetch("{{ route('apply.coupon') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ code: code })
                });
                const data = await res.json();

                if (!data.success) {
                    msgEl.textContent = data.message || 'Invalid coupon.';
                    msgEl.classList.add('text-red-500');
                    return;
                }

                let discount = 0;
                if (data.discount_type === 'fixed') discount = parseFloat(data.discount_value) || 0;
                else if (data.discount_type === 'percentage') discount = originalPrice * (parseFloat(data.discount_value || 0)/100);

                // Reject if discount >= original price
                if (discount >= originalPrice) {
                    msgEl.textContent = "This coupon is not valid (discount cannot be 100% or more).";
                    msgEl.classList.add('text-red-500');
                    return;
                }

                const discountedPrice = originalPrice - discount;
                const tax = discountedPrice * (taxRate/100);
                const total = discountedPrice + tax;

                document.querySelector('[data-billing="course_total"]').textContent = `SAR ${originalPrice.toFixed(2)}`;
                document.querySelector('[data-billing="coupon_discount"]').textContent = `- SAR ${discount.toFixed(2)}`;
                document.querySelector('[data-billing="tax"]').textContent = `SAR ${tax.toFixed(2)}`;
                document.querySelector('[data-billing="total"]').textContent = `SAR ${total.toFixed(2)}`;
                document.querySelector('[data-billing="saved_amount"]').textContent = `SAR ${(originalSaved + discount).toFixed(2)}`;

                document.getElementById('coupon_row').classList.remove('hidden');
                document.getElementById("coupon_type").value = data.discount_type;
                document.getElementById("coupon_code_hidden").value = code;
                document.getElementById("coupon_amount").value = discount.toFixed(2);
                if(inputTotal) inputTotal.value = total.toFixed(2);

                const discountMsg = data.discount_type === 'fixed' ? `SAR ${discount.toFixed(2)} off` : `${parseFloat(data.discount_value).toFixed(2)}% off`;
                msgEl.textContent = `Coupon applied: ${discountMsg}`;
                msgEl.classList.add('text-green-500');
            } catch(err) {
                console.error(err);
                msgEl.textContent = "An error occurred while applying the coupon. Please try again.";
                msgEl.classList.add('text-red-500');
            }
        });
        </script>

        <!-- Remove Item JS -->
        <script>
        $(document).ready(function () {
            let itemToRemoveId = null;
            let $clickedButton = null;

            $('.remove-item').on('click', function () {
                itemToRemoveId = $(this).data('id');
                $clickedButton = $(this);
                $('#removeConfirmModal').removeClass('hidden');
            });

            $('#cancelRemove').on('click', function () {
                itemToRemoveId = null;
                $clickedButton = null;
                $('#removeConfirmModal').addClass('hidden');
            });

            $('#confirmRemove').on('click', function () {
                if (!itemToRemoveId) return;

                $.ajax({
                    url: "{{ route('cart.remove', ':id') }}".replace(':id', itemToRemoveId),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            $('#removeConfirmModal').addClass('hidden');
                            localStorage.setItem('activeTab', 'cart');
                            location.reload();
                        } else {
                            alert(response.message || 'Remove failed');
                        }
                    },
                    error: function () {
                        alert('Something went wrong');
                    }
                });
            });

            if (localStorage.getItem('activeTab')) {
                document.addEventListener("alpine:init", () => {
                    Alpine.store('tabs', { active: localStorage.getItem('activeTab') });
                });
                localStorage.removeItem('activeTab');
            }
        });
        </script>

        <!-- Remove Confirmation Modal -->
        <div id="removeConfirmModal" class="fixed top-20 left-0 right-0 flex justify-center z-50 hidden">
            <div class="bg-white border border-gray-300 rounded-lg shadow-lg p-6 w-full max-w-sm text-center">
                <h3 class="text-lg font-semibold mb-4">Confirm Remove</h3>
                <p class="text-gray-600 mb-4">Are you sure you want to remove this item from your cart?</p>
                <div class="flex justify-center gap-4">
                    <button id="confirmRemove" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Yes, Remove</button>
                    <button id="cancelRemove" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">Cancel</button>
                </div>
            </div>
        </div>
        