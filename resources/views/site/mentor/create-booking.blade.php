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

	
    <div class="page-wraper">
        <div class="flex h-screen">
            @include('site.mentor.componants.sidebar')    

            <div class="flex-1 flex flex-col">
                @include('site.mentor.componants.navbar')
                <main class="p-6 bg-gray-100 flex-1 overflow-y-auto">
    <h2 class="text-2xl font-semibold mb-1">Booking slots</h2>

    <div class="bg-white rounded-lg shadow mt-6">
        <div class="border-b p-4 font-medium">Create time slots</div>

        <form id="slot-form" method="POST" action="{{ route('mentor.submit-bookings') }}" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="p-6 space-y-4">
                <!-- Select inputs -->
                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Coaching mode</label>
                        <select id="mode" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                            <option value="">Online/offline</option>
                            <option value="online">Online</option>
                            <option value="offline">Offline</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Start time</label>
                        <select id="startTime" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                            <option value="">Select start time</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">End time</label>
                        <select id="endTime" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                            <option value="">Select end time</option>
                        </select>
                    </div>
                </div>

                <!-- Error Message -->
                <div id="errorMessage" class="text-red-600 font-medium mt-2"></div>

                <!-- Create button -->
                <button type="button" onclick="addSlot()" class="bg-blue-700 text-white px-6 py-2 rounded-md">Create</button>

                <!-- Created slots -->
                <div id="slotContainer" class="flex flex-wrap gap-3 mt-4"></div>

                <!-- Submit button aligned right -->
                <div class="text-right mt-4">
                    <button type="submit" onclick="prepareForm()" class="bg-blue-700 text-white px-6 py-2 rounded-md">Submit</button>
                </div>
            </div>
        </form>

        <!-- Script -->
        <script>
            const timeOptions = [
                '01:00 am', '01:30 am', '02:00 am', '02:30 am',
                '03:00 am', '03:30 am', '04:00 am', '04:30 am',
                '05:00 am', '05:30 am', '06:00 am', '06:30 am',
                '07:00 am', '07:30 am', '08:00 am', '08:30 am',
                '09:00 am', '09:30 am', '10:00 am', '10:30 am',
                '11:00 am', '11:30 am', '12:00 pm'
            ];

            const startSelect = document.getElementById('startTime');
            const endSelect = document.getElementById('endTime');
            const slotContainer = document.getElementById('slotContainer');
            const errorMessage = document.getElementById('errorMessage');
            const form = document.getElementById('slot-form');
            const slots = [];

            function populateTimeOptions() {
                timeOptions.forEach(time => {
                    const option1 = document.createElement('option');
                    option1.value = time;
                    option1.textContent = time;
                    startSelect.appendChild(option1);

                    const option2 = document.createElement('option');
                    option2.value = time;
                    option2.textContent = time;
                    endSelect.appendChild(option2);
                });
            }

            function showError(message) {
                errorMessage.textContent = message;
            }

            function clearError() {
                errorMessage.textContent = '';
            }

            function addSlot() {
                const start = startSelect.value;
                const end = endSelect.value;

                clearError();

                if (!start || !end) {
                    showError("Please select both start and end time.");
                    return;
                }

                if (start === end) {
                    showError("Start time and end time cannot be the same.");
                    return;
                }

                const startIndex = timeOptions.indexOf(start);
                const endIndex = timeOptions.indexOf(end);

                if (startIndex >= endIndex) {
                    showError("Start time must be before end time.");
                    return;
                }

                const label = `${start} - ${end}`;
                if (slots.includes(label)) {
                    showError("This time slot already exists.");
                    return;
                }

                slots.push(label);

                const slotDiv = document.createElement('div');
                slotDiv.className = "flex items-center border border-blue-700 rounded px-3 py-1 text-sm";

                const span = document.createElement('span');
                span.textContent = label;
                span.className = "mr-2";

                const button = document.createElement('button');
                button.type = "button";
                button.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                `;
                button.onclick = () => {
                    slotContainer.removeChild(slotDiv);
                    const index = slots.indexOf(label);
                    if (index > -1) slots.splice(index, 1);
                };

                slotDiv.appendChild(span);
                slotDiv.appendChild(button);
                slotContainer.appendChild(slotDiv);

                startSelect.value = '';
                endSelect.value = '';
            }

            function prepareForm() {
                // Clear any previous hidden inputs
                document.querySelectorAll('input[name="slots[]"], input[name="mode"]').forEach(el => el.remove());

                // Add slot inputs
                slots.forEach(slot => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'slots[]';
                    input.value = slot;
                    form.appendChild(input);
                });

                // Add mode input
                const mode = document.getElementById('mode').value;
                if (mode) {
                    const modeInput = document.createElement('input');
                    modeInput.type = 'hidden';
                    modeInput.name = 'mode';
                    modeInput.value = mode;
                    form.appendChild(modeInput);
                }
            }

            document.addEventListener("DOMContentLoaded", populateTimeOptions);
        </script>
    </div>
</main>


                




                

            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
            </div>
        </div>

        <!-- Feather Icons -->
        <script>
            feather.replace()
        </script>


    </div>
           



          


<script  src="js/jquery-3.6.0.min.js"></script><!-- JQUERY.MIN JS -->
<script  src="js/popper.min.js"></script><!-- POPPER.MIN JS -->
<script  src="js/bootstrap.min.js"></script><!-- BOOTSTRAP.MIN JS -->
<script  src="js/magnific-popup.min.js"></script><!-- MAGNIFIC-POPUP JS -->
<script  src="js/waypoints.min.js"></script><!-- WAYPOINTS JS -->
<script  src="js/counterup.min.js"></script><!-- COUNTERUP JS -->
<script  src="js/waypoints-sticky.min.js"></script><!-- STICKY HEADER -->
<script  src="js/isotope.pkgd.min.js"></script><!-- MASONRY  -->
<script  src="js/imagesloaded.pkgd.min.js"></script><!-- MASONRY  -->
<script  src="js/owl.carousel.min.js"></script><!-- OWL  SLIDER  -->
<script  src="js/theia-sticky-sidebar.js"></script><!-- STICKY SIDEBAR  -->
<script  src="js/lc_lightbox.lite.js" ></script><!-- IMAGE POPUP -->
<script  src="js/bootstrap-select.min.js"></script><!-- Form js -->
<script  src="js/dropzone.js"></script><!-- IMAGE UPLOAD  -->
<script  src="js/jquery.scrollbar.js"></script><!-- scroller -->
<script  src="js/bootstrap-datepicker.js"></script><!-- scroller -->
<script  src="js/jquery.dataTables.min.js"></script><!-- Datatable -->
<script  src="js/dataTables.bootstrap5.min.js"></script><!-- Datatable -->
<script  src="js/chart.js"></script><!-- Chart -->
<script  src="js/bootstrap-slider.min.js"></script><!-- Price range slider -->
<script  src="js/swiper-bundle.min.js"></script><!-- Swiper JS -->
<script  src="js/custom.js"></script><!-- CUSTOM FUCTIONS  -->
<script  src="js/switcher.js"></script><!-- SHORTCODE FUCTIONS  -->


</body>


<!-- Mirrored from thewebmax.org/jobzilla/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 May 2025 07:18:30 GMT -->
</html>
