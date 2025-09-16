<?php
    $coachId = auth()->guard('coach')->id();

    // $jobseekersList = DB::table('jobseeker_saved_booking_session as p')
    //     ->join('jobseekers as j', 'p.jobseeker_id', '=', 'j.id')
    //     ->leftJoin('additional_info as ai', function($join) {
    //         $join->on('ai.user_id', '=', 'j.id')
    //             ->where('ai.user_type', 'jobseeker')
    //             ->where('ai.doc_type', 'profile_picture');
    //     })
    //     ->where('p.user_id', $coachId)
    //     ->where('p.user_type', 'coach')
    //     ->where('p.status', 'confirmed')
    //     ->where('p.admin_status', 'approved')
    //     ->select(
    //         'j.id as jobseeker_id',
    //         'j.name as jobseeker_name',
    //         'ai.document_path as profile_picture'
    //     )
    //     ->distinct()
    //     ->get();
        
    $jobseekersList = DB::table('jobseeker_saved_booking_session as p')
    ->join('jobseekers as j', 'p.jobseeker_id', '=', 'j.id')
    ->leftJoin('additional_info as ai', function($join) {
        $join->on('ai.user_id', '=', 'j.id')
            ->where('ai.user_type', 'jobseeker')
            ->where('ai.doc_type', 'profile_picture');
    })
    ->where('p.user_id', $coachId)
    ->where('p.user_type', 'coach')
    ->where('p.status', 'confirmed')
    ->where('p.admin_status', 'approved')
    ->select(
        'j.id as jobseeker_id',
        'j.name as jobseeker_name',
        'ai.document_path as profile_picture'
    )
    ->selectSub(function ($query) use ($coachId) {
        $query->from('messages as m')
            ->selectRaw('COUNT(*)')
            ->where('m.sender_type', 'jobseeker')
            ->where('m.receiver_id', $coachId)
            ->where('m.receiver_type', 'coach')
            ->where('m.is_read', 0)
            ->whereColumn('m.sender_id', 'j.id');
    }, 'unread_count')
    ->distinct()
    ->get();

    // echo "<pre>";
    // print_r( $jobseekersList );exit; 
    
?>



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

	@if($coachNeedsSubscription)
    @include('site.coach.subscription.index')
@endif
    <div class="page-wraper">
        <div class="flex h-screen">
              @include('site.coach.componants.sidebar')

            <div class="flex-1 flex flex-col">
                @include('site.coach.componants.navbar')

                <main class="p-6 ">
                    <h2 class="text-2xl font-semibold mb-6">Message</h2>
                    @php
                        $jobseekers = $jobseekersList->map(function($j) {
                            return [
                                'jobseeker_id' => $j->jobseeker_id,
                                'jobseeker_name' => $j->jobseeker_name,
                                'unread_count' => $j->unread_count ?? 0,
                                'avatar' => $j->profile_picture ?? 'https://via.placeholder.com/100',
                            ];
                        });
                    @endphp

                    <div x-data="coachChat()" x-init="initEcho()" class="grid grid-cols-3 gap-4 h-[calc(100vh-100px)] p-4">

                        <!-- ✅ Contacts Sidebar -->
                        <div class="bg-white rounded-lg shadow overflow-y-auto col-span-1 h-[calc(100vh-150px)]">
                            <template x-for="jobseeker in jobseekersList" :key="jobseeker.jobseeker_id">
                                <div
                                    class="border-b p-4 flex items-center gap-3 cursor-pointer transition-colors duration-200"
                                    :class="{
                                        'bg-blue-100': activeContact && activeContact.jobseeker_id === jobseeker.jobseeker_id,
                                        'bg-gray-50': jobseeker.unread_count > 0 && (!activeContact || activeContact.jobseeker_id !== jobseeker.jobseeker_id),
                                        'hover:bg-gray-100': !(activeContact && activeContact.jobseeker_id === jobseeker.jobseeker_id)
                                    }"
                                    @click="openChat(jobseeker)"
                                >
                                    <img :src="jobseeker.avatar" class="w-10 h-10 rounded-full object-cover" alt="User">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-sm" x-text="jobseeker.jobseeker_name"></h4>
                                        <p class="text-xs text-gray-500 truncate">Click to start chat...</p>
                                    </div>
                                    <div class="text-xs text-gray-400" x-text="new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true })"></div>
                                    <span class="ml-2 bg-red-500 text-white rounded-full px-2 py-0.5 text-xs"
                                        x-show="jobseeker.unread_count > 0"
                                        x-text="jobseeker.unread_count"></span>
                                </div>
                            </template>
                        </div>


                        <!-- ✅ Chat Panel -->
                        <div class="bg-white rounded-lg shadow overflow-y-auto col-span-2 flex flex-col h-full h-[calc(100vh-150px)]">

                            <template x-if="activeContact">
                                <div class="flex flex-col h-full">
                                    <!-- Header -->
                                    <div class="border-b p-4 flex items-center gap-3 bg-gray-50">
                                        <img :src="activeContact.avatar" class="w-10 h-10 rounded-full object-cover" alt="User">
                                        <div>
                                            <h4 class="font-semibold text-sm" x-text="activeContact.jobseeker_name"></h4>
                                            <p class="text-xs text-gray-500">Online</p>
                                        </div>
                                    </div>

                                    <!-- Messages -->
                                    <div class="flex-1 p-4 space-y-3 overflow-y-auto bg-white h-[calc(100vh-280px)] scroll-hidden"
                                        x-ref="chatContainer"
                                        x-init="$watch('activeContact.messages', () => { scrollToBottom() })">
                                        <template x-for="(msg, index) in activeContact.messages" :key="index">
                                            <div class="flex items-end gap-2" :class="msg.sender === 'me' ? 'justify-end' : 'justify-start'">
                                                <img x-show="msg.sender !== 'me'" :src="activeContact.avatar" class="w-8 h-8 rounded-full object-cover">
                                                <div :class="msg.sender === 'me'
                                                            ? 'bg-blue-100 text-black rounded-bl-lg rounded-tl-lg rounded-tr-2xl'
                                                            : 'bg-gray-200 text-gray-800 rounded-br-lg rounded-tr-lg rounded-tl-2xl'"
                                                    class="p-2 text-sm max-w-[70%] break-words shadow-md"
                                                    x-html="msg.html"></div>
                                                <img x-show="msg.sender === 'me'" :src="coachImage" class="w-8 h-8 rounded-full object-cover">
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Input -->
                                    <div class="p-4 border-t flex items-center gap-3 bg-gray-50">
                                        <input type="text" placeholder="Write your message..." class="flex-1 border border-gray-300 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            x-model="newMessage"
                                            @keyup.enter="sendMessage">
                                        <label class="cursor-pointer bg-gray-200 p-2 rounded-full hover:bg-gray-300">
                                            <i class="fas fa-paperclip text-gray-600"></i>
                                            <input type="file" class="hidden" @change="handleFileUpload">
                                        </label>
                                        <button class="bg-blue-300 hover:bg-blue-400 text-black p-2 rounded-full transition" @click="sendMessage">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <template x-if="!activeContact">
                                <div class="flex flex-col items-center justify-center flex-1 text-center text-gray-500 px-6">
                                    <img src="https://cdn-icons-png.flaticon.com/512/2462/2462719.png" class="w-24 h-24 mb-6 opacity-50" />
                                    <h3 class="text-lg font-semibold mb-2">Welcome to the coach Chat Panel</h3>
                                    <p class="text-sm text-gray-400">Select a contact from the left panel to view and send messages.</p>
                                </div>
                            </template>

                        </div>

                    </div>

                    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.js"></script>
                    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

                    <script>
                        window.coachChat = function () {
                            return {
                                coachImage: "{{ $coachImage->document_path ?? 'https://via.placeholder.com/40' }}",
                                currentUserId: {{ auth()->guard('coach')->id() }},
                                newMessage: '',
                                activeContact: null,
                                selectedFile: null,
                                jobseekersList: @json($jobseekers),

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

                                    Echo.channel('chat.coach')
                                        .listen('.message.sent', (e) => {
                                            if (parseInt(e.sender_id) !== parseInt(this.currentUserId)) {
                                                this.receiveMessage(e);
                                            }
                                        });
                                },

                                openChat(contact) {
                                    this.activeContact = { ...contact, messages: [] };
                                    this.getMessages(contact.jobseeker_id, 'jobseeker');

                                    // reset unread count
                                    let job = this.jobseekersList.find(j => j.jobseeker_id == contact.jobseeker_id);
                                    if (job) job.unread_count = 0;
                                },

                                getMessages(receiverId, receiverType) {
                                    fetch(`/coach/chat/messages?receiver_id=${receiverId}&receiver_type=${receiverType}`)
                                        .then(res => res.json())
                                        .then(messages => {
                                            this.activeContact.messages = messages.map(msg => ({
                                                html: this.formatMessage(msg.message, msg.type, msg.created_at),
                                                sender: msg.sender_id == this.currentUserId ? 'me' : 'them'
                                            }));
                                            this.scrollToBottom();
                                        });
                                },

                                sendMessage() {
                                    if (!this.newMessage.trim() && !this.selectedFile) return;

                                    let formData = new FormData();
                                    formData.append('receiver_id', this.activeContact.jobseeker_id);
                                    formData.append('receiver_type', 'jobseeker');
                                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                                    formData.append('message', this.newMessage.trim());
                                    if (this.selectedFile) formData.append('file', this.selectedFile);

                                    fetch('/coach/chat/send', { method: 'POST', body: formData })
                                        .then(res => res.json())
                                        .then(data => {
                                            this.activeContact.messages.push({
                                                html: this.formatMessage(data.file_url || data.message, data.type, data.created_at),
                                                sender: 'me'
                                            });
                                            this.newMessage = '';
                                            this.selectedFile = null;
                                            this.scrollToBottom();
                                        });
                                },

                                receiveMessage(e) {
                                    let job = this.jobseekersList.find(j => j.jobseeker_id == e.sender_id);
                                    if (this.activeContact && this.activeContact.jobseeker_id == e.sender_id) {
                                        this.activeContact.messages.push({
                                            html: this.formatMessage(e.message, e.type, e.created_at),
                                            sender: 'them'
                                        });
                                        this.scrollToBottom();
                                    } else if (job) {
                                        job.unread_count += 1;
                                    }
                                },

                                formatMessage(message, type, createdAt = null) {
                                    let content = '';
                                    if (type == 2) {
                                        let cleanPath = message.split('?')[0].split('#')[0];
                                        let fileName = decodeURIComponent(cleanPath.split('/').pop());
                                        let ext = fileName.split('.').pop().toLowerCase();

                                        if (['jpg','jpeg','png','gif','webp'].includes(ext)) {
                                            content = `<a href="${message}" target="_blank"><img src="${message}" style="max-width:150px;border-radius:6px;"></a>`;
                                        } else {
                                            let icon = ext === 'pdf' ? 'https://cdn-icons-png.flaticon.com/512/337/337946.png'
                                                    : (['doc','docx'].includes(ext) ? 'https://cdn-icons-png.flaticon.com/512/281/281760.png'
                                                    : 'https://cdn-icons-png.flaticon.com/512/2991/2991112.png');
                                            content = `<a href="${message}" target="_blank" class="file-message" style="display:flex;align-items:center;gap:10px;border:2px solid #1e90ff;border-radius:10px;padding:8px 12px;text-decoration:none;color:black;font-weight:bold;max-width:250px;">
                                                        <img src="${icon}" style="width:30px;height:30px;"><span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:180px;">${fileName}</span>
                                                    </a>`;
                                        }
                                    } else content = `<span>${message}</span>`;

                                    let time = createdAt ? new Date(createdAt).toLocaleTimeString([], { hour:'2-digit',minute:'2-digit',hour12:true }) : '';
                                    return `<div>${content}<div class="text-xs text-gray-500 mt-1">${time}</div></div>`;
                                },

                                handleFileUpload(e) {
                                    const file = e.target.files[0];
                                    if(file){ this.selectedFile = file; this.newMessage = file.name; }
                                },

                                scrollToBottom() {
                                    this.$nextTick(() => {
                                        const c = this.$refs.chatContainer;
                                        if(c) c.scrollTop = c.scrollHeight;
                                    });
                                }
                            }
                        }
                    </script>

                </main>


                <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

            </div>
        </div>

        <!-- Feather Icons -->
        <script>
            feather.replace()
        </script>


    </div>
           

@include('site.coach.componants.footer')