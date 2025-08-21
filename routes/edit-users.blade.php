@include('admin.layouts.header')
<body class="">
  <div class="wrapper ">
    @include('admin.layouts.sidebar')
    <div class="main-panel">
      @include('admin.layouts.navbar')
      
      <!-- End Navbar -->
      <div class="content">
        <div class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">
                <div class="card ">
                  <div class="card-header card-header-success card-header-icon">
                    <div class="card-icon">
                      <i class="material-icons"></i>
                    </div>
                    <h4 class="card-title">Edit User</h4>
                  </div>
                  @php
                      $roles = App\Models\Role::where('status','active')->get();
                  @endphp
                  <div class="container mt-3">
                    <form method="POST" action="{{ route('admin.updateUserDetails') }}" class="form" id="edit-user-form">
                      @csrf
                      <input type="hidden" name="user_id" value="{{ $user->id }}" required>

                       <div class="card">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12 mt-3">
                                        <h4 class="card-title">Product Category Allocation</h4>
                                    </div>
                                    @php
                                        $porCategories = App\Models\Category::where('status', 'active')->get();
                                        $existingCategories = App\Models\AgentProducts::where('agent_id', $user->id)->pluck('cat_id')->toArray();
                                        $existingProducts = App\Models\AgentProducts::where('agent_id', $user->id)->pluck('pro_id')->toArray();
                                    @endphp

                                    <input type="hidden" id="userCode" value="{{ $user->id }}">

                                    <!-- Category Selection -->
                                    <div class="row card-body">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div id="category" class="d-flex flex-wrap">
                                                    @foreach ($porCategories as $porCategory)
                                                        <div class="form-check me-3">
                                                            <label class="form-check-label">
                                                                <input 
                                                                    class="form-check-input category-checkbox" 
                                                                    type="checkbox" 
                                                                    name="category[]" 
                                                                    value="{{ $porCategory->category }}" 
                                                                    {{ in_array($porCategory->category, $existingCategories) ? 'checked' : '' }}> 
                                                                    <span class="form-check-sign">
                                                                        <span class="check"></span>
                                                                    </span>
                                                                {{ $porCategory->category }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Product Allocation -->
                                    <div class="col-md-12 mt-3 mb-2">
                                        <h4 class="card-title">Product Allocation</h4>
                                    </div>
                                    <div class="col-md-12" id="products">
                                        @php
                                            $products = App\Models\Product::whereIn('pro_category', $existingCategories)->get();
                                        @endphp

                                        @if($products->isEmpty())
                                            <p class="text-warning">No products assigned to the user.</p>
                                        @else
                                            <div class="row">
                                                @foreach ($products as $product)
                                                    <div class="col-md-4">
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input 
                                                                    class="form-check-input product-checkbox" 
                                                                    type="checkbox" 
                                                                    name="product[]" 
                                                                    value="{{ $product->id }}" 
                                                                    data-category="{{ $product->pro_category }}"
                                                                    {{ in_array($product->id, $existingProducts) ? 'checked' : '' }}> 
                                                                    <span class="form-check-sign">
                                                                        <span class="check"></span>
                                                                    </span>
                                                                {{ $product->pro_title }} ({{ $product->pro_category }})
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <!-- jQuery AJAX -->
                                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                    <script>
                                        $(document).ready(function(){
                                            function loadProducts() {
                                                let selectedCategories = $(".category-checkbox:checked").map(function() {
                                                    return $(this).val();
                                                }).get();

                                                let selectedProducts = $(".product-checkbox:checked").map(function() {
                                                    return $(this).val();
                                                }).get();

                                                let agentId = $("#userCode").val();

                                                $.ajax({
                                                    url: "{{ route('admin.get-products-by-category-edit') }}",
                                                    method: "POST",
                                                    data: {
                                                        _token: "{{ csrf_token() }}",
                                                        categories: selectedCategories,
                                                        products: selectedProducts,
                                                        agent_id: agentId
                                                    },
                                                    success: function(response){
                                                        $("#products").html(response);
                                                    }
                                                });
                                            }

                                            $(".category-checkbox").on("change", loadProducts);
                                            $(".product-checkbox").on("change", loadProducts);
                                        });
                                    </script>


                                </div>
                            </div>
                        </div>
                      <div class="row">
                        @php
                            $slabs = App\Models\Slab::where('status', 'active')->orderByDesc('id')->get();
                            // echo "<pre>"; print_r($slabs); die; 
                        @endphp
                        <div class="">
                            <div class="card-header">
                                <h4>Slab Assign</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($slabs as $slab)
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="radio" name="slab" value="{{ $slab->id }}"
                                                        @if ($user->slab)
                                                            {{ $slab->id === $user->slab ? 'checked' : '' }}
                                                        @else
                                                            {{ $slab->slab_name === 'Default' ? 'checked' : '' }}
                                                        @endif>
                                                    {{ $slab->slab_name }}
                                                    <span class="circle">
                                                        <span class="check"></span>
                                                    </span>
                                                    @error('slab')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                          <div class="col-md-12">
                              <div class="form-group">
                                  <select class="form-control @error('role') is-invalid @enderror" id="exampleAdminRole" name="role" required>
                                      <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select Role</option>
                                      @foreach($roles as $role)
                                          <option value="{{ $role->role }}" 
                                              {{ old('role', $user->role) == $role->role ? 'selected' : '' }}>
                                              {{ $role->role }}
                                          </option>
                                      @endforeach
                                  </select>
                                  @error('role')
                                      <div class="invalid-feedback">{{ $message }}</div>
                                  @enderror
                              </div>
                          </div>
                  
                          <div class="col-md-6 mt-5">
                              <div class="form-group">
                                  <label for="name{{ $user->id }}" class="bmd-label-floating">Name *</label>
                                  <input type="text" class="form-control @error('name') is-invalid @enderror" id="name{{ $user->id }}" name="name" value="{{ old('name', $user->name) }}" required>
                                  @error('name')
                                      <div class="invalid-feedback">{{ $message }}</div>
                                  @enderror
                              </div>
                          </div>
                  
                          <div class="col-md-6 mt-5">
                              <div class="form-group">
                                  <label for="email{{ $user->id }}" class="bmd-label-floating">Email Address *</label>
                                  <input type="email" readonly class="form-control @error('email') is-invalid @enderror" id="email{{ $user->id }}" name="email" value="{{ old('email', $user->email) }}" required>
                                  @error('email')
                                      <div class="invalid-feedback">{{ $message }}</div>
                                  @enderror
                              </div>
                          </div>
                  
                          <div class="col-md-2 mt-5">
                              <div class="form-group">
                                  <select name="phone_country_code" class="form-control @error('phone_country_code') is-invalid @enderror" required>
                                    <option value="" disabled>Select Emirate</option>
                                    <option value="Abu Dhabi" {{ old('phone_country_code', $user->phone_country_code) == 'Abu Dhabi' ? 'selected' : '' }}>Abu Dhabi</option>
                                    <option value="Dubai" {{ old('phone_country_code', $user->phone_country_code) == 'Dubai' ? 'selected' : '' }}>Dubai</option>
                                    <option value="Sharjah" {{ old('phone_country_code', $user->phone_country_code) == 'Sharjah' ? 'selected' : '' }}>Sharjah</option>
                                    <option value="Ajman" {{ old('phone_country_code', $user->phone_country_code) == 'Ajman' ? 'selected' : '' }}>Ajman</option>
                                    <option value="Umm Al Quwain" {{ old('phone_country_code', $user->phone_country_code) == 'Umm Al Quwain' ? 'selected' : '' }}>Umm Al Quwain</option>
                                    <option value="Fujairah" {{ old('phone_country_code', $user->phone_country_code) == 'Fujairah' ? 'selected' : '' }}>Fujairah</option>
                                    <option value="Ras Al Khaimah" {{ old('phone_country_code', $user->phone_country_code) == 'Ras Al Khaimah' ? 'selected' : '' }}>Ras Al Khaimah</option>
                                  </select>
                                  @error('phone_country_code')
                                      <div class="invalid-feedback">{{ $message }}</div>
                                  @enderror
                              </div>
                          </div>
                  
                          <div class="col-md-4 mt-5">
                              <div class="form-group">
                                  <label for="examplePhone" class="bmd-label-floating">Phone Number *</label>
                                  <input type="text" class="form-control @error('phone') is-invalid @enderror" id="examplePhone" placeholder="Enter 10-digit phone number" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required name="phone" value="{{ old('phone', $user->phone) }}">
                                  @error('phone')
                                      <div class="invalid-feedback">{{ $message }}</div>
                                  @enderror
                              </div>
                          </div>
                  
                          <div class="col-md-2 mt-5">
                              <div class="form-group">
                                  <select name="whatsapp_country_code" class="form-control @error('whatsapp_country_code') is-invalid @enderror" required>
                                    <option value="" disabled>Select Emirate</option>
                                    <option value="Abu Dhabi" {{ old('whatsapp_country_code', $user->whatsapp_country_code) == 'Abu Dhabi' ? 'selected' : '' }}>Abu Dhabi</option>
                                    <option value="Dubai" {{ old('whatsapp_country_code', $user->whatsapp_country_code) == 'Dubai' ? 'selected' : '' }}>Dubai</option>
                                    <option value="Sharjah" {{ old('whatsapp_country_code', $user->whatsapp_country_code) == 'Sharjah' ? 'selected' : '' }}>Sharjah</option>
                                    <option value="Ajman" {{ old('whatsapp_country_code', $user->whatsapp_country_code) == 'Ajman' ? 'selected' : '' }}>Ajman</option>
                                    <option value="Umm Al Quwain" {{ old('whatsapp_country_code', $user->whatsapp_country_code) == 'Umm Al Quwain' ? 'selected' : '' }}>Umm Al Quwain</option>
                                    <option value="Fujairah" {{ old('whatsapp_country_code', $user->whatsapp_country_code) == 'Fujairah' ? 'selected' : '' }}>Fujairah</option>
                                    <option value="Ras Al Khaimah" {{ old('whatsapp_country_code', $user->whatsapp_country_code) == 'Ras Al Khaimah' ? 'selected' : '' }}>Ras Al Khaimah</option>
                                  </select>
                                  @error('whatsapp_country_code')
                                      <div class="invalid-feedback">{{ $message }}</div>
                                  @enderror
                              </div>
                          </div>
                  
                          <div class="col-md-4 mt-5">
                              <div class="form-group">
                                  <label for="exampleWhatsapp" class="bmd-label-floating">WhatsApp Number *</label>
                                  <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);" required id="exampleWhatsapp" name="whatsapp_number" value="{{ old('whatsapp_number', $user->whatsapp_number) }}">
                                  @error('whatsapp_number')
                                      <div class="invalid-feedback">{{ $message }}</div>
                                  @enderror
                              </div>
                          </div>
                  
                          <div class="card-footer text-left mt-5">
                              <button type="submit" class="btn btn-rose" id="submit-button">Update User</button>
                          </div>
                      </div>
                  </form>
                   <!-- Full-page loader -->
                   <div id="page-loader" class="page-loader" style="display: none;">
                    <div class="spinner">
                        <i class="fa fa-spinner fa-spin"></i>
                    </div>
                </div>
                <style>
                    .page-loader {
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
                        z-index: 9999;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                    }
                    .spinner {
                        color: white;
                        font-size: 2rem;
                    }
                </style>
                <script>
                    document.getElementById('edit-user-form').addEventListener('submit', function(e) {
                        const submitButton = document.getElementById('submit-button');
                        const pageLoader = document.getElementById('page-loader');
                
                        // Disable the submit button and show the loader
                        submitButton.disabled = true;
                        pageLoader.style.display = 'flex';
                
                        // Allow the form to proceed with submission
                    });
                </script>
                  </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
@include('admin.layouts.footer')

      