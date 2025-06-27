<!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <header class="bg-white shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-4 flex items-center justify-between h-16 relative">

        <!-- Logo -->
        <a href="#" class="flex-shrink-0">
            <img src="{{ asset('asset/images/logo.png') }}" alt="Logo" class="h-8" />
        </a>

        <!-- Mobile: Sign in + Hamburger -->
        <div class="md:hidden flex items-center space-x-2">
        <!-- Sign In/Up Button -->
        <button onclick="toggleDropdown()" class="bg-blue-700 hover:bg-blue-800 text-white px-3 py-1.5 text-sm rounded flex items-center space-x-1">
            <span>Sign in</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Hamburger -->
        <button id="mobileMenuToggle" class="text-gray-800">
            <i data-feather="menu" class="w-6 h-6"></i>
        </button>
        </div>

    <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-6 ml-auto">
            <!-- Main Nav (no dropdowns) -->
            <nav class="flex space-x-10 text-base font-semibold text-black">
                <a href="{{ route('training') }}"
                class="{{ request()->routeIs('training', 'training-detail', 'buy-course', 'buy-course-for-team') ? 'text-blue-600' : 'hover:text-blue-600' }}">
                    Training
                </a>
                <a href="{{ route('mentorship') }}"
                class="{{ request()->routeIs('mentorship', 'mentorship-details', 'mentorship-book-session', 'mentorship-booking-success') ? 'text-blue-600' : 'hover:text-blue-600' }}">
                    Mentorship
                </a>
                <a href="{{ route('assessment') }}"
                class="{{ request()->routeIs('assessment', 'assessment-details', 'assessment-book-session', 'assessment-booking-success') ? 'text-blue-600' : 'hover:text-blue-600' }}">
                    Assessment
                </a>
                <a href="{{ route('coaching') }}"
                class="{{ request()->routeIs('coaching', 'coach-details', 'coach-book-session', 'coach-booking-success') ? 'text-blue-600' : 'hover:text-blue-600' }}">
                    Coaching
                </a>
            </nav>



        <!-- Notification -->
        <button class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-700 text-white">
            <i data-feather="bell" class="w-4 h-4"></i>
        </button>

        <!-- Language Selector -->
        <div class="relative flex items-center space-x-1">
            <div class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-700 text-white">
            <i data-feather="globe" class="w-4 h-4"></i>
            </div>
            <select class="bg-transparent appearance-none text-base font-medium pl-1 pr-4 text-black focus:outline-none">
            <option>English</option>
            <option>Spanish</option>
            </select>
        </div>

        <!-- Sign In/Up (shared dropdown) -->
        <button onclick="toggleDropdown()" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-1.5 text-base rounded flex items-center space-x-1">
            <span>Sign in / Sign up</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        </div>

        <!-- Shared Sign In/Up Dropdown -->
        <div id="signinDropdown" class="hidden absolute right-4 top-full mt-2 w-56 bg-white border border-gray-200 rounded shadow-lg z-50">
            <a href="{{ url('/jobseeker/sign-in') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign in as Jobseeker</a>
            <a href="{{ url('/mentor/sign-in') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign in as Mentor</a>
            <a href="{{ url('/trainer/sign-in') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign in as Trainer</a>
            <a href="{{ url('/assessor/sign-in') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign in as Assessor</a>
            <a href="{{ url('/coach/sign-in') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign in as Coach</a>
            <a href="{{ url('/recruiter/sign-in') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign in as Recruiter</a>
        </div>

    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="md:hidden hidden bg-white border-t border-gray-200 px-4 py-4 space-y-3 text-base font-semibold">
        <a href="training.html" class="block text-gray-800 hover:text-blue-600">Training</a>
        <a href="mentorship.html" class="block text-gray-800 hover:text-blue-600">Mentorship</a>
        <a href="assessment.html" class="block text-gray-800 hover:text-blue-600">Assessment</a>
        <a href="coaching.html" class="block text-gray-800 hover:text-blue-600">Coaching</a>
    </div>
    </header>


    <!-- JS -->
    <script>
        feather.replace();

        function toggleDropdown() {
            const dropdown = document.getElementById('signinDropdown');
            dropdown.classList.toggle('hidden');

            document.addEventListener('click', function handler(e) {
            if (!e.target.closest('#signinDropdown') && !e.target.closest('[onclick="toggleDropdown()"]')) {
                dropdown.classList.add('hidden');
                document.removeEventListener('click', handler);
            }
            });
        }

        document.getElementById('mobileMenuToggle').addEventListener('click', () => {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });
        </script>