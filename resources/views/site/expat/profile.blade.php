<?php
$user = Auth()->user();
// echo "<pre>";
// print_r($user);exit;
$edu = $user->educations;
$work = $user->experiences;
$skills = $user->skills->first();


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

    @if($expatNeedsSubscription)
        @include('site.expat.subscription.index')
    @endif


    <div class="page-content">
        <div class="relative bg-center bg-cover h-[400px] flex items-center"
            style="background-image: url('{{ asset('asset//images/banner/service page banner.png') }}');">
            <div class="absolute inset-0 bg-white bg-opacity-10"></div>
            <div class="relative z-10 container mx-auto px-4">
                <div class="space-y-2">
                    <h2 class="text-3xl font-bold text-white ml-[10%]">{{ langLabel('profile') }}</h2>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    @include('admin.errors')
    <main class="w-11/12 mx-auto py-8" x-data="{ tab: 'personal' }">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">
            <!-- Left/Main Content -->
            <div class="flex-1">
                <!-- Header -->
                <div class="max-w-6xl mx-auto py-6 px-4">
                    <div class="flex justify-between items-start">
                        <div class="flex gap-4">
                            @php

                                $userId = auth()->id();
                                $profile = App\Models\AdditionalInfo::where('user_id', $userId)
                                    ->where('doc_type', 'profile_picture')
                                    ->first();
                            @endphp

                            <!-- Image Preview -->
                            <img id="profilePreview"
                                src="{{ $profile ? asset($profile->document_path) : 'https://www.lscny.org/app/uploads/2018/05/mystery-person.png' }}"
                                class="h-20 w-20 rounded-md mb-2" alt="Profile Preview" />


                            <div class="mt-1">
                                <h2 class="text-xl font-semibold">{{$user->name}}</h2>
                                <p class="text-sm text-gray-600">{{$user->email}}</p>
                                <p class="text-sm text-gray-600">{{$user->phone_number}}</p>
                            </div>
                        </div>
                        <form action="{{ route('expat.logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="border rounded px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 flex items-center gap-2">
                                <i class="fas fa-power-off"></i>
                                {{ langLabel('logout') }}
                            </button>
                        </form>
                    </div>
                </div>


                <hr />

                <!-- Main Section -->
                <div class="max-w-6xl mx-auto flex px-4 py-6 gap-6">
                    <!-- Sidebar -->
                    <!-- <div x-data="{ tab: 'profile', profileTab: 'personal' }" class="flex max-w-5xl w-full space-x-6"> -->
                    <div x-data="{
                                tab: localStorage.getItem('activeTab') || 'profile',
                                profileTab: 'personal'
                            }" x-init="$watch('tab', value => localStorage.setItem('activeTab', value))"
                        class="flex max-w-5xl w-full space-x-6">
                        <!-- Sidebar Outer Tabs -->
                        <div class="w-1/5 space-y-2" style="background-color: rgb(238, 238, 238);">
                            <ul class="text-sm font-medium">
                                <li>
                                    <button @click="tab = 'profile'"
                                        :class="tab === 'profile' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                                        class="w-full text-left px-4 py-2 rounded">
                                        {{ langLabel('profile') }}
                                    </button>
                                </li>
                                <li>
                                    <button @click="tab = 'cart'"
                                        :class="tab === 'cart' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                                        class="w-full text-left px-4 py-2 rounded">
                                        {{ langLabel('cart') }}
                                    </button>
                                </li>
                                <li>
                                    <button @click="tab = 'training'"
                                        :class="tab === 'training' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                                        class="w-full text-left px-4 py-2 rounded">
                                        {{ langLabel('training') }}
                                    </button>
                                </li>
                                <li>
                                    <button @click="tab = 'mentorship'"
                                        :class="tab === 'mentorship' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                                        class="w-full text-left px-4 py-2 rounded">
                                        {{ langLabel('mentorship') }}
                                    </button>
                                </li>
                                <li>
                                    <button @click="tab = 'assessment'"
                                        :class="tab === 'assessment' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                                        class="w-full text-left px-4 py-2 rounded">
                                        {{ langLabel('assessment') }}
                                    </button>
                                </li>
                                <li>
                                    <button @click="tab = 'coaching'"
                                        :class="tab === 'coaching' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                                        class="w-full text-left px-4 py-2 rounded">
                                        {{ langLabel('coaching') }}
                                    </button>
                                </li>
                                <li>
                                    <button @click="tab = 'subscription'"
                                        :class="tab === 'subscription' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                                        class="w-full text-left px-4 py-2 rounded">
                                        {{ langLabel('subscription') }}
                                    </button>
                                </li>
                                <li>
                                    <button @click="tab = 'payment'"
                                        :class="tab === 'payment' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                                        class="w-full text-left px-4 py-2 rounded">
                                        {{ langLabel('payment') }}
                                    </button>
                                </li>
                                <li>
                                    <button @click="tab = 'certificates'"
                                        :class="tab === 'certificates' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100'"
                                        class="w-full text-left px-4 py-2 rounded">
                                        {{ langLabel('certificates') }}
                                    </button>
                                </li>
                                <!-- More outer tabs can be added here -->
                            </ul>
                        </div>

                        <!-- Main Content -->
                        <div class="w-4/5 space-y-6">
                            <!-- Profile Tab Content -->
                            @include('site.expat.profile.profile')
                            @include('site.expat.profile.cart')
                            @include('site.expat.profile.training')
                            @include('site.expat.profile.mentorship')
                            @include('site.expat.profile.assessment')
                            @include('site.expat.profile.coaching')
                            @include('site.expat.profile.subscription')
                            @include('site.expat.profile.payment')
                            @include('site.expat.profile.certificate')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    @include('site.expat.componants.footer')



