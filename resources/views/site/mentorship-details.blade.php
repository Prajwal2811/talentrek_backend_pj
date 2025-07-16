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


            <main class="w-11/12 mx-auto py-8">
                <style>
                    html {
                        scroll-behavior: smooth;
                    }
                </style>

                <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">
                    <!-- Left/Main Content -->
                    <div class="flex-1">
                        <!-- Mentor Info -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between p-6 rounded-lg">
                            <div class="flex items-center gap-6">
                                <img src="{{ $mentorDetails->profilePicture->url ?? asset('asset/images/client-logo/w2.png') }}" alt="Mentor" class="w-28 h-28 rounded-full object-cover border" />
                                <div>
                                    <h1 class="text-xl font-semibold text-gray-900">{{ $mentorDetails->name }}</h1>
                                    <p class="text-sm text-gray-600 mt-1">{{ $mentorDetails->profession ?? 'Not specified' }}</p>
                                    <p class="text-sm text-gray-500 mt-0.5">{{ $mentorDetails->experience }}+ years experience</p>

                                    @php
                                        $rating = round($mentorDetails->reviews->avg('ratings'), 1);
                                        $fullStars = floor($rating);
                                        $hasHalf = ($rating - $fullStars) >= 0.5;
                                    @endphp

                                    <div class="flex items-center mt-1 text-sm">
                                        <div class="flex text-[#f59e0b]">
                                            @for($i = 0; $i < $fullStars; $i++)
                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><polygon points="9.9,1.1 7.6,6.9 1.4,7.6 6.1,11.8 4.8,18 9.9,14.9 15,18 13.7,11.8 18.4,7.6 12.2,6.9"/></svg>
                                            @endfor
                                            @if($hasHalf)
                                                <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20"><polygon points="9.9,1.1 7.6,6.9 1.4,7.6 6.1,11.8 4.8,18 9.9,14.9 15,18 13.7,11.8 18.4,7.6 12.2,6.9"/></svg>
                                            @endif
                                            @for($i = $fullStars + ($hasHalf ? 1 : 0); $i < 5; $i++)
                                                <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20"><polygon points="9.9,1.1 7.6,6.9 1.4,7.6 6.1,11.8 4.8,18 9.9,14.9 15,18 13.7,11.8 18.4,7.6 12.2,6.9"/></svg>
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-gray-600">({{ number_format($rating, 1) }}/5) <span class="text-xs">Rating</span></span>
                                    </div>

                                    <p class="text-sm text-gray-800 font-semibold mt-1">
                                        {{ $mentorDetails->price ?? 'SAR 0' }} <span class="text-xs text-gray-500">per mentorship session</span>
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4 md:mt-0">
                                @if($mentorDetails->booking_slot_id)
                                    <a href="{{ route('mentorship-book-session', ['mentor_id' => $mentorDetails->mentor_id, 'slot_id' => $mentorDetails->booking_slot_id]) }}">
                                        <button class="bg-blue-800 hover:bg-blue-900 text-white px-6 py-2 rounded-md font-medium shadow-sm transition">
                                            Book Session
                                        </button>
                                    </a>
                                @else
                                    <button class="bg-gray-400 cursor-not-allowed text-white px-6 py-2 rounded-md font-medium shadow-sm" disabled>
                                        No Slot Available
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Tabs -->
                        <div class="flex gap-6 border-b mb-6 text-sm font-medium">
                            <a href="#about" class="tab-link pb-2 text-gray-600 hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600">About Mentor</a>
                            <a href="#reviews" class="tab-link pb-2 text-gray-600 hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600">Reviews</a>
                        </div>

                        <!-- About Section -->
                        <section id="about" class="mb-6">
                            <h2 class="text-lg font-semibold mb-2">About Mentor</h2>
                            <p class="text-sm text-gray-700 mb-6">
                                {{ $mentorDetails->about ?? 'No bio available.' }}
                            </p>

                            <h2 class="text-lg font-semibold mb-2">Experience</h2>
                            <div class="mb-6">
                                <p class="text-sm font-semibold text-gray-800">Work experience:</p>
                                <p class="text-sm text-gray-700">{{ $mentorDetails->experience }} years</p>
                            </div>

                            <h2 class="text-lg font-semibold mb-2">Qualification</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <p class="font-semibold text-gray-800">Highest qualification</p>
                                    <p class="text-gray-700">{{ $mentorDetails->additionalInfo->highest_qualification ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">Field of study:</p>
                                    <p class="text-gray-700">{{ $mentorDetails->additionalInfo->field_of_study ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">Institution name:</p>
                                    <p class="text-gray-700">{{ $mentorDetails->additionalInfo->institution_name ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </section>

                        <!-- Reviews Section -->
                        <section id="reviews">
                            <h2 class="text-lg font-semibold mb-3">Reviews</h2>

                            @php
                                $totalReviews = $mentorDetails->reviews->count();
                                $ratingCounts = $mentorDetails->reviews->groupBy('rating')->map->count();
                            @endphp

                            <div class="flex items-center mb-4">
                                <div class="text-4xl font-bold mr-4">{{ number_format($rating, 1) }}</div>
                                <div class="flex-1 space-y-1">
                                    @for($i = 5; $i >= 1; $i--)
                                        @php
                                            $percent = $totalReviews ? ($ratingCounts[$i] ?? 0) / $totalReviews * 100 : 0;
                                        @endphp
                                        <div class="flex items-center space-x-2">
                                            <div class="w-48 bg-gray-200 h-2 rounded">
                                                <div class="bg-orange-500 h-2 rounded" style="width: {{ $percent }}%"></div>
                                            </div>
                                            <span class="text-sm text-gray-500">{{ round($percent) }}%</span>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <!-- Write Review (can be wrapped in a form if needed) -->
                            <textarea rows="4" placeholder="Write a review..." class="w-full border border-gray-300 p-2 rounded mb-2 text-sm"></textarea>
                            <div class="flex items-center justify-between mb-6">
                                <div class="text-yellow-400 text-lg">★★★★★</div>
                                <button class="bg-blue-600 text-white px-4 py-1 rounded text-sm">Submit Review</button>
                            </div>

                            <!-- Review Cards -->
                            <div class="space-y-4">
                                @forelse($mentorDetails->reviews as $review)
                                    <div class="border p-4 rounded shadow-sm">
                                        <p class="text-sm font-semibold">{{ $review->jobseeker->name ?? 'Anonymous' }}</p>
                                        <p class="text-yellow-400 text-sm">
                                            {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                                        </p>
                                        <p class="text-sm text-gray-700">{{ $review->comment }}</p>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">No reviews yet.</p>
                                @endforelse
                            </div>
                        </section>
                    </div>
                </div>
            </main>


          
          <style>
            .active-tab {
              border-bottom-color: #2563eb; /* Tailwind blue-600 */
              color: #2563eb;
            }
          </style>
        </div>

@include('site.componants.footer')