@extends('panel_control.components.main')

@section('title', 'Riwayat Peminjaman | Perpustakaan')

@section('content')
  @php
    $isAdmin = auth()->user()?->isAdmin() ?? false;
  @endphp

  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
      <h4 class="mb-1">{{ $isAdmin ? 'Riwayat Peminjaman' : 'Riwayat Peminjaman Saya' }}</h4>
      <p class="text-muted mb-0">
        {{ $isAdmin ? 'Lihat riwayat peminjaman buku yang sudah selesai atau ditolak.' : 'Lihat riwayat peminjaman buku Anda yang sudah selesai atau ditolak.' }}
      </p>
    </div>
  </div>

  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if ($isAdmin)
    <div class="card mb-4">
      <div class="card-body">
        <form method="GET" action="{{ route('loans.history') }}" class="row g-3 align-items-end">
          <div class="col-md-9">
            <label for="searchHistory" class="form-label">Cari riwayat</label>
            <input
              type="text"
              id="searchHistory"
              name="q"
              class="form-control"
              placeholder="Cari nama buku atau nama peminjam"
              value="{{ request('q') }}"
            />
          </div>
          <div class="col-md-3 d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="submit" class="btn btn-outline-primary">
              <i class="bx bx-search me-1"></i>Cari
            </button>
            <a href="{{ route('loans.history') }}" class="btn btn-outline-secondary">Reset</a>
          </div>
        </form>
      </div>
    </div>
  @endif

  <div class="card">
    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
        <thead>
          <tr class="table-light">
            <th>#</th>
            @if ($isAdmin)
              <th>Peminjam</th>
            @endif
            <th>Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @forelse ($loans as $index => $loan)
            <tr>
              <td>{{ $index + 1 }}</td>
              @if ($isAdmin)
                <td>
                  <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm me-2 bg-light rounded d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                      <i class="bx bx-user text-secondary"></i>
                    </div>
                    <div>
                      <span class="fw-semibold d-block">{{ $loan->user?->name }}</span>
                      <small class="text-muted">{{ $loan->user?->email }}</small>
                    </div>
                  </div>
                </td>
              @endif
              <td>
                <div class="d-flex align-items-center">
                  <div class="avatar avatar-sm me-2 bg-light rounded d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i class="bx bx-book text-secondary"></i>
                  </div>
                  <div>
                    <span class="fw-semibold d-block text-truncate" style="max-width: 250px;" title="{{ $loan->book?->title }}">
                      {{ $loan->book?->title }}
                    </span>
                    <small class="text-muted">{{ $loan->book?->author }}</small>
                  </div>
                </div>
              </td>
              <td>{{ \Carbon\Carbon::parse($loan->borrow_date)->format('d M Y') }}</td>
              <td>
                @if ($loan->return_date)
                  {{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y') }}
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
              <td>
                @php
                  $statusBadge = match($loan->status) {
                    'pending' => 'bg-label-warning',
                    'approved' => 'bg-label-success',
                    'returned' => 'bg-label-info',
                    'rejected' => 'bg-label-danger',
                    default => 'bg-label-secondary'
                  };
                  $statusLabel = match($loan->status) {
                    'pending' => 'Menunggu Persetujuan',
                    'approved' => 'Sedang Dipinjam',
                    'returned' => 'Sudah Dikembalikan',
                    'rejected' => 'Ditolak',
                    default => $loan->status
                  };
                @endphp
                <span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>
              </td>
              <td class="text-center">
                <a href="{{ route('loans.show', $loan) }}" class="btn btn-sm btn-outline-primary">
                  <i class="bx bx-info-circle me-1"></i>Detail
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="{{ $isAdmin ? 7 : 5 }}" class="text-center py-4 text-muted">Belum ada riwayat peminjaman.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer d-flex justify-content-end">
      {{ $loans->withQueryString()->links() }}
    </div>
  </div>
@endsection
