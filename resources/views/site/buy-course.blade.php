@include('site.componants.header')
<body>
    <div class="loading-area">
        <div class="loading-box"></div>
        <div class="loading-pic">
            <div class="wrapper">
                <div class="cssload-loader"></div>
            </div>
        </div>
    </div>
	<style>
        .site-header.header-style-3.mobile-sider-drawer-menu {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: white;
        }
    </style>
      @include('site.componants.navbar')

  
<div class="page-content">
            <div class="relative bg-center bg-cover h-[400px] flex items-center" style="background-image: url('{{ asset('asset/images/banner/Training.png') }}');">
                <div class="absolute inset-0 bg-white bg-opacity-10"></div>
                <div class="relative z-10 container mx-auto px-4">
                    <div class="space-y-2">
                        <h2 class="text-5xl font-bold text-white ml-[10%]">Training</h2>
                    </div>
                </div>
            </div>
        </div>


        <script>
            function toggleSection(id, iconId) {
                const section = document.getElementById(id);
                const icon = document.getElementById(iconId);
                section.classList.toggle('hidden');
                if (icon.classList.contains('rotate-180')) {
                    icon.classList.remove('rotate-180');
                } else {
                    icon.classList.add('rotate-180');
                }
            }
        </script>

          <main class="w-11/12 mx-auto py-8">
            <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">
              <div class="flex-1">
                <div class="max-w-6xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-6">
                        <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Training mode</label>
                        <select class="w-64 border border-gray-300 rounded px-3 py-2 text-sm">
                            <option>Online/offline</option>
                            <option>Online</option>
                            <option>Offline</option>
                        </select>
                        </div>
                        <div>
                        <h3 class="text-sm font-medium mb-2">Select batch</h3>
                        <div class="space-y-2">
                            <label class="flex items-center justify-between bg-gray-50 px-4 py-3 rounded border">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" class="form-checkbox text-blue-600">
                                <span>Batch no 1</span>
                            </div>
                            <div class="flex space-x-8 text-sm text-gray-600">
                                <span>29/04/2025</span>
                                <span>03:30pm</span>
                            </div>
                            </label>
                        </div>
                        <div class="space-y-2">
                            <label class="flex items-center justify-between bg-gray-50 px-4 py-3 rounded border">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" class="form-checkbox text-blue-600">
                                <span>Batch no 2</span>
                            </div>
                            <div class="flex space-x-8 text-sm text-gray-600">
                                <span>30/05/2025</span>
                                <span>03:30pm</span>
                            </div>
                            </label>
                        </div>
                        <div class="space-y-2">
                            <label class="flex items-center justify-between bg-gray-50 px-4 py-3 rounded border">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" class="form-checkbox text-blue-600">
                                <span>Batch no 3</span>
                            </div>
                            <div class="flex space-x-8 text-sm text-gray-600">
                                <span>31/05/2025</span>
                                <span>03:30pm</span>
                            </div>
                            </label>
                        </div>
                        </div>

                        <div class="flex border rounded p-4 space-x-4">
                        <img src="{{ asset('asset/images/gallery/pic-4.png') }}" alt="Course" class="w-28 h-20 object-cover rounded">
                        <div class="flex-1">
                            <h4 class="font-semibold text-sm">Graphic design - Advance level</h4>
                            <p class="text-xs text-gray-600 mt-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                            <div class="flex items-center text-sm text-yellow-500 mt-1">
                            ★★★★☆ <span class="ml-1 text-gray-500 text-xs">(4.5) Rating</span>
                            </div>
                            <div class="flex items-center space-x-2 mt-2">
                            <span class="text-gray-400 line-through text-sm">SAR 89</span>
                            <span class="text-sm font-bold text-blue-600">SAR 89</span>
                            </div>
                            <button class="text-red-500 text-sm mt-2 hover:underline">🗑 Remove</button>
                        </div>
                        </div>
                    </div>

                  <!-- Include Alpine.js -->
                    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

                    <div x-data="{ paymentMethod: 'card' }" class="space-y-4">

                        <!-- Select Payment Method -->
                        <div>
                            <h3 class="text-sm font-medium mb-2">Select Payment Method:</h3>
                            <div class="space-y-2">
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="payment_method" value="card" x-model="paymentMethod" class="form-radio text-blue-600">
                                    <span class="text-sm">Credit / Debit Card</span>
                                </label>
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="payment_method" value="upi" x-model="paymentMethod" class="form-radio text-blue-600">
                                    <span class="text-sm">UPI / Online Payment</span>
                                </label>
                            </div>
                        </div>

                        <!-- Card Payment Section -->
                        <div x-show="paymentMethod === 'card'" class="space-y-2 border p-4 rounded bg-gray-50">
                            <h4 class="text-sm font-medium mb-1">Enter Card Details:</h4>
                            <input type="text" placeholder="Cardholder Name" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <input type="text" placeholder="Card Number" maxlength="19" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <div class="flex space-x-2">
                                <input type="text" placeholder="MM/YY" class="w-1/2 border border-gray-300 rounded px-3 py-2 text-sm">
                                <input type="text" placeholder="CVV" maxlength="4" class="w-1/2 border border-gray-300 rounded px-3 py-2 text-sm">
                            </div>
                        </div>

                        <!-- UPI Section -->
                        <div x-show="paymentMethod === 'upi'" class="space-y-2 border p-4 rounded bg-gray-50">
                            <h4 class="text-sm font-medium mb-1">Enter UPI ID:</h4>
                            <input type="text" placeholder="yourname@upi" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        </div>

                        <!-- Bank Transfer Section -->
                        <div x-show="paymentMethod === 'bank'" class="space-y-2 border p-4 rounded bg-gray-50">
                            <h4 class="text-sm font-medium mb-1">Enter Bank Transfer Details:</h4>
                            <input type="text" placeholder="Account Holder Name" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <input type="text" placeholder="Bank Name" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <input type="text" placeholder="Transaction Reference Number" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        </div>

                        <!-- Apply Promocode Section -->
                        <div>
                            <h3 class="text-sm font-medium mb-2">Apply Promocode:</h3>
                            <div class="flex space-x-2">
                                <input type="text" placeholder="Enter promocode for discount" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                <button class="bg-blue-600 text-white px-4 py-2 rounded text-sm">Apply</button>
                            </div>
                        </div>

                        <!-- Billing Information -->
                        <div class="border rounded p-4 space-y-2">
                            <h3 class="text-sm font-medium border-b pb-2">Billing Information</h3>
                            <div class="flex justify-between text-sm">
                                <span>Course total</span>
                                <span>SAR 89</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Saved amount</span>
                                <span>SAR 5</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Tax</span>
                                <span>SAR 2</span>
                            </div>
                            <div class="flex justify-between text-base font-semibold pt-2 border-t">
                                <span>Total</span>
                                <span>SAR 86</span>
                            </div>
                            <a href="#">
                                <button class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded mt-4 text-sm font-medium">
                                    Proceed to Checkout
                                </button>
                            </a>
                        </div>
                    </div>

                    </div>
            </div>
          </main>

          <script>
            document.addEventListener('DOMContentLoaded', () => {
              const tabs = document.querySelectorAll('.tab-link');
              const contents = document.querySelectorAll('.tab-content');

              tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                  tabs.forEach(t => {
                    t.classList.remove('active-tab', 'border-blue-600', 'text-blue-600');
                    t.classList.add('text-gray-600', 'border-transparent');
                  });

                  contents.forEach(content => content.classList.add('hidden'));

                  tab.classList.add('active-tab', 'border-blue-600', 'text-blue-600');
                  tab.classList.remove('text-gray-600', 'border-transparent');

                  const tabName = tab.getAttribute('data-tab');
                  const activeContent = document.querySelector(`.tab-content[data-tab-content="${tabName}"]`);
                  if (activeContent) activeContent.classList.remove('hidden');
                });
              });
            });
          </script>
          <style>
            .active-tab {
              border-bottom-color: #2563eb; 
              color: #2563eb;
            }
          </style>
@include('site.componants.footer')