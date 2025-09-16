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
                                <span>JustDo Coupons Management,</span>
                            </div>
                            <div class="col-xl-7 col-md-7 col-sm-12 text-md-right">

                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="header d-flex justify-content-between align-items-center">
                                    <h2>Coupons Management</h2>
                                    <a href="{{ route('admin.coupons.create') }}" class="btn btn-success">+ Add Coupon</a>
                                </div>
                                <div class="body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover dataTable js-exportable">
                                            <thead>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Code</th>
                                                    <th>Discount</th>
                                                    <th>Valid From</th>
                                                    <th>Valid To</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Code</th>
                                                    <th>Discount</th>
                                                    <th>Valid From</th>
                                                    <th>Valid To</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                @forelse($coupons as $index => $coupon)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $coupon->code }}</td>
                                                        <td>
                                                            @if($coupon->discount_type == 'percentage')
                                                                {{ $coupon->discount_value }}%
                                                            @else
                                                                ₹{{ number_format($coupon->discount_value, 2) }}
                                                            @endif
                                                        </td>
                                                        <td>{{ $coupon->valid_from ?? '-' }}</td>
                                                        <td>{{ $coupon->valid_to ?? '-' }}</td>
                                                        <td>
                                                            @if($coupon->is_active)
                                                                <span class="badge badge-success">Active</span>
                                                            @else
                                                                <span class="badge badge-danger">Inactive</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                                            
                                                            <!-- Delete Button (Opens Modal) -->
                                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteCouponModal-{{ $coupon->id }}">
                                                                Delete
                                                            </button>

                                                            <!-- Delete Confirmation Modal -->
                                                            <div class="modal fade" id="deleteCouponModal-{{ $coupon->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteCouponModalLabel-{{ $coupon->id }}" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header text-dark">
                                                                            <h5 class="modal-title" id="deleteCouponModalLabel-{{ $coupon->id }}">Confirm Delete</h5>
                                                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            Are you sure you want to delete coupon <strong>{{ $coupon->code }}</strong>?
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                            
                                                                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">No coupons found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <div class="mt-3">
                                            {{ $coupons->links() }} {{-- Laravel pagination --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        @include('admin.componants.footer')