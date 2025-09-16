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

                <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="trainingDashboard({{ $jobseekersData->toJson() }})">
                    <h2 class="text-2xl font-semibold mb-6">{{ langLabel('jobseeker') }} {{ langLabel('list') }}</h2>

                    <div class="bg-white rounded-md shadow-md">
                        <!-- Header -->
                        <div class="flex items-center justify-between px-6 py-4 border-b">
                            <h3 class="font-medium">{{ langLabel('jobseeker') }} {{ langLabel('list') }}</h3>
                            <div class="flex space-x-4 text-sm font-medium">
                                <template x-for="tab in ['Online', 'Offline', 'Recorded']" :key="tab">
                                    <button 
                                        @click="setActiveTab(tab)" 
                                        :class="{
                                            'text-black font-semibold border-b-2 border-blue-600': activeTab === tab,
                                            'text-gray-400': activeTab !== tab
                                        }"
                                        class="pb-1"
                                        x-text="tab"
                                    ></button>
                                </template>
                            </div>
                        </div>

                        <!-- List -->
                        <div class="divide-y">
                            <template x-for="(user, index) in paginatedUsers" :key="user.id">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between px-6 py-4 gap-4">
                                    <!-- Profile Info -->
                                    <div class="flex items-center gap-4">
                                        <img :src="user.avatar" alt="" class="w-14 h-14 rounded-full object-cover" />
                                        <div>
                                            <p class="font-semibold" x-text="user.name"></p>
                                            <p class="text-sm text-gray-500" x-text="user.designation"></p>
                                        </div>
                                    </div>

                                    <!-- Info Grid -->
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm text-gray-700 flex-1 md:ml-10">
                                        <template x-if="user.mode.toLowerCase() !== 'recorded'">
                                            <div>
                                                <p class="font-semibold">{{ langLabel('batch') }}</p>
                                                <p x-text="user.batchName ?? '-'"></p>
                                            </div>
                                        </template>
                                        <template x-if="user.mode.toLowerCase() === 'recorded'">
                                            <div>
                                                <p class="font-semibold">{{ langLabel('lessons') }}</p>
                                                <p x-text="user.totalLessons ?? 'N/A'"></p>
                                            </div>
                                        </template>

                                        <div>
                                            <p class="font-semibold">{{ langLabel('course') }}</p>
                                            <p x-text="user.courseName"></p>
                                        </div>

                                        <div>
                                            <p class="font-semibold">{{ langLabel('enrollment_no') }}</p>
                                            <p x-text="user.enrollmentNo ?? '-'"></p>
                                        </div>

                                        <div>
                                            <p class="font-semibold">{{ langLabel('mode') }}</p>
                                            <p x-text="user.mode"></p>
                                        </div>
                                    </div>

                                    <!-- Chat Button -->
                                    <div>
                                        <button class="border border-gray-300 px-4 py-1 rounded-md text-sm hover:bg-gray-100">
                                            {{ langLabel('chat') }}
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Pagination -->
                        <div class="flex justify-end items-center px-6 py-4 space-x-4 border-t">
                            <button @click="prevPage" :disabled="currentPage === 1"
                                class="text-sm px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50">
                                {{ langLabel('previous') }}
                            </button>
                            <span class="text-sm text-gray-600">
                                {{ langLabel('page') }} <span x-text="currentPage"></span> {{ langLabel('of') }} <span x-text="totalPages"></span>
                            </span>
                            <button @click="nextPage" :disabled="currentPage >= totalPages"
                                class="text-sm px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50">
                                {{ langLabel('next') }}
                            </button>
                        </div>
                    </div>
                </main>

                <script>
                    function trainingDashboard(data = []) {
                        return {
                            allUsers: data,
                            activeTab: 'Online',
                            currentPage: 1,
                            pageSize: 5,

                            get filteredUsers() {
                                return this.allUsers.filter(user => user.mode?.toLowerCase() === this.activeTab.toLowerCase());
                            },
                            get totalPages() {
                                return Math.ceil(this.filteredUsers.length / this.pageSize);
                            },
                            get paginatedUsers() {
                                const start = (this.currentPage - 1) * this.pageSize;
                                return this.filteredUsers.slice(start, start + this.pageSize);
                            },
                            setActiveTab(tab) {
                                this.activeTab = tab;
                                this.currentPage = 1;
                            },
                            nextPage() {
                                if (this.currentPage < this.totalPages) this.currentPage++;
                            },
                            prevPage() {
                                if (this.currentPage > 1) this.currentPage--;
                            }
                        };
                    }
                </script>

                <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
            </div>
        </div>

        <!-- Feather Icons -->
        <script>
            feather.replace()
        </script>

    </div>
           


@include('site.trainer.componants.footer')