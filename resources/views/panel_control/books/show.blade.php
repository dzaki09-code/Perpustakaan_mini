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
    <div class="card-body p-4">
      <div class="row g-4">
        <div class="col-xl-9">
          <div class="bg-light rounded p-4">
            <div class="row g-4 align-items-start">
              <div class="col-md-4 text-center text-md-start">
                @if($book->cover_url)
                  <img
                    src="{{ $book->cover_url }}"
                    alt="{{ $book->title }}"
                    class="img-fluid rounded border shadow-sm bg-white"
                    style="width: 220px; aspect-ratio: 2 / 3; object-fit: cover;"
                  >
                @else
                  <div class="rounded border bg-white d-inline-flex align-items-center justify-content-center text-muted" style="width: 220px; aspect-ratio: 2 / 3;">
                    <i class="bx bx-book fs-1"></i>
                  </div>
                @endif
              </div>

              <div class="col-md-8">
                <h4 class="mb-2 text-break">{{ $book->title }}</h4>
                <p class="text-muted mb-3">{{ $book->author }}</p>

                <div class="d-flex flex-wrap gap-2 mb-4">
                  <span class="badge bg-label-primary">{{ $book->category ?: __('category') }}</span>
                  <span class="badge {{ $book->stock > 0 ? 'bg-label-success' : 'bg-label-secondary' }}">
                    {{ $book->stock > 0 ? __('available') : __('outOfStock') }}
                  </span>
                </div>

                <div class="table-responsive">
                  <table class="table table-borderless mb-0">
                    <tbody>
                      <tr>
                        <th class="ps-0 text-muted" style="width: 160px;">{{ __('publisher') }}</th>
                        <td class="text-break">{{ $book->publisher ?: '-' }}</td>
                      </tr>
                      <tr>
                        <th class="ps-0 text-muted">{{ __('year') }}</th>
                        <td>{{ $book->publication_year ?: '-' }}</td>
                      </tr>
                      <tr>
                        <th class="ps-0 text-muted">{{ __('category') }}</th>
                        <td class="text-break">{{ $book->category ?: '-' }}</td>
                      </tr>
                      <tr>
                        <th class="ps-0 text-muted">{{ __('isbn') }}</th>
                        <td><code class="text-break">{{ $book->isbn ?: '-' }}</code></td>
                      </tr>
                      <tr>
                        <th class="ps-0 text-muted">{{ __('stock') }}</th>
                        <td>{{ $book->stock }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-4">
            <h5 class="mb-3">{{ __('description') }}</h5>
            <div class="rounded border p-3 bg-white" style="min-height: 150px;">
              <p class="mb-0 text-break">{{ $book->description ?: '-' }}</p>
            </div>
          </div>
        </div>

        <div class="col-xl-3">
          <div class="rounded border p-3 h-100">
            <h6 class="mb-3">{{ __('summary') }}</h6>
            <p class="mb-2"><strong>{{ __('added') }}:</strong> {{ $book->created_at?->format('d M Y H:i') ?? '-' }}</p>
            <p class="mb-2"><strong>{{ __('updated') }}:</strong> {{ $book->updated_at?->format('d M Y H:i') ?? '-' }}</p>
            <p class="mb-0"><strong>{{ __('stockStatus') }}</strong> {{ $book->stock > 0 ? __('available') : __('outOfStock') }}</p>

            @if(!auth()->user()->isAdmin())
              <hr>
              <h6 class="mb-3">{{ __('actions') }}</h6>
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
                <form action="{{ route('loans.borrow', $book) }}" method="POST">
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
