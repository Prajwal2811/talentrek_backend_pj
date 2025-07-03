<!-- project main left menubar -->
<div id="left-sidebar" class="sidebar">
    <a href="javascript:void(0);" class="menu_toggle"><i class="fa fa-angle-left"></i></a>
    <div class="navbar-brand">
        <a href="{{ route('home') }}" target="_blank">
            <img src="{{ asset('asset/backend/images/icon.svg') }}" alt="Mooli Logo" class="img-fluid logo">
            <span>Talentrek</span>
        </a>
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
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                </li>

                @if(Auth()->user()->role == 'superadmin' || Auth()->user()->role == 'admin')
                    <li class="header">Admin</li>
                    <li class="{{ request()->routeIs('admin.create', 'admin.index') ? 'active' : '' }}">
                        <a href="#" class="has-arrow"><i class="fa fa-user-md"></i><span>Admin</span></a>
                        <ul class="{{ request()->routeIs('admin.create', 'admin.index') ? 'collapse in' : 'collapse' }}">
                            <li class="{{ request()->routeIs('admin.create') ? 'active' : '' }}">
                                <a href="{{ route('admin.create') }}">Create Admin</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.index') }}">Manage Admin</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if(Auth()->user()->role == 'superadmin' || Auth()->user()->role == 'admin')
                    <li class="header">Users</li>
                    <li class="{{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                        <a href="#" class="has-arrow"><i class="fa fa-user-md"></i><span>Users</span></a>
                        <ul class="{{ request()->routeIs('admin.user.*') ? 'collapse in' : 'collapse' }}">
                            <li class="{{ request()->routeIs('admin.user.create') ? 'active' : '' }}">
                                <a href="{{ route('admin.user.create') }}">Create User</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.user.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.user.index') }}">Manage User</a>
                            </li>
                        </ul>
                    </li>
                @endif
                
                <li class="header">User Roles</li>
                <li class="{{ request()->routeIs('admin.jobseekers') ? 'active' : '' }}">
                    <a href="{{ route('admin.jobseekers') }}"><i class="fa fa-user"></i><span>Jobseekers</span></a>
                </li>
                <li class="{{ request()->routeIs('admin.expat') ? 'active' : '' }}">
                    <a href="{{ route('admin.expat') }}"><i class="fa fa-globe"></i><span>Expat</span></a>
                </li>
                <li class="{{ request()->routeIs('admin.recruiters') ? 'active' : '' }}">
                    <a href="{{ route('admin.recruiters') }}"><i class="fa fa-briefcase"></i><span>Recruiters</span></a>
                </li>
                <li class="{{ request()->routeIs('admin.trainers') ? 'active' : '' }}">
                    <a href="{{ route('admin.trainers') }}"><i class="fa fa-graduation-cap"></i><span>Trainers</span></a>
                </li>
                <li class="{{ request()->routeIs('admin.assessors') ? 'active' : '' }}">
                    <a href="{{ route('admin.assessors') }}"><i class="fa fa-check-square-o"></i><span>Assessors</span></a>
                </li>
                <li class="{{ request()->routeIs('admin.coach') ? 'active' : '' }}">
                    <a href="{{ route('admin.coach') }}"><i class="fa fa-user"></i><span>Coach</span></a>
                </li>
                <li class="{{ request()->routeIs('admin.mentors') ? 'active' : '' }}">
                    <a href="{{ route('admin.mentors') }}"><i class="fa fa-users"></i><span>Mentors</span></a>
                </li>

                @php $userRole = auth()->user()->role; @endphp
                    @if($userRole === 'superadmin' || $userRole === 'admin')
                    <li class="header">Site activity</li>

                        <li class="{{ request()->routeIs('admin.cms') ? 'active' : '' }}">
                            <a href="{{ route('admin.cms') }}">
                                <i class="fa fa-file-text"></i><span>CMS</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.subscriptions') ? 'active' : '' }}">
                            <a href="{{ route('admin.subscriptions') }}">
                                <i class="fa fa-credit-card"></i><span>Subscriptions</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.certification.template') ? 'active' : '' }}">
                            <a href="{{ route('admin.certification.template') }}">
                                <i class="fa fa-certificate"></i><span>Certification Template</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.payments') ? 'active' : '' }}">
                            <a href="{{ route('admin.payments') }}">
                                <i class="fa fa-money"></i><span>Payments</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.testimonials') ? 'active' : '' }}">
                            <a href="{{ route('admin.testimonials') }}">
                                <i class="fa fa-comments"></i><span>Testimonials</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.languages') ? 'active' : '' }}">
                            <a href="{{ route('admin.languages') }}">
                                <i class="fa fa-language"></i><span>Languages</span>
                            </a>
                        </li>

                        <li class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                            <a href="{{ route('admin.settings') }}">
                                <i class="fa fa-cog"></i><span>Settings</span>
                            </a>
                        </li>


                        <li class="header">System Log</li>
                        <li class="{{ request()->routeIs('admin.activity.log') ? 'active' : '' }}">
                            <a href="{{ route('admin.activity.log') }}"><i class="fa fa-history"></i><span>Logs</span></a>
                        </li>
                    @endif
            </ul>
        </nav>
    </div>
</div>
