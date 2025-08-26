<?php 
    //All jobseeker chat list
    $jobseekersList = [];

    // $jobseekersList = DB::table('jobseekers as j')
    //     ->select('j.id as user_id', 'j.name as jobseeker_name')
    //     ->where('j.status', 'active')
    //     ->get();
    // $jobseekers = DB::table('jobseekers')
    // ->select('id as user_id','name as jobseeker_name')
    // ->get()
    // ->map(function($jobseeker){
    //     $jobseeker->unread_count = DB::table('admin_group_chats')
    //         ->where('sender_id',$jobseeker->user_id)
    //         ->where('sender_type','jobseeker')
    //         ->where('receiver_type','admin')
    //         ->where('is_read',0)
    //         ->count();
    //     return $jobseeker;
    // });
    // echo "<pre>";
    // print_r($jobseekers);exit;
    
    $recruitersCompanyList = DB::table('recruiters_company as rc')
        ->select('rc.id as user_id', 'rc.company_name')
        ->where('rc.status', 'active')
        ->get();

    $mentorsList = DB::table('mentors as m')
        ->select('m.id as user_id', 'm.name as mentor_name')
        ->where('m.status', 'active')
        ->get();    
    
    $assessorsList = DB::table('assessors as a')
        ->select('a.id as user_id', 'a.name as assessor_name')
        ->where('a.status', 'active')
        ->get();  

    $coachesList = DB::table('coaches as j')
        ->select('j.id as user_id', 'j.name as coach_name')
        ->where('j.status', 'active')
        ->get();  
    // echo "<pre>";
    // print_r($assessorsList);exit;

    
?>

@include('admin.componants.header')

<body data-theme="light">
    <div id="body" class="theme-cyan">
        <div class="themesetting">
        </div>
        <div class="overlay"></div>
        <div id="wrapper">
            @include('admin.componants.navbar')
            @include('admin.componants.sidebar')
            <div id="main-content">
                <div class="container-fluid">
                   <!-- Page header section  -->
                    <div class="block-header">
                        <div class="row clearfix">
                            <div class="col-xl-5 col-md-5 col-sm-12">
                                <h1>Hi, {{  Auth()->user()->name }}!</h1>
                                <span>JustDo Chat,</span>
                            </div>            
                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-12">
                            <div class="card">
                                    <div class="chatapp_list">
                                        <ul class="nav nav-tabs2 mb-4 d-flex text-center">
                                            <li class="nav-item flex-fill"><a data-toggle="tab" class="nav-link active show" href="#chats-Jobseekers">Jobseekers</a></li>
                                            <li class="nav-item flex-fill"><a data-toggle="tab" class="nav-link" href="#chats-Recruiters">Recruiters</a></li>
                                            <li class="nav-item flex-fill mt-2"><a data-toggle="tab" class="nav-link mr-0" href="#chats-Mentors">Mentors</a></li>
                                            <li class="nav-item flex-fill mt-2"><a data-toggle="tab" class="nav-link " href="#chats-Assessors">Assessors</a></li>
                                            <li class="nav-item flex-fill mt-2" ><a data-toggle="tab" class="nav-link" href="#chats-Coach">Coach</a></li>
                                            
                                        </ul>
                                        <div class="tab-content">

                                            {{-- Jobseekers --}}
                                            <div class="tab-pane vivify fadeIn active show" id="chats-Jobseekers">
                                                <ul class="right_chat list-unstyled mb-0 animation-li-delay">
                                                    @foreach($jobseekersList as $jobseeker)
                                                        <li class="online">
                                                            <a href="javascript:void(0);" 
                                                            class="media openChat" 
                                                            data-id="{{ $jobseeker->user_id }}" 
                                                            data-name="{{ $jobseeker->jobseeker_name }}" 
                                                            data-type="Jobseeker">

                                                                <div class="media-body d-flex justify-content-between align-items-center">
                                                                    <span class="name">
                                                                        {{ $jobseeker->jobseeker_name }}
                                                                        <small class="text-muted font-12 d-block">Active</small>
                                                                    </span>

                                                                    @if($jobseeker->unread_count > 0)
                                                                        <span class="badge badge-danger ml-2" id="new-message-{{ $jobseeker->user_id }}">
                                                                            {{ $jobseeker->unread_count }}
                                                                        </span>
                                                                    @endif
                                                                </div>

                                                                <div class="media-body">
                                                                    <span class="message">Click to chat</span>
                                                                    <span class="badge badge-outline status"></span>
                                                                </div>
                                                            </a>
                                                        </li>

                                                    @endforeach
                                                </ul>
                                            </div>

                                            {{-- Recruiters --}}
                                            <div class="tab-pane vivify fadeIn" id="chats-Recruiters">
                                                <ul class="right_chat list-unstyled mb-0 animation-li-delay">
                                                    @foreach($recruitersCompanyList as $recruiter)
                                                        <li class="online">
                                                            <a href="javascript:void(0);" 
                                                            class="media openChat"
                                                            data-id="{{ $recruiter->user_id }}" 
                                                            data-name="{{ $recruiter->company_name }}" 
                                                            data-type="Recruiter">
                                                                <img class="media-object" src="../assets/images/xs/avatar5.jpg" alt="">
                                                                <div class="media-body">
                                                                    <span class="name">
                                                                        {{ $recruiter->company_name }}
                                                                        <small class="text-muted font-12">Active</small>
                                                                    </span>
                                                                    <span class="message">Click to chat</span>
                                                                    <span class="badge badge-outline status"></span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>

                                            {{-- Mentors --}}
                                            <div class="tab-pane vivify fadeIn" id="chats-Mentors">
                                                <ul class="right_chat list-unstyled mb-0 animation-li-delay">
                                                    @foreach($mentorsList as $mentor)
                                                        <li class="online">
                                                            <a href="javascript:void(0);" 
                                                            class="media openChat"
                                                            data-id="{{ $mentor->user_id }}" 
                                                            data-name="{{ $mentor->mentor_name }}" 
                                                            data-type="Mentor">
                                                                <img class="media-object" src="../assets/images/xs/avatar3.jpg" alt="">
                                                                <div class="media-body">
                                                                    <span class="name">
                                                                        {{ $mentor->mentor_name }}
                                                                        <small class="text-muted font-12">Active</small>
                                                                    </span>
                                                                    <span class="message">Click to chat</span>
                                                                    <span class="badge badge-outline status"></span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>

                                            {{-- Assessors --}}
                                            <div class="tab-pane vivify fadeIn" id="chats-Assessors">
                                                <ul class="right_chat list-unstyled mb-0 animation-li-delay">
                                                    @foreach($assessorsList as $assessor)
                                                        <li class="online">
                                                            <a href="javascript:void(0);" 
                                                            class="media openChat"
                                                            data-id="{{ $assessor->user_id }}" 
                                                            data-name="{{ $assessor->assessor_name }}" 
                                                            data-type="Assessor">
                                                                <img class="media-object" src="../assets/images/xs/avatar5.jpg" alt="">
                                                                <div class="media-body">
                                                                    <span class="name">
                                                                        {{ $assessor->assessor_name }}
                                                                        <small class="text-muted font-12">Active</small>
                                                                    </span>
                                                                    <span class="message">Click to chat</span>
                                                                    <span class="badge badge-outline status"></span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>

                                            {{-- Coaches --}}
                                            <div class="tab-pane vivify fadeIn" id="chats-Coach">
                                                <ul class="right_chat list-unstyled mb-0 animation-li-delay">
                                                    @foreach($coachesList as $coach)
                                                        <li class="online">
                                                            <a href="javascript:void(0);" 
                                                            class="media openChat"
                                                            data-id="{{ $coach->user_id }}" 
                                                            data-name="{{ $coach->coach_name }}" 
                                                            data-type="Coach">
                                                                <img class="media-object" src="../assets/images/xs/avatar3.jpg" alt="">
                                                                <div class="media-body">
                                                                    <span class="name">
                                                                        {{ $coach->coach_name }}
                                                                    <small class="text-muted font-12">Active</small>
                                                                    </span>
                                                                    <span class="message">Click to chat</span>
                                                                    <span class="badge badge-outline status"></span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>

                                        </div>
                                    </div>

                                    {{-- Chat Box --}}
                                    <div class="chatapp_body d-none" id="chatBox">
                                        <div class="chat-header">
                                            <div class="media mb-0">
                                                <img class="rounded-circle w35" src="../assets/images/user.png" alt="">
                                                <div class="media-body mr-3 ml-3 text-muted">
                                                    <h6 class="m-0" id="chatUserName">Select User</h6>
                                                    <small id="chatUserType">Type</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="chat-history">
                                            <ul class="message_data" id="chatMessages">
                                                <!-- Messages load dynamically -->
                                            </ul>
                                        </div>

                                        <div class="chat-message d-flex align-items-center gap-2 w-100">
                                            <input type="file" id="fileInput" class="d-none">

                                            <!-- Input + Buttons -->
                                            <div class="d-flex align-items-center gap-2 w-100">
                                                <!-- Input field -->
                                                <div class="flex-grow-1">
                                                    <textarea class="form-control h-100" id="messageInput" 
                                                        placeholder="Enter text here..." rows="1" style="height: 42px; resize: none;"></textarea>
                                                </div>

                                                <!-- File Button -->
                                                <button class="btn btn-light d-flex align-items-center justify-content-center" 
                                                    id="fileBtn" style="height: 42px; width: 42px;">
                                                    <i class="fa fa-paperclip"></i>
                                                </button>

                                                <!-- Send Button -->
                                                <button class="btn btn-primary d-flex align-items-center justify-content-center" 
                                                    id="sendBtn" style="height: 42px; width: 42px;">
                                                    <i class="fa fa-paper-plane"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>


                                {{-- JS --}}
                                <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                <script src="/asset/backend/bundles/libscripts.bundle.js"></script>
                                <script src="/asset/backend/bundles/mainscripts.bundle.js"></script> -->

                                <!-- <script>
                                    let currentUserId   = null;
                                    let currentUserType = null;
                                    let authAdminId     = "{{ auth()->id() }}";

                                    // 📦 Open chat
                                    $(document).on('click', '.openChat', function (e) {
                                        e.preventDefault();

                                        currentUserId   = $(this).data('id');
                                        let userName    = $(this).data('name');
                                        currentUserType = $(this).data('type');

                                        $(".right_chat li").removeClass("active-chat");
                                        $(this).closest("li").addClass("active-chat");

                                        $("#chatBox").removeClass('d-none');
                                        $("#chatUserName").text(userName);
                                        $("#chatUserType").text(currentUserType);

                                        loadMessages();
                                    });

                                    // 📨 Load messages
                                    function loadMessages() {
                                        $.ajax({
                                            url: "{{ route('admin.group.chat.fetch') }}",
                                            method: "GET",
                                            data: {
                                                receiver_id: currentUserId,
                                                receiver_type: currentUserType.toLowerCase(),
                                                _token: "{{ csrf_token() }}"
                                            },
                                            success: function (res) {
                                                $("#chatMessages").html('');

                                                (Array.isArray(res) ? res : res.messages).forEach(msg => {
                                                    let align   = msg.sender_id == authAdminId ? 'right' : 'left';
                                                    let content = msg.type == 2
                                                        ? `<a href="${msg.message}" target="_blank">📎 File</a>`
                                                        : msg.message;

                                                    $("#chatMessages").append(
                                                        `<li class="${align} clearfix">
                                                            <div class="message"><span>${content}</span></div>
                                                            <span class="data_time">${new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                                                        </li>`
                                                    );
                                                });

                                                $(".chat-history").scrollTop($("#chatMessages")[0].scrollHeight);
                                            }
                                        });
                                    }

                                    // 📁 File upload trigger
                                    $("#fileBtn").click(function () {
                                        $("#fileInput").click();
                                    });

                                    // 🛫 Send message
                                    $("#sendBtn").click(function () {
                                        let msg  = $("#messageInput").val();
                                        let file = $("#fileInput")[0].files[0];

                                        if (!msg.trim() && !file) return;

                                        let formData = new FormData();
                                        formData.append("_token", "{{ csrf_token() }}");
                                        formData.append("receiver_id", currentUserId);
                                        formData.append("receiver_type", currentUserType);

                                        if (file) {
                                            formData.append("file", file);
                                        } else {
                                            formData.append("message", msg);
                                        }

                                        $.ajax({
                                            url: "{{ route('admin.group.chat.send') }}",
                                            method: "POST",
                                            data: formData,
                                            contentType: false,
                                            processData: false,
                                            success: function () {
                                                $("#messageInput").val("");
                                                $("#fileInput").val("");
                                                loadMessages(); 
                                            }
                                        });
                                    });
                                </script> -->


                                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.js"></script>

                                <script>
                                let currentUserId = null;
                                let currentUserType = null;
                                let authUserId = "{{ auth()->id() }}";

                                // ✅ Echo Init
                                window.Echo = new Echo({
                                    broadcaster: 'pusher',
                                    key: '{{ env("PUSHER_APP_KEY") }}',
                                    wsHost: window.location.hostname,
                                    wsPort: 6001,
                                    forceTLS: false,
                                    authEndpoint: '/broadcasting/auth',
                                    withCredentials: true,
                                    auth: {
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        }
                                    }
                                });

                                // ✅ Jobseeker List Load with Unread Badges
                                function loadUserList() {
                                    $.ajax({
                                        url: "{{ route('admin.jobseekers.list') }}",
                                        method: "GET",
                                        success: function (res) {
                                            $(".right_chat").html('');

                                            res.forEach(user => {
                                                let hasUnread = user.unread_count > 0;
                                                let unreadClass = hasUnread ? 'has-unread' : '';
                                                let unreadBadge = hasUnread
                                                    ? `<span class="badge badge-danger" id="new-message-${user.user_id}">${user.unread_count}</span>`
                                                    : '';

                                                $(".right_chat").append(`
                                                    <li class="online ${unreadClass}" data-id="${user.user_id}">
                                                        <a href="javascript:void(0);" 
                                                            class="media openChat" 
                                                            data-id="${user.user_id}" 
                                                            data-type="jobseeker"
                                                            data-name="${user.jobseeker_name}">
                                                            <div class="media-body">
                                                                <span class="name">${user.jobseeker_name}</span>
                                                                ${unreadBadge}
                                                                <span class="message">Click to chat</span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                `);
                                            });

                                            // ✅ Click Chat → Open Chat Box + Clear Badge
                                            $(".openChat").off("click").on("click", function () {
                                                $(".right_chat li").removeClass("active-chat");
                                                $(this).closest("li").addClass("active-chat").removeClass("has-unread");

                                                currentUserId = $(this).data('id');
                                                currentUserType = $(this).data('type').toLowerCase();
                                                let userName = $(this).data('name');

                                                $("#chatBox").removeClass('d-none');
                                                $("#chatUserName").text(userName);
                                                $("#chatUserType").text(currentUserType);

                                                loadMessages();

                                                // ✅ Seen mark karte hi unread badge hata do
                                                $.post("{{ route('admin.group.chat.seen') }}", {
                                                    receiver_id: currentUserId,
                                                    receiver_type: currentUserType,
                                                    _token: "{{ csrf_token() }}"
                                                }, function (res) {
                                                    $(`#new-message-${currentUserId}`).remove();
                                                });
                                            });
                                        }
                                    });
                                }

                                // ✅ Jobseeker List Load with Unread Badges
                                function loadMentorList() {
                                    $.ajax({
                                        url: "{{ route('admin.mentors.list') }}",
                                        method: "GET",
                                        success: function (res) {
                                            $(".right_chat").html('');

                                            res.forEach(user => {
                                                let hasUnread = user.unread_count > 0;
                                                let unreadClass = hasUnread ? 'has-unread' : '';
                                                let unreadBadge = hasUnread
                                                    ? `<span class="badge badge-danger" id="new-message-${user.user_id}">${user.unread_count}</span>`
                                                    : '';

                                                $(".right_chat").append(`
                                                    <li class="online ${unreadClass}" data-id="${user.user_id}">
                                                        <a href="javascript:void(0);" 
                                                            class="media openChat" 
                                                            data-id="${user.user_id}" 
                                                            data-type="jobseeker"
                                                            data-name="${user.jobseeker_name}">
                                                            <div class="media-body">
                                                                <span class="name">${user.jobseeker_name}</span>
                                                                ${unreadBadge}
                                                                <span class="message">Click to chat</span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                `);
                                            });

                                            // ✅ Click Chat → Open Chat Box + Clear Badge
                                            $(".openChat").off("click").on("click", function () {
                                                $(".right_chat li").removeClass("active-chat");
                                                $(this).closest("li").addClass("active-chat").removeClass("has-unread");

                                                currentUserId = $(this).data('id');
                                                currentUserType = $(this).data('type').toLowerCase();
                                                let userName = $(this).data('name');

                                                $("#chatBox").removeClass('d-none');
                                                $("#chatUserName").text(userName);
                                                $("#chatUserType").text(currentUserType);

                                                loadMessages();

                                                // ✅ Seen mark karte hi unread badge hata do
                                                $.post("{{ route('admin.group.chat.seen') }}", {
                                                    receiver_id: currentUserId,
                                                    receiver_type: currentUserType,
                                                    _token: "{{ csrf_token() }}"
                                                }, function (res) {
                                                    $(`#new-message-${currentUserId}`).remove();
                                                });
                                            });
                                        }
                                    });
                                }

                               
                                // ✅ Append message
                                    function appendMessage(msg) {
                                        let isSelf = (msg.sender_type === 'admin');
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

                                // ✅ Load Chat Messages
                                function loadMessages() {
                                    if (!currentUserId || !currentUserType) return;

                                    $.get("{{ route('admin.group.chat.fetch') }}", {
                                        receiver_id: currentUserId,
                                        receiver_type: currentUserType,
                                        _token: "{{ csrf_token() }}"
                                    }, function (res) {
                                        $("#chatMessages").html('');
                                        res.forEach(msg => appendMessage(msg));
                                    });
                                }

                                // ✅ Page Load → Setup Everything
                                $(document).ready(function () {
                                    loadUserList();

                                    // ✅ Message Send
                                    $("#sendBtn").click(function () {
                                        let msg = $("#messageInput").val();
                                        let file = $("#fileInput")[0].files[0];
                                        if (!msg.trim() && !file) return;

                                        let formData = new FormData();
                                        formData.append('_token', "{{ csrf_token() }}");
                                        formData.append('receiver_id', currentUserId);
                                        formData.append('receiver_type', currentUserType);
                                        if (file) formData.append('file', file);
                                        else formData.append('message', msg);

                                        $.ajax({
                                            url: "{{ route('admin.group.chat.send') }}",
                                            method: "POST",
                                            data: formData,
                                            contentType: false,
                                            processData: false,
                                            success: function (res) {
                                                appendMessage({ ...res, sender_id: authUserId });
                                                $("#messageInput").val('');
                                                $("#fileInput").val('');
                                            }
                                        });
                                    });

                                    // ✅ Realtime Pusher Events
                                    Echo.channel('chat.admin')
                                        .listen('.message.sent', (e) => {
                                            if (e.sender_id != authUserId) {
                                                // ✅ Agar user ka chat open hai to message dikhao
                                                if (e.sender_id == currentUserId && e.sender_type === currentUserType) {
                                                    appendMessage(e);
                                                } else {
                                                    // ✅ Badge update karo, message window me mat dikhao
                                                    let badge = $(`#new-message-${e.sender_id}`);
                                                    if (badge.length) {
                                                        badge.text((parseInt(badge.text()) || 0) + 1);
                                                    } else {
                                                        let $targetUser = $(`.right_chat li[data-id="${e.sender_id}"]`);
                                                        if ($targetUser.length) {
                                                            $targetUser.addClass("has-unread");
                                                            $targetUser.find('.media-body .name').after(
                                                                `<span class="badge badge-danger" id="new-message-${e.sender_id}">1</span>`
                                                            );
                                                        } else {
                                                            loadUserList(); // fallback if user not found
                                                        }
                                                    }
                                                }
                                            }
                                        })
                                        .listen('.message.seen', (e) => {
                                            if (e.to_id == authUserId && e.to_type == 'admin') {
                                                $(`#new-message-${e.sender_id}`).remove();
                                            }
                                        });
                                });
                                </script>






        
                                <style>
                                    .has-unread {
                                        background-color: #b2f0fb !important;  /* Light blue background */
                                        border-radius: 5px;
                                        transition: background-color 0.3s ease;
                                    }

                                    .badge-danger {
                                        background-color: red;
                                        color: white;
                                        border-radius: 10px;
                                        padding: 2px 6px;
                                        font-size: 12px;
                                        margin-left: 5px;
                                    }

                                </style>

                                
                                
                                
                                <style>
                                    .right_chat li.active-chat {
                                        background: #e9f3ff;
                                        border-radius: 6px;
                                    }
                                    .right_chat li.active-chat a {
                                        color: #000;
                                        font-weight: 600;
                                    }
                                    /* Right side (Admin messages) */
                                    #chatMessages li.right .message {
                                        background-color: #59c4bc;
                                        color: #000000;
                                        padding: 8px 12px;
                                        border-radius: 12px;
                                        display: inline-block;
                                        max-width: 70%;
                                        word-wrap: break-word;
                                    }

                                    /* Left side (user messages) normal styling */
                                    #chatMessages li.left .message {
                                        background-color: #f1f1f1; /* apne hisaab se rakh sakte ho */
                                        color: #000000;
                                        padding: 8px 12px;
                                        border-radius: 12px;
                                        display: inline-block;
                                        max-width: 70%;
                                        word-wrap: break-word;
                                    }

                                </style>


                                <div class="user_detail">
                                    <div class="text-center mb-4">
                                        <div class="profile-image"><img src="../assets/images/user.png" class="rounded-circle mb-3" alt=""></div>
                                        <h4 class="m-b-0">Louis Pierce</h4>
                                        <span>Washington, d.c.</span>
                                    </div>
                                    <div class="text-center">
                                        <small class="text-muted">Address: </small>
                                        <p>795 Folsom Ave, Suite 600 San Francisco, 94107</p>
                                        <small class="text-muted">Email address: </small>
                                        <p>louispierce@example.com</p>
                                        <small class="text-muted">Mobile: </small>
                                        <p>+ 202-222-2121</p>
                                        <small class="text-muted">Birth Date: </small>
                                        <p class="m-b-0">October 17th, 93</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.componants.footer')
    