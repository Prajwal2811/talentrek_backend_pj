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
                        @php
                            $profilePicture = optional($mentorDetails->profilePicture)->document_path ?? asset('default.jpg');
                            $avgRating = number_format($mentorDetails->reviews->avg('ratings'), 1);
                            $reviewStars = floor($avgRating);
                        @endphp

                        <img src="{{ $profilePicture }}" alt="{{ $mentorDetails->name }}" class="w-28 h-28 rounded-full object-cover border" />

                        <div>
                            <h1 class="text-xl font-semibold text-gray-900">{{ $mentorDetails->name }}</h1>
                            <p class="text-sm text-gray-600 mt-1">{{ $mentorDetails->additionalInfo->designation ?? 'mentor' }}</p>
                            <p class="text-sm text-gray-500 mt-0.5">
                                {{ $mentorDetails->total_experience ?? '0 years 0 months 0 days' }} of experience
                            </p>


                            <div class="flex items-center mt-1 text-sm">
                                <div class="flex text-[#f59e0b]">
                                    @for ($i = 0; $i < 5; $i++)
                                        @if ($i < $reviewStars)
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><polygon points="9.9,1.1 7.6,6.9 1.4,7.6 6.1,11.8 4.8,18 9.9,14.9 15,18 13.7,11.8 18.4,7.6 12.2,6.9"/></svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20"><polygon points="9.9,1.1 7.6,6.9 1.4,7.6 6.1,11.8 4.8,18 9.9,14.9 15,18 13.7,11.8 18.4,7.6 12.2,6.9"/></svg>
                                        @endif
                                    @endfor
                                </div>
                                <span class="ml-2 text-gray-600">({{ $avgRating }}/5) <span class="text-xs">Rating</span></span>
                            </div>
                        </div>
                    </div>

                       @if($mentorDetails->bookingSlots->isNotEmpty())
                            @php
                                $firstSlot = $mentorDetails->bookingSlots->first();
                            @endphp
                            <a href="{{ route('mentorship-book-session', ['mentor_id' => $mentorDetails->id, 'slot_id' => $firstSlot->id]) }}">
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

                <!-- Tabs -->
                <div class="flex gap-6 border-b mb-6 text-sm font-medium">
                    <a href="#about" class="tab-link pb-2 text-gray-600 hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600">About Mentor</a>
                    <a href="#reviews" class="tab-link pb-2 text-gray-600 hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600">Reviews</a>
                </div>

                <!-- About Section -->
                <section id="about" class="mb-6 mt-6">
                    <h2 class="text-lg font-semibold mb-2">About Mentor</h2>
                    <p class="text-sm text-gray-700 mb-6">
                        {!! nl2br(e($mentorDetails->about_mentor ?? 'Bio not available.')) !!}
                    </p>

                    <h2 class="text-lg font-semibold mb-2">Experience</h2>
                    <div class="mb-6">
                        <p class="text-sm font-semibold text-gray-800">Work experience:</p>
                        <p class="text-sm text-gray-700 mt-0.5">
                            {{ $mentorDetails->total_experience ?? '0 years 0 months 0 days' }} of experience
                        </p>
                    </div>

                    <h2 class="text-lg font-semibold mb-2">Qualification</h2>
                    @php
                        $education = $mentorDetails->educations->first();
                        
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="font-semibold text-gray-800">Highest qualification</p>
                            <p class="text-gray-700">{{ $education->high_education ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Field of study:</p>
                            <p class="text-gray-700">{{ $education->field_of_study ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Institution name:</p>
                            <p class="text-gray-700">{{ $education->institution ?? 'N/A' }}</p>
                        </div>
                    </div>
                </section>
               @php
                    $totalReviews = $mentorDetails->reviews->count();
                    $avgRating = $totalReviews > 0 ? round($mentorDetails->reviews->avg('ratings'), 1) : 0;

                    $ratingsPercent = [];
                    foreach ([5, 4, 3, 2, 1] as $star) {
                        $count = $mentorDetails->reviews->where('ratings', $star)->count();
                        $ratingsPercent[$star] = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
                    }
                @endphp
                    
                <!-- Reviews Section -->
                <section id="reviews">
                    <h2 class="text-lg font-semibold mb-3">Reviews</h2>
                    <div class="flex items-center mb-4">
                    <div class="text-4xl font-bold mr-4">{{ $avgRating }}</div>
                    <div class="flex-1 space-y-1">
                        @foreach([5, 4, 3, 2, 1] as $star)
                            <div class="flex items-center space-x-2">
                                <!-- <span class="w-8 text-sm text-gray-700">{{ $star }}★</span> -->
                                <div class="w-48 bg-gray-200 h-2 rounded">
                                    <div class="bg-orange-500 h-2 rounded" style="width: {{ $ratingsPercent[$star] }}%; min-width: 2px;"></div>
                                </div>
                                <span class="text-sm text-gray-500">{{ $ratingsPercent[$star] }}%</span>
                            </div>
                        @endforeach
                    </div>


                    </div>
                    

                    <!-- Hidden mentor ID -->
                    <input type="hidden" id="mentor_id" value="{{ $mentorDetails->id }}">

                    <!-- Write Review Box -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Write a review:</h3>
                        
                        <textarea id="review-text" rows="4" placeholder="Write here . . ." 
                            class="w-full border border-gray-300 p-3 rounded mb-2 text-sm"></textarea>

                        <div class="flex items-center justify-between">
                            <div id="star-rating" class="text-orange-500 text-2xl cursor-pointer space-x-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="star" data-value="{{ $i }}">☆</span>
                                @endfor
                            </div>

                            <button id="submit-review" 
                                class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-2 rounded text-sm">
                                Submit Review
                            </button>
                        </div>
                    </div>

                    <!-- Review Output -->
                    <div id="review-list" class="space-y-4 mt-4"></div>

                    <!-- JavaScript -->
                    <script>
                        let selectedRating = 0;

                        // Handle star click
                        document.querySelectorAll('.star').forEach(star => {
                            star.addEventListener('click', function () {
                                selectedRating = parseInt(this.dataset.value);
                                highlightStars(selectedRating);
                            });
                        });

                        function highlightStars(count) {
                            document.querySelectorAll('.star').forEach((star, index) => {
                                star.textContent = index < count ? '★' : '☆';
                            });
                        }

                        document.getElementById('submit-review').addEventListener('click', function () {
                            const reviewText = document.getElementById('review-text').value.trim();
                            const mentorId = document.getElementById('mentor_id').value;

                            if (!reviewText || selectedRating === 0) {
                                alert('Please write a review and select a rating.');
                                return;
                            }

                            fetch("{{ route('submit.mentor.review') }}", {
                                method: "POST",
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    mentor_id: mentorId,
                                    review: reviewText,
                                    rating: selectedRating
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('review-text').value = '';
                                    selectedRating = 0;
                                    highlightStars(0);

                                    const newReview = `
                                        <div class="border p-4 rounded shadow-sm bg-white">
                                            <p class="text-sm font-semibold">${data.review.jobseeker_name}</p>
                                            <p class="text-yellow-400 text-sm">
                                                ${'★'.repeat(data.review.ratings)}${'☆'.repeat(5 - data.review.ratings)}
                                            </p>
                                            <p class="text-sm text-gray-700">${data.review.reviews}</p>
                                        </div>
                                    `;
                                    document.getElementById('review-list').insertAdjacentHTML('afterbegin', newReview);
                                } else {
                                    alert(data.error || 'Review submission failed.');
                                }
                            })
                            .catch(() => alert('Something went wrong.'));
                        });
                    </script>


                    <!-- Review Cards -->
                    <div class="space-y-4 mt-3" id="review-list">
                    @foreach ($reviews as $review)
                        <div class="border p-4 rounded shadow-sm bg-white">
                        <p class="text-sm font-semibold">{{ $review->jobseeker_name }}</p>
                        <p class="text-yellow-400 text-sm">
                            @for ($i = 1; $i <= 5; $i++)
                            {{ $i <= $review->ratings ? '★' : '☆' }}
                            @endfor
                        </p>
                        <p class="text-sm text-gray-700">{{ $review->reviews }}</p>
                        </div>
                    @endforeach
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