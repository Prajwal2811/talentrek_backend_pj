@php
    $user = auth()->user();
    $role = $user->role ?? null;
    $permissions = $user->permissions ?? [];
@endphp

<div id="left-sidebar" class="sidebar">
    <a href="javascript:void(0);" class="menu_toggle"><i class="fa fa-angle-left"></i></a>
    <div class="navbar-brand">
        <a href="{{ route('home') }}" target="_blank">
            <img src="{{ asset('asset/backend/images/icon.svg') }}" alt="Talentrek Logo" class="img-fluid logo">
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
                <span>{{ $role }}</span>
                <a href="javascript:void(0);" class="dropdown-toggle user-name" data-toggle="dropdown">
                    <strong>{{ $user->name }}</strong>
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

                @if($role === 'superadmin')
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


                @if($role === 'superadmin' || $role === 'admin')
                    <li class="header">User</li>
                    <li class="{{ request()->routeIs('admin.user.create', 'admin.user.index') ? 'active' : '' }}">
                        <a href="#" class="has-arrow"><i class="fa fa-user-md"></i><span>User</span></a>
                        <ul class="{{ request()->routeIs('admin.user.create', 'admin.user.index') ? 'collapse in' : 'collapse' }}">
                            <li class="{{ request()->routeIs('admin.create') ? 'active' : '' }}">
                                <a href="{{ route('admin.user.create') }}">Create User</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.user.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.user.index') }}">Manage User</a>
                            </li>
                        </ul>
                    </li>
                @endif

                <li class="header">User Roles</li>

                @php
                    $menuItems = [
                        'Jobseekers' => ['route' => 'admin.jobseekers', 'icon' => 'fa-users'],
                        'Expat' => ['route' => 'admin.expat', 'icon' => 'fa-globe'],
                        'Recruiters' => ['route' => 'admin.recruiters', 'icon' => 'fa-user-tie'],
                        'Trainers' => ['route' => 'admin.trainers', 'icon' => 'fa-chalkboard-teacher'],
                        'Assessors' => ['route' => 'admin.assessors', 'icon' => 'fa-check-circle'],
                        'Coach' => ['route' => 'admin.coach', 'icon' => 'fa-user-check'],
                        'Mentors' => ['route' => 'admin.mentors', 'icon' => 'fa-user-graduate'],
                    ];
                @endphp

                @foreach ($menuItems as $label => $data)
                    @if($role === 'superadmin' || in_array($label, $permissions))
                        <li class="{{ request()->routeIs($data['route']) ? 'active' : '' }}">
                            <a href="{{ route($data['route']) }}">
                                <i class="fa {{ $data['icon'] }}"></i> <span>{{ $label }}</span>
                            </a>
                        </li>
                    @endif
                @endforeach

                <li class="header">Site Activity</li>

                @php
                    $menuItems = [
                        'CMS' => ['route' => 'admin.cms', 'icon' => 'fa-file-alt'],
                        'Subscriptions' => ['route' => 'admin.subscriptions', 'icon' => 'fa-credit-card'],
                        'Certification Template' => ['route' => 'admin.certification.template', 'icon' => 'fa-certificate'],
                        'Payments' => ['route' => 'admin.payments', 'icon' => 'fa-money-check-alt'],
                        'Languages' => ['route' => 'admin.languages', 'icon' => 'fa-language'],
                        'Settings' => ['route' => 'admin.settings', 'icon' => 'fa-cog'],
                        'Contact Support' => ['route' => 'admin.contact_support', 'icon' => 'fa-cog'],
                    ];
                @endphp

                @foreach ($menuItems as $label => $data)
                    @if($role === 'superadmin' || in_array($label, $permissions))
                        <li class="{{ request()->routeIs($data['route']) ? 'active' : '' }}">
                            <a href="{{ route($data['route']) }}">
                                <i class="fas {{ $data['icon'] }}"></i> <span>{{ $label }}</span>
                            </a>
                        </li>
                    @endif
                @endforeach


                @if($role === 'superadmin' || in_array('Testimonials', $permissions))
                    <li class="{{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                        <a href="#" class="has-arrow"><i class="fa fa-comments"></i><span>Testimonials</span></a>
                        <ul class="{{ request()->routeIs('admin.testimonials.*') ? 'collapse in' : 'collapse' }}">
                            <li class="{{ request()->routeIs('admin.testimonials.add') ? 'active' : '' }}">
                                <a href="{{ route('admin.testimonials.add') }}">Add Testimonial</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.testimonials') ? 'active' : '' }}">
                                <a href="{{ route('admin.testimonials') }}">Manage Testimonials</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if($role === 'superadmin' || in_array('Activity Log', $permissions))
                    <li class="header">System Log</li>
                    <li class="{{ request()->routeIs('admin.activity.log') ? 'active' : '' }}">
                        <a href="{{ route('admin.activity.log') }}"><i class="fa fa-history"></i><span>Logs</span></a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</div>
