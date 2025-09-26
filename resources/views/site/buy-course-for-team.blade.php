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


        <main class="w-11/12 mx-auto py-8">
            @php
                use App\Models\Taxation;
                use Carbon\Carbon;

                $taxation = Taxation::where('user_type', 'trainer')->where('is_active', 1)->first();
                $actualPrice = floatval($material->training_price);
                $offerPrice = floatval($material->training_offer_price ?? $actualPrice);
                $taxRate = floatval($taxation->rate ?? 0);
            @endphp

            <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">
                <div class="flex-1">
                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" id="errorAlert">
                            <strong>Oops!</strong> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <script>
                        setTimeout(() => {
                            ['successAlert','errorAlert'].forEach(id => {
                                const el = document.getElementById(id);
                                if(el) el.remove();
                            });
                        }, 3000);
                    </script>

                    <form action="{{ route('jobseeker.team-purchase-course-for-team') }}" method="POST">
                        @csrf
                        <input type="hidden" name="material_id" value="{{ $material->id }}">
                        <input type="hidden" name="training_type" value="{{ $material->training_type }}">
                        <input type="hidden" name="member_count" id="memberCountInput" value="2">
                        <input type="hidden" name="original_price" id="original_price" value="{{ $offerPrice }}">
                        <input type="hidden" id="tax_rate" value="{{ $taxRate }}">
                        <input type="hidden" name="coupon_type" id="coupon_type" value="">
                        <input type="hidden" name="coupon_code" id="coupon_code_hidden" value="">
                        <input type="hidden" name="coupon_amount" id="coupon_amount" value="">
                        <input type="hidden" name="amount_paid" id="totalInput" value="">

                        <div class="max-w-6xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

                            <!-- Left Column: Batch + Members -->
                            <div class="lg:col-span-2 space-y-6">

                                @if($material->training_type === 'online' || $material->training_type === 'classroom')
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ langLabel('training_mode') }}</label>
                                        <input type="hidden" name="session_type" value="{{ $material->training_type }}">
                                        <select class="w-64 border border-gray-300 rounded px-3 py-2 text-sm" disabled>
                                            <option value="online" @if($material->training_type==='online') selected @endif>Online</option>
                                            <option value="classroom" @if($material->training_type==='classroom') selected @endif>Classroom</option>
                                        </select>
                                    </div>

                                    <!-- Select Batch -->
                                    <h3 class="text-sm font-medium mb-2">{{ langLabel('select_batch') }}</h3>
                                    <div class="grid grid-cols-1 gap-4">
                                        @forelse($material->batches as $batch)
                                            @php
                                                $start = Carbon::parse($batch->start_date);
                                                $end = isset($batch->end_date) ? Carbon::parse($batch->end_date) : $start;
                                                $ended = $end->isPast();
                                                $started = $start->isPast() && !$ended;
                                                $strength = $batch->strength;
                                                $enrolled = \App\Models\JobseekerTrainingMaterialPurchase::where('batch_id', $batch->id)
                                                                ->where('material_id', $material->id)
                                                                ->count();
                                                $availableSeats = $strength - $enrolled;
                                                $isFull = $availableSeats <= 0;
                                                $days = is_array(json_decode($batch->days)) ? implode(', ', json_decode($batch->days)) : $batch->days;
                                            @endphp

                                            <div class="border rounded-lg p-4 flex justify-between items-center cursor-pointer hover:shadow-lg transition relative {{ $ended || $isFull ? 'bg-gray-100 cursor-not-allowed opacity-60' : 'bg-white' }}"
                                                onclick="{{ ($ended || $isFull) ? '' : 'selectBatch('.$batch->id.','.$availableSeats.')' }}">
                                                <div class="flex items-center space-x-4">
                                                    <input type="radio" name="batch_id" value="{{ $batch->id }}" id="batch-radio-{{ $batch->id }}" class="form-radio h-5 w-5 text-blue-600" {{ ($ended || $isFull)?'disabled':'' }}>
                                                    <div>
                                                        <h4 class="font-semibold text-gray-800">{{ $batch->batch_no }}</h4>
                                                        <p class="text-gray-500 text-sm">
                                                            Start: {{ $start->format('d M Y') }} <br>
                                                            End: {{ $end->format('d M Y') }} <br>
                                                            Timing: {{ Carbon::parse($batch->start_timing)->format('h:i A') }} - {{ Carbon::parse($batch->end_timing)->format('h:i A') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="text-right space-y-1">
                                                    @if($ended)
                                                        <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded">Batch Ended</span>
                                                    @elseif($started)
                                                        <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded">Batch Started</span>
                                                    @elseif($isFull)
                                                        <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded">Full</span>
                                                    @else
                                                        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded">{{ $availableSeats }} Seats Left</span>
                                                    @endif
                                                    <p class="text-gray-600 text-sm">Duration: {{ $batch->duration }}</p>
                                                    <p class="text-gray-600 text-sm">Days: {{ $days }}</p>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-gray-500 text-center">No batches available</p>
                                        @endforelse
                                    </div>
                                @endif

                                <!-- Member Selection -->
                                <div class="flex border rounded p-4 space-x-4 mt-4">
                                    <img src="{{ $material->thumbnail ?? asset('asset/images/gallery/pic-4.png') }}" alt="Course" class="w-28 h-20 object-cover rounded">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-sm">{{ $material->training_title . ' (' . $material->training_type .')' }}</h4>
                                        <div class="flex items-center space-x-2 mt-2">
                                            <span class="text-sm font-medium text-gray-700">{{ langLabel('number_of_members') }}</span>
                                            <div class="inline-flex items-center border border-gray-300 rounded px-3 py-2 space-x-6">
                                                <button type="button" onclick="changeCount(-1)" class="text-gray-600 text-lg">◀</button>
                                                <span id="memberCount" class="text-base font-semibold text-gray-800">2</span>
                                                <button type="button" onclick="changeCount(1)" class="text-gray-600 text-lg">▶</button>
                                            </div>
                                        </div>
                                        <small id="memberCountError" class="text-red-600 text-sm mt-1 hidden"></small>
                                    </div>
                                </div>

                                <!-- Self Include Checkbox -->
                                <div class="mt-3">
                                    <label class="inline-flex items-center space-x-2">
                                        <input type="checkbox" id="includeSelf" class="form-checkbox h-5 w-5 text-blue-600">
                                        <span class="text-sm font-medium text-gray-700">Include myself as a team member</span>
                                    </label>
                                </div>

                                <!-- Member Emails -->
                                <div id="memberEmails" data-max-members="0" class="mt-3">
                                    <input type="email" name="member_emails[]" placeholder="Enter email for member 1" class="border p-2 rounded w-full mb-2">
                                    <input type="email" name="member_emails[]" placeholder="Enter email for member 2" class="border p-2 rounded w-full mb-2">
                                </div>

                            </div>

                            <!-- Right Column: Billing + Coupon -->
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-sm font-medium mb-2">{{ langLabel('apply_promocode') }}:</h3>
                                    <div class="flex space-x-2">
                                        <input type="text" id="coupon_code" placeholder="Enter promocode" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                        <button type="button" id="apply_coupon" class="bg-blue-600 text-white px-4 py-2 rounded text-sm">{{ langLabel('apply') }}</button>
                                    </div>
                                    <small id="coupon_message" class="text-red-500 mt-1 block"></small>
                                </div>

                                <div class="border rounded p-4 space-y-2">
                                    <h3 class="text-sm font-medium border-b pb-2">{{ langLabel('billing_information') }}</h3>
                                    <div class="flex justify-between text-sm">
                                        <span>{{ langLabel('course_total') }}</span>
                                        <span id="billingCourseTotal"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span>{{ langLabel('saved_amount') }}</span>
                                        <span id="billingSavedAmount"></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span>{{ langLabel('tax') }} ({{ $taxRate }}%)</span>
                                        <span id="billingTax"></span>
                                    </div>
                                    <div class="flex justify-between text-base font-semibold pt-2 border-t">
                                        <span>{{ langLabel('total') }}</span>
                                        <span id="billingTotal"></span>
                                    </div>
                                    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded mt-4 text-sm font-medium">
                                        {{ langLabel('proceed_checkout') }}
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>

                    <!-- JS Section -->
                    <script>
                        let memberCount = 2;
                        const offerPrice = {{ $offerPrice }};
                        const actualPrice = {{ $actualPrice }};
                        const taxRate = {{ $taxRate/100 }};

                        function selectBatch(batchId, availableSeats){
                            const radio = document.getElementById('batch-radio-'+batchId);
                            if(radio) radio.checked = true;

                            const emails = document.getElementById('memberEmails');
                            const includeSelf = document.getElementById('includeSelf').checked;
                            const maxMembers = includeSelf ? availableSeats - 1 : availableSeats;
                            emails.dataset.maxMembers = maxMembers;

                            const countEl = document.getElementById('memberCount');
                            let currentCount = parseInt(countEl.textContent);
                            if(currentCount > maxMembers) changeCount(maxMembers - currentCount);
                        }

                        function changeCount(delta){
                            const countEl = document.getElementById('memberCount');
                            const inputEl = document.getElementById('memberCountInput');
                            const emails = document.getElementById('memberEmails');
                            const errorEl = document.getElementById('memberCountError');
                            const max = parseInt(emails.dataset.maxMembers);

                            if(!max || max <= 0){
                                errorEl.textContent = "Please select a batch first.";
                                errorEl.classList.remove('hidden');
                                return;
                            } else {
                                errorEl.textContent = "";
                                errorEl.classList.add('hidden');
                            }

                            let newCount = memberCount + delta;
                            if(newCount < 2) newCount = 2;
                            if(newCount > max) newCount = max;
                            memberCount = newCount;
                            countEl.textContent = newCount;
                            inputEl.value = newCount;

                            const selfInput = document.getElementById('selfEmailInput');
                            const selfIncluded = selfInput ? 1 : 0;
                            const needed = newCount - selfIncluded;
                            let existing = [...emails.querySelectorAll('input')].filter(i => i.id !== 'selfEmailInput');

                            if(needed > existing.length){
                                for(let i = existing.length + 1; i <= needed; i++){
                                    const input = document.createElement('input');
                                    input.type='email';
                                    input.name='member_emails[]';
                                    input.placeholder=`Enter email for member ${i}`;
                                    input.className='border p-2 rounded w-full mb-2';
                                    emails.appendChild(input);
                                }
                            } else if(needed < existing.length){
                                for(let i = existing.length; i > needed; i--){
                                    emails.removeChild(existing[i-1]);
                                }
                            }

                            updateBilling();
                        }

                        function updateBilling(discount = 0){
                            const courseTotal = offerPrice * memberCount - discount;
                            const savedAmount = (actualPrice - offerPrice) * memberCount + discount;
                            const tax = courseTotal * taxRate;
                            const total = courseTotal + tax;

                            document.getElementById('billingCourseTotal').textContent = `SAR ${courseTotal.toFixed(2)}`;
                            document.getElementById('billingSavedAmount').textContent = `SAR ${savedAmount.toFixed(2)}`;
                            document.getElementById('billingTax').textContent = `SAR ${tax.toFixed(2)}`;
                            document.getElementById('billingTotal').textContent = `SAR ${total.toFixed(2)}`;
                            document.getElementById('totalInput').value = total.toFixed(2);
                        }

                        document.addEventListener("DOMContentLoaded", () => { updateBilling(); });

                        document.getElementById('apply_coupon').addEventListener('click', function(){
                            const code = document.getElementById('coupon_code').value.trim();
                            const msgEl = document.getElementById('coupon_message');
                            if(!code){ msgEl.textContent="Enter coupon code."; return; }

                            fetch("{{ route('apply.coupon') }}", {
                                method:'POST',
                                headers:{"Content-Type":"application/json","X-CSRF-TOKEN":"{{ csrf_token() }}"},
                                body:JSON.stringify({code})
                            })
                            .then(res => res.json())
                            .then(data => {
                                if(!data.success){
                                    msgEl.textContent = data.message;
                                    msgEl.classList.add('text-red-500');
                                    msgEl.classList.remove('text-green-500');
                                    return;
                                }

                                let discount = 0;
                                if(data.discount_type==='fixed') discount = data.discount_value;
                                else if(data.discount_type==='percentage') discount = (offerPrice*memberCount*data.discount_value)/100;

                                document.getElementById('coupon_type').value = data.discount_type;
                                document.getElementById('coupon_code_hidden').value = code;
                                document.getElementById('coupon_amount').value = discount.toFixed(2);

                                updateBilling(discount);

                                msgEl.textContent = `Coupon applied: ${data.discount_type==='fixed' ? 'SAR '+discount.toFixed(2)+' off' : data.discount_value+'% off'}`;
                                msgEl.classList.remove('text-red-500');
                                msgEl.classList.add('text-green-500');
                            });
                        });

                        // Self include checkbox
                        document.getElementById('includeSelf').addEventListener('change', function() {
                            const emails = document.getElementById('memberEmails');
                            const batchRadio = document.querySelector('input[name="batch_id"]:checked');
                            let availableSeats = 0;

                            if(batchRadio){
                                const batchId = parseInt(batchRadio.value);
                                const batchData = @json($material->batches);
                                const enrolledMap = @json($material->batches->mapWithKeys(fn($b) => [$b->id => \App\Models\JobseekerTrainingMaterialPurchase::where('batch_id',$b->id)->where('material_id',$material->id)->count()]));
                                const batch = batchData.find(b => b.id === batchId);
                                if(batch){
                                    const strength = batch.strength;
                                    availableSeats = strength - (enrolledMap[batchId] ?? 0);
                                }
                            }

                            if(this.checked){
                                const input = document.createElement('input');
                                input.type = 'email';
                                input.name = 'member_emails[]';
                                input.id = 'selfEmailInput';
                                input.value = "{{ auth('jobseeker')->user()->email ?? '' }}";
                                input.readOnly = true;
                                input.className = 'border p-2 rounded w-full mb-2 bg-gray-100';
                                emails.prepend(input);
                            } else {
                                const selfInput = document.getElementById('selfEmailInput');
                                if (selfInput) selfInput.remove();
                            }

                            const maxMembers = this.checked ? availableSeats - 1 : availableSeats;
                            emails.dataset.maxMembers = maxMembers;

                            if(memberCount > maxMembers){
                                changeCount(maxMembers - memberCount);
                            }

                            updateBilling();
                        });
                    </script>
                </div>
            </div>
        </main>




    
        </div>

@include('site.componants.footer')