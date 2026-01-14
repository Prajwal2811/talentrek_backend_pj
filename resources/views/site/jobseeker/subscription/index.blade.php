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
            // Fetch jobseeker subscription plans
            $subscriptions = App\Models\SubscriptionPlan::where('user_type', 'jobseeker')->get();

            // Fetch tax percentage (for example, 5 means 5%)
            $taxPercent = App\Models\Setting::value('subscriptionTax') ?? 0;
        @endphp

        <!-- Subscription Modal -->
        <div id="subscriptionModal" class="fixed inset-0 bg-gray-200 bg-opacity-80 flex items-center justify-center z-50">
            <div class="bg-white w-full max-w-3xl p-5 rounded-xl shadow-lg relative"> {{-- Reduced max width from 5xl → 3xl --}}
                @include('admin.errors')

                <h3 class="text-xl font-semibold mb-5 text-gray-800 text-center">
                    {{ langLabel('available_subscription_plans') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach($subscriptions as $plan)
                        @php
                            $taxAmount = ($plan->price * $taxPercent) / 100;
                            $totalPrice = $plan->price + $taxAmount;
                        @endphp

                        <div class="border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition duration-200 bg-white">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 flex items-center justify-center bg-blue-50 rounded-full mb-2.5">
                                    <i class="fas fa-crown text-blue-500 text-xl"></i>
                                </div>

                                <h4 class="font-semibold text-base text-gray-800">{{ $plan->title }}</h4>
                                <p class="font-bold text-lg text-blue-600 mt-1">AED {{ number_format($plan->price, 2) }}</p>
                            </div>

                            <p class="text-sm text-gray-500 mt-2 mb-3 text-center">{{ $plan->description }}</p>

                            <ul class="list-disc list-outside pl-5 text-sm text-gray-700 mb-4 text-left space-y-1">
                                @foreach(is_array($plan->features) ? $plan->features : explode(',', $plan->features) as $feature)
                                    <li>{{ trim($feature) }}</li>
                                @endforeach
                            </ul>

                            <!-- Stylish Tax and Total Section -->
                            <div class="bg-gray-50 border border-gray-100 rounded-md px-3 py-2.5 mb-3">
                                <div class="flex justify-between items-center text-xs mb-1">
                                    <span class="text-gray-600">Base Price:</span>
                                    <span class="font-medium text-gray-800">AED {{ number_format($plan->price, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-xs mb-1">
                                    <span class="text-gray-600">Tax ({{ $taxPercent }}%):</span>
                                    <span class="font-medium text-gray-800">AED {{ number_format($taxAmount, 2) }}</span>
                                </div>
                                <div class="border-t border-gray-200 mt-2 pt-1.5 flex justify-between items-center">
                                    <span class="font-semibold text-gray-700 text-sm">Total (incl. tax):</span>
                                    <span class="font-bold text-blue-600 text-sm">AED {{ number_format($totalPrice, 2) }}</span>
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
                                    class="bg-blue-600 hover:bg-blue-700 text-white w-full py-2 rounded-md text-xs font-medium shadow-sm transition">
                                    <i class="fas fa-shopping-cart mr-1"></i> {{ langLabel('buy') }} {{ langLabel('subscription') }}
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>


