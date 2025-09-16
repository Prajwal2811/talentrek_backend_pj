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
            $subscriptions = App\Models\SubscriptionPlan::where('user_type', 'trainer')->get();
            // print_r($subscriptions); die;
        @endphp
            <!-- Subscription Modal -->
        <div id="subscriptionModal"  class="fixed inset-0 bg-gray-200 bg-opacity-80 flex items-center justify-center z-50">
             <div class="bg-white w-full max-w-2xl p-6 rounded-lg shadow-lg relative">
                @include('admin.errors')
                <h3 class="text-xl font-semibold mb-6">Available Subscription Plans</h3>
                <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
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
                                    Buy subscription
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>