<!-- BEGIN: Header-->
<nav class="header-navbar navbar-expand-lg navbar navbar-with-menu floating-nav navbar-light navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-container content">
            <div class="navbar-collapse" id="navbar-mobile">
                <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                    <ul class="nav navbar-nav">
                        <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ficon feather icon-menu"></i></a></li>
                    </ul>

                    <ul class="nav navbar-nav">
                        <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand"><i class="ficon feather icon-maximize"></i></a></li>
                    </ul>
                </div>
                <ul class="nav navbar-nav float-right">
                    <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                            <div class="user-nav d-sm-flex d-none"><span class="user-name text-bold-600">{!! auth()->user()->first_name . ' ' . auth()->user()->last_name !!}</span></div><span>
                                @if(auth()->user()->image != '' && file_exists(uploadsDir('admin') . auth()->user()->image ))
                                <img class="round" src="{!! asset(uploadsDir('admin') . auth()->user()->image) !!}" alt="avatar" height="40" width="40"></span>
                                @else
                                <img class="round" src="{!! asset('assets/admin/app-assets/images/portrait/small/avatar-s-11.jpg') !!}" alt="avatar" height="40" width="40"></span>
                                @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="{!! route('admin.update-profile') !!}"><i class="feather icon-user"></i> Edit Profile</a>
                            <div class="dropdown-divider"></div><a class="dropdown-item" href="javascript:;" onclick="logout()"><i class="feather icon-power"></i> Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<!-- END: Header-->


<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <a class="navbar-brand brand-logo" href="#"
           style="background: url('{{ $siteSettings->logo_path  }}') no-repeat; background-position: center; height: 70px; width: auto;background-size: contain;"></a>
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto"><a class="navbar-brand" href="#">
                    <div class="brand-logo"></div>
{{--                    <h2 class="brand-text mb-0">{{ $siteSettings->site_title }}</h2>--}}
                </a></li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="feather icon-x d-block d-xl-none font-medium-4 primary toggle-icon"></i><i class="toggle-icon feather icon-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary" data-ticon="icon-disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item {{ request()->segment(2) == 'dashboard' ? 'active' : '' }}">
                <a href="{{ url('/admin') }}">
                    <i class="feather icon-home"></i>
                    <span class="menu-title" data-i18n="Dashboard">
                        Dashboard
                    </span>
                </a>
            </li>

            <li class=" navigation-header"><span>Modules</span>
            </li>
            <li class="nav-item {{ request()->segment(2) == 'administrators' ? 'active' : '' }}"> <a href="#"><i class="feather icon-cast"></i><span class="menu-title" data-i18n="User">Administrators</span></a>
                <ul class="menu-content">
                    <li class="{{ (request()->segment(2) == 'administrators' && request()->segment(3) == 'create') ? 'active' : '' }}"><a href="{{ route('admin.administrators.create') }}"><i class="feather icon-circle"></i><span class="menu-item" data-i18n="List">Add New</span></a>
                    </li>
                    <li class="{{ (request()->segment(2) == 'administrators' && request()->segment(3) != 'create') ? 'active' : '' }}"><a href="{{ route('admin.administrators.index') }}"><i class="feather icon-circle"></i><span class="menu-item" data-i18n="View">List</span></a>
                    </li>
                </ul>
            </li>
            <li class="nav-item {{ request()->segment(2) == 'users' ? 'active' : '' }}"> <a href="#"><i class="feather icon-user"></i><span class="menu-title" data-i18n="User">Users</span></a>
                <ul class="menu-content">
                    <li class="{{ (request()->segment(2) == 'users' && request()->segment(3) != 'create') ? 'active' : '' }}"><a href="{{ route('admin.users.index') }}"><i class="feather icon-circle"></i><span class="menu-item" data-i18n="View">List</span></a>
                    </li>
                </ul>
            </li>
            <li class="nav-item {{ request()->segment(2) == 'notification' ? 'active' : '' }}"> <a href="#"><i class="feather icon-cast"></i><span class="menu-title" data-i18n="User">Notifications</span></a>
                <ul class="menu-content">
                    <li class="{{ (request()->segment(2) == 'notification' && request()->segment(3) == 'create') ? 'active' : '' }}"><a href="{{ route('admin.notification.create') }}"><i class="feather icon-circle"></i><span class="menu-item" data-i18n="List">Add New</span></a>
                    </li>
                    <li class="{{ (request()->segment(2) == 'notification' && request()->segment(3) != 'create') ? 'active' : '' }}"><a href="{{ route('admin.notification.index') }}"><i class="feather icon-circle"></i><span class="menu-item" data-i18n="View">List</span></a>
                    </li>
                </ul>
            </li>


            <li class="nav-item {{ request()->segment(2) == 'event' ? 'active' : '' }}"> <a href="#"><i class="feather icon-cast"></i><span class="menu-title" data-i18n="User">Events</span></a>
                <ul class="menu-content">
                    <li class="{{ (request()->segment(2) == 'event' && request()->segment(3) == 'create') ? 'active' : '' }}"><a href="{{ route('admin.event.create') }}"><i class="feather icon-circle"></i><span class="menu-item" data-i18n="List">Add New</span></a>
                    </li>
                    <li class="{{ (request()->segment(2) == 'event' && request()->segment(3) != 'create') ? 'active' : '' }}"><a href="{{ route('admin.event.index') }}"><i class="feather icon-circle"></i><span class="menu-item" data-i18n="View">List</span></a>
                    </li>
                </ul>
            </li>

            <li class="nav-item {{ request()->segment(2) == 'group' ? 'active' : '' }}"> <a href="#"><i class="feather icon-cast"></i><span class="menu-title" data-i18n="User">Group</span></a>
                <ul class="menu-content">
                    <li class="{{ (request()->segment(2) == 'group' && request()->segment(3) == 'create') ? 'active' : '' }}"><a href="{{ route('admin.group.create') }}"><i class="feather icon-circle"></i><span class="menu-item" data-i18n="List">Add New</span></a>
                    </li>
                    <li class="{{ (request()->segment(2) == 'group' && request()->segment(3) != 'create') ? 'active' : '' }}"><a href="{{ route('admin.group.index') }}"><i class="feather icon-circle"></i><span class="menu-item" data-i18n="View">List</span></a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
