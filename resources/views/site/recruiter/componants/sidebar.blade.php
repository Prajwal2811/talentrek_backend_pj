            <aside 
                class="bg-blue-900 text-white flex flex-col py-8 px-4 transition-all duration-300"
                :class="{ 'w-64': sidebarOpen, 'w-16': !sidebarOpen }">
                <!-- Logo -->
                <div class="flex justify-center mb-8">
                    <img src="{{ asset('asset/images/Talentrek.png')}}" alt="Talentrek Logo" 
                        class="h-10 transition-all duration-300" 
                        :class="{ 'hidden': !sidebarOpen }" />
                </div>

                <!-- Navigation -->
                <nav 
                    class="flex flex-col gap-4 text-white" 
                    x-data="{ active: window.location.pathname.split('/').pop() }"
                    :class="sidebarOpen ? '' : 'items-center'"
                    >
                    <!-- Dashboard -->
                    <a 
                        href="{{ route('recruiter.dashboard') }}" 
                        :class="[ 
                            active === 'dashboard' 
                                ? 'bg-white text-blue-900 font-semibold' 
                                : 'hover:bg-white hover:text-blue-900', 
                            'flex items-center px-4 py-2 rounded-md transition-colors duration-200' 
                        ]" 
                        :title="!sidebarOpen ? 'Dashboard' : ''"
                        @click="active = 'dashboard'"
                    >
                        <i data-feather="grid" class="mr-3"></i>
                        <span x-show="sidebarOpen" x-transition>{{ langLabel('dashboard') }}</span>
                    </a>

                    <!-- Jobseekers -->
                    <a 
                        href="{{ route('recruiter.jobseeker') }}" 
                        :class="[ 
                            active === 'jobseeker' 
                                ? 'bg-white text-blue-900 font-semibold' 
                                : 'hover:bg-white hover:text-blue-900', 
                            'flex items-center px-4 py-2 rounded-md transition-colors duration-200' 
                        ]" 
                        :title="!sidebarOpen ? 'About recruiter' : ''"
                        @click="active = 'jobseeker'"
                    >
                        <i data-feather="user" class="mr-3"></i>
                        <span x-show="sidebarOpen" x-transition>{{ langLabel('jobseeker') }}</span>
                    </a>

                    <!-- Admin Support -->
                    <a 
                        href="{{ route('recruiter.admin.support') }}" 
                        :class="[ 
                            active === 'admin-support' 
                                ? 'bg-white text-blue-900 font-semibold' 
                                : 'hover:bg-white hover:text-blue-900', 
                            'flex items-center px-4 py-2 rounded-md transition-colors duration-200' 
                        ]" 
                        :title="!sidebarOpen ? 'Admin support' : ''"
                        @click="active = 'admin-support'; markMessagesAsReadRecruiter();"
                    >
                        <i data-feather="headphones" class="mr-3"></i>
                        <span x-show="sidebarOpen" x-transition>{{ langLabel('admin_support') }}</span>

                        <!-- 🔴 Badge -->
                        <span id="admin-support-badge-recruiter"
                            class="ml-2 bg-red-600 text-white text-xs font-bold rounded-full px-2 py-0.5 {{ ($unreadCount ?? 0) > 0 ? '' : 'hidden' }}">
                            {{ $unreadCount ?? 0 }}
                        </span>
                    </a>




                    <!-- Settings -->
                    <a 
                        href="{{ route('recruiter.settings') }}" 
                        :class="[
                            active === 'settings'
                                ? 'bg-white text-blue-900 font-semibold' 
                                : 'hover:bg-white hover:text-blue-900',
                            'flex items-center px-4 py-2 rounded-md transition-colors duration-200'
                        ]"
                        :title="!sidebarOpen ? 'Settings' : ''"
                        @click="active = 'settings'"
                    >
                        <i data-feather="settings" class="mr-3"></i>
                        <span x-show="sidebarOpen" x-transition>{{ langLabel('settings') }}</span>
                    </a>



                    <a 
                        href="{{ route('recruiter.logout') }}" 
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        :class="[
                            active === 'logout' 
                                ? 'bg-white text-blue-900 font-semibold' 
                                : 'hover:bg-white hover:text-blue-900',
                            'flex items-center px-4 py-2 rounded-md transition-colors duration-200'
                        ]"
                        :title="!sidebarOpen ? 'Logout' : ''"
                    >
                        <i data-feather="log-out" class="mr-3"></i>
                        <span x-show="sidebarOpen" x-transition>{{ langLabel('logout') }}</span>
                    </a>

                    <form id="logout-form" action="{{ route('recruiter.logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>

                </nav>
            </aside>
            <!-- Feather Icons -->
            <script src="https://unpkg.com/feather-icons"></script>
            <script>feather.replace();</script>
            <style>
                /* Center icons when sidebar is collapsed */
                aside.w-16 nav a {
                justify-content: center;
                }
            </style>
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
    fetchUnreadCountRecruiter();

    // Realtime listener
    Echo.channel('chat.recruiter')
        .listen('.message.sent', (e) => {
            if (currentRoute === 'admin-support-recruiter') {
                markMessagesAsReadRecruiter();
            } else {
                fetchUnreadCountRecruiter();
            }
        });

    // Listen for seen event
    Echo.channel('chat.admin')
        .listen('.message.seen', (e) => {
            let badge = document.getElementById('admin-support-badge-recruiter');
            if (badge) {
                badge.innerText = 0;
                badge.classList.add('hidden');
            }
        });
    });

    // Fetch unread count
    function fetchUnreadCountRecruiter() {
        fetch("{{ route('recruiter.getUnreadCount') }}")
            .then(res => res.json())
            .then(data => {
                let badge = document.getElementById('admin-support-badge-recruiter');
                if (badge) {
                    badge.innerText = data.count;
                    badge.classList.toggle('hidden', data.count == 0);
                }
            });
    }

    // Mark messages as read
    function markMessagesAsReadRecruiter() {
        fetch("{{ route('recruiter.markMessagesSeen') }}", {
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
                let badge = document.getElementById('admin-support-badge-recruiter');
                if (badge) {
                    badge.innerText = 0;
                    badge.classList.add('hidden');
                }
            }
        });
    }


</script>