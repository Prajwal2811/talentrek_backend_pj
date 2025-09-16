<?php
use Illuminate\Support\Facades\DB;
    // Fetch all trainers
    // $assessors = DB::table('assessors')->get();
 

    use App\Models\Assessors;

    $assessors = Assessors::with([
        'reviews',
        'additionalInfo' => function ($q) {
            $q->whereIn('doc_type', ['assessor_resume', 'assessor_certificate']);
        },
        'profilePicture' => function ($q) {
            $q->where('doc_type', 'assessor_profile_picture');
        },
        'experiences'
    ])
    ->where('status', 'active')
    ->paginate(9);



    // echo "<pre>";
    // print_r($assessors);exit;
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
	<style>
        .site-header.header-style-3.mobile-sider-drawer-menu {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: white;
        }
    </style>
@include('site.componants.navbar')

  <div class="page-content">
            <div class="relative bg-center bg-cover h-[400px] flex items-center" style="background-image: url('{{ asset('asset/images/banner/Assessment.png') }}');">
                <div class="absolute inset-0 bg-white bg-opacity-10"></div>
                <div class="relative z-10 container mx-auto px-4">
                    <div class="space-y-2">
                        <h2 class="text-5xl font-bold text-white ml-[10%]">Assessment</h2>
                    </div>
                </div>
            </div>
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

            <!-- Alpine.js CDN -->
            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

            <div class="flex max-w-7xl mx-auto px-4 py-6" x-data="mentorApp()">
                <!-- Sidebar Filter -->
                <aside class="w-1/4 pr-6">
                    <button class="block text-gray-700 font-semibold mb-6">☰ Filter</button>

                    <!-- Course topic -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center cursor-pointer" onclick="toggleSection('topicSection', 'iconTopic')">
                            <h3 class="font-semibold text-gray-900 mb-2">Course topic</h3>
                            <i id="iconTopic" class="ph ph-caret-down transition-transform duration-300"></i>
                        </div>
                        <div id="topicSection" class="space-y-2">
                            <label class="block"><input type="checkbox" class="mr-2" value="design" x-model="selectedTopics">Design</label>
                            <label class="block"><input type="checkbox" class="mr-2" value="coding" x-model="selectedTopics">Coding</label>
                            <label class="block"><input type="checkbox" class="mr-2" value="mechanical" x-model="selectedTopics">Mechanical</label>
                            <label class="block"><input type="checkbox" class="mr-2" value="language" x-model="selectedTopics">Language</label>
                        </div>
                    </div>

                    <!-- Mentorship level -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center cursor-pointer" onclick="toggleSection('levelSection', 'iconLevel')">
                            <h3 class="font-semibold text-gray-900 mb-2">Mentorship level</h3>
                            <i id="iconLevel" class="ph ph-caret-down transition-transform duration-300"></i>
                        </div>
                        <div id="levelSection" class="space-y-2">
                            <label class="block"><input type="checkbox" class="mr-2" value="basic" x-model="selectedLevels">Basic(online/virtual)</label>
                            <label class="block"><input type="checkbox" class="mr-2" value="advanced" x-model="selectedLevels">Advanced(Physical)</label>
                        </div>
                    </div>
                </aside>

                <!-- Main content -->
                <main class="w-3/4 mx-auto mt-8" 
                    x-data="assessorList({ assessors: {{ json_encode($assessors->items()) }} })" 
                    x-init="init()"> <!-- Added Alpine.js data -->

                    <!-- Header -->
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-xl font-semibold">Assessment</h1>
                        <span class="text-sm text-gray-500">
                            Showing <span x-text="filteredAssessors.length"></span> total results <!-- changed from filteredMentors -->
                        </span>
                    </div>

                    <!-- Search -->
                    <div class="mb-6 relative">
                        <input type="text" placeholder="Search here..." x-model="search" 
                            class="w-full border border-gray-300 rounded-md px-4 py-2 pr-12" />
                        <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                            🔍
                        </button>
                    </div>

                    <!-- Expandable Sections -->
                    <div class="max-w-4xl mx-auto space-y-4" x-data="{ open: null }">
                        <!-- Assessment Overview -->
                        <div class="border-b pb-4">
                            <button @click="open === 1 ? open = null : open = 1"
                                class="w-full flex justify-between items-center text-left text-lg font-semibold focus:outline-none">
                                <span>Assessment overview</span>
                                <svg :class="{'rotate-180': open === 1}" class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open === 1" x-transition class="mt-4 text-gray-700">
                                <p>Hi, I’m Mohammad Raza — a dedicated mentor with over 5 years of experience ...</p>
                                <p class="mt-2">Over the years, I’ve had the opportunity ...</p>
                                <p class="mt-2">My teaching style is focused on clarity ...</p>
                                <p class="mt-2">I’m here not just to teach ...</p>
                                <p class="mt-2">Looking forward to being part of your learning experience ...</p>
                            </div>
                        </div>

                        <!-- Benefits of Assessment -->
                        <div class="border-b pb-4">
                            <button @click="open === 2 ? open = null : open = 2"
                                class="w-full flex justify-between items-center text-left text-lg font-semibold focus:outline-none">
                                <span>Benefits of Assessment</span>
                                <svg :class="{'rotate-180': open === 2}" class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open === 2" x-transition class="mt-4 text-gray-700">
                                <ul class="list-disc list-inside space-y-2">
                                    <li>Personalized guidance tailored to your learning goals</li>
                                    <li>Insight into real-world applications and industry practices</li>
                                    <li>Motivation, support, and feedback to stay on track</li>
                                    <li>Networking opportunities with industry professionals</li>
                                    <li>Boosted confidence to apply your knowledge effectively</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Assessor Cards -->
                    <div class="container mx-auto px-4 py-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Replaced foreach with Alpine.js template for reactive search -->
                            <template x-for="assessor in paginated" :key="assessor.id">
                                <div class="bg-white rounded-lg shadow p-4 text-center space-y-3">
                                    <img :src="assessor.profilePicture ?? '{{ asset('default.jpg') }}'" alt="Assessor Image" class="w-full h-48 object-cover rounded-lg">
                                    <a :href="'/assessor-details/' + assessor.id">
                                        <h3 class="text-lg font-semibold text-gray-900" x-text="assessor.name"></h3>
                                    </a>
                                    <div class="flex items-center justify-center text-sm text-gray-700 space-x-1">
                                        <span class="text-orange-500">★</span>
                                        <span x-text="(assessor.avgRating ?? 0).toFixed(1) + '/5'"></span>
                                        <span>(<span x-text="assessor.reviewCount ?? 0"></span> reviews)</span>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Pagination -->
                        <div class="flex justify-start items-center mt-8 space-x-2">
                            <button @click="prevPage" :disabled="page === 1" class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50">Prev</button>

                            <template x-for="n in totalPages" :key="n">
                                <button @click="page = n" :class="{'bg-gray-200 font-semibold': page === n}" class="px-3 py-1 border rounded hover:bg-gray-100" x-text="n"></button>
                            </template>

                            <button @click="nextPage" :disabled="page === totalPages" class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50">Next</button>
                        </div>
                    </div>
                </main>

                <!-- Alpine.js Logic -->
                <script>
                function assessorList({ assessors }) {
                    return {
                        assessors: assessors.map(a => ({
                            id: a.id,
                            name: a.name.toLowerCase(), // for case-insensitive search
                            profilePicture: a.profilePicture ?? '{{ asset('default.jpg') }}',
                            avgRating: a.avgRating ?? 0,
                            reviewCount: a.reviewCount ?? 0
                        })),
                        search: "", // reactive search input
                        page: 1,
                        perPage: 6, // cards per page
                        get filteredAssessors() { // search filter
                            if (!this.search) return this.assessors;
                            return this.assessors.filter(a => 
                                a.name.includes(this.search.toLowerCase()) ||
                                String(a.avgRating).includes(this.search) ||
                                String(a.reviewCount).includes(this.search)
                            );
                        },
                        get totalPages() { // total pages based on filtered results
                            return Math.ceil(this.filteredAssessors.length / this.perPage) || 1;
                        },
                        get paginated() { // current page data
                            const start = (this.page - 1) * this.perPage;
                            return this.filteredAssessors.slice(start, start + this.perPage);
                        },
                        prevPage() { if (this.page > 1) this.page--; },
                        nextPage() { if (this.page < this.totalPages) this.page++; },
                        init() { this.page = 1; } // reset page on init
                    }
                }
                </script>

            </div>

            <!-- Alpine Data -->
            <script>
               

                // Optional: Accordion toggle for filter sections
                function toggleSection(sectionId, iconId) {
                    const section = document.getElementById(sectionId);
                    const icon = document.getElementById(iconId);
                    section.classList.toggle('hidden');
                    icon.classList.toggle('rotate-180');
                }
            </script>
        </div>

@include('site.componants.footer')