@extends('panel_control.components.main')

@section('title', 'Daftar Pengguna | Perpustakaan')

@section('content')
  @php
    $rowClasses = ['table-default', 'table-active', 'table-primary', 'table-secondary', 'table-success', 'table-danger', 'table-warning', 'table-info', 'table-light', 'table-dark'];
  @endphp

  <div class="card">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
      <div>
        <h5 class="mb-1">Daftar Pengguna</h5>
        <small class="text-muted">Akun admin dan anggota yang terdaftar di sistem.</small>
      </div>

      @if (session('success'))
        <div class="alert alert-success mb-0 py-2" role="alert">
          {{ session('success') }}
        </div>
      @endif

      @if (session('error'))
        <div class="alert alert-danger mb-0 py-2" role="alert">
          {{ session('error') }}
        </div>
      @endif

      <form method="GET" action="{{ route('users.index') }}" class="row g-3 align-items-end" style="width:100%; max-width:720px;">
        <div class="col-md-9">
          <label for="searchUser" class="form-label">Cari pengguna</label>
          <input
            type="text"
            id="searchUser"
            name="q"
            class="form-control"
            placeholder="Nama, email, role, atau status"
            value="{{ request('q') }}"
          />
        </div>
        <div class="col-md-3 d-flex gap-2 justify-content-end align-items-end">
          <button type="submit" class="btn btn-outline-primary">
            <i class="bx bx-search me-2"></i>Cari
          </button>
          <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
      </form>
    </div>

    <div class="table-responsive text-nowrap">
      <table class="table table-borderless mb-0">
        <thead>
          <tr>
            <th class="text-uppercase text-muted letter-spacing-1">No</th>
            <th class="text-uppercase text-muted letter-spacing-1">Nama</th>
            <th class="text-uppercase text-muted letter-spacing-1">Email</th>
            <th class="text-uppercase text-muted letter-spacing-1">Role</th>
            <th class="text-uppercase text-muted letter-spacing-1">Status</th>
            <th class="text-uppercase text-muted letter-spacing-1 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($users as $index => $user)
            <tr class="{{ $rowClasses[$index % count($rowClasses)] }}">
              <td>{{ $users->firstItem() + $index }}</td>
              <td>
                <div class="d-flex align-items-center">
                  <div class="avatar avatar-sm me-3">
                    <span class="avatar-initial rounded-circle bg-label-{{ $user->isAdmin() ? 'primary' : 'secondary' }}">
                      {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                  </div>
                  <div>
                    <strong>{{ $user->name }}</strong>
                    <div class="small text-muted">Bergabung {{ $user->created_at?->format('d M Y') ?? '-' }}</div>
                  </div>
                </div>
              </td>
              <td>{{ $user->email }}</td>
              <td>
                <span class="badge bg-label-{{ $user->isAdmin() ? 'primary' : 'secondary' }}">
                  {{ ucfirst($user->role) }}
                </span>
              </td>
              <td>
                <span class="badge bg-label-{{ $user->statusBadgeColor() }}">
                  {{ $user->statusLabel() }}
                </span>
              </td>
              <td class="text-center">
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-label="Aksi pengguna">
                    <i class="bx bx-dots-vertical-rounded"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-end">
                    <form action="{{ route('users.update_status', $user) }}" method="POST">
                      @csrf
                      @method('PATCH')
                      <input type="hidden" name="status" value="active" />
                      <button type="submit" class="dropdown-item" @disabled($user->status === 'active' || auth()->id() === $user->id)>
                        Aktifkan
                      </button>
                    </form>
                    <form action="{{ route('users.update_status', $user) }}" method="POST">
                      @csrf
                      @method('PATCH')
                      <input type="hidden" name="status" value="inactive" />
                      <button type="submit" class="dropdown-item" @disabled($user->status === 'inactive' || auth()->id() === $user->id)>
                        Nonaktifkan
                      </button>
                    </form>
                    <form action="{{ route('users.update_status', $user) }}" method="POST" onsubmit="return confirm('Blokir pengguna ini?')">
                      @csrf
                      @method('PATCH')
                      <input type="hidden" name="status" value="blocked" />
                      <button type="submit" class="dropdown-item text-danger" @disabled($user->status === 'blocked' || auth()->id() === $user->id)>
                        Blokir
                      </button>
                    </form>
                  </div>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-4 text-muted">Belum ada data pengguna.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card-footer d-flex justify-content-end">
      {{ $users->links() }}
    </div>
  </div>
@endsection
