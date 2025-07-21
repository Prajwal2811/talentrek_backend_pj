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
        <div class="flex h-screen" x-data="{ sidebarOpen: true }" x-init="$watch('sidebarOpen', () => feather.replace())">
          
            @include('site.trainer.componants.sidebar')

            <div class="flex-1 flex flex-col">
                @include('site.trainer.componants.navbar')

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
                                <p class="text-gray-700">Total upcoming sessions</p>
                                <h3 class="text-3xl font-semibold mt-1">5</h3>
                            </div>
                            <div class="mt-6 space-x-4 text-sm mt-5">
                                <span class="text-green-600 font-medium">Completed: <span class="font-bold">2</span></span>
                                <span class="text-red-500 font-medium">Pending: <span class="font-bold">3</span></span>
                            </div>
                        </div>
                    </div>
                </div>


                <div x-data="dashboard()" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Enrolled Jobseekers -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-base font-semibold mb-3">Enrolled jobseekers</h3>
                        <div class="divide-y">
                            <template x-for="jobseeker in visibleJobseekers" :key="jobseeker.enrollment">
                                <div class="flex justify-between items-center py-3">
                                    <div class="flex items-center space-x-3">
                                        <img :src="jobseeker.img" class="w-10 h-10 rounded-full object-cover" alt="Profile" />
                                        <div>
                                            <h4 class="font-medium text-sm" x-text="jobseeker.name"></h4>
                                            <p class="text-xs text-gray-500" x-text="jobseeker.role"></p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600" x-text="`Enrollment No:${jobseeker.enrollment}`"></p>
                                </div>
                            </template>
                        </div>
                        <div class="pt-4 text-sm text-blue-600 text-right">
                            <button x-show="visibleJobseekers.length < jobseekers.length" @click="loadMoreJobseekers()" class="hover:underline">See all</button>
                        </div>
                    </div>

                    <!-- Today's Sessions -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-base font-semibold mb-3">Today's sessions</h3>
                        <div class="divide-y">
                            <template x-for="session in visibleSessions" :key="session.title + session.time">
                                <div class="flex justify-between items-center py-3">
                                    <div>
                                        <h4 class="text-sm font-medium" x-text="session.title"></h4>
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
                            // Full data lists
                            jobseekers: [
                                { name: 'Ravi Kumar', role: 'UI UX Designer', enrollment: '373857', img: 'https://randomuser.me/api/portraits/men/11.jpg' },
                                { name: 'Anjali Sharma', role: 'Frontend Dev', enrollment: '374122', img: 'https://randomuser.me/api/portraits/women/12.jpg' },
                                { name: 'Vikram Singh', role: 'Backend Dev', enrollment: '374890', img: 'https://randomuser.me/api/portraits/men/13.jpg' },
                                { name: 'Neha Verma', role: 'QA Engineer', enrollment: '372398', img: 'https://randomuser.me/api/portraits/women/14.jpg' },
                                { name: 'Amit Patel', role: 'Data Analyst', enrollment: '375671', img: 'https://randomuser.me/api/portraits/men/15.jpg' },
                                { name: 'Kiran Joshi', role: 'DevOps Engineer', enrollment: '375999', img: 'https://randomuser.me/api/portraits/women/16.jpg' },
                                { name: 'Rahul Mehta', role: 'React Developer', enrollment: '376321', img: 'https://randomuser.me/api/portraits/men/17.jpg' },
                            ],
                            sessions: [
                                { title: 'UI UX design - Basic', time: '03:15 pm', batch: 'Batch 01' },
                                { title: 'UI UX design - Basic', time: '05:15 pm', batch: 'Batch 02' },
                                { title: 'Graphic design - Basic', time: '01:15 pm', batch: 'Batch 01' },
                                { title: 'Graphic design - Basic', time: '03:15 pm', batch: 'Batch 02' },
                                { title: 'Web design - Advanced', time: '10:15 am', batch: 'Batch 03' },
                                { title: 'Digital Marketing', time: '04:00 pm', batch: 'Batch 01' },
                            ],

                            // Pagination control
                            visibleJobseekers: [],
                            visibleSessions: [],
                            limit: 5,

                            // On init
                            init() {
                                this.visibleJobseekers = this.jobseekers.slice(0, this.limit);
                                this.visibleSessions = this.sessions.slice(0, this.limit);
                            },

                            // Load more
                            loadMoreJobseekers() {
                                const next = this.visibleJobseekers.length + this.limit;
                                this.visibleJobseekers = this.jobseekers.slice(0, next);
                            },
                            loadMoreSessions() {
                                const next = this.visibleSessions.length + this.limit;
                                this.visibleSessions = this.sessions.slice(0, next);
                            }
                        };
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