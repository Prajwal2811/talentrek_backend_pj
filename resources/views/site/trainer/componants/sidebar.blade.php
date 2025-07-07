<aside class="w-64 bg-blue-900 text-white flex flex-col py-8 px-4">
    <!-- Logo -->
    <div class="flex justify-center mb-8">
        <img src="{{ asset('asset/images/Talentrek.png') }}" alt="Talentrek Logo"
            class="h-10 transition-all duration-300"
            :class="{ 'hidden': !sidebarOpen }" />
    </div>

    <nav class="flex flex-col gap-4">

        <!-- Dashboard -->
        <a href="{{ route('trainer.dashboard') }}"
            class="flex items-center px-4 py-2 rounded-md {{ request()->routeIs('trainer.dashboard') ? 'bg-white text-blue-700' : 'text-white hover:bg-blue-600' }}">
            <i data-feather="grid" class="mr-3"></i> Dashboard
        </a>

        <!-- My Training -->
        <div x-data="{ open: {{ request()->routeIs('training.*') ? 'true' : 'false' }} }" class="flex flex-col">
            <button
                @click="open = !open"
                class="flex items-center px-4 py-2 text-white rounded-md hover:bg-blue-600 transition-colors duration-200 focus:outline-none"
                :aria-expanded="open">
                <i data-feather="book-open" class="mr-3"></i> My Training
                <svg :class="{ 'rotate-90': open }" class="ml-auto w-4 h-4 transition-transform duration-200"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <div x-show="open" x-transition class="flex flex-col ml-8 mt-1 space-y-1">
                <a href="{{ route('training.list') }}"
                    class="px-4 py-2 rounded-md {{ request()->routeIs('training.list') ? 'bg-white text-blue-700' : 'text-white hover:bg-blue-600' }}">
                    Training List
                </a>
                <a href="{{ route('training.add') }}"
                    class="px-4 py-2 rounded-md {{ request()->routeIs('training.add') ? 'bg-white text-blue-700' : 'text-white hover:bg-blue-600' }}">
                    Add Training
                </a>
            </div>
        </div>

        <!-- Assessment -->
        <div x-data="{ open: {{ request()->routeIs('assessment.*') ? 'true' : 'false' }} }" class="flex flex-col">
            <button
                @click="open = !open"
                class="flex items-center px-4 py-2 text-white rounded-md hover:bg-blue-600 transition-colors duration-200 focus:outline-none"
                :aria-expanded="open">
                <i data-feather="file-text" class="mr-3"></i> Assessment
                <svg :class="{ 'rotate-90': open }" class="ml-auto w-4 h-4 transition-transform duration-200"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <div x-show="open" x-transition class="flex flex-col ml-8 mt-1 space-y-1">
                <a href="{{ route('assessment.list') }}"
                    class="px-4 py-2 rounded-md {{ request()->routeIs('assessment.list') ? 'bg-white text-blue-700' : 'text-white hover:bg-blue-600' }}">
                    Assessment List
                </a>
                <a href="{{ route('assessment.add') }}"
                    class="px-4 py-2 rounded-md {{ request()->routeIs('assessment.add') ? 'bg-white text-blue-700' : 'text-white hover:bg-blue-600' }}">
                    Add Assessment
                </a>
            </div>
        </div>

        <!-- Batch -->
        <a href="{{ route('batch') }}"
            class="flex items-center px-4 py-2 rounded-md {{ request()->routeIs('batch') ? 'bg-white text-blue-700' : 'text-white hover:bg-blue-600' }}">
            <i data-feather="layers" class="mr-3"></i> Batch
        </a>

        <!-- Trainees / Jobseeker -->
        <a href="{{ route('trainees.jobseekers') }}"
            class="flex items-center px-4 py-2 rounded-md {{ request()->routeIs('trainees.jobseekers') ? 'bg-white text-blue-700' : 'text-white hover:bg-blue-600' }}">
            <i data-feather="users" class="mr-3"></i> Trainees / Jobseeker
        </a>

        <!-- Chat -->
        <a href="{{ route('chat.with.jobseeker') }}"
            class="flex items-center px-4 py-2 rounded-md {{ request()->routeIs('chat.with.jobseeker') ? 'bg-white text-blue-700' : 'text-white hover:bg-blue-600' }}">
            <i data-feather="message-circle" class="mr-3"></i> Chat with jobseeker
        </a>

        <!-- Reviews -->
        <a href="{{ route('reviews') }}"
            class="flex items-center px-4 py-2 rounded-md {{ request()->routeIs('reviews') ? 'bg-white text-blue-700' : 'text-white hover:bg-blue-600' }}">
            <i data-feather="star" class="mr-3"></i> Reviews
        </a>

        <!-- Settings -->
        <a href="{{ route('trainer.settings') }}"
            class="flex items-center px-4 py-2 rounded-md {{ request()->routeIs('trainer.settings') ? 'bg-white text-blue-700' : 'text-white hover:bg-blue-600' }}">
            <i data-feather="settings" class="mr-3"></i> Settings
        </a>

        <!-- Logout -->
        <a href="{{ route('trainer.logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="flex items-center px-4 py-2 rounded-md hover:bg-blue-600">
            <i data-feather="log-out" class="mr-3"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('trainer.logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </nav>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
</aside>
