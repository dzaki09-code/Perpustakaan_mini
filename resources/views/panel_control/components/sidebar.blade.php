<!-- Sidebar Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <!-- Brand Logo -->
  <div class="app-brand demo">
    <a href="{{ route('dashboard') }}" class="app-brand-link">
      <span class="app-brand-logo demo">
        <i class="bx bx-book-open fs-3 text-primary"></i>
      </span>
      <span class="app-brand-text demo menu-text fw-bolder ms-2">Perpustakaan</span>
    </a>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <!-- Menu Items -->
  <ul class="menu-inner py-1">
    <!-- Dashboard -->
    <li class="menu-item {{ Route::is('dashboard') ? 'active' : '' }}">
      <a href="{{ route('dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div data-i18n="Dashboard">Dashboard</div>
      </a>
    </li>

    <!-- Account Settings -->
    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-dock-top"></i>
        <div data-i18n="Account Settings">Account Settings</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="#" class="menu-link">
            <div data-i18n="Account">Account</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="#" class="menu-link">
            <div data-i18n="Notifications">Notifications</div>
          </a>
        </li>
      </ul>
    </li>

    <!-- Components Section -->
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Components</span>
    </li>

    <!-- Cards -->
    <li class="menu-item">
      <a href="#" class="menu-link">
        <i class="menu-icon tf-icons bx bx-collection"></i>
        <div data-i18n="Cards">Cards</div>
      </a>
    </li>

    <!-- User Interface -->
    <li class="menu-item">
      <a href="javascript:void(0)" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-box"></i>
        <div data-i18n="User interface">User interface</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="#" class="menu-link">
            <div data-i18n="Buttons">Buttons</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="#" class="menu-link">
            <div data-i18n="Modals">Modals</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="#" class="menu-link">
            <div data-i18n="Alerts">Alerts</div>
          </a>
        </li>
      </ul>
    </li>

    <!-- Misc Section -->
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Misc</span>
    </li>
    <li class="menu-item">
      <a href="https://github.com" target="_blank" class="menu-link">
        <i class="menu-icon tf-icons bx bx-support"></i>
        <div data-i18n="Support">Support</div>
      </a>
    </li>
  </ul>
</aside>
