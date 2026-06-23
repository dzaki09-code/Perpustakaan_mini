@extends('panel_control.components.main')

@section('title', __('loansList') . ' | ' . __('dashboard'))

@section('content')
  @php
    $isAdmin = auth()->user()?->isAdmin() ?? false;
  @endphp

  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
      <h4 class="mb-1">{{ $isAdmin ? __('loanManagement') : __('myLoans') }}</h4>
      <p class="text-muted mb-0">
        {{ $isAdmin ? __('manageLoanApprovals') : __('trackLoans') }}
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
        <form method="GET" action="{{ route('loans.index') }}" class="row g-3 align-items-end">
          <div class="col-md-9">
            <label for="searchLoan" class="form-label">{{ __('searchLoan') }}</label>
            <input
              type="text"
              id="searchLoan"
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
            <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">{{ __('reset') }}</a>
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
            <th>{{ __('returnDate') }}</th>
            <th>{{ __('status') }}</th>
            <th class="text-center">{{ __('actions') }}</th>
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
                    'pending' => __('pending'),
                    'approved' => __('approved'),
                    'returned' => __('returned'),
                    'rejected' => __('rejected'),
                    default => $loan->status
                  };
                @endphp
                <span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>
              </td>
              <td>
                <div class="d-flex justify-content-center gap-2">
                  <a href="{{ route('loans.show', $loan) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-info-circle me-1"></i>{{ __('detail') }}
                  </a>
                  @if ($isAdmin)
                    @if ($loan->status === 'pending')
                      <form action="{{ route('loans.approve', $loan) }}" method="POST" onsubmit="return confirm('{{ __('approveConfirm') }}')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-success">
                          <i class="bx bx-check me-1"></i>{{ __('approve') }}
                        </button>
                      </form>
                      <form action="{{ route('loans.reject', $loan) }}" method="POST" onsubmit="return confirm('{{ __('rejectConfirm') }}')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-danger">
                          <i class="bx bx-x me-1"></i>{{ __('reject') }}
                        </button>
                      </form>
                    @elseif ($loan->status === 'approved')
                      <form action="{{ route('loans.return', $loan) }}" method="POST" onsubmit="return confirm('{{ __('borrowConfirm') }}')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-info">
                          <i class="bx bx-undo me-1"></i>{{ __('return') }}
                        </button>
                      </form>
                    @endif
                  @endif

                  @if (! $isAdmin && $loan->status === 'approved')
                    <form action="{{ route('loans.return', $loan) }}" method="POST" onsubmit="return confirm('{{ __('borrowConfirm') }}')">
                      @csrf
                      @method('PATCH')
                      <button type="submit" class="btn btn-sm btn-info">
                        <i class="bx bx-undo me-1"></i>{{ __('return') }}
                      </button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="{{ $isAdmin ? 7 : 5 }}" class="text-center py-4 text-muted">Belum ada data transaksi peminjaman.</td>
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
