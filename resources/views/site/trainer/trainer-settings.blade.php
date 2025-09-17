<?php
//  'trainerSkills',
// 'educationDetails',
// 'workExperiences'
// 'additonalDetails'
// echo "<pre>";
// print_r($additonalDetails );exit;
// echo "</pre>";
?>
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
 @if($trainerNeedsSubscription)
        @include('site.trainer.subscription.index')
    @endif
	
    <div class="page-wraper">
        <div class="flex h-screen" x-data="{ sidebarOpen: true }" x-init="$watch('sidebarOpen', () => feather.replace())">
          
            @include('site.trainer.componants.sidebar')

            <div class="flex-1 flex flex-col">
                @include('site.trainer.componants.navbar')

           <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="{ activeSection: 'profile', activeSubTab: 'company' }">
                    <h2 class="text-2xl font-semibold mb-6">{{ langLabel('settings') }}</h2>

                    <div class="flex">
                        <!-- Sidebar -->
                        <aside class="w-60 bg-white p-4 border-r mt-4 shadow rounded-lg">
                            <ul class="space-y-2">
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'profile'"
                                    :class="activeSection === 'profile' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >{{ langLabel('profile') }}</a>
                                </li>
                                
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'notifications'"
                                    :class="activeSection === 'notifications' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >{{ langLabel('notifications') }}</a>
                                </li>
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'payment'"
                                    :class="activeSection === 'payment' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >{{ langLabel('payment_history') }}</a>
                                </li>
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'subscription'; activeSubTab = 'subscription'"
                                    :class="activeSection === 'subscription' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >{{ langLabel('subscription') }}</a>
                                </li>
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'privacy'"
                                    :class="activeSection === 'privacy' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >{{ langLabel('privacy_policy') }}</a>
                                </li>
                                <li>
                                <!-- <a
                                    href="#"
                                    @click.prevent="activeSection = 'logout'"
                                    :class="activeSection === 'logout' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >Log out</a> -->
                                </li>
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'delete'"
                                    :class="activeSection === 'delete' ? 'bg-red-100 text-red-700 rounded px-2 py-2 block' : 'block px-2 py-2 text-red-600 hover:bg-red-100 rounded'"
                                >{{ langLabel('delete_account') }}</a>
                                </li>
                            </ul>
                        </aside>
                        

                        <!-- Main Content -->
                        <section class="flex-1 p-6">
                            
                            <div class="bg-white rounded-lg shadow p-6">
                                
                                <div x-show="activeSection === 'subscription'" x-transition class="bg-white p-6">
                                    <h3 class="text-xl font-semibold mb-4 border-b pb-2">{{ langLabel('subscription') }}</h3>

                                    @php
                                        $userId = auth()->id();
                                        $subscriptions = App\Models\SubscriptionPlan::where('user_type', 'trainer')->get();
                                        $purchasedSubscriptions = App\Models\PurchasedSubscription::select(
                                                'subscription_plans.title',
                                                'subscription_plans.duration_days',
                                                'purchased_subscriptions.id',
                                                'purchased_subscriptions.subscription_plan_id',
                                                'purchased_subscriptions.user_id',
                                                'payments_history.paid_at'
                                            )
                                            ->join('subscription_plans', 'purchased_subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
                                            ->join('payments_history', 'payments_history.transaction_id', '=', 'purchased_subscriptions.transaction_id')
                                            ->where('subscription_plans.user_type', 'trainer')
                                            ->where('purchased_subscriptions.user_id', $userId)
                                            ->orderBy('payments_history.paid_at', 'desc')
                                            ->get();

                                        $showPlansModal = false;

                                        if ($purchasedSubscriptions->count() > 0) {
                                            foreach ($purchasedSubscriptions as $sub) {
                                                $startDate = \Carbon\Carbon::parse($sub->paid_at);
                                                $endDate   = $startDate->copy()->addDays($sub->duration_days);
                                                $daysLeft  = \Carbon\Carbon::now()->diffInDays($endDate, false);

                                                // check if any active subscription is expiring soon
                                                if ($daysLeft > 0 && $daysLeft <= 30) {
                                                    $showPlansModal = true;
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp

                                    <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        @if($showPlansModal)
                                            document.getElementById('plansModal').classList.remove('hidden');
                                        @endif
                                    });
                                    </script>

                                    <!-- Subscription Card -->
                                    <div class="bg-gray-100 p-6 rounded-md flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                                        <div>
                                            <h4 class="text-lg font-semibold mb-1">{{ langLabel('subscription_plans') }}</h4>
                                            <p class="text-gray-600 text-sm">{{ langLabel('purchase_subscription') }}</p>
                                        </div>
                                        <button onclick="document.getElementById('plansModal').classList.remove('hidden')"
                                            class="mt-4 md:mt-0 bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                                            {{ langLabel('view_plans') }}
                                        </button>
                                    </div>

                                    <!-- Plans Modal -->
                                    <div id="plansModal" class="fixed inset-0 bg-gray-200 bg-opacity-80 flex items-center justify-center z-50 hidden">
                                        <div class="bg-white w-full max-w-2xl p-6 rounded-lg shadow-lg relative">
                                            <button onclick="document.getElementById('plansModal').classList.add('hidden')"
                                                class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-lg">✕</button>

                                            <h3 class="text-xl font-semibold mb-6">{{ langLabel('available_subscription_plans') }}</h3>

                                            <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-1 gap-4">
                                                @foreach($subscriptions as $plan)
                                                    <div class="border rounded-lg p-4 shadow-sm text-center">
                                                        <div class="flex flex-col items-center">
                                                            <div class="w-12 h-12 flex items-center justify-center bg-gray-100 rounded-full mb-2">
                                                                <i class="fas fa-crown text-blue-500 text-xl"></i>
                                                            </div>

                                                            <h4 class="font-semibold">{{ $plan->title }}</h4>
                                                            <p class="font-bold text-lg mt-1">AED {{ $plan->price }}</p>
                                                        </div>
                                                        <p class="text-sm text-gray-500 mt-2 mb-3">{{ $plan->description }}</p>
                                                        <ul class="list-disc list-outside pl-5 text-sm text-gray-700 mb-4">
                                                            @foreach(is_array($plan->features) ? $plan->features : explode(',', $plan->features) as $feature)
                                                                <li>{{ trim($feature) }}</li>
                                                            @endforeach
                                                        </ul>

                                                        <!-- Direct form submit instead of JS -->
                                                        <form action="{{ route('subscription.payment') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                                            <input type="hidden" name="user_id" value="{{ auth()->user('trainer')->id }}">
                                                            <input type="hidden" name="type" value="trainer">
                                                            <button type="submit"
                                                                class="bg-blue-500 hover:bg-blue-600 text-white w-full py-2 rounded-md text-sm font-medium">
                                                                Renew subscription
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Payment Modal -->
                                    <div id="paymentModal" class="fixed inset-0 bg-gray-200 bg-opacity-80 z-50 hidden flex items-center justify-center">
                                        <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg relative">
                                            <h3 class="text-xl font-semibold mb-4 text-center">{{ langLabel('payment') }}</h3>
                                            <p class="mb-6 text-gray-600 text-center">{{ langLabel('enter_card_details') }}</p>

                                            <form id="paymentForm">
                                                @csrf
                                                <input type="hidden" name="plan_id" id="selectedPlanId">

                                                <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ langLabel('card_number') }}</label>
                                                    <input type="text" name="card_number" value="4242424242424242"
                                                        class="w-full border border-gray-300 rounded-md px-4 py-2">
                                                </div>

                                                <div class="mb-4 flex space-x-2">
                                                    <div class="w-1/2">
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ langLabel('expiry') }}</label>
                                                        <input type="text" name="expiry" value="12/30"
                                                            class="w-full border border-gray-300 rounded-md px-4 py-2">
                                                    </div>
                                                    <div class="w-1/2">
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ langLabel('cvv') }}</label>
                                                        <input type="text" name="cvv" value="123"
                                                            class="w-full border border-gray-300 rounded-md px-4 py-2">
                                                    </div>
                                                </div>
                                                <button type="submit"
                                                    class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 transition">
                                                    {{ langLabel('pay_now') }}
                                                </button>
                                            </form>
                                            <div id="paymentMessage" class="mt-3 text-center text-sm"></div>
                                            <button onclick="closePaymentModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl font-bold">
                                                ×
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Subscription History Table -->
                                    <h4 class="text-lg font-semibold mb-3">{{ langLabel('subscription_history') }}</h4>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full border border-gray-200 text-sm">
                                            <thead class="bg-gray-100 text-left">
                                                <tr>
                                                    <th class="px-4 py-2 font-medium text-gray-700">{{ langLabel('sr_no') }}</th>
                                                    <th class="px-4 py-2 font-medium text-gray-700">{{ langLabel('subscription') }}</th>
                                                    <th class="px-4 py-2 font-medium text-gray-700">{{ langLabel('duration') }}</th>
                                                    <th class="px-4 py-2 font-medium text-gray-700">{{ langLabel('purchased_on') }}</th>
                                                    <th class="px-4 py-2 font-medium text-gray-700">{{ langLabel('expired_on') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($purchasedSubscriptions as $index => $subscription)
                                                    <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                                                        <td class="px-4 py-3">{{ $index + 1 }}.</td>
                                                        <td class="px-4 py-3">{{ $subscription->title }}</td>
                                                        <td class="px-4 py-3">{{ $subscription->duration_days }} {{ Str::plural('days', $subscription->duration_days) }}</td>
                                                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($subscription->start_date)->format('d/m/Y') }}</td>
                                                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($subscription->end_date)->format('d/m/Y') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                               <!-- Include SweetAlert2 -->
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                                <script>
                                    function openPaymentModal(planId) {
                                        document.getElementById('selectedPlanId').value = planId;
                                        document.getElementById('paymentModal').classList.remove('hidden');
                                    }

                                    function closePaymentModal() {
                                        document.getElementById('paymentModal').classList.add('hidden');
                                    }

                                    document.addEventListener('DOMContentLoaded', () => {
                                        document.querySelectorAll('.buy-subscription-btn').forEach(button => {
                                            button.addEventListener('click', function () {
                                                openPaymentModal(this.getAttribute('data-plan-id'));
                                            });
                                        });

                                        document.addEventListener('keydown', function (e) {
                                            if (e.key === 'Escape') closePaymentModal();
                                        });

                                        document.getElementById('paymentForm').addEventListener('submit', function (e) {
                                            e.preventDefault();
                                            let formData = new FormData(this);

                                            fetch("{{ route('trainer.subscription.payment') }}", {
                                                method: "POST",
                                                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                                                body: formData
                                            })
                                            .then(async response => {
                                                let data = await response.json();
                                                if (!response.ok) throw data;
                                                return data;
                                            })
                                            .then(data => {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Payment Successful',
                                                    text: data.message,
                                                    confirmButtonColor: '#3085d6',
                                                    timer: 2000,
                                                    timerProgressBar: true
                                                }).then(() => {
                                                    closePaymentModal();
                                                    location.reload();
                                                });
                                            })
                                            .catch(error => {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Payment Failed',
                                                    text: error.message || "Something went wrong!",
                                                    confirmButtonColor: '#d33'
                                                });
                                            });
                                        });
                                    });
                                </script>



                                <!-- Delete Account Section -->
                                <div 
                                    x-show="activeSection === 'delete'" 
                                    x-transition 
                                    class="bg-white p-6 rounded-lg border border-red-200 shadow-md mt-4"
                                >
                                    <h3 class="text-xl font-semibold mb-3 text-red-600">Delete Account</h3>

                                    <p class="text-gray-700 leading-relaxed mb-4">
                                        This action is <span class="font-semibold text-red-700">irreversible</span>. 
                                        Are you sure you want to permanently delete your account?
                                    </p>

                                    <form id="deleteAccountForm" action="{{ route('trainer.destroy') }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button 
                                            type="button" 
                                            id="deleteAccountBtn" 
                                            class="bg-red-600 text-white px-5 py-2.5 rounded hover:bg-red-700 transition-colors duration-200"
                                        >
                                            Delete Account
                                        </button>
                                    </form>
                                </div>

    
                                <div x-show="activeSection === 'notifications'" x-transition class="bg-white p-6 ">
                                    <h3 class="text-xl font-semibold mb-4 border-b pb-2">Notifications</h3>

                                    <!-- Scrollable notification list -->
                                    <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                                        <!-- Notification Items -->
                                        <div class="flex items-start gap-4 border-b pb-4">
                                        <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                        <div>
                                            <p><span class="font-semibold">James Walker</span> You have meeting on <span class="font-medium">12:30 pm</span></p>
                                            <p class="text-sm text-gray-500 mt-1">4 Minute ago</p>
                                        </div>
                                        </div>

                                        <div class="flex items-start gap-4 border-b pb-4">
                                        <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                        <div>
                                            <p><span class="font-semibold">James Walker</span> Your task deadline is <span class="font-medium">3:00 pm</span></p>
                                            <p class="text-sm text-gray-500 mt-1">10 Minute ago</p>
                                        </div>
                                        </div>

                                        <div class="flex items-start gap-4 border-b pb-4">
                                        <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                        <div>
                                            <p><span class="font-semibold">James Walker</span> Sent you a file at <span class="font-medium">11:45 am</span></p>
                                            <p class="text-sm text-gray-500 mt-1">30 Minute ago</p>
                                        </div>
                                        </div>

                                        <div class="flex items-start gap-4 border-b pb-4">
                                        <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                        <div>
                                            <p><span class="font-semibold">James Walker</span> Meeting rescheduled to <span class="font-medium">1:30 pm</span></p>
                                            <p class="text-sm text-gray-500 mt-1">1 Hour ago</p>
                                        </div>
                                        </div>

                                        <div class="flex items-start gap-4 border-b pb-4">
                                        <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                        <div>
                                            <p><span class="font-semibold">James Walker</span> Approved your request</p>
                                            <p class="text-sm text-gray-500 mt-1">2 Hours ago</p>
                                        </div>
                                        </div>

                                        <div class="flex items-start gap-4 border-b pb-4">
                                        <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                        <div>
                                            <p><span class="font-semibold">James Walker</span> You have call scheduled at <span class="font-medium">4:00 pm</span></p>
                                            <p class="text-sm text-gray-500 mt-1">3 Hours ago</p>
                                        </div>
                                        </div>

                                        <div class="flex items-start gap-4 border-b pb-4">
                                        <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                        <div>
                                            <p><span class="font-semibold">James Walker</span> New comment on your post</p>
                                            <p class="text-sm text-gray-500 mt-1">3 Hours ago</p>
                                        </div>
                                        </div>

                                        <div class="flex items-start gap-4 border-b pb-4">
                                        <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                        <div>
                                            <p><span class="font-semibold">James Walker</span> You have meeting on <span class="font-medium">5:30 pm</span></p>
                                            <p class="text-sm text-gray-500 mt-1">4 Hours ago</p>
                                        </div>
                                        </div>

                                        <div class="flex items-start gap-4 border-b pb-4">
                                        <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                        <div>
                                            <p><span class="font-semibold">James Walker</span> Sent a reminder for report submission</p>
                                            <p class="text-sm text-gray-500 mt-1">5 Hours ago</p>
                                        </div>
                                        </div>

                                        <div class="flex items-start gap-4 border-b pb-4">
                                        <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                        <div>
                                            <p><span class="font-semibold">James Walker</span> Task completed: <span class="font-medium">UI Design</span></p>
                                            <p class="text-sm text-gray-500 mt-1">6 Hours ago</p>
                                        </div>
                                        </div>

                                        <div class="flex items-start gap-4 border-b pb-4">
                                        <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                        <div>
                                            <p><span class="font-semibold">James Walker</span> You have meeting on <span class="font-medium">10:30 am</span> tomorrow</p>
                                            <p class="text-sm text-gray-500 mt-1">Yesterday</p>
                                        </div>
                                        </div>

                                        <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                        <div>
                                            <p><span class="font-semibold">James Walker</span> Reminder: Submit your weekly report</p>
                                            <p class="text-sm text-gray-500 mt-1">Yesterday</p>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                

                                <div x-show="activeSection === 'payment'" x-transition class="bg-white p-6">
                                    <h3 class="text-xl font-semibold mb-4 border-b pb-2">Payment History</h3>

                                    <div class="overflow-x-auto">
                                        <table class="min-w-full text-sm text-left border rounded-lg overflow-hidden">
                                            <thead class="bg-gray-100 text-gray-700">
                                                <tr>
                                                <th class="px-4 py-2 border">Sr. No.</th>
                                                <th class="px-4 py-2 border">Paid to</th>
                                                <th class="px-4 py-2 border">Date</th>
                                                <th class="px-4 py-2 border">Amount</th>
                                                <th class="px-4 py-2 border">Payment status</th>
                                                <th class="px-4 py-2 border">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-700">
                                                <tr class="border-b">
                                                <td class="px-4 py-2">1.</td>
                                                <td class="px-4 py-2">Session1</td>
                                                <td class="px-4 py-2">24/04/2025</td>
                                                <td class="px-4 py-2">200</td>
                                                <td class="px-4 py-2">Paid</td>
                                                <td class="px-4 py-2">
                                                    <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                                    View invoice
                                                    </button>
                                                </td>
                                                </tr>
                                                <tr class="border-b">
                                                <td class="px-4 py-2">2.</td>
                                                <td class="px-4 py-2">Session2</td>
                                                <td class="px-4 py-2">26/04/2025</td>
                                                <td class="px-4 py-2">100</td>
                                                <td class="px-4 py-2">Paid</td>
                                                <td class="px-4 py-2">
                                                    <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                                    View invoice
                                                    </button>
                                                </td>
                                                </tr>
                                                <!-- Add more rows dynamically if needed -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Notifications Section -->
                                <div x-show="activeSection === 'notifications'" x-transition class="bg-white p-6 ">
                                    <h3 class="text-xl font-semibold mb-4 border-b pb-2">Notifications</h3>

                                <!-- Scrollable notification list -->
                                <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                                    <!-- Notification Items -->
                                    <div class="flex items-start gap-4 border-b pb-4">
                                        <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                            <div>
                                                <p><span class="font-semibold">James Walker</span> You have meeting on <span class="font-medium">12:30 pm</span></p>
                                                <p class="text-sm text-gray-500 mt-1">4 Minute ago</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-4 border-b pb-4">
                                            <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                            <div>
                                                <p><span class="font-semibold">James Walker</span> Your task deadline is <span class="font-medium">3:00 pm</span></p>
                                                <p class="text-sm text-gray-500 mt-1">10 Minute ago</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-4 border-b pb-4">
                                            <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                                <div>
                                                    <p><span class="font-semibold">James Walker</span> Sent you a file at <span class="font-medium">11:45 am</span></p>
                                                    <p class="text-sm text-gray-500 mt-1">30 Minute ago</p>
                                                </div>
                                            </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Meeting rescheduled to <span class="font-medium">1:30 pm</span></p>
                                        <p class="text-sm text-gray-500 mt-1">1 Hour ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Approved your request</p>
                                        <p class="text-sm text-gray-500 mt-1">2 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> You have call scheduled at <span class="font-medium">4:00 pm</span></p>
                                        <p class="text-sm text-gray-500 mt-1">3 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> New comment on your post</p>
                                        <p class="text-sm text-gray-500 mt-1">3 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> You have meeting on <span class="font-medium">5:30 pm</span></p>
                                        <p class="text-sm text-gray-500 mt-1">4 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Sent a reminder for report submission</p>
                                        <p class="text-sm text-gray-500 mt-1">5 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Task completed: <span class="font-medium">UI Design</span></p>
                                        <p class="text-sm text-gray-500 mt-1">6 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> You have meeting on <span class="font-medium">10:30 am</span> tomorrow</p>
                                        <p class="text-sm text-gray-500 mt-1">Yesterday</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Reminder: Submit your weekly report</p>
                                        <p class="text-sm text-gray-500 mt-1">Yesterday</p>
                                    </div>
                                    </div>
                                </div>
                                </div>


                                

                                <!-- Subscription Section (with internal tabs) -->
                                <div x-show="activeSection === 'profile'" x-transition>
                                    <!-- Company Header -->
                                    <div class="flex items-center space-x-4 mb-4">
                                        @php
                                            $user = auth()->user();
                                            $userId = $user->id;
                                            $profile = \App\Models\AdditionalInfo::where('user_id', $userId)
                                                ->where('user_type', 'trainer')
                                                ->where('doc_type', 'trainer_profile_picture')
                                                ->first();
                                        @endphp

                                        @if($profile && $profile->document_path)
                                            <img src="{{ $profile->document_path }}" alt="Profile" class="w-20 h-20 rounded-lg object-cover" />
                                        @else
                                            <img src="{{ asset('images/default-profile.png') }}" alt="Default" class="w-20 h-20 rounded-lg object-cover" />
                                        @endif


                                        <div>
                                            <h3 class="text-xl font-semibold">{{$user->name}}</h3>
                                            <p class="text-gray-600">{{$user->email}}</p>
                                            <p class="text-gray-600">{{$user->phone_number}}</p>
                                        </div>
                                    </div>
                                <div x-data="{ activeSubTab: 'personal' }">
                                    <!-- Inner Tabs -->
                                    <div class="border-b mb-4">
                                        <nav class="flex space-x-6">
                                            <button
                                                @click="activeSubTab = 'personal'"
                                                :class="activeSubTab === 'personal' ? 'pb-2 border-b-2 border-blue-600 font-medium' : 'pb-2 text-gray-500 hover:text-black'"
                                                class="focus:outline-none"
                                            >
                                                Personal Information
                                            </button>
                                            <button
                                                @click="activeSubTab = 'education'"
                                                :class="activeSubTab === 'education' ? 'pb-2 border-b-2 border-blue-600 font-medium' : 'pb-2 text-gray-500 hover:text-black'"
                                                class="focus:outline-none"
                                            >
                                                Educational Details
                                            </button>
                                            <button
                                                @click="activeSubTab = 'work'"
                                                :class="activeSubTab === 'work' ? 'pb-2 border-b-2 border-blue-600 font-medium' : 'pb-2 text-gray-500 hover:text-black'"
                                                class="focus:outline-none"
                                            >
                                                Work Experience
                                            </button>
                                            <button
                                                @click="activeSubTab = 'skills'"
                                                :class="activeSubTab === 'skills' ? 'pb-2 border-b-2 border-blue-600 font-medium' : 'pb-2 text-gray-500 hover:text-black'"
                                                class="focus:outline-none"
                                            >
                                                Skills & Training
                                            </button>
                                            <button
                                                @click="activeSubTab = 'additional'"
                                                :class="activeSubTab === 'additional' ? 'pb-2 border-b-2 border-blue-600 font-medium' : 'pb-2 text-gray-500 hover:text-black'"
                                                class="focus:outline-none"
                                            >
                                                Additional Information
                                            </button>
                                        </nav>

                                    </div>

                                    <!-- Inner Tab Contents -->
                                    <div >
                                       <!-- Personal Information Tab -->
                                        <div x-show="activeSubTab === 'personal'" x-transition >
                                            <!-- Success message -->
                                            <div id="trainer-info-success" class="col-12 ml-auto mr-auto text-center alert alert-success alert-dismissible fade show" style="display: none;">
                                                <strong>Success!</strong> <span class="message-text"></span>
                                            </div>

                                            <!-- Trainer Info Form -->
                                            <form id="trainer-info-form" action="{{ route('trainer.profile.update') }}" method="POST">
                                                @csrf
                                                <div class="grid grid-cols-2 gap-6 mt-3">
                                                    <!-- Full Name -->
                                                    <div>
                                                        <label class="block mb-1 font-medium">Full Name</label>
                                                        <input type="text" name="name" value="{{ $trainerSkills->name ?? '' }}" placeholder="John Doe" class="w-full border rounded px-3 py-2" />
                                                        @error('name')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Email -->
                                                    <div>
                                                        <label class="block mb-1 font-medium">Email</label>
                                                        <input type="email" name="email" value="{{ $trainerSkills->email ?? '' }}" placeholder="john.doe@example.com" class="w-full border rounded px-3 py-2" />
                                                        @error('email')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-2 gap-6 mt-3">
                                                    <!-- Phone Number -->
                                                    <div>
                                                        <label class="block mb-1 font-medium">Phone Number</label>
                                                        <input type="text" name="phone" value="{{ $trainerSkills->phone_number ?? '' }}" placeholder="+91 9876543210" class="w-full border rounded px-3 py-2" />
                                                        @error('phone')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Date of Birth -->
                                                    <div>
                                                        <label class="block mb-1 font-medium">Date of Birth</label>
                                                        <input type="date" name="dob" value="{{ $trainerSkills->date_of_birth ?? '' }}" class="w-full border rounded px-3 py-2" />
                                                        @error('dob')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-2 gap-6 mt-3">
                                                    <div>
                                                        <label class="block mb-1 text-sm font-medium">National ID Number</label>
                                                        <span class="text-xs text-blue-600">
                                                            National ID should start with 1 for male and 2 for female.
                                                        </span>
                                                        <input 
                                                            type="text" 
                                                            name="national_id" 
                                                            id="national_id" 
                                                            class="w-full border rounded-md p-2 mt-1" 
                                                            placeholder="Enter national id number" 
                                                            value="{{ $trainerSkills->national_id ?? '' }}"
                                                            maxlength="15"
                                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);" 
                                                        />
                                                        @error('national_id')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <!-- Address -->
                                                <div class="md:col-span-2 mt-3">
                                                    <label class="block mb-1 font-medium">Address</label>
                                                    <textarea name="address" placeholder="Enter address" class="w-full border rounded px-3 py-2">{{ $trainerSkills->address ?? '' }}</textarea>
                                                    @error('address')
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- City -->
                                                <div class="mt-3">
                                                    <label class="block font-medium mb-1">City</label>
                                                    <input type="text" name="city" value="{{ $trainerSkills->city }}" class="w-full border rounded px-3 py-2" />
                                                    @error('city')
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- State -->
                                                <div class="mt-3">
                                                    <label class="block font-medium mb-1">State</label>
                                                    <input type="text" name="state" placeholder="Enter State" value="{{ $trainerSkills->state }}" class="w-full border rounded px-3 py-2" />
                                                    @error('state')
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Country -->
                                                <div class="mt-3">
                                                    <label class="block font-medium mb-1">Country</label>
                                                    <input type="text" name="country" value="{{ $trainerSkills->country }}" class="w-full border rounded px-3 py-2" />
                                                    @error('country')
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Pin code -->
                                                <div class="mt-3">
                                                    <label class="block font-medium mb-1">Pin code</label>
                                                    <input type="text" name="pin_code" value="{{ $trainerSkills->pin_code }}" class="w-full border rounded px-3 py-2" />
                                                    @error('pin_code')
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Buttons -->
                                                <div class="mt-6 flex justify-end space-x-3">
                                                    <button @click.prevent="activeSubTab = 'education'" class="border px-6 py-2 rounded hover:bg-gray-100">
                                                        Next
                                                    </button>
                                                    <button type="button" id="save-trainer-info" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                                                        Save
                                                    </button>   
                                                </div>
                                            </form>
                                        </div>
                                        <!-- JavaScript for AJAX Submission -->
                                        <script>
                                            document.getElementById('save-trainer-info').addEventListener('click', function () {
                                                const form = document.getElementById('trainer-info-form');
                                                const formData = new FormData(form);
                                                const successBox = document.getElementById('trainer-info-success');
                                                const successText = successBox.querySelector('.message-text');

                                                // Clear previous errors
                                                form.querySelectorAll('.text-red-600').forEach(el => el.remove());

                                                fetch(form.action, {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                                                        'Accept': 'application/json'
                                                    },
                                                    body: formData
                                                })
                                                .then(response => {
                                                    if (!response.ok) return response.json().then(err => Promise.reject(err));
                                                    return response.json();
                                                })
                                                .then(data => {
                                                    // Show success
                                                    successText.textContent = data.message;
                                                    successBox.style.display = 'block';

                                                    // Hide after 3 seconds
                                                    setTimeout(() => {
                                                        successBox.style.display = 'none';
                                                        successText.textContent = '';
                                                    }, 3000);

                                                    // Optional: Move to next tab
                                                    if (typeof activeSubTab !== 'undefined') activeSubTab = 'education';
                                                })
                                                .catch(error => {
                                                    const errors = error.errors || {};
                                                    Object.keys(errors).forEach(field => {
                                                        const input = form.querySelector(`[name="${field}"]`);
                                                        if (input) {
                                                            const errorElem = document.createElement('p');
                                                            errorElem.className = 'text-red-600 text-sm mt-1';
                                                            errorElem.textContent = errors[field][0];
                                                            input.insertAdjacentElement('afterend', errorElem);
                                                        }
                                                    });
                                                });
                                            });
                                        </script>

                                        <!-- Educational Details Tab -->
                                        <div x-show="activeSubTab === 'education'" x-transition>
                                            <!-- Success Message -->
                                            <div id="education-success" class="col-12 ml-auto mr-auto text-center alert alert-success alert-dismissible fade show" style="display: none;">
                                                <strong>Success!</strong> <span class="message-text"></span>
                                            </div>

                                            <!-- Education Form -->
                                            <form id="education-info-form" method="POST" action="{{ route('trainer.education.update') }}" class="space-y-6">
                                                @csrf
                                                <div id="education-container" class="space-y-6">
                                                    @foreach($educationDetails as $education)
                                                    <div class="education-entry border border-gray-300 rounded-md p-4 space-y-4 relative">
                                                        <input type="hidden" name="education_id[]" value="{{ $education->id }}">

                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div>
                                                                <label class="block mb-1 font-medium">Highest Qualification</label>
                                                                <input type="text" name="high_education[]" 
                                                                    value="{{ ucfirst(str_replace('_', ' ', $education->high_education)) }}" 
                                                                    class="w-full border rounded px-3 py-2" 
                                                                    placeholder="e.g., Bachelor's Degree" />
                                                            </div>
                                                            <div>
                                                                <label class="block mb-1 font-medium">Field of Study</label>
                                                                <input type="text" name="field_of_study[]" 
                                                                    value="{{ ucfirst($education->field_of_study) }}" 
                                                                    class="w-full border rounded px-3 py-2" 
                                                                    placeholder="e.g., Computer Science" />
                                                            </div>
                                                        </div>

                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div>
                                                                <label class="block mb-1 font-medium">Institution Name</label>
                                                                <input type="text" name="institution[]" 
                                                                    value="{{ $education->institution }}" 
                                                                    class="w-full border rounded px-3 py-2" 
                                                                    placeholder="e.g., ABC University" />
                                                            </div>
                                                            <div>
                                                                <label class="block mb-1 font-medium">Graduation Year</label>
                                                                <input type="number" name="graduate_year[]" 
                                                                    value="{{ $education->graduate_year }}" 
                                                                    class="w-full border rounded px-3 py-2" 
                                                                    placeholder="e.g., 2023" 
                                                                    min="1900" max="2099" 
                                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                                            </div>
                                                        </div>

                                                        <button type="button" class="remove-education absolute top-2 right-2 text-red-600 font-bold text-lg" style="{{ $loop->first ? 'display:none;' : '' }}">&times;</button>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </form>

                                            <!-- Add More Button -->
                                            <div class="col-span-2">
                                                <button type="button" id="add-education" class="text-green-600 text-sm">Add education +</button>
                                            </div>

                                            <!-- Submit Buttons -->
                                            <div class="mt-6 flex justify-end space-x-3">
                                                <button @click.prevent="activeSubTab = 'work'" class="border px-6 py-2 rounded hover:bg-gray-100">Next</button>
                                                <button type="button" id="save-education-info" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save</button>
                                            </div>
                                        </div>

                                        <!-- AJAX & Dynamic Handling -->
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                const educationContainer = document.getElementById('education-container');
                                                const addEducationBtn = document.getElementById('add-education');
                                                const saveBtn = document.getElementById('save-education-info');
                                                const form = document.getElementById('education-info-form');
                                                const successBox = document.getElementById('education-success');
                                                const successText = successBox.querySelector('.message-text');

                                                // Add More Education Entry
                                                addEducationBtn.addEventListener('click', () => {
                                                    const firstEntry = educationContainer.querySelector('.education-entry');
                                                    const clone = firstEntry.cloneNode(true);

                                                    // Remove hidden input (education_id)
                                                    const hiddenInput = clone.querySelector('input[name="education_id[]"]');
                                                    if (hiddenInput) hiddenInput.remove();

                                                    // Clear all input fields
                                                    clone.querySelectorAll('input').forEach(input => input.value = '');

                                                    // Remove all error messages
                                                    clone.querySelectorAll('p.text-red-600').forEach(p => p.remove());

                                                    // Ensure remove button is visible
                                                    const removeBtn = clone.querySelector('.remove-education');
                                                    if (removeBtn) {
                                                        removeBtn.style.display = 'block';
                                                    }

                                                    educationContainer.appendChild(clone);
                                                });

                                                // Remove Education Entry
                                                educationContainer.addEventListener('click', function (e) {
                                                    if (e.target.classList.contains('remove-education')) {
                                                        const entry = e.target.closest('.education-entry');
                                                        const entries = educationContainer.querySelectorAll('.education-entry');
                                                        if (entries.length > 1) {
                                                            entry.remove();
                                                        }
                                                    }
                                                });

                                                // Save Education via AJAX
                                                saveBtn.addEventListener('click', function () {
                                                    const formData = new FormData(form);

                                                    // Clear previous errors
                                                    form.querySelectorAll('p.text-red-600').forEach(e => e.remove());

                                                    fetch(form.action, {
                                                        method: 'POST',
                                                        headers: {
                                                            'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                                                            'Accept': 'application/json'
                                                        },
                                                        body: formData
                                                    })
                                                    .then(response => {
                                                        if (!response.ok) {
                                                            return response.json().then(err => Promise.reject(err));
                                                        }
                                                        return response.json();
                                                    })
                                                    .then(data => {
                                                        successText.textContent = data.message || 'Saved successfully!';
                                                        successBox.style.display = 'block';

                                                        setTimeout(() => {
                                                            successBox.style.display = 'none';
                                                            successText.textContent = '';
                                                        }, 3000);

                                                        if (typeof nextTab === "function") nextTab();
                                                    })
                                                    .catch(error => {
                                                        const errors = error.errors || {};

                                                        Object.keys(errors).forEach(fieldName => {
                                                            const match = fieldName.match(/(\w+)\.(\d+)/);
                                                            if (match) {
                                                                const baseField = match[1];
                                                                const index = parseInt(match[2]);
                                                                const inputList = form.querySelectorAll(`[name="${baseField}[]"]`);
                                                                const input = inputList[index];
                                                                if (input) {
                                                                    const errorElem = document.createElement('p');
                                                                    errorElem.className = 'text-red-600 text-sm mt-1';
                                                                    errorElem.textContent = errors[fieldName][0];
                                                                    input.insertAdjacentElement('afterend', errorElem);
                                                                }
                                                            }
                                                        });
                                                    });
                                                });
                                            });
                                        </script>
                                        
                                        <!-- Work Experience Tab -->
                                        <div x-show="activeSubTab === 'work'" x-transition>
                                            <!-- Success Message -->
                                            <div id="work-success" class="col-12 ml-auto mr-auto text-center alert alert-success alert-dismissible fade show" style="display: none;">
                                                <strong>Success!</strong> <span class="message-text"></span>
                                            </div>

                                            <form id="work-info-form" method="POST" action="{{ route('trainer.workexprience.update')}}"   class="space-y-6">
                                                @csrf

                                                <div id="work-container" class="space-y-6">
                                                    @foreach($workExperiences as $work)
                                                    <div class="work-entry border border-gray-300 rounded-md p-4 space-y-4 relative">
                                                        <input type="hidden" name="work_id[]" value="{{ $work->id }}">

                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div>
                                                                <label class="block mb-1 font-medium">Job Role</label>
                                                                <input type="text" name="job_role[]" value="{{ $work->job_role }}" class="w-full border rounded px-3 py-2" placeholder="e.g., Software Engineer" />
                                                            </div>
                                                            <div>
                                                                <label class="block mb-1 font-medium">Organization</label>
                                                                <input type="text" name="organization[]" value="{{ $work->organization }}" class="w-full border rounded px-3 py-2" placeholder="e.g., XYZ Corp" />
                                                            </div>
                                                        </div>

                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div>
                                                                <label class="block mb-1 font-medium">Started From</label>
                                                                <input type="date" name="starts_from[]" value="{{ \Carbon\Carbon::parse($work->starts_from)->format('Y-m-d') }}" class="w-full border rounded px-3 py-2" />
                                                            </div>
                                                            <div>
                                                                <label class="block mb-1 font-medium">To</label>
                                                                <input 
                                                                    type="date" 
                                                                    name="end_to[]" 
                                                                    value="{{ !is_null($work->end_to) && $work->end_to !== 'Work here' ? \Carbon\Carbon::parse($work->end_to)->format('Y-m-d') : '' }}" 
                                                                    class="w-full border rounded px-3 py-2" 
                                                                    {{ is_null($work->end_to) || $work->end_to === 'Work here' ? 'disabled' : '' }} 
                                                                />

                                                                <label class="inline-flex items-center mt-2">
                                                                    <input 
                                                                            type="checkbox" 
                                                                            name="currently_working[{{ $loop->index }}]" 
                                                                            value="1"
                                                                            onchange="toggleEndDate(this)" 
                                                                            {{ is_null($work->end_to) || $work->end_to === 'Work here' ? 'checked' : '' }} 
                                                                        />

                                                                    <span class="ml-2">I currently work here</span>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <button type="button" class="remove-work absolute top-2 right-2 text-red-600 font-bold text-lg" style="{{ $loop->first ? 'display:none;' : '' }}">&times;</button>
                                                    </div>
                                                    @endforeach
                                                </div>

                                                <div class="text-left">
                                                    <button type="button" id="add-work" class="text-green-600 text-sm">+ Add Experience</button>
                                                </div>

                                                <div class="mt-6 flex justify-end space-x-3">
                                                    <button @click.prevent="activeSubTab = 'skills'" class="border px-6 py-2 rounded hover:bg-gray-100">Next</button>
                                                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                const workContainer = document.getElementById('work-container');
                                                const addWorkBtn = document.getElementById('add-work');
                                                const workForm = document.getElementById('work-info-form');
                                                const successBox = document.getElementById('work-success');
                                                const successText = successBox.querySelector('.message-text');

                                                // Add Work Experience Entry
                                                addWorkBtn.addEventListener('click', () => {
                                                    const firstEntry = workContainer.querySelector('.work-entry');
                                                    const clone = firstEntry.cloneNode(true);

                                                    // Remove hidden input
                                                    const hiddenInput = clone.querySelector('input[name="work_id[]"]');
                                                    if (hiddenInput) hiddenInput.remove();

                                                    // Clear all inputs
                                                    clone.querySelectorAll('input').forEach(input => {
                                                        if (input.type === 'text' || input.type === 'date') {
                                                            input.value = '';
                                                        }
                                                        if (input.type === 'checkbox') {
                                                            input.checked = false;
                                                        }
                                                    });

                                                    // Enable end date input
                                                    const endDateInput = clone.querySelector('input[name="end_to[]"]');
                                                    if (endDateInput) endDateInput.disabled = false;

                                                    // Remove error messages
                                                    clone.querySelectorAll('p.text-red-600').forEach(p => p.remove());

                                                    // Show remove button
                                                    const removeBtn = clone.querySelector('.remove-work');
                                                    if (removeBtn) removeBtn.style.display = 'block';

                                                    workContainer.appendChild(clone);
                                                });

                                                // Remove Work Experience Entry
                                                workContainer.addEventListener('click', function (e) {
                                                    if (e.target.classList.contains('remove-work')) {
                                                        const entry = e.target.closest('.work-entry');
                                                        const entries = workContainer.querySelectorAll('.work-entry');
                                                        if (entries.length > 1) {
                                                            entry.remove();
                                                        }
                                                    }
                                                });

                                                // function toggleEndDate(checkbox) {
                                                //     const endDateInput = checkbox.closest('.work-entry').querySelector('input[name="end_to[]"]');
                                                //     if (checkbox.checked) {
                                                //         endDateInput.value = '';
                                                //         endDateInput.disabled = true;
                                                //     } else {
                                                //         endDateInput.disabled = false;
                                                //     }
                                                // }


                                                // Toggle End Date when checkbox clicked
                                                window.toggleEndDate = function (checkbox) {
                                                    const container = checkbox.closest('div');
                                                    const endDateInput = container.querySelector('input[name="end_to[]"]');
                                                    if (checkbox.checked) {
                                                        endDateInput.value = '';
                                                        endDateInput.disabled = true;
                                                    } else {
                                                        endDateInput.disabled = false;
                                                    }
                                                };

                                                // AJAX Form Submission
                                                workForm.addEventListener('submit', function (e) {
                                                    e.preventDefault();
                                                    const formData = new FormData(workForm);

                                                    // Clear previous errors
                                                    workForm.querySelectorAll('p.text-red-600').forEach(e => e.remove());

                                                    fetch(workForm.action, {
                                                        method: 'POST',
                                                        headers: {
                                                            'X-CSRF-TOKEN': workForm.querySelector('[name="_token"]').value,
                                                            'Accept': 'application/json'
                                                        },
                                                        body: formData
                                                    })
                                                    .then(response => {
                                                        if (!response.ok) return response.json().then(err => Promise.reject(err));
                                                        return response.json();
                                                    })
                                                    .then(data => {
                                                        successText.textContent = data.message || 'Work experience saved successfully!';
                                                        successBox.style.display = 'block';

                                                        setTimeout(() => {
                                                            successBox.style.display = 'none';
                                                            successText.textContent = '';
                                                        }, 3000);
                                                    })
                                                    .catch(error => {
                                                        const errors = error.errors || {};

                                                        Object.keys(errors).forEach(field => {
                                                            const match = field.match(/(\w+)\.(\d+)/);
                                                            if (match) {
                                                                const baseField = match[1];
                                                                const index = parseInt(match[2]);
                                                                const inputList = workForm.querySelectorAll(`[name="${baseField}[]"]`);
                                                                const input = inputList[index];

                                                                if (input) {
                                                                    const errorElem = document.createElement('p');
                                                                    errorElem.className = 'text-red-600 text-sm mt-1';
                                                                    errorElem.textContent = errors[field][0];
                                                                    input.insertAdjacentElement('afterend', errorElem);
                                                                }
                                                            }
                                                        });
                                                    });
                                                });
                                            });
                                        </script>
                                        
                                        <!-- Skills & Training Tab -->
                                        <div x-show="activeSubTab === 'skills'" x-transition>
                                            <!-- Skills Success Message -->
                                            <div id="trainer-skills-success" class="col-12 ml-auto mr-auto text-center alert alert-success alert-dismissible fade show" style="display: none;">
                                                <strong>Success!</strong> <span class="message-text"></span>
                                            </div>

                                            <!-- Skills & Training Form -->
                                            <form id="trainer-skills-info-form" method="POST" action="{{ route('trainer.skill.update') }}">
                                                @csrf
                                                <div x-show="activeSubTab === 'skills'" x-cloak class="space-y-4">

                                                    <!-- Training Skills -->
                                                    <div>
                                                        <label class="block text-sm font-medium mb-1">Skills</label>
                                                        <input type="text" name="training_skills" placeholder="E.g., JavaScript, UI/UX, Communication"
                                                            class="w-full border rounded px-3 py-2"
                                                            value="{{ old('training_skills', $trainerSkills->training_skills ?? '') }}" />
                                                        @error('training_skills')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Training Experience -->
                                                    <div>
                                                        <label class="block text-sm font-medium mb-1">Training Experience</label>
                                                        <input type="text" name="training_experience" placeholder="training experience"
                                                            class="w-full border rounded px-3 py-2"
                                                            value="{{ old('training_experience', $trainerSkills->training_experience ?? '') }}" />
                                                        @error('training_experience')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Website Link -->
                                                    <div>
                                                        <label class="block text-sm font-medium mb-1">Website Link</label>
                                                        <input type="url" name="website_link" placeholder="https://example.com"
                                                            class="w-full border rounded px-3 py-2"
                                                            value="{{ old('website_link', $trainerSkills->website_link ?? '') }}" />
                                                        @error('website_link')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Portfolio Link -->
                                                    <div>
                                                        <label class="block text-sm font-medium mb-1">Portfolio Link</label>
                                                        <input type="url" name="portfolio_link" placeholder="https://portfolio.com"
                                                            class="w-full border rounded px-3 py-2"
                                                            value="{{ old('portfolio_link', $trainerSkills->portfolio_link ?? '') }}" />
                                                        @error('portfolio_link')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Buttons -->
                                                    <div class="flex justify-end gap-4 mt-6">
                                                        <button type="button" class="border rounded px-6 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                            @click="nextTab">
                                                            Next
                                                        </button>
                                                        <button type="button" id="save-trainer-skills-info"
                                                            class="bg-blue-700 text-white px-6 py-2 rounded text-sm hover:bg-blue-800">Save</button>
                                                    </div>
                                                </div>
                                            </form>


                                        </div>
                                        <script>
                                            document.getElementById('save-trainer-skills-info').addEventListener('click', function () {
                                                const form = document.getElementById('trainer-skills-info-form');
                                                const formData = new FormData(form);
                                                const successBox = document.getElementById('trainer-skills-success');
                                                const successText = successBox.querySelector('.message-text');

                                                // Clear previous error messages
                                                form.querySelectorAll('.text-red-600').forEach(el => el.remove());

                                                fetch(form.action, {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                                                        'Accept': 'application/json'
                                                    },
                                                    body: formData
                                                })
                                                .then(response => {
                                                    if (!response.ok) return response.json().then(err => Promise.reject(err));
                                                    return response.json();
                                                })
                                                .then(data => {
                                                    successText.textContent = data.message;
                                                    successBox.style.display = 'block';
                                                    setTimeout(() => {
                                                        successBox.style.display = 'none';
                                                        successText.textContent = '';
                                                    }, 3000);

                                                    if (typeof nextTab === "function") nextTab();
                                                })
                                                .catch(error => {
                                                    const errors = error.errors || {};
                                                    Object.keys(errors).forEach(field => {
                                                        const input = form.querySelector(`[name="${field}"]`);
                                                        if (input) {
                                                            const errorElem = document.createElement('p');
                                                            errorElem.className = 'text-red-600 text-sm mt-1';
                                                            errorElem.textContent = errors[field][0];
                                                            input.insertAdjacentElement('afterend', errorElem);
                                                        }
                                                    });
                                                });
                                            });
                                        </script>

                                        <!-- Additional Information Tab -->
                                        <div x-show="activeSubTab === 'additional'" x-transition>
                                            <h3 class="text-lg font-semibold mb-4">Upload Documents</h3>
                                           
                                             @php
                                                $user = auth()->user();
                                                $userId = $user->id;
                                                $profile = \App\Models\AdditionalInfo::where('user_id', $userId)
                                                    ->where('user_type', 'trainer')
                                                    ->where('doc_type', 'trainer_profile_picture')
                                                    ->first();

                                                $resume = \App\Models\AdditionalInfo::where('user_id', $userId)
                                                    ->where('user_type', 'trainer')
                                                    ->where('doc_type', 'resume')
                                                    ->first();  

                                                $certificate = \App\Models\AdditionalInfo::where('user_id', $userId)
                                                    ->where('user_type', 'trainer')
                                                    ->where('doc_type', 'training_certificate')
                                                    ->first();  
                                            @endphp

                                            <!-- Success Message -->
                                            <div id="trainer-additional-success" class="alert alert-success text-center" style="display: none;">
                                                <strong>Success!</strong> <span class="message-text"></span>
                                            </div>

                                            <!-- Trainer Additional Info Form -->
                                            <form id="trainer-additional-info-form" method="POST" action="{{ route('trainer.additional.update') }}" enctype="multipart/form-data">
                                                @csrf
                                                <div x-show="activeSubTab === 'additional'" x-cloak class="space-y-4 text-sm">

                                                    <!-- Resume -->
                                                    <div>
                                                        <label class="font-medium mb-1 block">Upload Resume</label>
                                                        <div class="flex flex-col gap-2">
                                                            @if($resume)
                                                                <div class="flex items-center gap-4">
                                                                    <a href="{{ asset($resume->document_path) }}" target="_blank" class="bg-blue-600 text-white px-3 py-1.5 text-xs rounded hover:bg-blue-700">📄 View Resume</a>
                                                                    <button type="button" class="delete-file text-red-600 text-sm" data-type="resume">Delete</button>
                                                                </div>
                                                            @endif
                                                            <input type="file" name="resume" accept=".pdf,.doc,.docx,.txt" class="border rounded p-2 w-full" />
                                                        </div>
                                                    </div>

                                                    <!-- Profile Picture -->
                                                    <div>
                                                        <label class="font-medium mb-1 block">Upload Profile Picture</label>
                                                        <div class="flex flex-col gap-2">
                                                            @if($profile)
                                                                <div class="flex items-center gap-4">
                                                                    <a href="{{ asset($profile->document_path) }}" target="_blank" class="bg-green-600 text-white px-3 py-1.5 text-xs rounded hover:bg-green-700">📄 View Profile</a>
                                                                    <button type="button" class="delete-file text-red-600 text-sm" data-type="profile_picture">Delete</button>
                                                                </div>
                                                            @endif
                                                            <input type="file" name="profile_picture" accept="image/*" class="border rounded p-2 w-full" />
                                                        </div>
                                                    </div>

                                                    <!-- Training Certificate -->
                                                    <div>
                                                        <label class="font-medium mb-1 block">Upload Training Certificate</label>
                                                        <div class="flex flex-col gap-2">
                                                            @if($certificate)
                                                                <div class="flex items-center gap-4">
                                                                    <a href="{{ asset($certificate->document_path) }}" target="_blank" class="bg-purple-600 text-white px-3 py-1.5 text-xs rounded hover:bg-purple-700">📄 View Certificate</a>
                                                                    <button type="button" class="delete-file text-red-600 text-sm" data-type="training_certificate">Delete</button>
                                                                </div>
                                                            @endif
                                                            <input type="file" name="training_certificate" accept=".pdf,.jpg,.jpeg,.png" class="border rounded p-2 w-full" />
                                                        </div>
                                                    </div>

                                                    <!-- Submit Button -->
                                                    <div class="flex justify-end mt-6">
                                                        <button type="button" id="save-trainer-additional-info" class="bg-blue-700 text-white px-6 py-2 rounded hover:bg-blue-800">Save</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- Delete Confirmation Modal -->
                                        <div id="trainerDeleteConfirmModal" class="fixed inset-0 bg-gray-100 bg-opacity-90 flex items-center justify-center z-50 hidden">
                                            <div class="bg-white rounded p-6 w-full max-w-sm shadow-lg">
                                                <h2 class="text-lg font-semibold mb-4">Confirm Delete</h2>
                                                <p class="text-gray-700 mb-6">Are you sure you want to delete <span id="trainer-delete-file-type" class="font-semibold"></span>?</p>
                                                <div class="flex justify-end gap-4">
                                                    <button type="button" id="trainerCancelDeleteBtn" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">Cancel</button>
                                                    <button type="button" id="trainerConfirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded">Yes, Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Script -->
                                        <script>
                                            // Save AJAX
                                            document.getElementById('save-trainer-additional-info').addEventListener('click', function () {
                                                const form = document.getElementById('trainer-additional-info-form');
                                                const formData = new FormData(form);
                                                const successBox = document.getElementById('trainer-additional-success');
                                                const successText = successBox.querySelector('.message-text');

                                                form.querySelectorAll('.text-red-600').forEach(e => e.remove());

                                                fetch(form.action, {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                                                        'Accept': 'application/json'
                                                    },
                                                    body: formData
                                                })
                                                .then(response => {
                                                    if (!response.ok) return response.json().then(err => Promise.reject(err));
                                                    return response.json();
                                                })
                                                .then(data => {
                                                    successText.textContent = data.message;
                                                    successBox.style.display = 'block';
                                                    setTimeout(() => {
                                                        successBox.style.display = 'none';
                                                        successText.textContent = '';
                                                        location.reload(); // 🔄 Reload after success
                                                    }, 1000);
                                                })
                                                .catch(error => {
                                                    const errors = error.errors || {};
                                                    Object.keys(errors).forEach(field => {
                                                        const input = form.querySelector(`[name="${field}"]`);
                                                        if (input) {
                                                            const errorElem = document.createElement('p');
                                                            errorElem.className = 'text-red-600 text-sm mt-1';
                                                            errorElem.textContent = errors[field][0];
                                                            input.insertAdjacentElement('afterend', errorElem);
                                                        }
                                                    });
                                                });
                                            });

                                            // Delete logic
                                            let trainerSelectedFileType = null;

                                            document.querySelectorAll('.delete-file').forEach(btn => {
                                                btn.addEventListener('click', function () {
                                                    trainerSelectedFileType = this.dataset.type;
                                                    document.getElementById('trainer-delete-file-type').textContent = trainerSelectedFileType.replace('_', ' ');
                                                    document.getElementById('trainerDeleteConfirmModal').classList.remove('hidden');
                                                });
                                            });

                                            document.getElementById('trainerCancelDeleteBtn').addEventListener('click', function () {
                                                document.getElementById('trainerDeleteConfirmModal').classList.add('hidden');
                                                trainerSelectedFileType = null;
                                            });

                                            document.getElementById('trainerConfirmDeleteBtn').addEventListener('click', function () {
                                                if (!trainerSelectedFileType) return;

                                                fetch(`{{ route('trainer.additional.delete', ':type') }}`.replace(':type', trainerSelectedFileType), {
                                                    method: 'DELETE',
                                                    headers: {
                                                        'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                                                        'Accept': 'application/json'
                                                    }
                                                })
                                                .then(res => res.json())
                                                .then(data => {
                                                    document.getElementById('trainerDeleteConfirmModal').classList.add('hidden');
                                                    trainerSelectedFileType = null;

                                                    const successBox = document.getElementById('trainer-additional-success');
                                                    const successText = successBox.querySelector('.message-text');
                                                    successText.textContent = data.message;
                                                    successBox.style.display = 'block';

                                                    setTimeout(() => {
                                                        successBox.style.display = 'none';
                                                        successText.textContent = '';
                                                        location.reload(); // 🔄 Reload after delete
                                                    }, 3000);
                                                })
                                                .catch(() => {
                                                    alert('Delete failed.');
                                                    document.getElementById('trainerDeleteConfirmModal').classList.add('hidden');
                                                });
                                            });
                                        </script>


                                    </div>
                                </div>


                                <!-- Privacy Policy Section -->
                                <div x-show="activeSection === 'privacy'" x-transition>
                                    <h3 class="text-xl font-semibold mb-4">Privacy Policy</h3>
                                    
                                    <p class="mb-4">At XYZ Infotech, we are committed to protecting your privacy. This Privacy Policy outlines how we collect, use, and safeguard your personal information.</p>

                                    <h4 class="text-lg font-semibold mb-2">1. Information We Collect</h4>
                                    <ul class="list-disc list-inside mb-4 text-sm text-gray-700">
                                        <li>Personal information such as name, email address, phone number, and address.</li>
                                        <li>Usage data including pages visited, time spent, and actions taken on our platform.</li>
                                        <li>Device and browser information for improving user experience.</li>
                                    </ul>

                                    <h4 class="text-lg font-semibold mb-2">2. How We Use Your Information</h4>
                                    <ul class="list-disc list-inside mb-4 text-sm text-gray-700">
                                        <li>To provide and maintain our services.</li>
                                        <li>To improve customer support and experience.</li>
                                        <li>To send transactional and promotional communications.</li>
                                        <li>To comply with legal obligations.</li>
                                    </ul>

                                    <h4 class="text-lg font-semibold mb-2">3. Data Security</h4>
                                    <p class="mb-4 text-sm text-gray-700">We implement a variety of security measures to maintain the safety of your personal information. However, no method of transmission over the Internet is 100% secure.</p>

                                    <h4 class="text-lg font-semibold mb-2">4. Third-Party Services</h4>
                                    <p class="mb-4 text-sm text-gray-700">We may use third-party services (e.g., analytics providers, payment gateways) that collect, monitor, and analyze data. These services have their own privacy policies.</p>

                                    <h4 class="text-lg font-semibold mb-2">5. Your Rights</h4>
                                    <ul class="list-disc list-inside mb-4 text-sm text-gray-700">
                                        <li>Access and update your personal information.</li>
                                        <li>Request deletion of your data.</li>
                                        <li>Opt out of marketing communications.</li>
                                    </ul>

                                    <h4 class="text-lg font-semibold mb-2">6. Changes to This Policy</h4>
                                    <p class="mb-4 text-sm text-gray-700">We may update this Privacy Policy periodically. We will notify you of any significant changes via email or on our platform.</p>

                                    <h4 class="text-lg font-semibold mb-2">7. Contact Us</h4>
                                    <p class="text-sm text-gray-700">If you have any questions or concerns about this policy, please contact us at <a href="mailto:support@xyzinfotech.com" class="text-blue-600 underline">support@xyzinfotech.com</a>.</p>
                                </div>


                                <!-- Log Out Section -->
                                <div x-show="activeSection === 'logout'" x-transition>
                                    <h3 class="text-xl font-semibold mb-4">Log Out</h3>
                                    <p>Are you sure you want to log out?</p>
                                    <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Log Out</button>
                                </div>

                                

                            </div>
                        </section>
                    </div>
                </main>

            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
         
            </div>
        </div>

        <!-- Feather Icons -->
        <script>
            feather.replace()
        </script>

    </div>
           







      
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('deleteAccountBtn').addEventListener('click', function (e) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action will permanently delete your account!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteAccountForm').submit();
        }
    });
});
</script>
@include('site.trainer.componants.footer')