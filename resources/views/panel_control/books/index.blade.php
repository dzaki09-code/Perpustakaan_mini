@extends('panel_control.components.main')

@section('title', 'Manajemen Buku | Perpustakaan')

@section('content')
  @php
    $isAdmin = auth()->user()?->isAdmin() ?? false;
  @endphp

  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
      <h4 class="mb-1">{{ $isAdmin ? 'Manajemen Buku' : 'Daftar Buku' }}</h4>
      <p class="text-muted mb-0">
        {{ $isAdmin ? 'Kelola data koleksi buku perpustakaan mini.' : 'Lihat koleksi buku yang tersedia di perpustakaan.' }}
      </p>
    </div>
    @if ($isAdmin)
      <a href="{{ route('books.create') }}" class="btn btn-primary">
        <i class="bx bx-plus me-1"></i>Tambah Buku
      </a>
    @endif
  </div>

  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card mb-4">
    <div class="card-body">
      <form method="GET" action="{{ route('books.index') }}" class="row g-3 align-items-end">
        <div class="col-md-9">
          <label for="searchBook" class="form-label">Cari buku</label>
          <input
            type="text"
            id="searchBook"
            name="q"
            class="form-control"
            placeholder="Judul, penulis, kategori, atau ISBN"
            value="{{ request('q') }}"
          />
        </div>
        <div class="col-md-3 d-grid gap-2 d-md-flex justify-content-md-end">
          <button type="submit" class="btn btn-outline-primary">
            <i class="bx bx-search me-1"></i>Cari
          </button>
          <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
      </form>
    </div>
  </div>

  <div class="card">
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Kategori</th>
            <th>Tahun</th>
            <th>Stok</th>
            <th>ISBN</th>
            @if ($isAdmin)
              <th class="text-end">Aksi</th>
            @endif
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @forelse ($books as $index => $book)
            <tr>
              <td>{{ $books->firstItem() + $index }}</td>
              <td>
                <strong>{{ $book->title }}</strong>
                <div class="text-muted small">{{ $book->publisher ?: '-' }}</div>
              </td>
              <td>{{ $book->author }}</td>
              <td>{{ $book->category ?: '-' }}</td>
              <td>{{ $book->publication_year ?: '-' }}</td>
              <td>{{ $book->stock }}</td>
              <td>{{ $book->isbn ?: '-' }}</td>
              @if ($isAdmin)
                <td>
                  <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('books.destroy', $book) }}" method="POST" onsubmit="return confirm('Hapus data buku ini?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                  </div>
                </td>
              @endif
            </tr>
          @empty
            <tr>
              <td colspan="{{ $isAdmin ? 8 : 7 }}" class="text-center py-4 text-muted">Belum ada data buku.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card-footer d-flex justify-content-end">
      {{ $books->links() }}
    </div>
  </div>
@endsection
