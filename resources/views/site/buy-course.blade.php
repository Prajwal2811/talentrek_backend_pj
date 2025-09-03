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
                    <h2 class="text-5xl font-bold text-white ml-[10%]">Training</h2>
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
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">
            <div class="flex-1">
                <div class="row">
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
                
                <form accept="multipart/form-data" action="{{ route('jobseeker.purchase-course') }}" method="POST">
                    @csrf
                    <input type="text" name="material_id" value="{{ $material->id }}" hidden>
                    <input type="text" name="training_type" value="{{ $material->training_type }}" hidden>

                    <div class="max-w-6xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2 space-y-6">
                            @if($material->training_type  === "online" || $material->training_type  === "classroom")
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Training mode</label>
                                    <input type="hidden" name="session_type" value="{{ $material->training_type }}">
                                    <select class="w-64 border border-gray-300 rounded px-3 py-2 text-sm" >
                                        <option value="" disabled>Select Training Mode</option>
                                        <option value="online" @if($material->training_type === 'online') selected @endif disabled>Online</option>
                                        <option value="classroom" @if($material->training_type === 'classroom') selected @endif disabled>Classroom</option>
                                    </select>
                                </div>
                                @error('session_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                               <div>
                                <h3 class="text-sm font-medium mb-2">Select batch</h3>
                                <table class="w-full border border-gray-200 rounded">
                                    <thead class="bg-gray-100 text-gray-700 text-left text-sm">
                                        <tr>
                                            <th class="px-4 py-2">Select</th>
                                            <th class="px-4 py-2">Batch No</th>
                                            <th class="px-4 py-2">Start Date</th>
                                            <th class="px-4 py-2">Timings</th>
                                            <th class="px-4 py-2">Duration</th>
                                            <th class="px-4 py-2">Strength</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($material->batches->isNotEmpty())
                                            @foreach ($material->batches as $batch)
                                                @php
                                                    $startDate = \Carbon\Carbon::parse($batch->start_date);
                                                    $now = \Carbon\Carbon::now();

                                                    // Parse duration
                                                    $duration = strtolower($batch->duration);
                                                    preg_match('/\d+/', $duration, $matches);
                                                    $durationValue = isset($matches[0]) ? (int)$matches[0] : 0;

                                                    // Calculate end date
                                                    if (str_contains($duration, 'day')) {
                                                        $endDate = $startDate->copy()->addDays($durationValue);
                                                    } elseif (str_contains($duration, 'month')) {
                                                        $endDate = $startDate->copy()->addMonths($durationValue);
                                                    } elseif (str_contains($duration, 'year')) {
                                                        $endDate = $startDate->copy()->addYears($durationValue);
                                                    } else {
                                                        $endDate = $startDate;
                                                    }

                                                    $isStarted = $startDate->isPast();
                                                    $isEnded = $endDate->isPast();
                                                @endphp

                                                <tr class="border-t {{ $isEnded ? 'bg-gray-200 text-gray-500' : 'cursor-pointer' }}" 
                                                    onclick="{{ $isEnded ? '' : 'selectRadio(' . $batch->id . ')' }}">
                                                    
                                                    <td class="px-4 py-3">
                                                        <input type="radio" class="form-radio" name="batch" value="{{ $batch->id }}"
                                                            id="batch-radio-{{ $batch->id }}" {{ $isEnded ? 'disabled' : '' }}>
                                                    </td>

                                                    <td class="px-4 py-3">{{ $batch->batch_no }}</td>

                                                    <td class="px-4 py-3">
                                                        {{ $startDate->format('d M Y') }}
                                                        @if ($isStarted && !$isEnded)
                                                            <div class="text-sm text-orange-600 font-semibold">Batch has already started</div>
                                                        @elseif ($isEnded)
                                                            <div class="text-sm text-red-600 font-semibold">Batch has already ended</div>
                                                        @endif
                                                    </td>

                                                    <td class="px-4 py-3">
                                                        {{ \Carbon\Carbon::parse($batch->start_timing)->format('h:i A') }} -
                                                        {{ \Carbon\Carbon::parse($batch->end_timing)->format('h:i A') }}
                                                    </td>

                                                    <td class="px-4 py-3">{{ $batch->duration }}</td>

                                                    <!-- Strength Column -->
                                                    <td class="px-4 py-3">
                                                        {{ $batch->strength ?? 'N/A' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center text-gray-500 py-4">No batches available.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                @error('batch')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <script>
                                    function selectRadio(id) {
                                        const radio = document.getElementById('batch-radio-' + id);
                                        if (radio) {
                                            radio.checked = true;
                                        }
                                    }
                                </script>
                            </div>

                                <div class="flex border rounded p-4 space-x-4">
                                    <img src="{{ $material->thumbnail ?? asset('asset/images/gallery/pic-4.png') }}"
                                        alt="Course" class="w-28 h-20 object-cover rounded">

                                    <div class="flex-1">
                                        <h4 class="font-semibold text-sm">{{ $material->training_title . ' (' . $material->training_type .')' }}</h4>

                                        <p class="text-xs text-gray-600 mt-1">
                                            {{ Str::limit($material->training_descriptions, 80) }}
                                        </p>

                                        <!-- <div class="flex items-center text-sm text-yellow-500 mt-1">
                                            ★★★★☆ 
                                            <span class="ml-1 text-gray-500 text-xs">(4.5) Rating</span>
                                        </div> -->
                                        <div class="flex items-center text-yellow-500">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($average))
                                                    ★
                                                @elseif ($i - $average < 1)
                                                    ☆
                                                @else
                                                    ☆
                                                @endif
                                            @endfor
                                            <span class="ml-1 text-gray-500 text-xs">({{ $average }}/5)</span>
                                            <span class="ml-1 text-gray-500 text-xs">Rating</span>
                                        </div>


                                        <div class="flex items-center space-x-2 mt-2">
                                            <span class="text-gray-400 line-through text-sm">SAR
                                                {{ $material->training_price  }}</span>
                                            <span class="text-sm font-bold text-blue-600">SAR
                                                {{ $material->training_offer_price  }}</span>
                                        </div>

                                        {{-- <button class="text-red-500 text-sm mt-2 hover:underline">🗑 Remove</button> --}}
                                    </div>
                                </div>
                            @elseif($material->training_type  === "recorded")
                                <div class="flex border rounded p-4 space-x-4">
                                    <img src="{{ $material->thumbnail ?? asset('asset/images/gallery/pic-4.png') }}"
                                        alt="Course" class="w-28 h-20 object-cover rounded">

                                    <div class="flex-1">
                                        <h4 class="font-semibold text-sm">{{ $material->training_title . ' (' . $material->training_type .')' }}</h4>

                                        <p class="text-xs text-gray-600 mt-1">
                                            {{ Str::limit($material->training_descriptions, 80) }}
                                        </p>

                                        <!-- <div class="flex items-center text-sm text-yellow-500 mt-1">
                                            ★★★★☆ 
                                            <span class="ml-1 text-gray-500 text-xs">(4.5) Rating</span>
                                        </div> -->
                                        <div class="flex items-center text-yellow-500">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($average))
                                                    ★
                                                @elseif ($i - $average < 1)
                                                    ☆
                                                @else
                                                    ☆
                                                @endif
                                            @endfor
                                            <span class="ml-1 text-gray-500 text-xs">({{ $average }}/5)</span>
                                            <span class="ml-1 text-gray-500 text-xs">Rating</span>
                                        </div>


                                        <div class="flex items-center space-x-2 mt-2">
                                            <span class="text-gray-400 line-through text-sm">SAR
                                                {{ $material->training_price  }}</span>
                                            <span class="text-sm font-bold text-blue-600">SAR
                                                {{ $material->training_offer_price  }}</span>
                                        </div>

                                        {{-- <button class="text-red-500 text-sm mt-2 hover:underline">🗑 Remove</button> --}}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Include Alpine.js -->
                        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

                        <div x-data="{ paymentMethod: 'card' }" class="space-y-4">

                            <!-- Select Payment Method -->
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

                            <!-- Card Payment Section -->
                            <div x-show="paymentMethod === 'card'" class="space-y-2 border p-4 rounded bg-gray-50">
                                <h4 class="text-sm font-medium mb-1">Enter Card Details:</h4>
                                <input type="text" placeholder="Cardholder Name"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                <input type="text" placeholder="Card Number" maxlength="19"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                <div class="flex space-x-2">
                                    <input type="text" placeholder="MM/YY"
                                        class="w-1/2 border border-gray-300 rounded px-3 py-2 text-sm">
                                    <input type="text" placeholder="CVV" maxlength="4"
                                        class="w-1/2 border border-gray-300 rounded px-3 py-2 text-sm">
                                </div>
                            </div>

                            <!-- UPI Section -->
                            <div x-show="paymentMethod === 'upi'" class="space-y-2 border p-4 rounded bg-gray-50">
                                <h4 class="text-sm font-medium mb-1">Enter UPI ID:</h4>
                                <input type="text" placeholder="yourname@upi"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            </div>

                            <!-- Bank Transfer Section -->
                            <div x-show="paymentMethod === 'bank'" class="space-y-2 border p-4 rounded bg-gray-50">
                                <h4 class="text-sm font-medium mb-1">Enter Bank Transfer Details:</h4>
                                <input type="text" placeholder="Account Holder Name"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                <input type="text" placeholder="Bank Name"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                <input type="text" placeholder="Transaction Reference Number"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            </div>

                            <!-- Apply Promocode Section -->
                            <div>
                                <h3 class="text-sm font-medium mb-2">Apply Promocode:</h3>
                                <div class="flex space-x-2">
                                    <input type="text" placeholder="Enter promocode for discount"
                                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                    <button class="bg-blue-600 text-white px-4 py-2 rounded text-sm">Apply</button>
                                </div>
                            </div>


                            @php
                                $actualPrice = $material->training_price;
                                $offerPrice = $material->training_offer_price;
                                $savedPrice = $actualPrice - $offerPrice;

                                $tax = round($offerPrice * 0.10, 2); // 10% tax, rounded to 2 decimal places
                                $total = $offerPrice + $tax;
                            @endphp

                            <!-- Billing Information -->
                            <div class="border rounded p-4 space-y-2">
                                <h3 class="text-sm font-medium border-b pb-2">Billing Information</h3>

                                <div class="flex justify-between text-sm">
                                    <span>Course total</span>
                                    <span>SAR {{ number_format($offerPrice, 2) }}</span>
                                </div>

                                <div class="flex justify-between text-sm">
                                    <span>Saved amount</span>
                                    <span>SAR {{ number_format($savedPrice, 2) }}</span>
                                </div>

                                <div class="flex justify-between text-sm">
                                    <span>Tax (10%)</span>
                                    <span>SAR {{ number_format($tax, 2) }}</span>
                                </div>

                                <div class="flex justify-between text-base font-semibold pt-2 border-t">
                                    <span>Total</span>
                                    <span>SAR {{ number_format($total, 2) }}</span>
                                </div>

                                <button type="submit"
                                    class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded mt-4 text-sm font-medium">
                                    Proceed to Checkout
                                </button>

                            </div>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tabs = document.querySelectorAll('.tab-link');
            const contents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => {
                        t.classList.remove('active-tab', 'border-blue-600', 'text-blue-600');
                        t.classList.add('text-gray-600', 'border-transparent');
                    });

                    contents.forEach(content => content.classList.add('hidden'));

                    tab.classList.add('active-tab', 'border-blue-600', 'text-blue-600');
                    tab.classList.remove('text-gray-600', 'border-transparent');

                    const tabName = tab.getAttribute('data-tab');
                    const activeContent = document.querySelector(`.tab-content[data-tab-content="${tabName}"]`);
                    if (activeContent) activeContent.classList.remove('hidden');
                });
            });
        });
    </script>
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
    </script>
    <style>
        .active-tab {
            border-bottom-color: #2563eb;
            color: #2563eb;
        }
    </style>
    @include('site.componants.footer')