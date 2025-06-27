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
            <div class="relative bg-cover bg-no-repeat bg-center min-h-[750px]" style="background-image: url('{{ asset('asset/images/banner/Banner.png') }}');">
                <div class="container mx-auto px-6 md:px-12 py-64 flex items-center">
                    <div class="w-full md:w-1/2 text-white space-y-6">
                    <h1 class="text-3xl md:text-5xl font-bold leading-tight text-white">
                        Your Journey to <br />
                        <span class="text-white">Grow & Succeed Starts Here</span>
                    </h1>
                    <p class="text-base text-gray-100 max-w-md">
                        Earn certificates and gain new skills with trusted educators and industry leaders—anytime, anywhere.
                    </p>
                    <button class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-2 rounded text-sm">
                        Sign In / Sign Up
                    </button>
                    </div>
                </div>
                <!-- Curved bottom image -->
                <div class="absolute bottom-0 left-0 w-full z-10 translate-y-[15px]">
                    <img src="{{ asset('asset/images/banner/curve-bottom.png') }}" alt="Curved Bottom" class="w-full h-auto" />
                </div>
            </div>

          
       
            <!-- Training Programs Section -->
            <section class="py-16 bg-white relative">
                <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-black">Training Programs</h2>
                <p class="text-gray-500 mt-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>

                <div class="relative max-w-[1300px] mx-auto px-4">
                <!-- Left Arrow (absolute left outside) -->
                <div class="training-prev absolute -left-6 top-1/2 transform -translate-y-1/2 z-10">
                    <button class="bg-gray-200 hover:bg-gray-300 p-3 rounded-full shadow">
                    <img src="https://img.icons8.com/ios-filled/24/000000/chevron-left.png" alt="Prev"/>
                    </button>
                </div>

                <!-- Right Arrow (absolute right outside) -->
                <div class="training-next absolute -right-6 top-1/2 transform -translate-y-1/2 z-10">
                    <button class="bg-gray-200 hover:bg-gray-300 p-3 rounded-full shadow">
                    <img src="https://img.icons8.com/ios-filled/24/000000/chevron-right.png" alt="Next"/>
                    </button>
                </div>

                <!-- Swiper Slider -->
                <div class="swiper trainingSwiper">
                    <div class="swiper-wrapper">
                        <!-- Slide 1 -->
                        <div class="swiper-slide">
                            <div class="bg-blue-50 rounded-lg text-center p-6 space-y-4 h-full flex flex-col justify-between">
                            <div>
                                <div class="w-16 h-16 bg-white mx-auto rounded-full flex items-center justify-center shadow mb-4">
                                <img src="https://img.icons8.com/ios/50/money.png" class="w-6 h-6" />
                                </div>
                                <h4 class="font-semibold text-lg mb-1">Finance Accounting</h4>
                                <p class="text-sm text-gray-600">Learn financial reporting, tax management, and auditing skills.</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">20+ Training programs</p>
                            </div>
                        </div>

                        <!-- Slide 2 -->
                        <div class="swiper-slide">
                            <div class="bg-blue-50 rounded-lg text-center p-6 space-y-4 h-full flex flex-col justify-between">
                            <div>
                                <div class="w-16 h-16 bg-white mx-auto rounded-full flex items-center justify-center shadow mb-4">
                                <img src="https://img.icons8.com/ios/50/meditation-guru.png" class="w-6 h-6" />
                                </div>
                                <h4 class="font-semibold text-lg mb-1">Personal Development</h4>
                                <p class="text-sm text-gray-600">Improve communication, confidence, leadership, and emotional intelligence.</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">20+ Training programs</p>
                            </div>
                        </div>

                        <!-- Slide 3 -->
                        <div class="swiper-slide">
                            <div class="bg-blue-50 rounded-lg text-center p-6 space-y-4 h-full flex flex-col justify-between">
                            <div>
                                <div class="w-16 h-16 bg-white mx-auto rounded-full flex items-center justify-center shadow mb-4">
                                <img src="https://img.icons8.com/ios/50/design.png" class="w-6 h-6" />
                                </div>
                                <h4 class="font-semibold text-lg mb-1">Design</h4>
                                <p class="text-sm text-gray-600">Explore UI/UX design, graphic design, and branding essentials.</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">20+ Training programs</p>
                            </div>
                        </div>

                        <!-- Slide 4 -->
                        <div class="swiper-slide">
                            <div class="bg-blue-50 rounded-lg text-center p-6 space-y-4 h-full flex flex-col justify-between">
                            <div>
                                <div class="w-16 h-16 bg-white mx-auto rounded-full flex items-center justify-center shadow mb-4">
                                <img src="https://img.icons8.com/ios/50/megaphone.png" class="w-6 h-6" />
                                </div>
                                <h4 class="font-semibold text-lg mb-1">Sales & Marketing</h4>
                                <p class="text-sm text-gray-600">Learn digital marketing, customer engagement, and sales strategies.</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">20+ Training programs</p>
                            </div>
                        </div>

                        <!-- Slide 5 -->
                        <div class="swiper-slide">
                            <div class="bg-blue-50 rounded-lg text-center p-6 space-y-4 h-full flex flex-col justify-between">
                            <div>
                                <div class="w-16 h-16 bg-white mx-auto rounded-full flex items-center justify-center shadow mb-4">
                                <img src="https://img.icons8.com/ios/50/code.png" class="w-6 h-6" />
                                </div>
                                <h4 class="font-semibold text-lg mb-1">Advanced Coding</h4>
                                <p class="text-sm text-gray-600">Master Python, JavaScript, APIs, backend systems and data structures.</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">30+ Training programs</p>
                            </div>
                        </div>
                    </div>

                </div>
                </div>
            </section>

    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <!-- Swiper Initialization -->
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        new Swiper(".trainingSwiper", {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        navigation: {
            nextEl: ".training-next",
            prevEl: ".training-prev",
        },
        breakpoints: {
            640: {
            slidesPerView: 2,
            },
            768: {
            slidesPerView: 3,
            },
            1024: {
            slidesPerView: 4,
            },
            1280: {
            slidesPerView: 6, // ✅ Show 6 items on large screens
            },
        },
        });
    });
    </script>


            <section class="py-16">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold">Trending Courses</h2>
                        <p class="text-gray-500">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                    <div id="courseContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    </div>
                    <div class="mt-10 text-center">
                    <button id="viewAllBtn" class="px-6 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-md transition">
                        View All Courses
                    </button>
                    </div>
                </div>
            </section>
            <script>
                const courses = [
                    {
                        title: "Learn FIGMA - UIUX Design essentials",
                        image: "{{ asset('asset/images/gallery/pic-2.png') }}",
                        lessons: 6,
                        hours: "30hrs",
                        level: "Beginner",
                        instructor: "Abhishek S M"
                    },
                    {
                        title: "Python course",
                        image: "{{ asset('asset/images/gallery/pic-1.png') }}",
                        lessons: 8,
                        hours: "32hrs",
                        level: "Beginner",
                        instructor: "Matthew"
                    },
                    {
                        title: "SharePoint development",
                        image: "{{ asset('asset/images/gallery/pic-3.png') }}",
                        lessons: 6,
                        hours: "24hrs",
                        level: "Beginner",
                        instructor: "James Cameron"
                    },
                    {
                        title: "Graphic design - Advance level",
                        image: "{{ asset('asset/images/gallery/pic-4.png') }}",
                        lessons: 10,
                        hours: "20hrs",
                        level: "Advance",
                        instructor: "Julia Maccarthy"
                    },
                    {
                        title: "React.js for Beginners",
                        image: "{{ asset('asset/images/gallery/pic-1.png') }}",
                        lessons: 7,
                        hours: "25hrs",
                        level: "Beginner",
                        instructor: "Sara Lee"
                    },
                    {
                        title: "Advanced JavaScript",
                        image: "{{ asset('asset/images/gallery/pic-3.png') }}",
                        lessons: 9,
                        hours: "28hrs",
                        level: "Advance",
                        instructor: "David Wills"
                    },
                    {
                        title: "Mastering Node.js",
                        image: "{{ asset('asset/images/gallery/pic-2.png') }}",
                        lessons: 12,
                        hours: "35hrs",
                        level: "Intermediate",
                        instructor: "Ravi Kumar"
                    },
                    {
                        title: "Data Science with Python",
                        image: "{{ asset('asset/images/gallery/pic-4.png') }}",
                        lessons: 15,
                        hours: "40hrs",
                        level: "Intermediate",
                        instructor: "Dr. Anita Raj"
                    },
                    {
                        title: "Fullstack Web Development",
                        image: "{{ asset('asset/images/gallery/pic-1.png') }}",
                        lessons: 20,
                        hours: "60hrs",
                        level: "Advance",
                        instructor: "Mark Benson"
                    },
                    {
                        title: "Digital Marketing 101",
                        image: "{{ asset('asset/images/gallery/pic-3.png') }}",
                        lessons: 10,
                        hours: "18hrs",
                        level: "Beginner",
                        instructor: "Priya Sharma"
                    }
                ];



                const courseContainer = document.getElementById('courseContainer');
                const viewAllBtn = document.getElementById('viewAllBtn');

                const coursesPerRow = 4;
                let shownCourses = 0;

                function renderCourses() {
                    const end = Math.min(shownCourses + coursesPerRow, courses.length);
                    for (let i = shownCourses; i < end; i++) {
                        const c = courses[i];
                        const courseHTML = `
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition hover:-translate-y-1">
                                <img src="${c.image}" alt="${c.title}" class="w-full h-40 object-cover">
                                <div class="p-4">
                                    <h6 class="text-base font-semibold mb-2">${c.title}</h6>
                                    <div class="flex items-center text-yellow-500 text-sm mb-2">
                                        <span class="mr-1">★★★★☆</span>
                                        <span class="text-gray-500 text-xs">(4/5) Rating</span>
                                    </div>
                                    <ul class="text-xs text-gray-500 flex flex-wrap gap-4 mb-4">
                                        <li><i class="bi bi-book"></i> ${c.lessons} lessons</li>
                                        <li><i class="bi bi-clock"></i> ${c.hours}</li>
                                        <li><i class="bi bi-bar-chart"></i> ${c.level}</li>
                                    </ul>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <img src="http://weimaracademy.org/wp-content/uploads/2021/08/dummy-user.png" alt="${c.instructor}" class="w-7 h-7 rounded-full mr-2">
                                            <span class="text-xs text-gray-600">${c.instructor}</span>
                                        </div>
                                        <div class="text-right text-xs">
                                            <span class="line-through text-gray-400">SAR 99</span>
                                            <span class="text-green-600 font-semibold ml-1">SAR 89</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        courseContainer.insertAdjacentHTML('beforeend', courseHTML);
                    }
                    shownCourses = end;

                    if (shownCourses >= courses.length) {
                        viewAllBtn.classList.add('hidden');
                    }
                }

                viewAllBtn.addEventListener('click', () => {
                    renderCourses();
                });

                // Initial render of first row
                renderCourses();
            </script>


            <section class="py-16 bg-white">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="flex flex-col lg:flex-row items-center gap-10">
                    
                    <!-- Left Content -->
                    <div class="lg:w-1/2">
                        <h2 class="text-3xl md:text-4xl font-bold leading-snug">
                        Join <span class="text-blue-600">Talentrek</span><br />
                        as a Trainer, Mentor, Assessor, and Coach
                        </h2>
                        <p class="text-gray-700 mt-4 mb-6">
                        Share your expertise, guide jobseeker/professional, and one stop-shop powerful platform. 
                        </p>

                        <!-- Bullet Buttons with Circle Check Icon -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex items-center gap-2 px-4 py-2 border-2 border-blue-600 rounded-full text-blue-600">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-sm font-bold">✓</span>
                                Empower Learners
                            </div>
                            <div class="flex items-center gap-2 px-4 py-2 border-2 border-blue-600 rounded-full text-blue-600">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-sm font-bold">✓</span>
                                Earn & Grow
                            </div>
                            <div class="flex items-center gap-2 px-4 py-2 border-2 border-blue-600 rounded-full text-blue-600">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-sm font-bold">✓</span>
                                    Flexible Engagement
                            </div>
                            <div class="flex items-center gap-2 px-4 py-2 border-2 border-blue-600 rounded-full text-blue-600">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-sm font-bold">✓</span>
                                Expand Your Reach
                            </div>
                        </div>


                        <!-- CTA Button -->
                        <a href="#" class="mt-6 inline-block px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        Join Talentrek
                        </a>
                    </div>

                    <!-- Right Image -->
                    <div class="lg:w-1/2 text-center">
                        <div class="inline-block p-2 rounded-full">
                            <img src="{{ asset('asset/images/gallery/teams.png') }}" alt="Mentor" class="rounded-full w-full max-w-xs" />
                        </div>
                    </div>

                    </div>
                </div>
            </section>


             <section class="py-12 bg-white">
                <div class="max-w-6xl mx-auto px-4">
                    <h2 class="text-3xl font-bold text-center mb-10">What people are saying</h2>
                    <div class="relative">
                    <div class="swiper mySwiper">
                        <div class="swiper-wrapper">
                        <div class="swiper-slide px-2">
                            <div class="bg-white shadow-md rounded-lg p-6 h-full">
                            <div class="flex items-center mb-4">
                                <img src="https://randomuser.me/api/portraits/women/79.jpg" class="w-10 h-10 rounded-full mr-3" />
                                <div>
                                <p class="font-medium text-sm">Sarah M.</p>
                                </div>
                            </div>
                            <h4 class="font-semibold text-lg mb-2">A Game-Changer for My Career!</h4>
                            <p class="text-gray-600 text-sm">
                                Enrolling in these courses helped me upskill and land a job I truly love. The content is high quality and the instructors are incredibly knowledgeable.
                            </p>
                            </div>
                        </div>

                        <div class="swiper-slide px-2">
                            <div class="bg-white shadow-md rounded-lg p-6 h-full">
                                <div class="flex items-center mb-4">
                                    <img src="https://randomuser.me/api/portraits/men/45.jpg" class="w-10 h-10 rounded-full mr-3" />
                                    <div>
                                    <p class="font-medium text-sm">James R</p>
                                    </div>
                                </div>
                            <h4 class="font-semibold text-lg mb-2">Learning That Fits My Schedule</h4>
                            <p class="text-gray-600 text-sm">
                                As a working professional, flexibility is key. These online classes gave me the freedom to learn at my own pace while earning a certificate from a top university!
                            </p>
                            </div>
                        </div>

                        <div class="swiper-slide px-2">
                            <div class="bg-white shadow-md rounded-lg p-6 h-full">
                                <div class="flex items-center mb-4">
                                    <img src="https://randomuser.me/api/portraits/women/65.jpg" class="w-10 h-10 rounded-full mr-3" />
                                    <div>
                                    <p class="font-medium text-sm">Emily T</p>
                                    </div>
                                </div>
                            <h4 class="font-semibold text-lg mb-2">Highly Recommended</h4>
                            <p class="text-gray-600 text-sm">
                                The platform was easy to use, and the lessons were interactive and engaging. I completed two certifications and felt ready to apply what I learned immediately.
                            </p>
                            </div>
                        </div>

                        <div class="swiper-slide px-2">
                            <div class="bg-white shadow-md rounded-lg p-6 h-full">
                            <div class="flex items-center mb-4">
                                <img src="https://randomuser.me/api/portraits/men/60.jpg" class="w-10 h-10 rounded-full mr-3" />
                                <div>
                                <p class="font-medium text-sm">David K</p>
                                </div>
                            </div>
                            <h4 class="font-semibold text-lg mb-2">Solid Courses with Practical Value</h4>
                            <p class="text-gray-600 text-sm">
                                I appreciated the real-world examples and industry-relevant content. The instructors were top-notch and supportive throughout.
                            </p>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div class="swiper-button-prev !text-gray-500 hover:!text-black !left-0"></div>
                    <div class="swiper-button-next !text-gray-500 hover:!text-black !right-0"></div>
                    <div class="swiper-pagination mt-6 text-center"></div>
                    </div>
                </div>
            </section>
            <script>
                const swiper = new Swiper(".mySwiper", {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    loop: true,
                    autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                    },
                    navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev"
                    },
                    pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                    },
                    breakpoints: {
                    768: {
                        slidesPerView: 2,
                    }
                    }
                });
            </script>

            <style>
                .stats-section {
                background-color: #0047AB; /* Blue background */
                color: white;
                padding: 60px 0;
                text-align: center;
                }

                .stats-number {
                color: orange;
                font-size: 2rem;
                font-weight: bold;
                }

                .stats-description {
                font-size: 0.95rem;
                margin-top: 5px;
                }
            </style>


            <script>
                const swiper = new Swiper(".mySwiper", {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    loop: true,
                    autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                    },
                    navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev"
                    },
                    pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                    },
                    breakpoints: {
                    768: {
                        slidesPerView: 2,
                    }
                    }
                });
            </script>

            <style>
                .stats-section {
                background-color: #0047AB; /* Blue background */
                color: white;
                padding: 60px 0;
                text-align: center;
                }

                .stats-number {
                color: orange;
                font-size: 2rem;
                font-weight: bold;
                }

                .stats-description {
                font-size: 0.95rem;
                margin-top: 5px;
                }
            </style>
           

            <section class="stats-section">
                <div class="container">
                    <div class="row text-center">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <div class="stats-number">35000+</div>
                            <div class="stats-description">Student worldwide</div>
                        </div>
                        <div class="col-md-4 mb-4 mb-md-0">
                            <div class="stats-number">500+</div>
                            <div class="stats-description">Course available</div>
                        </div>
                        <div class="col-md-4">
                            <div class="stats-number">10000+</div>
                            <div class="stats-description">People loved it</div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

@include('site.componants.footer')