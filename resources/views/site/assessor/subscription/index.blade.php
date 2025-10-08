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


       @php
    // Get assessor standard subscription plans
    $subscriptions = App\Models\SubscriptionPlan::where('user_type', 'assessor')
        ->where('slug', 'assessor_standard')
        ->get();

    // Fetch tax percentage (for example, 5 means 5%)
    $taxPercent = App\Models\Setting::value('subscriptionTax') ?? 0;
@endphp

<!-- Subscription Modal -->
<div id="subscriptionModal" class="fixed inset-0 bg-gray-200 bg-opacity-80 flex items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white w-full max-w-2xl p-6 rounded-2xl shadow-lg relative">
        @include('admin.errors')


        <h3 class="text-2xl font-semibold mb-6 text-gray-800">{{ langLabel('available_subscription_plans') }}</h3>

        <div class="grid grid-cols-1 gap-6">
            @foreach($subscriptions as $plan)
                @php
                    $taxAmount = ($plan->price * $taxPercent) / 100;
                    $totalPrice = $plan->price + $taxAmount;
                @endphp

                <div class="border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition duration-200 bg-white">
                    <!-- Plan Header -->
                    <div class="flex flex-col items-center">
                        <div class="w-14 h-14 flex items-center justify-center bg-blue-50 rounded-full mb-3">
                            <i class="fas fa-crown text-blue-500 text-2xl"></i>
                        </div>

                        <h4 class="font-semibold text-lg text-gray-800">{{ $plan->title }}</h4>
                        <p class="font-bold text-xl text-blue-600 mt-1">AED {{ number_format($plan->price, 2) }}</p>
                    </div>

                    <!-- Description -->
                    <p class="text-sm text-gray-500 mt-3 mb-4 text-center">{{ $plan->description }}</p>

                    <!-- Features List -->
                    <ul class="list-disc list-outside pl-6 text-sm text-gray-700 mb-5 text-left space-y-1">
                        @foreach(is_array($plan->features) ? $plan->features : explode(',', $plan->features) as $feature)
                            <li>{{ trim($feature) }}</li>
                        @endforeach
                    </ul>

                    <!-- Tax & Total Section -->
                    <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3 mb-4">
                        <div class="flex justify-between items-center text-sm mb-1">
                            <span class="text-gray-600">Base Price:</span>
                            <span class="font-medium text-gray-800">AED {{ number_format($plan->price, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm mb-1">
                            <span class="text-gray-600">Tax ({{ $taxPercent }}%):</span>
                            <span class="font-medium text-gray-800">AED {{ number_format($taxAmount, 2) }}</span>
                        </div>
                        <div class="border-t border-gray-200 mt-2 pt-2 flex justify-between items-center">
                            <span class="font-semibold text-gray-700">Total (incl. tax):</span>
                            <span class="font-bold text-blue-600 text-base">AED {{ number_format($totalPrice, 2) }}</span>
                        </div>
                    </div>

                    <!-- Purchase Button -->
                    <form action="{{ route('subscription.payment') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <input type="hidden" name="user_id" value="{{ auth()->user('assessor')->id }}">
                        <input type="hidden" name="type" value="assessor">
                        <input type="hidden" name="total_price" value="{{ $totalPrice }}">

                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white w-full py-2.5 rounded-md text-sm font-medium shadow-sm transition flex items-center justify-center gap-1">
                            <i class="fas fa-shopping-cart"></i>
                            {{ langLabel('buy') }} {{ langLabel('subscription') }}
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</div>
