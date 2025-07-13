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
           @php
                // $mentors = App\Models\Mentors::select('mentors.*','reviews.*','mentors.id as mentor_id','additional_info.*')
                //                                 ->join('reviews', 'mentors.id', '=', 'reviews.user_id')
                //                                 ->join('additional_info', 'mentors.id', '=', 'additional_info.user_id')
                //                                 ->where('additional_info.user_type', '=', 'mentor')
                //                                 ->where('reviews.user_type', '=', 'mentor')
                //                                 ->where('mentors.status', 'active')
                //                                 ->get();
                 $mentors = App\Models\Mentors::select('mentors.*','mentors.id as mentor_id')
                                                // ->join('reviews', 'mentors.id', '=', 'reviews.user_id')
                                                // ->join('additional_info', 'mentors.id', '=', 'additional_info.user_id')
                                                // ->where('additional_info.user_type', '=', 'mentor')
                                                // ->where('reviews.user_type', '=', 'mentor')
                                                ->where('mentors.status', 'active')
                                                ->get();
            @endphp

            <div class="flex max-w-7xl mx-auto px-4 py-6">
                <!-- Sidebar Filter -->
                <aside class="w-1/4 pr-6">
                    <button class="block text-gray-700 font-semibold mb-6">☰ Filter</button>

                    <!-- Course topic -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-2">Course topic</h3>
                        <div class="space-y-2">
                            <label class="block"><input type="checkbox" class="mr-2">Design</label>
                            <label class="block"><input type="checkbox" class="mr-2">Coding</label>
                            <label class="block"><input type="checkbox" class="mr-2">Mechanical</label>
                            <label class="block"><input type="checkbox" class="mr-2">Language</label>
                        </div>
                    </div>

                    <!-- Mentorship level -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 mb-2">Mentorship level</h3>
                        <div class="space-y-2">
                            <label class="block"><input type="checkbox" class="mr-2">Basic (Online)</label>
                            <label class="block"><input type="checkbox" class="mr-2">Advanced (Physical)</label>
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
                        <input type="text" placeholder="Search here..." class="w-full border border-gray-300 rounded-md px-4 py-2 pr-12" disabled />
                        <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">🔍</button>
                    </div>

                    <!-- Mentorship Overview (static) -->
                    <div class="border-b pb-4 mb-4">
                        <h2 class="text-lg font-semibold mb-2">Mentorship overview</h2>
                        <p>Hi, I’m Mohammad Raza — a dedicated mentor with over 5 years of experience in web development...</p>
                    </div>

                    <!-- Benefits (static) -->
                    <div class="border-b pb-4 mb-6">
                        <h2 class="text-lg font-semibold mb-2">Benefits of mentorship</h2>
                        <ul class="list-disc list-inside space-y-2">
                            <li>Personalized guidance tailored to your learning goals</li>
                            <li>Insight into real-world applications and industry practices</li>
                            <li>Motivation, support, and feedback</li>
                            <li>Networking opportunities</li>
                            <li>Boosted confidence to apply knowledge effectively</li>
                        </ul>
                    </div>

                    <!-- Mentor cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($mentors as $mentor)
                            <div class="bg-white rounded-lg shadow p-4 text-center">
                                <img src="{{ $mentor->document_path }}" alt="{{ $mentor->name }}" class="w-full h-48 object-cover rounded-md mb-4 mx-auto">
                                <a href="{{ route('mentorship-details', ['id' => $mentor->mentor_id]) }}">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $mentor->name }}</h3>
                                </a>
                                <p class="text-sm text-gray-600 mt-1">{{ $mentor->role }}</p>
                                <div class="flex items-center justify-center mt-2">
                                    <span class="text-orange-500 text-sm mr-1">★</span>
                                    <span class="text-sm text-gray-700">(4/5) Rating</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination UI (static) -->
                    <div class="flex justify-start items-center mt-8 space-x-2">
                        <button class="px-3 py-1 border rounded bg-gray-200 font-semibold">1</button>
                        <button class="px-3 py-1 border rounded hover:bg-gray-100">2</button>
                        <button class="px-3 py-1 border rounded hover:bg-gray-100">Next</button>
                    </div>
                </main>
            </div>




@include('site.componants.footer')