<?php
    
    use Illuminate\Support\Facades\DB;

    // Fetch all trainers
    $trainers = DB::table('trainers')->get();

    foreach ($trainers as $trainer) {
        $profile = DB::table('additional_info')
            ->where('user_id', $trainer->id)
            ->where('user_type', 'trainer')
            ->where('doc_type', 'profile_picture')
            ->first();

        $trainer->profile_picture = $profile ? $profile->document_path : null;
        
        // Fetch materials for each trainer
        $trainer->materials = DB::table('training_materials')
            ->where('trainer_id', $trainer->id)
            ->get();

        foreach ($trainer->materials as $material) {
            // Fetch documents for each material
            $material->documents = DB::table('training_materials_documents')
                ->where('training_material_id', $material->id)
                ->get();

            // Fetch batches for each material
            $material->batches = DB::table('training_batches')
                ->where('training_material_id', $material->id)
                ->get();
        }
    }


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
                            @php
                                $categories = App\Models\TrainingCategory::all();
                            @endphp
                            @foreach ($categories as $category)
                                <label class="block"><input type="checkbox" class="mr-2" value="Design" @change="$dispatch('filter-change')">{{ $category->category }}</label>
                            @endforeach
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
              

                <!-- Load Alpine.js (if not already loaded) -->
                <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

                <!-- Wrapper with Alpine Pagination Logic -->
                <div x-data="coursePagination()" x-init="init()">
                    <div class="container py-4">
                        <h2 class="mb-4">All Courses</h2>

                        @foreach($trainers as $trainer)
                            @foreach($trainer->materials as $material)
                            <a href="{{ route('course.details', $material->id) }}" class="text-decoration-none text-dark">

                                <div class="course-item d-flex bg-white border rounded shadow-sm mb-4 overflow-hidden" style="height: 220px;">
                                    {{-- Thumbnail --}}
                                    <div style="min-width: 250px; max-width: 250px; overflow: hidden;">
                                        <img src="{{ asset($material->thumbnail_file_path) }}"
                                            alt="Thumbnail"
                                            class="img-fluid h-100 w-100 object-cover"
                                            style="object-fit: cover;">
                                    </div>

                                    {{-- Details --}}
                                    <div class="flex-grow-1 p-4 d-flex flex-column justify-content-between">
                                        <div>
                                            <h5 class="fw-bold mb-1">{{ $material->training_title }}</h5>
                                            <p class="text-muted mb-2" style="font-size: 0.95rem;">{{ $material->training_sub_title }}</p>
                                            <p class="mb-2"><span class="text-warning">★</span> (4/5) <strong>Rating</strong></p>
                                        </div>

                                        <div class="d-flex align-items-center gap-3 text-muted small flex-wrap">
                                            {{-- Trainer --}}
                                            <div class="d-flex align-items-center me-3">
                                                <img src="{{ $trainer->profile_picture ?? 'https://ui-avatars.com/api/? name=' . urlencode($trainer->name) }}" 
                                                        alt="{{ $trainer->name }}" 
                                                        class="w-7 h-7 rounded-full mr-2">
                                                {{ $trainer->name }}
                                            </div>

                                            {{-- Lessons --}}
                                            <div class="me-3">
                                                📘 {{ count($material->documents) }} lessons
                                            </div>

                                            {{-- Duration --}}
                                            <div class="me-3">
                                                ⏱️ 
                                                @php
                                                    $totalHours = 0;
                                                    foreach ($material->batches as $batch) {
                                                        $start = strtotime($batch->start_timing);
                                                        $end = strtotime($batch->end_timing);
                                                        $totalHours += ($end - $start) / 3600;
                                                    }
                                                @endphp
                                                {{ $totalHours }}hrs
                                            </div>

                                            {{-- Level --}}
                                            <div>
                                                📈 {{ $material->training_level ?? 'Beginner' }}
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Price --}}
                                    <div class="d-flex flex-column justify-content-center align-items-end p-4" style="min-width: 120px;">
                                        <div class="text-muted text-decoration-line-through">
                                            SAR {{ number_format($material->training_price, 0) }}
                                        </div>
                                        <div class="fw-bold fs-5 text-dark">
                                            SAR {{ number_format($material->training_offer_price, 0) }}
                                        </div>
                                    </div>
                                </div>
                            </a>    
                            @endforeach
                        @endforeach
                    </div>

                    <!-- Pagination controls -->
                    <div class="flex justify-center items-center space-x-2 mt-6">
                        <button
                            class="px-3 py-1 rounded border"
                            :disabled="currentPage === 1"
                            @click="prevPage"
                        >
                            Previous
                        </button>

                        <template x-for="page in totalPages" :key="page">
                            <button
                                class="px-3 py-1 rounded border"
                                :class="{'bg-blue-500 text-white': currentPage === page}"
                                @click="goToPage(page)"
                            >
                                <span x-text="page"></span>
                            </button>
                        </template>

                        <button
                            class="px-3 py-1 rounded border"
                            :disabled="currentPage === totalPages"
                            @click="nextPage"
                        >
                            Next
                        </button>
                    </div>
                </div>

                <!-- Alpine Pagination Logic -->
                 <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const items = document.querySelectorAll('.course-item');
                        const perPage = 5;
                        const totalPages = Math.ceil(items.length / perPage);
                        let currentPage = 1;

                        const paginationContainer = document.getElementById('pagination');

                        function showPage(page) {
                            currentPage = page;
                            let start = (page - 1) * perPage;
                            let end = start + perPage;

                            items.forEach((item, index) => {
                                item.style.display = (index >= start && index < end) ? 'flex' : 'none';
                            });

                            renderPagination();
                        }

                        function renderPagination() {
                            paginationContainer.innerHTML = '';

                            // Previous Button
                            let prev = document.createElement('button');
                            prev.innerText = 'Previous';
                            prev.className = 'px-3 py-1 rounded border';
                            prev.disabled = currentPage === 1;
                            prev.onclick = () => showPage(currentPage - 1);
                            paginationContainer.appendChild(prev);

                            // Page Numbers
                            for (let i = 1; i <= totalPages; i++) {
                                let btn = document.createElement('button');
                                btn.innerText = i;
                                btn.className = 'px-3 py-1 rounded border mx-1';
                                if (i === currentPage) {
                                    btn.classList.add('bg-blue-500', 'text-white');
                                }
                                btn.onclick = () => showPage(i);
                                paginationContainer.appendChild(btn);
                            }

                            // Next Button
                            let next = document.createElement('button');
                            next.innerText = 'Next';
                            next.className = 'px-3 py-1 rounded border';
                            next.disabled = currentPage === totalPages;
                            next.onclick = () => showPage(currentPage + 1);
                            paginationContainer.appendChild(next);
                        }

                        showPage(1);
                    });
                </script>




                </main>


                @php
                    $trainings = \App\Models\TrainingMaterial::select('*')
                                ->where('admin_status', 'approved', 'superadmin_approved')
                                ->get();

                @endphp
                <script>
                    function courseApp() {
                        return {
                        searchTerm: '',
                        courses: [
                            @foreach($trainings as $course)
                                {
                                    id: {{ $course->id }},
                                    title: @json($course->training_title),
                                    description: @json($course->training_sub_title),
                                    image: "{{ asset('storage/' . $course->thumbnail_file_path) }}",
                                    rating: {{ $course->rating ?? 0 }},
                                    instructor: @json($course->instructor_name),
                                    instructorImage: "{{ $course->instructor_image ?? 'https://randomuser.me/api/portraits/lego/1.jpg' }}",
                                    lessons: {{ $course->lessons ?? 0 }},
                                    duration: @json($course->duration ?? 'N/A'),
                                    level: @json($course->level ?? 'N/A'),
                                    topic: @json($course->topic ?? 'N/A'),
                                    trainingType: @json($course->training_type ?? 'N/A'),
                                    originalPrice: @json($course->original_price ?? 'N/A'),
                                    discountedPrice: @json($course->discounted_price ?? 'N/A'),
                                },
                            @endforeach
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