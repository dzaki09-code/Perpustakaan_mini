@extends('panel_control.components.main')

@section('title', 'Dashboard | Perpustakaan')

@section('content')
  <div class="row">
    <div class="col-lg-8 mb-4 order-0">
      <div class="card">
        <div class="d-flex align-items-end row">
          <div class="col-sm-7">
            <div class="card-body">
              <h5 class="card-title text-primary">Selamat datang di Perpustakaan Mini</h5>
              <p class="mb-4">Gunakan dashboard ini untuk mengelola data perpustakaan.</p>
            </div>
          </div>
          <div class="col-sm-5 text-center text-sm-left">
            <div class="card-body pb-0 px-0 px-md-4">
              <img
                src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}"
                height="140"
                alt="Dashboard"
                data-app-dark-img="illustrations/man-with-laptop-dark.png"
                data-app-light-img="illustrations/man-with-laptop-light.png"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-4 order-1">
      <div class="row">
        <div class="col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <span class="fw-semibold d-block mb-1">Buku</span>
              <h3 class="card-title mb-2">{{ $bookCount }}</h3>
              <small class="text-muted">Total data</small>
            </div>
          </div>
        </div>
        <div class="col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <span class="fw-semibold d-block mb-1">Anggota</span>
              <h3 class="card-title mb-2">{{ $userCount }}</h3>
              <small class="text-muted">Total data</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
