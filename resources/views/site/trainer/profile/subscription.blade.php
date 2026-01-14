<div x-show="activeSection === 'subscription'" x-transition class="bg-white p-6">
    <h3 class="text-xl font-semibold mb-4 border-b pb-2">{{ langLabel('subscription') }}</h3>

    @php
        use Carbon\Carbon;
        use Illuminate\Support\Str;

        $userId = auth()->id();

        // Fetch trainer plans
        $subscriptions = App\Models\SubscriptionPlan::where('user_type', 'trainer')->get();

        // Fetch subscription tax percentage (e.g., 5%)
        $taxPercent = App\Models\Setting::value('subscriptionTax') ?? 0;

        // Purchased subscriptions
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

        // Auto-open modal if expiring soon
        $showPlansModal = false;
        foreach ($purchasedSubscriptions as $sub) {
            $startDate = Carbon::parse($sub->paid_at);
            $endDate = $startDate->copy()->addDays($sub->duration_days);
            $daysLeft = Carbon::now()->diffInDays($endDate, false);

            if ($daysLeft > 0 && $daysLeft <= 30) {
                $showPlansModal = true;
                break;
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

    <!-- Subscription Header -->
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
                    @php
                        $price = $plan->price;
                        $taxAmount = ($price * $taxPercent) / 100;
                        $total = $price + $taxAmount;
                    @endphp

                    <div class="border rounded-lg p-4 shadow-sm text-center hover:shadow-md transition">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 flex items-center justify-center bg-gray-100 rounded-full mb-2">
                                <i class="fas fa-crown text-blue-500 text-xl"></i>
                            </div>

                            <h4 class="font-semibold">{{ $plan->title }}</h4>
                            <p class="font-bold text-lg mt-1">AED {{ number_format($price, 2) }}</p>
                        </div>

                        <p class="text-sm text-gray-500 mt-2 mb-3">{{ $plan->description }}</p>

                        <ul class="list-disc list-outside pl-5 text-sm text-gray-700 mb-4 text-left">
                            @foreach(is_array($plan->features) ? $plan->features : explode(',', $plan->features) as $feature)
                                <li>{{ trim($feature) }}</li>
                            @endforeach
                        </ul>

                        <div class="border-t pt-3 text-sm text-gray-700">
                            <p>Base Price: <span class="font-medium">AED {{ number_format($price, 2) }}</span></p>
                            <p>Tax ({{ $taxPercent }}%): <span class="font-medium">AED {{ number_format($taxAmount, 2) }}</span></p>
                            <p class="font-semibold mt-1">Total: <span class="text-blue-600 font-bold">AED {{ number_format($total, 2) }}</span></p>
                        </div>

                        <form action="{{ route('subscription.payment') }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <input type="hidden" name="user_id" value="{{ auth()->user('trainer')->id }}">
                            <input type="hidden" name="type" value="trainer">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white w-full py-2 rounded-md text-sm font-medium transition">
                                {{ langLabel('renew_subscription') }}
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Subscription History -->
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
                @forelse ($purchasedSubscriptions as $index => $subscription)
                    @php
                        $startDate = Carbon::parse($subscription->paid_at);
                        $endDate = $startDate->copy()->addDays($subscription->duration_days);
                    @endphp
                    <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                        <td class="px-4 py-3">{{ $index + 1 }}.</td>
                        <td class="px-4 py-3">{{ $subscription->title }}</td>
                        <td class="px-4 py-3">{{ $subscription->duration_days }} {{ Str::plural('days', $subscription->duration_days) }}</td>
                        <td class="px-4 py-3">{{ $startDate->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $endDate->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">
                            {{ langLabel('no_subscription_found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
