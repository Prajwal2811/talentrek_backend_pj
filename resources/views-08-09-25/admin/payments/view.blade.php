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
                    <div class="block-header">
                        <div class="row clearfix">
                            <div class="col-xl-5 col-md-5 col-sm-12">
                                <h1>Hi, {{  Auth()->user()->name }}!</h1>
                                <span>JustDo Payment Management,</span>
                            </div>
                            
                        </div>
                    </div>
                    
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Payment Management</h2>
                                </div>
                                <div class="body">
                                    <form>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Payment ID</label>
                                                @php
                                                    $year = now()->year;
                                                    $serialNumber = str_pad($payment->id, 3, '0', STR_PAD_LEFT);
                                                    $trackingNumber = 'TTPAY-' . $year . $serialNumber;
                                                @endphp
                                                <input type="text" class="form-control" value="{{ $trackingNumber }}" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Transaction ID</label>
                                                <input type="text" class="form-control" value="{{ $payment->transaction_id ?? 'N/A' }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Jobseeker Name</label>
                                                <input type="text" class="form-control" value="{{ $payment->jobseeker_name }}" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Jobseeker Email</label>
                                                <input type="email" class="form-control" value="{{ $payment->jobseeker_email }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Amount</label>
                                                <input type="text" class="form-control" value="₹{{ number_format($payment->amount_paid, 2) }}" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Payment Method</label>
                                                <input type="text" class="form-control" value="{{ ucfirst($payment->payment_method) }}" readonly>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Status</label>
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'warning',
                                                        'completed' => 'success',
                                                        'failed' => 'danger',
                                                        'refunded' => 'info', // or 'secondary' if you prefer
                                                    ];

                                                    $status = $payment->payment_status;
                                                    $bgClass = $statusColors[$status] ?? 'secondary';
                                                @endphp

                                                <input type="text" 
                                                    class="form-control bg-{{ $bgClass }} text-white" 
                                                    value="{{ ucfirst($status) }}" 
                                                    readonly>

                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Paid On</label>
                                                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y, h:i A') }}" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <h3 class="form-label heading text-bold">Training Info</h3>
                                                    <div class="row mb-3 mt-1">
                                                        <div class="col-md-12">
                                                            <label class="form-label">Training Course</label>
                                                            <input type="text" class="form-control" value="{{ $payment->training_title }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>  
                                        <a href="{{ route('admin.payments') }}" class="btn btn-secondary mt-3">Back to Payments</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>



    @include('admin.componants.footer')