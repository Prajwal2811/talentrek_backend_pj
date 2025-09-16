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

    @if($trainerNeedsSubscription)
        @include('site.trainer.subscription.index')
    @endif


    
    
    <div class="page-wraper">
        <div class="flex h-screen" x-data="{ sidebarOpen: true }" x-init="$watch('sidebarOpen', () => feather.replace())">
          
            @include('site.trainer.componants.sidebar')

            <div class="flex-1 flex flex-col">
                @include('site.trainer.componants.navbar')
                @include('admin.errors')
            <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="dashboard()">
                <h2 class="text-2xl font-semibold mb-6">Dashboard</h2>

                <!-- Stat Cards -->
                <div class="grid grid-cols-12 gap-4 mb-6">
                    <!-- First two cards side by side in col-span-6 -->
                    <div class="col-span-6 grid grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-lg shadow text-sm">
                            <p class="text-gray-700">Total courses</p>
                            <h3 class="text-3xl font-semibold mt-1">24</h3>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow text-sm">
                            <p class="text-gray-700">Enrolled jobseekers</p>
                            <h3 class="text-3xl font-semibold mt-1">310</h3>
                        </div>
                    </div>

                    <!-- Third card with counts inline (col-span-6) -->
                    <div class="col-span-6">
                        <div class="bg-white p-4 rounded-lg shadow text-sm flex justify-between items-start">
                            <div>
                                <p class="text-gray-700">Total upcoming batches</p>
                                <h3 class="text-3xl font-semibold mt-1">5</h3>
                            </div>
                            <div class="mt-6 space-x-4 text-sm mt-5">
                                <span class="text-green-600 font-medium">Completed: <span class="font-bold">2</span></span>
                                <span class="text-red-500 font-medium">Pending: <span class="font-bold">3</span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    window.jobseekersFromLaravel = @json($jobseekersData);
                    window.sessionsFromLaravel = @json($batches);
                </script>
               
                <div x-data="dashboard()" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Enrolled Jobseekers -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-base font-semibold mb-3">Enrolled jobseekers</h3>
                        <div class="divide-y">
                            <template x-for="jobseeker in visibleJobseekers" :key="jobseeker.id">
                                <div class="flex justify-between items-center py-3">
                                    <div class="flex items-center space-x-3">
                                        <img :src="jobseeker.avatar" class="w-10 h-10 rounded-full object-cover" alt="Profile" />
                                        <div>
                                            <h4 class="font-medium text-sm" x-text="jobseeker.name"></h4>
                                            <p class="text-xs text-gray-500" x-text="jobseeker.designation"></p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600" x-text="`Enrollment No: ${jobseeker.enrollmentNo}`"></p>
                                </div>
                            </template>
                        </div>

                        <div class="pt-4 text-sm text-blue-600 text-right">
                            <button x-show="visibleJobseekers.length < jobseekers.length" @click="loadMoreJobseekers()" class="hover:underline">See all</button>
                        </div>
                    </div>


                    <!-- Today's Sessions -->
                     <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-base font-semibold mb-3">Today's batches</h3>
                        <div class="divide-y">
                            <template x-for="session in visibleSessions" :key="session.id">
                                <div class="flex justify-between items-center py-3">
                                    <div>
                                        <h4 class="text-sm font-medium" x-text="session.title + ' - ' + session.training_level"></h4>
                                        <p class="text-xs text-gray-500">
                                            Time: <span x-text="session.time"></span> &nbsp;&nbsp;
                                            Batch: <span x-text="session.batch"></span>
                                        </p>
                                    </div>
                                    <button class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700">Join</button>
                                </div>
                            </template>
                        </div>
                        <div class="pt-4 text-sm text-blue-600 text-right">
                            <button x-show="visibleSessions.length < sessions.length" @click="loadMoreSessions()" class="hover:underline">See all</button>
                        </div>
                    </div>

                </div>

                <script>
                    function dashboard() {
                        return {
                            jobseekers: window.jobseekersFromLaravel || [],
                            sessions: (window.sessionsFromLaravel || []).map((session, index) => ({
                                id: session.id ?? index, // Unique ID fallback
                                title: session.training_name ?? 'No Title',
                                time: formatTime(session.start_timing ?? '00:00:00'),
                                batch: session.batch_no ?? 'Batch N/A',
                                training_level: session.training_level ?? 'Level N/A'
                            })),
                            visibleSessions: [],
                            limit: 5,

                            init() {
                                this.visibleJobseekers = this.jobseekers.slice(0, this.limit);
                                 this.visibleSessions = this.sessions.slice(0, this.limit);
                            },

                            loadMoreJobseekers() {
                                const next = this.visibleJobseekers.length + this.limit;
                                this.visibleJobseekers = this.jobseekers.slice(0, next);
                            },

                            loadMoreSessions() {
                                const next = this.visibleSessions.length + this.limit;
                                this.visibleSessions = this.sessions.slice(0, next);
                            }
                        }
                    }

                    // Optional helper function to format time to 12-hour format
                    function formatTime(timeStr) {
                        if (!timeStr) return 'Invalid';
                        const [hours, minutes] = timeStr.split(':');
                        const date = new Date();
                        date.setHours(hours, minutes);
                        return date.toLocaleTimeString('en-US', {
                            hour: '2-digit',
                            minute: '2-digit'
                        }).toLowerCase();
                    }

                </script>




            
            </main>

            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
            <script src="https://cdn.tailwindcss.com"></script>       



            </div>
        </div>

        <!-- Feather Icons -->
        <script src="https://unpkg.com/feather-icons"></script>
        <script>
            feather.replace()
        </script>


    </div>





          
@include('site.trainer.componants.footer')