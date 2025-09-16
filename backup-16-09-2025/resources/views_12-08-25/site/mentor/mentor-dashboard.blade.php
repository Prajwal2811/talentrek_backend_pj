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


    <!-- Add this script at the end of your body or inside a script tag -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dashboard', () => ({
                init() {
                    feather.replace();
                }
            }))
        });

        // Run feather.replace() on page load
        document.addEventListener('DOMContentLoaded', () => {
            feather.replace();
        });
    </script>

    <div class="flex h-screen" x-data="{ sidebarOpen: true }" x-init="$watch('sidebarOpen', () => feather.replace())">
        <!-- Sidebar -->

        @include('site.mentor.componants.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Navbar -->
            @include('site.mentor.componants.navbar')
            @include('admin.errors')
            <!-- Main -->
            <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="dashboard()">
                <h2 class="text-2xl font-semibold mb-6">Dashboard</h2>
                <!-- Stat Cards -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <p class="text-gray-500">Total upcoming sessions</p>
                        <h3 class="text-3xl font-bold mt-2">24</h3>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <p class="text-gray-500">Today’s sessions</p>
                        <h3 class="text-3xl font-bold mt-2">15</h3>
                    </div>
                </div>

                <!-- Jobseekers contacted -->
                <div x-data="sessionManager()" class="bg-white p-6 rounded-lg shadow relative">
                    <!-- Tabs -->
                    <div class="flex space-x-6 border-b mb-4 text-sm font-medium text-gray-600">
                        <button @click="switchTab('upcoming')" :class="tabClass('upcoming')">Upcoming sessions</button>
                        <button @click="switchTab('cancelled')" :class="tabClass('cancelled')">Cancelled
                            sessions</button>
                        <button @click="switchTab('completed')" :class="tabClass('completed')">Completed
                            sessions</button>
                    </div>
                    <!-- Session List -->
                    <template x-for="(session, index) in paginatedSessions()" :key="index">
                        <div class="flex justify-between items-center border-b py-4">
                            <div class="flex items-center space-x-4">
                                <img :src="session.img" class="w-12 h-12 rounded-full object-cover" alt="Profile" />
                                <div>
                                    <h4 class="font-semibold text-sm" x-text="session.name"></h4>
                                    <p class="text-sm text-gray-500" x-text="session.role"></p>
                                </div>
                            </div>

                            <div class="flex flex-1 justify-evenly  ml-10 text-sm text-gray-700">
                                <div>
                                    <p class="font-semibold">Session Date</p>
                                    <p x-text="session.date"></p>
                                </div>
                                <div>
                                    <p class="font-semibold">Session Time</p>
                                    <p x-text="session.time"></p>
                                </div>
                                <div>
                                    <p class="font-semibold">Mode</p>
                                    <p x-text="session.mode"></p>
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <template x-if="currentTab === 'upcoming'">
                                    <div class="flex space-x-2">
                                        <button
                                            class="border border-red-500 text-red-500 px-4 py-1.5 rounded hover:bg-red-50 text-sm"
                                            @click="openCancelModal(session)">Cancel</button>
                                        <button
                                            class="bg-blue-600 text-white px-4 py-1.5 rounded hover:bg-blue-700 text-sm"
                                            @click="joinSession(session)">Join</button>
                                    </div>
                                </template>

                                <template x-if="currentTab === 'completed'">
                                    <button
                                        class="bg-green-600 text-white px-4 py-1.5 rounded hover:bg-green-700 text-sm"
                                        @click="openFeedbackModal(session)">View Feedback</button>
                                </template>

                                <template x-if="currentTab === 'cancelled'">
                                    <button
                                        class="text-sm text-blue-600 underline hover:text-blue-800"
                                        @click="openCancelReasonModal(session)">
                                        View Cancel Reason
                                    </button>
                                </template>
                                <!-- Cancel Reason Modal -->
                                <div x-show="showCancelReasonModal" x-transition
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                    <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 relative">
                                        <button @click="closeCancelReasonModal"
                                                class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
                                        <h3 class="text-center text-lg font-semibold text-gray-800 mb-4">Cancellation Reason</h3>
                                        <div class="text-center text-gray-700 text-sm">
                                            <img :src="selectedCancelReason?.img" class="w-14 h-14 rounded-full object-cover mx-auto mb-2" />
                                            <h4 class="font-semibold" x-text="selectedCancelReason?.name"></h4>
                                            <div class="mt-4 border-t pt-3">
                                                <p class="text-gray-500">Reason:</p>
                                                <p class="text-gray-800 font-medium mt-1" x-text="selectedCancelReason?.cancellation_reason || 'No reason provided'"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </template>

                    <!-- Pagination -->
                    <div class="flex justify-end mt-6 space-x-2 items-center">
                        <button @click="prevPage()" :disabled="currentPage === 1"
                            class="p-1.5 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <template x-for="page in totalPages()" :key="page">
                            <button @click="currentPage = page"
                                :class="{'bg-blue-600 text-white': currentPage === page, 'bg-gray-200': currentPage !== page}"
                                class="px-3 py-1 rounded">
                                <span x-text="page"></span>
                            </button>
                        </template>
                        <button @click="nextPage()" :disabled="currentPage === totalPages()"
                            class="p-1.5 rounded-full bg-gray-200 hover:bg-gray-300 disabled:opacity-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>

                    <!-- Feedback Modal -->
                    <div x-show="showFeedbackModal" x-transition
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                        <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 relative">
                            <button @click="closeFeedbackModal"
                                class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
                            <h3 class="text-center text-lg font-semibold text-gray-800 mb-6">Jobseekers feedback</h3>
                            <div class="flex items-center justify-center flex-col mb-4 text-center">
                                <img :src="selectedFeedback?.img" class="w-16 h-16 rounded-full object-cover mb-2"
                                    alt="Profile" />
                                <h4 class="font-semibold text-base text-gray-800" x-text="selectedFeedback?.name"></h4>
                                <p class="text-sm text-gray-500" x-text="selectedFeedback?.role"></p>
                            </div>
                            <p class="text-sm text-gray-700 text-center leading-relaxed"
                                x-text="selectedFeedback?.feedback"></p>
                            <div class="flex justify-center mt-6">
                                <button
                                    class="px-6 py-2 bg-blue-700 text-white rounded-md hover:bg-blue-800 text-sm">Reply</button>
                            </div>
                        </div>
                    </div>

                    <!-- Cancel Modal -->
                    <div x-show="showCancelModal" x-transition 
                        x-data="{
                            cancelReason: '',
                            postponeSession: 'No',
                            newDate: '',
                            newTime: '',
                            get disableReschedule() {
                                return this.cancelReason.trim().length > 0;
                            },
                            get disableCancel() {
                                return this.postponeSession === 'Yes' || this.newDate !== '' || this.newTime !== '';
                            }
                        }" 
                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

                        <form id="basic-form" method="POST" novalidate action="{{ route('mentor.dashboard-action') }}">
                            @csrf
                            <input type="text" name="session_id" :value="session_id">
                            <input type="text" name="cancel_reason" :value="cancelReason">
                            <input type="text" name="postpone_session" :value="postponeSession">
                            <input type="text" name="new_date" :value="newDate">
                            <input type="text" name="new_time" :value="newTime">
                            <input type="text" name="action_type" x-ref="actionType">

                            <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-6 relative">
                                <!-- Header -->
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-md font-semibold text-gray-800">
                                        Cancel this session -
                                        <span class="font-normal text-gray-600"
                                            x-text="cancelledSession?.name + ' / Date: ' + cancelledSession?.date + ' / Time: ' + cancelledSession?.time"></span>
                                    </h3>
                                    <button type="button" @click="closeCancelModal"
                                        class="text-gray-500 hover:text-gray-700 text-xl leading-none">&times;</button>
                                </div>

                                <!-- Reason -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason for cancellation</label>
                                    <textarea x-model="cancelReason"
                                        class="w-full border border-gray-300 rounded-md p-2 text-sm resize-none" rows="3"
                                        placeholder="Write here..."></textarea>
                                </div>

                                <!-- Reschedule Options -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end mb-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Postpone session</label>
                                        <select x-model="postponeSession"
                                            class="w-full border border-gray-300 rounded-md p-2 text-sm">
                                            <option>No</option>
                                            <option>Yes</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Select session date</label>
                                        <input type="date" x-model="newDate"
                                            :disabled="postponeSession !== 'Yes'"
                                            class="w-full border border-gray-300 rounded-md p-2 text-sm" />
                                    </div>

                                    @php
                                        use Carbon\Carbon;
                                        $slots = App\Models\BookingSlot::where('user_id', auth()->id())
                                                            ->where('user_type', 'mentor')
                                                            ->get();
                                    @endphp

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Select session time</label>
                                        <select x-model="newTime"
                                            :disabled="postponeSession !== 'Yes'"
                                            class="w-full border border-gray-300 rounded-md p-2 text-sm">
                                            <option value="">Select time</option>
                                            @if($slots->isEmpty())
                                                <option disabled>No slots available</option>
                                            @else
                                                @foreach ($slots as $slot)
                                                    <option value="{{ $slot->start_time.'-'.$slot->end_time }}">
                                                        {{ Carbon::parse($slot->start_time)->format('h:i A') }}
                                                        -
                                                        {{ Carbon::parse($slot->end_time)->format('h:i A') }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>


                                <!-- Action Buttons -->
                                <div class="flex justify-end space-x-3">
                                    <!-- Cancel -->
                                    <button type="button"
                                        :disabled="disableCancel"
                                        @click="
                                            if (!disableCancel) {
                                                $refs.actionType.value = 'cancel';
                                                $event.target.closest('form').submit();
                                            }
                                        "
                                        class="px-6 py-2 bg-red-600 text-white rounded-md text-sm hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                        Cancel Session
                                    </button>

                                    <!-- Reschedule -->
                                    <button type="button"
                                        :disabled="disableReschedule"
                                        @click="
                                            if (!disableReschedule) {
                                                $refs.actionType.value = 'reschedule';
                                                $event.target.closest('form').submit();
                                            }
                                        "
                                        class="px-6 py-2 bg-gray-700 text-white rounded-md text-sm hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed">
                                        Reschedule
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>


                </div>

                <script>
                    window.mentorSessions = {
                        upcoming: @json($upcoming),
                        cancelled: @json($cancelled),
                        completed: @json($completed),
                    };
                </script>
                <script>
                    function sessionManager() {
                        return {
                            currentTab: 'upcoming',
                            currentPage: 1,
                            perPage: 4,
                            cancelledSession: null,
                            showCancelModal: false,
                            showFeedbackModal: false,
                            selectedFeedback: null,
                            session_id: '', // This will hold the session ID for the form submission
                            cancelReason: '',
                            postponeSession: 'No',
                            newDate: '',
                            newTime: '',

                            sessions: window.mentorSessions,

                            switchTab(tab) {
                                this.currentTab = tab;
                                this.currentPage = 1;
                            },
                            tabClass(tab) {
                                return this.currentTab === tab
                                    ? 'text-blue-600 border-b-2 border-blue-600 pb-2'
                                    : 'pb-2 hover:text-blue-600';
                            },
                            paginatedSessions() {
                                const list = this.sessions[this.currentTab] || [];
                                const start = (this.currentPage - 1) * this.perPage;
                                return list.slice(start, start + this.perPage);
                            },
                            totalPages() {
                                const list = this.sessions[this.currentTab] || [];
                                return Math.ceil(list.length / this.perPage) || 1;
                            },
                            prevPage() {
                                if (this.currentPage > 1) this.currentPage--;
                            },
                            nextPage() {
                                if (this.currentPage < this.totalPages()) this.currentPage++;
                            },
                            joinSession(session) {
                                alert(`Joining session with ${session.name}`);
                            },
                            openCancelModal(session) {
                                console.log("Session:", session); // Debug
                                this.cancelledSession = session;
                                this.session_id = session.session_id; // ✅ Make sure this line exists
                                this.showCancelModal = true;
                            },
                            closeCancelModal() {
                                this.cancelledSession = null;
                                this.showCancelModal = false;
                                this.cancelReason = '';
                                this.postponeSession = 'No';
                                this.newDate = '';
                                this.newTime = '';
                            },
                            confirmCancel() {
                                this.sessions.upcoming = this.sessions.upcoming.filter(s => s !== this.cancelledSession);
                                this.sessions.cancelled.push(this.cancelledSession);
                                this.closeCancelModal();
                            },
                            confirmReschedule() {
                                alert(`Rescheduling ${this.cancelledSession.name} to ${this.newDate} ${this.newTime}`);
                                this.closeCancelModal();
                            },
                            openFeedbackModal(session) {
                                this.selectedFeedback = session;
                                this.showFeedbackModal = true;
                            },
                            closeFeedbackModal() {
                                this.selectedFeedback = null;
                                this.showFeedbackModal = false;
                            },
                            showCancelReasonModal: false,
                            selectedCancelReason: null,

                            openCancelReasonModal(session) {
                                this.selectedCancelReason = session;
                                this.showCancelReasonModal = true;
                            },
                            closeCancelReasonModal() {
                                this.selectedCancelReason = null;
                                this.showCancelReasonModal = false;
                            }

                        }
                    }
                </script>
            </main>
        </div>
    </div>

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script>feather.replace();</script>



@include('site.mentor.componants.footer')