<!-- Top Navbar -->
<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <!-- Menu Toggle -->
    <div class="navbar-nav align-items-xl-center me-3 me-xl-0">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)" id="sidebarToggle" aria-label="Toggle sidebar"
            aria-expanded="true">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <!-- Navbar Content -->
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        <!-- Right Side Menu -->
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Language Selector -->
            <li class="nav-item dropdown me-2">
                <div class="dropdown">
                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="langDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-globe"></i>
                        {{ strtoupper(app()->getLocale()) }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="langDropdown">
                        <li><a class="dropdown-item" href="{{ url('lang', 'en') }}">{{ __('english') }}</a></li>
                        <li><a class="dropdown-item" href="{{ url('lang', 'id') }}">{{ __('indonesian') }}</a></li>
                    </ul>
                </div>
            </li>

            <!-- User Dropdown -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <span class="avatar-initial rounded-circle bg-label-primary">
                            <i class="bx bx-user"></i>
                        </span>
                    </div>
                </a>

                <!-- Dropdown Menu -->
                <ul class="dropdown-menu dropdown-menu-end">
                    <!-- User Profile Card -->
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            <i class="bx bx-user"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ Auth::user()->name ?? 'John Doe' }}</span>
                                    <small class="text-muted">{{ Auth::user()->role ?? 'Admin' }}</small>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>

                

                    <!-- Logout -->
                    <li>
                        <a class="dropdown-item" href="{{ route('signout') }}">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">{{ __('logoutNav') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- /User Dropdown -->
        </ul>
    </div>
</nav>
<!-- /Top Navbar -->
