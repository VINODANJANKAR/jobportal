<aside id="sidebar" class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar d-flex flex-column" data-simplebar>
        <div class="d-flex mb-4 align-items-center justify-content-between">
            <a href="{{ url('/') }}" class="text-nowrap logo-img ms-0 ms-md-1">
                <img src="{{ asset('images/logos/TJobs.png') }}" width="180" alt="">
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <nav class="sidebar-nav d-flex flex-column flex-grow-1">
            <ul id="sidebarnav" class="mb-4 pb-2" style="height: 100%">
                <li class="nav-small-cap">
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item {{ Request::is('/') ? 'selected' : '' }}">
                    <a class="sidebar-link sidebar-link theme-hover-bg " href="{{ url('/') }}">
                        <span class="aside-icon p-2 bg-light-theme rounded-3">
                            <i class="ti ti-dashboard fs-7 text-theme"></i>
                        </span>
                        <span class="hide-menu ms-2 ps-1">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item {{ Request::is('job-posts*') ? 'selected' : '' }}">
                    <a class="sidebar-link sidebar-link theme-hover-bg" href="{{ url('job-posts') }}">
                        <span class="aside-icon p-2 bg-light-theme rounded-3">
                            <i class="ti ti-briefcase fs-7 text-theme"></i>
                        </span>
                        <span class="hide-menu ms-2 ps-1">Job Postings</span>
                    </a>
                </li>
                @can('isAdmin')
                    <li class="sidebar-item {{ Request::is('profiles*') ? 'selected' : '' }}">
                        <a class="sidebar-link sidebar-link theme-hover-bg" href="{{ url('profiles') }}">
                            <span class="aside-icon p-2 bg-light-theme rounded-3">
                                <i class="ti ti-users fs-7 text-theme"></i>
                            </span>
                            <span class="hide-menu ms-2 ps-1">Profiles</span>
                        </a>
                    </li>

                    <hr />
                @endcan
                <!-- Show this section only if the user is an admin -->
                @can('isAdmin')
                    <li class="sidebar-item {{ Request::is('master*') ? 'selected' : '' }}">
                        <a class="sidebar-link sidebar-link theme-hover-bg" data-bs-toggle="collapse" href="#master"
                            role="button" aria-expanded="{{ Request::is('master*') ? 'true' : 'false' }}">
                            <span class="aside-icon p-2 bg-light-theme rounded-3">
                                <i class="ti ti-layout fs-7 text-theme"></i>
                            </span>
                            <span class="hide-menu ms-2 ps-1">Masters</span>
                        </a>
                        <div class="collapse {{ Request::is('master*') ? 'show' : '' }}" id="master">
                            <ul class="nav flex-column p-2">
                                <li class="nav-item">
                                    <a href="{{ url('master/user') }}"
                                        class="nav-link {{ Request::is('master/user*') ? 'active' : '' }}">
                                        <i class="me-4"></i> User
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('master/skills') }}"
                                        class="nav-link {{ Request::is('master/skills*') ? 'active' : '' }}">
                                        <i class="me-4"></i> Skills
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('master/experiences') }}"
                                        class="nav-link {{ Request::is('master/experiences*') ? 'active' : '' }}">
                                        <i class="me-4"></i> Experiences
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('master/qualifications') }}"
                                        class="nav-link {{ Request::is('master/qualifications*') ? 'active' : '' }}">
                                        <i class="me-4"></i> Qualifications
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan
            </ul>
            @include('layouts.partials.footer')
        </nav>
        <!-- End Sidebar navigation-->
    </div>
    <!-- End Sidebar scroll-->
</aside>

<style>
    .sidebar-item.selected {
        background-color: rgba(255, 255, 255, 0.15);
        border-radius: 10px;
    }

    .sidebar-item .nav-link.active {
        background-color: rgba(255, 255, 255, 0.15);
        border-radius: 10px;
    }

    .sidebar-item .nav-link {
        color: #ffffff;
    }
</style>
