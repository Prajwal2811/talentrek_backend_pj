<?php
    $trainerId = auth()->guard('trainer')->id();
        
    $jobseekersList = DB::table('jobseeker_training_material_purchases as p')
        ->join('jobseekers as j', 'p.jobseeker_id', '=', 'j.id')
        ->leftJoin('additional_info as ai', function($join) {
            $join->on('ai.user_id', '=', 'j.id')
                ->where('ai.user_type', '=', 'jobseeker')
                ->where('ai.doc_type', '=', 'profile_picture');
        })
        ->where('p.trainer_id', $trainerId)
        ->select(
            'j.id as jobseeker_id', 
            'j.name as jobseeker_name', 
            'ai.document_path as profile_picture'
        )
        ->distinct()
        ->get();
    //     echo "<pre>";
    // print_r($jobseekersList);exit;
    $trainerImage = App\Models\AdditionalInfo::where('doc_type', 'trainer_profile_picture')
        ->where('user_id', auth()->user()->id)
        ->first();
    //  echo "<pre>";
    //  print_r($trainerImage);exit;                                                
?>

@include('site.componants.header')
<style>
    /* Chrome, Safari, Edge, Opera */
    .scroll-hidden::-webkit-scrollbar {
        display: none;
    }

    /* Firefox */
    .scroll-hidden {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

</style>
<style>
    .chat-image {
        max-width: 200px;
        border-radius: 8px;
        display: block;
    }
    .chat-file-link {
        color: #007bff;
        text-decoration: none;
    }
    .chat-file-link:hover {
        text-decoration: underline;
    }


    .chat-image {
        max-width: 200px;
        max-height: 200px;
        border-radius: 8px;
        display: block;
    }
    .chat-file-link {
        color: #2563eb;
        text-decoration: underline;
    }

</style>
<body>

    <div class="loading-area">
        <div class="loading-box"></div>
        <div class="loading-pic">
            <div class="wrapper">
                <div class="cssload-loader"></div>
            </div>
        </div>
    </div>

	 @if($trainerNeedsSubscription)
        @include('site.trainer.subscription.index')
    @endif
    
    <div class="page-wraper">
        <div class="flex h-screen" x-data="{ sidebarOpen: true }" x-init="$watch('sidebarOpen', () => feather.replace())">
          
            @include('site.trainer.componants.sidebar')

            <div class="flex-1 flex flex-col">
                @include('site.trainer.componants.navbar')

            <main class="p-6 " x-data="chatApp()">
                <h2 class="text-2xl font-semibold mb-6">{{ langLabel('message') }}</h2>
                <div x-data="trainerChat()" x-init="initEcho()" class="grid grid-cols-3 gap-4 h-[calc(100vh-100px)] p-4">
    
                    <!-- ✅ Contacts Sidebar -->
                    <!-- <div class="bg-white rounded-lg shadow overflow-y-auto col-span-1"> -->
                    <div class="bg-white rounded-lg shadow overflow-y-auto col-span-1 h-[calc(100vh-150px)]">

                        @foreach ($jobseekersList as $jobseeker)
                            @php $avatar = $jobseeker->profile_picture ?? 'https://via.placeholder.com/100'; @endphp

                            <div 
                                class="border-b p-4 flex items-center gap-3 hover:bg-gray-50 cursor-pointer"
                                @click="openChat({ 
                                    id: {{ $jobseeker->jobseeker_id }}, 
                                    name: '{{ addslashes($jobseeker->jobseeker_name) }}', 
                                    type: 'jobseeker',
                                    avatar: '{{ $avatar }}', 
                                    status: 'Online' 
                                })">

                                <img src="{{ $avatar }}" class="w-10 h-10 rounded-full object-cover" alt="User">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-sm">{{ $jobseeker->jobseeker_name }}</h4>
                                    <p class="text-xs text-gray-500 truncate">{{ langLabel('click_start_chat') }}...</p>
                                </div>
                                <div class="text-xs text-gray-400">{{ now()->format('h:i A') }}</div>
                            </div>
                        @endforeach
                    </div>

                    <!-- ✅ Chat Panel -->
                    <div class="bg-white rounded-lg shadow overflow-y-auto col-span-2 scroll-hidden flex flex-col h-full h-[calc(100vh-150px)]">
                        
                        <!-- ✅ If contact selected -->
                        <template x-if="activeContact">
                            <div class="flex flex-col h-full">
                                <!-- Header -->
                                <div class="border-b p-4 flex items-center gap-3 bg-gray-50">
                                    <img :src="activeContact.avatar" class="w-10 h-10 rounded-full object-cover" alt="User">
                                    <div>
                                        <h4 class="font-semibold text-sm" x-text="activeContact.name"></h4>
                                        <p class="text-xs text-gray-500" x-text="activeContact.status"></p>
                                    </div>
                                    <div class="ml-auto text-xs text-gray-400" x-text="activeContact.time"></div>
                                </div>

                                <!-- Messages -->
                                 <!-- Messages -->
                                <div class="flex-1 p-4 space-y-3 overflow-y-auto bg-white h-[calc(100vh-280px)] scroll-hidden"
                                    x-ref="chatContainer"
                                    x-init="$watch('activeContact?.messages', () => { scrollToBottom(); })">

                                    <template x-for="(message, i) in activeContact?.messages || []" :key="i">
                                        <div class="flex items-end gap-2" :class="message.sender === 'me' ? 'justify-end' : 'justify-start'">

                                            <!-- Left avatar -->
                                            <img x-show="message.sender !== 'me'" :src="activeContact.avatar"
                                                class="w-8 h-8 rounded-full object-cover" alt="User">

                                            <!-- Message -->
                                            <div :class="message.sender === 'me'
                                                        ? 'bg-blue-100 text-black rounded-bl-lg rounded-tl-lg rounded-tr-2xl'
                                                        : 'bg-gray-200 text-gray-800 rounded-br-lg rounded-tr-lg rounded-tl-2xl'"
                                                class="p-2 text-sm max-w-[70%] break-words shadow-md"
                                                x-html="message.html">
                                            </div>

                                            <!-- Right avatar -->
                                            <img x-show="message.sender === 'me'" :src="trainerImage"
                                                class="w-8 h-8 rounded-full object-cover" alt="Trainer">
                                        </div>
                                    </template>
                                </div>


                                <!-- Input -->
                                <div class="p-4 border-t flex items-center gap-3 bg-gray-50">
                                    <input 
                                        type="text" 
                                        placeholder="{{ langLabel('write_message_here') }} ....." 
                                        class="flex-1 border border-gray-300 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        x-model="newMessage"
                                        @keyup.enter="sendMessage"
                                    />

                                    <label class="cursor-pointer bg-gray-200 p-2 rounded-full hover:bg-gray-300">
                                        <i class="fas fa-paperclip text-gray-600"></i>
                                        <input type="file" class="hidden" @change="handleFileUpload" />
                                    </label>

                                    <button class="bg-blue-100 hover:bg-blue-700 text-black p-2 rounded-full transition" @click="sendMessage">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </template>


                        <!-- ✅ Welcome Message (if no contact selected) -->
                        <template x-if="!activeContact">
                            <div class="flex flex-col items-center justify-center flex-1 text-center text-gray-500 px-6">
                                <img src="https://cdn-icons-png.flaticon.com/512/2462/2462719.png" class="w-24 h-24 mb-6 opacity-50" />
                                <h3 class="text-lg font-semibold mb-2">{{ langLabel('write_message_here') }}</h3>
                                <p class="text-sm text-gray-400">{{ langLabel('select_contact') }}</p>
                            </div>
                        </template>

                    </div>

                    
                    
                    <!-- Laravel Echo & Alpine.js Script -->
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.js"></script>
                    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

                    

                    <script>
                        window.trainerChat = function () {
                            return {
                                trainerImage: "{{ $trainerImage->document_path ?? 'https://via.placeholder.com/40' }}",
                                currentUserId: {{ auth()->guard('trainer')->id() }},
                                currentUserType: 'trainer',
                                newMessage: '',
                                activeContact: null,
                                selectedFile: null,

                                initEcho() {
                                    window.Echo = new Echo({
                                        broadcaster: 'pusher',
                                        key: '18bff0f2c88aa583c6d7',
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

                                    Echo.channel('chat.trainer')
                                        .listen('.message.sent', (e) => {
                                            if (parseInt(e.sender_id) !== parseInt(this.currentUserId)) {
                                                this.receiveMessage(e);
                                            }
                                        });
                                },

                                openChat(contact) {
                                    this.activeContact = { ...contact, messages: [] };
                                    this.getMessages(contact.id, contact.type);
                                },

                                getMessages(receiverId, receiverType) {
                                    fetch(`/trainer/chat/messages?receiver_id=${receiverId}&receiver_type=${receiverType}`)
                                        .then(res => res.json())
                                        .then(messages => {
                                            this.activeContact.messages = messages.map(msg => ({
                                                html: this.formatMessage(msg),   // ✅ full msg object pass
                                                sender: msg.sender_id == this.currentUserId ? 'me' : 'them'
                                            }));
                                            this.scrollToBottom();
                                        });
                                },

                                sendMessage() {
                                    if (!this.newMessage.trim() && !this.selectedFile) return;

                                    let formData = new FormData();
                                    formData.append('receiver_id', this.activeContact.id);
                                    formData.append('receiver_type', this.activeContact.type);
                                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                                    formData.append('message', this.newMessage.trim());

                                    if (this.selectedFile) {
                                        formData.append('file', this.selectedFile);
                                    }

                                    fetch('/trainer/chat/send', {
                                        method: 'POST',
                                        body: formData
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        this.activeContact.messages.push({
                                            html: this.formatMessage(data),  // ✅ full response object pass (contains created_at)
                                            sender: 'me'
                                        });
                                        this.newMessage = '';
                                        this.selectedFile = null;
                                        this.$refs.fileInput.value = '';
                                        this.scrollToBottom();
                                    });
                                },

                                receiveMessage(e) {
                                    if (this.activeContact && parseInt(this.activeContact.id) === parseInt(e.sender_id)) {
                                        this.activeContact.messages.push({
                                            html: this.formatMessage(e),  // ✅ full event data pass (contains created_at)
                                            sender: 'them'
                                        });
                                        this.scrollToBottom();
                                    }
                                },

                                formatMessage(msg) {
                                    let content = '';
                                    let type = msg.type;

                                    if (type == 2) {
                                        let cleanPath = msg.message.split('?')[0].split('#')[0];
                                        let fileName = decodeURIComponent(cleanPath.split('/').pop());
                                        let ext = fileName.split('.').pop().toLowerCase();

                                        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                                            content = `
                                                <a href="${msg.message}" target="_blank" style="display:inline-block;">
                                                    <img src="${msg.message}" alt="image" 
                                                        style="max-width:150px; border-radius:6px; display:block;" />
                                                </a>
                                            `;
                                        } else {
                                            let iconPath = '';
                                            if (ext === 'pdf') iconPath = 'https://cdn-icons-png.flaticon.com/512/337/337946.png';
                                            else if (['doc', 'docx'].includes(ext)) iconPath = 'https://cdn-icons-png.flaticon.com/512/281/281760.png';
                                            else iconPath = 'https://cdn-icons-png.flaticon.com/512/2991/2991112.png';

                                            content = `
                                                <a href="${msg.message}" target="_blank" class="file-message" style="
                                                    display: flex; align-items: center;
                                                    background: #fff; border: 2px solid #1e90ff;
                                                    border-radius: 10px; padding: 8px 12px;
                                                    text-decoration: none; color: #000; font-weight: bold;
                                                    max-width: 250px; gap: 10px;">
                                                    <img src="${iconPath}" alt="file" style="width: 30px; height: 30px;">
                                                    <span style="
                                                        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
                                                        max-width: 180px;">${fileName}</span>
                                                </a>
                                            `;
                                        }
                                    } else {
                                        content = `<span>${msg.message}</span>`;
                                    }

                                    // ✅ Time format (hh:mm AM/PM)
                                    let time = msg.created_at
                                        ? new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true })
                                        : '';

                                    return `
                                        <div>
                                            ${content}
                                            <div class="text-xs text-gray-500 mt-1">${time}</div>
                                        </div>
                                    `;
                                },

                                handleFileUpload(event) {
                                    const file = event.target.files[0];
                                    if (file) {
                                        this.selectedFile = file;
                                        this.newMessage = file.name;
                                    }
                                },

                                scrollToBottom() {
                                    this.$nextTick(() => {
                                        const container = this.$refs.chatContainer;
                                        if (container) {
                                            container.scrollTo({
                                                top: container.scrollHeight,
                                                behavior: 'smooth'
                                            });
                                        }
                                    });
                                }
                            };
                        }
                        </script>




                    <!-- Alpine Script -->
                    <script>
                        function chatApp() {
                            return {
                                trainerImage: "{{ $trainerImage->document_path ?? 'https://via.placeholder.com/40' }}", // ✅ trainer ki profile
                                contacts: [],
                                activeContact: null,
                                newMessage: '',
                                selectedFile: null,

                                selectContact(contact) {
                                    this.activeContact = contact;
                                    this.newMessage = '';
                                    this.selectedFile = null;
                                },

                                sendMessage() {
                                    if (!this.activeContact) return;

                                    if (this.newMessage.trim() !== '') {
                                        this.activeContact.messages.push({ sender: 'me', text: this.newMessage });
                                        this.newMessage = '';
                                    }

                                    if (this.selectedFile) {
                                        this.activeContact.messages.push({
                                            sender: 'me',
                                            text: `[File Attached: ${this.selectedFile.name}]`
                                        });
                                        this.selectedFile = null;
                                    }

                                    this.$nextTick(() => {
                                        const container = this.$refs.chatContainer;
                                        if (container) {
                                            container.scrollTo({
                                                top: container.scrollHeight,
                                                behavior: 'smooth'
                                            });
                                        }
                                    });
                                },

                                handleFileUpload(event) {
                                    const file = event.target.files[0];
                                    if (file) {
                                        this.selectedFile = file;
                                    }
                                }
                            };
                        }
                    </script>


                    <!-- <script>
                        window.trainerChat = function () {
                            return {
                                currentUserId: {{ auth()->guard('trainer')->id() }},
                                currentUserType: 'trainer',
                                newMessage: '',
                                activeContact: null,
                                selectedFile: null,
                                messagePollingInterval: null,

                                initEcho() {
                                    Pusher.logToConsole = true;
                                    window.Echo = new Echo({
                                        broadcaster: 'pusher',
                                        key: '18bff0f2c88aa583c6d7',
                                        wsHost: window.location.hostname,
                                        wsPort: 6001,
                                        wssPort: 6001,
                                        forceTLS: false,
                                        encrypted: false,
                                        enabledTransports: ['ws', 'wss'],
                                        authEndpoint: '/broadcasting/auth',
                                        auth: {
                                            headers: {
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                            }
                                        }
                                    });

                                    Echo.private(`chat.trainer.${this.currentUserId}`)
                                        .listen('.message.sent', (e) => {
                                            if (parseInt(e.sender_id) !== parseInt(this.currentUserId)) {
                                                this.receiveMessage(e);
                                            }
                                        });
                                },

                                openChat(contact) {
                                    this.activeContact = {
                                        ...contact,
                                        messages: [],
                                        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                                    };
                                    this.getMessages(contact.id, contact.type);

                                    this.startMessagePolling();
                                },

                                startMessagePolling() {
                                    this.messagePollingInterval = setInterval(() => {
                                        if (this.activeContact) {
                                            this.getMessages(this.activeContact.id, this.activeContact.type);
                                        }
                                    }, 5000); 
                                },

                                stopMessagePolling() {
                                    if (this.messagePollingInterval) {
                                        clearInterval(this.messagePollingInterval); 
                                        this.messagePollingInterval = null;
                                    }
                                },

                                getMessages(receiverId, receiverType) {
                                    fetch(`/trainer/chat/messages?receiver_id=${receiverId}&receiver_type=${receiverType}`)
                                        .then(res => res.json())
                                        .then(messages => {
                                            this.activeContact.messages = messages.map(msg => ({
                                                text: msg.message,
                                                sender: msg.sender_id == this.currentUserId ? 'me' : 'them'
                                            }));
                                            this.scrollToBottom();
                                        })
                                        .catch(err => {
                                            console.error('Error fetching messages:', err);
                                            alert('Could not load messages. Please try again.');
                                        });
                                },

                                sendMessage() {
                                    if (!this.newMessage.trim() || !this.activeContact) return;

                                    const payload = {
                                        receiver_id: this.activeContact.id,
                                        receiver_type: this.activeContact.type,
                                        message: this.newMessage,
                                        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    };

                                    fetch('/trainer/chat/send', {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json' },
                                        body: JSON.stringify(payload)
                                    })
                                    .then(res => res.json())
                                    .then(data => {
                                        this.activeContact.messages.push({
                                            text: this.newMessage,
                                            sender: 'me'
                                        });
                                        this.newMessage = '';
                                        this.scrollToBottom();
                                    })
                                    .catch(err => {
                                        console.error('Send error:', err);
                                        alert('Message sending failed.');
                                    });
                                },

                                receiveMessage(e) {
                                    if (this.activeContact && parseInt(this.activeContact.id) === parseInt(e.sender_id)) {
                                        this.activeContact.messages.push({
                                            text: e.message,
                                            sender: 'them'
                                        });
                                        this.scrollToBottom();
                                    }
                                },

                                scrollToBottom() {
                                    this.$nextTick(() => {
                                        const container = this.$refs.chatContainer;
                                        if (container) {
                                            container.scrollTop = container.scrollHeight;
                                        }
                                    });
                                },

                                handleFileUpload(event) {
                                    const file = event.target.files[0];
                                    if (file) {
                                        this.selectedFile = file;
                                        this.activeContact.messages.push({
                                            text: `[File Attached: ${file.name}]`,
                                            sender: 'me'
                                        });
                                    }
                                },

                                beforeDestroy() {
                                    this.stopMessagePolling();
                                }
                            };
                        }

                    </script>    -->

                </div>
            </main>


            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
          

            </div>
        </div>

        <!-- Feather Icons -->
        <script>
            feather.replace()
        </script>


    </div>
           



@include('site.trainer.componants.footer')