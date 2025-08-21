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
    @if($jobseekerNeedsSubscription)
            @include('site.jobseeker.subscription.index')
        @endif
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
            @php
                $mentors = App\Models\Mentors::with([
                        'reviews' => function ($q) {
                            $q->where('user_type', 'mentor');
                        },
                        'additionalInfo' => function ($q) {
                            $q->where('user_type', 'mentor');
                        },
                        'profilePicture' => function ($q) {
                            $q->where('user_type', 'mentor')
                            ->where('doc_type', 'mentor_profile_picture');
                        }
                    ])->where('status', 'active')->get();

            
                $trainingCategory = App\Models\TrainingCategory::select('training_categories.*')->get();

                $mentorshipOverview = App\Models\Cms::where('slug', 'mentorship-overview')->first();
                $benefitsOfMentorship = App\Models\Cms::where('slug', 'benefits-of-mentorship')->first();
            @endphp

            <div class="flex max-w-7xl mx-auto px-4 py-6">
                <!-- Sidebar Filter -->
                <aside class="w-1/4 pr-6">
                    <button class="block text-gray-700 font-semibold mb-6">☰ Filter</button>

                    <!-- Course topic -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-2">Course topic</h3>
                        <div class="space-y-2">
                            @foreach($trainingCategory->unique('category') as $category)
                                <label class="block">
                                    <input type="checkbox" class="mr-2"> {{ $category->category }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Mentorship level -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-2">Mentorship level</h3>
                        <div class="space-y-2">
                            <label class="block"><input type="checkbox" class="mr-2">Basic </label>
                            <label class="block"><input type="checkbox" class="mr-2">Advanced </label>
                            <label class="block"><input type="checkbox" class="mr-2">Intermidiate </label>
                        </div>
                    </div>
                </aside>

                <!-- Main content -->
                <main class="w-3/4">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-xl font-semibold">Mentors</h1>
                        <span class="text-sm text-gray-500">Showing {{ count($mentors) }} total results</span>
                    </div>

                    <!-- Search (static) -->
                    <div class="mb-6 relative">
                        <input type="text" id="searchInput" placeholder="Search here..." class="w-full border border-gray-300 rounded-md px-4 py-2 pr-12" />
                        <span class="absolute right-3 top-2.5 text-gray-400"></span>
                    </div>


                    <!-- Mentorship Overview (static) -->
                    <div class="border-b pb-4 mb-4">
                        <h2 class="text-lg font-semibold mb-2">Mentorship overview</h2>
                        <p>{{ $mentorshipOverview->description }}</p>
                    </div>

                    <!-- Benefits (static) -->
                    <div class="border-b pb-4 mb-6">
                        <h2 class="text-lg font-semibold mb-2">Benefits of mentorship</h2>
                         <p>{{ $benefitsOfMentorship->description }}</p>
                    </div>

                    <!-- Mentor cards -->
                    <div id="mentorList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($mentors as $mentor)
                            @php
                                $avgRating = $mentor->reviews->avg('ratings');
                                $ratingCount = $mentor->reviews->count();
                                $imagePath = $mentor->profilePicture?->document_path ?? 'default.jpg';
                            @endphp
                            <div class="bg-white rounded-lg shadow p-4 text-center mentor-card">
                                <img src="{{  $imagePath }}" alt="{{ $mentor->name }}" class="w-full h-48 object-cover rounded-md mb-4 mx-auto">
                                <a href="{{ route('mentorship-details', ['id' => $mentor->id]) }}">
                                    <h3 class="text-lg font-semibold text-gray-900 mentor-name">{{ $mentor->name }}</h3>
                                </a>
                                {{-- <p class="text-sm text-gray-600 mt-1 mentor-role">{{ $mentor->role ?? 'N/A' }}</p> --}}
                                <div class="flex items-center justify-center mt-2">
                                    <span class="text-orange-500 text-sm mr-1">★</span>
                                    <span class="text-sm text-gray-700">
                                        ({{ number_format($avgRating, 1) }}/5) Rating
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div id="pagination" class="flex justify-start items-center mt-8 space-x-2"></div>


                    <script>
                        const mentorCards = Array.from(document.querySelectorAll('.mentor-card'));
                        const mentorList = document.getElementById('mentorList');
                        const paginationContainer = document.getElementById('pagination');
                        const perPage = 6;
                        let currentPage = 1;

                        function renderPage(page) {
                            currentPage = page;
                            const start = (page - 1) * perPage;
                            const end = start + perPage;

                            // Clear and show current page's cards
                            mentorList.innerHTML = '';
                            mentorCards.slice(start, end).forEach(card => mentorList.appendChild(card));

                            renderPagination();
                        }

                        function renderPagination() {
                            const totalPages = Math.ceil(mentorCards.length / perPage);
                            paginationContainer.innerHTML = '';

                            // Prev button
                            const prevBtn = document.createElement('button');
                            prevBtn.textContent = 'Prev';
                            prevBtn.className = 'px-3 py-1 border rounded hover:bg-gray-100';
                            prevBtn.disabled = currentPage === 1;
                            prevBtn.classList.toggle('bg-gray-200', currentPage === 1);
                            prevBtn.addEventListener('click', () => renderPage(currentPage - 1));
                            paginationContainer.appendChild(prevBtn);

                            // Page numbers
                            for (let i = 1; i <= totalPages; i++) {
                                const btn = document.createElement('button');
                                btn.textContent = i;
                                btn.className = 'px-3 py-1 border rounded ' + (i === currentPage ? 'bg-gray-300 font-semibold' : 'hover:bg-gray-100');
                                btn.addEventListener('click', () => renderPage(i));
                                paginationContainer.appendChild(btn);
                            }

                            // Next button
                            const nextBtn = document.createElement('button');
                            nextBtn.textContent = 'Next';
                            nextBtn.className = 'px-3 py-1 border rounded hover:bg-gray-100';
                            nextBtn.disabled = currentPage === totalPages;
                            nextBtn.classList.toggle('bg-gray-200', currentPage === totalPages);
                            nextBtn.addEventListener('click', () => renderPage(currentPage + 1));
                            paginationContainer.appendChild(nextBtn);
                        }

                        // Initial render
                        renderPage(1);
                    </script>

                </main>
                <script>
                    document.getElementById('searchInput').addEventListener('input', function () {
                        const query = this.value.toLowerCase();
                        const mentorCards = document.querySelectorAll('.mentor-card');

                        mentorCards.forEach(card => {
                            const name = card.querySelector('.mentor-name').textContent.toLowerCase();
                            const role = card.querySelector('.mentor-role').textContent.toLowerCase();

                            if (name.includes(query) || role.includes(query)) {
                                card.style.display = 'block';
                            } else {
                                card.style.display = 'none';
                            }
                        });
                    });
                </script>

            </div>




@include('site.componants.footer')