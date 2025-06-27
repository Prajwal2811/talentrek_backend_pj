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

          <main class="w-11/12 mx-auto py-8">
            <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">
              <!-- Left/Main Content -->
              <div class="flex-1">
                <!-- Title -->
                <h1 class="text-2xl font-semibold text-gray-900 mb-1">Graphic design - Advance level</h1>
                <p class="text-sm text-gray-600 mb-3">
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                </p>

                <!-- Ratings and Meta -->
                <div class="flex items-center text-sm text-gray-600 mb-6 flex-wrap gap-2">
                  <div class="flex items-center text-yellow-500">★★★★☆</div>
                  <span>(4/5)</span>
                  <span>Rating</span>
                  <span class="mx-2">|</span>
                  <div class="flex items-center gap-1">
                    <img src="{{ asset('asset/images/avatar.png') }}" alt="Instructor" class="w-6 h-6 rounded-full object-cover">
                    <span class="text-gray-800 font-medium">Julia Maccarthy</span>
                  </div>
                  <span class="mx-2">|</span>
                  <span>📘 8 lessons</span>
                  <span>⏱️ 20hrs</span>
                  <span>🏷️ Advance</span>
                </div>

              <!-- Tabs -->
              <div class="flex gap-6 border-b mb-6 text-sm font-medium">
                <button class="tab-link pb-2 text-blue-600 border-b-2 border-blue-600 active-tab" data-tab="overview">Course overview</button>
                <button class="tab-link pb-2 text-gray-600 hover:text-blue-600 border-b-2 border-transparent" data-tab="benefits">Benefits of training</button>
                <button class="tab-link pb-2 text-gray-600 hover:text-blue-600 border-b-2 border-transparent" data-tab="content">Training content</button>
                <button class="tab-link pb-2 text-gray-600 hover:text-blue-600 border-b-2 border-transparent" data-tab="reviews">Reviews</button>
              </div>

              <!-- Overview -->
              <section class="mb-6 tab-content" data-tab-content="overview">
                <h2 class="text-lg font-semibold mb-2">Course overview</h2>
                <p class="text-sm text-gray-700">Unlock your creative potential... (overview text)</p>
              </section>

              <!-- Benefits -->
              <section class="mb-6 tab-content" data-tab-content="benefits">
                <h2 class="text-lg font-semibold mb-2">Benefits of training</h2>
                <ul class="list-disc pl-5 space-y-1 text-sm text-gray-700">
                  <li>Enhance Creativity...</li>
                  <li>Master Industry Tools...</li>
                  <li>Career Opportunities...</li>
                  <li>Effective & Hands-on Growth...</li>
                  <li>High Relevance & Confidence...</li>
                  <li>Build a Strong Portfolio...</li>
                </ul>
              </section>

              <!-- Training Content -->
              <section class="mb-6 tab-content" data-tab-content="content">
                <h2 class="text-lg font-semibold mb-2">Training content</h2>
                <ul class="list-disc pl-5 space-y-1 text-sm text-gray-700">
                  <li><strong>Module 1:</strong> Introduction to Graphic Design</li>
                  <li><strong>Module 2:</strong> Tools & Techniques</li>
                  <li><strong>Module 3:</strong> Typography and Color Theory</li>
                  <li><strong>Module 4:</strong> componants and Composition</li>
                  <li><strong>Module 5:</strong> Real-world Projects & Portfolio</li>
                </ul>
              </section>

              <!-- Reviews -->
              <!-- Reviews -->
               <section class="tab-content" data-tab-content="reviews">
                  <h2 class="text-lg font-semibold mb-3">Reviews</h2>
                  <div class="flex items-center mb-4">
                    <div class="text-4xl font-bold mr-4">4.8</div>
                    <div class="flex-1 space-y-1">
                      <div class="flex items-center space-x-2">
                        <div class="w-48 bg-gray-200 h-2 rounded">
                          <div class="bg-orange-500 h-2 rounded w-[72%]"></div>
                        </div>
                        <span class="text-sm text-gray-500">72%</span>
                      </div>
                      <div class="flex items-center space-x-2">
                        <div class="w-48 bg-gray-200 h-2 rounded">
                          <div class="bg-orange-500 h-2 rounded w-[19%]"></div>
                        </div>
                        <span class="text-sm text-gray-500">19%</span>
                      </div>
                      <div class="flex items-center space-x-2">
                        <div class="w-48 bg-gray-200 h-2 rounded">
                          <div class="bg-orange-500 h-2 rounded w-[7%]"></div>
                        </div>
                        <span class="text-sm text-gray-500">7%</span>
                      </div>
                      <div class="flex items-center space-x-2">
                        <div class="w-48 bg-gray-200 h-2 rounded">
                          <div class="bg-orange-500 h-2 rounded w-[2%]"></div>
                        </div>
                        <span class="text-sm text-gray-500">2%</span>
                      </div>
                    </div>
                  </div>

                  <!-- Write Review -->
                  <textarea rows="4" placeholder="Write a review..." class="w-full border border-gray-300 p-2 rounded mb-2 text-sm"></textarea>
                  <div class="flex items-center justify-between mb-6">
                    <div class="text-yellow-400 text-lg">★★★★★</div>
                    <button class="bg-blue-600 text-white px-4 py-1 rounded text-sm">Submit Review</button>
                  </div>

                  <!-- Review Cards -->
                  <div class="space-y-4">
                    <div class="border p-4 rounded shadow-sm">
                      <p class="text-sm font-semibold">Jane Smith</p>
                      <p class="text-yellow-400 text-sm">★★★★★</p>
                      <p class="text-sm text-gray-700">Excellent course. Really helped me understand design basics.</p>
                    </div>
                    <div class="border p-4 rounded shadow-sm">
                      <p class="text-sm font-semibold">Robert Chan</p>
                      <p class="text-yellow-400 text-sm">★★★★☆</p>
                      <p class="text-sm text-gray-700">Great structure, some parts were a bit fast-paced.</p>
                    </div>
                  </div>
                </section>
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
              <aside class="w-full lg:w-1/3">
                <div class="bg-white rounded-xl shadow-md overflow-hidden border p-4">
                  <!-- Course Image -->
                  <img src="{{ asset('asset/images/gallery/pic-4.png') }}" alt="Course Thumbnail" class="rounded-lg w-full h-48 object-cover mb-4">

                  <!-- Course Details -->
                  <ul class="text-sm text-gray-600 mb-4 space-y-2">
                    <li class="flex items-center space-x-2">
                      <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 20h9"></path><path d="M12 4H3"></path><path d="M12 4v16"></path></svg>
                      <span>6 lessons</span>
                    </li>
                    <li class="flex items-center space-x-2">
                      <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 6v6l4 2"></path><circle cx="12" cy="12" r="10"></circle></svg>
                      <span>20hrs</span>
                    </li>
                    <li class="flex items-center space-x-2">
                      <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h18v4H3zM3 17h18v4H3zM3 10h18v4H3z"></path></svg>
                      <span>Advance</span>
                    </li>
                  </ul>
                  <!-- Price -->
                  <div class="mb-4 flex items-center justify-between">
                    <p class="text-sm text-gray-500 font-bold">Price</p>
                    <div class="flex items-center space-x-2">
                      <span class="text-gray-400 line-through">SAR 89</span>
                      <span class="text-xl font-bold text-black-400">SAR 89</span>
                    </div>
                  </div>
                  <!-- Buttons -->
                   <a href="{{ route('buy-course') }}">
                      <button class="bg-blue-600 hover:bg-blue-700 text-white w-full py-2 rounded mb-2 font-medium">
                        Buy course
                      </button>
                    </a>
                    <a href="{{ route('buy-course-for-team') }}">

                      <button class="bg-blue-600 hover:bg-blue-700 text-white w-full py-2 rounded mb-2 font-medium">
                        Buy for team
                      </button>
                    </a>
                    <button class="border border-blue-600 text-blue-600 hover:bg-blue-50 w-full py-2 rounded font-medium">
                      Add to cart
                    </button>
                </div>
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