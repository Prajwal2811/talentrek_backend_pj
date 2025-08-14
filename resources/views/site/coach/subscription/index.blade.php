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
            // Get mentor standard subscription plans
            $subscriptions = App\Models\SubscriptionPlan::where('user_type', 'coach')
                ->where('slug', 'coach_standard')
                ->get();
        @endphp

        <!-- Subscription Modal -->
        <div id="subscriptionModal"
            class="fixed inset-0 bg-gray-200 bg-opacity-80 flex items-center justify-center z-50">
            <div class="bg-white w-full max-w-2xl p-6 rounded-lg shadow-lg relative">
                <h3 class="text-xl font-semibold mb-6">Available Subscription Plans</h3>
                <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
                    @foreach($subscriptions as $plan)
                        <div class="border rounded-lg p-4 shadow-sm text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-gray-300 rounded-full mb-2"></div>
                                <h4 class="font-semibold">{{ $plan->title }}</h4>
                                <p class="font-bold text-lg mt-1">AED {{ $plan->price }}</p>
                            </div>
                            <p class="text-sm text-gray-500 mt-2 mb-3">{{ $plan->description }}</p>
                            <ul class="list-disc list-outside pl-5 text-sm text-gray-700 mb-4">
                                @foreach(is_array($plan->features) ? $plan->features : explode(',', $plan->features) as $feature)
                                    <li>{{ trim($feature) }}</li>
                                @endforeach
                            </ul>
                            <button type="button"
                                class="bg-orange-500 hover:bg-orange-600 text-white w-full py-2 rounded-md text-sm font-medium buy-subscription-btn"
                                data-plan-id="{{ $plan->id }}">
                                Buy subscription
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Payment Modal -->
        <div id="paymentModal" class="fixed inset-0 bg-gray-200 bg-opacity-80 z-50 hidden flex items-center justify-center">
            <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg relative">
                <h3 class="text-xl font-semibold mb-4 text-center">Payment</h3>
                <p class="mb-6 text-gray-600 text-center">Enter your card details to continue</p>

                <form id="paymentForm">
                    @csrf
                    <input type="hidden" name="plan_id" id="selectedPlanId">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Card Number</label>
                        <input type="text" name="card_number" value="4242424242424242"
                            class="w-full border border-gray-300 rounded-md px-4 py-2">
                    </div>

                    <div class="mb-4 flex space-x-2">
                        <div class="w-1/2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expiry</label>
                            <input type="text" name="expiry" value="12/30"
                                class="w-full border border-gray-300 rounded-md px-4 py-2">
                        </div>
                        <div class="w-1/2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                            <input type="text" name="cvv" value="123"
                                class="w-full border border-gray-300 rounded-md px-4 py-2">
                        </div>
                    </div>
                    <button type="submit"
                        class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 transition">
                        Pay Now
                    </button>
                </form>
                <div id="paymentMessage" class="mt-3 text-center text-sm"></div>
                <button onclick="closePaymentModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl font-bold">
                    ×
                </button>
            </div>
        </div>
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
            // Open payment modal on Buy Subscription click
            document.querySelectorAll('.buy-subscription-btn').forEach(button => {
                button.addEventListener('click', function () {
                    openPaymentModal(this.getAttribute('data-plan-id'));
                });
            });

            // Close modal on Escape
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closePaymentModal();
            });

            // Handle AJAX payment
            document.getElementById('paymentForm').addEventListener('submit', function (e) {
                e.preventDefault();

                let formData = new FormData(this);
                let messageBox = document.getElementById('paymentMessage');
                messageBox.textContent = "";

                fetch("{{ route('coach.subscription.payment') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: formData
                })
                .then(async response => {
                    let data = await response.json();
                    if (!response.ok) throw data;
                    return data;
                })
                .then(data => {
                    closePaymentModal();

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        html: `<b>${data.message}</b>`,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                })
                .catch(error => {
                    let errorMsg = error.message || "Something went wrong!";
                    if (error.errors) {
                        errorMsg = Object.values(error.errors).flat().join('<br>');
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        html: `<b>${errorMsg}</b>`,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Close'
                    });
                });
            });
        });
        </script>
