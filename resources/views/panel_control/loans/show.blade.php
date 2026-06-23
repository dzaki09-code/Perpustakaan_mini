@extends('panel_control.components.main')

@section('title', 'Detail Peminjaman | Perpustakaan')

@section('content')
  @php
    $isAdmin = auth()->user()?->isAdmin() ?? false;
  @endphp

  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
      <h4 class="mb-1">Detail Transaksi Peminjaman</h4>
      <p class="text-muted mb-0">Informasi lengkap mengenai status pengajuan dan detail sirkulasi buku.</p>
    </div>
    <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">
      <i class="bx bx-chevron-left me-1"></i>Kembali
    </a>
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

  <div class="row g-4">
    <!-- Kolom Utama: Informasi Peminjaman & Buku -->
    <div class="col-lg-8">
      <!-- Card Informasi Transaksi -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center pb-2">
          <h5 class="card-title mb-0">Detail Peminjaman</h5>
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
          <span class="badge {{ $statusBadge }} fs-6 px-3 py-2">{{ $statusLabel }}</span>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label text-muted small uppercase">Tanggal Pinjam</label>
              <p class="fw-semibold mb-0 fs-5">
                <i class="bx bx-calendar me-1 text-primary"></i>
                {{ \Carbon\Carbon::parse($loan->borrow_date)->format('d M Y') }}
              </p>
            </div>
            <div class="col-md-6">
              <label class="form-label text-muted small uppercase">Tanggal Pengembalian</label>
              <p class="fw-semibold mb-0 fs-5">
                <i class="bx bx-calendar-check me-1 text-success"></i>
                @if ($loan->return_date)
                  {{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y') }}
                @else
                  <span class="text-muted fw-normal">-</span>
                @endif
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Card Detail Buku -->
      <div class="card">
        <div class="card-header pb-2">
          <h5 class="card-title mb-0">Informasi Buku</h5>
        </div>
        <div class="card-body">
          <div class="d-flex align-items-start gap-4 mb-4">
            <div class="avatar flex-shrink-0 bg-light rounded d-flex align-items-center justify-content-center text-primary" style="width: 64px; height: 64px;">
              <i class="bx bx-book fs-1"></i>
            </div>
            <div>
              <h4 class="mb-1 text-primary">{{ $loan->book?->title }}</h4>
              <p class="mb-2 text-muted fs-6">Ditulis oleh <strong class="text-dark">{{ $loan->book?->author }}</strong></p>
              <span class="badge bg-label-primary text-capitalize">{{ $loan->book?->category ?: 'Lainnya' }}</span>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-borderless">
              <tbody>
                <tr>
                  <th style="width: 180px;" class="pb-2">Penerbit</th>
                  <td class="pb-2">{{ $loan->book?->publisher ?: '-' }}</td>
                </tr>
                <tr>
                  <th class="pb-2">Tahun Terbit</th>
                  <td class="pb-2">{{ $loan->book?->publication_year ?: '-' }}</td>
                </tr>
                <tr>
                  <th class="pb-2">ISBN / Kode Gutenberg</th>
                  <td class="pb-2"><code>{{ $loan->book?->isbn ?: '-' }}</code></td>
                </tr>
                <tr>
                  <th class="pb-2">Deskripsi</th>
                  <td class="pb-2">{{ $loan->book?->description ?: '-' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Kolom Kanan: Peminjam & Tombol Aksi -->
    <div class="col-lg-4">
      @if ($isAdmin)
        <!-- Card Peminjam (Hanya Admin) -->
        <div class="card mb-4">
          <div class="card-header pb-2">
            <h5 class="card-title mb-0">Detail Peminjam</h5>
          </div>
          <div class="card-body">
            <div class="d-flex align-items-center mb-3">
              <div class="avatar avatar-md me-3 bg-light rounded-circle d-flex align-items-center justify-content-center text-secondary">
                <i class="bx bx-user fs-3"></i>
              </div>
              <div>
                <h6 class="mb-0 fw-semibold">{{ $loan->user?->name }}</h6>
                <small class="text-muted">{{ $loan->user?->email }}</small>
              </div>
            </div>
            <hr class="my-3">
            <p class="mb-2 small"><strong class="text-muted text-uppercase">Role:</strong> Anggota</p>
            <p class="mb-0 small">
              <strong class="text-muted text-uppercase">Status Akun:</strong> 
              <span class="badge bg-{{ $loan->user?->statusBadgeColor() }}">{{ $loan->user?->statusLabel() }}</span>
            </p>
          </div>
        </div>
      @endif

      <!-- Card Ringkasan Baca Digital & Aksi -->
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3">Aksi &amp; E-Book</h5>
          
          <!-- Tombol Baca E-Book -->
          @if ($loan->book?->read_url)
            @if ($isAdmin || $loan->status === 'approved')
              <a href="{{ $loan->book->read_url }}" target="_blank" class="btn btn-primary w-100 mb-3">
                <i class="bx bx-book-open me-1"></i> Baca Buku Online
              </a>
            @else
              <button class="btn btn-outline-secondary w-100 mb-3" disabled title="Tersedia setelah peminjaman disetujui">
                <i class="bx bx-lock-alt me-1"></i> Baca Online (Terkunci)
              </button>
              <small class="text-muted d-block text-center mb-3">Dapatkan persetujuan Admin untuk membaca.</small>
            @endif
          @else
            <p class="text-muted small text-center mb-3">Tautan baca digital tidak tersedia untuk buku ini.</p>
          @endif

          <!-- Tombol Tindakan Admin -->
          @if ($isAdmin)
            <hr class="my-3">
            @if ($loan->status === 'pending')
              <div class="d-flex flex-column gap-2">
                <form action="{{ route('loans.approve', $loan) }}" method="POST" onsubmit="return confirm('Setujui peminjaman ini?')">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="btn btn-success w-100">
                    <i class="bx bx-check me-1"></i> Setujui Peminjaman
                  </button>
                </form>
                <form action="{{ route('loans.reject', $loan) }}" method="POST" onsubmit="return confirm('Tolak peminjaman ini?')">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="btn btn-danger w-100">
                    <i class="bx bx-x me-1"></i> Tolak Pengajuan
                  </button>
                </form>
              </div>
            @elseif ($loan->status === 'approved')
              <form action="{{ route('loans.return', $loan) }}" method="POST" onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-info w-100">
                  <i class="bx bx-undo me-1"></i> Konfirmasi Pengembalian
                </button>
              </form>
            @else
              <p class="text-muted text-center mb-0 small">Transaksi ini telah selesai.</p>
            @endif
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection
