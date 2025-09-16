<?php
    
    use Illuminate\Support\Facades\DB;

    // Fetch all trainers
    $trainers = DB::table('trainers')->get();

    foreach ($trainers as $trainer) {
        $profile = DB::table('additional_info')
            ->where('user_id', $trainer->id)
            ->where('user_type', 'trainer')
            ->where('doc_type', 'trainer_profile_picture')
            ->first();
        
        $trainer->profile_picture = $profile ? $profile->document_path : null;
        
        // Fetch materials for each trainer
        $trainer->materials = DB::table('training_materials')
            ->where('trainer_id', $trainer->id)
            ->get();
        //     echo "<pre>";
        // print_r($trainer->materials);exit;
        foreach ($trainer->materials as $material) {
            // Fetch documents for each material
            $material->documents = DB::table('training_materials_documents')
                ->where('training_material_id', $material->id)
                ->get();

            // Fetch batches for each material
            $material->batches = DB::table('training_batches')
                ->where('training_material_id', $material->id)
                ->get();

            $material->rating = DB::table('reviews')
                ->where('user_type', 'trainer')
                ->where('user_id', $trainer->id)
                ->where('trainer_material', $material->id)
                ->avg('ratings');

            $material->rating_count = DB::table('reviews')
                ->where('user_type', 'trainer')
                ->where('user_id', $trainer->id)
                ->where('trainer_material', $material->id)
                ->count();

            $material->reviews = DB::table('reviews')
                ->where('user_type', 'trainer')
                ->where('user_id', $trainer->id)
                ->where('trainer_material', $material->id)
                ->select('ratings', 'reviews')
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

                <main class="w-3/4 mx-auto">

                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-xl font-semibold">Courses</h1>
                        <span class="text-sm text-gray-500">
                            Showing <span id="total-results">0</span> total results
                        </span>
                    </div>

                    <!-- Course List -->
                    <div id="course-list">
                        @foreach($trainers as $trainer)
                            @foreach($trainer->materials as $material)
                                <div class="course-item flex border rounded-md overflow-hidden shadow-sm mb-4 bg-white">
                                    <!-- Thumbnail -->
                                    <div style="min-width: 250px; max-width: 250px; overflow: hidden;">
                                        <img src="{{ asset($material->thumbnail_file_path) }}"
                                            alt="Thumbnail"
                                            class="h-full w-full object-cover" />
                                    </div>

                                    <!-- Details -->
                                    <div class="p-4 flex-1 flex flex-col justify-between">
                                        <div>
                                            <a href="{{ route('course.details', $material->id) }}">
                                                <h2 class="font-semibold text-lg text-gray-800">{{ $material->training_title }}</h2>
                                            </a>
                                            <p class="text-sm text-gray-600 mt-1">{{ $material->training_sub_title }}</p>
                                            @php
                                                $avgRating = round($material->rating ?? 0, 1); // rounded to 1 decimal place
                                                $filledStars = floor($avgRating); // for star display
                                            @endphp

                                            <p class="mt-1 text-yellow-500">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span class="{{ $i <= $filledStars ? '' : 'text-gray-300' }}">★</span>
                                                @endfor
                                                <span class="text-gray-700 font-medium ml-2">({{ $avgRating }}/5)</span>
                                            </p>
                                        </div>


                                        <div class="flex items-center text-sm text-gray-700 space-x-6 mt-4 flex-wrap">
                                            <div class="flex items-center space-x-1">
                                                <img src="{{ $trainer->profile_picture ?? 'https://ui-avatars.com/api/?name=' . urlencode($trainer->name) }}"
                                                    alt="{{ $trainer->name }}"
                                                    class="rounded-full w-6 h-6" />
                                                <span>{{ $trainer->name }}</span>
                                            </div>
                                            <div class="flex items-center space-x-1">📘 {{ count($material->documents) }} lessons</div>
                                            <div class="flex items-center space-x-1">
                                                ⏱️ 
                                                @php
                                                    $totalHours = 0;
                                                    foreach ($material->batches as $batch) {
                                                        $start = strtotime($batch->start_timing);
                                                        $end = strtotime($batch->end_timing);
                                                        $totalHours += ($end - $start) / 3600;
                                                    }
                                                @endphp
                                                {{ $totalHours }} hrs
                                            </div>
                                            <div>📈 {{ $material->training_level ?? 'Beginner' }}</div>
                                            <div>🎥 {{ $material->session_type ?? 'Recorded' }}</div>
                                        </div>
                                    </div>

                                    <!-- Price -->
                                    <div class="flex flex-col justify-center items-end p-4 w-32 text-right">
                                        <span class="text-gray-400 line-through text-sm">
                                            SAR {{ number_format($material->training_price, 0) }}
                                        </span>
                                        <span class="text-xl font-semibold text-gray-800">
                                            SAR {{ number_format($material->training_offer_price, 0) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div id="pagination-controls" class="flex justify-center items-center space-x-2 mt-6"></div>

                </main>

                <!-- JS Pagination Script -->
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const items = document.querySelectorAll('.course-item');
                    const perPage = 10;
                    let currentPage = 1;
                    const totalPages = Math.ceil(items.length / perPage);
                    const pagination = document.getElementById('pagination-controls');
                    document.getElementById('total-results').textContent = items.length;

                    function showPage(page) {
                        const start = (page - 1) * perPage;
                        const end = start + perPage;

                        items.forEach((item, index) => {
                            item.style.display = (index >= start && index < end) ? 'flex' : 'none';
                        });

                        renderPagination(page);
                    }

                    function renderPagination(page) {
                        pagination.innerHTML = '';

                        // Prev button
                        const prevBtn = document.createElement('button');
                        prevBtn.textContent = 'Previous';
                        prevBtn.className = 'px-3 py-1 rounded border';
                        prevBtn.disabled = page === 1;
                        prevBtn.onclick = () => showPage(page - 1);
                        pagination.appendChild(prevBtn);

                        // Page numbers
                        for (let i = 1; i <= totalPages; i++) {
                            const btn = document.createElement('button');
                            btn.textContent = i;
                            btn.className = `px-3 py-1 rounded border ${i === page ? 'bg-blue-500 text-white' : ''}`;
                            btn.onclick = () => showPage(i);
                            pagination.appendChild(btn);
                        }

                        // Next button
                        const nextBtn = document.createElement('button');
                        nextBtn.textContent = 'Next';
                        nextBtn.className = 'px-3 py-1 rounded border';
                        nextBtn.disabled = page === totalPages;
                        nextBtn.onclick = () => showPage(page + 1);
                        pagination.appendChild(nextBtn);
                    }

                    showPage(currentPage);
                });
                </script>



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