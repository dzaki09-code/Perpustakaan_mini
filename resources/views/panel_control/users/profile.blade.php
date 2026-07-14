@extends('panel_control.components.main')

@section('title', __('myProfile') . ' | Perpustakaan')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card shadow-sm border-0">
      <div class="card-body p-4 p-lg-5">
        <div class="d-flex flex-column align-items-center justify-content-center text-center mb-4">
          <div>
            <h4 class="mb-1 fw-bold">{{ __('myProfile') }}</h4>
          
          </div>
          <div class="dropdown mt-4">
            <button class="btn btn-outline-secondary btn-icon dropdown-toggle p-0 border-0 bg-transparent d-flex align-items-center justify-content-center" type="button" id="profilePhotoMenu" data-bs-toggle="dropdown" aria-expanded="false" style="width: 120px; height: 120px;">
              <div id="photoPreviewContainer" class="rounded-circle border overflow-hidden" style="width: 120px; height: 120px; display: inline-flex; align-items: center; justify-content: center;">
                @if ($user->profile_photo_path)
                  <img id="photoPreview" src="{{ Storage::disk('public')->url($user->profile_photo_path) }}" alt="Foto profil" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                  <span id="photoPreview" class="avatar-initial rounded-circle bg-label-primary fs-3 d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                    <i class="bx bx-moon"></i>
                  </span>
                @endif
              </div>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profilePhotoMenu">
              <li><button type="button" class="dropdown-item" id="viewProfilePhotoButton">{{ __('myProfile') }}</button></li>
              <li><button type="button" class="dropdown-item" id="importPhotoButton">{{ __('importProfilePhoto') }}</button></li>
            </ul>
          </div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <input type="file" class="d-none @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*">
          @error('photo')
            <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror

          <div class="row g-3">

            <div class="col-md-6">
              <label for="name" class="form-label">{{ __('name') }}</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
              @error('name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label for="email" class="form-label">{{ __('email') }}</label>
              <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
              @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label for="password" class="form-label">Password Baru</label>
              <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" autocomplete="new-password">
              @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-6">
              <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
            </div>
          </div>

          <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="bx bx-save me-1"></i>{{ __('save') }}
            </button>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
              <i class="bx bx-arrow-back me-1"></i>{{ __('cancel') }}
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="profilePhotoModal" tabindex="-1" aria-labelledby="profilePhotoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="profilePhotoModalLabel">{{ __('viewProfilePhoto') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        @if ($user->profile_photo_path)
          <img id="modalPhotoPreview" src="{{ Storage::disk('public')->url($user->profile_photo_path) }}" alt="Foto profil" class="img-fluid rounded">
        @else
          <p class="text-muted">{{ __('noProfilePhoto') }}</p>
        @endif
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const photoInput = document.getElementById('photo');
    const photoPreviewContainer = document.getElementById('photoPreviewContainer');
    const importPhotoButton = document.getElementById('importPhotoButton');
    const viewProfilePhotoButton = document.getElementById('viewProfilePhotoButton');
    const profilePhotoModal = document.getElementById('profilePhotoModal');

    if (importPhotoButton && photoInput) {
      importPhotoButton.addEventListener('click', function () {
        photoInput.click();
      });
    }

    if (viewProfilePhotoButton && profilePhotoModal) {
      viewProfilePhotoButton.addEventListener('click', function () {
        const modal = new bootstrap.Modal(profilePhotoModal);
        modal.show();
      });
    }

    if (photoInput && photoPreviewContainer) {
      photoInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (!file) {
          return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
          photoPreviewContainer.innerHTML = '<img id="photoPreview" src="' + e.target.result + '" alt="Foto profil" style="width: 100%; height: 100%; object-fit: cover;">';
          const modalPhotoPreview = document.getElementById('modalPhotoPreview');
          if (modalPhotoPreview) {
            modalPhotoPreview.src = e.target.result;
          }
        };
        reader.readAsDataURL(file);
      });
    }
  });
</script>
@endpush
@endsection
