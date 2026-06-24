@extends('panel_control.components.main')

@section('title', __('loanHistory') . ' | ' . __('dashboard'))

@section('content')
  @php
    $isAdmin = auth()->user()?->isAdmin() ?? false;
  @endphp

  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
      <h4 class="mb-1">{{ $isAdmin ? __('loanHistory') : __('myLoanHistory') }}</h4>
      <p class="text-muted mb-0">
        {{ $isAdmin ? __('trackLoans') : __('trackLoans') }}
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
            <label for="searchHistory" class="form-label">{{ __('searchLoan') }}</label>
            <input
              type="text"
              id="searchHistory"
              name="q"
              class="form-control"
              placeholder="Cari nama buku atau nama peminjam"
              value="{{ request('q') }}"
            />
          </div>
          <div class="col-md-3 d-flex gap-2 justify-content-end align-items-end">
            <button type="submit" class="btn btn-outline-primary">
              <i class="bx bx-search me-1"></i>{{ __('search') }}
            </button>
            <a href="{{ route('loans.history') }}" class="btn btn-outline-secondary">{{ __('reset') }}</a>
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
            <th>{{ __('tableNo') }}</th>
            @if ($isAdmin)
              <th>{{ __('borrower') }}</th>
            @endif
            <th>{{ __('book') }}</th>
            <th>{{ __('borrowDate') }}</th>
            <th>{{ __('dueDate') }}</th>
            <th>{{ __('returnDate') }}</th>
            <th>{{ __('status') }}</th>
            <th class="text-center">{{ __('actions') }}</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @forelse ($loans as $index => $loan)
            @php
              $borrowDate = \Carbon\Carbon::parse($loan->borrow_date);
              $dueDate = $loan->due_date ? \Carbon\Carbon::parse($loan->due_date) : $borrowDate->copy()->addDays(7);
              $loanDurationDays = $borrowDate->diffInDays($dueDate);
            @endphp
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
                  @if($loan->book?->cover_url)
                    <img
                      src="{{ $loan->book->cover_url }}"
                      alt="{{ $loan->book?->title }}"
                      class="me-2 rounded border bg-white flex-shrink-0"
                      style="width: 36px; height: 54px; object-fit: cover;"
                    >
                  @else
                    <div class="me-2 bg-light rounded border d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 54px;">
                      <i class="bx bx-book text-secondary"></i>
                    </div>
                  @endif
                  <div>
                    <span class="fw-semibold d-block text-truncate" style="max-width: 250px;" title="{{ $loan->book?->title }}">
                      {{ $loan->book?->title }}
                    </span>
                    <small class="text-muted">{{ $loan->book?->author }}</small>
                  </div>
                </div>
              </td>
              <td>{{ $borrowDate->format('d M Y') }}</td>
              <td>
                <span class="fw-semibold">{{ $dueDate->format('d M Y') }}</span>
                <div class="small text-muted">{{ __('loanDurationDays', ['count' => $loanDurationDays]) }}</div>
              </td>
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
                    'pending' => __('pending'),
                    'approved' => __('approved'),
                    'returned' => __('returned'),
                    'rejected' => __('rejected'),
                    default => $loan->status
                  };
                @endphp
                <span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>
              </td>
              <td class="text-center">
                <a href="{{ route('loans.show', $loan) }}" class="btn btn-sm btn-outline-primary">
                  <i class="bx bx-info-circle me-1"></i>{{ __('detail') }}
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="{{ $isAdmin ? 8 : 7 }}" class="text-center py-4 text-muted">Belum ada riwayat peminjaman.</td>
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
