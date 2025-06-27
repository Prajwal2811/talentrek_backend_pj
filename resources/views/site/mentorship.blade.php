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
            <div class="relative bg-center bg-cover h-[400px] flex items-center" style="background-image: url('{{ asset('asset/images/banner/Mentorship.png') }}');">
                <div class="absolute inset-0 bg-white bg-opacity-10"></div>
                <div class="relative z-10 container mx-auto px-4">
                    <div class="space-y-2">
                        <h2 class="text-5xl font-bold text-white ml-[10%]">Mentorship</h2>
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
                <main class="w-3/4 mx-auto mt-8">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-xl font-semibold">Mentors</h1>
                        <span class="text-sm text-gray-500">Showing <span x-text="filteredMentors.length"></span> total results</span>
                    </div>

                    <!-- Search -->
                    <div class="mb-6 relative">
                        <input type="text" placeholder="Search here..." x-model="search"
                            class="w-full border border-gray-300 rounded-md px-4 py-2 pr-12" />
                        <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                            🔍
                        </button>
                    </div>


                    <div class="max-w-4xl mx-auto space-y-4" x-data="{ open: null }">
                        <!-- Mentorship Overview -->
                        <div class="border-b pb-4">
                        <button
                            @click="open === 1 ? open = null : open = 1"
                            class="w-full flex justify-between items-center text-left text-lg font-semibold focus:outline-none"
                        >
                            <span>Mentorship overview</span>
                            <svg :class="{'rotate-180': open === 1}" class="w-5 h-5 transform transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open === 1" x-transition class="mt-4 text-gray-700">
                            <p>Hi, I’m Mohammad Raza — a dedicated mentor with over 5 years of experience in the field of [insert subject area, e.g., web development, UI/UX design, etc.]. I completed my studies at XYZ College, under ABC University, where I built a strong academic foundation and discovered my passion for teaching and knowledge-sharing.</p>
                            <p class="mt-2">Over the years, I’ve had the opportunity to work on a variety of challenging projects and mentor individuals from diverse backgrounds. These experiences not only sharpened my professional skills but also helped me understand how different learners grasp concepts in their own unique ways. That insight has shaped how I teach today — making my sessions practical, interactive, and easy to follow.</p>
                            <p class="mt-2">My teaching style is focused on clarity, engagement, and real-world application. I believe that learning should not be limited to just theory. That’s why I always aim to bring practical scenarios, case studies, and hands-on tasks into every course I deliver.</p>
                            <p class="mt-2">I’m here not just to teach but to guide, support, and motivate you through your learning journey. Whether you’re just starting out or looking to level up, my goal is to make sure you gain not only knowledge but the confidence to apply it effectively in your career.</p>
                            <p class="mt-2">Looking forward to being part of your learning experience on Talentrek — let’s grow together!</p>
                        </div>
                        </div>

                        <!-- Benefits of Mentorship -->
                        <div class="border-b pb-4">
                        <button
                            @click="open === 2 ? open = null : open = 2"
                            class="w-full flex justify-between items-center text-left text-lg font-semibold focus:outline-none"
                        >
                            <span>Benefits of mentorship</span>
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

                    <!-- Mentor cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="mentor in paginatedMentors()" :key="mentor.id">
                            <div class="bg-white rounded-lg shadow p-4 text-center">
                                <!-- Rectangular image with rounded corners -->
                                <img :src="mentor.image" :alt="mentor.name"
                                    class="w-full h-48 object-cover rounded-md mb-4 mx-auto">

                                <a href="{{ route('mentorship-details')}}">
                                    <h3 class="text-lg font-semibold text-gray-900" x-text="mentor.name"></h3>
                                </a>

                                <p class="text-sm text-gray-600 mt-1" x-text="mentor.role"></p>

                                <div class="flex items-center justify-center mt-2">
                                    <span class="text-orange-500 text-sm mr-1">★</span>
                                    <span class="text-sm text-gray-700">(4/5) Rating</span>
                                </div>
                            </div>
                        </template>
                    </div>


                    <!-- Pagination -->
                    <div class="flex justify-start items-center mt-8 space-x-2">
                        <button @click="prevPage" :disabled="page === 1"
                            class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50">Prev</button>

                        <template x-for="n in totalPages" :key="n">
                            <button @click="page = n" :class="{'bg-gray-200 font-semibold': page === n}"
                                class="px-3 py-1 border rounded hover:bg-gray-100" x-text="n"></button>
                        </template>

                        <button @click="nextPage" :disabled="page === totalPages"
                            class="px-3 py-1 border rounded hover:bg-gray-100 disabled:opacity-50">Next</button>
                    </div>
                </main>
            </div>

            <!-- Alpine Data -->
            <script>
                function mentorApp() {
                    return {
                        search: '',
                        page: 1,
                        perPage: 8,
                        selectedTopics: [],
                        selectedLevels: [],
                        mentors: [
                            { id: 1, name: 'Mohammad Raza', role: 'UI/UX Designer', rating: '4.5', image: 'https://randomuser.me/api/portraits/men/75.jpg', topic: 'design', level: 'basic' },
                            { id: 2, name: 'Zayd Rahman', role: 'Video Editor', rating: '4.6', image: 'https://randomuser.me/api/portraits/men/76.jpg', topic: 'design', level: 'advanced' },
                            { id: 3, name: 'Aisha Siddiqui', role: 'UI/UX Designer', rating: '4.7', image: 'https://randomuser.me/api/portraits/women/45.jpg', topic: 'design', level: 'basic' },
                            { id: 4, name: 'Farhan Khan', role: 'Web Developer', rating: '4.3', image: 'https://randomuser.me/api/portraits/men/78.jpg', topic: 'coding', level: 'advanced' },
                            { id: 5, name: 'Sana Ali', role: 'Graphic Designer', rating: '4.4', image: 'https://randomuser.me/api/portraits/women/47.jpg', topic: 'design', level: 'basic' },
                            { id: 6, name: 'Imran Patel', role: 'Digital Marketing', rating: '4.2', image: 'https://randomuser.me/api/portraits/men/80.jpg', topic: 'language', level: 'advanced' },
                            { id: 7, name: 'Fatima Noor', role: 'Animator', rating: '4.8', image: 'https://randomuser.me/api/portraits/women/52.jpg', topic: 'design', level: 'advanced' },
                            { id: 8, name: 'Rohit Sen', role: 'Data Analyst', rating: '4.1', image: 'https://randomuser.me/api/portraits/men/83.jpg', topic: 'mechanical', level: 'basic' },
                            { id: 9, name: 'Hina Sheikh', role: 'Content Writer', rating: '4.6', image: 'https://randomuser.me/api/portraits/women/54.jpg', topic: 'language', level: 'basic' },
                            { id: 10, name: 'Aman Verma', role: 'Frontend Developer', rating: '4.9', image: 'https://randomuser.me/api/portraits/men/84.jpg', topic: 'coding', level: 'advanced' },
                        ],
                        get filteredMentors() {
                            return this.mentors
                                .filter(m =>
                                    (!this.search || m.name.toLowerCase().includes(this.search.toLowerCase()) || m.role.toLowerCase().includes(this.search.toLowerCase()))
                                )
                                .filter(m =>
                                    this.selectedTopics.length === 0 || this.selectedTopics.includes(m.topic)
                                )
                                .filter(m =>
                                    this.selectedLevels.length === 0 || this.selectedLevels.includes(m.level)
                                );
                        },
                        paginatedMentors() {
                            const start = (this.page - 1) * this.perPage;
                            return this.filteredMentors.slice(start, start + this.perPage);
                        },
                        get totalPages() {
                            return Math.ceil(this.filteredMentors.length / this.perPage);
                        },
                        nextPage() {
                            if (this.page < this.totalPages) this.page++;
                        },
                        prevPage() {
                            if (this.page > 1) this.page--;
                        }
                    };
                }

                // Optional: Accordion toggle for filter sections
                function toggleSection(sectionId, iconId) {
                    const section = document.getElementById(sectionId);
                    const icon = document.getElementById(iconId);
                    section.classList.toggle('hidden');
                    icon.classList.toggle('rotate-180');
                }
            </script>

@include('site.componants.footer')