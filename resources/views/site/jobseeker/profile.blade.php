<?php
$user = Auth()->user();
$edu = $user->educationDetail;
$work = $user->workExperience;
$skills = $user->skills;
echo "<pre>";
print_r($edu);exit;
echo "</pre>";
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

    @include('site.componants.navbar')	
        <div class="page-content">
            <div class="relative bg-center bg-cover h-[400px] flex items-center" style="background-image: url('/images/banner/service page banner.png');">
                <div class="absolute inset-0 bg-white bg-opacity-10"></div>
                <div class="relative z-10 container mx-auto px-4">
                    <div class="space-y-2">
                        <h2 class="text-3xl font-bold text-white ml-[10%]">Profile</h2>
                    </div>
                </div>
            </div>
        </div>
    <main class="w-11/12 mx-auto py-8" x-data="{ tab: 'personal' }">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">
            <!-- Left/Main Content -->
            <div class="flex-1">
                <!-- Header -->
                <div class="max-w-6xl mx-auto py-6 px-4">
                    <div class="flex justify-between items-start">
                    <div class="flex gap-4">
                        <img src="https://randomuser.me/api/portraits/men/1.jpg" class="h-20 w-20 rounded-md" alt="Profile" />
                        <div class="mt-1">
                        <h2 class="text-xl font-semibold">{{$data->name}}</h2>
                        <p class="text-sm text-gray-600">{{$data->email}}</p>
                        <p class="text-sm text-gray-600">{{$data->phone_number}}</p>
                        </div>
                    </div>
                    <form action="{{ route('jobseeker.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="border rounded px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 flex items-center gap-2">
                            <i class="fas fa-power-off"></i>
                            Logout
                        </button>
                    </form>
                    </div>
                </div>

                <hr />

                <!-- Main Section -->
                <div class="max-w-6xl mx-auto flex px-4 py-6 gap-6">
                    <!-- Sidebar -->
                    <div x-data="{ tab: 'profile', profileTab: 'personal' }" class="flex max-w-5xl w-full space-x-6">
                    <!-- Sidebar Outer Tabs -->
                    <div class="w-1/5 space-y-2" style="background-color: rgb(238, 238, 238);">
                        <ul class="text-sm font-medium">
                        <li>
                        <button
                        @click="tab = 'profile'"
                        :class="tab === 'profile' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                        class="w-full text-left px-4 py-2 rounded"
                        >
                        Profile
                        </button>
                    </li>
                    <li>
                        <button
                        @click="tab = 'cart'"
                        :class="tab === 'cart' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                        class="w-full text-left px-4 py-2 rounded"
                        >
                        Cart
                        </button>
                    </li>
                    <li>
                        <button
                        @click="tab = 'training'"
                        :class="tab === 'training' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                        class="w-full text-left px-4 py-2 rounded"
                        >
                        Training
                        </button>
                    </li>
                    <li>
                        <button
                        @click="tab = 'mentorship'"
                        :class="tab === 'mentorship' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                        class="w-full text-left px-4 py-2 rounded"
                        >
                        Mentorship
                        </button>
                    </li>
                    <li>
                        <button
                        @click="tab = 'assessment'"
                        :class="tab === 'assessment' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                        class="w-full text-left px-4 py-2 rounded"
                        >
                        Assessment
                        </button>
                    </li>
                    <li>
                        <button
                        @click="tab = 'coaching'"
                        :class="tab === 'coaching' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                        class="w-full text-left px-4 py-2 rounded"
                        >
                        Coaching
                        </button>
                    </li>
                    <li>
                        <button
                        @click="tab = 'subscription'"
                        :class="tab === 'subscription' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                        class="w-full text-left px-4 py-2 rounded"
                        >
                        Subscription
                        </button>
                    </li>
                    <li>
                        <button
                        @click="tab = 'payment'"
                        :class="tab === 'payment' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                        class="w-full text-left px-4 py-2 rounded"
                        >
                        Payment
                        </button>
                    </li>
                    <li>
                        <button
                        @click="tab = 'certificates'"
                        :class="tab === 'certificates' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                        class="w-full text-left px-4 py-2 rounded"
                        >
                        Certificates
                        </button>
                    </li>
                    <li>
                        <button
                        @click="tab = 'settings'"
                        :class="tab === 'settings' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                        class="w-full text-left px-4 py-2 rounded"
                        >
                        Settings
                        </button>
                    </li>
                    <!-- More outer tabs can be added here -->
                    </ul>
                </div>

                <!-- Main Content -->
                <div class="w-4/5 space-y-6">
                    <!-- Profile Tab Content -->
                    <div x-show="tab === 'profile'" x-cloak 
                        x-data="{
                            profileTab: 'personal',
                            tabs: ['personal', 'education', 'work', 'skills', 'additional'],
                            nextTab() {
                                const currentIndex = this.tabs.indexOf(this.profileTab);
                                if (currentIndex < this.tabs.length - 1) {
                                    this.profileTab = this.tabs[currentIndex + 1];
                                }
                            }
                        }">
                        <h2 class="text-xl font-semibold mb-4">My Profile</h2>
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


                        <div class="border-b flex space-x-6 text-sm font-medium">
                            <button
                            @click="profileTab = 'personal'"
                            :class="profileTab === 'personal' ? 'border-b-2 border-black text-black' : 'text-gray-600'"
                            class="pb-2"
                            >Personal Information</button>
                            <button
                            @click="profileTab = 'education'"
                            :class="profileTab === 'education' ? 'border-b-2 border-black text-black' : 'text-gray-600'"
                            class="pb-2"
                            >Educational Details</button>
                            <button
                            @click="profileTab = 'work'"
                            :class="profileTab === 'work' ? 'border-b-2 border-black text-black' : 'text-gray-600'"
                            class="pb-2"
                            >Work Experience</button>
                            <button
                            @click="profileTab = 'skills'"
                            :class="profileTab === 'skills' ? 'border-b-2 border-black text-black' : 'text-gray-600'"
                            class="pb-2"
                            >Skills & Training</button>
                            <button
                            @click="profileTab = 'additional'"
                            :class="profileTab === 'additional' ? 'border-b-2 border-black text-black' : 'text-gray-600'"
                            class="pb-2"
                            >Additional Information</button>
                        </div>
                        <div class="space-y-4 mt-4">
                            <input type="hidden" name="id" value="{{ $user->id }}">

                            <!-- Personal Info -->
                            <form action="{{ route('jobseeker.profile.update') }}" method="POST">
                                @csrf
                            
                                <div x-show="profileTab === 'personal'" x-cloak class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Name</label>
                                        <input type="text" name="name" placeholder="Name" class="w-full border rounded px-3 py-2" value="{{ Auth()->user()->name ?? '' }}" />
                                        @error('name')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Email address</label>
                                            <input type="email" name="email" placeholder="Email address"  class="w-full border rounded px-3 py-2"  value="{{ Auth()->user()->email ?? '' }}"/>
                                            @error('email')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Gender</label>
                                            <select class="w-full border rounded px-3 py-2" name="gender">
                                                <option>{{Auth()->user()->gender}}</option>
                                                <option selected>Male</option>
                                                <option>Female</option>
                                            </select>
                                            @error('gender')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Phone number</label>
                                            <input type="tel" placeholder="Phone number" name="phone_number" class="w-full border rounded px-3 py-2" value="{{ Auth()->user()->phone_number ?? '' }}"/>
                                            @error('phone_number')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium mb-1">Date of Birth</label>
                                            <input 
                                                name="dob" 
                                                id="dob" 
                                                class="w-full border rounded px-3 py-2" 
                                                value="{{ optional(Auth()->user())->date_of_birth ? date('Y-m-d', strtotime(Auth()->user()->date_of_birth)) : '' }}" 
                                            />
                                            @error('dob')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium mb-1">Address</label>
                                        <input type="text" placeholder="Enter address" name="address"  class="w-full border rounded px-3 py-2" value="{{ Auth()->user()->address ?? '' }}"/>
                                        @error('address')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium mb-1">City</label>
                                        <input type="text" name="city" placeholder="Enter city" class="w-full border rounded px-3 py-2" value="{{ Auth()->user()->city ?? '' }}"/>
                                        @error('city')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="md:col-span-2 flex justify-end gap-4 mt-4">
                                        <button 
                                            type="button"
                                            class="border rounded px-6 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                                            @click="nextTab"
                                            x-show="profileTab !== 'additional'"
                                        >
                                            Next
                                        </button>
                                        <button type="submit" class="bg-blue-700 text-white px-6 py-2 rounded text-sm hover:bg-blue-800">Save</button>
                                    </div>
                                </div>
                            </form>
                            
                            <!-- Educational Details -->
                            <form action="{{ route('jobseeker.education.update') }}" method="POST">
                                @csrf
                                <div x-show="profileTab === 'education'" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-4" x-data="yearSelect" x-init="init()">

                                    <!-- Highest Qualification -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Highest Qualification</label>
                                        <select class="w-full border rounded px-3 py-2" name="high_education">
                                            <option value="">Select Qualification</option>
                                            @foreach(['High School', 'Diploma', "Bachelor's", "Master's", 'PhD', 'Other'] as $qualification)
                                                <option value="{{ $qualification }}"
                                                    {{ old('high_education', $edu->high_education ?? '') == $qualification ? 'selected' : '' }}>
                                                    {{ $qualification }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('high_education')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Field of Study -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Field of Study</label>
                                        <select class="w-full border rounded px-3 py-2" name="field_of_study">
                                            <option value="">Select Field</option>
                                            @foreach(['Engineering', 'Computer Science', 'Business', 'Medicine', 'Arts', 'Law', 'Other'] as $field)
                                                <option value="{{ $field }}"
                                                    {{ old('field_of_study', $edu->field_of_study ?? '') == $field ? 'selected' : '' }}>
                                                    {{ $field }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('field_of_study')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Institution Name -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Institution Name</label>
                                        <input type="text" name="institution" placeholder="Enter Institution Name"
                                            class="w-full border rounded px-3 py-2"
                                            value="{{ old('institution', $edu->institution ?? '') }}">
                                        @error('institution')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror    
                                    </div>

                                    <!-- Graduation Year -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Graduation Year</label>
                                        <select class="w-full border rounded px-3 py-2" name="graduate_year">
                                            <option value="">Select Year</option>
                                            @foreach(['2025', '2024', '2023', '2022', '2021', '2020', '2019', '2018', '2017', '2016', '2015', '2010-2014', 'Before 2010'] as $year)
                                                <option value="{{ $year }}"
                                                    {{ old('graduate_year', $edu->graduate_year ?? '') == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('graduate_year')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Buttons -->
                                    <div class="flex justify-end gap-4 mt-6 md:col-span-2 w-full px-3 py-2 ">
                                        <button type="button" class="border rounded px-6 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                @click="nextTab" x-show="profileTab !== 'additional'">
                                            Next
                                        </button>
                                        <button type="submit" class="bg-blue-700 text-white px-6 py-2 rounded text-sm hover:bg-blue-800">
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </form>
  


                            <!-- Work Experience -->
                            <form action="{{ route('jobseeker.workexprience.update') }}" method="POST">
                                @csrf
                                <div x-show="profileTab === 'work'" x-cloak x-data="workExperience()" class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                    <!-- Job Role -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Job Role</label>
                                        <input type="text" placeholder="Enter Job Role" name="job_role" class="w-full border rounded px-3 py-2"
                                            value="{{ old('job_role', $work->job_role ?? '') }}" />
                                        @error('job_role')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror    
                                    </div>

                                    <!-- Organization -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Organization</label>
                                        <input type="text" name="organization" placeholder="Enter Organization Name"
                                            class="w-full border rounded px-3 py-2"
                                            value="{{ old('organization', $work->organization ?? '') }}" />
                                        @error('organization')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror      
                                    </div>

                                    <!-- Started From -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Started From</label>
                                        <input  name="starts_from" id="starts_from" class="w-full border rounded px-3 py-2"
                                            value="{{ old('starts_from', $work->starts_from ?? '') }}" />
                                        @error('starts_from')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror        
                                    </div>

                                    <!-- To + Checkbox -->
                                    <div x-data="{ currentlyWorking: '{{ $work->end_to ?? '' }}' === 'Work here' }">
                                        <label class="block text-sm font-medium mb-1">To</label>
                                        <input 
                                            
                                            class="w-full border rounded px-3 py-2 mb-2" 
                                            name="end_to"
                                            id="end_to"
                                            :disabled="currentlyWorking"
                                            :value="currentlyWorking ? '' : '{{ old('end_to', $work->end_to !== 'Work here' ? $work->end_to : '') }}'"
                                        />

                                        @error('end_to')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror     

                                        <label class="inline-flex items-center space-x-2 mt-3">
                                            <input 
                                                type="checkbox" 
                                                x-model="currentlyWorking" 
                                                name="currently_working" 
                                                value="1" 
                                                :checked="currentlyWorking"
                                            />
                                            <span>I currently work here</span>
                                        </label>
                                    </div>



                                    <!-- Buttons -->
                                    <div class="flex justify-end gap-4 mt-6 md:col-span-2 w-full px-3 py-2 ">
                                        <button type="button" class="border rounded px-6 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                @click="nextTab" x-show="profileTab !== 'additional'">
                                            Next
                                        </button>
                                        <button type="submit" class="bg-blue-700 text-white px-6 py-2 rounded text-sm hover:bg-blue-800">
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </form>  


                            <!-- Skills & Training -->
                            <form action="{{ route('jobseeker.skill.update') }}" method="POST">
                                @csrf
                                <div x-show="profileTab === 'skills'" x-cloak class="space-y-4">

                                    <!-- Skills -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Skills</label>
                                        <input type="text" name="skills" placeholder="E.g., JavaScript, Excel, Marketing"
                                            class="w-full border rounded px-3 py-2"
                                            value="{{ old('skills', $skills->skills ?? '') }}" />
                                        @error('skills')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror  
                                    </div>

                                    <!-- Area of Interests -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Area of Interests</label>
                                        <input type="text" name="interest" placeholder="E.g., Data Science, Graphic Design"
                                            class="w-full border rounded px-3 py-2"
                                            value="{{ old('interest', $skills->interest ?? '') }}" />
                                        @error('interest')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror    
                                    </div>

                                    <!-- Job Categories -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Job Categories</label>
                                        <select class="w-full border rounded px-3 py-2" name="job_category">
                                            <option value="">{{ old('job_category', $skills->job_category ?? 'Select Job Category') }}</option>
                                            <option value="IT & Software">IT & Software</option>
                                            <option value="Sales & Marketing">Sales & Marketing</option>
                                            <option value="Design & Creative">Design & Creative</option>
                                            <option value="Finance & Accounting">Finance & Accounting</option>
                                            <option value="Education & Training">Education & Training</option>
                                            <option value="Healthcare">Healthcare</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        @error('job_category')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Website Link -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Website Link</label>
                                        <input type="url" name="website_link" placeholder="https://yourwebsite.com"
                                            class="w-full border rounded px-3 py-2"
                                            value="{{ old('website_link', $skills->website_link ?? '') }}" />
                                        @error('website_link')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror    
                                    </div>

                                    <!-- Portfolio Link -->
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Portfolio Link</label>
                                        <input type="url" name="portfolio_link" placeholder="https://yourportfolio.com"
                                            class="w-full border rounded px-3 py-2"
                                            value="{{ old('portfolio_link', $skills->portfolio_link ?? '') }}" />
                                        @error('portfolio_link')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror    
                                    </div>

                                    <!-- Buttons -->
                                    <div class="flex justify-end gap-4 mt-6">
                                        <button type="button" class="border rounded px-6 py-2 text-sm text-gray-700 hover:bg-gray-100" @click="nextTab"
                                            x-show="profileTab !== 'additional'">
                                            Next
                                        </button>
                                        <button type="submit" class="bg-blue-700 text-white px-6 py-2 rounded text-sm hover:bg-blue-800">Save</button>
                                    </div>
                                </div>
                            </form>  

                            <!-- Additional Info -->
                            <div x-show="profileTab === 'additional'" x-cloak class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Upload resume</label>
                                    <div class="flex gap-2 items-center">
                                        <input type="file" class="border rounded-md p-2 w-full text-sm" />
                                        <button
                                            class="bg-red-500 text-white px-4 py-2 rounded-md text-sm whitespace-nowrap w-38"
                                            type="button"
                                        >
                                            Remove
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1">Upload Profile</label>
                                    <div class="flex gap-2 items-center">
                                        <input type="file" class="border rounded-md p-2 w-full text-sm" />
                                        <button
                                            class="bg-red-500 text-white px-4 py-2 rounded-md text-sm whitespace-nowrap w-38"
                                            type="button"
                                        >
                                            Remove
                                        </button>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-4 mt-6">
                                <button class="bg-blue-700 text-white px-6 py-2 rounded text-sm hover:bg-blue-800">Save</button>
                                </div>
                            </div>

                        </div>
                        <!-- <div class="flex justify-end gap-4 mt-6">
                            <button 
                                class="border rounded px-6 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                                @click="nextTab"
                                x-show="profileTab !== 'additional'"
                            >
                                Next
                            </button>
                            <button class="bg-blue-700 text-white px-6 py-2 rounded text-sm hover:bg-blue-800">Save</button>
                        </div> -->
                    </div>

                    <div x-show="tab === 'cart'" x-cloak>
                        <h2 class="text-xl font-semibold mb-4">My cart</h2>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            
                            <!-- Left: Course List -->
                            <div class="lg:col-span-2 space-y-4">
                            <!-- Course Item -->
                            <div class="flex border rounded-lg p-4 gap-4">
                            <!-- Left side: Image + Remove -->
                            <div class="flex flex-col items-center gap-2">
                                <img src="/images/gallery/pic-4.png" alt="Course" class="w-48 h-48 object-cover rounded" />
                                <button class="text-red-500 text-sm hover:underline">🗑 Remove</button>
                            </div>

                            <!-- Right side: Course Info -->
                            <div class="flex-1">
                                <h4 class="font-semibold text-base">Graphic design - Advance level</h4>
                                <p class="text-sm text-gray-600 mt-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                <div class="flex items-center text-yellow-500 text-sm mt-1">
                                ★★★★☆ <span class="ml-2 text-gray-500 text-xs">(4/5) Rating</span>
                                </div>
                                <div class="flex items-center gap-2 mt-2">
                                <span class="line-through text-sm text-gray-400">SAR 89</span>
                                <span class="text-base font-semibold text-gray-800">SAR 89</span>
                                </div>
                            </div>
                            </div>


                            <div class="flex border rounded-lg p-4 gap-4">
                                <!-- Left side: Image + Remove -->
                                <div class="flex flex-col items-center gap-2">
                                    <img src="/images/gallery/pic-4.png" alt="Course" class="w-48 h-48 object-cover rounded" />
                                    <button class="text-red-500 text-sm hover:underline">🗑 Remove</button>
                                </div>

                                <!-- Right side: Course Info -->
                                <div class="flex-1">
                                    <h4 class="font-semibold text-base">Graphic design - Advance level</h4>
                                    <p class="text-sm text-gray-600 mt-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                    <div class="flex items-center text-yellow-500 text-sm mt-1">
                                    ★★★★☆ <span class="ml-2 text-gray-500 text-xs">(4/5) Rating</span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-2">
                                    <span class="line-through text-sm text-gray-400">SAR 89</span>
                                    <span class="text-base font-semibold text-gray-800">SAR 89</span>
                                    </div>
                                </div>
                                </div>


                            <!-- Add more items button -->
                            <div>
                                <button class="w-full border border-gray-300 text-sm py-2 rounded hover:bg-gray-100">
                                Add more items
                                </button>
                            </div>
                            </div>

                            <!-- Right: Promo + Billing -->
                            <div class="space-y-6">
                            <!-- Promo Code -->
                            <div>
                                <h3 class="text-sm font-medium mb-2">Apply Promocode:</h3>
                                <div class="flex gap-2">
                                <input type="text" placeholder="Enter promocode for dicount" class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm" />
                                <button class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded text-sm">Apply</button>
                                </div>
                            </div>

                            <!-- Billing Information -->
                            <div class="border rounded-lg p-4 space-y-3">
                                <h3 class="text-sm font-medium pb-2 border-b">Billing Information</h3>
                                <div class="flex justify-between text-sm">
                                <span>Course total</span>
                                <span>SAR 89</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                <span>Saved amount</span>
                                <span>SAR 5</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                <span>Tax</span>
                                <span>SAR 2</span>
                                </div>
                                <hr />
                                <div class="flex justify-between text-base font-semibold pt-2">
                                <span>Total</span>
                                <span>SAR 86</span>
                                </div>
                                <a href="checkout-1-page.html">
                                <button class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded text-sm font-medium mt-4">
                                    Proceed to Checkout
                                </button>
                                </a>
                            </div>
                            </div>
                        </div>
                    </div>

                    <!-- Training Tab -->
                    <div x-show="tab === 'training'" x-cloak>
                        <h2 class="text-xl font-semibold mb-4">Training</h2>
                        <div class="flex items-start border rounded-md shadow-sm overflow-hidden mb-4">
                        <!-- Course Image -->
                        <img src="images/gallery/pic-4.png" alt="Course" class="w-48 h-48 object-cover" />

                        <!-- Content -->
                        <div class="p-4 flex-1">
                            <!-- Title + Description -->
                            <a href="training-details-profile.html">
                                <h2 class="text-lg font-semibold text-gray-900">Graphic design - Advance level</h2>
                            </a>

                            <p class="text-sm text-gray-600 mt-1">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            </p>

                            <!-- Rating -->
                            <div class="flex items-center text-sm mt-2 space-x-2">
                            <div class="text-yellow-500 text-base">
                                ★★★★☆
                            </div>
                            <span class="text-gray-500">(4/5)</span>
                            <span class="text-gray-700 font-medium">Rating</span>
                            </div>

                            <!-- Metadata -->
                            <div class="flex items-center justify-between mt-4 text-sm text-gray-700">
                            <!-- Instructor -->
                            <div class="flex items-center space-x-2">
                                <img src="http://weimaracademy.org/wp-content/uploads/2021/08/dummy-user.png" alt="Instructor" class="w-6 h-6 rounded-full">
                                <span>Julia Maccarthy</span>
                            </div>

                            <!-- Lessons -->
                            <div class="flex items-center space-x-1 text-blue-600">
                                <i class="ph ph-file-text"></i>
                                <span>6 lessons</span>
                            </div>

                            <!-- Time -->
                            <div class="flex items-center space-x-1 text-blue-600">
                                <i class="ph ph-clock"></i>
                                <span>20hrs</span>
                            </div>

                            <!-- Level -->
                            <div class="flex items-center space-x-1 text-blue-600">
                                <i class="ph ph-activity"></i>
                                <span>Advance</span>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- Mentorship Tab -->
                    <div x-show="tab === 'mentorship'" x-cloak>
                        <h2 class="text-xl font-semibold mb-4">Mentorship</h2>
                        <div class="flex items-center border-b pb-4 mb-4 space-x-4">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Mentor" class="w-24 h-24 rounded-full object-cover">
                        <div>
                            <a href="mentorship-details-profile.html">
                                <h3 class="text-lg font-semibold text-gray-900">Mohammad Raza</h3>
                            </a>

                            <p class="text-sm text-gray-500 mt-1">UI UX designer</p>

                            <!-- Rating -->
                            <div class="flex items-center mt-2 text-sm space-x-2">
                            <div class="text-yellow-500 text-base">
                                ★★★★☆
                            </div>
                            <span class="text-gray-500">(4/5)</span>
                            <span class="text-gray-700 font-medium">Rating</span>
                            </div>
                        </div>
                    </div>

                    
                </div>

                <!-- Assessment Tab -->
                <div x-show="tab === 'assessment'" x-cloak>
                    <h2 class="text-xl font-semibold mb-4">Assessment</h2>

                    <div class="flex items-start space-x-4 border-b pb-4 mb-4">
                    <!-- Image -->
                    <img src="images/gallery/pic-4.png" alt="Assessment" class="w-32 h-24 object-cover rounded-md">

                    <!-- Content -->
                    <div>
                        <a href="assessment-quiz.html">
                            <h3 class="text-base font-semibold text-gray-900">
                                Graphic design beginner - Quiz 1
                            </h3>
                        </a>

                        <p class="text-sm text-gray-600 mt-1">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        </p>

                        <!-- Quiz Author -->
                        <div class="flex items-center mt-3 text-sm">
                        <span class="text-gray-600 mr-2">Quiz by:</span>
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Julia Maccarthy" class="w-5 h-5 rounded-full mr-1">
                        <span class="text-gray-800 font-medium">Julia Maccarthy</span>
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Coaching Tab -->
                <div x-show="tab === 'coaching'" x-cloak>
                    <h2 class="text-xl font-semibold mb-4">Coaching</h2>

                    <div class="flex items-center space-x-4 border-b pb-4 mb-4">
                    <!-- Coach Image -->
                    <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Tom Holland" class="w-24 h-24 object-cover rounded-full">

                    <!-- Coach Info -->
                    <div>
                        <a href="coaching-details-profile.html">
                            <h3 class="text-lg font-semibold text-gray-900">Tom Holland</h3>
                        </a>
                        <p class="text-sm text-gray-600">Java developer</p>

                        <!-- Rating -->
                        <div class="flex items-center mt-2 space-x-1 text-sm">
                        <div class="flex text-yellow-500 text-sm">
                            <i class="ph ph-star-fill"></i>
                            <i class="ph ph-star-fill"></i>
                            <i class="ph ph-star-fill"></i>
                            <i class="ph ph-star-fill"></i>
                            <i class="ph ph-star"></i>
                        </div>
                        <span class="text-gray-500">(4/5)</span>
                        <span class="text-gray-700 font-medium ml-1">Rating</span>
                        </div>
                    </div>
                    </div>

                </div>

                <!-- Subscription Tab -->
                <div x-show="tab === 'subscription'" x-cloak>
                    <h2 class="text-xl font-semibold mb-4">Subscription</h2>

                    <!-- Subscription Plans Banner -->
                    <div class="bg-white border p-4 rounded-lg shadow-sm mb-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-md font-semibold">Subscription Plans</h3>
                        <p class="text-sm text-gray-500">Purchase subscription to get access to premium feature of Talentrek</p>
                    </div>
                    <button onclick="document.getElementById('plansModal').classList.remove('hidden')" 
                            class="bg-blue-600 text-white text-sm font-medium px-5 py-2 rounded-md hover:bg-blue-700">
                        View Plans
                    </button>
                    </div>

                    <!-- Modal -->
                    <!-- Modal -->
                    <div id="plansModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50 hidden">
                        <div class="bg-white w-full max-w-5xl p-6 rounded-lg shadow-lg relative">
                            <!-- Close Button -->
                            <button onclick="document.getElementById('plansModal').classList.add('hidden')" 
                                    class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-lg">
                            ✕
                            </button>

                            <h3 class="text-xl font-semibold mb-6">Available Subscription Plans</h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Silver Plan -->
                            <div class="border rounded-lg p-4 shadow-sm text-center">
                                <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-gray-300 rounded-full mb-2"></div>
                                <h4 class="font-semibold">Silver</h4>
                                <p class="font-bold text-lg mt-1">AED 49</p>
                                </div>
                                <p class="text-sm text-gray-500 mt-2 mb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                                <ul class="text-sm text-gray-700 space-y-1 text-left pl-4 mb-4">
                                <li class="flex items-center"><i class="ph ph-check-circle text-blue-500 mr-2"></i> Lorem ipsum</li>
                                <li class="flex items-center"><i class="ph ph-check-circle text-blue-500 mr-2"></i> Lorem ipsum</li>
                                <li class="flex items-center"><i class="ph ph-check-circle text-blue-500 mr-2"></i> Lorem sit dolor amet</li>
                                </ul>
                                <button class="bg-orange-500 hover:bg-orange-600 text-white w-full py-2 rounded-md text-sm font-medium">
                                Buy subscription
                                </button>
                            </div>

                            <!-- Gold Plan -->
                            <div class="border rounded-lg p-4 shadow-sm text-center">
                                <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-gray-300 rounded-full mb-2"></div>
                                <h4 class="font-semibold">Gold</h4>
                                <p class="font-bold text-lg mt-1">AED 99</p>
                                </div>
                                <p class="text-sm text-gray-500 mt-2 mb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                                <ul class="text-sm text-gray-700 space-y-1 text-left pl-4 mb-4">
                                <li class="flex items-center"><i class="ph ph-check-circle text-blue-500 mr-2"></i> Lorem ipsum</li>
                                <li class="flex items-center"><i class="ph ph-check-circle text-blue-500 mr-2"></i> Lorem ipsum</li>
                                <li class="flex items-center"><i class="ph ph-check-circle text-blue-500 mr-2"></i> Lorem sit dolor amet</li>
                                </ul>
                                <button class="bg-orange-500 hover:bg-orange-600 text-white w-full py-2 rounded-md text-sm font-medium">
                                Buy subscription
                                </button>
                            </div>

                            <!-- Platinum Plan -->
                            <div class="border rounded-lg p-4 shadow-sm text-center">
                                <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-gray-300 rounded-full mb-2"></div>
                                <h4 class="font-semibold">Platinum</h4>
                                <p class="font-bold text-lg mt-1">AED 149</p>
                                </div>
                                <p class="text-sm text-gray-500 mt-2 mb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
                                <ul class="text-sm text-gray-700 space-y-1 text-left pl-4 mb-4">
                                <li class="flex items-center"><i class="ph ph-check-circle text-blue-500 mr-2"></i> Lorem ipsum</li>
                                <li class="flex items-center"><i class="ph ph-check-circle text-blue-500 mr-2"></i> Lorem ipsum</li>
                                <li class="flex items-center"><i class="ph ph-check-circle text-blue-500 mr-2"></i> Lorem sit dolor amet</li>
                                </ul>
                                <button class="bg-orange-500 hover:bg-orange-600 text-white w-full py-2 rounded-md text-sm font-medium">
                                Buy subscription
                                </button>
                            </div>
                            </div>
                        </div>
                    </div>
                    <!-- Subscription History Table -->
                    <h3 class="text-md font-semibold mb-2">Subscription History</h3>

                    <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 text-sm">
                        <thead class="bg-gray-100 text-left">
                        <tr>
                            <th class="px-4 py-2 font-medium text-gray-700">Sr. No.</th>
                            <th class="px-4 py-2 font-medium text-gray-700">Subscription</th>
                            <th class="px-4 py-2 font-medium text-gray-700">Duration</th>
                            <th class="px-4 py-2 font-medium text-gray-700">Purchased on</th>
                            <th class="px-4 py-2 font-medium text-gray-700">Expired on</th>
                            <th class="px-4 py-2 font-medium text-gray-700">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Row 1 -->
                        <tr class="bg-gray-50">
                            <td class="px-4 py-3">1.</td>
                            <td class="px-4 py-3">Silver tier</td>
                            <td class="px-4 py-3">1 months</td>
                            <td class="px-4 py-3">12/02/2025</td>
                            <td class="px-4 py-3">11/03/2025</td>
                            <td class="px-4 py-3">
                            <button class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">Repeat</button>
                            </td>
                        </tr>
                        <!-- Row 2 -->
                        <tr>
                            <td class="px-4 py-3">2.</td>
                            <td class="px-4 py-3">Silver tier</td>
                            <td class="px-4 py-3">1 months</td>
                            <td class="px-4 py-3">12/02/2025</td>
                            <td class="px-4 py-3">11/03/2025</td>
                            <td class="px-4 py-3">
                            <button class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">Repeat</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    </div>

                </div>

                <!-- Payment Tab -->
                <div x-show="tab === 'payment'" x-cloak>
                    <h2 class="text-xl font-semibold">Payment</h2>
                    <div class="bg-white overflow-hidden">
                        <div class="p-4 border-b">
                        <h3 class="font-medium text-gray-700">Payment history</h3>
                        </div>

                        <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100 text-gray-700 text-left">
                            <tr>
                                <th class="px-4 py-3">Sr. No.</th>
                                <th class="px-4 py-3">Paid to</th>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Amount</th>
                                <th class="px-4 py-3">Payment status</th>
                                <th class="px-4 py-3">Amount</th>
                                <th class="px-4 py-3">Action</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            <!-- Row 1 -->
                            <tr>
                                <td class="px-4 py-3">1.</td>
                                <td class="px-4 py-3">Paul Anderson</td>
                                <td class="px-4 py-3">12/04/2025</td>
                                <td class="px-4 py-3">12/04/2025</td>
                                <td class="px-4 py-3 text-green-600">Paid</td>
                                <td class="px-4 py-3">SAR 45</td>
                                <td class="px-4 py-3">
                                <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-xs">
                                    View invoice
                                </button>
                                </td>
                            </tr>
                            <!-- Row 2 -->
                            <tr>
                                <td class="px-4 py-3">2.</td>
                                <td class="px-4 py-3">Mohammad Raza</td>
                                <td class="px-4 py-3">12/04/2025</td>
                                <td class="px-4 py-3">12/03/2025</td>
                                <td class="px-4 py-3 text-green-600">Paid</td>
                                <td class="px-4 py-3">SAR 86</td>
                                <td class="px-4 py-3">
                                <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-xs">
                                    View invoice
                                </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>

                <div x-data="{ showModal: false, selectedCertificate: null }">
                    <div x-show="tab === 'certificates'" x-cloak>
                        <div class="p-6">
                            <h2 class="text-xl font-semibold mb-4">Certificates</h2>

                            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                <!-- Header -->
                                <div class="bg-gray-100 px-6 py-3 font-semibold text-sm text-gray-700 flex">
                                <div class="w-1/12">Sr. No.</div>
                                <div class="w-4/12">Certificate of</div>
                                <div class="w-3/12">Date of certification</div>
                                <div class="w-2/12">View</div>
                                <div class="w-2/12">Delete</div>
                                </div>

                                <!-- Row 1 -->
                                <div class="flex items-center px-6 py-4 text-sm text-gray-700 border-b">
                                <div class="w-1/12">1.</div>
                                <div class="w-4/12">Full Stack Deveopler Course</div>
                                <div class="w-3/12">12/04/2025</div>
                                <div class="w-2/12">
                                    <button 
                                    @click="showModal = true; selectedCertificate = 'https://udemy-certificate.s3.amazonaws.com/image/UC-c2013095-ec1b-4b2b-b77e-07a330160cb8.jpg?v=1719901769000'" 
                                    class="bg-[#2196F3] hover:bg-blue-600 text-white px-4 py-1 rounded text-xs">
                                    View doc
                                    </button>
                                </div>
                                <div class="w-2/12">
                                    <button class="bg-red-100 hover:bg-red-200 text-red-600 rounded-full p-2">
                                    <!-- Trash Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 3v1H4v2h1v13c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V6h1V4h-5V3H9zm2 4h2v10h-2V7zm-4 0h2v10H7V7zm8 0h2v10h-2V7z"/>
                                    </svg>
                                    </button>
                                </div>
                            </div>

                        <!-- Upload Button -->
                    </div>
                    </div>
                </div>

                <!-- Modal -->
                <div x-show="showModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white w-full max-w-3xl rounded shadow-lg overflow-hidden">
                        <div class="flex justify-between items-center px-4 py-2 border-b">
                            <h3 class="text-lg font-semibold">Certificate Document</h3>
                            <button @click="showModal = false" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
                        </div>
                        <div class="p-4 flex flex-col items-center">
                            <template x-if="selectedCertificate">
                                <img :src="selectedCertificate" alt="Certificate" class="max-h-[500px] w-auto object-contain" />
                            </template>
                            
                            <!-- Download Button -->
                            <template x-if="selectedCertificate">
                                <a 
                                    :href="selectedCertificate" 
                                    download="certificate.jpg" 
                                    class="mt-4 p-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition flex items-center justify-center"
                                    aria-label="Download Certificate"
                                >
                                    <!-- Download SVG Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                                    </svg>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>

            </div>



                <!-- Settings Tab -->
                <div x-show="tab === 'settings'" x-cloak>
                    <h2 class="text-xl font-semibold mb-4">Settings</h2>
                    <p>Update your account settings here.</p>
                </div>
        </div>
        </div>
                                    
        </div>
        </div>
        </div>
    </main>

</div>

       

    @include('site.jobseeker.componants.footer')

</div>













    
<script>
    function toggleSection(id, iconId) {
        const section = document.getElementById(id);
        const icon = document.getElementById(iconId);
        section.classList.toggle('hidden');
        if (icon.classList.contains('rotate-180')) {
            icon.classList.remove('rotate-180');
        } else {
            icon.classList.add('rotate-180');
        }
    }
</script>
<script>
    $(document).ready(function () {
        $('#dob').datepicker({
            format: 'yyyy-mm-dd',
            endDate: new Date(),
            autoclose: true,
            todayHighlight: true
        });
        $('#starts_from').datepicker({
            format: 'yyyy-mm-dd',
            endDate: new Date(),
            autoclose: true,
            todayHighlight: true
        });
         $('#end_to').datepicker({
            format: 'yyyy-mm-dd',
            endDate: new Date(),
            autoclose: true,
            todayHighlight: true
        });
    });
</script>











<!-- JAVASCRIPT  FILES ========================================= --> 
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
<!-- Add Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

