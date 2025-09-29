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

                    <form action="{{ route('jobseeker.team-purchase-course-for-team') }}" method="POST" id="teamPurchaseForm">
                        @csrf

                        <input type="text" name="material_id" value="{{ $material->id }}">
                        <input type="text" name="training_type" value="{{ $material->training_type }}">
                        <input type="text" name="member_count" id="memberCountInput" value="">
                        <input type="text" name="original_price" id="original_price" value="{{ $offerPrice }}">
                        <input type="text" name="tax_rate" id="tax_rate" value="{{ $taxRate }}">
                        <input type="text" name="coupon_type" id="coupon_type" value="">
                        <input type="text" name="coupon_code" id="coupon_code_text" value="">
                        <input type="text" name="coupon_amount" id="coupon_amount" value="">
                        <input type="text" name="amount_paid" id="totalInput" value="">

                        <div class="max-w-6xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Left Column -->
                            <div class="lg:col-span-2 space-y-6">
                                @if(in_array($material->training_type, ['online','classroom']))
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ langLabel('training_mode') }}</label>
                                        <input type="hidden" name="session_type" value="{{ $material->training_type }}">
                                        <select class="w-64 border border-gray-300 rounded px-3 py-2 text-sm" disabled>
                                            <option value="online" @if($material->training_type==='online') selected @endif>Online</option>
                                            <option value="classroom" @if($material->training_type==='classroom') selected @endif>Classroom</option>
                                        </select>
                                    </div>

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
                                                $availableSeats = max(0, $strength - $enrolled);
                                                $isFull = $availableSeats <= 0;
                                                $days = is_array(json_decode($batch->days)) ? implode(', ', json_decode($batch->days)) : $batch->days;
                                            @endphp

                                            <div class="border rounded-lg p-4 flex justify-between items-center cursor-pointer hover:shadow-lg transition relative {{ $ended || $isFull ? 'bg-gray-100 cursor-not-allowed opacity-60' : 'bg-white' }}"
                                                data-batch-id="{{ $batch->id }}" data-available-seats="{{ $availableSeats }}">
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
                                                <button type="button" id="decreaseMember" class="text-gray-600 text-lg">◀</button>
                                                <span id="memberCount" class="text-base font-semibold text-gray-800">2</span>
                                                <button type="button" id="increaseMember" class="text-gray-600 text-lg">▶</button>
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
                                        <input type="text" id="coupon_code_input" placeholder="Enter promocode" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
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

                    <script>
                        // Safe injection of server-side values
                        const offerPrice = parseFloat(@json($offerPrice)) || 0;
                        const actualPrice = parseFloat(@json($actualPrice)) || offerPrice;
                        const taxRatePercent = parseFloat(@json($taxRate)) || 0; // e.g. 5 => 5%
                        const jobseekerEmail = @json(auth('jobseeker')->user()->email ?? '');

                        // State
                        let memberCount = 2;
                        let couponDiscount = 0;
                        let couponType = '';
                        const MIN_MEMBERS = 2;

                        // Utilities
                        function formatCurrency(v){ return `SAR ${v.toFixed(2)}`; }
                        function getMaxFromEmailsContainer(){
                            const emails = document.getElementById('memberEmails');
                            const max = parseInt(emails.dataset.maxMembers);
                            return Number.isFinite(max) ? max : 0;
                        }

                        // Billing
                        function updateBilling(){
                            const courseTotalBeforeDiscount = offerPrice * memberCount;
                            const discount = Math.min(couponDiscount, courseTotalBeforeDiscount); // don't exceed total
                            const courseTotal = Math.max(0, courseTotalBeforeDiscount - discount);
                            const tax = +(courseTotal * (taxRatePercent/100));
                            const total = +(courseTotal + tax);

                            document.getElementById('billingCourseTotal').textContent = formatCurrency(courseTotal);
                            const savedAmount = (actualPrice - offerPrice) * memberCount + discount;
                            document.getElementById('billingSavedAmount').textContent = formatCurrency(Math.max(0, savedAmount));
                            document.getElementById('billingTax').textContent = formatCurrency(tax);
                            document.getElementById('billingTotal').textContent = formatCurrency(total);
                            document.getElementById('totalInput').value = total.toFixed(2);

                            // sync hidden inputs
                            document.getElementById('memberCountInput').value = memberCount;
                            document.getElementById('coupon_amount').value = discount.toFixed(2);
                            document.getElementById('coupon_type').value = couponType || '';
                        }

                        // Member inputs management
                        function reconcileMemberEmailInputs(){
                            const emails = document.getElementById('memberEmails');
                            const includeSelf = !!document.getElementById('selfEmailInput');
                            const needed = memberCount - (includeSelf ? 1 : 0);
                            // existing non-self inputs
                            const existing = Array.from(emails.querySelectorAll('input')).filter(i => i.id !== 'selfEmailInput');

                            // add inputs if needed
                            if(needed > existing.length){
                                for(let i = existing.length + 1; i <= needed; i++){
                                    const input = document.createElement('input');
                                    input.type = 'email';
                                    input.name = 'member_emails[]';
                                    input.placeholder = `Enter email for member ${i}`;
                                    input.className = 'border p-2 rounded w-full mb-2';
                                    emails.appendChild(input);
                                }
                            } else if(needed < existing.length){
                                for(let i = existing.length - 1; i >= needed; i--){
                                    existing[i].remove();
                                }
                            }

                            // update placeholders to be sequential (excluding self)
                            const after = Array.from(emails.querySelectorAll('input')).filter(i => i.id !== 'selfEmailInput');
                            after.forEach((el, idx) => el.placeholder = `Enter email for member ${idx+1}`);
                        }

                        // Member count change
                        function setMemberCount(newCount){
                            const emails = document.getElementById('memberEmails');
                            const max = getMaxFromEmailsContainer();
                            if(max && newCount > max) newCount = max;
                            if(newCount < MIN_MEMBERS) newCount = MIN_MEMBERS;
                            memberCount = newCount;
                            document.getElementById('memberCount').textContent = memberCount;
                            document.getElementById('memberCountInput').value = memberCount;
                            reconcileMemberEmailInputs();
                            updateBilling();
                        }

                        document.getElementById('increaseMember').addEventListener('click', ()=> {
                            setMemberCount(memberCount + 1);
                        });
                        document.getElementById('decreaseMember').addEventListener('click', ()=> {
                            setMemberCount(memberCount - 1);
                        });

                        // batch selection click handling
                        document.querySelectorAll('[data-batch-id]').forEach(el=>{
                            el.addEventListener('click', function(e){
                                const batchId = this.getAttribute('data-batch-id');
                                const available = parseInt(this.getAttribute('data-available-seats')) || 0;
                                const radio = document.getElementById('batch-radio-' + batchId);
                                if(radio && !radio.disabled){
                                    radio.checked = true;
                                }
                                // compute max members: if includeSelf is checked, one seat reserved for self
                                const includeSelfChecked = document.getElementById('includeSelf').checked;
                                let maxMembers = available;
                                if(includeSelfChecked) maxMembers = Math.max(0, available - 1);
                                // set dataset for emails container
                                const emails = document.getElementById('memberEmails');
                                emails.dataset.maxMembers = maxMembers;
                                // ensure memberCount within bounds
                                if(memberCount > maxMembers && maxMembers >= MIN_MEMBERS){
                                    setMemberCount(maxMembers);
                                } else if(maxMembers < MIN_MEMBERS){
                                    // show error
                                    const err = document.getElementById('memberCountError');
                                    err.textContent = "Not enough seats in this batch for minimum members.";
                                    err.classList.remove('hidden');
                                } else {
                                    const err = document.getElementById('memberCountError');
                                    err.textContent = "";
                                    err.classList.add('hidden');
                                }
                            });
                        });

                        // Apply coupon
                        document.getElementById('apply_coupon').addEventListener('click', function(){
                            const code = document.getElementById('coupon_code_input').value.trim();
                            const msgEl = document.getElementById('coupon_message');
                            msgEl.textContent = '';
                            if(!code){ msgEl.textContent = "Enter coupon code."; msgEl.classList.add('text-red-500'); return; }

                            fetch("{{ route('apply.coupon') }}", {
                                method:'POST',
                                headers:{"Content-Type":"application/json","X-CSRF-TOKEN":"{{ csrf_token() }}"},
                                body:JSON.stringify({code})
                            })
                            .then(res => res.json())
                            .then(data => {
                                if(!data.success){
                                    msgEl.textContent = data.message || 'Invalid coupon';
                                    msgEl.classList.remove('text-green-500');
                                    msgEl.classList.add('text-red-500');
                                    couponDiscount = 0; couponType = '';
                                    document.getElementById('coupon_code_hidden').value = '';
                                    updateBilling();
                                    return;
                                }

                                let discount = 0;
                                if(data.discount_type === 'fixed') discount = parseFloat(data.discount_value) || 0;
                                else if(data.discount_type === 'percentage'){
                                    discount = (offerPrice * memberCount * (parseFloat(data.discount_value) || 0))/100;
                                }

                                couponDiscount = Math.max(0, discount);
                                couponType = data.discount_type || '';
                                document.getElementById('coupon_type').value = couponType;
                                document.getElementById('coupon_code_hidden').value = code;
                                document.getElementById('coupon_amount').value = couponDiscount.toFixed(2);

                                updateBilling();

                                msgEl.textContent = `Coupon applied: ${couponType === 'fixed' ? 'SAR '+couponDiscount.toFixed(2)+' off' : (data.discount_value+'% off')}`;
                                msgEl.classList.remove('text-red-500');
                                msgEl.classList.add('text-green-500');
                            })
                            .catch(err => {
                                document.getElementById('coupon_message').textContent = 'Coupon apply failed. Try again.';
                                document.getElementById('coupon_message').classList.add('text-red-500');
                            });
                        });

                        // includeSelf toggle
                        document.getElementById('includeSelf').addEventListener('change', function(){
                            const emails = document.getElementById('memberEmails');
                            const checked = this.checked;
                            // determine available seats from selected batch
                            let availableSeats = 0;
                            const batchRadio = document.querySelector('input[name="batch_id"]:checked');
                            if(batchRadio){
                                const batchEl = document.querySelector(`[data-batch-id="${batchRadio.value}"]`);
                                if(batchEl) availableSeats = parseInt(batchEl.getAttribute('data-available-seats')) || 0;
                            }
                            if(checked){
                                // prepend read-only self input if not exist
                                if(!document.getElementById('selfEmailInput')){
                                    const input = document.createElement('input');
                                    input.type = 'email';
                                    input.name = 'member_emails[]';
                                    input.id = 'selfEmailInput';
                                    input.value = jobseekerEmail;
                                    input.readOnly = true;
                                    input.className = 'border p-2 rounded w-full mb-2 bg-gray-100';
                                    emails.prepend(input);
                                }
                            } else {
                                const selfInput = document.getElementById('selfEmailInput');
                                if(selfInput) selfInput.remove();
                            }

                            // recompute max
                            const maxMembers = checked ? Math.max(0, availableSeats - 1) : availableSeats;
                            emails.dataset.maxMembers = maxMembers;

                            // adjust memberCount if needed
                            if(memberCount > maxMembers && maxMembers >= MIN_MEMBERS){
                                setMemberCount(maxMembers);
                            } else if(maxMembers < MIN_MEMBERS){
                                // show not enough seats error
                                const err = document.getElementById('memberCountError');
                                err.textContent = "Batch doesn't have enough seats for minimum members.";
                                err.classList.remove('hidden');
                            } else {
                                const err = document.getElementById('memberCountError');
                                err.textContent = "";
                                err.classList.add('hidden');
                            }

                            reconcileMemberEmailInputs();
                            updateBilling();
                        });

                        // initial setup
                        document.addEventListener('DOMContentLoaded', function(){
                            // set initial max-members if any batch is preselected
                            const batchRadio = document.querySelector('input[name="batch_id"]:checked');
                            if(batchRadio){
                                const batchEl = document.querySelector(`[data-batch-id="${batchRadio.value}"]`);
                                if(batchEl){
                                    document.getElementById('memberEmails').dataset.maxMembers = batchEl.getAttribute('data-available-seats');
                                }
                            }
                            reconcileMemberEmailInputs();
                            updateBilling();
                        });
                    </script>

                </div>
            </div>
        </main>




    
        </div>

@include('site.componants.footer')