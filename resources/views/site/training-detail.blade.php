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

             <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
              @if(session('success'))
                  <script>
                      document.addEventListener("DOMContentLoaded", function () {
                          Swal.fire({
                              icon: 'success',
                              title: '{{ session('success') }}',
                              text: 'Go to your profile to continue learning.',
                              confirmButtonColor: '#3085d6',
                              confirmButtonText: 'Go to Profile'
                          }).then((result) => {
                              if (result.isConfirmed) {
                                  window.location.href = '{{ route('jobseeker.profile') }}';
                              }
                          });
                      });
                  </script>
              @endif

          

          <main class="w-11/12 mx-auto py-8">
            @include('admin.errors')
            <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">
              <!-- Left/Main Content -->
              <div class="flex-1">
                <!-- Title -->
                <!-- Title -->
                <h1 class="text-2xl font-semibold text-gray-900 mb-1">
                    {{ $material->training_title ?? 'N/A' }}
                </h1>

                <p class="text-sm text-gray-600 mb-3">
                    {{ $material->training_sub_title ?? '' }}
                </p>

                <!-- Ratings and Meta -->
                <div class="flex items-center text-sm text-gray-600 mb-6 flex-wrap gap-2">
                    <div class="flex items-center text-yellow-500">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($average))
                                ★
                            @elseif ($i - $average < 1)
                                ☆
                            @else
                                ☆
                            @endif
                        @endfor
                    </div>
                    <span>({{ $average }}/5)</span>

                    <span>Rating</span>
                    <span class="mx-2">|</span>

                    <!-- Trainer -->
                    <div class="flex items-center gap-2">
                        <img src="{{ $material->user_profile }}" alt="Trainer Image" class="w-10 h-10 rounded-full object-cover">

                        <span class="font-semibold">{{ $material->user_name }}</span>
                    </div>

                    <span class="mx-2">|</span>
                    @if(strtolower($material->training_type) === 'recorded' && isset($material->documents) && count($material->documents) > 0)
                        <span>📘 {{ count($material->documents) }} lessons</span>
                    @endif

                    <span>⏱️ 
                        @php
                            $totalHours = 0;
                            foreach ($material->batches as $batch) {
                                $start = strtotime($batch->start_timing);
                                $end = strtotime($batch->end_timing);
                                $totalHours += ($end - $start) / 3600;
                            }
                        @endphp
                        {{ $totalHours }} hrs
                    </span>

                    <span>🏷️ {{ ucfirst($material->training_level ?? 'Beginner') }}</span>
                </div>

                <!-- Tabs -->
                <div class="flex gap-6 border-b mb-6 text-sm font-medium">
                    <!-- <button class="tab-link pb-2 text-blue-600 border-b-2 border-blue-600 active-tab" data-tab="overview">Course overview</button>
                    <button class="tab-link pb-2 text-gray-600 hover:text-blue-600 border-b-2 border-transparent" data-tab="benefits">Benefits of training</button> -->
                    <button class="tab-link pb-2 text-gray-600 hover:text-blue-600 border-b-2 border-transparent" data-tab="content">Training content</button>
                    <button class="tab-link pb-2 text-gray-600 hover:text-blue-600 border-b-2 border-transparent" data-tab="reviews">Reviews</button>
                </div>
                <!-- < ?PHP dd($material);exit;?>               -->
                <!-- Overview -->
                <section class="mb-6 tab-content" data-tab-content="overview">
                    <h2 class="text-lg font-semibold mb-2">Course overview</h2>
                    <p class="text-sm text-gray-700">
                        {{ $material->training_descriptions ?? 'Overview not available.' }}
                    </p>
                </section>

                <!-- Benefits -->
                <section class="mb-6 tab-content hidden" data-tab-content="benefits">
                    <h2 class="text-lg font-semibold mb-2">Benefits of training</h2>
                    <ul class="list-disc pl-5 space-y-1 text-sm text-gray-700">
                        @php
                            $benefits = explode("\n", $material->training_benefits ?? '');
                        @endphp
                        @foreach($benefits as $benefit)
                            @if(trim($benefit) != '')
                                <li>{{ $benefit }}</li>
                            @endif
                        @endforeach
                    </ul>
                </section>

                <!-- Training Content -->
                <section class="mb-6 tab-content hidden" data-tab-content="content">
                    <h2 class="text-lg font-semibold mb-2">Training content</h2>
                    <ul class="list-disc pl-5 space-y-1 text-sm text-gray-700">
                        @foreach($material->documents as $index => $doc)
                            <li><strong>Lesson {{ $index + 1 }}:</strong> {{ $doc->description }}</li>
                        @endforeach
                    </ul>
                </section>

                <!-- Reviews -->
                <section class="mb-6 tab-content hidden" data-tab-content="reviews">
                    <h2 class="text-lg font-semibold mb-2">Reviews</h2>
                    <p class="text-sm text-gray-600">User reviews will appear here.</p>
                </section>

                <!-- JS for tab switching (Alpine.js or Vanilla JS) -->
                <script>
                    document.querySelectorAll('.tab-link').forEach(button => {
                        button.addEventListener('click', () => {
                            const tab = button.dataset.tab;

                            document.querySelectorAll('.tab-link').forEach(btn => {
                                btn.classList.remove('text-blue-600', 'border-blue-600', 'active-tab');
                                btn.classList.add('text-gray-600');
                            });

                            button.classList.remove('text-gray-600');
                            button.classList.add('text-blue-600', 'border-blue-600', 'active-tab');

                            document.querySelectorAll('.tab-content').forEach(content => {
                                content.classList.add('hidden');
                            });

                            document.querySelector(`[data-tab-content="${tab}"]`).classList.remove('hidden');
                        });
                    });
                </script>


              <!-- Reviews -->
              <section class="tab-content" data-tab-content="reviews">
                <h2 class="text-2xl font-bold mb-6">Reviews</h2>

                <!-- Rating Summary -->
                <div class="flex mb-8 space-x-8">
                  <!-- Average Rating Box -->
                  <div class="bg-gray-100 p-6 rounded-md w-48 text-center">
                    <div class="text-5xl font-extrabold text-black-500 leading-tight">{{ $average }}</div>
                     <div class="text-xl text-black-300 text-gray-600 mt-1">Overall rating</div>
                  </div>

                  <!-- Star Rating Breakdown (no 5/4/3/2/1 text) -->
                  <div class="flex-1 bg-blue-50 p-6 rounded-md space-y-3">
                    @foreach([5, 4, 3, 2, 1] as $star)
                      <div class="flex items-center space-x-4">
                        <div class="w-full bg-blue-100 h-2 rounded relative">
                          <div class="absolute top-0 left-0 h-2 bg-orange-500 rounded" style="width: {{ $ratingsPercent[$star] }}%;"></div>
                        </div>
                        <div class="w-12 text-right text-sm text-gray-600">{{ $ratingsPercent[$star] }}%</div>
                      </div>
                    @endforeach
                  </div>
                </div>

                <!-- Write Review Box -->
                <div class="mb-6">
                  <h3 class="text-lg font-semibold mb-2">Write a review:</h3>
                  <textarea id="review-text" rows="4" placeholder="Write here . . ." class="w-full border border-gray-300 p-3 rounded mb-2 text-sm"></textarea>

                  <div class="flex items-center justify-between">
                    <div id="star-rating" class="text-orange-500 text-2xl cursor-pointer space-x-1">
                      @for ($i = 1; $i <= 5; $i++)
                        <span class="star" data-value="{{ $i }}">☆</span>
                      @endfor
                    </div>

                    <button id="submit-review" class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-2 rounded text-sm">
                      Submit Review
                    </button>
                  </div>
                </div>

                <!-- Review List -->
                <div class="space-y-4" id="review-list">
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

              <!-- JavaScript (Inline or External) -->
              <script>
              let selectedRating = 0;

              // Star click handler
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

              // Submit review via AJAX
              const submitButton = document.getElementById('submit-review');
              submitButton.addEventListener('click', function () {
                const reviewText = document.getElementById('review-text').value;
                if (!reviewText || selectedRating === 0) {
                  alert('Please write a review and select a rating.');
                  return;
                }

                fetch("{{ route('submit.review') }}", {
                  method: "POST",
                  headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                  },
                  body: JSON.stringify({
                    user_type: '{{ $userType }}',     // dynamically passed from controller
                    user_id: '{{ $userId }}',         // trainer/mentor/etc ID
                    material_id: '{{ $material->id ?? '' }}',
                    reviews: reviewText,
                    ratings: selectedRating
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
                  }
                });
              });
              </script>
      

              <script>
                document.querySelectorAll('.tab-link').forEach(button => {
                  button.addEventListener('click', () => {
                    // Remove active styles from all tabs
                    document.querySelectorAll('.tab-link').forEach(btn => {
                      btn.classList.remove('text-blue-600', 'border-blue-600', 'active-tab');
                      btn.classList.add('text-gray-600', 'border-transparent');
                    });

                    // Add active style to clicked tab
                    button.classList.add('text-blue-600', 'border-blue-600', 'active-tab');
                    button.classList.remove('text-gray-600');

                    // Scroll to target section
                    const tab = button.getAttribute('data-tab');
                    const targetSection = document.querySelector(`[data-tab-content="${tab}"]`);
                    if (targetSection) {
                      targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                  });
                });
              </script>

              </div>

              <!-- Sidebar -->
              <aside class="w-full lg:w-1/3 lg:sticky top-12 self-start">
                <div class="bg-white rounded-xl shadow-md overflow-hidden border p-4">
                  <!-- Course Image -->
                  <img src="{{ asset($material->thumbnail_file_path ?? 'asset/images/gallery/pic-4.png') }}"
                      alt="Course Thumbnail"
                      class="rounded-lg w-full h-48 object-cover mb-4">

                  <!-- Course Details -->
                  <ul class="text-sm text-gray-600 mb-4 space-y-2">
                      <li class="flex items-center space-x-2">
                          @if(strtolower($material->training_type) === 'recorded' && isset($material->documents) && count($material->documents) > 0)
                              <span>📘 {{ count($material->documents) }} lessons</span>
                          @endif
                      </li>

                      @if(strtolower($material->training_type) === 'online')
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 6v6l4 2"></path><circle cx="12" cy="12" r="10"></circle>
                            </svg>
                            <span>
                                @php
                                    $totalHours = 0;
                                    foreach ($material->batches as $batch) {
                                        $start = strtotime($batch->start_timing);
                                        $end = strtotime($batch->end_timing);
                                        $totalHours += ($end - $start) / 3600;
                                    }
                                @endphp
                                {{ number_format($totalHours, 1) }} hrs
                            </span>
                        </li>
                      @endif

                      <li class="flex items-center space-x-2">
                          <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                              <path d="M3 3h18v4H3zM3 17h18v4H3zM3 10h18v4H3z"></path>
                          </svg>
                          <span>{{ ucfirst($material->training_level ?? 'Beginner') }}</span>
                      </li>
                  </ul>

                  <!-- Price -->
                  <div class="mb-4 flex items-center justify-between">
                      <p class="text-sm text-gray-500 font-bold">Price</p>
                      <div class="flex items-center space-x-2">
                          <span class="text-gray-400 line-through">
                              SAR {{ number_format($material->training_price ?? 0, 0) }}
                          </span>
                          <span class="text-xl font-bold text-black">
                              SAR {{ number_format($material->training_offer_price ?? 0, 0) }}
                          </span>
                      </div>
                  </div>
                </div>

                @php
                    $existOrNot = false;
                    if (auth('jobseeker')->check()) {
                        $existOrNot = App\Models\JobseekerTrainingMaterialPurchase::where('jobseeker_id', auth('jobseeker')->id())
                            ->where('material_id', $material->id)
                            ->exists();
                    }
                @endphp

                @if (auth('jobseeker')->check())
                    @if (!$existOrNot)
                        <!-- Not purchased yet -->
                        <a href="{{ route('buy-course', ['id' => $material->id]) }}">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white w-full py-2 rounded mb-2 font-medium mt-3">
                                Buy course
                            </button>
                        </a>
                        <a href="{{ route('buy-course-for-team') }}">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white w-full py-2 rounded mb-2 font-medium">
                                Buy for team
                            </button>
                        </a>
                        <form action="{{ route('jobseeker.addtocart', ['id' => $material->id]) }}" method="POST">
                          @csrf
                          <button type="submit" class="border border-blue-600 text-blue-600 hover:bg-blue-50 w-full py-2 rounded font-medium">
                              Add to cart
                          </button>
                      </form>
                    @else
                        <!-- Already purchased -->
                        <a href="{{ route('jobseeker.profile') }}">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white w-full py-2 rounded mb-2 font-medium mt-3">
                                Go to course
                            </button>
                        </a>
                    @endif
                @else
                    <!-- Not purchased yet -->
                    <a href="{{ route('buy-course', ['id' => $material->id]) }}">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white w-full py-2 rounded mb-2 font-medium mt-3">
                            Buy course
                        </button>
                    </a>
                    <a href="{{ route('buy-course-for-team') }}">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white w-full py-2 rounded mb-2 font-medium">
                            Buy for team
                        </button>
                    </a>
                    <form action="{{ route('jobseeker.addtocart', ['id' => $material->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="border border-blue-600 text-blue-600 hover:bg-blue-50 w-full py-2 rounded font-medium">
                            Add to cart
                        </button>
                    </form>
                @endif

              </aside>


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