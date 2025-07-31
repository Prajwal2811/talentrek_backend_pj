<?php
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

                                <!-- Delete Account Section -->
                                <div 
                                    x-show="activeSection === 'delete'" 
                                    x-transition 
                                    class="bg-white p-6 rounded-lg border border-red-200 shadow-md mt-4"
                                >
                                    <h3 class="text-xl font-semibold mb-3 text-red-600">Delete Account</h3>

                                    <p class="text-gray-700 leading-relaxed mb-4">
                                        This action is <span class="font-semibold text-red-700">irreversible</span>. 
                                        Are you sure you want to permanently delete your account?
                                    </p>

                                    <form id="deleteAccountForm" action="{{ route('trainer.destroy') }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button 
                                            type="button" 
                                            id="deleteAccountBtn" 
                                            class="bg-red-600 text-white px-5 py-2.5 rounded hover:bg-red-700 transition-colors duration-200"
                                        >
                                            Delete Account
                                        </button>
                                    </form>
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
                                        @php
                                            $user = auth()->user();
                                            $userId = $user->id;
                                            $profile = \App\Models\AdditionalInfo::where('user_id', $userId)
                                                ->where('user_type', 'trainer')
                                                ->where('doc_type', 'trainer_profile_picture')
                                                ->first();
                                        @endphp

                                        @if($profile && $profile->document_path)
                                            <img src="{{ $profile->document_path }}" alt="Profile" class="w-20 h-20 rounded-lg object-cover" />
                                        @else
                                            <img src="{{ asset('images/default-profile.png') }}" alt="Default" class="w-20 h-20 rounded-lg object-cover" />
                                        @endif


                                        <div>
                                            <h3 class="text-xl font-semibold">{{$user->name}}</h3>
                                            <p class="text-gray-600">{{$user->email}}</p>
                                            <p class="text-gray-600">{{$user->phone_number}}</p>
                                        </div>
                                    </div>
                                <div x-data="{ activeSubTab: 'personal' }">
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
                                    <div >
                                       <!-- Personal Information Tab -->
                                        <div x-show="activeSubTab === 'personal'" x-transition >
                                            <!-- Success message -->
                                            <div id="trainer-info-success" class="col-12 ml-auto mr-auto text-center alert alert-success alert-dismissible fade show" style="display: none;">
                                                <strong>Success!</strong> <span class="message-text"></span>
                                            </div>

                                            <!-- Trainer Info Form -->
                                            <form id="trainer-info-form" action="{{ route('trainer.profile.update') }}" method="POST">
                                                @csrf
                                                <div class="grid grid-cols-2 gap-6 mt-3">
                                                    <!-- Full Name -->
                                                    <div>
                                                        <label class="block mb-1 font-medium">Full Name</label>
                                                        <input type="text" name="name" value="{{ $trainerSkills->name ?? '' }}" placeholder="John Doe" class="w-full border rounded px-3 py-2" />
                                                        @error('name')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Email -->
                                                    <div>
                                                        <label class="block mb-1 font-medium">Email</label>
                                                        <input type="email" name="email" value="{{ $trainerSkills->email ?? '' }}" placeholder="john.doe@example.com" class="w-full border rounded px-3 py-2" />
                                                        @error('email')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-2 gap-6 mt-3">
                                                    <!-- Phone Number -->
                                                    <div>
                                                        <label class="block mb-1 font-medium">Phone Number</label>
                                                        <input type="text" name="phone" value="{{ $trainerSkills->phone_number ?? '' }}" placeholder="+91 9876543210" class="w-full border rounded px-3 py-2" />
                                                        @error('phone')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Date of Birth -->
                                                    <div>
                                                        <label class="block mb-1 font-medium">Date of Birth</label>
                                                        <input type="date" name="dob" value="{{ $trainerSkills->date_of_birth ?? '' }}" class="w-full border rounded px-3 py-2" />
                                                        @error('dob')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-2 gap-6 mt-3">
                                                    <div>
                                                        <label class="block mb-1 text-sm font-medium">National ID Number</label>
                                                        <span class="text-xs text-blue-600">
                                                            National ID should start with 1 for male and 2 for female.
                                                        </span>
                                                        <input 
                                                            type="text" 
                                                            name="national_id" 
                                                            id="national_id" 
                                                            class="w-full border rounded-md p-2 mt-1" 
                                                            placeholder="Enter national id number" 
                                                            value="{{ $trainerSkills->national_id ?? '' }}"
                                                            maxlength="15"
                                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 15);" 
                                                        />
                                                        @error('national_id')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <!-- Address -->
                                                <div class="md:col-span-2 mt-3">
                                                    <label class="block mb-1 font-medium">Address</label>
                                                    <textarea name="address" placeholder="Enter address" class="w-full border rounded px-3 py-2">{{ $trainerSkills->address ?? '' }}</textarea>
                                                    @error('address')
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- City -->
                                                <div class="mt-3">
                                                    <label class="block font-medium mb-1">City</label>
                                                    <input type="text" name="city" value="{{ $trainerSkills->city }}" class="w-full border rounded px-3 py-2" />
                                                    @error('city')
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- State -->
                                                <div class="mt-3">
                                                    <label class="block font-medium mb-1">State</label>
                                                    <input type="text" name="state" placeholder="Enter State" value="{{ $trainerSkills->state }}" class="w-full border rounded px-3 py-2" />
                                                    @error('state')
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Country -->
                                                <div class="mt-3">
                                                    <label class="block font-medium mb-1">Country</label>
                                                    <input type="text" name="country" value="{{ $trainerSkills->country }}" class="w-full border rounded px-3 py-2" />
                                                    @error('country')
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Pin code -->
                                                <div class="mt-3">
                                                    <label class="block font-medium mb-1">Pin code</label>
                                                    <input type="text" name="pin_code" value="{{ $trainerSkills->pin_code }}" class="w-full border rounded px-3 py-2" />
                                                    @error('pin_code')
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Buttons -->
                                                <div class="mt-6 flex justify-end space-x-3">
                                                    <button @click.prevent="activeSubTab = 'education'" class="border px-6 py-2 rounded hover:bg-gray-100">
                                                        Next
                                                    </button>
                                                    <button type="button" id="save-trainer-info" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                                                        Save
                                                    </button>   
                                                </div>
                                            </form>
                                        </div>
                                        <!-- JavaScript for AJAX Submission -->
                                        <script>
                                            document.getElementById('save-trainer-info').addEventListener('click', function () {
                                                const form = document.getElementById('trainer-info-form');
                                                const formData = new FormData(form);
                                                const successBox = document.getElementById('trainer-info-success');
                                                const successText = successBox.querySelector('.message-text');

                                                // Clear previous errors
                                                form.querySelectorAll('.text-red-600').forEach(el => el.remove());

                                                fetch(form.action, {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                                                        'Accept': 'application/json'
                                                    },
                                                    body: formData
                                                })
                                                .then(response => {
                                                    if (!response.ok) return response.json().then(err => Promise.reject(err));
                                                    return response.json();
                                                })
                                                .then(data => {
                                                    // Show success
                                                    successText.textContent = data.message;
                                                    successBox.style.display = 'block';

                                                    // Hide after 3 seconds
                                                    setTimeout(() => {
                                                        successBox.style.display = 'none';
                                                        successText.textContent = '';
                                                    }, 3000);

                                                    // Optional: Move to next tab
                                                    if (typeof activeSubTab !== 'undefined') activeSubTab = 'education';
                                                })
                                                .catch(error => {
                                                    const errors = error.errors || {};
                                                    Object.keys(errors).forEach(field => {
                                                        const input = form.querySelector(`[name="${field}"]`);
                                                        if (input) {
                                                            const errorElem = document.createElement('p');
                                                            errorElem.className = 'text-red-600 text-sm mt-1';
                                                            errorElem.textContent = errors[field][0];
                                                            input.insertAdjacentElement('afterend', errorElem);
                                                        }
                                                    });
                                                });
                                            });
                                        </script>

                                        <!-- Educational Details Tab -->
                                        <div x-show="activeSubTab === 'education'" x-transition>
                                            <!-- Success Message -->
                                            <div id="education-success" class="col-12 ml-auto mr-auto text-center alert alert-success alert-dismissible fade show" style="display: none;">
                                                <strong>Success!</strong> <span class="message-text"></span>
                                            </div>

                                            <!-- Education Form -->
                                            <form id="education-info-form" method="POST" action="{{ route('trainer.education.update') }}" class="space-y-6">
                                                @csrf
                                                <div id="education-container" class="space-y-6">
                                                    @foreach($educationDetails as $education)
                                                    <div class="education-entry border border-gray-300 rounded-md p-4 space-y-4 relative">
                                                        <input type="hidden" name="education_id[]" value="{{ $education->id }}">

                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div>
                                                                <label class="block mb-1 font-medium">Highest Qualification</label>
                                                                <input type="text" name="high_education[]" value="{{ ucfirst(str_replace('_', ' ', $education->high_education)) }}" class="w-full border rounded px-3 py-2" placeholder="e.g., Bachelor's Degree" />
                                                            </div>
                                                            <div>
                                                                <label class="block mb-1 font-medium">Field of Study</label>
                                                                <input type="text" name="field_of_study[]" value="{{ ucfirst($education->field_of_study) }}" class="w-full border rounded px-3 py-2" placeholder="e.g., Computer Science" />
                                                            </div>
                                                        </div>

                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div>
                                                                <label class="block mb-1 font-medium">Institution Name</label>
                                                                <input type="text" name="institution[]" value="{{ $education->institution }}" class="w-full border rounded px-3 py-2" placeholder="e.g., ABC University" />
                                                            </div>
                                                            <div>
                                                                <label class="block mb-1 font-medium">Graduation Year</label>
                                                                <input type="text" name="graduate_year[]" value="{{ $education->graduate_year }}" class="w-full border rounded px-3 py-2" placeholder="e.g., 2023" />
                                                            </div>
                                                        </div>

                                                        <button type="button" class="remove-education absolute top-2 right-2 text-red-600 font-bold text-lg" style="{{ $loop->first ? 'display:none;' : '' }}">&times;</button>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </form>

                                            <!-- Add More Button -->
                                            <div class="col-span-2">
                                                <button type="button" id="add-education" class="text-green-600 text-sm">Add education +</button>
                                            </div>

                                            <!-- Submit Buttons -->
                                            <div class="mt-6 flex justify-end space-x-3">
                                                <button @click.prevent="activeSubTab = 'work'" class="border px-6 py-2 rounded hover:bg-gray-100">Next</button>
                                                <button type="button" id="save-education-info" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save</button>
                                            </div>
                                        </div>
                                        <!-- AJAX & Dynamic Handling -->
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                const educationContainer = document.getElementById('education-container');
                                                const addEducationBtn = document.getElementById('add-education');
                                                const saveBtn = document.getElementById('save-education-info');
                                                const form = document.getElementById('education-info-form');
                                                const successBox = document.getElementById('education-success');
                                                const successText = successBox.querySelector('.message-text');

                                                // Add More Education Entry
                                                addEducationBtn.addEventListener('click', () => {
                                                    const firstEntry = educationContainer.querySelector('.education-entry');
                                                    const clone = firstEntry.cloneNode(true);

                                                    // Remove hidden input (education_id)
                                                    const hiddenInput = clone.querySelector('input[name="education_id[]"]');
                                                    if (hiddenInput) hiddenInput.remove();

                                                    // Clear all input fields
                                                    clone.querySelectorAll('input').forEach(input => input.value = '');

                                                    // Remove all error messages
                                                    clone.querySelectorAll('p.text-red-600').forEach(p => p.remove());

                                                    // Ensure remove button is visible
                                                    const removeBtn = clone.querySelector('.remove-education');
                                                    if (removeBtn) {
                                                        removeBtn.style.display = 'block';
                                                    }

                                                    educationContainer.appendChild(clone);
                                                });

                                                // Remove Education Entry
                                                educationContainer.addEventListener('click', function (e) {
                                                    if (e.target.classList.contains('remove-education')) {
                                                        const entry = e.target.closest('.education-entry');
                                                        const entries = educationContainer.querySelectorAll('.education-entry');
                                                        if (entries.length > 1) {
                                                            entry.remove();
                                                        }
                                                    }
                                                });

                                                // Save Education via AJAX
                                                saveBtn.addEventListener('click', function () {
                                                    const formData = new FormData(form);

                                                    // Clear previous errors
                                                    form.querySelectorAll('p.text-red-600').forEach(e => e.remove());

                                                    fetch(form.action, {
                                                        method: 'POST',
                                                        headers: {
                                                            'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                                                            'Accept': 'application/json'
                                                        },
                                                        body: formData
                                                    })
                                                    .then(response => {
                                                        if (!response.ok) {
                                                            return response.json().then(err => Promise.reject(err));
                                                        }
                                                        return response.json();
                                                    })
                                                    .then(data => {
                                                        successText.textContent = data.message || 'Saved successfully!';
                                                        successBox.style.display = 'block';

                                                        setTimeout(() => {
                                                            successBox.style.display = 'none';
                                                            successText.textContent = '';
                                                        }, 3000);

                                                        if (typeof nextTab === "function") nextTab();
                                                    })
                                                    .catch(error => {
                                                        const errors = error.errors || {};

                                                        Object.keys(errors).forEach(fieldName => {
                                                            const match = fieldName.match(/(\w+)\.(\d+)/);
                                                            if (match) {
                                                                const baseField = match[1];
                                                                const index = parseInt(match[2]);
                                                                const inputList = form.querySelectorAll(`[name="${baseField}[]"]`);
                                                                const input = inputList[index];
                                                                if (input) {
                                                                    const errorElem = document.createElement('p');
                                                                    errorElem.className = 'text-red-600 text-sm mt-1';
                                                                    errorElem.textContent = errors[fieldName][0];
                                                                    input.insertAdjacentElement('afterend', errorElem);
                                                                }
                                                            }
                                                        });
                                                    });
                                                });
                                            });
                                        </script>
                                        
                                        <!-- Work Experience Tab -->
                                        <div x-show="activeSubTab === 'work'" x-transition>
                                            <!-- Success Message -->
                                            <div id="work-success" class="col-12 ml-auto mr-auto text-center alert alert-success alert-dismissible fade show" style="display: none;">
                                                <strong>Success!</strong> <span class="message-text"></span>
                                            </div>

                                            <form id="work-info-form" method="POST" action="{{ route('trainer.workexprience.update')}}"   class="space-y-6">
                                                @csrf

                                                <div id="work-container" class="space-y-6">
                                                    @foreach($workExperiences as $work)
                                                    <div class="work-entry border border-gray-300 rounded-md p-4 space-y-4 relative">
                                                        <input type="hidden" name="work_id[]" value="{{ $work->id }}">

                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div>
                                                                <label class="block mb-1 font-medium">Job Role</label>
                                                                <input type="text" name="job_role[]" value="{{ $work->job_role }}" class="w-full border rounded px-3 py-2" placeholder="e.g., Software Engineer" />
                                                            </div>
                                                            <div>
                                                                <label class="block mb-1 font-medium">Organization</label>
                                                                <input type="text" name="organization[]" value="{{ $work->organization }}" class="w-full border rounded px-3 py-2" placeholder="e.g., XYZ Corp" />
                                                            </div>
                                                        </div>

                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div>
                                                                <label class="block mb-1 font-medium">Started From</label>
                                                                <input type="date" name="starts_from[]" value="{{ \Carbon\Carbon::parse($work->starts_from)->format('Y-m-d') }}" class="w-full border rounded px-3 py-2" />
                                                            </div>
                                                            <div>
                                                                <label class="block mb-1 font-medium">To</label>
                                                                <input 
                                                                    type="date" 
                                                                    name="end_to[]" 
                                                                    value="{{ !is_null($work->end_to) && $work->end_to !== 'Work here' ? \Carbon\Carbon::parse($work->end_to)->format('Y-m-d') : '' }}" 
                                                                    class="w-full border rounded px-3 py-2" 
                                                                    {{ is_null($work->end_to) || $work->end_to === 'Work here' ? 'disabled' : '' }} 
                                                                />

                                                                <label class="inline-flex items-center mt-2">
                                                                    <input 
                                                                            type="checkbox" 
                                                                            name="currently_working[{{ $loop->index }}]" 
                                                                            value="1"
                                                                            onchange="toggleEndDate(this)" 
                                                                            {{ is_null($work->end_to) || $work->end_to === 'Work here' ? 'checked' : '' }} 
                                                                        />

                                                                    <span class="ml-2">I currently work here</span>
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <button type="button" class="remove-work absolute top-2 right-2 text-red-600 font-bold text-lg" style="{{ $loop->first ? 'display:none;' : '' }}">&times;</button>
                                                    </div>
                                                    @endforeach
                                                </div>

                                                <div class="text-left">
                                                    <button type="button" id="add-work" class="text-green-600 text-sm">+ Add Experience</button>
                                                </div>

                                                <div class="mt-6 flex justify-end space-x-3">
                                                    <button @click.prevent="activeSubTab = 'skills'" class="border px-6 py-2 rounded hover:bg-gray-100">Next</button>
                                                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                const workContainer = document.getElementById('work-container');
                                                const addWorkBtn = document.getElementById('add-work');
                                                const workForm = document.getElementById('work-info-form');
                                                const successBox = document.getElementById('work-success');
                                                const successText = successBox.querySelector('.message-text');

                                                // Add Work Experience Entry
                                                addWorkBtn.addEventListener('click', () => {
                                                    const firstEntry = workContainer.querySelector('.work-entry');
                                                    const clone = firstEntry.cloneNode(true);

                                                    // Remove hidden input
                                                    const hiddenInput = clone.querySelector('input[name="work_id[]"]');
                                                    if (hiddenInput) hiddenInput.remove();

                                                    // Clear all inputs
                                                    clone.querySelectorAll('input').forEach(input => {
                                                        if (input.type === 'text' || input.type === 'date') {
                                                            input.value = '';
                                                        }
                                                        if (input.type === 'checkbox') {
                                                            input.checked = false;
                                                        }
                                                    });

                                                    // Enable end date input
                                                    const endDateInput = clone.querySelector('input[name="end_to[]"]');
                                                    if (endDateInput) endDateInput.disabled = false;

                                                    // Remove error messages
                                                    clone.querySelectorAll('p.text-red-600').forEach(p => p.remove());

                                                    // Show remove button
                                                    const removeBtn = clone.querySelector('.remove-work');
                                                    if (removeBtn) removeBtn.style.display = 'block';

                                                    workContainer.appendChild(clone);
                                                });

                                                // Remove Work Experience Entry
                                                workContainer.addEventListener('click', function (e) {
                                                    if (e.target.classList.contains('remove-work')) {
                                                        const entry = e.target.closest('.work-entry');
                                                        const entries = workContainer.querySelectorAll('.work-entry');
                                                        if (entries.length > 1) {
                                                            entry.remove();
                                                        }
                                                    }
                                                });

                                                // function toggleEndDate(checkbox) {
                                                //     const endDateInput = checkbox.closest('.work-entry').querySelector('input[name="end_to[]"]');
                                                //     if (checkbox.checked) {
                                                //         endDateInput.value = '';
                                                //         endDateInput.disabled = true;
                                                //     } else {
                                                //         endDateInput.disabled = false;
                                                //     }
                                                // }


                                                // Toggle End Date when checkbox clicked
                                                window.toggleEndDate = function (checkbox) {
                                                    const container = checkbox.closest('div');
                                                    const endDateInput = container.querySelector('input[name="end_to[]"]');
                                                    if (checkbox.checked) {
                                                        endDateInput.value = '';
                                                        endDateInput.disabled = true;
                                                    } else {
                                                        endDateInput.disabled = false;
                                                    }
                                                };

                                                // AJAX Form Submission
                                                workForm.addEventListener('submit', function (e) {
                                                    e.preventDefault();
                                                    const formData = new FormData(workForm);

                                                    // Clear previous errors
                                                    workForm.querySelectorAll('p.text-red-600').forEach(e => e.remove());

                                                    fetch(workForm.action, {
                                                        method: 'POST',
                                                        headers: {
                                                            'X-CSRF-TOKEN': workForm.querySelector('[name="_token"]').value,
                                                            'Accept': 'application/json'
                                                        },
                                                        body: formData
                                                    })
                                                    .then(response => {
                                                        if (!response.ok) return response.json().then(err => Promise.reject(err));
                                                        return response.json();
                                                    })
                                                    .then(data => {
                                                        successText.textContent = data.message || 'Work experience saved successfully!';
                                                        successBox.style.display = 'block';

                                                        setTimeout(() => {
                                                            successBox.style.display = 'none';
                                                            successText.textContent = '';
                                                        }, 3000);
                                                    })
                                                    .catch(error => {
                                                        const errors = error.errors || {};

                                                        Object.keys(errors).forEach(field => {
                                                            const match = field.match(/(\w+)\.(\d+)/);
                                                            if (match) {
                                                                const baseField = match[1];
                                                                const index = parseInt(match[2]);
                                                                const inputList = workForm.querySelectorAll(`[name="${baseField}[]"]`);
                                                                const input = inputList[index];

                                                                if (input) {
                                                                    const errorElem = document.createElement('p');
                                                                    errorElem.className = 'text-red-600 text-sm mt-1';
                                                                    errorElem.textContent = errors[field][0];
                                                                    input.insertAdjacentElement('afterend', errorElem);
                                                                }
                                                            }
                                                        });
                                                    });
                                                });
                                            });
                                        </script>
                                        
                                        <!-- Skills & Training Tab -->
                                        <div x-show="activeSubTab === 'skills'" x-transition>
                                            <!-- Skills Success Message -->
                                            <div id="trainer-skills-success" class="col-12 ml-auto mr-auto text-center alert alert-success alert-dismissible fade show" style="display: none;">
                                                <strong>Success!</strong> <span class="message-text"></span>
                                            </div>

                                            <!-- Skills & Training Form -->
                                            <form id="trainer-skills-info-form" method="POST" action="{{ route('trainer.skill.update') }}">
                                                @csrf
                                                <div x-show="activeSubTab === 'skills'" x-cloak class="space-y-4">

                                                    <!-- Training Skills -->
                                                    <div>
                                                        <label class="block text-sm font-medium mb-1">Skills</label>
                                                        <input type="text" name="training_skills" placeholder="E.g., JavaScript, UI/UX, Communication"
                                                            class="w-full border rounded px-3 py-2"
                                                            value="{{ old('training_skills', $trainerSkills->training_skills ?? '') }}" />
                                                        @error('training_skills')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Training Experience -->
                                                    <div>
                                                        <label class="block text-sm font-medium mb-1">Area of Interests</label>
                                                        <input type="text" name="training_experience" placeholder="E.g., Web Development, Teaching"
                                                            class="w-full border rounded px-3 py-2"
                                                            value="{{ old('training_experience', $trainerSkills->training_experience ?? '') }}" />
                                                        @error('training_experience')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Website Link -->
                                                    <div>
                                                        <label class="block text-sm font-medium mb-1">Website Link</label>
                                                        <input type="url" name="website_link" placeholder="https://example.com"
                                                            class="w-full border rounded px-3 py-2"
                                                            value="{{ old('website_link', $trainerSkills->website_link ?? '') }}" />
                                                        @error('website_link')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Portfolio Link -->
                                                    <div>
                                                        <label class="block text-sm font-medium mb-1">Portfolio Link</label>
                                                        <input type="url" name="portfolio_link" placeholder="https://portfolio.com"
                                                            class="w-full border rounded px-3 py-2"
                                                            value="{{ old('portfolio_link', $trainerSkills->portfolio_link ?? '') }}" />
                                                        @error('portfolio_link')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Buttons -->
                                                    <div class="flex justify-end gap-4 mt-6">
                                                        <button type="button" class="border rounded px-6 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                            @click="nextTab">
                                                            Next
                                                        </button>
                                                        <button type="button" id="save-trainer-skills-info"
                                                            class="bg-blue-700 text-white px-6 py-2 rounded text-sm hover:bg-blue-800">Save</button>
                                                    </div>
                                                </div>
                                            </form>


                                        </div>
                                        <script>
                                            document.getElementById('save-trainer-skills-info').addEventListener('click', function () {
                                                const form = document.getElementById('trainer-skills-info-form');
                                                const formData = new FormData(form);
                                                const successBox = document.getElementById('trainer-skills-success');
                                                const successText = successBox.querySelector('.message-text');

                                                // Clear previous error messages
                                                form.querySelectorAll('.text-red-600').forEach(el => el.remove());

                                                fetch(form.action, {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                                                        'Accept': 'application/json'
                                                    },
                                                    body: formData
                                                })
                                                .then(response => {
                                                    if (!response.ok) return response.json().then(err => Promise.reject(err));
                                                    return response.json();
                                                })
                                                .then(data => {
                                                    successText.textContent = data.message;
                                                    successBox.style.display = 'block';
                                                    setTimeout(() => {
                                                        successBox.style.display = 'none';
                                                        successText.textContent = '';
                                                    }, 3000);

                                                    if (typeof nextTab === "function") nextTab();
                                                })
                                                .catch(error => {
                                                    const errors = error.errors || {};
                                                    Object.keys(errors).forEach(field => {
                                                        const input = form.querySelector(`[name="${field}"]`);
                                                        if (input) {
                                                            const errorElem = document.createElement('p');
                                                            errorElem.className = 'text-red-600 text-sm mt-1';
                                                            errorElem.textContent = errors[field][0];
                                                            input.insertAdjacentElement('afterend', errorElem);
                                                        }
                                                    });
                                                });
                                            });
                                        </script>

                                        <!-- Additional Information Tab -->
                                        <div x-show="activeSubTab === 'additional'" x-transition>
                                            <h3 class="text-lg font-semibold mb-4">Upload Documents</h3>
                                           
                                             @php
                                                $user = auth()->user();
                                                $userId = $user->id;
                                                $profile = \App\Models\AdditionalInfo::where('user_id', $userId)
                                                    ->where('user_type', 'trainer')
                                                    ->where('doc_type', 'trainer_profile_picture')
                                                    ->first();

                                                $resume = \App\Models\AdditionalInfo::where('user_id', $userId)
                                                    ->where('user_type', 'trainer')
                                                    ->where('doc_type', 'resume')
                                                    ->first();  

                                                $certificate = \App\Models\AdditionalInfo::where('user_id', $userId)
                                                    ->where('user_type', 'trainer')
                                                    ->where('doc_type', 'training_certificate')
                                                    ->first();  
                                            @endphp

                                            <!-- Success Message -->
                                            <div id="trainer-additional-success" class="alert alert-success text-center" style="display: none;">
                                                <strong>Success!</strong> <span class="message-text"></span>
                                            </div>

                                            <!-- Trainer Additional Info Form -->
                                            <form id="trainer-additional-info-form" method="POST" action="{{ route('trainer.additional.update') }}" enctype="multipart/form-data">
                                                @csrf
                                                <div x-show="activeSubTab === 'additional'" x-cloak class="space-y-4 text-sm">

                                                    <!-- Resume -->
                                                    <div>
                                                        <label class="font-medium mb-1 block">Upload Resume</label>
                                                        <div class="flex flex-col gap-2">
                                                            @if($resume)
                                                                <div class="flex items-center gap-4">
                                                                    <a href="{{ asset($resume->document_path) }}" target="_blank" class="bg-blue-600 text-white px-3 py-1.5 text-xs rounded hover:bg-blue-700">📄 View Resume</a>
                                                                    <button type="button" class="delete-file text-red-600 text-sm" data-type="resume">Delete</button>
                                                                </div>
                                                            @endif
                                                            <input type="file" name="resume" accept=".pdf,.doc,.docx,.txt" class="border rounded p-2 w-full" />
                                                        </div>
                                                    </div>

                                                    <!-- Profile Picture -->
                                                    <div>
                                                        <label class="font-medium mb-1 block">Upload Profile Picture</label>
                                                        <div class="flex flex-col gap-2">
                                                            @if($profile)
                                                                <div class="flex items-center gap-4">
                                                                    <a href="{{ asset($profile->document_path) }}" target="_blank" class="bg-green-600 text-white px-3 py-1.5 text-xs rounded hover:bg-green-700">📄 View Profile</a>
                                                                    <button type="button" class="delete-file text-red-600 text-sm" data-type="profile_picture">Delete</button>
                                                                </div>
                                                            @endif
                                                            <input type="file" name="profile_picture" accept="image/*" class="border rounded p-2 w-full" />
                                                        </div>
                                                    </div>

                                                    <!-- Training Certificate -->
                                                    <div>
                                                        <label class="font-medium mb-1 block">Upload Training Certificate</label>
                                                        <div class="flex flex-col gap-2">
                                                            @if($certificate)
                                                                <div class="flex items-center gap-4">
                                                                    <a href="{{ asset($certificate->document_path) }}" target="_blank" class="bg-purple-600 text-white px-3 py-1.5 text-xs rounded hover:bg-purple-700">📄 View Certificate</a>
                                                                    <button type="button" class="delete-file text-red-600 text-sm" data-type="training_certificate">Delete</button>
                                                                </div>
                                                            @endif
                                                            <input type="file" name="training_certificate" accept=".pdf,.jpg,.jpeg,.png" class="border rounded p-2 w-full" />
                                                        </div>
                                                    </div>

                                                    <!-- Submit Button -->
                                                    <div class="flex justify-end mt-6">
                                                        <button type="button" id="save-trainer-additional-info" class="bg-blue-700 text-white px-6 py-2 rounded hover:bg-blue-800">Save</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- Delete Confirmation Modal -->
                                        <div id="trainerDeleteConfirmModal" class="fixed inset-0 bg-gray-100 bg-opacity-90 flex items-center justify-center z-50 hidden">
                                            <div class="bg-white rounded p-6 w-full max-w-sm shadow-lg">
                                                <h2 class="text-lg font-semibold mb-4">Confirm Delete</h2>
                                                <p class="text-gray-700 mb-6">Are you sure you want to delete <span id="trainer-delete-file-type" class="font-semibold"></span>?</p>
                                                <div class="flex justify-end gap-4">
                                                    <button type="button" id="trainerCancelDeleteBtn" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">Cancel</button>
                                                    <button type="button" id="trainerConfirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded">Yes, Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Script -->
                                        <script>
                                            // Save AJAX
                                            document.getElementById('save-trainer-additional-info').addEventListener('click', function () {
                                                const form = document.getElementById('trainer-additional-info-form');
                                                const formData = new FormData(form);
                                                const successBox = document.getElementById('trainer-additional-success');
                                                const successText = successBox.querySelector('.message-text');

                                                form.querySelectorAll('.text-red-600').forEach(e => e.remove());

                                                fetch(form.action, {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                                                        'Accept': 'application/json'
                                                    },
                                                    body: formData
                                                })
                                                .then(response => {
                                                    if (!response.ok) return response.json().then(err => Promise.reject(err));
                                                    return response.json();
                                                })
                                                .then(data => {
                                                    successText.textContent = data.message;
                                                    successBox.style.display = 'block';
                                                    setTimeout(() => {
                                                        successBox.style.display = 'none';
                                                        successText.textContent = '';
                                                        location.reload(); // 🔄 Reload after success
                                                    }, 1000);
                                                })
                                                .catch(error => {
                                                    const errors = error.errors || {};
                                                    Object.keys(errors).forEach(field => {
                                                        const input = form.querySelector(`[name="${field}"]`);
                                                        if (input) {
                                                            const errorElem = document.createElement('p');
                                                            errorElem.className = 'text-red-600 text-sm mt-1';
                                                            errorElem.textContent = errors[field][0];
                                                            input.insertAdjacentElement('afterend', errorElem);
                                                        }
                                                    });
                                                });
                                            });

                                            // Delete logic
                                            let trainerSelectedFileType = null;

                                            document.querySelectorAll('.delete-file').forEach(btn => {
                                                btn.addEventListener('click', function () {
                                                    trainerSelectedFileType = this.dataset.type;
                                                    document.getElementById('trainer-delete-file-type').textContent = trainerSelectedFileType.replace('_', ' ');
                                                    document.getElementById('trainerDeleteConfirmModal').classList.remove('hidden');
                                                });
                                            });

                                            document.getElementById('trainerCancelDeleteBtn').addEventListener('click', function () {
                                                document.getElementById('trainerDeleteConfirmModal').classList.add('hidden');
                                                trainerSelectedFileType = null;
                                            });

                                            document.getElementById('trainerConfirmDeleteBtn').addEventListener('click', function () {
                                                if (!trainerSelectedFileType) return;

                                                fetch(`{{ route('trainer.additional.delete', ':type') }}`.replace(':type', trainerSelectedFileType), {
                                                    method: 'DELETE',
                                                    headers: {
                                                        'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                                                        'Accept': 'application/json'
                                                    }
                                                })
                                                .then(res => res.json())
                                                .then(data => {
                                                    document.getElementById('trainerDeleteConfirmModal').classList.add('hidden');
                                                    trainerSelectedFileType = null;

                                                    const successBox = document.getElementById('trainer-additional-success');
                                                    const successText = successBox.querySelector('.message-text');
                                                    successText.textContent = data.message;
                                                    successBox.style.display = 'block';

                                                    setTimeout(() => {
                                                        successBox.style.display = 'none';
                                                        successText.textContent = '';
                                                        location.reload(); // 🔄 Reload after delete
                                                    }, 3000);
                                                })
                                                .catch(() => {
                                                    alert('Delete failed.');
                                                    document.getElementById('trainerDeleteConfirmModal').classList.add('hidden');
                                                });
                                            });
                                        </script>


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
           







      
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.getElementById('deleteAccountBtn').addEventListener('click', function (e) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action will permanently delete your account!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteAccountForm').submit();
        }
    });
});
</script>
@include('site.trainer.componants.footer')