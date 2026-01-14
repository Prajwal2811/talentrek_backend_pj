
    <!-- Invoice Header -->
    <div class="flex justify-between items-center border-b pb-4 mb-4">
        <div>
            <h2 class="text-3xl font-bold tracking-wide">Invoice</h2>
            <p class="text-gray-600 mt-1">Invoice #: <span x-text="selectedPayment.track_id"></span></p>
            <p class="text-gray-600">Order ID: <span x-text="selectedPayment.order_id"></span></p>
            <p class="text-gray-600">Date: <span x-text="new Date(selectedPayment.paid_at).toLocaleDateString()"></span></p>
        </div>
        <div class="text-right">
            <img :src="'{{ $headerLogoUrl }}'" alt="Company Logo" class="h-14 w-auto">
        </div>
    </div>

    <!-- Billing Info -->
    <div class="grid grid-cols-2 gap-6 mb-6">
        <div>
            <h3 class="font-semibold text-gray-700 mb-1">Billed To:</h3>
            <p class="text-gray-600" x-text="selectedPayment.recruiter_name + ' (ID: ' + selectedPayment.user_id + ')'"></p>
        </div>
        <div>
            <h3 class="font-semibold text-gray-700 mb-1">Paid To:</h3>
            <p class="text-gray-600" x-text="selectedPayment.receiver_type"></p>
            <p class="text-gray-600" x-text="selectedPayment.payment_method"></p>
        </div>
    </div>

    <!-- Payment Details -->
    <table class="min-w-full text-sm border rounded-lg overflow-hidden mb-6">
        <thead class="bg-gray-100 text-gray-700">
            <tr>
                <th class="px-4 py-2 border">Description</th>
                <th class="px-4 py-2 border">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr class="border-b">
                <td class="px-4 py-2" x-text="'Payment For: ' + selectedPayment.payment_for"></td>
                <td class="px-4 py-2" x-text="selectedPayment.currency + ' ' + Number(selectedPayment.amount_paid).toFixed(2)"></td>
            </tr>
            <template x-if="selectedPayment.tax">
                <tr class="border-b">
                    <td class="px-4 py-2">Tax</td>
                    <td class="px-4 py-2" x-text="selectedPayment.currency + ' ' + Number(selectedPayment.tax).toFixed(2)"></td>
                </tr>
            </template>
            <template x-if="selectedPayment.applied_coupon">
                <tr class="border-b">
                    <td class="px-4 py-2">Coupon Discount</td>
                    <td class="px-4 py-2" x-text="'- ' + selectedPayment.currency + ' ' + Number(selectedPayment.applied_coupon).toFixed(2)"></td>
                </tr>
            </template>
            <tr class="font-bold bg-gray-50">
                <td class="px-4 py-2 text-right">Total</td>
                <td class="px-4 py-2"
                    x-text="selectedPayment.currency + ' ' + (Number(selectedPayment.amount_paid) + Number(selectedPayment.tax || 0) - Number(selectedPayment.applied_coupon || 0)).toFixed(2)">
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="flex justify-between items-center mt-6">
        <p class="text-gray-500 text-sm italic">Thank you for your payment!</p>
        <button @click="downloadInvoice(selectedPayment)"
                class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700 transition">
            Download PDF
        </button>
    </div>