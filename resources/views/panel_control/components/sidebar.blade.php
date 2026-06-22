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

    <!-- Manajemen Buku -->
    <li class="menu-item {{ Route::is('books.*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-book-open"></i>
        <div data-i18n="Manajemen Buku">Manajemen Buku</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ Route::is('books.index') ? 'active' : '' }}">
          <a href="{{ route('books.index') }}" class="menu-link">
            <div data-i18n="Daftar Buku">Daftar Buku</div>
          </a>
        </li>
        @if ($isAdmin)
          <li class="menu-item {{ Route::is('books.create') ? 'active' : '' }}">
            <a href="{{ route('books.create') }}" class="menu-link">
              <div data-i18n="Tambah Buku">Tambah Buku</div>
            </a>
          </li>
        @endif
      </ul>
    </li>

    @if ($isAdmin)
      <!-- Peminjaman -->
      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons bx bx-transfer"></i>
          <div data-i18n="Peminjaman">Peminjaman</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item">
            <a href="#" class="menu-link">
              <div data-i18n="Daftar Peminjaman">Daftar Peminjaman</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="#" class="menu-link">
              <div data-i18n="Tambah Peminjaman">Tambah Peminjaman</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="#" class="menu-link">
              <div data-i18n="Pengembalian">Pengembalian</div>
            </a>
          </li>
        </ul>
      </li>
    @endif

    <!-- Riwayat -->
    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-history"></i>
        <div data-i18n="Riwayat">Riwayat</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="#" class="menu-link">
            <div data-i18n="Riwayat Peminjaman">Riwayat Peminjaman</div>
          </a>
        </li>
      </ul>
    </li>

    @if ($isAdmin)
      <!-- Pengguna -->
      <li class="menu-item {{ Route::is('users.*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons bx bx-user"></i>
          <div data-i18n="Pengguna">Pengguna</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item {{ Route::is('users.index') ? 'active' : '' }}">
            <a href="{{ route('users.index') }}" class="menu-link">
              <div data-i18n="Daftar Pengguna">Daftar Pengguna</div>
            </a>
          </li>
        </ul>
      </li>
    @endif

    <!-- Akun -->
    <li class="menu-item">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-user-circle"></i>
        <div data-i18n="Akun">Akun</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item">
          <a href="#" class="menu-link">
            <div data-i18n="Profil">Profil</div>
          </a>
        </li>
        <li class="menu-item">
          <a href="{{ route('signout') }}" class="menu-link">
            <div data-i18n="Logout">Logout</div>
          </a>
        </li>
      </ul>
    </li>
  </ul>
</aside>
