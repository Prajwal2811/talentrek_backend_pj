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
            <div class="relative bg-center bg-cover h-[400px] flex items-center" style="background-image: url('{{ asset('asset/images/banner/Assessment.png') }}');">
                <div class="absolute inset-0 bg-white bg-opacity-10"></div>
                <div class="relative z-10 container mx-auto px-4">
                    <div class="space-y-2">
                        <h2 class="text-5xl font-bold text-white ml-[10%]">Assessment</h2>
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
                    <img src="{{ asset('asset/images/client-logo/w2.png') }}" alt="Mentor" class="w-28 h-28 rounded-full object-cover border" />
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">Mohammad Raza</h1>
                        <p class="text-sm text-gray-600 mt-1">Web Designer</p>
                        <p class="text-sm text-gray-500 mt-0.5">5+ years experience</p>
                        <div class="flex items-center mt-1 text-sm">
                            <div class="flex text-[#f59e0b]">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><polygon points="9.9,1.1 7.6,6.9 1.4,7.6 6.1,11.8 4.8,18 9.9,14.9 15,18 13.7,11.8 18.4,7.6 12.2,6.9 "/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><polygon points="9.9,1.1 7.6,6.9 1.4,7.6 6.1,11.8 4.8,18 9.9,14.9 15,18 13.7,11.8 18.4,7.6 12.2,6.9 "/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><polygon points="9.9,1.1 7.6,6.9 1.4,7.6 6.1,11.8 4.8,18 9.9,14.9 15,18 13.7,11.8 18.4,7.6 12.2,6.9 "/></svg>
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><polygon points="9.9,1.1 7.6,6.9 1.4,7.6 6.1,11.8 4.8,18 9.9,14.9 15,18 13.7,11.8 18.4,7.6 12.2,6.9 "/></svg>
                            <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20"><polygon points="9.9,1.1 7.6,6.9 1.4,7.6 6.1,11.8 4.8,18 9.9,14.9 15,18 13.7,11.8 18.4,7.6 12.2,6.9 "/></svg>
                            </div>
                            <span class="ml-2 text-gray-600">(4.8/5) <span class="text-xs">Rating</span></span>
                        </div>
                        <p class="text-sm text-gray-800 font-semibold mt-1">
                        SAR 89 <span class="text-xs text-gray-500">per mentorship session</span>
                        </p>
                    </div>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('assessment-book-session') }}">
                            <button class="bg-blue-800 hover:bg-blue-900 text-white px-6 py-2 rounded-md font-medium shadow-sm transition">Book Session</button>
                        </a>
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
                        Hi, I’m Mohammad Raza — a passionate and experienced web design mentor with over 5 years of industry experience. My journey in the world of design began with a strong academic foundation at the XYZ Saudi Institute of Engineering and Technology, where I honed my technical skills and developed a deep interest in user-centric design. Over the years, I’ve worked on numerous projects across a wide range of industries, from startups to enterprise clients, helping businesses craft visually appealing, responsive, and accessible websites that align with their brand identity and user needs.

                        <br><br>

                        Beyond hands-on project work, my true calling lies in mentoring aspiring designers and professionals who are looking to build or enhance their careers in web design. I’ve conducted dozens of one-on-one mentorship sessions, group workshops, and online classes focused on HTML, CSS, UX/UI principles, design tools like Figma and Adobe XD, and real-world project workflows. My teaching philosophy centers around making learning practical, engaging, and tailored to each individual’s learning style.

                        <br><br>

                        Whether you’re a complete beginner or someone looking to sharpen specific skills, my sessions are designed to be interactive, feedback-driven, and filled with real project examples. I strongly believe that design is not just about aesthetics — it’s about solving problems creatively and empathetically. My goal is to not only help you master technical skills but also develop the confidence to think like a designer and make decisions that impact user experience positively.

                        <br><br>

                        I’m here to support, guide, and challenge you throughout your learning journey. Let’s work together to unlock your potential and turn your passion for design into a successful career. Looking forward to mentoring you on Talentrek!
                    </p>


                    <h2 class="text-lg font-semibold mb-2">Experience</h2>
                    <div class="mb-6">
                    <p class="text-sm font-semibold text-gray-800">Work experience:</p>
                    <p class="text-sm text-gray-700">5 years</p>
                    </div>

                    <h2 class="text-lg font-semibold mb-2">Qualification</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <p class="font-semibold text-gray-800">Highest qualification</p>
                        <p class="text-gray-700">Bachelor of engineering</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Field of study:</p>
                        <p class="text-gray-700">Electrical engineering</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Institution name:</p>
                        <p class="text-gray-700">XYZ Saudi institute of<br />engineering and technology</p>
                    </div>
                    </div>
                </section>

                <!-- Reviews Section -->
                <section id="reviews">
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
                </div>
            </div>
            </main>

          
          <style>
            .active-tab {
              border-bottom-color: #2563eb; /* Tailwind blue-600 */
              color: #2563eb;
            }
          </style>

@include('site.componants.footer')