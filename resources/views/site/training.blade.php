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
            <div class="relative bg-center bg-cover h-[400px] flex items-center" style="background-image: url('{{ asset('asset/images/banner/Training.png') }}');">
                <div class="absolute inset-0 bg-white bg-opacity-10"></div>
                <div class="relative z-10 container mx-auto px-4">
                    <div class="space-y-2">
                        <h2 class="text-5xl font-bold text-white ml-[10%]">Training</h2>
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

            <div class="flex max-w-7xl mx-auto px-4 py-6">
                <!-- Sidebar Filter -->
                <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
                <aside class="w-1/4 pr-6" x-data="filtersApp()" x-init="initFilters()">
                    <button class="block text-gray-700 font-semibold mb-6">☰ Filter</button>

                    <!-- Course topic -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center cursor-pointer" onclick="toggleSection('topicSection', 'iconTopic')">
                        <h3 class="font-semibold text-gray-900 mb-2">Course topic</h3>
                        <i id="iconTopic" class="ph ph-caret-down transition-transform duration-300"></i>
                        </div>
                        <div id="topicSection" class="space-y-2">
                        <label class="block"><input type="checkbox" class="mr-2" value="Design" @change="$dispatch('filter-change')">Design</label>
                        <label class="block"><input type="checkbox" class="mr-2" value="Coding" @change="$dispatch('filter-change')">Coding</label>
                        <label class="block"><input type="checkbox" class="mr-2" value="Mechanical" @change="$dispatch('filter-change')">Mechanical</label>
                        <label class="block"><input type="checkbox" class="mr-2" value="Language" @change="$dispatch('filter-change')">Language</label>
                        </div>
                    </div>

                    <!-- Course level -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center cursor-pointer" onclick="toggleSection('levelSection', 'iconLevel')">
                        <h3 class="font-semibold text-gray-900 mb-2">Course level</h3>
                        <i id="iconLevel" class="ph ph-caret-down transition-transform duration-300"></i>
                        </div>
                        <div id="levelSection" class="space-y-2">
                        <label class="block"><input type="checkbox" class="mr-2" value="Beginner" @change="$dispatch('filter-change')">Beginner</label>
                        <label class="block"><input type="checkbox" class="mr-2" value="Intermediate" @change="$dispatch('filter-change')">Intermediate</label>
                        <label class="block"><input type="checkbox" class="mr-2" value="Advanced" @change="$dispatch('filter-change')">Advanced</label>
                        <label class="block"><input type="checkbox" class="mr-2" value="All" @change="$dispatch('filter-change')">All</label>
                        </div>
                    </div>

                    <!-- Training type -->
                    <div>
                        <div class="flex justify-between items-center cursor-pointer" onclick="toggleSection('typeSection', 'iconType')">
                        <h3 class="font-semibold text-gray-900 mb-2">Training type</h3>
                        <i id="iconType" class="ph ph-caret-down transition-transform duration-300"></i>
                        </div>
                        <div id="typeSection" class="space-y-2">
                        <label class="block"><input type="checkbox" class="mr-2" value="Offline in classroom" @change="$dispatch('filter-change')">Offline in classroom</label>
                        <label class="block"><input type="checkbox" class="mr-2" value="Virtual/Online" @change="$dispatch('filter-change')">Virtual/Online</label>
                        <label class="block"><input type="checkbox" class="mr-2" value="Recorded lectures" @change="$dispatch('filter-change')">Recorded lectures</label>
                        </div>
                    </div>
                </aside>

                <main 
                x-data="courseApp()" 
                x-init="init()" 
                @filter-change.window="updateFilters($event)" 
                class="w-3/4"
                >
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-xl font-semibold">Courses</h1>
                    <span class="text-sm text-gray-500">
                    showing <span x-text="filteredCourses.length"></span> total results
                    </span>
                </div>

                <!-- Search input -->
                <div class="mb-4 relative">
                    <input
                    type="text"
                    placeholder="Search here..."
                    class="w-full border border-gray-300 rounded-md px-4 py-2 pr-12"
                    x-model="searchTerm"
                    @input.debounce.300ms="filterCourses()"
                    />
                    <button
                    type="button"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                    aria-label="Search"
                    @click="filterCourses()"
                    >
                    <!-- Search Icon SVG -->
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                    </svg>
                    </button>
                </div>

                <!-- Course cards -->
                <template x-for="course in paginatedCourses" :key="course.id">
                    <div class="flex border rounded-md overflow-hidden shadow-sm mb-4 course-card">
                    <img :src="course.image" alt="Course" class="w-1/4 object-cover" />
                    <div class="p-4 flex-1">
                        <a href="{{route('training-detail')}}">
                            <h2 class="font-semibold text-lg text-gray-800 course-title" x-text="course.title"></h2>
                        </a>
                        <p class="text-sm text-gray-600 mt-1" x-text="course.description"></p>
                        <div class="flex items-center mt-2 space-x-2">
                        <div class="flex text-yellow-500 text-sm">
                            <template x-for="i in 5" :key="i">
                            <i
                                :class="i <= Math.floor(course.rating) ? 'ph ph-star-fill' : 'ph ph-star'"
                            ></i>
                            </template>
                        </div>
                        <span class="text-sm text-gray-500" x-text="`(${course.rating}/5)`"></span>
                        <span class="text-sm text-gray-700 font-medium">Rating</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-700 space-x-6 mt-4">
                        <div class="flex items-center space-x-1">
                            <img :src="course.instructorImage" alt="Instructor" class="rounded-full w-6 h-6" />
                            <span x-text="course.instructor"></span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <i class="ph ph-play-circle text-blue-500"></i><span x-text="course.lessons + ' lessons'"></span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <i class="ph ph-clock text-blue-500"></i><span x-text="course.duration"></span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <i class="ph ph-trend-up text-blue-500"></i><span x-text="course.level"></span>
                        </div>
                        </div>
                    </div>
                    <div class="flex flex-col justify-center items-end p-4 w-32 text-right">
                        <span class="text-gray-400 line-through text-sm" x-text="course.originalPrice"></span>
                        <span class="text-xl font-semibold text-gray-800" x-text="course.discountedPrice"></span>
                    </div>
                    </div>
                </template>

                <!-- Pagination controls -->
                <div class="flex justify-center items-center space-x-2 mt-6">
                    <button
                    class="px-3 py-1 rounded border"
                    :disabled="currentPage === 1"
                    @click="currentPage--"
                    >
                    Previous
                    </button>

                    <template x-for="page in totalPages" :key="page">
                    <button
                        class="px-3 py-1 rounded border"
                        :class="{'bg-blue-500 text-white': currentPage === page}"
                        @click="currentPage = page"
                    >
                        <span x-text="page"></span>
                    </button>
                    </template>

                    <button
                    class="px-3 py-1 rounded border"
                    :disabled="currentPage === totalPages"
                    @click="currentPage++"
                    >
                    Next
                    </button>
                </div>
                </main>

                <script>
                    function courseApp() {
                        return {
                        searchTerm: '',
                        courses: [
                            {
                                id: 1,
                                title: "Mobile App Development",
                                description: "Build apps for Android and iOS from scratch.",
                                image: "{{ asset('asset/images/gallery/pic-4.png') }}",
                                rating: 4,
                                instructor: "Lisa Turner",
                                instructorImage: "https://randomuser.me/api/portraits/women/6.jpg",
                                lessons: 18,
                                duration: "30hrs",
                                level: "Intermediate",
                                topic: "Coding",
                                trainingType: "Virtual/Online",
                                originalPrice: "SAR 200",
                                discountedPrice: "SAR 180",
                            },
                            {
                                id: 2,
                                title: "Web Design Basics",
                                description: "Learn the fundamentals of web design with HTML, CSS, and more.",
                                image: "{{ asset('asset/images/gallery/pic-2.png') }}",
                                rating: 5,
                                instructor: "Mark Wilson",
                                instructorImage: "https://randomuser.me/api/portraits/men/12.jpg",
                                lessons: 12,
                                duration: "25hrs",
                                level: "Beginner",
                                topic: "Design",
                                trainingType: "Offline in classroom",
                                originalPrice: "SAR 150",
                                discountedPrice: "SAR 130",
                            },
                            {
                                id: 3,
                                title: "Advanced JavaScript",
                                description: "Deep dive into JavaScript ES6+ and modern frameworks.",
                                image: "{{ asset('asset/images/gallery/pic-3.png') }}",
                                rating: 4.5,
                                instructor: "Anna Smith",
                                instructorImage: "https://randomuser.me/api/portraits/women/21.jpg",
                                lessons: 20,
                                duration: "40hrs",
                                level: "Advanced",
                                topic: "Coding",
                                trainingType: "Recorded lectures",
                                originalPrice: "SAR 250",
                                discountedPrice: "SAR 220",
                            },
                            {
                                id: 4,
                                title: "Graphic Design Masterclass",
                                description: "Become a pro at graphic design with practical projects.",
                                image: "{{ asset('asset/images/gallery/pic-4.png') }}",
                                rating: 4.2,
                                instructor: "Jessica Lee",
                                instructorImage: "https://randomuser.me/api/portraits/women/25.jpg",
                                lessons: 15,
                                duration: "28hrs",
                                level: "Intermediate",
                                topic: "Design",
                                trainingType: "Virtual/Online",
                                originalPrice: "SAR 190",
                                discountedPrice: "SAR 170",
                            },
                            {
                                id: 5,
                                title: "Mechanical Engineering Basics",
                                description: "Introductory course on mechanical engineering concepts.",
                                image: "{{ asset('asset/images/gallery/pic-1.png') }}",
                                rating: 3.8,
                                instructor: "John Doe",
                                instructorImage: "https://randomuser.me/api/portraits/men/31.jpg",
                                lessons: 10,
                                duration: "20hrs",
                                level: "Beginner",
                                topic: "Mechanical",
                                trainingType: "Offline in classroom",
                                originalPrice: "SAR 140",
                                discountedPrice: "SAR 120",
                            },
                        ],

                        filteredCourses: [],
                        filters: {
                            topics: [],
                            levels: [],
                            types: [],
                        },
                        currentPage: 1,
                        perPage: 4,

                        init() {
                            this.filteredCourses = this.courses
                        },

                        get totalPages() {
                            return Math.ceil(this.filteredCourses.length / this.perPage) || 1
                        },

                        get paginatedCourses() {
                            const start = (this.currentPage - 1) * this.perPage
                            return this.filteredCourses.slice(start, start + this.perPage)
                        },

                        updateFilters() {
                            this.filters.topics = Array.from(document.querySelectorAll('#topicSection input[type=checkbox]:checked')).map(i => i.value)
                            this.filters.levels = Array.from(document.querySelectorAll('#levelSection input[type=checkbox]:checked')).map(i => i.value)
                            this.filters.types = Array.from(document.querySelectorAll('#typeSection input[type=checkbox]:checked')).map(i => i.value)
                            this.currentPage = 1
                            this.filterCourses()
                        },

                        filterCourses() {
                            let term = this.searchTerm.trim().toLowerCase()
                            this.filteredCourses = this.courses.filter(course => {
                            const matchesSearch = term === '' ||
                                course.title.toLowerCase().includes(term) ||
                                course.description.toLowerCase().includes(term) ||
                                course.instructor.toLowerCase().includes(term)

                            const topicsSelected = this.filters.topics.length === 0
                            const matchesTopic = topicsSelected || this.filters.topics.includes(course.topic)

                            const levelsSelected = this.filters.levels.length === 0 || this.filters.levels.includes('All')
                            const matchesLevel = levelsSelected || this.filters.levels.includes(course.level)

                            const typesSelected = this.filters.types.length === 0
                            const matchesType = typesSelected || this.filters.types.includes(course.trainingType)

                            return matchesSearch && matchesTopic && matchesLevel && matchesType
                            })
                            if (this.currentPage > this.totalPages) {
                            this.currentPage = this.totalPages
                            }
                        }
                        }
                    }
                </script>
            </div>

      
@include('site.componants.footer')