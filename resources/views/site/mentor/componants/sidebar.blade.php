<!-- Alpine.js + Feather Icons -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://unpkg.com/feather-icons"></script>

<!-- Sidebar -->
<aside x-data="{ sidebarOpen: true }"
       class="bg-blue-900 text-white flex flex-col py-8 px-4 transition-all duration-300"
       :class="{ 'w-64': sidebarOpen, 'w-16': !sidebarOpen }">

    <!-- Logo -->
    <div class="flex justify-center mb-8">
        <img src="{{ asset('asset/images/Talentrek.png') }}"
             alt="Talentrek Logo"
             class="h-10 transition-all duration-300"
             :class="{ 'hidden': !sidebarOpen }" />
    </div>

    <!-- Navigation -->
    <nav class="flex flex-col gap-4" :class="sidebarOpen ? '' : 'items-center'" x-data="{ active: '{{ Request::route()->getName() }}' }">

        <!-- Dashboard -->
        <a href="{{ route('mentor.dashboard') }}"
           :class="[
               active === 'mentor.dashboard' ? 'bg-white text-blue-900 font-semibold' : 'hover:bg-white hover:text-blue-900',
               'flex items-center px-4 py-2 rounded-md transition duration-200'
           ]"
           @click="active = 'mentor.dashboard'"
           :title="!sidebarOpen ? 'Dashboard' : ''">
            <i data-feather="grid" class="mr-3"></i>
            <span x-show="sidebarOpen" x-transition>Dashboard</span>
        </a>

        <!-- About Coach -->
        <a href="{{ route('about.mentor') }}"
           :class="[
               active === 'about.mentor' ? 'bg-white text-blue-900 font-semibold' : 'hover:bg-white hover:text-blue-900',
               'flex items-center px-4 py-2 rounded-md transition duration-200'
           ]"
           @click="active = 'about.mentor'"
           :title="!sidebarOpen ? 'About mentor' : ''">
            <i data-feather="user" class="mr-3"></i>
            <span x-show="sidebarOpen" x-transition>About Mentor</span>
        </a>

        <!-- Booking Slots -->
        <div x-data="{ open: {{ request()->routeIs('manage.booking.slots.mentor') || request()->routeIs('create.booking.slots.mentor') ? 'true' : 'false' }} }" class="flex flex-col">
            <button @click="open = !open"
                class="flex items-center px-4 py-2 rounded-md transition duration-200 hover:bg-white hover:text-blue-900"
                :class="sidebarOpen ? '' : 'justify-center'"
                :title="!sidebarOpen ? 'Booking Slots' : ''">
                <i data-feather="book-open" class="mr-3"></i>
                <span x-show="sidebarOpen">Booking Slots</span>
                <svg x-show="sidebarOpen" :class="{ 'rotate-90': open }" class="ml-auto w-4 h-4 transition-transform duration-200"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <div x-show="open && sidebarOpen" x-transition class="flex flex-col ml-8 mt-1 space-y-1">
                <a href="{{ route('manage.booking.slots.mentor') }}"
                   class="px-4 py-2 rounded-md {{ request()->routeIs('manage.booking.slots.mentor') ? 'bg-white text-blue-700' : 'text-white hover:bg-blue-600' }}">
                    Manage Booking Slots
                </a>
                <a href="{{ route('create.booking.slots.mentor') }}"
                   class="px-4 py-2 rounded-md {{ request()->routeIs('create.booking.slots.mentor') ? 'bg-white text-blue-700' : 'text-white hover:bg-blue-600' }}">
                    Create Booking Slots
                </a>
            </div>
        </div>

        <!-- Other Mentor Links -->
        @php
            $mentorLinks = [
                ['route' => 'chat.with.jobseeker.mentor', 'icon' => 'message-circle', 'label' => 'Chat with Jobseeker'],
                ['route' => 'mentor.reviews', 'icon' => 'star', 'label' => 'Reviews'],
                ['route' => 'admin-support-mentor', 'icon' => 'headphones', 'label' => 'Admin Support'],
                ['route' => 'setting.mentor', 'icon' => 'settings', 'label' => 'Settings'],
            ];
        @endphp

        @foreach($mentorLinks as $link)
            <a href="{{ route($link['route']) }}"
               :class="[
                   active === '{{ $link['route'] }}' ? 'bg-white text-blue-900 font-semibold' : 'hover:bg-white hover:text-blue-900',
                   'flex items-center px-4 py-2 rounded-md transition duration-200'
               ]"
               @click="active = '{{ $link['route'] }}'"
               :title="!sidebarOpen ? '{{ $link['label'] }}' : ''">
                <i data-feather="{{ $link['icon'] }}" class="mr-3"></i>
                <span x-show="sidebarOpen" x-transition>{{ $link['label'] }}</span>
            </a>
        @endforeach

        <!-- Logout -->
        <a href="{{ route('mentor.logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="flex items-center px-4 py-2 rounded-md hover:bg-white hover:text-blue-900 transition duration-200"
            :title="!sidebarOpen ? 'Logout' : ''">
                <i data-feather="log-out" class="mr-3"></i>
                <span x-show="sidebarOpen" x-transition>Logout</span>
        </a>

        <form id="logout-form" action="{{ route('mentor.logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </nav>
</aside>

<!-- Feather Icons Replace -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        feather.replace();
    });
</script>

<!-- Center icons when sidebar is collapsed -->
<style>
    aside.w-16 nav a {
        justify-content: center;
    }
</style>
