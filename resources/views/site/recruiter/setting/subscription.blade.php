<div x-show="activeSection === 'subscription'" x-transition class="bg-white p-6">
    <h3 class="text-xl font-semibold mb-4 border-b pb-2">Subscription</h3>
    @php
        $userId = auth()->user('recruiter')->id;
        $userRole = auth()->user('recruiter')->role;
        $companyId = auth()->user('recruiter')->company_id;
        // Fetch available plans for this user type
        $subscriptions = App\Models\SubscriptionPlan::where('user_type', 'recruiter')->get();
        // echo "<pre>"; print_r($subscriptions); 
        // Fetch purchased subscriptions for current user
        $purchasedSubscriptions = App\Models\PurchasedSubscription::select('subscription_plans.*', 'purchased_subscriptions.*')
            ->join('subscription_plans', 'purchased_subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->where('subscription_plans.user_type', 'recruiter')
            // ->where('purchased_subscriptions.user_id', $userId)
            ->where('purchased_subscriptions.company_id', $companyId)
            ->orderBy('purchased_subscriptions.created_at', 'desc')
            ->get();
        // echo "<pre>"; print_r($subscriptions); 


        $showPlansModal = false;

        if ($purchasedSubscriptions->count() > 0) {
            $latest = $purchasedSubscriptions->first();
            $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($latest->end_date), false);

            if ($daysLeft > 0 && $daysLeft <= 30) {
                $showPlansModal = true;
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

    @if(auth()->user('recruiter')->role === 'main')
        <!-- Subscription Card -->
        <div
            class="bg-gray-100 p-6 rounded-md flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h4 class="text-lg font-semibold mb-1">Subscription Plans</h4>
                <p class="text-gray-600 text-sm">Purchase subscription to get access to
                    premium features</p>
            </div>
            <button
                onclick="document.getElementById('plansModal').classList.remove('hidden')"
                class="mt-4 md:mt-0 bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                View Plans
            </button>
        </div>

        <!-- Plans Modal -->
        <div id="plansModal"
            class="fixed inset-0 bg-gray-200 bg-opacity-80 flex items-center justify-center z-50 hidden">
            <div class="bg-white w-full max-w-6xl p-6 rounded-lg shadow-lg relative">
                <button
                    onclick="document.getElementById('plansModal').classList.add('hidden')"
                    class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-lg">✕</button>

                <h3 class="text-xl font-semibold mb-6">Available Subscription Plans</h3>

                <div class="grid grid-cols-3 sm:grid-cols-3 lg:grid-cols-3 gap-4">
                    @foreach($subscriptions as $plan)
                        <div class="border rounded-lg p-4 shadow-sm text-center">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-12 h-12 flex items-center justify-center bg-gray-100 rounded-full mb-2">
                                    <i class="fas fa-crown text-blue-500 text-xl"></i>
                                </div>

                                <h4 class="font-semibold">{{ $plan->title }}</h4>
                                <p class="font-bold text-lg mt-1">AED {{ $plan->price }}</p>
                            </div>
                            <p class="text-sm text-gray-500 mt-2 mb-3">{{ $plan->description }}
                            </p>
                            <ul class="list-disc list-outside pl-5 text-sm text-gray-700 mb-4">
                                @foreach(is_array($plan->features) ? $plan->features : explode(',', $plan->features) as $feature)
                                    <li>{{ trim($feature) }}</li>
                                @endforeach
                            </ul>

                            <!-- Direct form submit instead of JS -->
                            <form action="{{ route('subscription.payment') }}" method="POST">
                                @csrf
                                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                <input type="hidden" name="user_id"
                                    value="{{ auth()->user('recruiter')->id }}">
                                <input type="hidden" name="type" value="recruiter">
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
    @endif
    <h4 class="text-lg font-semibold mb-3">Subscription History</h4>
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
                    <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                        <td class="px-4 py-3">{{ $index + 1 }}.</td>
                        <td class="px-4 py-3">{{ $subscription->title }}</td>
                        <td class="px-4 py-3">{{ $subscription->duration_days }}
                            {{ Str::plural('days', $subscription->duration_days) }}</td>
                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($subscription->start_date)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($subscription->end_date)->format('d/m/Y') }}
                        </td>
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

            fetch("{{ route('recruiter.subscription.payment') }}", {
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