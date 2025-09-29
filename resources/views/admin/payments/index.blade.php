@include('admin.componants.header')

<body data-theme="light">
    <div id="body" class="theme-cyan">
        <div class="themesetting">
        </div>
        <div class="overlay"></div>
        <div id="wrapper">
            @include('admin.componants.navbar')
            @include('admin.componants.sidebar')
            <div id="main-content">
                <div class="container-fluid">
                    @include('admin.errors')
                    <div class="block-header">
                        <div class="row clearfix">
                            <div class="col-xl-5 col-md-5 col-sm-12">
                                <h1>Hi, {{  Auth()->user()->name }}!</h1>
                                <span>JustDo Admin Management,</span>
                            </div>
                            <div class="col-xl-7 col-md-7 col-sm-12 text-md-right">

                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Admin Management</h2>
                                </div>
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover dataTable js-exportable">
                                            <thead>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Full Name</th>
                                                    <th>Payment Method</th>
                                                    <th>Payment Date</th>
                                                    <th>Payment Status</th>
                                                    <th>Invoice</th> <!-- New column -->
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Full Name</th>
                                                    <th>Payment Method</th>
                                                    <th>Payment Date</th>
                                                    <th>Payment Status</th>
                                                    <th>Invoice</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                @foreach($payments as $payment)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $payment->user_name ?? 'N/A' }}</td>
                                                        <td>{{ $payment->payment_method ?? 'N/A' }}</td>
                                                        <td>
                                                            {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d M Y, h:i A') : 'N/A' }}
                                                        </td>
                                                        <td>
                                                            @php
                                                                $status = strtolower($payment->payment_status);
                                                                $badgeClass = match($status) {
                                                                    'completed' => 'success',
                                                                    'pending' => 'warning',
                                                                    'failed' => 'danger',
                                                                    'refunded' => 'info',
                                                                    default => 'secondary'
                                                                };
                                                            @endphp
                                                            <span class="badge bg-{{ $badgeClass }} text-light">
                                                                {{ ucfirst($status) }}
                                                            </span>
                                                        </td>
                                                    @php
                                                        $headerLogo = App\Models\Setting::value('header_logo');
                                                    @endphp  
                                                    <td>
                                                        <!-- Invoice Button -->
                                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#invoiceModal{{ $payment->id }}">
                                                            View Invoice
                                                        </button>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="invoiceModal{{ $payment->id }}" tabindex="-1" aria-labelledby="invoiceModalLabel{{ $payment->id }}" aria-hidden="true">
                                                            <div class="modal-dialog modal-xl modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header border-0">
                                                                        <h5 class="modal-title" id="invoiceModalLabel{{ $payment->id }}">Invoice #{{ str_pad($payment->id, 3, '0', STR_PAD_LEFT) }}</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>

                                                                    <div class="modal-body p-5" style="background-color: #f8f9fa;" id="invoiceContent{{ $payment->id }}">
                                                                        <!-- Header -->
                                                                        <div class="row mb-4 align-items-center">
                                                                            <div class="col-md-6">
                                                                                <img src="{{ $headerLogo }}" alt="Talentrek Logo" style="height: 60px; width: auto;">
                                                                                <p class="mt-2 mb-0">
                                                                                    <strong>Talentrek Pvt. Ltd.</strong><br>
                                                                                    123 Business Street, Mumbai, India<br>
                                                                                    support@talentrek.com
                                                                                </p>
                                                                            </div>
                                                                            <div class="col-md-6 text-end">
                                                                                <h4 class="fw-bold">Invoice</h4>
                                                                                <p class="mb-1">
                                                                                    <strong>#{{ str_pad($payment->id, 3, '0', STR_PAD_LEFT) }}</strong><br>
                                                                                    Date: {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d M Y') : 'N/A' }}<br>
                                                                                    Status: 
                                                                                    @php
                                                                                        $statusColors = [
                                                                                            'pending' => 'warning',
                                                                                            'completed' => 'success',
                                                                                            'failed' => 'danger',
                                                                                            'refunded' => 'info',
                                                                                        ];
                                                                                        $statusClass = $statusColors[$payment->payment_status] ?? 'secondary';
                                                                                    @endphp
                                                                                    <span class="badge bg-{{ $statusClass }} text-light">
                                                                                        {{ ucfirst($payment->payment_status) }}
                                                                                    </span>
                                                                                </p>
                                                                            </div>
                                                                        </div>

                                                                        <hr>

                                                                        <!-- Billing & Payment Details -->
                                                                        <div class="row mb-4">
                                                                            <div class="col-md-6">
                                                                                <h6 class="fw-bold text-muted">Billed To:</h6>
                                                                                <p class="mb-0">{{ $payment->user_name ?? 'N/A' }}</p>
                                                                                <p>{{ $payment->user_email ?? 'N/A' }}</p>
                                                                            </div>
                                                                            <div class="col-md-6 text-end">
                                                                                <h6 class="fw-bold text-muted">Payment Details:</h6>
                                                                                <p class="mb-0">Payment Method: {{ ucfirst($payment->payment_method ?? 'N/A') }}</p>
                                                                                <p class="mb-0">Transaction ID: {{ $payment->transaction_id ?? 'N/A' }}</p>
                                                                                <p>Order ID: {{ $payment->order_id ?? 'N/A' }}</p>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Invoice Table -->
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <table class="table table-bordered table-striped">
                                                                                    <thead class="table-light">
                                                                                        <tr>
                                                                                            <th>#</th>
                                                                                            <th>Description</th>
                                                                                            <th class="text-end">Amount (₹)</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td>1</td>
                                                                                            <td>
                                                                                                @switch($payment->payment_for)
                                                                                                    @case('training')
                                                                                                        Training Course: {{ $payment->material->title ?? 'N/A' }}
                                                                                                        @break
                                                                                                    @case('booking_slot')
                                                                                                        Booking Slot
                                                                                                        @break
                                                                                                    @case('subscription')
                                                                                                        Subscription
                                                                                                        @break
                                                                                                    @default
                                                                                                        Payment
                                                                                                @endswitch
                                                                                            </td>
                                                                                            <td class="text-end">{{ number_format($payment->amount_paid, 2) }}</td>
                                                                                        </tr>

                                                                                        @if($payment->tax && $payment->tax > 0)
                                                                                        <tr>
                                                                                            <td colspan="2" class="text-end"><strong>Tax</strong></td>
                                                                                            <td class="text-end">{{ number_format($payment->tax, 2) }}</td>
                                                                                        </tr>
                                                                                        @endif

                                                                                        <tr>
                                                                                            <td colspan="2" class="text-end"><strong>Discount</strong></td>
                                                                                            <td class="text-end">
                                                                                                @if($payment->applied_coupon)
                                                                                                    -₹{{ number_format($payment->discount ?? 0, 2) }} (Coupon: {{ $payment->applied_coupon }})
                                                                                                @else
                                                                                                    -
                                                                                                @endif
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr class="table-success">
                                                                                            <td colspan="2" class="text-end fw-bold">Total</td>
                                                                                            <td class="text-end fw-bold">
                                                                                                ₹{{ number_format(($payment->amount_paid + ($payment->tax ?? 0)) - ($payment->discount ?? 0), 2) }}
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Footer -->
                                                                        <div class="row mt-4">
                                                                            <div class="col-12 text-center">
                                                                                <p class="fw-bold mb-0">Thank you for your payment!</p>
                                                                                <small class="text-muted">This is a computer-generated invoice and does not require a signature.</small>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="modal-footer border-0">
                                                                        <button class="btn btn-success" onclick="downloadInvoice('{{ $payment->id }}', '{{ $payment->user_name ?? 'User' }}', '{{ $payment->payment_for }}')">
                                                                            <i class="bi bi-download"></i> Download PDF
                                                                        </button>
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
                                                    <script>
                                                    function downloadInvoice(paymentId, userName, paymentFor) {
                                                        const element = document.getElementById('invoiceContent' + paymentId);

                                                        // Clean file name
                                                        const cleanName = userName.replace(/[^a-zA-Z0-9]/g, '_');
                                                        const cleanPaymentFor = paymentFor.replace(/[^a-zA-Z0-9]/g, '_');

                                                        const filename = `Invoice_${cleanName}_${cleanPaymentFor}_#${paymentId}.pdf`;

                                                        var opt = {
                                                                margin:       0.5,
                                                                filename:     filename,
                                                                image:        { type: 'jpeg', quality: 0.98 },
                                                                html2canvas:  { scale: 3, useCORS: true }, // enable CORS
                                                                jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
                                                            };

                                                        html2pdf().set(opt).from(element).save();
                                                    }
                                                    </script>




                                                        <td>
                                                            <a href="{{ route('admin.payment.view', ['id' => $payment->id]) }}" class="btn btn-sm btn-primary">View Details</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.componants.footer')