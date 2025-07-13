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
        <div class="relative bg-center bg-cover h-[400px] flex items-center" style="background-image: url('{{ asset('asset/images/banner/Mentorship.png') }}');">
            <div class="absolute inset-0 bg-white bg-opacity-10"></div>
            <div class="relative z-10 container mx-auto px-4">
                <div class="space-y-2">
                    <h2 class="text-5xl font-bold text-white ml-[10%]">Mentorship</h2>
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

                    @include('admin.errors')
                    <section class="max-w-7xl mx-auto p-4">
                        <form method="POST" action="{{ route('mentorship-booking-submit') }}">
                            @csrf
                            <!-- Mentorship mode & Date -->
                            <div class="grid grid-cols-2 gap-6 mb-6">
                                <div>
                                    <input type="hidden" name="mentor_id" id="mentorName" value="{{ $mentorDetails->mentor_id }}" class="w-full border rounded px-4 py-2 mb-4" readonly />
                                    <input type="hidden" name="jobseeker_id" value="{{ optional(auth('jobseeker')->user())->id }}" class="w-full border rounded px-4 py-2 mb-4" readonly />

                                    <label class="block font-semibold mb-2">Mentorship mode</label>
                                    <select id="modeSelect" name="mode" class="w-full border rounded px-4 py-2" required>
                                        <option value="">Online/Offline</option>
                                        <option value="online">Online</option>
                                        <option value="offline">Offline</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-semibold mb-2">Select the Date</label>
                                    <input type="date" id="dateInput" name="date" class="w-full border rounded px-4 py-2" required />
                                </div>
                            </div>

                            <!-- Time Slot Section -->
                            <div class="mb-4">
                                <label class="block font-semibold mb-4">Select the Time</label>
                                <div id="slotContainer" class="grid grid-cols-4 gap-4 text-center text-sm">
                                    <!-- Slots will be dynamically loaded here -->
                                </div>
                                <input type="hidden" name="slot_id" id="selectedSlotId" required />
                            </div>

                            <!-- Book Session Button -->
                            <div class="mt-6">
                                <button type="submit" class="bg-blue-700 text-white px-6 py-2 rounded font-semibold">
                                    Book Session
                                </button>
                            </div>
                        </form>

                        <!-- Script -->
                        <script>
                            document.getElementById('modeSelect').addEventListener('change', fetchSlots);
                            document.getElementById('dateInput').addEventListener('change', fetchSlots);

                            function fetchSlots() {
                                const mode = document.getElementById('modeSelect').value;
                                const date = document.getElementById('dateInput').value;
                                const mentorId = document.getElementById('mentorName').value;
                                const container = document.getElementById('slotContainer');

                                if (!mode || !date || !mentorId) {
                                    container.innerHTML = '';
                                    return;
                                }

                                const url = `{{ route('get-available-slots') }}?mode=${mode}&date=${date}&mentor_id=${mentorId}`;

                                fetch(url)
                                    .then(response => response.json())
                                    .then(data => {
                                        container.innerHTML = '';

                                        if (data.length === 0) {
                                            container.innerHTML = `<p class="col-span-4 text-center text-gray-500">No slots available</p>`;
                                            return;
                                        }

                                        data.forEach(slot => {
                                            const isUnavailable = slot.is_unavailable;

                                            const btn = document.createElement('button');
                                            btn.type = 'button';
                                            btn.className = `border rounded py-2 px-3 w-full ${
                                                isUnavailable
                                                    ? 'text-red-600 border-red-500 cursor-not-allowed bg-red-50'
                                                    : 'text-gray-900 hover:bg-blue-100'
                                            }`;

                                            btn.disabled = isUnavailable;

                                            btn.innerHTML = `
                                                <p class="font-medium">${slot.start_time} - ${slot.end_time}</p>
                                                <p class="text-xs ${isUnavailable ? 'text-red-600' : 'text-green-600'}">
                                                    ${isUnavailable ? 'Unavailable' : 'Available'}
                                                </p>
                                                <input type="hidden" name="slot_time" value="${slot.start_time} - ${slot.end_time}" required />
                                            `;

                                            if (!isUnavailable) {
                                                btn.setAttribute('data-available', 'true');
                                                btn.setAttribute('data-slot-id', slot.id); // Assuming slot.id exists
                                                btn.onclick = function () {
                                                    selectTimeSlot(this);
                                                };
                                            }

                                            container.appendChild(btn);
                                        });
                                    })
                                    .catch(error => {
                                        console.error('Failed to fetch slots:', error);
                                        container.innerHTML = `<p class="col-span-4 text-center text-red-600">Error loading slots</p>`;
                                    });
                            }

                            function selectTimeSlot(selectedButton) {
                                document.querySelectorAll('#slotContainer button[data-available="true"]').forEach(btn => {
                                    btn.classList.remove('bg-blue-500', 'text-white');
                                    btn.classList.add('text-gray-900');
                                });

                                selectedButton.classList.add('bg-blue-500', 'text-white');
                                selectedButton.classList.remove('text-gray-900');

                                const slotId = selectedButton.getAttribute('data-slot-id');
                                document.getElementById('selectedSlotId').value = slotId;
                            }
                        </script>
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
        </div>

@include('site.componants.footer')