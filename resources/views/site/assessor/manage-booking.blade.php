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
            @include('site.assessor.componants.sidebar')
            <div class="flex-1 flex flex-col">
            @include('site.assessor.componants.navbar')
            @include('admin.errors')
            <script src="//unpkg.com/alpinejs" defer></script>

            <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="createBookingSlots()">
                <!-- ✅ Success Message -->
                <div 
                    x-show="successMessage"
                    x-text="successMessage"
                    class="mb-4 p-2 rounded bg-green-100 text-green-800 border border-green-300 transition"
                    x-transition
                ></div>

                <h2 class="text-2xl font-semibold mb-4">Booking slots</h2>
                <div class="flex gap-6">
                    <!-- ✅ Left: Calendar -->
                    <div class="bg-white p-4 rounded-lg shadow w-1/3">
                        <h3 class="text-lg font-semibold mb-3">Date</h3>
                        <div class="border p-2 rounded">
                            <!-- Month and Year Navigation -->
                            <div class="flex justify-between items-center mb-2 gap-2">
                                <!-- Month Dropdown -->
                                <select x-model="currentMonth" class="text-sm border rounded px-2 py-1">
                                    <template x-for="(month, index) in monthNames" :key="index">
                                        <option :value="index" x-text="month"></option>
                                    </template>
                                </select>

                                <!-- Year Dropdown -->
                                <select x-model="currentYear" class="text-sm border rounded px-2 py-1">
                                    <template x-for="year in yearRange" :key="year">
                                        <option :value="year" x-text="year"></option>
                                    </template>
                                </select>
                            </div>


                            <!-- Weekdays -->
                            <div class="grid grid-cols-7 text-center text-sm text-gray-600 mb-2">
                                <template x-for="day in weekDays" :key="day">
                                    <div x-text="day"></div>
                                </template>
                            </div>

                            <!-- Calendar Days -->
                            <div class="grid grid-cols-7 text-center text-sm gap-1">
                                <template x-for="blank in blanks" :key="'b'+blank">
                                    <div></div>
                                </template>
                                <template x-for="day in daysInMonth" :key="day">
                                    <button
                                        @click="selectedDate = day"
                                        class="py-1 rounded w-full"
                                        :class="{
                                            'bg-primary text-white font-bold': isToday(day),
                                            'bg-blue-100': selectedDate === day && !isToday(day) && !areAllSlotsUnavailable(day),
                                            'bg-red-200 text-red-800 font-semibold': areAllSlotsUnavailable(day),
                                            'hover:bg-blue-200': !areAllSlotsUnavailable(day),
                                            'line-through text-gray-400': isUnavailable(day)
                                        }"

                                        x-text="day"
                                    ></button>

                                </template>
                            </div>

                            <button class="mt-3 w-full py-2 bg-red-100 text-red-600 rounded border border-red-300 hover:bg-red-200"
                                @click="markUnavailable">
                                Mark unavailable
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            Note: To mark the date unavailable, select the date and click on "Mark unavailable".
                        </p>
                    </div>

                    <!-- ✅ Right: Time Slots -->
                    <div class="bg-white p-4 rounded-lg shadow w-2/3">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Time slots</h3>
                            <div>
                                <button 
                                    @click="activeMode = 'online'"
                                    :class="activeMode === 'online' ? 'text-blue-600 font-medium mr-2 border-b-2 border-blue-600' : 'text-gray-400 font-medium mr-2'">
                                    Online mode
                                </button>
                                <button 
                                    @click="activeMode = 'offline'"
                                    :class="activeMode === 'offline' ? 'text-blue-600 font-medium border-b-2 border-blue-600' : 'text-gray-400 font-medium'">
                                    Offline mode
                                </button>
                            </div>
                        </div>

                        <div class="text-sm text-gray-700 mb-2">
                            Date: <span class="font-medium" x-text="selectedDateFormatted()"></span>
                        </div>

                        <table class="w-full table-auto border text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left">Sr. No.</th>
                                    <th class="px-4 py-2 text-left">Time</th>
                                    <th class="px-4 py-2 text-left">Availability</th>
                                    <th class="px-4 py-2 text-left">Edit</th>
                                    <th class="px-4 py-2 text-left">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(slot, index) in activeSlots" :key="index">
                                    <tr class="border-b">
                                        <td class="px-4 py-2" x-text="index + 1"></td>
                                        <td class="px-4 py-2" x-text="slot.start_time + ' - ' + slot.end_time"></td>
                                        <td class="px-4 py-2">
                                            <div class="flex items-center gap-2" x-data="{ updateUrl: @js(route('assessor.update-slot-status')) }">
                                                <input 
                                                    type="checkbox" 
                                                    :checked="!isSlotUnavailable(slot.id)"
                                                    @change="
                                                        if (!selectedDate) {
                                                            alert('Please select a date first.');
                                                            $event.target.checked = !$event.target.checked;
                                                            return;
                                                        }

                                                        const selectedFullDate = new Date(currentYear, currentMonth, selectedDate);
                                                        const formattedDate = selectedFullDate.toISOString().split('T')[0];
                                                        const isAvailable = $event.target.checked ? 1 : 0;

                                                        fetch(updateUrl, {
                                                            method: 'POST',
                                                            headers: {
                                                                'Content-Type': 'application/json',
                                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                            },
                                                            body: JSON.stringify({
                                                                slot_id: slot.id,
                                                                is_available: isAvailable,
                                                                date: formattedDate
                                                            })
                                                        })
                                                        .then(res => res.json())
                                                        .then(data => {
                                                            if (isAvailable === 0) {
                                                                if (!unavailableDatesMap[slot.id]) unavailableDatesMap[slot.id] = [];
                                                                if (!unavailableDatesMap[slot.id].includes(formattedDate)) {
                                                                    unavailableDatesMap[slot.id].push(formattedDate);
                                                                }
                                                            } else {
                                                                if (unavailableDatesMap[slot.id]) {
                                                                    unavailableDatesMap[slot.id] = unavailableDatesMap[slot.id].filter(d => d !== formattedDate);
                                                                }
                                                            }
                                                            successMessage = 'Slot status updated successfully!';
                                                            setTimeout(() => successMessage = '', 3000);
                                                        })
                                                        .catch(err => console.error('Update failed', err));
                                                    "
                                                    class="toggle-checkbox"
                                                />
                                                <span 
                                                    :class="!isSlotUnavailable(slot.id) ? 'text-green-600' : 'text-red-600'" 
                                                    x-text="!isSlotUnavailable(slot.id) ? 'Available' : 'Unavailable'"
                                                ></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-2">
                                            <button 
                                                class="bg-blue-800 text-white hover:bg-blue-700 rounded-full p-2"
                                                @click="openEditModal(slot)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>

                            
                                        <td class="px-4 py-2">
                                            <button  class="bg-red-800 text-white hover:bg-red-700 rounded-full p-2"  @click="prepareDelete(slot.id)" >
                                                <i class="fas fa-trash-alt"></i>
                                            </button>

                                        </td>
                                    </tr>
                                </template>
                                <!-- Edit Slot Modal -->
                                <div 
                                    x-show="showEditModal" 
                                    x-transition 
                                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                                    style="display: none;"
                                >
                                    <div 
                                        class="bg-white rounded-lg p-6 w-full max-w-md" 
                                        @click.outside="showEditModal = false"
                                    >
                                        <h2 class="text-lg font-semibold mb-4">Edit Slot Time</h2>
                                        <form @submit.prevent="updateSlotTime">
                                            <!-- Start Time -->
                                            <div class="mb-4">
                                                <label class="block text-sm mb-1">Start Time</label>
                                                <select x-model="editSlot.start_time" class="w-full border rounded px-3 py-2" required>
                                                    <template x-for="time in timeOptions" :key="'start-' + time">
                                                        <option :value="time" x-text="time"></option>
                                                    </template>
                                                </select>
                                            </div>

                                            <!-- End Time -->
                                            <div class="mb-4">
                                                <label class="block text-sm mb-1">End Time</label>
                                                <select x-model="editSlot.end_time" class="w-full border rounded px-3 py-2" required>
                                                    <template x-for="time in timeOptions" :key="'end-' + time">
                                                        <option :value="time" x-text="time"></option>
                                                    </template>
                                                </select>
                                            </div>

                                            <!-- Buttons -->
                                            <div class="flex justify-end gap-2">
                                                <button type="button" class="bg-gray-300 px-4 py-2 rounded" @click="showEditModal = false">Cancel</button>
                                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- ✅ Delete Confirmation Modal -->
                                <div 
                                    x-show="showDeleteModal" 
                                    x-transition 
                                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                                    style="display: none;"
                                >
                                    <div class="bg-white rounded-lg p-6 w-full max-w-md" @click.outside="showDeleteModal = false">
                                        <h2 class="text-lg font-semibold mb-4 text-red-700">Confirm Deletion</h2>
                                        <p class="text-sm text-gray-700 mb-6">Are you sure you want to delete this slot? This action cannot be undone.</p>
                                        <div class="flex justify-end gap-3">
                                            <button class="bg-gray-300 px-4 py-2 rounded" @click="showDeleteModal = false">Cancel</button>
                                            <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" @click="confirmDelete">Delete</button>
                                        </div>
                                    </div>
                                </div>


                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
            <script>
                window.bookingData = {
                    onlineSlots: @json($onlineSlots),
                    offlineSlots: @json($offlineSlots),
                    unavailableDatesMap: @json($unavailableDatesMap),
                };

                function createBookingSlots() {
    const today = new Date();
    const thisYear = today.getFullYear();

    return {
        // ✅ Default selections
        today: today,
        currentMonth: today.getMonth(),   // 0–11 → used in dropdown
        currentYear: thisYear,                   // e.g. 2025
        selectedDate: today.getDate(),          // Select today's date initially

        // ✅ Dropdown options
        monthNames: ['January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'],
        weekDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
        yearRange: Array.from({ length: 10 }, (_, i) => thisYear - 5 + i), // ±5 year range

        // ✅ Booking Data
        activeMode: 'online',
        onlineSlots: window.bookingData?.onlineSlots || [],
        offlineSlots: window.bookingData?.offlineSlots || [],
        unavailableDatesMap: window.bookingData?.unavailableDatesMap || {},
        successMessage: '',

        // ✅ Calendar Logic
        get daysInMonth() {
            return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
        },
        get blanks() {
            return new Date(this.currentYear, this.currentMonth, 1).getDay();
        },
        isToday(day) {
            const d = new Date(this.currentYear, this.currentMonth, day);
            return d.toDateString() === this.today.toDateString();
        },
        selectedDateFormatted() {
            if (!this.selectedDate) return 'Select a date';
            const date = new Date(this.currentYear, this.currentMonth, this.selectedDate);
            return date.toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' });
        },

        get activeSlots() {
            return this.activeMode === 'online' ? this.onlineSlots : this.offlineSlots;
        },
        isSlotUnavailable(slotId) {
            if (!this.selectedDate) return false;
            const selected = new Date(this.currentYear, this.currentMonth, this.selectedDate);
            const formattedDate = selected.toISOString().split('T')[0];
            return (this.unavailableDatesMap[slotId] || []).includes(formattedDate);
        },
        isUnavailable(day) {
            const dateObj = new Date(this.currentYear, this.currentMonth, day);
            const formattedDate = dateObj.toISOString().split('T')[0];
            return this.activeSlots.some(slot => {
                const slotUnavailableDates = this.unavailableDatesMap[slot.id] || [];
                return slotUnavailableDates.includes(formattedDate);
            });
        },
        areAllSlotsUnavailable(day) {
            const dateObj = new Date(this.currentYear, this.currentMonth, day);
            const formattedDate = dateObj.toISOString().split('T')[0];
            return this.activeSlots.length > 0 && this.activeSlots.every(slot => {
                const slotUnavailableDates = this.unavailableDatesMap[slot.id] || [];
                return slotUnavailableDates.includes(formattedDate);
            });
        },

        markUnavailable() {
            if (!this.selectedDate) {
                alert('Please select a date first');
                return;
            }

            const selectedFullDate = new Date(this.currentYear, this.currentMonth, this.selectedDate);
            const formattedDate = selectedFullDate.toISOString().split('T')[0];

            const allSlots = this.activeMode === 'online' ? this.onlineSlots : this.offlineSlots;
            const updateUrl = '{{ route('assessor.update-slot-status') }}';

            Promise.all(
                allSlots.map(slot => {
                    if (!this.unavailableDatesMap[slot.id]) {
                        this.unavailableDatesMap[slot.id] = [];
                    }

                    if (!this.unavailableDatesMap[slot.id].includes(formattedDate)) {
                        this.unavailableDatesMap[slot.id].push(formattedDate);
                    }

                    return fetch(updateUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            slot_id: slot.id,
                            is_available: 0,
                            date: formattedDate
                        })
                    });
                })
            )
            .then(() => {
                this.successMessage = 'All slots marked as unavailable for selected date!';
                setTimeout(() => this.successMessage = '', 3000);
            })
            .catch(err => {
                console.error('Failed to mark slots as unavailable', err);
                alert('Something went wrong. Please try again.');
            });
        },

        showEditModal: false,
        editSlot: {
            id: null,
            start_time: '',
            end_time: ''
        },
        openEditModal(slot) {
            this.editSlot = {
                id: slot.id,
                start_time: this.formatTime12Hour(slot.start_time),
                end_time: this.formatTime12Hour(slot.end_time)
            };
            this.showEditModal = true;
        },
        formatTime12Hour(time24) {
            const [hourStr, minuteStr] = time24.split(':');
            let hour = parseInt(hourStr);
            const minute = minuteStr;
            const ampm = hour >= 12 ? 'pm' : 'am';
            hour = hour % 12;
            hour = hour === 0 ? 12 : hour;
            return `${hour.toString().padStart(2, '0')}:${minute} ${ampm}`;
        },
        updateSlotTime() {
            fetch('{{ route('assessor.update-slot-time') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(this.editSlot)
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const slots = this.activeMode === 'online' ? this.onlineSlots : this.offlineSlots;
                    const idx = slots.findIndex(s => s.id === this.editSlot.id);
                    if (idx !== -1) {
                        slots[idx].start_time = this.editSlot.start_time;
                        slots[idx].end_time = this.editSlot.end_time;
                    }
                    this.successMessage = 'Slot time updated successfully!';
                    setTimeout(() => this.successMessage = '', 3000);
                    this.showEditModal = false;
                }
            })
            .catch(err => {
                console.error('Failed to update slot time', err);
            });
        },

        // Delete
        deleteSlotId: null,
        showDeleteModal: false,
        prepareDelete(slotId) {
            this.deleteSlotId = slotId;
            this.showDeleteModal = true;
        },
        confirmDelete() {
            fetch('{{ route('assessor.delete-slot') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: this.deleteSlotId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const slotArray = this.activeMode === 'online' ? this.onlineSlots : this.offlineSlots;
                    const index = slotArray.findIndex(s => s.id === this.deleteSlotId);
                    if (index !== -1) {
                        slotArray.splice(index, 1);
                    }
                    delete this.unavailableDatesMap[this.deleteSlotId];
                    this.successMessage = 'Slot deleted successfully!';
                    setTimeout(() => this.successMessage = '', 3000);
                }
                this.showDeleteModal = false;
                this.deleteSlotId = null;
            })
            .catch(err => {
                console.error('Delete failed', err);
                alert('Something went wrong. Try again.');
                this.showDeleteModal = false;
                this.deleteSlotId = null;
            });
        },

        // Time options
        timeOptions: [
            // AM
            '01:00 am', '01:30 am', '02:00 am', '02:30 am', '03:00 am', '03:30 am',
            '04:00 am', '04:30 am', '05:00 am', '05:30 am', '06:00 am', '06:30 am',
            '07:00 am', '07:30 am', '08:00 am', '08:30 am', '09:00 am', '09:30 am',
            '10:00 am', '10:30 am', '11:00 am', '11:30 am', '12:00 pm',

            // PM
            '12:30 pm', '01:00 pm', '01:30 pm', '02:00 pm', '02:30 pm', '03:00 pm',
            '03:30 pm', '04:00 pm', '04:30 pm', '05:00 pm', '05:30 pm', '06:00 pm',
            '06:30 pm', '07:00 pm', '07:30 pm', '08:00 pm', '08:30 pm', '09:00 pm',
            '09:30 pm', '10:00 pm', '10:30 pm', '11:00 pm', '11:30 pm', '12:00 am'
        ]
    };
}


            </script>




            <style>
                .toggle-checkbox {
                    appearance: none;
                    width: 34px;
                    height: 18px;
                    background-color: #ccc;
                    border-radius: 9999px;
                    position: relative;
                    outline: none;
                    cursor: pointer;
                }
                .toggle-checkbox:checked {
                    background-color: #4ade80;
                }
                .toggle-checkbox:checked::before {
                    transform: translateX(16px);
                }
                .toggle-checkbox::before {
                    content: '';
                    position: absolute;
                    top: 2px;
                    left: 2px;
                    width: 14px;
                    height: 14px;
                    background-color: white;
                    border-radius: 9999px;
                    transition: transform 0.3s ease;
                }
            </style>

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
