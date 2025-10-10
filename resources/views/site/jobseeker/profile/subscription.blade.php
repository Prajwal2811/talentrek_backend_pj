<div x-show="tab === 'subscription'" x-cloak>
    <h2 class="text-xl font-semibold mb-4">Subscription</h2>

    @php
        $subscriptions = App\Models\SubscriptionPlan::where('user_type', 'jobseeker')->get();

        // Tax percentage (e.g., 5%)
        $taxPercent = App\Models\Setting::value('subscriptionTax') ?? 0;

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
                $endDate = $startDate->copy()->addDays($sub->duration_days);
                $daysLeft = \Carbon\Carbon::now()->diffInDays($endDate, false);

                // Auto show modal if expiring soon
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

            // Close modal on Escape
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    document.getElementById('plansModal').classList.add('hidden');
                }
            });
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
    <div id="plansModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white w-full max-w-4xl p-6 rounded-xl shadow-xl relative overflow-y-auto max-h-[90vh]">
            <!-- Close Button -->
            <button onclick="document.getElementById('plansModal').classList.add('hidden')"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-lg">
                ✕
            </button>

            <h3 class="text-2xl font-semibold mb-6 text-center text-gray-800 border-b border-gray-200 pb-2">
                Available Subscription Plans
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach($subscriptions as $plan)
                    @php
                        $taxAmount = ($plan->price * $taxPercent) / 100;
                        $totalPrice = $plan->price + $taxAmount;
                    @endphp

                    <div
                        class="border rounded-xl p-5 shadow-sm hover:shadow-md transition-all duration-200 bg-white text-center">
                        <div class="flex flex-col items-center mb-3">
                            <div class="w-14 h-14 flex items-center justify-center bg-blue-50 rounded-full mb-3">
                                <i class="fas fa-crown text-blue-500 text-2xl"></i>
                            </div>

                            <h4 class="font-semibold text-lg text-gray-800">{{ $plan->title }}</h4>
                            <p class="font-bold text-xl text-blue-600 mt-1">
                                AED {{ number_format($plan->price, 2) }}
                            </p>
                        </div>

                        <p class="text-sm text-gray-500 mb-4">{{ $plan->description }}</p>

                        <ul class="list-disc list-outside pl-5 text-sm text-gray-700 mb-5 text-left space-y-1">
                            @foreach(is_array($plan->features) ? $plan->features : explode(',', $plan->features) as $feature)
                                <li>{{ trim($feature) }}</li>
                            @endforeach
                        </ul>

                        <!-- Tax and Total Section -->
                        <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3 mb-4">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Base Price:</span>
                                <span class="font-medium text-gray-800">AED {{ number_format($plan->price, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">Tax ({{ $taxPercent }}%):</span>
                                <span class="font-medium text-gray-800">AED {{ number_format($taxAmount, 2) }}</span>
                            </div>
                            <div class="border-t border-gray-200 mt-2 pt-2 flex justify-between text-sm font-semibold">
                                <span>Total (incl. tax):</span>
                                <span class="text-blue-600">AED {{ number_format($totalPrice, 2) }}</span>
                            </div>
                        </div>

                        <!-- Purchase Button -->
                        <form action="{{ route('subscription.payment') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <input type="hidden" name="user_id" value="{{ auth()->user('jobseeker')->id }}">
                            <input type="hidden" name="type" value="jobseeker">
                            <input type="hidden" name="total_price" value="{{ $totalPrice }}">

                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white w-full py-2.5 rounded-md text-sm font-medium shadow-sm transition">
                                <i class="fas fa-shopping-cart mr-1"></i> Renew Subscription
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
                        $endDate = $startDate->copy()->addDays($subscription->duration_days);
                    @endphp
                    <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                        <td class="px-4 py-3">{{ $index + 1 }}.</td>
                        <td class="px-4 py-3">{{ $subscription->title }}</td>
                        <td class="px-4 py-3">{{ $subscription->duration_days }}
                            {{ Str::plural('day', $subscription->duration_days) }}
                        </td>
                        <td class="px-4 py-3">{{ $startDate->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">{{ $endDate->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>