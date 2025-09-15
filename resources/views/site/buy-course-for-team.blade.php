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
            <div class="relative bg-center bg-cover h-[400px] flex items-center" style="background-image: url('{{ asset('asset/images/banner/Training.png') }}');">
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


            @php
                $memberCount = 4; // Default count shown on load

                $actualPrice = $material->training_price;  // per member actual price
                $offerPrice = $material->training_offer_price; // per member offer price
                $savedPrice = ($material->training_price - $material->training_offer_price) * $memberCount;

                $tax = round($offerPrice * 0.10, 2); // 10% tax
                $total = $offerPrice + $tax;
            @endphp


        <script>
            let memberCount = 1; // start at 1

            const offerPrice = {{ $offerPrice }}; // per member
            const actualPrice = {{ $actualPrice }}; // per member
            const taxRate = 0.10; // 10%

            function updateBilling() {
                const courseTotal = offerPrice * memberCount;
                const savedAmount = (actualPrice - offerPrice) * memberCount;
                const tax = courseTotal * taxRate;
                const total = courseTotal + tax;

                document.getElementById("billingCourseTotal").textContent = `SAR ${courseTotal.toFixed(2)}`;
                document.getElementById("billingSavedAmount").textContent = `SAR ${savedAmount.toFixed(2)}`;
                document.getElementById("billingTax").textContent = `SAR ${tax.toFixed(2)}`;
                document.getElementById("billingTotal").textContent = `SAR ${total.toFixed(2)}`;
            }

            function increaseCount() {
                memberCount++;
                document.getElementById("memberCount").textContent = memberCount;
                document.getElementById("memberCountInput").value = memberCount;
                updateBilling();
            }

            function decreaseCount() {
                if (memberCount > 1) { // minimum 1
                    memberCount--;
                    document.getElementById("memberCount").textContent = memberCount;
                    document.getElementById("memberCountInput").value = memberCount;
                    updateBilling();
                }
            }

            // run once on page load so billing matches default 1 member
            document.addEventListener("DOMContentLoaded", updateBilling);
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
                
                <form accept="multipart/form-data" action="{{ route('jobseeker.team-purchase-course') }}" method="POST">
                    @csrf
                    <input type="hidden" name="material_id" value="{{ $material->id }}">
                    <input type="hidden" name="training_type" value="{{ $material->training_type }}">

                    <div class="max-w-6xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2 space-y-6">

                            @if($material->training_type === "online" || $material->training_type === "classroom")
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ langLabel('training_mode') }}</label>
                                    <input type="hidden" name="session_type" value="{{ $material->training_type }}">
                                    <select class="w-64 border border-gray-300 rounded px-3 py-2 text-sm" disabled>
                                        <option value="" disabled>{{ langLabel('select_training_mode') }}</option>
                                        <option value="online" @if($material->training_type === 'online') selected @endif>Online</option>
                                        <option value="classroom" @if($material->training_type === 'classroom') selected @endif>Classroom</option>
                                    </select>
                                </div>
                                @error('session_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                <div>
                                    <h3 class="text-sm font-medium mb-2">{{ langLabel('select_batch') }}</h3>
                                    <div class="overflow-x-auto">
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
                                                        $availableSeats = $strength - $enrolled;
                                                        $isFull = $availableSeats <= 0;
                                                    @endphp
                                                    <tr class="border-t {{ $ended || $isFull ? 'bg-gray-200 text-gray-500' : 'cursor-pointer hover:bg-gray-50' }}"
                                                        onclick="{{ ($ended || $isFull) ? '' : 'selectRadio(' . $batch->id . ',' . $availableSeats . ')' }}">
                                                        <td class="px-4 py-2">
                                                            <input type="radio" name="batch" value="{{ $batch->id }}" class="form-radio" id="batch-radio-{{ $batch->id }}"
                                                                {{ ($ended || $isFull) ? 'disabled' : '' }}>
                                                        </td>
                                                        <td class="px-4 py-2">{{ $batch->batch_no }}</td>
                                                        <td class="px-4 py-2">
                                                            {{ $start->format('d M Y') }}
                                                            @if($started)
                                                                <div class="text-xs text-orange-600 mt-1 font-semibold">{{ langLabel('batch_hs_started') }}</div>
                                                            @elseif($ended)
                                                                <div class="text-xs text-red-600 mt-1 font-semibold">{{ langLabel('batch_ended') }}</div>
                                                            @elseif($isFull)
                                                                <div class="text-xs text-red-600 mt-1 font-semibold">{{ langLabel('batch_full') }}</div>
                                                            @endif
                                                        </td>
                                                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($batch->start_timing)->format('h:i A') }} - {{ \Carbon\Carbon::parse($batch->end_timing)->format('h:i A') }}</td>
                                                        <td class="px-4 py-2">{{ $batch->duration }}</td>
                                                        <td class="px-4 py-2">{{ $batch->strength.' ('.$availableSeats.')' }}</td>
                                                        @php
                                                            $days = is_array(json_decode($batch->days)) ? implode(', ', json_decode($batch->days)) : $batch->days;
                                                        @endphp
                                                        <td class="px-4 py-2">{{ $days }}</td>
                                                    </tr>
                                                @empty
                                                    <tr><td colspan="7" class="px-4 py-2 text-gray-500">{{ langLabel('no_batches_available') }}</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    @error('batch')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                    <script>
                                        function selectRadio(id, availableSeats) {
                                            const radio = document.getElementById('batch-radio-' + id);
                                            if (radio) {
                                                radio.checked = true;
                                                const emailsContainer = document.getElementById('memberEmails');
                                                // Max additional members = availableSeats - 1 (current user counts as 1)
                                                const maxAdditionalMembers = availableSeats > 2 ? availableSeats - 2 : 1;
                                                emailsContainer.dataset.maxMembers = maxAdditionalMembers;

                                                const countEl = document.getElementById('memberCount');
                                                const inputEl = document.getElementById('memberCountInput');
                                                let current = parseInt(countEl.textContent);

                                                if(current > maxAdditionalMembers + 1) { 
                                                    changeCount(maxAdditionalMembers + 1 - current);
                                                }
                                            }
                                        }
                                    </script>
                                </div>
                            @endif

                            <!-- Member selection -->
                            <div class="flex border rounded p-4 space-x-4">
                                <img src="{{ $material->thumbnail ?? asset('asset/images/gallery/pic-4.png') }}" alt="Course" class="w-28 h-20 object-cover rounded">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-sm">{{ $material->training_title . ' (' . $material->training_type .')' }}</h4>
                                    <div class="flex items-center space-x-2 mt-2">
                                        <span class="text-sm font-medium text-gray-700">{{ langLabel('number_of_menbers') }}</span>
                                        <div class="inline-flex items-center border border-gray-300 rounded px-3 py-2 space-x-6">
                                            <button type="button" onclick="changeCount(-1)" class="text-gray-600 text-lg">◀</button>
                                            <span id="memberCount" class="text-base font-semibold text-gray-800">2</span>
                                            <button type="button" onclick="changeCount(1)" class="text-gray-600 text-lg">▶</button>
                                        </div>
                                    </div>
                                    <small id="memberCountError" class="text-red-600 text-sm mt-1 block hidden"></small>
                                    <input type="hidden" name="member_count" id="memberCountInput" value="2">
                                </div>
                            </div>

                            <!-- Emails container -->
                            <div id="memberEmails" class="mt-3" data-max-members="0">
                                <input type="email" name="member_emails[]" placeholder="Enter email for member 1" class="border p-2 rounded w-full mb-2">
                                <input type="email" name="member_emails[]" placeholder="Enter email for member 2" class="border p-2 rounded w-full mb-2">
                            </div>

                            <script>
                                function changeCount(delta) {
                                    const countEl = document.getElementById('memberCount');
                                    const inputEl = document.getElementById('memberCountInput');
                                    const emailsContainer = document.getElementById('memberEmails');
                                    const errorEl = document.getElementById('memberCountError');

                                    const maxAdditionalMembers = parseInt(emailsContainer.dataset.maxMembers);

                                    if (isNaN(maxAdditionalMembers) || maxAdditionalMembers === 0) {
                                        errorEl.textContent = "Please select a batch first.";
                                        errorEl.classList.remove('hidden');
                                        return;
                                    } else {
                                        errorEl.textContent = "";
                                        errorEl.classList.add('hidden');
                                    }

                                    let current = parseInt(countEl.textContent);
                                    let newCount = current + delta;

                                    if (newCount < 2) newCount = 2; 
                                    if (newCount > maxAdditionalMembers + 1) newCount = maxAdditionalMembers + 1;

                                    countEl.textContent = newCount;
                                    inputEl.value = newCount;

                                    const existingInputs = emailsContainer.querySelectorAll('input');
                                    const memberInputsNeeded = newCount - 1;

                                    if (memberInputsNeeded > existingInputs.length) {
                                        for (let i = existingInputs.length + 1; i <= memberInputsNeeded; i++) {
                                            const input = document.createElement('input');
                                            input.type = 'email';
                                            input.name = 'member_emails[]';
                                            input.placeholder = `Enter email for member ${i + 1}`;
                                            input.className = 'border p-2 rounded w-full mb-2';
                                            emailsContainer.appendChild(input);
                                        }
                                    } else if (memberInputsNeeded < existingInputs.length) {
                                        for (let i = existingInputs.length; i > memberInputsNeeded; i--) {
                                            emailsContainer.removeChild(existingInputs[i - 1]);
                                        }
                                    }
                                }
                            </script>

                        </div>

                        <!-- Billing & Checkout -->
                        <div x-data="{ paymentMethod: 'card' }" class="space-y-4">
                            @php
                                $taxation = App\Models\Taxation::where('user_type', 'trainer')->where('is_active', 1)->first();
                                $actualPrice = floatval($material->training_price);
                                $offerPrice = floatval($material->training_offer_price ?? $actualPrice);
                                $savedPrice = $actualPrice - $offerPrice;
                                $taxRate = floatval($taxation->rate);
                                $tax = round($offerPrice * ($taxRate / 100), 2);
                                $total = round($offerPrice + $tax, 2);
                            @endphp

                            <div class="border rounded p-4 space-y-2">
                                <h3 class="text-sm font-medium border-b pb-2">{{ langLabel('billing_information') }}</h3>
                                <div class="flex justify-between text-sm">
                                    <span>{{ langLabel('course_total') }}</span>
                                    <span>SAR {{ number_format($offerPrice, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span>{{ langLabel('saved_amount') }}</span>
                                    <span>SAR {{ number_format($savedPrice, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span>{{ langLabel('tax') }} ({{ $taxation->rate }}%)</span>
                                    <span>SAR {{ number_format($tax, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-base font-semibold pt-2 border-t">
                                    <span>{{ langLabel('total') }}</span>
                                    <span>SAR {{ number_format($total, 2) }}</span>
                                </div>
                                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded mt-4 text-sm font-medium">
                                    {{ langLabel('proceed_checkout') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>


                    <style>
                        .active-tab {
                            border-bottom-color: #2563eb;
                            color: #2563eb;
                        }
                    </style>

            </div>
        </div>
    </main>
    
        </div>

@include('site.componants.footer')