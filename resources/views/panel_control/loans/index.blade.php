@extends('panel_control.components.main')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">

        <div class="card-header">
            <h4 class="mb-0">
                Data Peminjaman Buku
            </h4>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead>
                        <tr>

                            <th>No</th>

                            @if(auth()->user()->isAdmin())
                                <th>Peminjam</th>
                            @endif

                            <th>Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>

                            @if(auth()->user()->isAdmin())
                                <th>Aksi</th>
                            @endif

                        </tr>
                    </thead>

                    <tbody>

                        @forelse($loans as $loan)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            @if(auth()->user()->isAdmin())
                            <td>
                                {{ $loan->user->name }}
                            </td>
                            @endif

                            <td>
                                {{ $loan->book->title }}
                            </td>

                            <td>
                                {{ $loan->borrow_date }}
                            </td>

                            <td>
                                {{ $loan->return_date ?? '-' }}
                            </td>

                            <td>

                                @if($loan->status == 'pending')
                                    <span class="badge bg-warning">
                                        Pending
                                    </span>

                                @elseif($loan->status == 'approved')
                                    <span class="badge bg-primary">
                                        Dipinjam
                                    </span>

                                @elseif($loan->status == 'returned')
                                    <span class="badge bg-success">
                                        Dikembalikan
                                    </span>

                                @else
                                    <span class="badge bg-danger">
                                        Ditolak
                                    </span>
                                @endif

                            </td>

                            @if(auth()->user()->isAdmin())

                            <td>

                                @if($loan->status == 'pending')

                                    <form action="{{ route('loans.approve',$loan) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf
                                        @method('PATCH')

                                        <button class="btn btn-success btn-sm">
                                            Approve
                                        </button>

                                    </form>

                                    <form action="{{ route('loans.reject',$loan) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf
                                        @method('PATCH')

                                        <button class="btn btn-danger btn-sm">
                                            Reject
                                        </button>

                                    </form>

                                @elseif($loan->status == 'approved')

                                    <form action="{{ route('loans.return',$loan) }}"
                                          method="POST">

                                        @csrf
                                        @method('PATCH')

                                        <button class="btn btn-warning btn-sm">
                                            Return
                                        </button>

                                    </form>

                                @endif

                            </td>

                            @endif

                        </tr>

                        @empty

                        <tr>
                            <td colspan="7" class="text-center">
                                Belum ada data peminjaman
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
