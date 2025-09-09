@php
$coachId = auth()->guard('coach')->id();
$unreadCount = DB::table('admin_group_chats')
    ->where('receiver_id', $coachId)
    ->where('receiver_type', 'coach')
    ->where('is_read', 0)
    ->count();
@endphp

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
        <a href="{{ route('coach.dashboard') }}"
           :class="[
               active === 'coach.dashboard' ? 'bg-white text-blue-900 font-semibold' : 'hover:bg-white hover:text-blue-900',
               'flex items-center px-4 py-2 rounded-md transition duration-200'
           ]"
           @click="active = 'coach.dashboard'"
           :title="!sidebarOpen ? 'Dashboard' : ''">
            <i data-feather="grid" class="mr-3"></i>
            <span x-show="sidebarOpen" x-transition>Dashboard</span>
        </a>


        <!-- Booking Slots -->
        <div x-data="{ open: {{ request()->routeIs('coach.manage-bookings') || request()->routeIs('coach.create-bookings') ? 'true' : 'false' }} }" class="flex flex-col">
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
                <a href="{{ route('coach.manage-bookings') }}"
                   class="px-4 py-2 rounded-md {{ request()->routeIs('coach.manage-bookings') ? 'bg-white text-blue-700' : 'text-white hover:bg-blue-600' }}">
                    Booking Slots
                </a>
                <a href="{{ route('coach.create-bookings') }}"
                   class="px-4 py-2 rounded-md {{ request()->routeIs('coach.create-bookings') ? 'bg-white text-blue-700' : 'text-white hover:bg-blue-600' }}">
                    Create Slots
                </a>
            </div>
        </div>

        <!-- Other coach Links -->
        @php
            $coachLinks = [
                ['route' => 'chat.with.jobseeker.coach', 'icon' => 'message-circle', 'label' => 'Chat with Jobseeker'],
                ['route' => 'coach.reviews', 'icon' => 'star', 'label' => 'Reviews'],
                ['route' => 'admin-support-coach', 'icon' => 'headphones', 'label' => 'Admin Support', 'badge' => $unreadCount],
                ['route' => 'setting.coach', 'icon' => 'settings', 'label' => 'Settings'],
            ];
        @endphp

        @foreach($coachLinks as $link)
        <a href="{{ route($link['route']) }}"
           :class="[active === '{{ $link['route'] }}' ? 'bg-white text-blue-900 font-semibold' : 'hover:bg-white hover:text-blue-900','flex items-center px-4 py-2 rounded-md transition duration-200']"
           @click="active = '{{ $link['route'] }}'; 
                   if('{{ $link['route'] }}' === 'admin-support-coach'){ markMessagesAsRead(); }"
           :title="!sidebarOpen ? '{{ $link['label'] }}' : ''">

            <i data-feather="{{ $link['icon'] }}" class="mr-3"></i>
            <span x-show="sidebarOpen" x-transition>{{ $link['label'] }}</span>

            @if($link['route'] === 'admin-support-coach')
                <span id="admin-support-badge"
                      class="ml-2 bg-red-600 text-white text-xs font-bold rounded-full px-2 py-0.5 {{ ($unreadCount ?? 0) > 0 ? '' : 'hidden' }}">
                      {{ $unreadCount ?? 0 }}
                </span>
            @endif
        </a>
        @endforeach

        <!-- Logout -->
        <a href="{{ route('coach.logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="flex items-center px-4 py-2 rounded-md hover:bg-white hover:text-blue-900 transition duration-200"
            :title="!sidebarOpen ? 'Logout' : ''">
                <i data-feather="log-out" class="mr-3"></i>
                <span x-show="sidebarOpen" x-transition>Logout</span>
        </a>

        <form id="logout-form" action="{{ route('coach.logout') }}" method="POST" class="hidden">
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
<script>
   const currentRoute = "{{ Route::currentRouteName() }}";

    document.addEventListener("DOMContentLoaded", () => {
        feather.replace();

        // Initial unread count
        fetchUnreadCount();

        // Realtime listener
        Echo.channel('chat.coach')
            .listen('.message.sent', (e) => {

                // Agar coach admin-support page par hai → turant mark read
                if (currentRoute === 'admin-support-coach') {
                    markMessagesAsRead();
                } else {
                    // Dusre page par → update badge
                    fetchUnreadCount();
                }
            });

        // Listen for seen event
        Echo.channel('chat.admin')
            .listen('.message.seen', (e) => {
                let badge = document.getElementById('admin-support-badge');
                if (badge) {
                    badge.innerText = 0;
                    badge.classList.add('hidden');
                }
            });
    });

    // Fetch unread count
    function fetchUnreadCount() {
        fetch("{{ route('coach.getUnreadCount') }}")
            .then(res => res.json())
            .then(data => {
                let badge = document.getElementById('admin-support-badge');
                if (badge) {
                    badge.innerText = data.count;
                    badge.classList.toggle('hidden', data.count == 0);
                }
            });
    }

    // Mark messages as read (manual / auto)
    function markMessagesAsRead() {
        fetch("{{ route('coach.markMessagesSeen') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({})
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                let badge = document.getElementById('admin-support-badge');
                if (badge) {
                    badge.innerText = 0;
                    badge.classList.add('hidden');
                }
            }
        });
    }


</script>