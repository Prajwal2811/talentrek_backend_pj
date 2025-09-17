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
            use App\Models\SubscriptionPlan;
            use App\Models\PurchasedSubscription;
            use App\Models\Recruiters;

            // Get recruiter subscription plans
            $subscriptions = SubscriptionPlan::where('user_type', 'recruiter')->get();
            $recruiter     = auth()->guard('recruiter')->user();
            $recruiterId   = $recruiter->id;
            $role          = $recruiter->role;

            if ($role === "main") {
                // Check subscription for main recruiter
                $isExpired = PurchasedSubscription::where('user_id', $recruiterId)
                                ->where('user_type', 'recruiter')
                                ->where('end_date', '<', now())
                                ->exists();
            } else {
                // Find parent recruiter (the "main recruiter" of this sub_main)
                $recruiterData = Recruiters::where('id', $recruiter->recruiter_of)->first();

                $isExpired = $recruiterData 
                    ? PurchasedSubscription::where('user_id', $recruiterData->id)
                                ->where('user_type', 'recruiter')
                                ->where('end_date', '<', now())
                                ->exists()
                    : true; // fallback -> treat as expired if no parent found
            }
        @endphp


    {{-- Case 1: Main recruiter (always show modal) --}}
    @if($role === 'main')
        <div id="subscriptionModal"
            class="fixed inset-0 bg-gray-200 bg-opacity-80 flex items-center justify-center z-50">
            <div class="bg-white w-full max-w-6xl p-6 rounded-lg shadow-lg relative">
                @if($isExpired)
                    <h3 class="text-xl font-semibold mb-6 text-red-600">
                        Your subscription has expired.
                    </h3>
                @else
                    <h3 class="text-xl font-semibold mb-6">
                        Available Subscription Plans
                    </h3>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach($subscriptions as $plan)
                        <div class="border rounded-lg p-4 shadow-sm text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 flex items-center justify-center bg-gray-100 rounded-full mb-2">
                                    <i class="fas fa-crown text-orange-500 text-xl"></i>
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
                            <form action="{{ route('subscription.payment') }}" method="POST">
                                @csrf
                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                <input type="hidden" name="user_id" value="{{ $recruiterId }}">
                                <input type="hidden" name="type" value="recruiter">
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white w-full py-2 rounded-md text-sm font-medium">
                                    Buy subscription
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    {{-- Case 2: Sub_main recruiter with expired subscription --}}
    @elseif($role === 'sub_recruiter' && $isExpired)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-70 flex items-center justify-center z-50">
            <div class="bg-white w-full max-w-lg p-8 rounded-2xl shadow-2xl relative transform transition-all duration-300 scale-95 hover:scale-100">
                <div class="flex justify-center mb-4">
                    <div class="bg-yellow-100 text-yellow-600 w-16 h-16 flex items-center justify-center rounded-full shadow-md">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01M4.93 4.93l14.14 14.14M19.07 4.93L4.93 19.07" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-center text-blue-600">⚠ Subscription Expired</h3>
                <div class="mt-4 text-center">
                    <p class="text-gray-700 text-lg">
                        Your subscription has <span class="font-semibold text-red-500">expired</span>.<br>
                        Please contact your <span class="font-semibold text-blue-600">Head Recruiter</span> for assistance.
                    </p>
                </div>
                <div class="my-6 border-t border-gray-200"></div>
            </div>
        </div>
    @endif

