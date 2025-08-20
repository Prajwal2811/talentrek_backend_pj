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
                        <h2 class="text-2xl font-semibold mb-6">Admin support</h2>

                        <!-- Chat Card -->
                        <div class="bg-white rounded-lg shadow p-4 flex flex-col h-[600px]">

                            <!-- Header -->
                            <div class="flex items-center space-x-4 border-b pb-4 mb-4">
                                <img src="https://i.pravatar.cc/100?img=3" alt="Avatar" class="w-12 h-12 rounded-full object-cover">
                                <div>
                                    <h3 class="text-md font-semibold">Talentrek admin support</h3>
                                    <p class="text-gray-500 text-sm">Ask anything about recruitment</p>
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



                   <!-- jQuery -->
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                    <!-- Pusher -->
                    <script src="https://js.pusher.com/8.0/pusher.min.js"></script>

                    <!-- Laravel Echo (CDN build) -->
                    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.0/dist/echo.iife.js"></script>

                    <script>
                        function appendMessage(message) {
                            let alignment = message.is_self ? 'justify-end' : 'justify-start';
                            let bubbleClass = 'bg-gray-200 text-black';

                            if (message.sender_type === 'assessor' || message.sender_type === 'coach') {
                                bubbleClass = 'bg-blue-100 text-black';
                            }

                            let html = `
                                <div class="flex ${alignment}">
                                    <div class="${bubbleClass} p-2 rounded max-w-xs break-words">
                                        ${message.type == 1 
                                            ? message.message 
                                            : `<a href="${message.message}" target="_blank">📎 File</a>`}
                                        <div class="text-xs text-gray-500 mt-1">${message.created_at}</div>
                                    </div>
                                </div>`;

                            $('#chatMessages').append(html);
                            $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
                        }

                        // Fetch existing messages
                        $.get('{{ route("coach.group.chat.fetch") }}', function(messages) {
                            messages.forEach(msg => {
                                msg.is_self = (msg.sender_id == '{{ auth()->id() }}'); 
                                appendMessage(msg);
                            });
                        });

                        // Send message via AJAX
                        $('#sendBtn').click(function() {
                            let formData = new FormData();
                            formData.append('message', $('#chatInput').val());
                            let file = $('#fileInput')[0].files[0];
                            if(file) formData.append('file', file);

                            $.ajax({
                                url: '{{ route("coach.group.chat.send") }}',
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                                success: function(res) {
                                    res.is_self = true;
                                    appendMessage(res);
                                    $('#chatInput').val('');
                                    $('#fileInput').val('');
                                },
                                error: function(err) {
                                    alert(err.responseJSON.error);
                                }
                            });
                        });
                        let currentUserId = '{{ auth()->guard("coach")->id() }}';
                        window.Echo = new Echo({
                            broadcaster: 'pusher',
                            key: '18bff0f2c88aa583c6d7',
                            cluster: 'ap2', // 👈 yeh add karo
                            wsHost: window.location.hostname,
                            wsPort: 6001,
                            forceTLS: false,
                            enabledTransports: ['ws', 'wss'],
                            authEndpoint: '/broadcasting/auth',
                            auth: {
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            }
                        });



                      
                        // Echo.channel('chat.coach')
                        //     .listen('.message.sent', (e) => {
                        //         this.appendMessage(e);
                        //     });
                        Echo.channel('chat.coach')
                        .listen('.message.sent', (e) => {
                            if (parseInt(e.sender_id) !== parseInt(this.currentUserId)) {
                                this.receiveMessage(e);
                            }
                        });
                    </script>

                    <!-- Tailwind (⚠️ dev only, for production build with CLI/PostCSS) -->
                    <script src="https://cdn.tailwindcss.com"></script>
                    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>






            </div>
        </div>

      


    </div>
           



          
@include('site.coach.componants.footer')
