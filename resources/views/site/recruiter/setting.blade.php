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

    @if($recruiterNeedsSubscription && auth()->user('recruiter')->role != "sub_recruiter")
        @include('site.recruiter.subscription.index')
    @endif
     @if($otherRecruiterSubscription && auth()->user('recruiter')->role != "sub_recruiter")
        @include('site.recruiter.subscription.add-other-recruiters')
    @endif

    <div class="page-wraper">
        <div class="flex h-screen" x-data="{ sidebarOpen: true }"
            x-init="$watch('sidebarOpen', () => feather.replace())">
            <!-- Sidebar -->
            @include('site.recruiter.componants.sidebar')
            <div class="flex-1 flex flex-col">
                @include('site.recruiter.componants.navbar')


                <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
                <main class="p-6 bg-gray-100 flex-1 overflow-y-auto"
                    x-data="{ activeSection: 'profile', activeSubTab: 'company' }">
                    <h2 class="text-2xl font-semibold mb-6">{{ langLabel('settings') }}</h2>

                    <div class="flex">
                        <!-- Sidebar -->
                        <aside class="w-60 bg-white p-4 border-r mt-4 shadow rounded-lg">
                            <ul class="space-y-2">
                                <li>
                                    <a href="#" @click.prevent="activeSection = 'profile'; activeSubTab = 'company'"
                                        :class="activeSection === 'profile' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'">Profile</a>

                                </li>
                                <li>
                                    <a href="#" @click.prevent="activeSection = 'notifications'"
                                        :class="activeSection === 'notifications' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'">{{ langLabel('notifications') }}</a>
                                </li>
                                <li>
                                    <a href="#"
                                        @click.prevent="activeSection = 'subscription'"
                                        :class="activeSection === 'subscription' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'">{{ langLabel('subscription') }}</a>
                                </li>
                                @if(auth()->user('recruiter')->role === 'main')
                                    <li>
                                        <a href="#" @click.prevent="activeSection = 'payment'"
                                            :class="activeSection === 'payment' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'">{{ langLabel('payment_history') }}</a>
                                    </li>
                                @endif
                                <li>
                                    <a href="#" @click.prevent="activeSection = 'privacy'"
                                        :class="activeSection === 'privacy' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'">{{ langLabel('privacy_policy') }}</a>
                                </li>

                                <li>
                                    <a href="#" @click.prevent="activeSection = 'delete'"
                                        :class="activeSection === 'delete' ? 'bg-red-100 text-red-700 rounded px-2 py-2 block' : 'block px-2 py-2 text-red-600 hover:bg-red-100 rounded'">{{ langLabel('delete_account') }}</a>
                                </li>
                            </ul>
                        </aside>

                        <!-- Main Content -->
                        <section class="flex-1 p-6">
                            <div class="bg-white rounded-lg shadow p-6">
                                <!-- Profile Section -->
                                @include('site.recruiter.setting.profile')


                                <!-- Notifications Section -->
                                <div x-show="activeSection === 'notifications'" x-transition class="bg-white p-6 ">
                                    <h3 class="text-xl font-semibold mb-4 border-b pb-2">
                                        {{ langLabel('notifications') }}</h3>

                                    <!-- Scrollable notification list -->
                                    <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                                        <!-- Notification Items -->
                                        @php $notifications = notificationUsersSent('recruiter'); @endphp
                                        @foreach($notifications as $notification)
                                            <div class="flex items-start gap-4 border-b pb-4">
                                                <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                                <a
                                                    href="{{ route('recruiter.notifications_details', ['id' => $notification->id, 'user_type' => 'recruiter']) }}">
                                                    <div>
                                                        <p><span class="font-semibold">{{ $notification->message }}</span>
                                                        </p>
                                                        <p class="text-sm text-gray-500 mt-1">
                                                            {{ date('d-m-y H:s A', strtotime($notification->created_at)) }}
                                                        </p>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                        @if($notifications->count() < 1)
                                            <a href=""
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ langLabel('view_records_found') }}</a>
                                        @endif
                                    </div>
                                </div>

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
                                


                                @if(auth()->user('recruiter')->role === 'main')
                                    @php
                                        $paymentHistory = App\Models\PaymentHistory::where('user_type', 'recruiter')
                                            ->where('user_id', auth()->user('recruiter')->id)
                                            ->get()
                                            ->map(function($payment) {
                                                $recruiter = App\Models\Recruiters::find($payment->user_id);
                                                $payment->recruiter_name = $recruiter ? $recruiter->name : 'N/A';
                                                return $payment;
                                            });

                                        // Get full URL for logo
                                        $headerLogo = App\Models\Setting::value('header_logo');
                                        $headerLogoUrl = $headerLogo ? asset($headerLogo) : '';
                                    @endphp

                                        <div x-data="{ activeSection: 'payment', openInvoice: null, selectedPayment: null }" class="bg-white p-6">
                                    {{-- <div x-show="activeSection === 'payment'" x-transition class="bg-white p-6"> --}}
                                        <h3 class="text-2xl font-semibold mb-6 border-b pb-3">Payment History</h3>
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full text-sm text-left border rounded-lg overflow-hidden shadow-sm">
                                                <thead class="bg-gray-100 text-gray-700">
                                                    <tr>
                                                        <th class="px-4 py-2 border">#</th>
                                                        <th class="px-4 py-2 border">Paid To</th>
                                                        <th class="px-4 py-2 border">Date</th>
                                                        <th class="px-4 py-2 border">Amount</th>
                                                        <th class="px-4 py-2 border">Status</th>
                                                        <th class="px-4 py-2 border">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-gray-700">
                                                    @foreach($paymentHistory as $index => $payment)
                                                        <tr class="border-b hover:bg-gray-50">
                                                            <td class="px-4 py-2">{{ $index + 1 }}</td>
                                                            <td class="px-4 py-2">{{ $payment->receiver_type }}</td>
                                                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($payment->paid_at)->format('d/m/Y H:i') }}</td>
                                                            <td class="px-4 py-2 font-medium">{{ $payment->currency }} {{ number_format($payment->amount_paid, 2) }}</td>
                                                            <td class="px-4 py-2">
                                                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                                                    {{ $payment->payment_status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                                    {{ ucfirst($payment->payment_status) }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-2">
                                                                <button @click="openInvoice = {{ $payment->id }}; selectedPayment = {{ $payment->toJson() }}"
                                                                    class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">
                                                                    View Invoice
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Invoice Modal -->
                                        <div x-show="openInvoice" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4">
                                            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto relative"
                                                @click.away="openInvoice = null">
                                                <button @click="openInvoice = null" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-3xl">&times;</button>
                                                @include('site.invoice.invoice-template')
                                            </div>
                                        </div>
                                    </div>

                                    <!-- html2pdf -->
                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
                                    <script>
                                    function downloadInvoice(payment) {
                                        const logoUrl = '{{ $headerLogoUrl }}';

                                        const tempDiv = document.createElement('div');
                                        tempDiv.style.padding = '20px';
                                        tempDiv.innerHTML = `
                                            <div style="font-family:sans-serif; max-width:700px; margin:auto;">
                                                <div style="display:flex; justify-content:space-between; border-bottom:1px solid #ddd; padding-bottom:10px;">
                                                    <div>
                                                        <h2 style="font-size:24px; margin:0;">Invoice</h2>
                                                        <p>Invoice #: ${payment.track_id}</p>
                                                        <p>Order ID: ${payment.order_id}</p>
                                                        <p>Date: ${new Date(payment.paid_at).toLocaleDateString()}</p>
                                                    </div>
                                                    <div>
                                                        <img src="${logoUrl}" style="height:50px;" />
                                                    </div>
                                                </div>

                                                <div style="display:flex; justify-content:space-between; margin-top:20px;">
                                                    <div>
                                                        <strong>Billed To:</strong>
                                                        <p>${payment.recruiter_name} (ID: ${payment.user_id})</p>
                                                    </div>
                                                    <div>
                                                        <strong>Paid To:</strong>
                                                        <p>${payment.receiver_type}</p>
                                                        <p>${payment.payment_method}</p>
                                                    </div>
                                                </div>

                                                <table style="width:100%; border-collapse:collapse; margin-top:20px;">
                                                    <thead>
                                                        <tr style="background:#f0f0f0;">
                                                            <th style="border:1px solid #ddd; padding:8px;">Description</th>
                                                            <th style="border:1px solid #ddd; padding:8px;">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td style="border:1px solid #ddd; padding:8px;">Payment For: ${payment.payment_for}</td>
                                                            <td style="border:1px solid #ddd; padding:8px;">${payment.currency} ${Number(payment.amount_paid).toFixed(2)}</td>
                                                        </tr>
                                                        ${payment.tax ? `<tr><td style="border:1px solid #ddd; padding:8px;">Tax</td><td style="border:1px solid #ddd; padding:8px;">${payment.currency} ${Number(payment.tax).toFixed(2)}</td></tr>` : ''}
                                                        ${payment.applied_coupon ? `<tr><td style="border:1px solid #ddd; padding:8px;">Coupon Discount</td><td style="border:1px solid #ddd; padding:8px;">- ${payment.currency} ${Number(payment.applied_coupon).toFixed(2)}</td></tr>` : ''}
                                                        <tr>
                                                            <td style="border:1px solid #ddd; padding:8px; text-align:right;"><strong>Total</strong></td>
                                                            <td style="border:1px solid #ddd; padding:8px;"><strong>${payment.currency} ${(Number(payment.amount_paid) + Number(payment.tax || 0) - Number(payment.applied_coupon || 0)).toFixed(2)}</strong></td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                                <p style="margin-top:20px; font-style:italic;">Thank you for your payment!</p>
                                            </div>
                                        `;

                                        const filename = `${payment.recruiter_name.replace(/\s+/g,'_')}_${new Date(payment.paid_at).toISOString().split('T')[0]}.pdf`;

                                        html2pdf().set({
                                            margin: 0.5,
                                            filename: filename,
                                            image: { type: 'jpeg', quality: 0.98 },
                                            html2canvas: { scale: 2 },
                                            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                                        }).from(tempDiv).save();
                                    }
                                    </script>
                                @endif



                                <!-- Privacy Policy Section -->
                                <div x-show="activeSection === 'privacy'" x-transition class="bg-white p-6">
                                    <h3 class="text-xl font-semibold mb-4">Privacy Policy</h3>

                                    <p class="mb-4">At XYZ Infotech, we are committed to protecting your privacy. This
                                        Privacy Policy outlines how we collect, use, and safeguard your personal
                                        information.</p>

                                    <h4 class="text-lg font-semibold mb-2">1. Information We Collect</h4>
                                    <ul class="list-disc list-inside mb-4 text-sm text-gray-700">
                                        <li>Personal information such as name, email address, phone number, and address.
                                        </li>
                                        <li>Usage data including pages visited, time spent, and actions taken on our
                                            platform.</li>
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
                                    <p class="mb-4 text-sm text-gray-700">We implement a variety of security measures to
                                        maintain the safety of your personal information. However, no method of
                                        transmission over the Internet is 100% secure.</p>

                                    <h4 class="text-lg font-semibold mb-2">4. Third-Party Services</h4>
                                    <p class="mb-4 text-sm text-gray-700">We may use third-party services (e.g.,
                                        analytics providers, payment gateways) that collect, monitor, and analyze data.
                                        These services have their own privacy policies.</p>

                                    <h4 class="text-lg font-semibold mb-2">5. Your Rights</h4>
                                    <ul class="list-disc list-inside mb-4 text-sm text-gray-700">
                                        <li>Access and update your personal information.</li>
                                        <li>Request deletion of your data.</li>
                                        <li>Opt out of marketing communications.</li>
                                    </ul>

                                    <h4 class="text-lg font-semibold mb-2">6. Changes to This Policy</h4>
                                    <p class="mb-4 text-sm text-gray-700">We may update this Privacy Policy
                                        periodically. We will notify you of any significant changes via email or on our
                                        platform.</p>

                                    <h4 class="text-lg font-semibold mb-2">7. Contact Us</h4>
                                    <p class="text-sm text-gray-700">If you have any questions or concerns about this
                                        policy, please contact us at <a href="mailto:support@xyzinfotech.com"
                                            class="text-blue-600 underline">support@xyzinfotech.com</a>.</p>
                                </div>


                                <!-- Log Out Section -->
                                <div x-show="activeSection === 'logout'" x-transition class="bg-white p-6">
                                    <h3 class="text-xl font-semibold mb-4">Log Out</h3>
                                    <p>Are you sure you want to log out?</p>
                                    <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Log
                                        Out</button>
                                </div>

                                <!-- Delete Account Section -->
                                <div x-show="activeSection === 'delete'" x-transition class="bg-white p-6">
                                    <h3 class="text-xl font-semibold mb-4 text-red-600">Delete Account</h3>
                                    <p>This action is irreversible. Are you sure you want to delete your account?</p>
                                    <!-- <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete Account</button> -->
                                    <form id="deleteAccountForm" action="{{ route('recruiter.destroy') }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" id="deleteAccountBtn"
                                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                            Delete Account
                                        </button>
                                    </form>

                                    <!-- SweetAlert2 CDN -->
                                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                    <script>
                                        document.getElementById('deleteAccountBtn').addEventListener('click', function () {
                                            Swal.fire({
                                                title: 'Are you sure?',
                                                text: "This action is irreversible. Delete your account?",
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#d33',
                                                cancelButtonColor: '#3085d6',
                                                confirmButtonText: 'Yes, delete it!',
                                                cancelButtonText: 'No',
                                                width: '350px', // Make it smaller
                                                padding: '1em',
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('deleteAccountForm').submit();
                                                }
                                            });
                                        });
                                    </script>
                                </div>
                            </div>
                        </section>
                    </div>
                </main>
            </div>
        </div>
        <!-- Feather Icons -->
        <script>
            feather.replace()
        </script>


    </div>




    @include('site.recruiter.componants.footer')