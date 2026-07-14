@php
  $currentUser = auth()->user();
  $isAdmin = $currentUser?->isAdmin() ?? false;
@endphp

<!-- Sidebar Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <!-- Brand Logo -->
  <div class="app-brand demo">
    <a href="{{ route('dashboard') }}" class="app-brand-link">
      <span class="app-brand-logo demo">
        <i class="bx bx-book-open fs-3 text-primary"></i>
      </span>
      <span class="app-brand-text demo menu-text fw-bolder ms-1 fs-4">{{ __('miniLibrary') }}</span>
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
        <div>{{ __('dashboard') }}</div>
      </a>
    </li>

    <!-- Manajemen Buku -->
    <li class="menu-item {{ Route::is('books.*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-book-open"></i>
        <div>{{ __('managementBooks') }}</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ Route::is('books.index') ? 'active' : '' }}">
          <a href="{{ route('books.index') }}" class="menu-link">
            <div>{{ __('booksList') }}</div>
          </a>
        </li>
        @if ($isAdmin)
          <li class="menu-item {{ Route::is('books.create') ? 'active' : '' }}">
            <a href="{{ route('books.create') }}" class="menu-link">
              <div>{{ __('addBook') }}</div>
            </a>
          </li>
        @endif
      </ul>
    </li>

    <!-- Peminjaman sesuai dengan role -->
    @if(auth()->user()->isAdmin())
      <li class="menu-item {{ Route::is('loans.index') ? 'active' : '' }}">
        <a href="{{ route('loans.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-book-reader"></i>
          <div>{{ __('loans') }}</div>
        </a>
      </li>
    @endif
    @if(auth()->user()->isUser())
      <li class="menu-item {{ Route::is('loans.index') ? 'active' : '' }}">
        <a href="{{ route('loans.index') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-book-reader"></i>
          <div>{{ __('myLoansNav') }}</div>
        </a>
      </li>
    @endif

    <!-- Riwayat -->
    @if(auth()->user()->isAdmin())
      <li class="menu-item {{ Route::is('loans.history') ? 'active' : '' }}">
        <a href="{{ route('loans.history') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-history"></i>
          <div>{{ __('loanHistoryNav') }}</div>
        </a>
      </li>
    @endif
    @if(auth()->user()->isUser())
      <li class="menu-item {{ Route::is('loans.history') ? 'active' : '' }}">
        <a href="{{ route('loans.history') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-history"></i>
          <div>{{ __('myLoanHistory') }}</div>
        </a>
      </li>
    @endif

    <li class="menu-item {{ Route::is('profile.*') ? 'active' : '' }}">
      <a href="{{ route('profile.edit') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user-circle"></i>
        <div>{{ __('myProfile') }}</div>
      </a>
    </li>

    @if ($isAdmin)
      <!-- Pengguna -->
      <li class="menu-item {{ Route::is('users.*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons bx bx-user"></i>
          <div>{{ __('usersNav') }}</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item {{ Route::is('users.index') ? 'active' : '' }}">
            <a href="{{ route('users.index') }}" class="menu-link">
              <div>{{ __('usersTitle') }}</div>
            </a>
          </li>
        </ul>
      </li>
    @endif

  </ul>
</aside>
