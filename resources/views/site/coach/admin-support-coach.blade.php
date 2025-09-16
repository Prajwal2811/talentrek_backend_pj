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


    <div class="page-wraper">
        <div class="flex h-screen">
             @include('site.coach.componants.sidebar')

            <div class="flex-1 flex flex-col">
                @include('site.coach.componants.navbar')

                    <main class="p-6 bg-gray-100 flex-1 overflow-y-auto">
                        <h2 class="text-2xl font-semibold mb-6">{{ langLabel('admin_support') }}</h2>

                        <!-- Chat Card -->
                        <div class="bg-white rounded-lg shadow p-4 flex flex-col h-[600px]">

                            <!-- Header -->
                            <div class="flex items-center space-x-4 border-b pb-4 mb-4">
                                <img src="https://i.pravatar.cc/100?img=3" alt="Avatar" class="w-12 h-12 rounded-full object-cover">
                                <div>
                                    <h3 class="text-md font-semibold">Talentrek {{ langLabel('admin_support') }}</h3>
                                    <p class="text-gray-500 text-sm">{{ langLabel('ask_anything') }}</p>
                                </div>
                            </div>

                            <!-- Chat Messages -->
                            <div id="chatMessages" class="flex-1 space-y-4 overflow-y-auto pr-2">
                                <!-- JS will insert messages here -->
                            </div>

                            <!-- Chat Input -->
                            <div class="pt-4 mt-4 border-t flex items-center space-x-2">
                                <input
                                    type="text"
                                    id="chatInput"
                                    placeholder="Write your message here..."
                                    class="flex-1 px-4 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-200"
                                />

                                <label class="p-2 text-gray-500 hover:text-gray-700 rounded-full cursor-pointer">
                                    <i class="fas fa-paperclip"></i>
                                    <input type="file" id="fileInput" class="hidden" />
                                </label>

                                <button id="sendBtn" class="p-2 text-white bg-blue-600 hover:bg-blue-700 rounded-full">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </main>



                   <!-- Scripts -->
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.js"></script>

                    <meta name="csrf-token" content="{{ csrf_token() }}">

                    <script>
                        let authUserId = "{{ auth()->id() }}";

                        // ✅ Laravel Echo setup
                        window.Echo = new Echo({
                            broadcaster: 'pusher',
                            key: '{{ env("PUSHER_APP_KEY") }}',
                            wsHost: window.location.hostname,
                            wsPort: 6001,
                            forceTLS: false,
                            enabledTransports: ['ws', 'wss'],
                            authEndpoint: '/broadcasting/auth',
                            withCredentials: true,
                            auth: {
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            }
                        });

                        // ✅ Page load → fetch old messages
                        $(document).ready(function () {
                            $.ajax({
                                url: "{{ route('coach.group.chat.fetch') }}",
                                method: "GET",
                                success: function (res) {
                                    $("#chatMessages").html('');
                                    res.forEach(msg => appendMessage(msg));
                                }
                            });

                            // File preview
                            $("#fileInput").on('change', function () {
                                let file = this.files[0];
                                if (file) $("#chatInput").val(file.name);
                            });

                            // File upload trigger
                            $("#fileBtn").click(function () {
                                $("#fileInput").click();
                            });

                            // ✅ Send message
                            $("#sendBtn").click(function () {
                                let msg = $("#chatInput").val();
                                let file = $("#fileInput")[0].files[0];
                                if (!msg.trim() && !file) return;

                                let formData = new FormData();
                                formData.append('_token', "{{ csrf_token() }}");

                                if (file) formData.append('file', file);
                                else formData.append('message', msg);

                                $.ajax({
                                    url: "{{ route('coach.group.chat.send') }}",
                                    method: "POST",
                                    data: formData,
                                    contentType: false,
                                    processData: false,
                                    success: function (res) {
                                        appendMessage({ ...res, sender_id: authUserId });
                                        $("#chatInput").val('');
                                        $("#fileInput").val('');
                                    }
                                });
                            });
                        });

                        // ✅ Listen realtime channel
                        Echo.channel('chat.coach')
                            .listen('.message.sent', (e) => {
                                if (e.sender_id != authUserId) appendMessage(e);
                            });

                        // ✅ Append message function
                        function appendMessage(msg) {
                            let isSelf = (msg.sender_id == authUserId);
                            let content = '';

                            if (msg.type == 2) {
                                let cleanPath = msg.message.split('?')[0].split('#')[0];
                                let fileName = decodeURIComponent(cleanPath.split('/').pop());
                                let ext = fileName.split('.').pop().toLowerCase();

                                if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
                                    content = `<img src="${msg.message}" style="max-width:250px; border-radius:6px;" />`;
                                } else {
                                    let iconPath = '';
                                    if (ext === 'pdf') {
                                        iconPath = 'https://cdn-icons-png.flaticon.com/512/337/337946.png';
                                    } else if (ext === 'doc' || ext === 'docx') {
                                        iconPath = 'https://cdn-icons-png.flaticon.com/512/281/281760.png';
                                    } else {
                                        iconPath = 'https://cdn-icons-png.flaticon.com/512/2991/2991112.png';
                                    }

                                    content = `
                                        <a href="${msg.message}" target="_blank" style="
                                            display: flex;
                                            align-items: center;
                                            background: white;
                                            border: 2px solid #1e90ff;
                                            border-radius: 10px;
                                            padding: 8px 12px;
                                            text-decoration: none;
                                            color: black;
                                            font-weight: bold;
                                            max-width: 250px;
                                            gap: 10px;
                                        ">
                                            <img src="${iconPath}" style="width: 30px; height: 30px;">
                                            <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${fileName}</span>
                                        </a>
                                    `;
                                }
                            } else {
                                content = msg.message;
                            }

                            let html = `
                                <div class="flex ${isSelf ? 'justify-end' : 'justify-start'} mb-2">
                                    <div class="p-2 rounded max-w-xs break-words ${isSelf ? 'bg-blue-100 text-black' : 'bg-gray-200 text-black'}">
                                        ${content}
                                        <div class="text-xs text-gray-500 mt-1">
                                            ${new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                                        </div>
                                    </div>
                                </div>
                            `;

                            $('#chatMessages').append(html);
                            $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
                        }
                    </script>

                    <!-- Tailwind (⚠️ dev only, for production build with CLI/PostCSS) -->
                    <script src="https://cdn.tailwindcss.com"></script>
                    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>






            </div>
        </div>

      


    </div>
           



          
@include('site.coach.componants.footer')
