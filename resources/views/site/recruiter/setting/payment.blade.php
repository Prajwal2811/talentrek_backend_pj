@if(auth()->user('recruiter')->role === 'main')
    @php
        $paymentHistory = App\Models\PaymentHistory::where('user_type', 'recruiter')
            ->where('user_id', auth()->user('recruiter')->id)
            ->get()
            ->map(function($payment) {
                $payer = App\Models\Recruiters::find($payment->user_id);
                $receiver = $payment->receiver_id ? App\Models\Recruiters::find($payment->receiver_id) : null;

                $payment->payer_name = $payer ? $payer->name : 'N/A';
                $payment->receiver_name = $receiver ? $receiver->name : $payment->receiver_type ?? 'N/A';
                return $payment;
            });

        $headerLogo = App\Models\Setting::value('header_logo');
        $headerLogoUrl = $headerLogo ? asset($headerLogo) : '';
    @endphp

    <div x-data="{ openInvoice: null, selectedPayment: null }" class="bg-white p-6 rounded-lg shadow-md">
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
                            <td class="px-4 py-2">{{ $payment->receiver_name }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($payment->paid_at)->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2 font-medium">{{ $payment->currency }} {{ number_format($payment->amount_paid, 2) }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $payment->payment_status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($payment->payment_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <button 
                                    @click="openInvoice = {{ $payment->id }}; selectedPayment = {{ $payment->toJson() }}"
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
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl p-6 max-h-[85vh] overflow-y-auto relative"
                 @click.away="openInvoice = null">
                 
                <button @click="openInvoice = null" 
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
                                <span>ID: <span x-text="selectedPayment.user_id"></span></span>
                            </div>
                            <div class="text-right">
                                <strong>Receiver:</strong><br>
                                <span x-text="selectedPayment.receiver_name"></span><br>
                                <span x-text="selectedPayment.payment_method"></span>
                            </div>
                        </div>
                        <div class="border-t pt-2">
                            <p><strong>Payment For:</strong> <span x-text="selectedPayment.payment_for"></span></p>
                            <p><strong>Date:</strong> <span x-text="new Date(selectedPayment.paid_at).toLocaleString()"></span></p>
                            <p><strong>Transaction ID:</strong> <span x-text="selectedPayment.transaction_id || 'N/A'"></span></p>
                            <p><strong>Order ID:</strong> <span x-text="selectedPayment.order_id || 'N/A'"></span></p>
                            <p><strong>Tax Percentage:</strong> <span x-text="selectedPayment.tax_percentage ? selectedPayment.tax_percentage + '%' : '0%'"></span></p>
                            <p><strong>Taxed Amount:</strong> <span x-text="selectedPayment.taxed_amount ? selectedPayment.currency + ' ' + Number(selectedPayment.taxed_amount).toFixed(2) : selectedPayment.currency + ' 0.00'"></span></p>
                            <p x-show="selectedPayment.applied_coupon"><strong>Coupon Applied:</strong> <span x-text="selectedPayment.applied_coupon"></span></p>
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
                                <td class="border px-3 py-2 text-right" x-text="selectedPayment.currency + ' ' + Number(selectedPayment.amount_paid).toFixed(2)"></td>
                            </tr>
                            <template x-if="selectedPayment.taxed_amount">
                                <tr>
                                    <td class="border px-3 py-2">Tax</td>
                                    <td class="border px-3 py-2 text-right" x-text="selectedPayment.currency + ' ' + Number(selectedPayment.taxed_amount).toFixed(2)"></td>
                                </tr>
                            </template>
                            <template x-if="selectedPayment.applied_coupon">
                                <tr>
                                    <td class="border px-3 py-2">Coupon Discount</td>
                                    <td class="border px-3 py-2 text-right text-red-600" x-text="'- ' + selectedPayment.currency + ' ' + Number(selectedPayment.applied_coupon).toFixed(2)"></td>
                                </tr>
                            </template>
                            <tr class="font-semibold bg-gray-50">
                                <td class="border px-3 py-2 text-right">Total</td>
                                <td class="border px-3 py-2 text-right"
                                    x-text="selectedPayment.currency + ' ' + (Number(selectedPayment.amount_paid) + Number(selectedPayment.taxed_amount || 0) - Number(selectedPayment.applied_coupon || 0)).toFixed(2)">
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
                            <p>Order ID: ${payment.order_id || 'N/A'}</p>
                            <p>Date: ${new Date(payment.paid_at).toLocaleString()}</p>
                            <p>Transaction ID: ${payment.transaction_id || 'N/A'}</p>
                        </div>
                        <div>
                            <img src="${logoUrl}" style="height:50px;" />
                        </div>
                    </div>

                    <div style="display:flex; justify-content:space-between; margin-top:20px;">
                        <div>
                            <strong>Payer:</strong>
                            <p>${payment.payer_name} (ID: ${payment.user_id})</p>
                        </div>
                        <div>
                            <strong>Receiver:</strong>
                            <p>${payment.receiver_name}</p>
                            <p>${payment.payment_method}</p>
                        </div>
                    </div>

                    <p><strong>Payment For:</strong> ${payment.payment_for}</p>
                    <p><strong>Tax Percentage:</strong> ${payment.tax_percentage || 0}%</p>

                    <table style="width:100%; border-collapse:collapse; margin-top:20px;">
                        <thead>
                            <tr style="background:#f0f0f0;">
                                <th style="border:1px solid #ddd; padding:8px;">Description</th>
                                <th style="border:1px solid #ddd; padding:8px;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="border:1px solid #ddd; padding:8px;">Amount Paid</td>
                                <td style="border:1px solid #ddd; padding:8px;">${payment.currency} ${Number(payment.amount_paid).toFixed(2)}</td>
                            </tr>
                            ${payment.taxed_amount ? `<tr><td style="border:1px solid #ddd; padding:8px;">Tax</td><td style="border:1px solid #ddd; padding:8px;">${payment.currency} ${Number(payment.taxed_amount).toFixed(2)}</td></tr>` : ''}
                            ${payment.applied_coupon ? `<tr><td style="border:1px solid #ddd; padding:8px;">Coupon Discount</td><td style="border:1px solid #ddd; padding:8px;">- ${payment.currency} ${Number(payment.applied_coupon).toFixed(2)}</td></tr>` : ''}
                            <tr>
                                <td style="border:1px solid #ddd; padding:8px; text-align:right;"><strong>Total</strong></td>
                                <td style="border:1px solid #ddd; padding:8px;"><strong>${payment.currency} ${(Number(payment.amount_paid) + Number(payment.taxed_amount || 0) - Number(payment.applied_coupon || 0)).toFixed(2)}</strong></td>
                            </tr>
                        </tbody>
                    </table>

                    <p style="margin-top:20px; font-style:italic;">Thank you for your payment!</p>
                </div>
            `;
            const filename = `${payment.payer_name.replace(/\s+/g,'_')}_${new Date(payment.paid_at).toISOString().split('T')[0]}.pdf`;
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
