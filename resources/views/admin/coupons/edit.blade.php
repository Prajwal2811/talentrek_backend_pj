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
                                <span>JustDo Admin Edit,</span>
                            </div>
                            
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h2>Edit Coupon</h2>
                                </div>
                                <div class="body">
                                    <form id="coupon-form" method="POST" action="{{ route('admin.coupons.update', $coupon->id) }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="row">
                                            <!-- Coupon Code -->
                                            <div class="form-group c_form_group col-md-6">
                                                <label>Coupon Code</label>
                                                <input type="text" class="form-control" name="code"
                                                    value="{{ old('code', $coupon->code) }}" placeholder="Enter coupon code" required>
                                                @error('code')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <!-- Discount Type -->
                                            <div class="form-group c_form_group col-md-6">
                                                <label>Discount Type</label>
                                                <select class="form-control" name="discount_type" required>
                                                    <option value="percentage" {{ old('discount_type', $coupon->discount_type) == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                                    <option value="fixed" {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}>Fixed (₹)</option>
                                                </select>
                                                @error('discount_type')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- Discount Value -->
                                            <div class="form-group c_form_group col-md-6">
                                                <label>Discount Value</label>
                                                <input type="number" step="0.01" class="form-control" name="discount_value"
                                                    value="{{ old('discount_value', $coupon->discount_value) }}" placeholder="Enter discount value" required>
                                                @error('discount_value')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <!-- Active Status -->
                                            <div class="form-group c_form_group col-md-6">
                                                <label>Status</label>
                                                <select class="form-control" name="is_active">
                                                    <option value="1" {{ old('is_active', $coupon->is_active) ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ old('is_active', $coupon->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                                @error('is_active')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- Valid From -->
                                            <div class="form-group c_form_group col-md-6">
                                                <label>Valid From</label>
                                                <input type="date" class="form-control" name="valid_from"
                                                    value="{{ old('valid_from', $coupon->valid_from) }}">
                                                @error('valid_from')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>

                                            <!-- Valid To -->
                                            <div class="form-group c_form_group col-md-6">
                                                <label>Valid To</label>
                                                <input type="date" class="form-control" name="valid_to"
                                                    value="{{ old('valid_to', $coupon->valid_to) }}">
                                                @error('valid_to')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- Coupon Visibility -->
                                            <div class="form-group c_form_group col-md-6">
                                                <label>
                                                    <input type="checkbox" name="is_private" value="yes" {{ old('is_private', $coupon->is_private) == 'yes' ? 'checked' : '' }}>
                                                    Private Coupon
                                                </label>
                                                @error('is_private')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>


                                        <button type="submit" class="btn btn-primary theme-bg">Update Coupon</button>
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