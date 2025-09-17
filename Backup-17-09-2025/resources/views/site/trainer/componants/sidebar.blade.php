

    <!-- Sidebar -->
    <aside 
        class="bg-blue-900 text-white flex flex-col py-8 px-4 transition-all duration-300"
        :class="{ 'w-64': sidebarOpen, 'w-16': !sidebarOpen }">
    
        <!-- Logo -->
        <div class="flex justify-center mb-8" style="background">
            <img src="{{ asset('asset/images/Talentrek.png') }}" alt="Talentrek Logo"
                class="h-10 transition-all duration-300"
                :class="{ 'hidden': !sidebarOpen }" />
        </div>

        <!-- Navigation -->
        <nav class="flex flex-col gap-4" :class="sidebarOpen ? '' : 'items-center'" x-data="{ active: '{{ Request::route()->getName() }}' }">

            <!-- Dashboard -->
            <a href="{{ route('trainer.dashboard') }}"
               :class="[
                   active === 'trainer.dashboard' ? 'bg-white text-blue-900 font-semibold' : 'hover:bg-white hover:text-blue-900',
                   'flex items-center px-4 py-2 rounded-md transition duration-200'
               ]"
               @click="active = 'trainer.dashboard'"
               :title="!sidebarOpen ? 'Dashboard' : ''">
                <i data-feather="grid" class="mr-3"></i>
                <span x-show="sidebarOpen" x-transition>{{ langLabel('dashboard') }}</span>
            </a>

            <!-- My Training -->
            <div x-data="{ open: {{ request()->routeIs('training.*') ? 'true' : 'false' }} }" class="flex flex-col">
                <button
                    @click="open = !open"
                    class="flex items-center px-4 py-2 text-white rounded-md hover:bg-blue-600 transition duration-200 focus:outline-none"
                    :class="sidebarOpen ? '' : 'justify-center' "
                    :title="!sidebarOpen ? 'My Training' : '' ">
                    <i data-feather="book-open" class="mr-3"></i>
                    <span x-show="sidebarOpen">{{ langLabel('my_training') }}</span>
                    <svg x-show="sidebarOpen" :class="{ 'rotate-90': open }" class="ml-auto w-4 h-4 transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
                <div x-show="open && sidebarOpen" x-transition class="flex flex-col ml-8 mt-1 space-y-1">
                    <a href="{{ route('training.list') }}"
                       class="px-4 py-2 rounded-md {{ request()->routeIs('training.list') ? 'bg-white text-blue-700' : 'text-white hover:bg-blue-600' }}">
                        {{ langLabel('training') }} {{ langLabel('list') }}
                    </a>
                    <a href="{{ route('training.add') }}"
                       class="px-4 py-2 rounded-md {{ request()->routeIs('training.add') ? 'bg-white text-blue-700' : 'text-white hover:bg-blue-600' }}">
                        {{ langLabel('add') }} {{ langLabel('training') }}
                    </a>
                </div>
            </div>

            <!-- Assessment -->
            <div x-data="{ open: {{ request()->routeIs('assessment.*') ? 'true' : 'false' }} }" class="flex flex-col">
                <button
                    @click="open = !open"
                    class="flex items-center px-4 py-2 text-white rounded-md hover:bg-blue-600 transition duration-200"
                    :class="sidebarOpen ? '' : 'justify-center'"
                    :title="!sidebarOpen ? 'Assessment' : ''">
                    <i data-feather="file-text" class="mr-3"></i>
                    <span x-show="sidebarOpen">{{ langLabel('assessment') }}</span>
                    <svg x-show="sidebarOpen" :class="{ 'rotate-90': open }" class="ml-auto w-4 h-4 transition-transform duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
                <div x-show="open && sidebarOpen" x-transition class="flex flex-col ml-8 mt-1 space-y-1">
                    <a href="{{ route('assessment.list') }}"
                       class="px-4 py-2 rounded-md {{ request()->routeIs('assessment.list') ? 'bg-white text-blue-700' : 'text-white hover:bg-blue-600' }}">
                        {{ langLabel('assessment') }} {{ langLabel('list') }}
                    </a>
                    <a href="{{ route('assessment.add') }}"
                       class="px-4 py-2 rounded-md {{ request()->routeIs('assessment.add') ? 'bg-white text-blue-700' : 'text-white hover:bg-blue-600' }}">
                        {{ langLabel('add') }} {{ langLabel('assessment') }}
                    </a>
                </div>
            </div>

            <!-- Other Items -->
            @php
                $links = [
                    ['route' => 'batch', 'icon' => 'layers', 'label' => 'Batch'],
                    ['route' => 'trainees.jobseekers', 'icon' => 'users', 'label' => 'Trainees / Jobseeker'],
                    ['route' => 'chat.with.jobseeker', 'icon' => 'message-circle', 'label' => 'Chat with jobseeker'],
                    ['route' => 'trainer.reviews', 'icon' => 'star', 'label' => 'Reviews'],
                    ['route' => 'trainer.settings', 'icon' => 'settings', 'label' => 'Settings'],
                ];
            @endphp

            @foreach($links as $link)
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
            <a href="{{ route('trainer.logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               :class="[
                   active === 'trainer.logout' ? 'bg-white text-blue-900 font-semibold' : 'hover:bg-white hover:text-blue-900',
                   'flex items-center px-4 py-2 rounded-md transition duration-200'
               ]"
               :title="!sidebarOpen ? 'Logout' : ''">
                <i data-feather="log-out" class="mr-3"></i>
                <span x-show="sidebarOpen" x-transition>{{ langLabel('logout') }}</span>
            </a>
            <form id="logout-form" action="{{ route('trainer.logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </nav>
    </aside>



<!-- Feather Replace -->
<script src="https://unpkg.com/feather-icons"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        feather.replace();
    });
</script>

<!-- Style to center icons when collapsed -->
<style>
    aside.w-16 nav a {
        justify-content: center;
    }
</style>
