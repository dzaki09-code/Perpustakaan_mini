@extends('panel_control.components.main')

@section('title', ($mode === 'edit' ? 'Edit' : 'Tambah') . ' Buku | Perpustakaan')

@section('content')
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
      <h4 class="mb-1">{{ $mode === 'edit' ? 'Edit Buku' : 'Tambah Buku' }}</h4>
      <p class="text-muted mb-0">Isi data buku dengan benar agar mudah dikelola.</p>
    </div>
    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Kembali</a>
  </div>

  <div class="card">
    <div class="card-body">
      <form
        method="POST"
        action="{{ $mode === 'edit' ? route('books.update', $book) : route('books.store') }}"
      >
        @csrf
        @if ($mode === 'edit')
          @method('PUT')
        @endif

        <div class="row g-3">
          <div class="col-md-8">
            <label for="title" class="form-label">Judul Buku</label>
            <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $book->title) }}" required />
            @error('title')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-4">
            <label for="author" class="form-label">Penulis</label>
            <input type="text" id="author" name="author" class="form-control @error('author') is-invalid @enderror" value="{{ old('author', $book->author) }}" required />
            @error('author')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-4">
            <label for="publisher" class="form-label">Penerbit</label>
            <input type="text" id="publisher" name="publisher" class="form-control @error('publisher') is-invalid @enderror" value="{{ old('publisher', $book->publisher) }}" />
            @error('publisher')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-2">
            <label for="publication_year" class="form-label">Tahun</label>
            <input type="number" id="publication_year" name="publication_year" class="form-control @error('publication_year') is-invalid @enderror" value="{{ old('publication_year', $book->publication_year) }}" min="1500" max="{{ date('Y') }}" />
            @error('publication_year')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-3">
            <label for="category" class="form-label">Kategori</label>
            <input type="text" id="category" name="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category', $book->category) }}" />
            @error('category')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-3">
            <label for="isbn" class="form-label">ISBN</label>
            <input type="text" id="isbn" name="isbn" class="form-control @error('isbn') is-invalid @enderror" value="{{ old('isbn', $book->isbn) }}" />
            @error('isbn')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-2">
            <label for="stock" class="form-label">Stok</label>
            <input type="number" id="stock" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $book->stock ?? 0) }}" min="0" required />
            @error('stock')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-12">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $book->description) }}</textarea>
            @error('description')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
          <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Batal</a>
          <button type="submit" class="btn btn-primary">{{ $mode === 'edit' ? 'Perbarui' : 'Simpan' }}</button>
        </div>
      </form>
    </div>
  </div>
@endsection