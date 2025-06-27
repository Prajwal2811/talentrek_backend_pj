        <!-- project main left menubar -->
        <div id="left-sidebar" class="sidebar">
            <a href="javascript:void(0);" class="menu_toggle"><i class="fa fa-angle-left"></i></a>
            <div class="navbar-brand">
                <a href="index.html"><img src="{{ asset('asset/backend/images/icon.svg') }}" alt="Mooli Logo" class="img-fluid logo"><span>Talentrek</span></a>
                <button type="button" class="btn-toggle-offcanvas btn btn-sm float-right"><i class="fa fa-close"></i></button>
            </div>
            <div class="sidebar-scroll">
                <div class="user-account">
                    <div class="user_div">
                        <img src="{{ asset('asset/backend/images/user.png') }}" class="user-photo" alt="User Profile Picture">
                    </div>
                    <div class="dropdown">
                        <span>{{ Auth()->user()->role }}</span>
                        <a href="javascript:void(0);" class="dropdown-toggle user-name" data-toggle="dropdown">
                            <strong>{{ Auth()->user()->name }}</strong>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right account vivify flipInY">
                            <li><a href="{{ route('admin.profile') }}"><i class="fa fa-user"></i>My Profile</a></li>
                            <li><a href="{{ route('admin.settings') }}"><i class="fa fa-gear"></i>Settings</a></li>
                            <li class="divider"></li>
                            <li><a href="{{ route('admin.signOut') }}"><i class="fa fa-power-off"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>  

                <nav id="left-sidebar-nav" class="sidebar-nav">
                    <ul id="main-menu" class="metismenu animation-li-delay">
                        <li class="header">Main</li>
                        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>

                        @if(Auth()->user()->role == 'superadmin')
                        <li class="header">Admin</li>
                        <li>
                            <a href="#" class="has-arrow"><i class="fa fa-user-md"></i><span>Admin</span></a>
                            <ul>
                                <li><a href="{{ route('admin.create') }}">Create Admin</a></li>
                                <li><a href="{{ route('admin.index') }}">Manage Admin</a></li>
                            </ul>
                        </li>



                        @endif
                        

                        <li class="header">User Roles</li>
                        <li><a href="{{ route('admin.jobseekers') }}"><i class="fa fa-user"></i><span>Jobseekers</span></a></li>
                        <li><a href="{{ route('admin.expat') }}"><i class="fa fa-globe"></i><span>Expat</span></a></li>
                        <li><a href="{{ route('admin.recruiters') }}"><i class="fa fa-briefcase"></i><span>Recruiters</span></a></li>
                        <li><a href="{{ route('admin.trainers') }}"><i class="fa fa-chalkboard-teacher"></i><span>Trainers</span></a></li>
                        <li><a href="{{ route('admin.assessors') }}"><i class="fa fa-clipboard-check"></i><span>Assessors</span></a></li>
                        <li><a href="{{ route('admin.coach') }}"><i class="fa fa-user-tie"></i><span>Coach</span></a></li>
                        <li><a href="{{ route('admin.mentors') }}"><i class="fa fa-users"></i><span>Mentors</span></a></li>

                        <li><a href="{{ route('admin.signOut') }}"><i class="fa fa-power-off"></i><span>Logout</span></a></li>
                    </ul>
                </nav>
            
            </div>
        </div>