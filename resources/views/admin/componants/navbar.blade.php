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
                        <a href="javascript:void(0);" class="dropdown-toggle icon-menu" data-toggle="dropdown">
                            <i class="fa fa-bell-o text"></i>
                            <span class="notification-dot info">4</span>
                        </a>
                        <ul class="dropdown-menu feeds_widget mt-0 animation-li-delay">
                            <li class="header theme-bg mt-0 text-light">You have 4 New Notifications</li>
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="mr-4"><i class="fa fa-check text-red"></i></div>
                                    <div class="feeds-body">
                                        <h4 class="title text-danger">Issue Fixed <small class="float-right text-muted font-12">9:10 AM</small></h4>
                                        <small>We have fixed all design bugs with responsiveness.</small>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="mr-4"><i class="fa fa-user text-info"></i></div>
                                    <div class="feeds-body">
                                        <h4 class="title text-info">New User <small class="float-right text-muted font-12">9:15 AM</small></h4>
                                        <small>I feel great! Thanks team</small>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="mr-4"><i class="fa fa-question-circle text-warning"></i></div>
                                    <div class="feeds-body">
                                        <h4 class="title text-warning">Server Warning <small class="float-right text-muted font-12">9:17 AM</small></h4>
                                        <small>Your connection is not private</small>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <div class="mr-4"><i class="fa fa-thumbs-o-up text-success"></i></div>
                                    <div class="feeds-body">
                                        <h4 class="title text-success">2 New Feedback <small class="float-right text-muted font-12">9:22 AM</small></h4>
                                        <small>It will give a smart finishing to your site</small>
                                    </div>
                                </a>
                            </li>
                        </ul>
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
