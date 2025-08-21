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
                                                    <th>Actions</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                @foreach($payments as $payment)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $payment->jobseeker_name }}</td>
                                                        <td>{{ $payment->payment_method }}</td>
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y, h:i A') }}
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

                                                        <td>
                                                            <a href="{{ route('admin.payment.view', ['id' => $payment->payment_id]) }}" class="btn btn-sm btn-primary">View Details</a>
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