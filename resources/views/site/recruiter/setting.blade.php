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

    @if($recruiterNeedsSubscription && auth()->user('recruiter')->role != "sub_recruiter")
        @include('site.recruiter.subscription.index')
    @endif
     @if($otherRecruiterSubscription && auth()->user('recruiter')->role != "sub_recruiter")
        @include('site.recruiter.subscription.add-other-recruiters')
    @endif

    <div class="page-wraper">
        <div class="flex h-screen" x-data="{ sidebarOpen: true }"
            x-init="$watch('sidebarOpen', () => feather.replace())">
            <!-- Sidebar -->
            @include('site.recruiter.componants.sidebar')
            <div class="flex-1 flex flex-col">
                @include('site.recruiter.componants.navbar')


                <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
                <main class="p-6 bg-gray-100 flex-1 overflow-y-auto"
                    x-data="{ activeSection: 'profile', activeSubTab: 'company' }">
                    <h2 class="text-2xl font-semibold mb-6">{{ langLabel('settings') }}</h2>

                    <div class="flex">
                        <!-- Sidebar -->
                        <aside class="w-60 bg-white p-4 border-r mt-4 shadow rounded-lg">
                            <ul class="space-y-2">
                                <li>
                                    <a href="#" @click.prevent="activeSection = 'profile'; activeSubTab = 'company'"
                                        :class="activeSection === 'profile' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'">Profile</a>

                                </li>
                                <li>
                                    <a href="#" @click.prevent="activeSection = 'notifications'"
                                        :class="activeSection === 'notifications' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'">{{ langLabel('notifications') }}</a>
                                </li>
                                <li>
                                    <a href="#"
                                        @click.prevent="activeSection = 'subscription'"
                                        :class="activeSection === 'subscription' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'">{{ langLabel('subscription') }}</a>
                                </li>
                                @if(auth()->user('recruiter')->role === 'main')
                                    <li>
                                        <a href="#" @click.prevent="activeSection = 'payment'"
                                            :class="activeSection === 'payment' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'">{{ langLabel('payment_history') }}</a>
                                    </li>
                                @endif
                                <li>
                                    <a href="#" @click.prevent="activeSection = 'privacy'"
                                        :class="activeSection === 'privacy' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'">{{ langLabel('privacy_policy') }}</a>
                                </li>

                                <li>
                                    <a href="#" @click.prevent="activeSection = 'delete'"
                                        :class="activeSection === 'delete' ? 'bg-red-100 text-red-700 rounded px-2 py-2 block' : 'block px-2 py-2 text-red-600 hover:bg-red-100 rounded'">{{ langLabel('delete_account') }}</a>
                                </li>
                            </ul>
                        </aside>

                        <!-- Main Content -->
                        <section class="flex-1 p-6">
                            <div class="bg-white rounded-lg shadow p-6">
                                <!-- Profile Section: Contains recruiter profile info and edit form -->
                                @include('site.recruiter.setting.profile')

                                <!-- Notifications Section: Manages recruiter notification preferences -->
                                @include('site.recruiter.setting.notification')

                                <!-- Subscription Section: Shows recruiter subscription plans and details -->
                                @include('site.recruiter.setting.subscription')

                                <!-- Payment Section: Displays payment history or linked payment methods -->
                                @include('site.recruiter.setting.payment')

                                <!-- Privacy Policy Section: Shows privacy policy and data handling info -->
                                @include('site.recruiter.setting.privacy-policy')

                                <!-- Log Out Section: Allows recruiter to securely log out -->
                                @include('site.recruiter.setting.logout')

                                <!-- Delete Account Section: Option to delete recruiter account permanently -->
                                @include('site.recruiter.setting.delete')
                            </div>
                        </section>
                    </div>
                </main>
            </div>
        </div>
        <!-- Feather Icons -->
        <script>
            feather.replace()
        </script>


    </div>




    @include('site.recruiter.componants.footer')