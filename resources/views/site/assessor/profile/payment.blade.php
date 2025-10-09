@php
                                    $assessor = auth()->user('assessor');

                                    $paymentHistory = App\Models\PaymentHistory::where('user_type', 'assessor')
                                        ->where('user_id', $assessor->id)
                                        ->get()
                                        ->map(function ($payment) use ($assessor) {
                                            $payment->payer_name = $assessor->name; // Payer is Trainer
                                            $payment->receiver_name = 'Talentrek'; // Receiver is always Talentrek
                                            return $payment;
                                        });

                                    $headerLogo = App\Models\Setting::value('header_logo');
                                    $headerLogoUrl = $headerLogo ? asset($headerLogo) : '';
                                @endphp

                                <div x-data="{ activeSection: 'payment', openInvoice: false, selectedPayment: null }">
                                    <!-- Payment History Table -->
                                    <div x-show="activeSection === 'payment'" x-transition class="bg-white p-6">
                                        <h3 class="text-xl font-semibold mb-4 border-b pb-2">Payment History</h3>
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full text-sm text-left border rounded-lg overflow-hidden shadow-sm">
                                                <thead class="bg-gray-100 text-gray-700">
                                                    <tr>
                                                        <th class="px-3 py-2 border">#</th>
                                                        <th class="px-3 py-2 border">Payer</th>
                                                        <th class="px-3 py-2 border">Date</th>
                                                        <th class="px-3 py-2 border">Amount</th>
                                                        <th class="px-3 py-2 border">Status</th>
                                                        <th class="px-3 py-2 border">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-gray-700">
                                                    @foreach($paymentHistory as $index => $payment)
                                                        <tr class="border-b hover:bg-gray-50">
                                                            <td class="px-3 py-2">{{ $index + 1 }}</td>
                                                            <td class="px-3 py-2">{{ $payment->payer_name }}</td>
                                                            <td class="px-3 py-2">{{ \Carbon\Carbon::parse($payment->paid_at)->format('d/m/Y H:i') }}</td>
                                                            <td class="px-3 py-2 font-medium">{{ $payment->currency }}
                                                                {{ number_format($payment->amount_paid, 2) }}</td>
                                                            <td class="px-3 py-2">
                                                                <span
                                                                    class="px-2 py-1 rounded-full text-xs font-semibold
                                                                                                        {{ $payment->payment_status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                                    {{ ucfirst($payment->payment_status) }}
                                                                </span>
                                                            </td>
                                                            <td class="px-3 py-2">
                                                                <button
                                                                    @click="openInvoice = true; selectedPayment = {{ Illuminate\Support\Js::from($payment) }};"
                                                                    class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-xs">
                                                                    View Invoice
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Invoice Modal -->
                                        <div x-show="openInvoice" x-cloak
                                            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4">
                                            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl p-6 max-h-[85vh] overflow-y-auto relative"
                                                @click.away="openInvoice = false">

                                                <button @click="openInvoice = false"
                                                    class="absolute top-3 right-4 text-gray-500 hover:text-gray-800 text-3xl">&times;</button>

                                                <div x-show="selectedPayment" x-cloak>
                                                    <div class="text-center border-b pb-3 mb-3">
                                                        <img src="{{ $headerLogoUrl }}" alt="Logo" class="h-10 mx-auto mb-2">
                                                        <h2 class="text-lg font-bold">Payment Invoice</h2>
                                                        <p class="text-gray-600 text-sm">Invoice #: <span x-text="selectedPayment.track_id"></span></p>
                                                    </div>

                                                    <div class="text-sm space-y-2 mb-4">
                                                        <div class="flex justify-between">
                                                            <div>
                                                                <strong>Payer:</strong><br>
                                                                <span x-text="selectedPayment.payer_name"></span><br>
                                                                <span>ID: <span x-text="selectedPayment.user_id || 'N/A'"></span></span>
                                                            </div>
                                                            <div class="text-right">
                                                                <strong>Receiver:</strong><br>
                                                                <span>Talentrek</span><br>
                                                                <span x-text="selectedPayment.payment_method"></span>
                                                            </div>
                                                        </div>
                                                        <div class="border-t pt-2">
                                                            <p><strong>Payment For:</strong> <span x-text="selectedPayment.payment_for"></span></p>
                                                            <p><strong>Date:</strong> <span
                                                                    x-text="new Date(selectedPayment.paid_at).toLocaleString()"></span></p>
                                                            <p><strong>Transaction ID:</strong> <span
                                                                    x-text="selectedPayment.transaction_id || 'N/A'"></span></p>
                                                            <p><strong>Order ID:</strong> <span x-text="selectedPayment.order_id || 'N/A'"></span></p>
                                                            <p><strong>Tax Percentage:</strong> <span
                                                                    x-text="selectedPayment.tax_percentage ? selectedPayment.tax_percentage + '%' : '0%'"></span>
                                                            </p>
                                                            <p><strong>Taxed Amount:</strong> <span
                                                                    x-text="selectedPayment.taxed_amount ? selectedPayment.currency + ' ' + Number(selectedPayment.taxed_amount).toFixed(2) : selectedPayment.currency + ' 0.00'"></span>
                                                            </p>
                                                            <p x-show="selectedPayment.applied_coupon"><strong>Coupon Applied:</strong> <span
                                                                    x-text="selectedPayment.applied_coupon"></span></p>
                                                        </div>
                                                    </div>

                                                    <table class="w-full text-sm border border-gray-200">
                                                        <thead>
                                                            <tr class="bg-gray-100 text-gray-700">
                                                                <th class="border px-3 py-2 text-left">Description</th>
                                                                <th class="border px-3 py-2 text-right">Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="border px-3 py-2">Amount Paid</td>
                                                                <td class="border px-3 py-2 text-right"
                                                                    x-text="selectedPayment.currency + ' ' + Number(selectedPayment.amount_paid).toFixed(2)">
                                                                </td>
                                                            </tr>
                                                            <template x-if="selectedPayment.taxed_amount">
                                                                <tr>
                                                                    <td class="border px-3 py-2">Tax</td>
                                                                    <td class="border px-3 py-2 text-right"
                                                                        x-text="selectedPayment.currency + ' ' + Number(selectedPayment.taxed_amount).toFixed(2)">
                                                                    </td>
                                                                </tr>
                                                            </template>
                                                            <template x-if="selectedPayment.applied_coupon">
                                                                <tr>
                                                                    <td class="border px-3 py-2">Coupon Discount</td>
                                                                    <td class="border px-3 py-2 text-right text-red-600"
                                                                        x-text="'- ' + selectedPayment.currency + ' ' + Number(selectedPayment.applied_coupon).toFixed(2)">
                                                                    </td>
                                                                </tr>
                                                            </template>
                                                            <tr class="font-semibold bg-gray-50">
                                                                <td class="border px-3 py-2 text-right">Total</td>
                                                                <td class="border px-3 py-2 text-right"
                                                                    x-text="selectedPayment.currency + ' ' + (Number(selectedPayment.amount_paid) - Number(selectedPayment.applied_coupon || 0)).toFixed(2)">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    <p class="text-gray-600 text-sm italic mt-4 text-center">Thank you for your payment!</p>

                                                    <div class="mt-4 text-center">
                                                        <button @click="downloadInvoice(selectedPayment)"
                                                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                                            Download Invoice PDF
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>