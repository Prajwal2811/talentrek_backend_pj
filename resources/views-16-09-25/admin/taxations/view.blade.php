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
                                <h1>Hi, {{ Auth()->user()->name }}!</h1>
                                <span>JustDo Subscription Plan Creation</span>
                            </div>
                        </div>
                    </div>

                   <div class="container">
                       <form method="POST" action="{{ route('admin.taxations.update', $type) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="user_type" value="{{ strtolower($type) }}">

                        @php
                            $taxData = App\Models\Setting::first();
                            $rateValue = '';
                            if ($type === 'Trainer') {
                                $rateValue = $taxData->trainingMaterialTax;
                            } elseif ($type === 'Mentor') {
                                $rateValue = $taxData->mentorTax;
                            } elseif ($type === 'Assessor') {
                                $rateValue = $taxData->assessorTax;
                            } elseif ($type === 'Coach') {
                                $rateValue = $taxData->coachTax;
                            }
                        @endphp

                        <div class="card mb-3 taxation-block">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ ucfirst($type) }} Taxation</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Tax Rate -->
                                    <div class="form-group col-md-6">
                                        <label>Rate (%)</label>
                                        <input 
                                            type="number" 
                                            name="rate" 
                                            value="{{ $rateValue }}" 
                                            class="form-control" 
                                            step="0.01" 
                                            min="0" 
                                            required>
                                        @error("rate") 
                                            <small class="text-danger">{{ $message }}</small> 
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Taxation</button>
                    </form>



                    </div>

                </div>
            </div>

            


        </div>
    </div>

    @include('admin.componants.footer')