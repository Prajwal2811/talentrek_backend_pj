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

           <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="{ activeSection: 'profile', activeSubTab: 'company' }">
                    <h2 class="text-2xl font-semibold mb-6">Settings</h2>

                    <div class="flex">
                        <!-- Sidebar -->
                        <aside class="w-60 bg-white p-4 border-r mt-4 shadow rounded-lg">
                            <ul class="space-y-2">
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'profile'"
                                    :class="activeSection === 'profile' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >Profile</a>
                                </li>
                                
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'notifications'"
                                    :class="activeSection === 'notifications' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >Notifications</a>
                                </li>
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'payment'"
                                    :class="activeSection === 'payment' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >Payment History</a>
                                </li>
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'subscription'; activeSubTab = 'subscription'"
                                    :class="activeSection === 'subscription' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >Subscription</a>
                                </li>
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'privacy'"
                                    :class="activeSection === 'privacy' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >Privacy policy</a>
                                </li>
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'logout'"
                                    :class="activeSection === 'logout' ? 'bg-blue-100 text-blue-700 rounded px-2 py-2 block' : 'block px-2 py-2 hover:bg-gray-100 rounded'"
                                >Log out</a>
                                </li>
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'delete'"
                                    :class="activeSection === 'delete' ? 'bg-red-100 text-red-700 rounded px-2 py-2 block' : 'block px-2 py-2 text-red-600 hover:bg-red-100 rounded'"
                                >Delete account</a>
                                </li>
                            </ul>
                        </aside>

                        <!-- Main Content -->
                        <section class="flex-1 p-6">
                            <div class="bg-white rounded-lg shadow p-6">
                                <!-- Profile Section -->
                                <div x-show="activeSection === 'subscription'" x-transition class="bg-white p-6">
                                    <h3 class="text-xl font-semibold mb-4 border-b pb-2">Subscription</h3>

                                    <!-- Include Alpine.js -->
                                    <script src="//unpkg.com/alpinejs" defer></script>

                                    <!-- Main Section -->
                                    <div x-data="{ showPlans: false }">
                                        <!-- Subscription Card -->
                                        <div class="bg-gray-100 p-6 rounded-md flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                                            <div>
                                                <h4 class="text-lg font-semibold mb-1">Subscription Plans</h4>
                                                <p class="text-gray-600 text-sm">Purchase subscription to get access to premium features of Talentrek</p>
                                            </div>
                                            <button @click="showPlans = true"
                                                class="mt-4 md:mt-0 bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                                                View Plans
                                            </button>
                                        </div>

                                        <!-- Modal Overlay -->
                                        <div
                                            x-show="showPlans"
                                            x-transition
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                            style="display: none;"
                                        >
                                            <!-- Modal Content -->
                                            <div @click.outside="showPlans = false"
                                                class="bg-white rounded-lg p-6 w-full max-w-5xl mx-auto shadow-lg">
                                                <div class="flex justify-between items-center mb-6">
                                                    <h2 class="text-xl font-semibold">Choose Your Plan</h2>
                                                    <button @click="showPlans = false" class="text-gray-600 hover:text-black text-2xl">&times;</button>
                                                </div>

                                                <!-- Plans Grid -->
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                    <!-- Plan Example -->
                                                    <template x-for="(plan, index) in ['Silver', 'Gold', 'Platinum']" :key="index">
                                                        <div class="border rounded-xl shadow p-5 text-center">
                                                            <div class="w-16 h-16 mx-auto rounded-full bg-gradient-to-b from-gray-300 to-gray-200 mb-3"></div>
                                                            <h3 class="font-semibold" x-text="plan"></h3>
                                                            <p class="text-2xl font-bold my-2">$100</p>
                                                            <hr class="my-3">
                                                            <p class="text-sm text-gray-600 mb-3">Access premium features with this plan.</p>
                                                            <ul class="text-left text-sm space-y-1 mb-4 text-gray-700">
                                                                <li class="flex items-center"><span class="text-blue-600 mr-2">✔</span> Feature A</li>
                                                                <li class="flex items-center"><span class="text-blue-600 mr-2">✔</span> Feature B</li>
                                                                <li class="flex items-center"><span class="text-blue-600 mr-2">✔</span> Feature C</li>
                                                            </ul>
                                                            <button class="bg-orange-500 text-white w-full py-2 rounded hover:bg-orange-600 transition">
                                                                Buy subscription
                                                            </button>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Subscription History Table -->
                                    <h4 class="text-lg font-semibold mb-3">Subscription History</h4>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full bg-white border border-gray-200 text-sm text-left">
                                        <thead class="bg-gray-100 text-gray-700">
                                            <tr>
                                            <th class="px-4 py-2 border-b">Sr. No.</th>
                                            <th class="px-4 py-2 border-b">Paid to</th>
                                            <th class="px-4 py-2 border-b">Date</th>
                                            <th class="px-4 py-2 border-b">Amount</th>
                                            <th class="px-4 py-2 border-b">Payment status</th>
                                            <th class="px-4 py-2 border-b">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-700">
                                            <tr class="border-b">
                                            <td class="px-4 py-2">1.</td>
                                            <td class="px-4 py-2">Silver tier</td>
                                            <td class="px-4 py-2">12/04/2025</td>
                                            <td class="px-4 py-2">100$</td>
                                            <td class="px-4 py-2">Paid</td>
                                            <td class="px-4 py-2">
                                                <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">
                                                View invoice
                                                </button>
                                            </td>
                                            </tr>
                                            <tr class="border-b">
                                            <td class="px-4 py-2">2.</td>
                                            <td class="px-4 py-2">Silver tier</td>
                                            <td class="px-4 py-2">12/04/2025</td>
                                            <td class="px-4 py-2">100$</td>
                                            <td class="px-4 py-2">Paid</td>
                                            <td class="px-4 py-2">
                                                <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">
                                                View invoice
                                                </button>
                                            </td>
                                            </tr>
                                            <tr class="border-b">
                                            <td class="px-4 py-2">2.</td>
                                            <td class="px-4 py-2">Silver tier</td>
                                            <td class="px-4 py-2">12/04/2025</td>
                                            <td class="px-4 py-2">100$</td>
                                            <td class="px-4 py-2">Paid</td>
                                            <td class="px-4 py-2">
                                                <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">
                                                View invoice
                                                </button>
                                            </td>
                                            </tr>
                                            <!-- Add more rows as needed -->
                                        </tbody>
                                        </table>
                                    </div>
                                </div>


                                   <div x-show="activeSection === 'notifications'" x-transition class="bg-white p-6 ">
                                <h3 class="text-xl font-semibold mb-4 border-b pb-2">Notifications</h3>

                                <!-- Scrollable notification list -->
                                <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                                    <!-- Notification Items -->
                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> You have meeting on <span class="font-medium">12:30 pm</span></p>
                                        <p class="text-sm text-gray-500 mt-1">4 Minute ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Your task deadline is <span class="font-medium">3:00 pm</span></p>
                                        <p class="text-sm text-gray-500 mt-1">10 Minute ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Sent you a file at <span class="font-medium">11:45 am</span></p>
                                        <p class="text-sm text-gray-500 mt-1">30 Minute ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Meeting rescheduled to <span class="font-medium">1:30 pm</span></p>
                                        <p class="text-sm text-gray-500 mt-1">1 Hour ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Approved your request</p>
                                        <p class="text-sm text-gray-500 mt-1">2 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> You have call scheduled at <span class="font-medium">4:00 pm</span></p>
                                        <p class="text-sm text-gray-500 mt-1">3 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> New comment on your post</p>
                                        <p class="text-sm text-gray-500 mt-1">3 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> You have meeting on <span class="font-medium">5:30 pm</span></p>
                                        <p class="text-sm text-gray-500 mt-1">4 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Sent a reminder for report submission</p>
                                        <p class="text-sm text-gray-500 mt-1">5 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Task completed: <span class="font-medium">UI Design</span></p>
                                        <p class="text-sm text-gray-500 mt-1">6 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> You have meeting on <span class="font-medium">10:30 am</span> tomorrow</p>
                                        <p class="text-sm text-gray-500 mt-1">Yesterday</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Reminder: Submit your weekly report</p>
                                        <p class="text-sm text-gray-500 mt-1">Yesterday</p>
                                    </div>
                                    </div>
                                </div>
                                </div>
                                

                                <div x-show="activeSection === 'payment'" x-transition class="bg-white p-6">
                                    <h3 class="text-xl font-semibold mb-4 border-b pb-2">Payment History</h3>

                                    <div class="overflow-x-auto">
                                        <table class="min-w-full text-sm text-left border rounded-lg overflow-hidden">
                                        <thead class="bg-gray-100 text-gray-700">
                                            <tr>
                                            <th class="px-4 py-2 border">Sr. No.</th>
                                            <th class="px-4 py-2 border">Paid to</th>
                                            <th class="px-4 py-2 border">Date</th>
                                            <th class="px-4 py-2 border">Amount</th>
                                            <th class="px-4 py-2 border">Payment status</th>
                                            <th class="px-4 py-2 border">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-700">
                                            <tr class="border-b">
                                            <td class="px-4 py-2">1.</td>
                                            <td class="px-4 py-2">Session1</td>
                                            <td class="px-4 py-2">24/04/2025</td>
                                            <td class="px-4 py-2">200</td>
                                            <td class="px-4 py-2">Paid</td>
                                            <td class="px-4 py-2">
                                                <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                                View invoice
                                                </button>
                                            </td>
                                            </tr>
                                            <tr class="border-b">
                                            <td class="px-4 py-2">2.</td>
                                            <td class="px-4 py-2">Session2</td>
                                            <td class="px-4 py-2">26/04/2025</td>
                                            <td class="px-4 py-2">100</td>
                                            <td class="px-4 py-2">Paid</td>
                                            <td class="px-4 py-2">
                                                <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                                View invoice
                                                </button>
                                            </td>
                                            </tr>
                                            <!-- Add more rows dynamically if needed -->
                                        </tbody>
                                        </table>
                                    </div>
                                    </div>

                                <!-- Notifications Section -->
                                <div x-show="activeSection === 'notifications'" x-transition class="bg-white p-6 ">
                                <h3 class="text-xl font-semibold mb-4 border-b pb-2">Notifications</h3>

                                <!-- Scrollable notification list -->
                                <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                                    <!-- Notification Items -->
                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> You have meeting on <span class="font-medium">12:30 pm</span></p>
                                        <p class="text-sm text-gray-500 mt-1">4 Minute ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Your task deadline is <span class="font-medium">3:00 pm</span></p>
                                        <p class="text-sm text-gray-500 mt-1">10 Minute ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Sent you a file at <span class="font-medium">11:45 am</span></p>
                                        <p class="text-sm text-gray-500 mt-1">30 Minute ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Meeting rescheduled to <span class="font-medium">1:30 pm</span></p>
                                        <p class="text-sm text-gray-500 mt-1">1 Hour ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Approved your request</p>
                                        <p class="text-sm text-gray-500 mt-1">2 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> You have call scheduled at <span class="font-medium">4:00 pm</span></p>
                                        <p class="text-sm text-gray-500 mt-1">3 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> New comment on your post</p>
                                        <p class="text-sm text-gray-500 mt-1">3 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> You have meeting on <span class="font-medium">5:30 pm</span></p>
                                        <p class="text-sm text-gray-500 mt-1">4 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Sent a reminder for report submission</p>
                                        <p class="text-sm text-gray-500 mt-1">5 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Task completed: <span class="font-medium">UI Design</span></p>
                                        <p class="text-sm text-gray-500 mt-1">6 Hours ago</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4 border-b pb-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> You have meeting on <span class="font-medium">10:30 am</span> tomorrow</p>
                                        <p class="text-sm text-gray-500 mt-1">Yesterday</p>
                                    </div>
                                    </div>

                                    <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 shrink-0"></div>
                                    <div>
                                        <p><span class="font-semibold">James Walker</span> Reminder: Submit your weekly report</p>
                                        <p class="text-sm text-gray-500 mt-1">Yesterday</p>
                                    </div>
                                    </div>
                                </div>
                                </div>


                                

                                <!-- Subscription Section (with internal tabs) -->
                                <div x-show="activeSection === 'profile'" x-transition>
                                    <!-- Company Header -->
                                    <div class="flex items-center space-x-4 mb-4">
                                        <img src="./images/gallery/pic8.jpg" alt="Logo" class="w-20 h-20 rounded-lg object-cover" />
                                        <div>
                                            <h3 class="text-xl font-semibold">XYZ Infotech</h3>
                                            <p class="text-gray-600">xyz@gmail.com</p>
                                            <p class="text-gray-600">+96673 459 7802</p>
                                        </div>
                                    </div>

                                    <!-- Inner Tabs -->
                                    <div class="border-b mb-4">
                                        <nav class="flex space-x-6">
                                            <button
                                                @click="activeSubTab = 'personal'"
                                                :class="activeSubTab === 'personal' ? 'pb-2 border-b-2 border-blue-600 font-medium' : 'pb-2 text-gray-500 hover:text-black'"
                                                class="focus:outline-none"
                                            >
                                                Personal Information
                                            </button>
                                            <button
                                                @click="activeSubTab = 'education'"
                                                :class="activeSubTab === 'education' ? 'pb-2 border-b-2 border-blue-600 font-medium' : 'pb-2 text-gray-500 hover:text-black'"
                                                class="focus:outline-none"
                                            >
                                                Educational Details
                                            </button>
                                            <button
                                                @click="activeSubTab = 'work'"
                                                :class="activeSubTab === 'work' ? 'pb-2 border-b-2 border-blue-600 font-medium' : 'pb-2 text-gray-500 hover:text-black'"
                                                class="focus:outline-none"
                                            >
                                                Work Experience
                                            </button>
                                            <button
                                                @click="activeSubTab = 'skills'"
                                                :class="activeSubTab === 'skills' ? 'pb-2 border-b-2 border-blue-600 font-medium' : 'pb-2 text-gray-500 hover:text-black'"
                                                class="focus:outline-none"
                                            >
                                                Skills & Training
                                            </button>
                                            <button
                                                @click="activeSubTab = 'additional'"
                                                :class="activeSubTab === 'additional' ? 'pb-2 border-b-2 border-blue-600 font-medium' : 'pb-2 text-gray-500 hover:text-black'"
                                                class="focus:outline-none"
                                            >
                                                Additional Information
                                            </button>
                                        </nav>

                                    </div>

                                    <!-- Inner Tab Contents -->
                                    <div>
                                       <!-- Personal Information Tab -->
                                        <!-- Personal Information Tab -->
                                        <div x-show="activeSubTab === 'personal'" x-transition>
                                        <form class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                            <!-- Name full width -->
                                            <div class="md:col-span-2">
                                            <label class="block mb-1 font-medium">Full Name</label>
                                            <input type="text" placeholder="John Doe" class="w-full border rounded px-3 py-2" />
                                            </div>

                                            <!-- Email full width -->
                                            <div class="md:col-span-2">
                                            <label class="block mb-1 font-medium">Email</label>
                                            <input type="email" placeholder="john.doe@example.com" class="w-full border rounded px-3 py-2" />
                                            </div>

                                            <div>
                                            <label class="block mb-1 font-medium">Phone Number</label>
                                            <input type="text" placeholder="+91 9876543210" class="w-full border rounded px-3 py-2" />
                                            </div>

                                            <div>
                                            <label class="block mb-1 font-medium">Date of Birth</label>
                                            <input type="date" class="w-full border rounded px-3 py-2" />
                                            </div>

                                            <!-- Location full width -->
                                            <div class="md:col-span-2">
                                            <label class="block mb-1 font-medium">Location</label>
                                            <input type="text" placeholder="City, State, Country" class="w-full border rounded px-3 py-2" />
                                            </div>
                                        </form>

                                        <div class="mt-6 flex justify-end space-x-3">
                                            <button @click.prevent="activeSubTab = 'education'" class="border px-6 py-2 rounded hover:bg-gray-100">
                                            Next
                                            </button>
                                            <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                                            Save
                                            </button>
                                        </div>
                                        </div>


                                        <!-- Educational Details Tab -->
                                       <div x-show="activeSubTab === 'education'" x-transition>
                                            <form class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                                <div class="md:col-span-2">
                                                <label class="block mb-1 font-medium">Highest Qualification</label>
                                                <input type="text" class="w-full border rounded px-3 py-2" placeholder="e.g., Bachelor's Degree" />
                                                </div>
                                                <div>
                                                <label class="block mb-1 font-medium">Field of Study</label>
                                                <input type="text" class="w-full border rounded px-3 py-2" placeholder="e.g., Computer Science" />
                                                </div>
                                                <div class="md:col-span-2">
                                                <label class="block mb-1 font-medium">Institution Name</label>
                                                <input type="text" class="w-full border rounded px-3 py-2" placeholder="e.g., ABC University" />
                                                </div>
                                                <div>
                                                <label class="block mb-1 font-medium">Graduation Year</label>
                                                <input type="text" class="w-full border rounded px-3 py-2" placeholder="e.g., 2023" />
                                                </div>
                                            </form>
                                            <div class="mt-6 flex justify-end space-x-3">
                                                <button @click.prevent="activeSubTab = 'work'" class="border px-6 py-2 rounded hover:bg-gray-100">
                                                Next
                                                </button>
                                                <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                                                Save
                                                </button>
                                            </div>
                                            </div>


                                        <!-- Work Experience Tab -->
                                        <div x-show="activeSubTab === 'work'" x-transition>
                                            <form class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                                <div>
                                                <label class="block mb-1 font-medium">Job Role</label>
                                                <input type="text" class="w-full border rounded px-3 py-2" placeholder="e.g., Software Engineer" />
                                                </div>
                                                <div>
                                                <label class="block mb-1 font-medium">Organization</label>
                                                <input type="text" class="w-full border rounded px-3 py-2" placeholder="e.g., XYZ Corp" />
                                                </div>
                                                <div>
                                                <label class="block mb-1 font-medium">Started From</label>
                                                <input type="date" class="w-full border rounded px-3 py-2" />
                                                </div>
                                                <div>
                                                <label class="block mb-1 font-medium">To</label>
                                                <input type="date" class="w-full border rounded px-3 py-2" />
                                                </div>
                                            </form>
                                            <div class="mt-6 flex justify-end space-x-3">
                                                <button @click.prevent="activeSubTab = 'skills'" class="border px-6 py-2 rounded hover:bg-gray-100">
                                                Next
                                                </button>
                                                <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                                                Save
                                                </button>
                                            </div>
                                            </div>


                                        <!-- Skills & Training Tab -->
                                        <div x-show="activeSubTab === 'skills'" x-transition>
                                            <form class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                                <div class="md:col-span-2">
                                                <label class="block mb-1 font-medium">Skills</label>
                                                <input type="text" placeholder="e.g., JavaScript, Python, UI/UX" class="w-full border rounded px-3 py-2" />
                                                </div>
                                                <div class="md:col-span-2">
                                                <label class="block mb-1 font-medium">Area of Interests</label>
                                                <input type="text" placeholder="e.g., Web Development, AI, Design" class="w-full border rounded px-3 py-2" />
                                                </div>
                                                <div class="md:col-span-2">
                                                <label class="block mb-1 font-medium">Job Categories</label>
                                                <input type="text" placeholder="e.g., Software, Marketing, HR" class="w-full border rounded px-3 py-2" />
                                                </div>
                                                <div>
                                                <label class="block mb-1 font-medium">Website Link</label>
                                                <input type="url" placeholder="https://example.com" class="w-full border rounded px-3 py-2" />
                                                </div>
                                                <div>
                                                <label class="block mb-1 font-medium">Portfolio Link</label>
                                                <input type="url" placeholder="https://portfolio.com" class="w-full border rounded px-3 py-2" />
                                                </div>
                                            </form>
                                            <div class="mt-6 flex justify-end space-x-3">
                                                <button @click.prevent="activeSubTab = 'additional'" class="border px-6 py-2 rounded hover:bg-gray-100">
                                                Next
                                                </button>
                                                <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                                                Save
                                                </button>
                                            </div>
                                            </div>


                                        <!-- Additional Information Tab -->
                                        <div x-show="activeSubTab === 'additional'" x-transition>
                                            <h3 class="text-lg font-semibold mb-4">Upload Documents</h3>
                                            <form class="space-y-4 text-sm">
                                                <div>
                                                    <label class="block mb-1 font-medium" for="doc1">Upload Resume</label>
                                                    <input type="file" id="doc1" class="w-full border rounded px-3 py-2" />
                                                </div>
                                                <div>
                                                    <label class="block mb-1 font-medium" for="doc2">Upload Profile Picture </label>
                                                    <input type="file" id="doc2" class="w-full border rounded px-3 py-2" />
                                                </div>
                                            </form>
                                            <div class="mt-6 flex justify-end space-x-3">
                                                <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                                                    Submit
                                                </button>
                                            </div>
                                        </div>

                                        
                                    </div>
                                </div>


                                <!-- Privacy Policy Section -->
                              <div x-show="activeSection === 'privacy'" x-transition>
                                <h3 class="text-xl font-semibold mb-4">Privacy Policy</h3>
                                
                                <p class="mb-4">At XYZ Infotech, we are committed to protecting your privacy. This Privacy Policy outlines how we collect, use, and safeguard your personal information.</p>

                                <h4 class="text-lg font-semibold mb-2">1. Information We Collect</h4>
                                <ul class="list-disc list-inside mb-4 text-sm text-gray-700">
                                    <li>Personal information such as name, email address, phone number, and address.</li>
                                    <li>Usage data including pages visited, time spent, and actions taken on our platform.</li>
                                    <li>Device and browser information for improving user experience.</li>
                                </ul>

                                <h4 class="text-lg font-semibold mb-2">2. How We Use Your Information</h4>
                                <ul class="list-disc list-inside mb-4 text-sm text-gray-700">
                                    <li>To provide and maintain our services.</li>
                                    <li>To improve customer support and experience.</li>
                                    <li>To send transactional and promotional communications.</li>
                                    <li>To comply with legal obligations.</li>
                                </ul>

                                <h4 class="text-lg font-semibold mb-2">3. Data Security</h4>
                                <p class="mb-4 text-sm text-gray-700">We implement a variety of security measures to maintain the safety of your personal information. However, no method of transmission over the Internet is 100% secure.</p>

                                <h4 class="text-lg font-semibold mb-2">4. Third-Party Services</h4>
                                <p class="mb-4 text-sm text-gray-700">We may use third-party services (e.g., analytics providers, payment gateways) that collect, monitor, and analyze data. These services have their own privacy policies.</p>

                                <h4 class="text-lg font-semibold mb-2">5. Your Rights</h4>
                                <ul class="list-disc list-inside mb-4 text-sm text-gray-700">
                                    <li>Access and update your personal information.</li>
                                    <li>Request deletion of your data.</li>
                                    <li>Opt out of marketing communications.</li>
                                </ul>

                                <h4 class="text-lg font-semibold mb-2">6. Changes to This Policy</h4>
                                <p class="mb-4 text-sm text-gray-700">We may update this Privacy Policy periodically. We will notify you of any significant changes via email or on our platform.</p>

                                <h4 class="text-lg font-semibold mb-2">7. Contact Us</h4>
                                <p class="text-sm text-gray-700">If you have any questions or concerns about this policy, please contact us at <a href="mailto:support@xyzinfotech.com" class="text-blue-600 underline">support@xyzinfotech.com</a>.</p>
                                </div>


                                <!-- Log Out Section -->
                                <div x-show="activeSection === 'logout'" x-transition>
                                <h3 class="text-xl font-semibold mb-4">Log Out</h3>
                                <p>Are you sure you want to log out?</p>
                                <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Log Out</button>
                                </div>

                                <!-- Delete Account Section -->
                                <div x-show="activeSection === 'delete'" x-transition>
                                <h3 class="text-xl font-semibold mb-4 text-red-600">Delete Account</h3>
                                <p>This action is irreversible. Are you sure you want to delete your account?</p>
                                <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete Account</button>
                                </div>
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
