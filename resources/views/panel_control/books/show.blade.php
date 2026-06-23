@extends('panel_control.components.main')

@section('title', 'Detail Buku | Perpustakaan')

@section('content')
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
      <h4 class="mb-1">Detail Buku</h4>
      <p class="text-muted mb-0">Informasi lengkap buku yang sudah ditambahkan ke sistem.</p>
    </div>
    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Kembali</a>
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
                  <th style="width: 220px;">Penulis</th>
                  <td>{{ $book->author }}</td>
                </tr>
                <tr>
                  <th>Penerbit</th>
                  <td>{{ $book->publisher ?: '-' }}</td>
                </tr>
                <tr>
                  <th>Tahun Terbit</th>
                  <td>{{ $book->publication_year ?: '-' }}</td>
                </tr>
                <tr>
                  <th>Kategori</th>
                  <td>{{ $book->category ?: '-' }}</td>
                </tr>
                <tr>
                  <th>ISBN</th>
                  <td>{{ $book->isbn ?: '-' }}</td>
                </tr>
                <tr>
                  <th>Stok</th>
                  <td>{{ $book->stock }}</td>
                </tr>
                <tr>
                  <th>Deskripsi</th>
                  <td>{{ $book->description ?: '-' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="p-4 rounded bg-light h-100">
            <h6 class="mb-3">Ringkasan</h6>
            <p class="mb-2"><strong>Ditambahkan:</strong> {{ $book->created_at?->format('d M Y H:i') ?? '-' }}</p>
            <p class="mb-2"><strong>Diperbarui:</strong> {{ $book->updated_at?->format('d M Y H:i') ?? '-' }}</p>
            <p class="mb-0"><strong>Status stok:</strong> {{ $book->stock > 0 ? 'Tersedia' : 'Habis' }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection