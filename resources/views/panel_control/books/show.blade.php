@extends('panel_control.components.main')

@section('title', __('detailBook') . ' | ' . __('dashboard'))

@section('content')
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

  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
      <h4 class="mb-1">{{ __('detailBook') }}</h4>
      <p class="text-muted mb-0">{{ __('detailBookSubtitle') }}</p>
    </div>
    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">{{ __('back') }}</a>
  </div>

  <div class="card">
    <div class="card-body">
      <div class="row g-4">
        <div class="col-lg-8">
          <h5 class="mb-3">{{ $book->title }}</h5>
          <div class="table-responsive">
            <table class="table table-borderless mb-0">
              <tbody>
                <tr>
                  <th style="width: 220px;">{{ __('author') }}</th>
                  <td>{{ $book->author }}</td>
                </tr>
                <tr>
                  <th>{{ __('publisher') }}</th>
                  <td>{{ $book->publisher ?: '-' }}</td>
                </tr>
                <tr>
                  <th>{{ __('year') }}</th>
                  <td>{{ $book->publication_year ?: '-' }}</td>
                </tr>
                <tr>
                  <th>{{ __('category') }}</th>
                  <td>{{ $book->category ?: '-' }}</td>
                </tr>
                <tr>
                  <th>{{ __('isbn') }}</th>
                  <td>{{ $book->isbn ?: '-' }}</td>
                </tr>
                <tr>
                  <th>{{ __('stock') }}</th>
                  <td>{{ $book->stock }}</td>
                </tr>
                <tr>
                  <th>{{ __('description') }}</th>
                  <td>{{ $book->description ?: '-' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="p-4 rounded bg-light h-100">
            <h6 class="mb-3">{{ __('summary') }}</h6>
            <p class="mb-2"><strong>{{ __('added') }}:</strong> {{ $book->created_at?->format('d M Y H:i') ?? '-' }}</p>
            <p class="mb-2"><strong>{{ __('updated') }}:</strong> {{ $book->updated_at?->format('d M Y H:i') ?? '-' }}</p>
            <p class="mb-3"><strong>{{ __('stockStatus') }}</strong> {{ $book->stock > 0 ? __('available') : __('outOfStock') }}</p>

            @php
              $currentUser = auth()->user();
              $isAdmin = $currentUser?->isAdmin() ?? false;
              $isLoanApproved = false;

              if (!$isAdmin && $currentUser) {
                  $isLoanApproved = $currentUser->loans()
                      ->where('book_id', $book->id)
                      ->where('status', 'approved')
                      ->exists();
              }
            @endphp

            @if($book->read_url)
              @if($isAdmin || $isLoanApproved)
                <a href="{{ $book->read_url }}" target="_blank" class="btn btn-primary w-100 mb-3">
                  <i class="bx bx-book-open me-1"></i> {{ __('readBookOnline') }}
                </a>
              @else
                <button class="btn btn-outline-secondary w-100 mb-3" disabled title="{{ __('getApprovalToRead') }}">
                  <i class="bx bx-lock-alt me-1"></i> {{ __('readOnlineLocked') }}
                </button>
              @endif
            @endif

            @if(!auth()->user()->isAdmin())
              <hr>
              @php
                $existingLoan = auth()->user()->loans()->where('book_id', $book->id)->whereIn('status', ['pending', 'approved'])->first();
              @endphp

              @if($existingLoan)
                <div class="alert alert-info py-2 px-3 small mb-0" role="alert">
                  @if($existingLoan->status === 'pending')
                    <i class="bx bx-time-five me-1"></i> Pengajuan peminjaman sedang diproses.
                  @else
                    <i class="bx bx-check-circle me-1"></i> Anda sedang meminjam buku ini.
                  @endif
                </div>
              @elseif($book->stock > 0)
                <form action="{{ route('loans.borrow', $book) }}" method="POST" onsubmit="return confirm('Ajukan peminjaman untuk buku ini?')">
                  @csrf
                  <button type="submit" class="btn btn-primary w-100">
                    <i class="bx bx-bookmark-plus me-1"></i> Ajukan Peminjaman
                  </button>
                </form>
              @else
                <button class="btn btn-secondary w-100" disabled>{{ __('outOfStock') }}</button>
              @endif
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection