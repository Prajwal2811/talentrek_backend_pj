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
    @if($jobseekerNeedsSubscription)
            @include('site.jobseeker.subscription.index')
        @endif
@include('site.componants.navbar')

    <div class="page-content">
        <div class="relative bg-center bg-cover h-[400px] flex items-center" style="background-image: url('{{ asset('asset/images/banner/Assessment.png') }}');">
            <div class="absolute inset-0 bg-white bg-opacity-10"></div>
            <div class="relative z-10 container mx-auto px-4">
                <div class="space-y-2">
                    <h2 class="text-5xl font-bold text-white ml-[10%]">Assessment</h2>
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


        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
       @if(session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                Swal.fire({
                    icon: 'success',
                    title: '{{ session('success') }}',
                    html: `
                        <section class="flex flex-col items-center justify-center text-center">
                            <h2 class="text-xl font-medium mb-1">Your Assessment Booked Successfully</h2>
                            <p class="text-sm text-gray-600 mb-1">Booking ID: <span class="font-semibold">#{{ session('booking_id') }}</span></p>

                            <div class="text-sm text-gray-600 mb-4">
                                <p>{{ \Carbon\Carbon::parse(session('slot_date'))->format('F d, Y') }}</p>
                                <p>{{ session('slot_time') }}</p>
                            </div>

                            @if(session('assessor_address'))
                                <div class="text-sm text-gray-700 mt-2">
                                    <p class="font-semibold text-gray-800">Location:</p>
                                    <p>{{ session('assessor_address') }}</p>
                                </div>
                            @endif

                            @if(session('zoom_link'))
                                <div class="flex items-center w-full max-w-md bg-gray-100 rounded-md px-4 py-2 shadow mt-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2H4m16 4V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2z" />
                                    </svg>
                                    <input type="text" readonly value="{{ session('zoom_link') }}" class="flex-1 bg-transparent text-sm outline-none" />
                                    <button onclick="navigator.clipboard.writeText('{{ session('zoom_link') }}')" class="bg-blue-700 text-white text-sm font-medium px-4 py-1 rounded hover:bg-blue-800 ml-2">
                                        Copy
                                    </button>
                                </div>
                            @endif
                        </section>
                    `,
                    confirmButtonColor: '#3085d6',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('jobseeker.profile') }}';
                    }
                });
            });
        </script>
        @endif




          <main class="w-11/12 mx-auto py-8">
            <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">
              <!-- Left/Main Content -->
              <div class="flex-1">


                <div class="flex flex-col md:flex-row md:items-center justify-between p-6 rounded-lg">
                    <div class="flex items-center gap-6">
                        @php
                            $profilePicture = optional($assessor->profilePicture)->document_path ?? asset('default.jpg');
                            $avgRating = number_format($assessor->reviews->avg('ratings'), 1);
                            $reviewStars = floor($avgRating);
                        @endphp

                        <img src="{{ $profilePicture }}" alt="{{ $assessor->name }}" class="w-28 h-28 rounded-full object-cover border" />

                        <div>
                            <h1 class="text-xl font-semibold text-gray-900">{{ $assessor->name }}</h1>
                            <!-- <p class="text-sm text-gray-600 mt-1">{{ $assessor->additionalInfo->designation ?? 'Assessor' }}</p> -->
                             <p class="text-sm text-gray-700 mt-0.5">
                                {{ $assessor->total_experience ?? '0 years 0 months 0 days' }} of experience
                            </p>

                            <div class="flex items-center mt-1 text-sm">
                                <div class="flex text-[#f59e0b]">
                                    @for ($i = 0; $i < 5; $i++)
                                        @if ($i < $reviewStars)
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><polygon points="9.9,1.1 7.6,6.9 1.4,7.6 6.1,11.8 4.8,18 9.9,14.9 15,18 13.7,11.8 18.4,7.6 12.2,6.9"/></svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20"><polygon points="9.9,1.1 7.6,6.9 1.4,7.6 6.1,11.8 4.8,18 9.9,14.9 15,18 13.7,11.8 18.4,7.6 12.2,6.9"/></svg>
                                        @endif
                                    @endfor
                                </div>
                                <span class="ml-2 text-gray-600">({{ $avgRating }}/5) <span class="text-xs">Rating</span></span>
                            </div>
                        </div>
                    

                            </div>
                        <!-- <p class="text-sm text-gray-800 font-semibold mt-1">
                            SAR 89 <span class="text-xs text-gray-500">per assessment session</span>
                        </p> -->
                        </div>
                    </div>
                </div>

                    

                    <div class="row">
                        <div class="col-6 ml-auto mr-auto text-center" style="margin: auto">
                            @if (session()->has('error'))
                                <div class="alert alert-danger alert-dismissible fade show" id="errorAlert">
                                    <strong>Oops!</strong> {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <script>
                        // Automatically hide alerts after 3 seconds
                        setTimeout(() => {
                            const successAlert = document.getElementById('successAlert');
                            const errorAlert = document.getElementById('errorAlert');

                            if (successAlert) {
                                successAlert.classList.add('fade');
                                setTimeout(() => successAlert.remove(), 500); // Remove from DOM after fade
                            }
                            if (errorAlert) {
                                errorAlert.classList.add('fade');
                                setTimeout(() => errorAlert.remove(), 500); // Remove from DOM after fade
                            }
                        }, 10000);
                    </script>
                    <section class="max-w-7xl mx-auto p-4">
                        <form method="POST" action="{{ route('assessor-booking-submit') }}">
                            @csrf

                            <!-- assessment mode & Date -->
                            <div class="grid grid-cols-2 gap-6 mb-6">
                                <div>
                                    <!-- < ?php echo "<pre>"; print_r($assessorDetails);exit;?> -->
                                    <input type="hidden" name="assessor_id" id="assessorName" value="{{ $assessorDetails->assessor_id }}" />
                                    <input type="hidden" name="jobseeker_id" value="{{ optional(auth('jobseeker')->user())->id }}" />

                                    <label class="block font-semibold mb-2">Assessment mode</label>
                                    <select id="modeSelect" name="mode" class="w-full border rounded px-4 py-2" required>
                                        <option value="">Online/Offline</option>
                                        <option value="online" {{ old('mode') == 'online' ? 'selected' : '' }}>Online</option>
                                        <option value="offline" {{ old('mode') == 'offline' ? 'selected' : '' }}>Offline</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-semibold mb-2">Select the Date</label>
                                    <input type="date" id="dateInput" name="date" class="w-full border rounded px-4 py-2"  value="{{ old('date') }}" required />
                                </div>
                            </div>

                            <!-- Time Slot Section -->
                            <div class="mb-4">
                                <label class="block font-semibold mb-4">Select the Time</label>
                                <div id="slotContainer" class="grid grid-cols-4 gap-4 text-center text-sm">
                                    <!-- Slots will be dynamically loaded here -->
                                </div>
                                <input type="hidden" name="slot_id" id="selectedSlotId" required />
                                <input type="hidden" name="slot_time" id="selectedSlotTime" required />
                            </div>

                            <!-- Book Session Button -->
                            <div class="mt-6">
                                <button id="bookBtn" type="submit" class="bg-blue-700 text-white px-6 py-2 rounded font-semibold">
                                    Book Session
                                </button>
                            </div>
                        </form>
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script>
                            $(document).ready(function () {
                                // Book button click validation
                                $('#bookBtn').on('click', function(e) {
                                    e.preventDefault();

                                    let mode = $('#modeSelect').val();
                                    let date = $('#dateInput').val();
                                    let slotId = $('#selectedSlotId').val();
                                    let slotTime = $('#selectedSlotTime').val();

                                    // Clear previous errors
                                    $('.error-message').remove();

                                    let isValid = true;

                                    if (!mode) {
                                        $('#modeSelect').after('<span class="text-red-500 error-message">Please select assessment mode</span>');
                                        isValid = false;
                                    }

                                    if (!date) {
                                        $('#dateInput').after('<span class="text-red-500 error-message">Please select a date</span>');
                                        isValid = false;
                                    }

                                    if (!slotId || !slotTime) {
                                        $('#slotContainer').after('<span class="text-red-500 error-message">Please select a time slot</span>');
                                        isValid = false;
                                    }

                                    if (isValid) {
                                        $(this).closest('form').submit();
                                    }
                                });

                                // Remove error message on change or input
                                $('#modeSelect').on('change', function () {
                                    $(this).next('.error-message').remove();
                                });

                                $('#dateInput').on('input change', function () {
                                    $(this).next('.error-message').remove();
                                });

                                $('#slotContainer').on('click', 'button', function () {
                                    $('#slotContainer').next('.error-message').remove();
                                });
                            });
                        </script>


                        <!-- Script -->
                        <script>
                            document.getElementById('modeSelect').addEventListener('change', fetchSlots);
                            document.getElementById('dateInput').addEventListener('change', fetchSlots);

                            function fetchSlots() {
                                const mode = document.getElementById('modeSelect').value;
                                const date = document.getElementById('dateInput').value;
                                const assessorId = document.getElementById('assessorName').value;
                                const container = document.getElementById('slotContainer');

                                if (!mode || !date || !assessorId) {
                                    container.innerHTML = '';
                                    return;
                                }

                                const url = `{{ route('get-assessor-available-slots') }}?mode=${mode}&date=${date}&assessor_id=${assessorId}`;

                                fetch(url)
                                    .then(response => response.json())
                                    .then(data => {
                                        container.innerHTML = '';

                                        if (!data.status || data.slots.length === 0) {
                                            container.innerHTML = `<p class="col-span-2 text-center text-gray-500">No slots available</p>`;
                                            return;
                                        }

                                        const bookedSlotIds = data.booked_slot_ids || [];

                                        data.slots.forEach(slot => {
                                            const isUnavailable = slot.is_unavailable;
                                            const isBooked = bookedSlotIds.includes(slot.id);

                                            const btn = document.createElement('button');
                                            btn.type = 'button';

                                            let baseClass = 'border rounded py-2 px-3 w-full';
                                            if (isUnavailable) {
                                                baseClass += ' text-red-600 border-red-500 cursor-not-allowed bg-red-50';
                                            } else if (isBooked) {
                                                baseClass += ' bg-yellow-200 border-yellow-500 text-yellow-800'; // highlight booked
                                            } else {
                                                baseClass += ' text-gray-900';
                                            }

                                            btn.className = baseClass;
                                            btn.disabled = isUnavailable;

                                            btn.innerHTML = `
                                                <p class="font-medium">${slot.start_time} - ${slot.end_time}</p>
                                                <p class="text-xs ${
                                                    isUnavailable ? 'text-red-600' : isBooked ? 'text-yellow-700' : 'text-green-600'
                                                }">${isUnavailable ? 'Unavailable' : isBooked ? 'Already Booked' : 'Available'}</p>
                                            `;

                                            btn.disabled = isUnavailable || isBooked;


                                            if (!isUnavailable) {
                                                btn.setAttribute('data-available', 'true');
                                                btn.setAttribute('data-slot-id', slot.id);
                                                btn.onclick = function () {
                                                    selectTimeSlot(this);
                                                };
                                            }

                                            container.appendChild(btn);
                                        });
                                    })

                                    .catch(error => {
                                        console.error('Failed to fetch slots:', error);
                                        container.innerHTML = `<p class="col-span-2 text-center text-red-600">Error loading slots</p>`;
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
                                const slotTime = selectedButton.querySelector('p.font-medium').innerText;

                                document.getElementById('selectedSlotId').value = slotId;
                                document.getElementById('selectedSlotTime').value = slotTime;
                            }

                            document.querySelector('form').addEventListener('submit', function () {
                                const btn = document.getElementById('bookBtn');
                                btn.disabled = true;
                                btn.innerText = 'Booking...';
                            });
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