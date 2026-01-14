<?php

$mentor = Auth()->user();
//  'trainerSkills',
// 'educationDetails',
// 'workExperiences'
// 'additonalDetails'
// echo "<pre>";
// print_r($additonalDetails );exit;
// echo "</pre>";
?>
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

	@if($mentorNeedsSubscription)
    @include('site.mentor.subscription.index')
@endif
    <div class="page-wraper">
        <div class="flex h-screen" x-data="{ sidebarOpen: true }" x-init="$watch('sidebarOpen', () => feather.replace())">
          
            @include('site.mentor.componants.sidebar')

            <div class="flex-1 flex flex-col">
                @include('site.mentor.componants.navbar')

           <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="{ activeSection: 'profile', activeSubTab: 'company' }">
                    <h2 class="text-2xl font-semibold mb-6">{{ langLabel('settings') }}</h2>

                    <div class="flex"> 
                        <!-- Sidebar -->
                        <aside class="w-60 bg-white p-4 border-r mt-4 shadow rounded-lg">
                            <ul class="space-y-2">
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'profile'"
                                    :class="activeSection === 'profile' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >{{ langLabel('profile') }}</a>
                                </li>
                                
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'notifications'"
                                    :class="activeSection === 'notifications' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >{{ langLabel('notifications') }}</a>
                                </li>
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'payment'"
                                    :class="activeSection === 'payment' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >{{ langLabel('payment_history') }}</a>
                                </li>
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'subscription'; activeSubTab = 'subscription'"
                                    :class="activeSection === 'subscription' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >{{ langLabel('subscription') }}</a>
                                </li>
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'privacy'"
                                    :class="activeSection === 'privacy' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >{{ langLabel('privacy_policy') }}</a>
                                </li>
                                <li>
                                <!-- <a
                                    href="#"
                                    @click.prevent="activeSection = 'logout'"
                                    :class="activeSection === 'logout' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >Log out</a> -->
                                </li>
                                  <li>
                                     <a
                                    href="#"
                                    @click.prevent="activeSection = 'delete'"
                                    :class="activeSection === 'delete' ? 'bg-red-100 text-red-700 rounded px-2 py-2 block' : 'block px-2 py-2 text-red-600 hover:bg-red-100 rounded'"
                                >{{ langLabel('delete_account') }}</a>
                                </li>
                            </ul>
                        </aside>

                        <!-- Main Content -->
                        <section class="flex-1 p-6">
                            <div class="bg-white rounded-lg shadow p-6">
                                <!-- Profile Section -->
                                @include('site.mentor.setting.profile')
                                @include('site.mentor.setting.subscription')
                                @include('site.mentor.setting.delete')
                                @include('site.mentor.setting.notification')
                                @include('site.mentor.setting.payment')
                                @include('site.mentor.setting.privacy')
                                @include('site.mentor.setting.logout')
                            </div>
                        </section>
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
           


<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


          
@include('site.mentor.componants.footer')