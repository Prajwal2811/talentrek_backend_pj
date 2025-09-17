@php
$mentorId = auth()->guard('mentor')->id();
$unreadCount = DB::table('admin_group_chats')
    ->where('receiver_id', $mentorId)
    ->where('receiver_type', 'mentor')
    ->where('is_read', 0)
    ->count();
@endphp

<aside x-data="{ sidebarOpen: true }" class="bg-blue-900 text-white flex flex-col py-8 px-4 transition-all duration-300" :class="{ 'w-64': sidebarOpen, 'w-16': !sidebarOpen }">
    
    <!-- Logo -->
    <div class="flex justify-center mb-8">
        <img src="{{ asset('asset/images/Talentrek.png') }}" alt="Talentrek Logo" class="h-10 transition-all duration-300" :class="{ 'hidden': !sidebarOpen }" />
    </div>

    <!-- Navigation -->
    <nav class="flex flex-col gap-4" :class="sidebarOpen ? '' : 'items-center'" x-data="{ active: '{{ Request::route()->getName() }}' }">

        <!-- Dashboard -->
        <a href="{{ route('mentor.dashboard') }}"
           :class="[active === 'mentor.dashboard' ? 'bg-white text-blue-900 font-semibold' : 'hover:bg-white hover:text-blue-900', 'flex items-center px-4 py-2 rounded-md transition duration-200']"
           @click="active = 'mentor.dashboard'"
           :title="!sidebarOpen ? 'Dashboard' : ''">
            <i data-feather="grid" class="mr-3"></i>
            <span x-show="sidebarOpen" x-transition>{{ langLabel('dashboard') }}</span>
        </a>


        <div x-data="{ open: {{ request()->routeIs('mentor.manage-bookings') || request()->routeIs('mentor.create-bookings') ? 'true' : 'false' }} }" class="flex flex-col">
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
                <a href="{{ route('mentor.manage-bookings') }}"
                   class="px-4 py-2 rounded-md {{ request()->routeIs('mentor.manage-bookings') ? 'bg-white text-blue-700' : 'text-white hover:bg-blue-600' }}">
                    {{ langLabel('booking_slots') }}
                </a>
                <a href="{{ route('mentor.create-bookings') }}"
                   class="px-4 py-2 rounded-md {{ request()->routeIs('mentor.create-bookings') ? 'bg-white text-blue-700' : 'text-white hover:bg-blue-600' }}">
                    {{ langLabel('create_slots') }}
                </a>
            </div>
        </div>

        <!-- Admin Support Link with Badge -->
        @php
            $mentorLinks = [
                ['route' => 'chat.with.jobseeker.mentor', 'icon' => 'message-circle', 'label' => 'Chat with Jobseeker'],
                ['route' => 'mentor.reviews', 'icon' => 'star', 'label' => 'Reviews'],
                ['route' => 'admin-support-mentor', 'icon' => 'headphones', 'label' => 'Admin Support', 'badge' => $unreadCount],
                ['route' => 'setting.mentor', 'icon' => 'settings', 'label' => 'Settings'],
            ];
        @endphp

        @foreach($mentorLinks as $link)
        <a href="{{ route($link['route']) }}"
           :class="[active === '{{ $link['route'] }}' ? 'bg-white text-blue-900 font-semibold' : 'hover:bg-white hover:text-blue-900','flex items-center px-4 py-2 rounded-md transition duration-200']"
           @click="active = '{{ $link['route'] }}'; 
                   if('{{ $link['route'] }}' === 'admin-support-mentor'){ markMessagesAsRead(); }"
           :title="!sidebarOpen ? '{{ $link['label'] }}' : ''">

            <i data-feather="{{ $link['icon'] }}" class="mr-3"></i>
            <span x-show="sidebarOpen" x-transition>{{ $link['label'] }}</span>

            @if($link['route'] === 'admin-support-mentor')
                <span id="admin-support-badge"
                      class="ml-2 bg-red-600 text-white text-xs font-bold rounded-full px-2 py-0.5 {{ ($unreadCount ?? 0) > 0 ? '' : 'hidden' }}">
                      {{ $unreadCount ?? 0 }}
                </span>
            @endif
        </a>
        @endforeach

        <!-- Logout -->
        <a href="{{ route('mentor.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="flex items-center px-4 py-2 rounded-md hover:bg-white hover:text-blue-900 transition duration-200" :title="!sidebarOpen ? 'Logout' : ''">
           <i data-feather="log-out" class="mr-3"></i>
           <span x-show="sidebarOpen" x-transition>{{ langLabel('logout') }}</span>
        </a>
        <form id="logout-form" action="{{ route('mentor.logout') }}" method="POST" class="hidden">@csrf</form>
    </nav>
</aside>

<!-- Feather Icons -->
<script>
document.addEventListener("DOMContentLoaded", () => { feather.replace(); });
</script>

<style>
aside.w-16 nav a { justify-content: center; }
</style>

<script>
   const currentRoute = "{{ Route::currentRouteName() }}";

    document.addEventListener("DOMContentLoaded", () => {
        feather.replace();

        // Initial unread count
        fetchUnreadCount();

        // Realtime listener
        Echo.channel('chat.mentor')
            .listen('.message.sent', (e) => {

                // Agar mentor admin-support page par hai → turant mark read
                if (currentRoute === 'admin-support-mentor') {
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
        fetch("{{ route('mentor.getUnreadCount') }}")
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
        fetch("{{ route('mentor.markMessagesSeen') }}", {
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

