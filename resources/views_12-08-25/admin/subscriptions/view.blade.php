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
                        <form method="POST" action="{{ route('admin.subscription.store') }}">
                            @csrf
                            <input type="hidden" name="user_type" value="{{ $type }}">

                            <div id="plan-container">
                               @php
                                    $oldPlans = old('plans');
                                    $plansToShow = $oldPlans ?? ($plans->isEmpty() ? [['title' => '', 'price' => '', 'duration_months' => '', 'features' => '', 'description' => '']] : $plans->toArray());
                                @endphp


                                @foreach($plansToShow as $i => $plan)
                                <div class="card mb-3 plan-block">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Subscription Plan</h5>
                                        {{-- <button type="button" class="btn btn-danger btn-sm remove-plan {{ $i === 0 ? 'd-none' : '' }}">Remove</button> --}}
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Title -->
                                            <div class="form-group col-md-6">
                                                <label>Plan Title</label>
                                                <input type="text" name="plans[{{ $i }}][title]" value="{{ $plan['title'] ?? '' }}" class="form-control" required>
                                                @error("plans.$i.title") <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>

                                            <!-- Price -->
                                            <div class="form-group col-md-6">
                                                <label>Price (AED)</label>
                                                <input type="number" name="plans[{{ $i }}][price]" value="{{ $plan['price'] ?? '' }}" class="form-control" step="0.01" min="0.01" required>
                                                @error("plans.$i.price") <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>

                                            <!-- Duration -->
                                            <div class="form-group col-md-6">
                                                <label>Duration (Months)</label>
                                                <select name="plans[{{ $i }}][duration_months]" class="form-control" required>
                                                    <option disabled {{ empty($plan['duration_months']) ? 'selected' : '' }}>Select duration</option>
                                                    @foreach([1, 3, 6, 12] as $m)
                                                        <option value="{{ $m }}" {{ ($plan['duration_months'] ?? ($plan['duration_days'] ?? 0)/30) == $m ? 'selected' : '' }}>
                                                            {{ $m }} Month{{ $m > 1 ? 's' : '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error("plans.$i.duration_months") <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>

                                            <!-- Features -->
                                            <div class="form-group col-md-6">
                                                <label>Features (comma separated)</label>
                                                @php
                                                    $rawFeatures = $plan['features'] ?? '';
                                                    if (is_array($rawFeatures)) {
                                                        $featuresValue = implode(', ', $rawFeatures);
                                                    } elseif (is_string($rawFeatures) && str_starts_with($rawFeatures, '[')) {
                                                        $featuresValue = implode(', ', json_decode($rawFeatures, true) ?? []);
                                                    } else {
                                                        $featuresValue = (string) $rawFeatures;
                                                    }
                                                @endphp

                                                <input type="text" name="plans[{{ $i }}][features]" value="{{ $featuresValue }}" class="form-control" placeholder="e.g. Chat, Support, Analytics">
                                                @error("plans.$i.features") <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>

                                            <!-- Description -->
                                            <div class="form-group col-md-12">
                                                <label>Description</label>
                                                <textarea name="plans[{{ $i }}][description]" class="form-control" rows="3">{{ $plan['description'] ?? '' }}</textarea>
                                                @error("plans.$i.description") <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Add Plan -->
                            {{-- <div class="mb-3">
                                <button type="button" id="add-plan" class="btn btn-info">+ Add Another Plan</button>
                            </div> --}}

                            <!-- Submit -->
                            <button type="submit" class="btn btn-primary">Submit All Plans</button>
                        </form>
                    </div>

                    <!-- JS for dynamic plan blocks -->
                    <script>
                        let planIndex = {{ count($plansToShow) }};
                        document.getElementById('add-plan').addEventListener('click', function () {
                            const container = document.getElementById('plan-container');
                            const original = container.querySelector('.plan-block');
                            const clone = original.cloneNode(true);

                            clone.querySelectorAll('input, textarea, select').forEach(input => {
                                const name = input.name;
                                if (name) {
                                    input.name = name.replace(/\[\d+]/, `[${planIndex}]`);
                                }
                                if (input.tagName === 'SELECT') {
                                    input.selectedIndex = 0;
                                } else {
                                    input.value = '';
                                }
                            });

                            clone.querySelectorAll('.text-danger').forEach(el => el.remove());

                            const removeBtn = clone.querySelector('.remove-plan');
                            removeBtn.classList.remove('d-none');
                            removeBtn.onclick = () => clone.remove();

                            container.appendChild(clone);
                            planIndex++;
                        });

                        document.querySelectorAll('.remove-plan').forEach(btn => {
                            btn.onclick = function () {
                                this.closest('.plan-block').remove();
                            };
                        });
                    </script>




                </div>
            </div>

            


        </div>
    </div>

    @include('admin.componants.footer')