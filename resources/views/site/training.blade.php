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

                <aside class="w-1/4 pr-6" x-data="courseFilter()">
                    <button class="block text-gray-700 font-semibold mb-6">☰ Filter</button>

                    @php
                        use App\Models\Trainers;
                        use App\Models\TrainingCategory;
                        use App\Models\TrainingMaterial;

                        $trainers = Trainers::with('materials')->get();
                        $categories = TrainingCategory::all();
                        $materials = TrainingMaterial::all();
                    @endphp

                    <!-- Course Topic -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-2">Course topic</h3>
                        <div id="topicSection" class="space-y-2">
                            @foreach ($categories as $category)
                            <label class="block">
                                <input type="checkbox"
                                    value="{{ $category->category }}"
                                    @change="$dispatch('filter-change', {type: 'category', value: '{{ $category->category }}', checked: $event.target.checked})"
                                    class="mr-2">
                                {{ $category->category }}
                            </label>
                            @endforeach
                        </div>
                    </div>


                    <!-- Course Level -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-2">Course level</h3>
                        <div id="levelSection" class="space-y-2">
                            @foreach(['Beginner','Intermediate','Advanced'] as $level)
                            <label class="block">
                                <input type="checkbox"
                                    value="{{ $level }}"
                                    @change="$dispatch('filter-change', {type: 'level', value: '{{ $level }}', checked: $event.target.checked})"
                                    class="mr-2">
                                {{ $level }}
                            </label>
                            @endforeach

                        </div>
                    </div>

                    <!-- Training Type -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-2">Training type</h3>
                        <div id="typeSection" class="space-y-2">
                            @foreach(['online','classroom','recorded'] as $type)
                            <label class="block">
                                <input type="checkbox"
                                    value="{{ $type }}"
                                    @change="$dispatch('filter-change', {type: 'training_type', value: '{{ $type }}', checked: $event.target.checked})"
                                    class="mr-2">
                                {{ ucfirst($type) }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                </aside>


                <main class="w-3/4 mx-auto" x-data="courseFilter()">

                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-xl font-semibold">Courses</h1>
                        <span class="text-sm text-gray-500">
                            Showing <span x-text="filteredCourses.length"></span> total results
                        </span>
                    </div>

                    <!-- Search -->
                    <div class="mb-6 relative">
                        <input type="text" placeholder="Search here..."
                            x-model="search"
                            @input="filterCourses"
                            class="w-full border border-gray-300 rounded-md px-4 py-2 pr-12" />
                        <span class="absolute right-3 top-2.5 text-gray-400">🔍</span>
                    </div>

                    <!-- Course List -->
                    <div id="course-list">
                        <template x-for="material in paginatedCourses() " :key="material.id">
                            <div class="course-item flex border rounded-md overflow-hidden shadow-sm mb-4 bg-white">
                                <!-- Thumbnail -->
                                <div style="min-width: 250px; max-width: 250px; overflow: hidden;">
                                    <img :src="material.thumbnail_file_path 
                                            ? '{{ asset('uploads') }}/' + material.thumbnail_file_path.split('/').pop() 
                                            : '/default.png'" 
                                        alt="Thumbnail"
                                        class="h-full w-full object-cover" />



                                </div>

                                <!-- Details -->
                                <div class="p-4 flex-1 flex flex-col justify-between">
                                    <div>
                                        <a :href="`{{ route('course.details', '') }}/${material.id}`">
                                            <h2 class="font-semibold text-lg text-gray-800" x-text="material.training_title"></h2>
                                        </a>

                                        <p class="text-sm text-gray-600 mt-1" x-text="material.training_sub_title"></p>

                                        <!-- Rating -->
                                        <p class="mt-1 text-yellow-500">
                                            <template x-for="i in 5">
                                                <span :class="i <= Math.floor(material.rating ?? 0) ? '' : 'text-gray-300'">★</span>
                                            </template>
                                            <span class="text-gray-700 font-medium ml-2">(<span x-text="material.rating ?? 0"></span>/5)</span>
                                        </p>
                                    </div>

                                    <div class="flex items-center text-sm text-gray-700 space-x-6 mt-4 flex-wrap">
                                        <!-- Trainer -->
                                        <div class="flex items-center space-x-1">
                                            <img :src="material.trainer?.profile_picture ?? 'https://ui-avatars.com/api/?name=' + encodeURIComponent(material.trainer?.name)"
                                                class="rounded-full w-6 h-6" />
                                            <span x-text="material.trainer?.name"></span>
                                        </div>
                                        
                                        <!-- Lessons -->
                                        <div x-show="material.training_type === 'recorded'" class="flex items-center space-x-1">
                                            📘 <span x-text="material.documents?.length ?? 0"></span> lessons
                                        </div>

                                        <!-- Duration -->
                                        <div x-show="material.training_type === 'recorded'" class="flex items-center space-x-1">
                                            ⏱️ <span x-text="calculateHours(material.batches)"></span> hrs
                                        </div>

                                         <!-- Lessons -->
                                        <!-- <div class="flex items-center space-x-1">📘 <span x-text="material.documents?.length ?? 0"></span> lessons</div> -->

                                        <!-- Duration -->
                                        <!-- <div class="flex items-center space-x-1">
                                            ⏱️ <span x-text="calculateHours(material.batches)"></span> hrs
                                        </div> -->


                                        <!-- Level -->
                                        <div>📈 <span x-text="material.training_level ?? 'Beginner'"></span></div>

                                        <!-- Session -->
                                        <div>🎥 <span x-text="material.session_type ?? 'Recorded'"></span></div>
                                    </div>
                                </div>

                                <!-- Price -->
                                <div class="flex flex-col justify-center items-end p-4 w-32 text-right">
                                    <span class="text-gray-400 line-through text-sm">
                                        SAR <span x-text="Number(material.training_price).toFixed(0)"></span>
                                    </span>
                                    <span class="text-xl font-semibold text-gray-800">
                                        SAR <span x-text="Number(material.training_offer_price).toFixed(0)"></span>
                                    </span>
                                </div>
                            </div>
                        </template>
                    </div>
                    <!-- Pagination Controls -->
                    <div class="flex justify-center items-center space-x-2 mt-6">
                        <button @click="prevPage" :disabled="currentPage === 1"
                            class="px-3 py-1 rounded border"
                            :class="{'opacity-50 cursor-not-allowed': currentPage === 1}">
                            Previous
                        </button>

                        <template x-for="page in totalPages()" :key="page">
                            <button @click="goToPage(page)"
                                class="px-3 py-1 rounded border"
                                :class="{'bg-blue-500 text-white': currentPage === page}">
                                <span x-text="page"></span>
                            </button>
                        </template>

                        <button @click="nextPage" :disabled="currentPage === totalPages()"
                            class="px-3 py-1 rounded border"
                            :class="{'opacity-50 cursor-not-allowed': currentPage === totalPages()}">
                            Next
                        </button>
                    </div>
                </main>


                <script>
                    function courseFilter() {
                        return {
                            allCourses: @json($materials), // Laravel se sab data aa gaya
                            filteredCourses: [],
                            search: '',
                            selectedCategories: [],
                            selectedLevels: [],
                            selectedTypes: [],

                            // 🔹 Pagination states
                            currentPage: 1,
                            perPage: 10,

                            init() {
                                this.filteredCourses = this.allCourses;

                                // Filter checkboxes ke events handle karne ke liye listener
                                window.addEventListener('filter-change', (e) => {
                                    const { type, value, checked } = e.detail;

                                    if (type === 'category') {
                                        if (checked) this.selectedCategories.push(value);
                                        else this.selectedCategories = this.selectedCategories.filter(c => c !== value);
                                    }

                                    if (type === 'level') {
                                        if (checked) this.selectedLevels.push(value);
                                        else this.selectedLevels = this.selectedLevels.filter(l => l !== value);
                                    }

                                    if (type === 'training_type') {
                                        if (checked) this.selectedTypes.push(value);
                                        else this.selectedTypes = this.selectedTypes.filter(t => t !== value);
                                    }

                                    this.filterCourses();
                                });
                            },

                            filterCourses() {
                                this.filteredCourses = this.allCourses.filter(course => {
                                    const categoryMatch = this.selectedCategories.length === 0 
                                        || this.selectedCategories.includes(String(course.training_category));

                                    const levelMatch = this.selectedLevels.length === 0 
                                        || this.selectedLevels.includes(course.training_level);

                                    const typeMatch = this.selectedTypes.length === 0 
                                        || this.selectedTypes.includes(course.training_type);

                                    const searchMatch = course.training_title.toLowerCase().includes(this.search.toLowerCase());

                                    return categoryMatch && levelMatch && typeMatch && searchMatch;
                                });

                                // 🔹 Reset page after filter
                                this.currentPage = 1;
                            },

                            // 🔹 Pagination helpers
                            totalPages() {
                                return Math.ceil(this.filteredCourses.length / this.perPage);
                            },

                            paginatedCourses() {
                                const start = (this.currentPage - 1) * this.perPage;
                                return this.filteredCourses.slice(start, start + this.perPage);
                            },

                            nextPage() {
                                if (this.currentPage < this.totalPages()) this.currentPage++;
                            },

                            prevPage() {
                                if (this.currentPage > 1) this.currentPage--;
                            },

                            goToPage(page) {
                                this.currentPage = page;
                            },

                            calculateHours(batches) {
                                if (!batches) return 0;
                                let total = 0;
                                batches.forEach(b => {
                                    const start = new Date('1970-01-01T' + b.start_timing);
                                    const end = new Date('1970-01-01T' + b.end_timing);
                                    total += (end - start) / 3600000;
                                });
                                return total;
                            }
                        }
                    }

                </script>




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

            </div>




      
@include('site.componants.footer')