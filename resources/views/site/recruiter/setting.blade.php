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

           <!-- Sidebar -->
            @include('site.recruiter.componants.sidebar')	

            <div class="flex-1 flex flex-col">
                <nav class="bg-white shadow-md px-6 py-3 flex items-center justify-between">
                    <div class="flex items-center space-x-6 w-1/2">
                        <button 
                            @click="sidebarOpen = !sidebarOpen" 
                            class="text-gray-700 hover:text-blue-600 focus:outline-none"
                            title="Toggle Sidebar"
                            aria-label="Toggle Sidebar"
                            type="button"
                            >
                            <i data-feather="menu" class="w-6 h-6"></i>
                        </button>
                        <!-- <div class="relative w-full">
                            <input type="text" placeholder="Search for talent" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                                <i class="fas fa-search"></i>
                            </button>
                        </div> -->
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                        <button aria-label="Notifications" class="text-gray-700 hover:text-blue-600 focus:outline-none relative">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-600 text-white">
                            <i class="feather-bell text-xl"></i>
                            </span>
                            <span class="absolute top-0 right-0 inline-block w-2.5 h-2.5 bg-red-600 rounded-full border-2 border-white"></span>
                        </button>
                        </div>
                        <div class="relative inline-block">
                        <select aria-label="Select Language" 
                                class="appearance-none border border-gray-300 rounded-md px-10 py-1 text-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="en" selected>English</option>
                            <option value="es">Spanish</option>
                            <option value="fr">French</option>
                            <!-- add more languages as needed -->
                        </select>
                        <span class="pointer-events-none absolute left-2 top-1/2 transform -translate-y-1/2 inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-600 text-white">
                            <i class="feather-globe"></i>
                        </span>
                        </div>
                    <div>
                        <a href="#" role="button"
                            class="inline-flex items-center space-x-1 border border-blue-600 bg-blue-600 text-white rounded-md px-3 py-1.5 transition">
                        <i class="fa fa-user-circle" aria-hidden="true"></i>
                            <span> {{$companyDetails->name}}</span>
                            
                        </a>
                    </div>
                    </div>
                </nav>

                <main class="p-6 bg-gray-100 flex-1 overflow-y-auto" x-data="{ activeSection: 'profile', activeSubTab: 'company' }">
                    <h2 class="text-2xl font-semibold mb-6">Settings</h2>

                    <div class="flex">
                        <!-- Sidebar -->
                        <aside class="w-60 bg-white p-4 border-r mt-4 shadow rounded-lg">
                            <ul class="space-y-2">
                                <li>
                                <a
                                    href="#"
                                    @click.prevent="activeSection = 'profile'; activeSubTab = 'company'"
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
                                <div x-show="activeSection === 'profile'"  x-transition>
                                    <!-- Company Header -->
                                    <div class="flex items-center space-x-4 mb-4">
                                        @php
                                            use App\Models\AdditionalInfo;
                                            $userId = auth()->id();
                                            
                                            $profile = AdditionalInfo::where('user_id', $userId)
                                                        ->where('doc_type', 'company_profile')
                                                        ->first();
                                        @endphp

                                        <!-- Image Preview -->
                                        <img 
                                                id="profilePreview" 
                                                src="{{ $profile ? asset($profile->document_path) : 'https://www.lscny.org/app/uploads/2018/05/mystery-person.png' }}" 
                                                class="h-20 w-20 rounded-md mb-2" 
                                                alt="Profile Preview" 
                                            />


                                        <div>
                                            <h3 class="text-xl font-semibold">                {{$companyDetails->name}}
                                            </h3>
                                            <p class="text-gray-600">{{$companyDetails->email}}</p>
                                            
                                        </div>
                                    </div>
                                    @if(session('success'))
                                        <span id="successMessage" class="inline-flex items-center bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 gap-2">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <span class="text-sm font-medium">{{ session('success') }}</span>
                                        </span>

                                        <script>
                                            setTimeout(() => {
                                                const el = document.getElementById('successMessage');
                                                if (el) {
                                                    el.classList.add('opacity-0'); 
                                                    setTimeout(() => el.style.display = 'none', 2000); 
                                                }
                                            }, 10000); 
                                        </script>
                                    @endif
                                    @if(session('error'))
                                        <span id="successMessage" class="inline-flex items-center bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 gap-2">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <span class="text-sm font-medium">{{ session('error') }}</span>
                                        </span>

                                        <script>
                                            setTimeout(() => {
                                                const el = document.getElementById('successMessage');
                                                if (el) {
                                                    el.classList.add('opacity-0'); 
                                                    setTimeout(() => el.style.display = 'none', 2000); 
                                                }
                                            }, 10000); 
                                        </script>
                                    @endif

                                    <!-- Inner Tabs -->
                                    <div class="border-b mb-4">
                                        <nav class="flex space-x-6">
                                            <button
                                                @click="activeSubTab = 'company'"
                                                :class="activeSubTab === 'company' ? 'pb-2 border-b-2 border-blue-600 font-medium' : 'pb-2 text-gray-500 hover:text-black'"
                                                class="focus:outline-none active"
                                            >
                                                Company information
                                            </button>
                                            <button
                                                @click="activeSubTab = 'documents'"
                                                :class="activeSubTab === 'documents' ? 'pb-2 border-b-2 border-blue-600 font-medium' : 'pb-2 text-gray-500 hover:text-black'"
                                                class="focus:outline-none"
                                            >
                                                Documents
                                            </button>
                                        </nav>
                                    </div>

                                    <!-- Inner Tab Contents -->
                                    <div>
                                        <!-- Company Info Tab -->
                                        <div x-show="activeSubTab === 'company'" x-transition>
                                            <!-- Success Message -->
                                            <div id="company-profile-success" class="alert alert-success text-center" style="display: none;">
                                                <strong>Success!</strong> <span class="message-text"></span>
                                            </div>    
                                            <form id="company-profile-form" action="{{ route('recruiter.company.profile.update') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                                @csrf

                                                <!-- Company Info -->
                                                <div>
                                                    <label class="block mb-1 font-medium">Company name <span style="color: red; font-size: 17px;">*</span></label>
                                                    <input type="text" name="company_name" value="{{ $companyDetails->company_name }}" class="w-full border rounded px-3 py-2" />
                                                    @error("company_name") <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>

                                                <div>
                                                    <label class="block mb-1 font-medium">Company email <span style="color: red; font-size: 17px;">*</span></label>
                                                    <input type="email" name="business_email" value="{{ $companyDetails->business_email }}" class="w-full border rounded px-3 py-2" />
                                                    @error("business_email") <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>

                                                <div>
                                                    <label class="block mb-1 font-medium">Company Phone number <span style="color: red; font-size: 17px;">*</span></label>
                                                    <input type="text" name="company_phone_number" value="{{ $companyDetails->company_phone_number }}" class="w-full border rounded px-3 py-2" />
                                                    @error("company_phone_number") <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>

                                                <div>
                                                    <label class="block mb-1 font-medium">Industry type <span style="color: red; font-size: 17px;">*</span></label>
                                                    <select class="w-full border rounded px-3 py-2" name="industry_type">
                                                        <option value="">Select Industry</option>
                                                        <option value="Information technology" {{ old('industry_type', $companyDetails->industry_type) == 'Information technology' ? 'selected' : '' }}>Information technology</option>
                                                        <option value="Healthcare" {{ old('industry_type', $companyDetails->industry_type) == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                                                        <option value="Finance" {{ old('industry_type', $companyDetails->industry_type) == 'Finance' ? 'selected' : '' }}>Finance</option>
                                                    </select>
                                                    @error("industry_type") <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>

                                                <div>
                                                    <label class="block mb-1 font-medium">Company establishment date <span style="color: red; font-size: 17px;">*</span></label>
                                                    <input id="establishment_date" name="establishment_date" value="{{ \Carbon\Carbon::parse($companyDetails->updated_at)->format('d-m-Y') }}" class="w-full border rounded px-3 py-2 form-control" />
                                                    @error("establishment_date") <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>

                                                <div>
                                                    <label class="block mb-1 font-medium">Company website <span style="color: red; font-size: 17px;">*</span></label>
                                                    <input type="text" name="company_website" value="{{ $companyDetails->company_website }}" class="w-full border rounded px-3 py-2" />
                                                    @error("company_website") <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>

                                                <!-- Recruiter Info -->
                                                <div class="col-span-2 mt-4">
                                                    <h3 class="text-xl font-semibold">Recruiter Details</h3>
                                                </div>

                                                <div>
                                                    <label class="block mb-1 font-medium">Recruiter Name <span style="color: red; font-size: 17px;">*</span></label>
                                                    <input type="text" name="name" value="{{ $companyDetails->name }}" class="w-full border rounded px-3 py-2" />
                                                    @error("name") <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>

                                                <div>
                                                    <label class="block mb-1 font-medium">Recruiter Email <span style="color: red; font-size: 17px;">*</span></label>
                                                    <input type="text" name="email" value="{{ $companyDetails->email }}" class="w-full border rounded px-3 py-2" />
                                                    @error("email") <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>

                                                <div class="">
                                                    <label class="block mb-1 font-medium">National ID Number <span style="color: red; font-size: 17px;">*</span></label>
                                                    <input type="text" name="national_id" value="{{ $companyDetails->national_id }}" class="w-full border rounded px-3 py-2" />
                                                    @error("national_id") <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                                                </div>

                                                <!-- Buttons -->
                                                <div class="col-span-2 mt-6 flex justify-end space-x-3">
                                                    <button @click.prevent="activeSubTab = 'documents'" class="border px-6 py-2 rounded hover:bg-gray-100">Next</button>
                                                    <button type="submit" id="save-company-profile" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                        <script>
                                            document.getElementById('save-company-profile').addEventListener('click', function (e) {    
                                                e.preventDefault(); // Prevent default form submission

                                                const form = document.getElementById('company-profile-form');
                                                const formData = new FormData(form);
                                                const successBox = document.getElementById('company-profile-success');
                                                const successText = successBox.querySelector('.message-text');

                                                // Clear previous error messages
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
                                                    // Show success alert
                                                    successText.textContent = data.message;
                                                    successBox.style.display = 'block';

                                                    // Hide after 3 seconds
                                                    setTimeout(() => {
                                                        successBox.style.display = 'none';
                                                        successText.textContent = '';
                                                    }, 3000);

                                                    // Optional: switch to next tab
                                                    if (typeof nextTab === "function") nextTab();
                                                })
                                                .catch(error => {
                                                    const errors = error.errors || {};
                                                    Object.keys(errors).forEach(field => {
                                                        const message = errors[field][0];
                                                        const input = form.querySelector(`[name="${field}"]`);
                                                        if (input) {
                                                            const errorElem = document.createElement('p');
                                                            errorElem.className = 'text-red-600 text-sm mt-1';
                                                            errorElem.textContent = message;
                                                            input.insertAdjacentElement('afterend', errorElem);
                                                        }
                                                    });
                                                });
                                            });
                                        </script>

    

                                        <!-- Documents Tab -->
                                        <div x-show="activeSubTab === 'documents'" x-transition>
                                            <!-- Success Alert -->
                                            <div id="company-docs-success" class="alert alert-success mt-4 text-center hidden">
                                                <span class="message-text font-semibold"></span>
                                            </div>

                                            <form id="company-documents-form" method="POST" action="{{ route('recruiter.company.document.update') }}" enctype="multipart/form-data" class="space-y-4 text-sm">
                                                @csrf
                                                <h3 class="text-lg font-semibold mb-4">Upload Documents</h3>

                                                <!-- Company Profile Picture -->
                                                <div>
                                                    <label class="block font-medium mb-1">Company Profile Picture <span style="color: red; font-size: 17px;">*</span></label>
                                                    @if($companyProfile)
                                                        <div class="flex items-center gap-4">
                                                            <a href="{{ asset($companyProfile->document_path) }}" target="_blank" class="bg-green-600 text-white px-3 py-1.5 rounded text-xs hover:bg-green-700">📄 View Image</a>
                                                            <button type="button" class="delete-doc text-red-600 text-sm" data-type="company_profile">Delete</button>
                                                        </div>
                                                    @endif
                                                    <input type="file" name="company_profile" accept="image/*" class="w-full border rounded px-3 py-2" />
                                                </div>

                                                <!-- Registration Document -->
                                                <div>
                                                    <label class="block font-medium mb-1">Registration Document <span style="color: red; font-size: 17px;">*</span></label>
                                                    @if($registrationDoc)
                                                        <div class="flex items-center gap-4">
                                                            <a href="{{ asset($registrationDoc->document_path) }}" target="_blank" class="bg-blue-600 text-white px-3 py-1.5 rounded text-xs hover:bg-blue-700">📄 View Document</a>
                                                            <button type="button" class="delete-doc text-red-600 text-sm" data-type="register_document">Delete</button>
                                                        </div>
                                                    @endif
                                                    <input type="file" name="register_document" accept=".pdf,.doc,.docx" class="w-full border rounded px-3 py-2" />
                                                </div>

                                                <div class="flex justify-end mt-4">
                                                    <button type="button" id="save-company-docs" class="bg-blue-700 text-white px-6 py-2 rounded hover:bg-blue-800">Update</button>
                                                </div>
                                            </form>

                                            <!-- Delete Modal -->
                                            <div id="deleteCompanyDocModal" class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50 hidden">
                                                <div class="bg-white p-6 rounded shadow-lg w-full max-w-sm">
                                                    <h2 class="text-lg font-semibold mb-4">Confirm Delete</h2>
                                                    <p class="mb-4 text-gray-700">Are you sure you want to delete <span id="delete-doc-type" class="font-bold"></span>?</p>
                                                    <div class="flex justify-end gap-4">
                                                        <button type="button" id="cancelCompanyDocDelete" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
                                                        <button type="button" id="confirmCompanyDocDelete" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            let selectedCompanyDocType = null;

                                            document.getElementById('save-company-docs').addEventListener('click', function () {
                                                const form = document.getElementById('company-documents-form');
                                                const formData = new FormData(form);
                                                const successBox = document.getElementById('company-docs-success');
                                                const successText = successBox.querySelector('.message-text');

                                                fetch(form.action, {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                                                        'Accept': 'application/json'
                                                    },
                                                    body: formData
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    successText.textContent = data.message;
                                                    successBox.classList.remove('hidden');
                                                    setTimeout(() => location.reload(), 2000); // Reload after success
                                                })
                                                .catch(error => {
                                                    alert('Failed to upload documents.');
                                                });
                                            });

                                            document.querySelectorAll('.delete-doc').forEach(btn => {
                                                btn.addEventListener('click', () => {
                                                    selectedCompanyDocType = btn.dataset.type;
                                                    document.getElementById('delete-doc-type').textContent = selectedCompanyDocType.replace(/_/g, ' ');
                                                    document.getElementById('deleteCompanyDocModal').classList.remove('hidden');
                                                });
                                            });

                                            document.getElementById('cancelCompanyDocDelete').addEventListener('click', () => {
                                                selectedCompanyDocType = null;
                                                document.getElementById('deleteCompanyDocModal').classList.add('hidden');
                                            });

                                            document.getElementById('confirmCompanyDocDelete').addEventListener('click', () => {
                                                if (!selectedCompanyDocType) return;

                                                fetch(`{{ route('recruiter.company.document.delete', ':type') }}`.replace(':type', selectedCompanyDocType), {
                                                    method: 'DELETE',
                                                    headers: {
                                                        'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                                                        'Accept': 'application/json'
                                                    }
                                                })
                                                .then(res => res.json())
                                                .then(data => {
                                                    document.getElementById('deleteCompanyDocModal').classList.add('hidden');
                                                    selectedCompanyDocType = null;

                                                    const successBox = document.getElementById('company-docs-success');
                                                    const successText = successBox.querySelector('.message-text');
                                                    successText.textContent = data.message;
                                                    successBox.classList.remove('hidden');
                                                    setTimeout(() => location.reload(), 2000);
                                                })
                                                .catch(() => {
                                                    alert('Delete failed.');
                                                    document.getElementById('deleteCompanyDocModal').classList.add('hidden');
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

                                <!-- Delete Account Section -->
                                <div x-show="activeSection === 'delete'" x-transition>
                                    <h3 class="text-xl font-semibold mb-4 text-red-600">Delete Account</h3>
                                    <p>This action is irreversible. Are you sure you want to delete your account?</p>
                                    <!-- <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Delete Account</button> -->
                                    <form id="
                                    " action="{{ route('recruiter.destroy') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" id="deleteAccountBtn" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                            Delete Account
                                        </button>
                                    </form>
                                </div>
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