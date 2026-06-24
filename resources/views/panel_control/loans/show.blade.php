@extends('panel_control.components.main')

@section('title', __('loanDetail') . ' | ' . __('dashboard'))

@section('content')
  @php
    $isAdmin = auth()->user()?->isAdmin() ?? false;
    $borrowDate = \Carbon\Carbon::parse($loan->borrow_date);
    $dueDate = $loan->due_date ? \Carbon\Carbon::parse($loan->due_date) : $borrowDate->copy()->addDays(7);
    $loanDurationDays = $borrowDate->diffInDays($dueDate);
  @endphp

  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
      <h4 class="mb-1">{{ __('loanDetail') }}</h4>
      <p class="text-muted mb-0">{{ __('loanTransactionDetail') }}</p>
    </div>
    <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">
      <i class="bx bx-chevron-left me-1"></i>{{ __('back') }}
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
    <div class="{{ $isAdmin ? 'col-lg-8' : 'col-12' }}">
      <!-- Card Informasi Transaksi -->
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center pb-2">
          <h5 class="card-title mb-0">{{ __('loanDetail') }}</h5>
          @php
            $statusBadge = match($loan->status) {
              'pending' => 'bg-label-warning',
              'approved' => 'bg-label-success',
              'returned' => 'bg-label-info',
              'rejected' => 'bg-label-danger',
              default => 'bg-label-secondary'
            };
            $statusLabel = match($loan->status) {
              'pending' => __('pending'),
              'approved' => __('approved'),
              'returned' => __('returned'),
              'rejected' => __('rejected'),
              default => $loan->status
            };
          @endphp
          <span class="badge {{ $statusBadge }} fs-6 px-3 py-2">{{ $statusLabel }}</span>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label text-muted small uppercase">{{ __('borrowDate') }}</label>
              <p class="fw-semibold mb-0 fs-5">
                <i class="bx bx-calendar me-1 text-primary"></i>
                {{ $borrowDate->format('d M Y') }}
              </p>
            </div>
            <div class="col-md-4">
              <label class="form-label text-muted small uppercase">{{ __('dueDate') }}</label>
              <p class="fw-semibold mb-1 fs-5">
                <i class="bx bx-calendar-exclamation me-1 text-warning"></i>
                {{ $dueDate->format('d M Y') }}
              </p>
              <small class="text-muted">{{ __('loanDuration') }}: {{ __('loanDurationDays', ['count' => $loanDurationDays]) }}</small>
            </div>
            <div class="col-md-4">
              <label class="form-label text-muted small uppercase">{{ __('returnDate') }}</label>
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
          <h5 class="card-title mb-0">{{ __('bookDetail') }}</h5>
        </div>
        <div class="card-body">
          <div class="d-flex flex-wrap align-items-start gap-4 mb-4">
            @if($loan->book?->cover_url)
              <img
                src="{{ $loan->book->cover_url }}"
                alt="{{ $loan->book?->title }}"
                class="rounded border shadow-sm bg-white flex-shrink-0"
                style="width: 96px; height: 144px; object-fit: cover;"
              >
            @else
              <div class="rounded border bg-light d-flex align-items-center justify-content-center text-primary flex-shrink-0" style="width: 96px; height: 144px;">
                <i class="bx bx-book fs-1"></i>
              </div>
            @endif
            <div class="flex-grow-1" style="min-width: 220px;">
              <h4 class="mb-1 text-primary">{{ $loan->book?->title }}</h4>
              <p class="mb-2 text-muted fs-6">{{ __('author') }}: <strong class="text-dark">{{ $loan->book?->author }}</strong></p>
              <span class="badge bg-label-primary text-capitalize">{{ $loan->book?->category ?: __('other') }}</span>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-borderless">
              <tbody>
                <tr>
                  <th style="width: 180px;" class="pb-2">{{ __('publisher') }}</th>
                  <td class="pb-2">{{ $loan->book?->publisher ?: '-' }}</td>
                </tr>
                <tr>
                  <th class="pb-2">{{ __('year') }}</th>
                  <td class="pb-2">{{ $loan->book?->publication_year ?: '-' }}</td>
                </tr>
                <tr>
                  <th class="pb-2">{{ __('isbn') }}</th>
                  <td class="pb-2"><code>{{ $loan->book?->isbn ?: '-' }}</code></td>
                </tr>
                <tr>
                  <th class="pb-2">{{ __('description') }}</th>
                  <td class="pb-2">{{ $loan->book?->description ?: '-' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Kolom Kanan: Peminjam & Tombol Aksi -->
    @if ($isAdmin)
      <div class="col-lg-4">
        <!-- Card Peminjam (Hanya Admin) -->
        <div class="card mb-4">
          <div class="card-header pb-2">
            <h5 class="card-title mb-0">{{ __('borrowerDetail') }}</h5>
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
            <p class="mb-2 small"><strong class="text-muted text-uppercase">{{ __('tableRole') }}:</strong> {{ $loan->user?->isAdmin() ? __('admin') : __('member') }}</p>
            <p class="mb-0 small">
              <strong class="text-muted text-uppercase">{{ __('tableStatus') }}:</strong> 
              <span class="badge bg-{{ $loan->user?->statusBadgeColor() }}">{{ $loan->user?->statusLabel() }}</span>
            </p>
          </div>
        </div>

        <!-- Card Aksi -->
        <div class="card">
          <div class="card-body">
            <h5 class="card-title mb-3">{{ __('actions') }}</h5>
            @if ($loan->status === 'pending')
              <div class="d-flex flex-column gap-2">
                <form action="{{ route('loans.approve', $loan) }}" method="POST">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="btn btn-success w-100">
                    <i class="bx bx-check me-1"></i> {{ __('approveLoan') }}
                  </button>
                </form>
                <form action="{{ route('loans.reject', $loan) }}" method="POST">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="btn btn-danger w-100">
                    <i class="bx bx-x me-1"></i> {{ __('rejectLoan') }}
                  </button>
                </form>
              </div>
            @elseif ($loan->status === 'approved')
              <form action="{{ route('loans.return', $loan) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-info w-100">
                  <i class="bx bx-undo me-1"></i> {{ __('confirmReturn') }}
                </button>
              </form>
            @else
              <p class="text-muted text-center mb-0 small">{{ __('transactionCompleted') }}</p>
            @endif
          </div>
        </div>
      </div>
    @endif
  </div>
@endsection
