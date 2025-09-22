<nav class="bg-white shadow-md px-6 py-3 flex items-center justify-between">
                    <div class="flex items-center space-x-6 w-1/2">
                        <button 
                            @click="sidebarOpen = !sidebarOpen" 
                            class="text-gray-700 hover:text-blue-600 focus:outline-none"
                            title="Toggle Sidebar"
                            aria-label="Toggle Sidebar"
                            type="button"
                            >
                            <i data-feather="menu" class="w-6 h-6"></i>
                        </button>
                    <!-- <div class="relative w-full">
                        <input type="text" placeholder="Search for talent" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                            <i class="fas fa-search"></i>
                        </button>
                    </div> -->
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative"> 
                            @php $notifications = notificationUsersSent('assessor'); @endphp
                            <button onclick="toggleBellDropdown()"
                                class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-700 text-white">
                                <span><i data-feather="bell" class="w-4 h-4"></i></span>
                                @if($notifications->count() > 0) 
                                    <span class="absolute top-0 right-0 inline-block w-2.5 h-2.5 bg-red-600 rounded-full border-2 border-white"></span> 
                                @endif
                            </button>

                            <div id="bellDropdown"
                                class="hidden absolute right-4 top-full mt-2 w-56 bg-white border border-gray-200 rounded shadow-lg z-50" style="width: 305px;">
                                <a href=""
                                    class="block px-4 py-2 text-sm text-gray-700 bg-blue-700 border-blue-200 text-white">{{ langLabel('notifications') }} <span class="float-right">{{ langLabel('view_all') }}</span></a>
                                @foreach($notifications as $notification)
                                    <a href=""
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"> {{ $notification->message }} <small class="float-right">{{ date('d-m-y H:s A',strtotime($notification->created_at)) }}</small></a>
                                    <hr>
                                    <!-- <div class="clearfix">...</div> -->
                                @endforeach
                                @if($notifications->count() < 1) 
                                    <a href=""
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">{{ langLabel('view_records_found') }}</a>
                                @endif
                            </div>
                        </div>

                        <script>
                            function toggleBellDropdown() {
                                const dropdown = document.getElementById('bellDropdown');
                                dropdown.classList.toggle('hidden');
                            }

                            document.addEventListener('click', function (e) {
                                const dropdown = document.getElementById('bellDropdown');
                                const button = e.target.closest('button[onclick="toggleBellDropdown()"]');

                                if (!dropdown.contains(e.target) && !button) {
                                    dropdown.classList.add('hidden');
                                }
                            });
                        </script>
                        <div class="relative inline-block">
                            <form method="POST" action="{{ route('change.language') }}">
                                @csrf
                                    <select
                                        class="appearance-none border border-gray-300 rounded-md px-10 py-1 text-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-600" name="lang" id="lang" onchange="this.form.submit()">
                                        <option value="english" {{ session('lang', 'english') == 'english' ? 'selected' : '' }}>English</option>

                                        <option value="arabic" {{ session('lang', 'english') == 'arabic' ? 'selected' : '' }}>Arabic</option>
                                    </select>
                            </form>
                        
                        <span class="pointer-events-none absolute left-2 top-1/2 transform -translate-y-1/2 inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-600 text-white">
                            <i class="feather-globe"></i>
                        </span>
                        </div>
                    <div>
                        <a href="#" role="button"
                            class="inline-flex items-center space-x-1 border border-blue-600 bg-blue-600 text-white rounded-md px-3 py-1.5 transition">
                        <i class="fa fa-user-circle" aria-hidden="true"></i>
                            <span>{{ Auth::user()->name }}</span>

                        </a>
                    </div>
                    </div>
                </nav>
                   <!-- Feather Icons -->
        <script>
            feather.replace()
        </script>
        