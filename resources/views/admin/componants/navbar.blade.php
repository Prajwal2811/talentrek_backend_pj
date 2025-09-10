<style>
.notification_scroll{
    overflow-y: scroll !important;
    height: 578px;
}

</style>
<!-- main page header -->
<nav class="navbar navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-left">
            <div class="navbar-btn">
                <a href="#"><img src="{{ asset('asset/backend/images/icon.svg') }}" alt="Talentrek Logo"
                        class="img-fluid logo"></a>
                <button type="button" class="btn-toggle-offcanvas"><i class="fa fa-align-left"></i></button>
            </div>
        </div>
        <div class="navbar-right">
            <div id="navbar-menu">
                <ul class="nav navbar-nav">
                    <!-- Language Switch -->
                    <li class="dropdown">
                        {{-- <a href="javascript:void(0);" class="dropdown-toggle icon-menu d-flex align-items-center" data-toggle="dropdown">
                            <span class="ml-1 d-flex align-items-center text-uppercase">
                                @switch(app()->getLocale())
                                    @case('en')
                                        <img src="https://flagcdn.com/w20/us.png" alt="English" width="20" class="mr-1"> English
                                        @break
                                    @case('ar')
                                        <img src="https://flagcdn.com/w20/sa.png" alt="Arabic" width="20" class="mr-1"> Arabic
                                        @break
                                @endswitch
                            </span>
                        </a> --}}
                        {{-- <ul class="dropdown-menu dropdown-menu-right p-2" style="min-width: 160px;">
                            <li class="mb-1">
                                <a class="dropdown-item d-flex align-items-center" href="#" onclick="switchLanguage('en')">
                                    <img src="	https://flagcdn.com/w20/us.png" alt="English" width="20" class="mr-2"> English
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="#" onclick="switchLanguage('ar')">
                                    <img src="	https://flagcdn.com/w20/sa.png" alt="Arabic" width="20" class="mr-2"> Arabic
                                </a>
                            </li>
                        </ul> --}}
                    </li>



                    <!-- Notifications -->
                    <li class="dropdown">
                        <div id="notify">
                            @php $notifications = notificationSent(); @endphp
                            <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown">
                                <i class="fa fa-bell text"></i>
                                <span class="notification-dot info">{{ $notifications->count() }}</span>
                            </a>
                            <ul class="dropdown-menu feeds_widget mt-0 animation-li-delay notification_scroll">
                                <li class="header theme-bg mt-0 text-light"> <a href="{{ route('admin.notifications.index') }}"><span class="btn btn-info">All</span></a></li>
                                
                                @foreach($notifications as $notification)
                                    <li>
                                        <a href="{{ route('admin.notifications.view',['id' => $notification->id]) }}">
                                            <div class="mr-4"><i class="fa fa-check text-red"></i></div>
                                            <div class="feeds-body">
                                                <h4 class="title text-info">{{ $notification->sender_type }}
                                                    <small class="float-right text-muted font-12">{{ date('H:i A', strtotime($notification->created_at)) }}</small></h4>
                                                <small>{{ $notification->message }}</small>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                                @if($notifications->count() < 0)
                                   <h4>No Data Found</h4>
                                @endif
                            </ul>
                            <script>
                                function refreshSection() {
                                document.getElementById("notify").refresh();
                                }
                                // Refresh every 5 seconds
                                setInterval(refreshSection, 5000);
                            </script>
                        </div>
                    </li>

                    <!-- Logout -->
                    <li>
                        <a href="{{ route('admin.signOut') }}" class="icon-menu"><i class="fa fa-power-off"></i> </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
