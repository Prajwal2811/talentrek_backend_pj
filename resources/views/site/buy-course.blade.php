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
                                    <table class="w-full table-auto border border-gray-200 rounded text-sm text-center">
                                        <thead class="bg-gray-100 text-gray-700">
                                            <tr>
                                                <th class="px-4 py-2">Select</th>
                                                <th class="px-4 py-2">Batch No</th>
                                                <th class="px-4 py-2">Start Date</th>
                                                <th class="px-4 py-2">Timings</th>
                                                <th class="px-4 py-2">Duration</th>
                                                <th class="px-4 py-2">Strength</th>
                                                <th class="px-4 py-2">Days</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($material->batches as $batch)
                                                @php
                                                    $start = \Carbon\Carbon::parse($batch->start_date);
                                                    $now = \Carbon\Carbon::now();

                                                    preg_match('/\d+/', strtolower($batch->duration), $m);
                                                    $end = match (true) {
                                                        str_contains(strtolower($batch->duration),'day') => $start->copy()->addDays($m[0] ?? 0),
                                                        str_contains(strtolower($batch->duration),'month') => $start->copy()->addMonths($m[0] ?? 0),
                                                        str_contains(strtolower($batch->duration),'year') => $start->copy()->addYears($m[0] ?? 0),
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
                                                @endphp
                                                <tr class="border-t {{ $ended || $isFull ? 'bg-gray-200 text-gray-500' : 'cursor-pointer hover:bg-gray-50' }}"
                                                    onclick="{{ ($ended || $isFull) ? '' : 'selectRadio(' . $batch->id . ')' }}">
                                                    <td class="px-4 py-2">
                                                        <input type="radio" name="batch" value="{{ $batch->id }}" class="form-radio" id="batch-radio-{{ $batch->id }}"
                                                            {{ ($ended || $isFull) ? 'disabled' : '' }}>
                                                    </td>
                                                    <td class="px-4 py-2">{{ $batch->batch_no }}</td>
                                                    <td class="px-4 py-2">
                                                        {{ $start->format('d M Y') }}
                                                        @if($started)
                                                            <div class="text-xs text-orange-600 mt-1 font-semibold">Batch has started</div>
                                                        @elseif($ended)
                                                            <div class="text-xs text-red-600 mt-1 font-semibold">Batch ended</div>
                                                        @elseif($isFull)
                                                            <div class="text-xs text-red-600 mt-1 font-semibold">Batch full</div>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($batch->start_timing)->format('h:i A') }} - {{ \Carbon\Carbon::parse($batch->end_timing)->format('h:i A') }}</td>
                                                    <td class="px-4 py-2">{{ $batch->duration }}</td>
                                                    <td class="px-4 py-2">{{ $batch->strength }}</td>
                                                    @php
                                                        $days = is_array(json_decode($batch->days)) ? implode(', ', json_decode($batch->days)) : $batch->days 
                                                    @endphp
                                                    <td class="px-4 py-2">{{ $days }}</td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="7" class="px-4 py-2 text-gray-500">No batches available.</td></tr>
                                            @endforelse

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
                            <!-- Apply Promocode Section -->
                            @php
                                $coupnCode = App\Models\Coupon::where('is_active', 1)->first();
                                $taxation = App\Models\Taxation::where('user_type', 'trainer')->where('is_active', 1)->first();
                            @endphp
                            <div>
                                <h3 class="text-sm font-medium mb-2">Apply Promocode:</h3>
                                <div class="flex space-x-2">
                                    <input type="text" placeholder="Enter promocode for discount"
                                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                    <button class="bg-blue-600 text-white px-4 py-2 rounded text-sm">Apply</button>
                                </div>
                            </div>


                            @php
                                $actualPrice = floatval($material->training_price);
                                $offerPrice = floatval($material->training_offer_price ?? $actualPrice); // fallback
                                $savedPrice = $actualPrice - $offerPrice;

                                $taxRate = floatval($taxation->rate);
                                $tax = round($offerPrice * ($taxRate / 100), 2);
                                $total = round($offerPrice + $tax, 2);
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
                                    <span>Tax ({{ $taxation->rate }}%)</span>
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