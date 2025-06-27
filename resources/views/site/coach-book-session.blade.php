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
            <div class="relative bg-center bg-cover h-[400px] flex items-center" style="background-image: url('{{ asset('asset/images/banner/Coaching.png') }}');">
                <div class="absolute inset-0 bg-white bg-opacity-10"></div>
                <div class="relative z-10 container mx-auto px-4">
                    <div class="space-y-2">
                        <h2 class="text-5xl font-bold text-white ml-[10%]">Coaching</h2>
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
              <!-- Left/Main Content -->
              <div class="flex-1">
                 <div class="flex flex-col md:flex-row md:items-center justify-between p-6 rounded-lg">
                    <div class="flex items-center gap-6">
                        <img src="{{ asset('asset/images/client-logo/w2.png') }}" alt="Mentor" class="w-28 h-28 rounded-full object-cover border" />
                        <div>
                        <h1 class="text-xl font-semibold text-gray-900">Mohammad Raza</h1>
                        <p class="text-sm text-gray-600 mt-1">Web Designer</p>
                        <p class="text-sm text-gray-500 mt-0.5">5+ years experience</p>
                        <div class="flex items-center mt-1 text-sm">
                            <div class="flex text-[#f59e0b]">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><polygon points="9.9,1.1 7.6,6.9 1.4,7.6 6.1,11.8 4.8,18 9.9,14.9 15,18 13.7,11.8 18.4,7.6 12.2,6.9 "/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><polygon points="9.9,1.1 7.6,6.9 1.4,7.6 6.1,11.8 4.8,18 9.9,14.9 15,18 13.7,11.8 18.4,7.6 12.2,6.9 "/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><polygon points="9.9,1.1 7.6,6.9 1.4,7.6 6.1,11.8 4.8,18 9.9,14.9 15,18 13.7,11.8 18.4,7.6 12.2,6.9 "/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><polygon points="9.9,1.1 7.6,6.9 1.4,7.6 6.1,11.8 4.8,18 9.9,14.9 15,18 13.7,11.8 18.4,7.6 12.2,6.9 "/></svg>
                            <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20"><polygon points="9.9,1.1 7.6,6.9 1.4,7.6 6.1,11.8 4.8,18 9.9,14.9 15,18 13.7,11.8 18.4,7.6 12.2,6.9 "/></svg>
                            </div>
                            <span class="ml-2 text-gray-600">(4.8/5) <span class="text-xs">Rating</span></span>
                        </div>
                        <p class="text-sm text-gray-800 font-semibold mt-1">
                            SAR 89 <span class="text-xs text-gray-500">per mentorship session</span>
                        </p>
                        </div>
                    </div>
                    </div>

                    <section class="max-w-7xl mx-auto p-4">
                    <!-- Mentorship mode -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block font-semibold mb-2">Mentorship mode</label>
                            <label class="block text-sm text-gray-700 mb-1">Date</label>
                            <select class="w-full border rounded px-4 py-2">
                            <option>Online/offline</option>
                            <option>Online</option>
                            <option>Offline</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold mb-2">Select the Date</label>
                            <label class="block text-sm text-gray-700 mb-1">Date</label>
                            <input type="date" value="2025-04-15" class="w-full border rounded px-4 py-2" />
                        </div>
                    </div>


                    <!-- Select the Time -->
                 <div class="mb-4">
                    <label class="block font-semibold mb-4">Select the Time</label>
                    <div class="grid grid-cols-4 gap-4 text-center text-sm">
                        <!-- Time Slot -->
                        <button
                        type="button"
                        class="border rounded py-2 px-3 text-gray-900 focus:outline-none"
                        data-available="true"
                        onclick="selectTimeSlot(this)"
                        >
                        <p class="font-medium">09:00 am</p>
                        <p class="text-xs text-gray-500">Available</p>
                        </button>

                        <button
                        type="button"
                        class="border rounded py-2 px-3 text-red-600 border-red-500 cursor-not-allowed"
                        disabled
                        >
                        <p class="font-medium">10:15 am</p>
                        <p class="text-xs">Unavailable</p>
                        </button>

                        <button
                        type="button"
                        class="border rounded py-2 px-3 text-gray-900 focus:outline-none"
                        data-available="true"
                        onclick="selectTimeSlot(this)"
                        >
                        <p class="font-medium">11:10 am</p>
                        <p class="text-xs text-gray-500">Available</p>
                        </button>

                        <button
                        type="button"
                        class="border rounded py-2 px-3 text-gray-900 focus:outline-none"
                        data-available="true"
                        onclick="selectTimeSlot(this)"
                        >
                        <p class="font-medium">01:10 pm</p>
                        <p class="text-xs text-gray-500">Available</p>
                        </button>

                        <button
                        type="button"
                        class="border rounded py-2 px-3 text-gray-900 focus:outline-none"
                        data-available="true"
                        onclick="selectTimeSlot(this)"
                        >
                        <p class="font-medium">02:00 pm</p>
                        <p class="text-xs text-gray-500">Available</p>
                        </button>

                        <button
                        type="button"
                        class="border rounded py-2 px-3 text-red-600 border-red-500 cursor-not-allowed"
                        disabled
                        >
                        <p class="font-medium">02:45 pm</p>
                        <p class="text-xs">Unavailable</p>
                        </button>

                        <button
                        type="button"
                        class="border rounded py-2 px-3 text-gray-900 focus:outline-none"
                        data-available="true"
                        onclick="selectTimeSlot(this)"
                        >
                        <p class="font-medium">03:30 pm</p>
                        <p class="text-xs text-gray-500">Available</p>
                        </button>

                        <button
                        type="button"
                        class="border rounded py-2 px-3 text-gray-900 focus:outline-none"
                        data-available="true"
                        onclick="selectTimeSlot(this)"
                        >
                        <p class="font-medium">04:15 pm</p>
                        <p class="text-xs text-gray-500">Available</p>
                        </button>

                        <button
                        type="button"
                        class="border rounded py-2 px-3 text-red-600 border-red-500 cursor-not-allowed"
                        disabled
                        >
                        <p class="font-medium">05:00 pm</p>
                        <p class="text-xs">Unavailable</p>
                        </button>


                        <!-- Add more slots similarly -->
                    </div>
                    </div>

                    <script>
                    function selectTimeSlot(selectedButton) {
                        // Deselect all selectable buttons
                        document.querySelectorAll(
                        '.grid button[data-available="true"]'
                        ).forEach((btn) => {
                        btn.classList.remove('bg-blue-500', 'text-white');
                        btn.classList.add('text-gray-900');
                        });

                        // Select clicked button
                        selectedButton.classList.add('bg-blue-500', 'text-white');
                        selectedButton.classList.remove('text-gray-900');
                    }
                    </script>


                    <!-- Book Session Button -->
                    <div class="mt-6">
                        <a href="{{ route('coach-booking-success')}}">
                            <button class="bg-blue-700 text-white px-6 py-2 rounded font-semibold">
                                Book Session
                            </button>
                        </a>
                        
                    </div>
                    </section>

                


               
              </div>

            </div>
          </main>

          <script>
            document.addEventListener('DOMContentLoaded', () => {
              const tabs = document.querySelectorAll('.tab-link');
              const contents = document.querySelectorAll('.tab-content');

              tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                  // Remove active class from all tabs
                  tabs.forEach(t => {
                    t.classList.remove('active-tab', 'border-blue-600', 'text-blue-600');
                    t.classList.add('text-gray-600', 'border-transparent');
                  });

                  // Hide all contents
                  contents.forEach(content => content.classList.add('hidden'));

                  // Add active class to clicked tab
                  tab.classList.add('active-tab', 'border-blue-600', 'text-blue-600');
                  tab.classList.remove('text-gray-600', 'border-transparent');

                  // Show corresponding content
                  const tabName = tab.getAttribute('data-tab');
                  const activeContent = document.querySelector(`.tab-content[data-tab-content="${tabName}"]`);
                  if (activeContent) activeContent.classList.remove('hidden');
                });
              });
            });
          </script>
          <style>
            .active-tab {
              border-bottom-color: #2563eb; /* Tailwind blue-600 */
              color: #2563eb;
            }
          </style> 

@include('site.componants.footer')