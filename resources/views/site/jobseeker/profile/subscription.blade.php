<div x-show="tab === 'subscription'" x-cloak>
                        <h2 class="text-xl font-semibold mb-4">Subscription</h2>
                        @php
                            $subscriptions = App\Models\SubscriptionPlan::where('user_type', 'jobseeker')->get();
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
                                ->where('subscription_plans.user_type', 'jobseeker')
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

                        <!-- Subscription Plans Banner -->
                        <div class="bg-white border p-4 rounded-lg shadow-sm mb-6 flex items-center justify-between">
                            <div>
                                <h3 class="text-md font-semibold">Subscription Plans</h3>
                                <p class="text-sm text-gray-500">Purchase subscription to get access to premium features of Talentrek</p>
                            </div>
                            <button onclick="document.getElementById('plansModal').classList.remove('hidden')" 
                                    class="bg-blue-600 text-white text-sm font-medium px-5 py-2 rounded-md hover:bg-blue-700">
                                View Plans
                            </button>
                        </div>

                        <!-- Plans Modal -->
                        <div id="plansModal" class="fixed inset-0 bg-gray-200 bg-opacity-80 flex items-center justify-center z-50 hidden">
                            <div class="bg-white w-full max-w-5xl p-6 rounded-lg shadow-lg relative">
                                <!-- Close Button -->
                                <button onclick="document.getElementById('plansModal').classList.add('hidden')" 
                                        class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-lg">
                                    ✕
                                </button>

                                <h3 class="text-xl font-semibold mb-6">Available Subscription Plans</h3>

                               <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                                                <input type="hidden" name="user_id" value="{{ auth()->user('jobseeker')->id }}">
                                                <input type="hidden" name="type" value="jobseeker">
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


                        <!-- Subscription History Table -->
                        <h3 class="text-md font-semibold mb-2">Subscription History</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-gray-200 text-sm">
                                <thead class="bg-gray-100 text-left">
                                    <tr>
                                        <th class="px-4 py-2 font-medium text-gray-700">Sr. No.</th>
                                        <th class="px-4 py-2 font-medium text-gray-700">Subscription</th>
                                        <th class="px-4 py-2 font-medium text-gray-700">Duration</th>
                                        <th class="px-4 py-2 font-medium text-gray-700">Purchased on</th>
                                        <th class="px-4 py-2 font-medium text-gray-700">Expired on</th>
                                    </tr>
                                </thead>
                                <tbody>
    @foreach ($purchasedSubscriptions as $index => $subscription)
        @php
            $startDate = \Carbon\Carbon::parse($subscription->paid_at);
            $endDate   = $startDate->copy()->addDays($subscription->duration_days);
        @endphp
        <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
            <td class="px-4 py-3">{{ $index + 1 }}.</td>
            <td class="px-4 py-3">{{ $subscription->title }}</td>
            <td class="px-4 py-3">
                {{ $subscription->duration_days }} {{ Str::plural('days', $subscription->duration_days) }}
            </td>
            <td class="px-4 py-3">{{ $startDate->format('d/m/Y H:i') }}</td>
            <td class="px-4 py-3">{{ $endDate->format('d/m/Y H:i') }}</td>
        </tr>
    @endforeach
</tbody>

                            </table>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            // Auto-open plans modal if subscription is expiring soon
                            @if($showPlansModal)
                                document.getElementById('plansModal').classList.remove('hidden');
                            @endif

                            // Allow closing modal with Escape key
                            document.addEventListener('keydown', function (e) {
                                if (e.key === 'Escape') {
                                    document.getElementById('plansModal').classList.add('hidden');
                                }
                            });
                        });
                    </script>