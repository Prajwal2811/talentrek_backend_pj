<?php
    // Fetch all trainers
    $trainers = DB::table('trainers')->get();

    foreach ($trainers as $trainer) {
        // Fetch profile picture from talentrek_additional_info
        $profile = DB::table('additional_info')
            ->where('user_id', $trainer->id)
            ->where('user_type', 'trainer')
            ->where('doc_type', 'trainer_profile_picture')
            ->first();

        $trainer->profile_picture = $profile ? $profile->document_path : null;

        // Fetch materials for each trainer
        $trainer->materials = DB::table('training_materials')
            ->where('trainer_id', $trainer->id)
            ->get();

        foreach ($trainer->materials as $material) {
            // Fetch documents for each material
            $material->documents = DB::table('training_materials_documents')
                ->where('training_material_id', $material->id)
                ->get();

            // Fetch batches for each material
            $material->batches = DB::table('training_batches')
                ->where('training_material_id', $material->id)
                ->get();
            
            $material->rating = DB::table('reviews')
                ->where('user_type', 'trainer')
                ->where('user_id', $trainer->id)
                ->where('trainer_material', $material->id)
                ->avg('ratings');

            $material->rating_count = DB::table('reviews')
                ->where('user_type', 'trainer')
                ->where('user_id', $trainer->id)
                ->where('trainer_material', $material->id)
                ->count();

            $material->reviews = DB::table('reviews')
                ->where('user_type', 'trainer')
                ->where('user_id', $trainer->id)
                ->where('trainer_material', $material->id)
                ->select('ratings', 'reviews')
                ->get();  
        }
    }

    $jobseekerId = auth()->guard('jobseeker')->id();
    //All Trainer chat list with perticular jobseeker
    $trainersList = DB::table('jobseeker_training_material_purchases as p')
        ->join('trainers as t', 'p.trainer_id', '=', 't.id')
        ->leftJoin('additional_info as ai', function($join) {
            $join->on('ai.user_id', '=', 't.id')
                ->where('ai.user_type', '=', 'trainer')
                ->where('ai.doc_type', '=', 'profile_picture');
        })
        ->where('p.jobseeker_id', $jobseekerId)
        ->select(
            't.id as trainer_id', 
            't.name as trainer_name', 
            'ai.document_path as profile_picture'
        )
        ->distinct()
        ->get();
     //All Mentor chat list with perticular jobseeker
    $mentorsList = DB::table('jobseeker_saved_booking_session as p')
    ->join('mentors as m', 'p.user_id', '=', 'm.id')
    ->leftJoin('additional_info as ai', function($join) {
            $join->on('ai.user_id', '=', 'm.id')
                ->where('ai.user_type', '=', 'mentor')
                ->where('ai.doc_type', '=', 'mentor_profile_picture');
        })
        ->where('p.jobseeker_id', $jobseekerId)
        ->select(
            'm.id as user_id', 
            'm.name as mentor_name', 
            'ai.document_path as profile_picture'
        )
        ->distinct()
    ->get();
    // echo "<pre>";
    // print_r($mentorsList);exit;

?>


@include('site.componants.header')



<!-- CHAT MODULE CSS START -->

<style>
    /* CSS */
    .chat-button {
    position: fixed;
    bottom: 20px;
    right: 70px;
    display: flex;
    align-items: center;
    z-index: 10000; /* ⬅️ Make sure it's higher than chatBox or chatModal */
        cursor: pointer;
    }

    .chat-label {
    background-color: white;
    padding: 10px 20px;              /* increased padding */
    border-radius: 25px;             /* more rounded */
    font-weight: bold;
    font-size: 18px;                 /* larger text */
    margin-right: 8px;              /* more spacing */
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
    position: relative;
    z-index: 2;
    }

    .chat-icon {
    background-color: #e36a42;
    color: white;
    padding: 12px;                   /* more inner space */
    border-radius: 50% 50% 0% 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 50px;                    /* larger icon bubble */
    width: 50px;
    font-size: 22px;                 /* larger icon */
    position: relative;
    z-index: 1;
    }

    /* Chat Modal */
    .chat-modal {
    position: fixed;
    bottom: 90px;
    right: 30px;
    width: 360px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 0 20px rgba(0,0,0,0.2);
    overflow: hidden;
    z-index: 999;
    display: flex;
    flex-direction: column;
    max-height: 500px;
    min-height: 400px;
    }

    .hidden {
    display: none;
    }

    /* Tabs */
    .chat-tabs {
    display: flex;
    border-bottom: 1px solid #ddd;
    }

    .chat-tabs button {
    flex: 1;
    padding: 10px;
    border: none;
    background: none;
    font-weight: 600;
    cursor: pointer;
    color: #555;
    }

    .chat-tabs button.active {
    color: #007bff;
    border-bottom: 2px solid #007bff;
    }

    /* Chat List Area */
    .chat-lists {
    overflow-y: auto;
    flex: 1;
    }

    /* Each Chat User */
    .chat-user {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 12px;
    border-bottom: 1px solid #eee;
    }

    .chat-user img {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 10px;
    }

    .chat-text {
    flex: 1;
    margin-left: 10px;
    overflow: hidden;
    }

    .chat-name {
    font-weight: 600;
    font-size: 15px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    }

    .chat-message {
    font-size: 13px;
    color: #666;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    }

    .chat-time {
    font-size: 12px;
    color: #999;
    white-space: nowrap;
    margin-left: 8px;
    }

</style>
<style>
    .chat-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #007bff;
        color: white;
        border: none;
        padding: 15px;
        border-radius: 50%;
        font-size: 18px;
        box-shadow: 0 0 10px rgba(0,0,0,0.15);
        cursor: pointer;
        z-index: 9999;
    }

    .chat-modal {
        position: fixed;
        bottom: 80px;
        right: 20px;
        width: 350px;
        height: 500px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        z-index: 9999;
        display: flex;
        flex-direction: column;
    }

    .hidden {
        display: none;
    }

    .chat-tabs {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #ddd;
        background: #f6f6f6;
    }

    .chat-tabs button {
        flex: 1;
        padding: 10px;
        border: none;
        background: transparent;
        cursor: pointer;
        font-weight: bold;
    }

    .chat-tabs button.active {
        background: #007bff;
        color: white;
    }

    .chat-lists {
        flex: 1;
        overflow-y: auto;
        padding: 10px;
    }

    .chat-user {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }

    .chat-user img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        margin-right: 10px;
    }

    .chat-text {
        flex: 1;
    }

    .chat-name {
        font-weight: 600;
    }

    .chat-message {
        font-size: 13px;
        color: #777;
    }

    .chat-time {
        font-size: 12px;
        color: #999;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }
</style>
<style>
    .chat-box {
        position: fixed;
        bottom: 80px;
        right: 20px;
        width: 350px;
        height: 500px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2); /* ⬅️ Stronger shadow */
        z-index: 9999;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }


    .chat-box.hidden {
        display: none;
    }

    .chat-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        background: #f5f5f5;
        border-bottom: 1px solid #ddd;
    }

    .chat-header img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    .chat-name {
        font-weight: bold;
    }

    .chat-subtext {
        font-size: 12px;
        color: #666;
    }

    .chat-body {
        flex: 1;
        padding: 12px;
        overflow-y: auto;
        background: #fafafa;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .message {
        max-width: 70%;
        padding: 8px 12px;
        border-radius: 18px;
        font-size: 14px;
        line-height: 1.4;
    }

 


    .chat-input {
        display: flex;
        border-top: 1px solid #ddd;
    }

    .chat-input input {
        flex-grow: 1;
        border: none;
        padding: 10px;
        outline: none;
    }

    .chat-input button {
        padding: 10px 15px;
        border: none;
        background: #007bff;
        color: white;
        cursor: pointer;
    }

    .chat-header i {
        font-size: 18px;
        cursor: pointer;
        margin-right: 6px;
    }

    .chat-modal.hidden {
        display: none;
    }
</style>

<style>

    #chatMessages {
        display: flex;
        flex-direction: column;
        padding: 10px;
        background-color: #f9f9f9;
    }

    .chat-body-box {
        max-height: 400px;
        overflow-y: auto;
        scroll-behavior: smooth;
    }

    .chat-body-box {
        overflow-y: scroll;
        scrollbar-width: none; 
        -ms-overflow-style: none;  

        scroll-behavior: smooth;
    }

    .chat-body-box::-webkit-scrollbar {
        display: none;
    }



    .message {
        padding: 10px 15px;
        border-radius: 15px;
        max-width: 60%;
        word-wrap: break-word;
        margin: 5px 0;
        display: inline-block;
        font-size: 14px;
        line-height: 1.4;
    }

    .message.outgoing {
        background-color: #007bff;
        color: white;
        align-self: flex-end;
        text-align: left;
        border-top-left-radius: 0;
    }

    .message.incoming {
        background-color: #e4e6eb;
        color: #000;
        align-self: flex-start;
        text-align: left;
        border-top-right-radius: 0;
    }


</style>
<!-- CHAT MODULE CSS END -->
<body>
    <div class="loading-area">
        <div class="loading-box"></div>
        <div class="loading-pic">
            <div class="wrapper">
                <div class="cssload-loader"></div>
            </div>
        </div>
    </div>
	<style>
        .site-header.header-style-3.mobile-sider-drawer-menu {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: white;
        }
    </style>
      @include('site.componants.navbar')
        @php
            $bannerContent = App\Models\CMS::where('slug', 'web_banner')->first();
        @endphp
        <div class="page-content">
            <div class="relative bg-cover bg-no-repeat bg-center min-h-[750px]" style="background-image: url('{{ $bannerContent->file_path }}');">
                {{-- CMS Start form here --}}
                    @php
                        echo $bannerContent->description;
                    @endphp
                {{-- CMS end form here --}}
                <!-- Curved bottom image -->
                <div class="absolute bottom-0 left-0 w-full z-10 translate-y-[15px]">
                    <img src="{{ asset('asset/images/banner/curve-bottom.png') }}" alt="Curved Bottom" class="w-full h-auto" />
                </div>
            </div>


            <!-- Training Programs Section -->
            <section class="py-16 bg-white relative">
               
                <!-- Font Awesome CDN -->
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

                <!-- Chat Button -->
                <div class="chat-button" onclick="toggleChatModal()">
                    <div class="chat-label">Chat</div>
                    <div class="chat-icon">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                </div>

                <!-- Chat Modal -->
                <div id="chatModal" class="chat-modal hidden">
                    <div class="chat-tabs">
                        <button onclick="showTab('trainer')" class="active">Trainer</button>
                        <button onclick="showTab('mentor')">Mentor</button>
                        <button onclick="showTab('coach')">Coach</button>
                        <button onclick="showTab('assessor')">Assessor</button>
                    </div>

                    <!-- Chat Lists -->
                    <div class="chat-lists">
                        <!-- Trainer Tab -->
                        <div id="trainer" class="tab-content active">
                            @foreach($trainersList as $trainer)
                                <div class="chat-user" onclick="openChat({{ $trainer->trainer_id }}, 'trainer', '{{ $trainer->trainer_name }}', '{{ $trainer->profile_picture ?? asset('images/default-profile.png') }}')">
                                    <img src="{{ $trainer->profile_picture ?? asset('images/default-profile.png') }}" />
                                    <div class="chat-text">
                                        <div class="chat-name">{{ $trainer->trainer_name }}</div>
                                        <div class="chat-message">Click to start chat</div>
                                    </div>
                                    <div class="chat-time">{{ \Carbon\Carbon::now()->format('h:i A') }}</div>
                                </div>
                            @endforeach
                        </div>


                        <!-- Mentor Tab -->
                        <div id="mentor" class="tab-content">
                            @foreach($mentorsList as $mentor)
                                <div class="chat-user" onclick="openChat({{ $mentor->user_id }}, 'mentor', '{{ $mentor->mentor_name }}', '{{ $mentor->profile_picture ?? asset('images/default-profile.png') }}')">
                                    <img src="{{ $mentor->profile_picture ?? asset('images/default-profile.png') }}" />
                                    <div class="chat-text">
                                        <div class="chat-name">{{ $mentor->mentor_name }}</div>
                                        <div class="chat-message">Click to start chat</div>
                                    </div>
                                    <div class="chat-time">{{ \Carbon\Carbon::now()->format('h:i A') }}</div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Coach Tab -->
                        <div id="coach" class="tab-content">
                            <div class="chat-user">
                                <img src="https://randomuser.me/api/portraits/women/3.jpg" />
                                <div class="chat-text">
                                    <div class="chat-name">Coach Lucy</div>
                                    <div class="chat-message">Coach message sample</div>
                                </div>
                                <div class="chat-time">05:30 PM</div>
                            </div>
                        </div>

                        <!-- Assessor Tab -->
                        <div id="assessor" class="tab-content">
                            <div class="chat-user">
                                <img src="https://randomuser.me/api/portraits/men/4.jpg" />
                                <div class="chat-text">
                                    <div class="chat-name">Assessor Mike</div>
                                    <div class="chat-message">Assessor message sample</div>
                                </div>
                                <div class="chat-time">04:45 PM</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Window Box -->
                <!-- <div id="chatBox" class="chat-box hidden">
                    <div class="chat-header">
                        <i class="fa-solid fa-arrow-left" onclick="backToChatList()"></i>
                        <img id="chatProfile" src="" />
                        <div>
                            <div id="chatName" class="chat-name">Name</div>
                            <div id="chatSubtext" class="chat-subtext">Lorem ipsum sit dolor amet</div>
                        </div>
                        <div id="chatTime" class="chat-time">00:00 PM</div>
                    </div>
                    <div class="chat-body">
                        <div class="message incoming">Hello, How was your lecture</div>
                        <div class="message outgoing">Very easy to understand</div>
                    </div>
                    <div class="chat-input">
                        <input type="text" placeholder="Write your message here ....." />
                        <button id="sendBtn"><i class="fa-solid fa-paper-plane"></i></button>
                    </div>
                </div> -->
                <div id="chatBox" class="chat-box hidden">
                    <div class="chat-header">
                        <i class="fa-solid fa-arrow-left" onclick="backToChatList()"></i>
                        <img id="chatProfile" src="" />
                        <div>
                            <div id="chatName" class="chat-name">Name</div>
                            <div id="chatSubtext" class="chat-subtext">Lorem ipsum sit dolor amet</div>
                        </div>
                        <div id="chatTime" class="chat-time">00:00 PM</div>
                    </div>

                    <div class="chat-body chat-body-box"  id="chatBody">
                        <div id="chatMessages"></div> <!-- ✅ Important -->
                    </div>

                    <div class="chat-input">
                        <input type="hidden" id="receiver_id">
                        <input type="hidden" id="receiver_type">

                        <!-- Text input -->
                        <input type="text" id="message" placeholder="Write your message here ....." />

                        <!-- File select button -->
                        <label for="fileInput" class="file-label">
                            <i class="fa-solid fa-paperclip"></i>
                        </label>
                        <input type="file" id="fileInput" style="display: none;" />

                        <!-- Send button -->
                        <button id="sendBtn" class="send-btn">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </div>

                    <style>
                    .chat-input {
                        display: flex;
                        align-items: center;
                        padding: 8px;
                        background: #fff;
                        border-top: 1px solid #ddd;
                    }

                    .chat-input input[type="text"] {
                        flex: 1;
                        padding: 10px 12px;
                        border: 1px solid #ccc;
                        border-radius: 20px;
                        outline: none;
                    }

                    .file-label {
                        cursor: pointer;
                        font-size: 18px;
                        color: #555;
                        padding: 0 8px;
                    }

                    .file-label:hover {
                        color: #000;
                    }

                    .send-btn {
                        background: #007bff;
                        border: none;
                        color: #fff;
                        padding: 10px 14px;
                        border-radius: 50%;
                        cursor: pointer;
                        margin-left: 4px;
                        font-size: 16px;
                    }

                    .send-btn:hover {
                        background: #0069d9;
                    }
                    </style>


                </div>

                <!-- ========================================== -->
                    <!-- Laravel Echo -->
                <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.js"></script>
                <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
                <meta name="csrf-token" content="{{ csrf_token() }}">

                <script>
                    Pusher.logToConsole = true;

                    window.Echo = new Echo({
                        broadcaster: 'pusher',
                        key: '18bff0f2c88aa583c6d7',
                        wsHost: window.location.hostname,
                        wsPort: 6001,
                        wssPort: 6001,
                        forceTLS: false,
                        enableStats: false,
                        encrypted: false,
                        enabledTransports: ['ws', 'wss'],
                        authEndpoint: '/broadcasting/auth',
                        withCredentials: true,  // Ensure cookies are sent with the request
                        auth: {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        }
                    });

                </script>


                <script>
                   
                    const currentUserId = {{ auth()->guard('jobseeker')->id() }};
                    const currentUserType = 'jobseeker'; 
                    //console.log(currentUserId);
                    // Echo.private(`chat.jobseeker.${currentUserId}`)
                    Echo.channel('chat.jobseeker')
                        .error((err) => {
                            console.error('Subscription error:', err);
                        })
                        .listen('.message.sent', (e) => {
                            if (parseInt(e.sender_id) !== parseInt(currentUserId)) {
                                appendMessage(e);
                                scrollToBottom();
                            }
                        })
                        .listen('.message.deleted', (e) => {
                            $(`#message-${e.messageId}`).remove();
                        });

                    
                    // $('#sendBtn').on('click', function () {
                    //     const receiverId = $('#receiver_id').val();
                    //     const receiverType = $('#receiver_type').val();
                    //     const messageText = $('#message').val().trim();

                    //     if (!messageText) return;

                    //     $.ajax({
                    //         url: '/chat/send',
                    //         method: 'POST',
                    //         data: {
                    //             receiver_id: receiverId,
                    //             receiver_type: receiverType,
                    //             message: messageText,
                    //             _token: $('meta[name="csrf-token"]').attr('content')
                    //         },
                    //         success: function (res) {
                    //             $('#message').val('');

                    //             if (res && res.id) {
                    //                 appendMessage({
                    //                     id: res.id,
                    //                     sender_id: res.sender_id,
                    //                     sender_type: res.sender_type,
                    //                     message: res.message,
                    //                     created_at: res.created_at
                    //                 });
                    //                 getMessages(receiverId, receiverType);
                    //             } else {
                    //                 console.error('Unexpected response:', res);
                    //             }
                    //         },
                    //         error: function (xhr, status, error) {
                    //             console.error('Send error:', error);
                    //             alert('Message sending failed.');
                    //         }
                    //     });
                    // });

                    // Jab file select ho, uska naam input me dikhao
                    $('#fileInput').on('change', function () {
                        const file = this.files[0];
                        if (file) {
                            $('#message').val(file.name); // File ka naam text field me
                        }
                    });

                    // ✅ Send button click - Text + File ek hi request me jayenge
                    $('#sendBtn').on('click', function () {
                        const receiverId = $('#receiver_id').val();
                        const receiverType = $('#receiver_type').val();
                        const messageText = $('#message').val().trim();
                        const file = $('#fileInput')[0].files[0];

                        if (!messageText && !file) return; // Dono empty to kuch mat karo

                        const formData = new FormData();
                        formData.append('receiver_id', receiverId);
                        formData.append('receiver_type', receiverType);
                        formData.append('message', messageText);
                        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                        if (file) {
                            formData.append('file', file);
                        }

                        $.ajax({
                            url: '/chat/send', // 🔹 Backend me merged function ka route
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (res) {
                                $('#message').val('');
                                $('#fileInput').val('');

                                if (res && res.id) {
                                    appendMessage(res);
                                    scrollToBottom();
                                } else {
                                    console.error('Unexpected response:', res);
                                }
                            },
                            error: function (xhr) {
                                console.error('Send error:', xhr.responseText);
                                alert('Message sending failed.');
                            }
                        });
                    });


                    
                    function getMessages(receiverId, receiverType) {
                        $.get('/chat/messages', {
                            receiver_id: receiverId,
                            receiver_type: receiverType
                        }, function (messages) {
                            $('#chatMessages').html('');
                            messages.forEach(msg => appendMessage(msg));
                            scrollToBottom();
                        });
                    }

                
                    // function appendMessage(msg) {
                    //     const isOutgoing = parseInt(msg.sender_id) === parseInt(currentUserId);

                    //     const msgHtml = `
                    //         <div class="message ${isOutgoing ? 'outgoing' : 'incoming'}" id="message-${msg.id}">
                    //             ${msg.message}
                    //         </div>
                    //     `;
                    //     $('#chatMessages').append(msgHtml);
                    //     //scrollToBottom();
                    // }
                    function appendMessage(msg) {
                        const isOutgoing = parseInt(msg.sender_id) === parseInt(currentUserId);
                        let content = '';

                        if (msg.type == 2) {
                            // Clean path & get extension
                            let cleanPath = msg.message.split('?')[0].split('#')[0];
                            let fileName = decodeURIComponent(cleanPath.split('/').pop());
                            let ext = fileName.split('.').pop().toLowerCase();

                            let iconPath = '';
                            if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
                                // Directly show image
                                content = `<img src="${msg.message}" style="max-width:125px; border-radius:6px;" />`;
                            } else {
                                // Select icon based on file type
                                if (ext === 'pdf') {
                                    iconPath = 'https://cdn-icons-png.flaticon.com/512/337/337946.png'; // PDF icon
                                } else if (ext === 'doc' || ext === 'docx') {
                                    iconPath = 'https://cdn-icons-png.flaticon.com/512/281/281760.png'; // Word icon
                                } else {
                                    iconPath = 'https://cdn-icons-png.flaticon.com/512/2991/2991112.png'; // Generic file
                                }

                                // File card
                                content = `
                                    <a href="${msg.message}" target="_blank" class="file-message" style="
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
                                        <span style="
                                            white-space: nowrap;
                                            overflow: hidden;
                                            text-overflow: ellipsis;
                                        ">${fileName}</span>
                                    </a>
                                `;
                            }
                        } else {
                            // Text message
                            content = msg.message;
                        }

                        const msgHtml = `
                            <div class="message ${isOutgoing ? 'outgoing' : 'incoming'}" id="message-${msg.id}">
                                ${content}
                            </div>
                        `;
                        $('#chatMessages').append(msgHtml);
                    }




                    function openChat(receiverId, receiverType, receiverName, receiverProfile) {
                        $('#receiver_id').val(receiverId);
                        $('#receiver_type').val(receiverType);
                        $('#chatName').text(receiverName);
                        $('#chatProfile').attr('src', receiverProfile);
                        $('#chatTime').text(new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }));

                        $('#chatModal').addClass('hidden');
                        $('#chatBox').removeClass('hidden');

                        getMessages(receiverId, receiverType);

                    }

                    function scrollToBottom() {
                        const chatBody = document.querySelector('.chat-body-box');
                        if (chatBody) {
                            chatBody.scrollTop = chatBody.scrollHeight;
                        }
                    }


                </script>



            

 
                <script>
                    // Toggle modal open/close
                    function toggleChatModal() {
                        const modal = document.getElementById("chatModal");
                        const chatBox = document.getElementById("chatBox");
                        modal.classList.toggle("hidden");
                        chatBox.classList.add("hidden"); // always close chat when opening modal
                    }

                    // Tab switcher
                    function showTab(tabId) {
                        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
                        document.getElementById(tabId).classList.add('active');

                        document.querySelectorAll('.chat-tabs button').forEach(btn => btn.classList.remove('active'));
                        const activeBtn = Array.from(document.querySelectorAll('.chat-tabs button')).find(btn => btn.textContent.toLowerCase() === tabId);
                        if (activeBtn) activeBtn.classList.add('active');
                    }

                    // Open chat with clicked user
                    document.querySelectorAll('.chat-user').forEach(user => {
                        user.addEventListener('click', function () {
                            const imgSrc = this.querySelector('img').src;
                            const name = this.querySelector('.chat-name').textContent;
                            const message = this.querySelector('.chat-message').textContent;
                            const time = this.querySelector('.chat-time').textContent;

                            document.getElementById('chatProfile').src = imgSrc;
                            document.getElementById('chatName').textContent = name;
                            document.getElementById('chatSubtext').textContent = message;
                            document.getElementById('chatTime').textContent = time;

                            // Hide list modal, show chat box
                            document.getElementById('chatModal').classList.add('hidden');
                            document.getElementById('chatBox').classList.remove('hidden');
                        });
                    });

                    // Back to chat list
                    function backToChatList() {
                        document.getElementById('chatBox').classList.add('hidden');
                        document.getElementById('chatModal').classList.remove('hidden');
                    }
                </script>








                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-black">Training Programs</h2>
                    <p class="text-gray-500 mt-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>

                <div class="relative max-w-[1300px] mx-auto px-4">
                <!-- Left Arrow (absolute left outside) -->
                <div class="training-prev absolute -left-6 top-1/2 transform -translate-y-1/2 z-10">
                    <button class="bg-gray-200 hover:bg-gray-300 p-3 rounded-full shadow">
                    <img src="https://img.icons8.com/ios-filled/24/000000/chevron-left.png" alt="Prev"/>
                    </button>
                </div>

                <!-- Right Arrow (absolute right outside) -->
                <div class="training-next absolute -right-6 top-1/2 transform -translate-y-1/2 z-10">
                    <button class="bg-gray-200 hover:bg-gray-300 p-3 rounded-full shadow">
                    <img src="https://img.icons8.com/ios-filled/24/000000/chevron-right.png" alt="Next"/>
                    </button>
                </div>

                @php
                    $trainingCategory = App\Models\TrainingCategory::withCount('trainings')->get();
                @endphp
                <!-- Swiper Slider -->
                <div class="swiper trainingSwiper">
                    <div class="swiper-wrapper">
                        @foreach ($trainingCategory as $category)
                            <div class="swiper-slide">
                                <div class="bg-blue-50 rounded-lg text-center p-6 space-y-4 h-full flex flex-col justify-between">
                                    <div>
                                        <div class="w-16 h-16 bg-white mx-auto rounded-full flex items-center justify-center shadow mb-4">
                                            <img src="https://img.icons8.com/ios/50/money.png" class="w-6 h-6" />
                                        </div>
                                        <h4 class="font-semibold text-lg mb-1 leading-snug">
                                            @php
                                                $words = explode(' ', $category->category);
                                            @endphp
                                            @foreach ($words as $word)
                                                {{ $word }}<br>
                                            @endforeach
                                        </h4>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">{{ $category->trainings_count }}+ Training programs</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                </div>
            </section>

            <!-- Swiper JS -->
            <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

            <!-- Swiper Initialization -->
            <script>
            document.addEventListener("DOMContentLoaded", function () {
                new Swiper(".trainingSwiper", {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: true,
                navigation: {
                    nextEl: ".training-next",
                    prevEl: ".training-prev",
                },
                breakpoints: {
                    640: {
                    slidesPerView: 2,
                    },
                    768: {
                    slidesPerView: 3,
                    },
                    1024: {
                    slidesPerView: 4,
                    },
                    1280: {
                    slidesPerView: 6, // ✅ Show 6 items on large screens
                    },
                },
                });
            });
            </script>


            <section class="py-16">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold">Trending Courses</h2>
                        <p class="text-gray-500">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                    <div id="courseContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @php $index = 0; @endphp
                        @foreach($trainers as $trainer)
                            @foreach($trainer->materials as $material)
                            <a href="{{ route('course.details', $material->id) }}" class="text-decoration-none text-dark">
                                <div class="course-card {{ $index >= 4 ? 'hidden' : '' }}">
                                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition hover:-translate-y-1">
                                        <img src="{{ asset($material->thumbnail_file_path) }}" alt="{{ $material->training_title }}" class="w-full h-40 object-cover">
                                        <div class="p-4">
                                            <h6 class="text-base font-semibold mb-2">{{ $material->training_title }}</h6>
                                            <p class="text-sm text-gray-500 mb-2">{{ $material->training_sub_title }}</p>

                                            <div class="flex items-center text-yellow-500 text-sm mb-2">
                                               @php
                                                $avgRating = round($material->rating ?? 0, 1); // rounded to 1 decimal place
                                                $filledStars = floor($avgRating); // for star display
                                                @endphp

                                                <p class="mt-1 text-yellow-500">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <span class="{{ $i <= $filledStars ? '' : 'text-gray-300' }}">★</span>
                                                    @endfor
                                                    <span class="text-gray-500 font-medium ml-2">({{ $avgRating }}/5) Rating</span>
                                                </p>
                                            </div>

                                            <ul class="text-xs text-gray-500 flex flex-wrap gap-4 mb-4">
                                                <li><i class="bi bi-book"></i> {{ count($material->documents) }} lessons</li>
                                                <li><i class="bi bi-clock"></i> 
                                                    @php
                                                        $totalHours = 0;
                                                        foreach ($material->batches as $batch) {
                                                            $start = strtotime($batch->start_timing);
                                                            $end = strtotime($batch->end_timing);
                                                            $totalHours += ($end - $start) / 3600;
                                                        }
                                                    @endphp
                                                    {{ $totalHours }} hrs
                                                </li>
                                                <li><i class="bi bi-bar-chart"></i> {{ $material->training_level ?? 'Beginner' }}</li>
                                                <li>
                                                    <i class="bi bi-play-circle"></i>
                                                    {{ !empty($material->session_type) ? $material->session_type : 'Recorded' }}
                                                </li>

                                            </ul>

                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <img src="{{ $trainer->profile_picture ?? 'https://ui-avatars.com/api/?name=' . urlencode($trainer->name) }}" 
                                                        alt="{{ $trainer->name }}" 
                                                        class="w-7 h-7 rounded-full mr-2">
                                                    <span class="text-xs text-gray-600">{{ $trainer->name }}</span>
                                                </div>
                                                <div class="text-right text-xs">
                                                    <span class="line-through text-gray-400">SAR {{ number_format($material->training_price, 0) }}</span>
                                                    <span class="text-green-600 font-semibold ml-1">SAR {{ number_format($material->training_offer_price, 0) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php $index++; @endphp
                                </a>    
                            @endforeach
                        @endforeach
                    </div>

                    <div class="mt-10 text-center">
                        <button id="viewAllBtn" class="px-6 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-md transition">
                            View All Courses
                        </button>
                    </div>

                </div>
            </section>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const allCards = document.querySelectorAll(".course-card");
                    let visibleCount = 4;

                    document.getElementById("viewAllBtn").addEventListener("click", function () {
                        let nextVisibleCount = visibleCount + 4;
                        for (let i = visibleCount; i < nextVisibleCount && i < allCards.length; i++) {
                            allCards[i].classList.remove("hidden");
                        }
                        visibleCount = nextVisibleCount;

                        // Hide button if all cards are visible
                        if (visibleCount >= allCards.length) {
                            this.style.display = "none";
                        }
                    });
                });
            </script>

            <!-- <script>
                const courses = [
                    {
                        title: "Learn FIGMA - UIUX Design essentials",
                        image: "{{ asset('asset/images/gallery/pic-2.png') }}",
                        lessons: 6,
                        hours: "30hrs",
                        level: "Beginner",
                        instructor: "Abhishek S M"
                    },
                    {
                        title: "Python course",
                        image: "{{ asset('asset/images/gallery/pic-1.png') }}",
                        lessons: 8,
                        hours: "32hrs",
                        level: "Beginner",
                        instructor: "Matthew"
                    },
                    {
                        title: "SharePoint development",
                        image: "{{ asset('asset/images/gallery/pic-3.png') }}",
                        lessons: 6,
                        hours: "24hrs",
                        level: "Beginner",
                        instructor: "James Cameron"
                    },
                    {
                        title: "Graphic design - Advance level",
                        image: "{{ asset('asset/images/gallery/pic-4.png') }}",
                        lessons: 10,
                        hours: "20hrs",
                        level: "Advance",
                        instructor: "Julia Maccarthy"
                    },
                    {
                        title: "React.js for Beginners",
                        image: "{{ asset('asset/images/gallery/pic-1.png') }}",
                        lessons: 7,
                        hours: "25hrs",
                        level: "Beginner",
                        instructor: "Sara Lee"
                    },
                    {
                        title: "Advanced JavaScript",
                        image: "{{ asset('asset/images/gallery/pic-3.png') }}",
                        lessons: 9,
                        hours: "28hrs",
                        level: "Advance",
                        instructor: "David Wills"
                    },
                    {
                        title: "Mastering Node.js",
                        image: "{{ asset('asset/images/gallery/pic-2.png') }}",
                        lessons: 12,
                        hours: "35hrs",
                        level: "Intermediate",
                        instructor: "Ravi Kumar"
                    },
                    {
                        title: "Data Science with Python",
                        image: "{{ asset('asset/images/gallery/pic-4.png') }}",
                        lessons: 15,
                        hours: "40hrs",
                        level: "Intermediate",
                        instructor: "Dr. Anita Raj"
                    },
                    {
                        title: "Fullstack Web Development",
                        image: "{{ asset('asset/images/gallery/pic-1.png') }}",
                        lessons: 20,
                        hours: "60hrs",
                        level: "Advance",
                        instructor: "Mark Benson"
                    },
                    {
                        title: "Digital Marketing 101",
                        image: "{{ asset('asset/images/gallery/pic-3.png') }}",
                        lessons: 10,
                        hours: "18hrs",
                        level: "Beginner",
                        instructor: "Priya Sharma"
                    }
                ];



                const courseContainer = document.getElementById('courseContainer');
                const viewAllBtn = document.getElementById('viewAllBtn');

                const coursesPerRow = 4;
                let shownCourses = 0;

                function renderCourses() {
                    const end = Math.min(shownCourses + coursesPerRow, courses.length);
                    for (let i = shownCourses; i < end; i++) {
                        const c = courses[i];
                        const courseHTML = `
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition hover:-translate-y-1">
                                <img src="${c.image}" alt="${c.title}" class="w-full h-40 object-cover">
                                <div class="p-4">
                                    <h6 class="text-base font-semibold mb-2">${c.title}</h6>
                                    <div class="flex items-center text-yellow-500 text-sm mb-2">
                                        <span class="mr-1">★★★★☆</span>
                                        <span class="text-gray-500 text-xs">(4/5) Rating</span>
                                    </div>
                                    <ul class="text-xs text-gray-500 flex flex-wrap gap-4 mb-4">
                                        <li><i class="bi bi-book"></i> ${c.lessons} lessons</li>
                                        <li><i class="bi bi-clock"></i> ${c.hours}</li>
                                        <li><i class="bi bi-bar-chart"></i> ${c.level}</li>
                                    </ul>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <img src="http://weimaracademy.org/wp-content/uploads/2021/08/dummy-user.png" alt="${c.instructor}" class="w-7 h-7 rounded-full mr-2">
                                            <span class="text-xs text-gray-600">${c.instructor}</span>
                                        </div>
                                        <div class="text-right text-xs">
                                            <span class="line-through text-gray-400">SAR 99</span>
                                            <span class="text-green-600 font-semibold ml-1">SAR 89</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        courseContainer.insertAdjacentHTML('beforeend', courseHTML);
                    }
                    shownCourses = end;

                    if (shownCourses >= courses.length) {
                        viewAllBtn.classList.add('hidden');
                    }
                }

                viewAllBtn.addEventListener('click', () => {
                    renderCourses();
                });

                // Initial render of first row
                renderCourses();
            </script> -->



            @php
                $AboutContent = App\Models\CMS::where('slug', 'join-talentrek')->first();
            @endphp

            <section class="py-16 bg-white">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="flex flex-col lg:flex-row items-center gap-10">
                    {{-- CMS Start form here --}}
                       @php
                        echo $AboutContent->description;
                       @endphp
                    {{-- CMS end form here --}}

                    <!-- Right Image -->
                    <div class="lg:w-1/2 text-center">
                        <div class="inline-block p-2 rounded-full">
                            <img src="{{ $AboutContent->file_path }}" alt="Mentor" class="rounded-full w-full max-w-xs" />
                        </div>
                    </div>

                    </div>
                </div>
            </section>

            @php
                use Illuminate\Support\Facades\DB;
                $testimonials = DB::table('testimonials')->whereNotNull('message')->get();
            @endphp

            <section class="py-12 bg-white">
                <div class="max-w-6xl mx-auto px-4">
                    <h2 class="text-3xl font-bold text-center mb-10">What people are saying</h2>
                    <div class="relative px-10">
                        <div class="swiper mySwiper">
                            <div class="swiper-wrapper">
                                @foreach ($testimonials as $testimonial)
                                    <div class="swiper-slide px-2">
                                        <div class="bg-white shadow-md rounded-lg p-6 h-full flex flex-col justify-between">
                                            <div class="flex items-center mb-4">
                                                @if ($testimonial->file_path)
                                                    <img src="{{ asset($testimonial->file_path) }}" class="w-10 h-10 rounded-full mr-3" alt="{{ $testimonial->name }}" />
                                                @else
                                                    <img src="https://via.placeholder.com/40" class="w-10 h-10 rounded-full mr-3" alt="Default" />
                                                @endif
                                                <p class="font-medium text-sm">{{ $testimonial->name }}</p>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-lg mb-2">{{ $testimonial->title ?? 'What They Said' }}</h4>
                                                <p class="text-gray-600 text-sm">
                                                    {{ $testimonial->message }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Swiper Arrows -->
                        <div class="swiper-button-prev !text-gray-500 hover:!text-black !left-0"></div>
                        <div class="swiper-button-next !text-gray-500 hover:!text-black !right-0"></div>

                        <!-- Swiper Pagination -->
                        <div class="swiper-pagination bullets mt-6 text-center"></div>
                    </div>
                    <!-- Swiper Pagination OUTSIDE for better spacing -->

                </div>
            </section>


            <!-- Swiper JS -->
            <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

            <!-- Swiper Init -->
            <script>
                const swiper = new Swiper(".mySwiper", {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    loop: true,
                    autoplay: {
                        delay: 4000,
                        disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev"
                    },
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true,
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 2,
                        }
                    }
                });
            </script>




            <!-- <script>
                const swiper = new Swiper(".mySwiper", {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    loop: true,
                    autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                    },
                    navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev"
                    },
                    pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                    },
                    breakpoints: {
                    768: {
                        slidesPerView: 2,
                    }
                    }
                });
            </script>

            <style>
                .stats-section {
                background-color: #0047AB; /* Blue background */
                color: white;
                padding: 60px 0;
                text-align: center;
                }

                .stats-number {
                color: orange;
                font-size: 2rem;
                font-weight: bold;
                }

                .stats-description {
                font-size: 0.95rem;
                margin-top: 5px;
                }
            </style> -->
           


            @php
                $CountContent = App\Models\CMS::where('slug', 'countings')->first();
            @endphp
            <section class="stats-section">
                <div class="container">
                    @php
                        echo $CountContent->description;
                    @endphp
                </div>
            </section>
        </div>






<style>
    .stats-section {
    background-color: #0047AB; /* Blue background */
    color: white;
    padding: 60px 0;
    text-align: center;
    }

    .stats-number {
    color: orange;
    font-size: 2rem;
    font-weight: bold;
    }

    .stats-description {
    font-size: 0.95rem;
    margin-top: 5px;
    }
</style>






@include('site.componants.footer')