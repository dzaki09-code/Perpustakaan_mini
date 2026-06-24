@extends('panel_control.components.main')

@section('title', __('dashboard') . ' | Perpustakaan')

@section('content')
    <!-- Row 1: Welcome Banner & Top Metrics -->
    <div class="row">
        <!-- Welcome Banner -->
        <div class="col-lg-8 mb-4 order-0">
            <div class="card h-100">
                <div class="d-flex align-items-end row h-100">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">{{ __('welcomeBack', ['name' => auth()->user()->name]) }}</h5>
                            <p class="mb-4">
                                {{ __('dashboardSubtitle', ['role' => auth()->user()->role === 'admin' ? __('admin') : __('member')]) }}
                            </p>
                            <a href="{{ route('books.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="bx bx-book-open me-1"></i>{{ __('viewCollection') }}
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img
                                src="{{ asset('public/assets/img/illustrations/man-with-laptop-light.png') }}"
                                onerror="this.onerror=null; this.src='{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}';"
                                height="140"
                                alt="Dashboard Welcome Illustration"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Metrics (Total Buku & Total Stok) -->
        <div class="col-lg-4 col-md-4 order-1">
            <div class="row h-100">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-2">
                                <div class="avatar flex-shrink-0">
                                    <span class="badge bg-label-primary p-2 rounded">
                                        <i class="bx bx-book-open text-primary fs-3"></i>
                                    </span>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-muted">{{ __('totalTitles') }}</span>
                            <h3 class="card-title mb-1 fw-bold">{{ $bookCount }}</h3>
                            <small class="text-primary fw-medium">{{ __('titlesLabel') }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-2">
                                <div class="avatar flex-shrink-0">
                                    <span class="badge bg-label-success p-2 rounded">
                                        <i class="bx bx-archive text-success fs-3"></i>
                                    </span>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-muted">{{ __('totalStock') }}</span>
                            <h3 class="card-title mb-1 fw-bold">{{ $totalStock }}</h3>
                            <small class="text-success fw-medium">{{ __('copies') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Category Distribution Chart & Secondary Metrics -->
    <div class="row">
        <!-- Distribution Chart -->
        <div class="col-lg-8 col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between pb-0">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2 fw-semibold">{{ __('categoryDistribution') }}</h5>
                        <small class="text-muted">{{ __('categoryDistributionSubtitle') }}</small>
                    </div>
                </div>
                <div class="card-body mt-3">
                    @if(count($chartLabels) > 0)
                        <div id="categoryChart" style="min-height: 250px;"></div>
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center py-5 text-muted">
                            <i class="bx bx-pie-chart-alt fs-1 mb-2"></i>
                            <span>{{ __('noCategoryData') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Secondary Metrics (Total Anggota & Kategori Unik) -->
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="row h-100">
                @if(auth()->user()->isAdmin())
                <div class="col-6 col-md-6 col-lg-12 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-2">
                                <div class="avatar flex-shrink-0">
                                    <span class="badge bg-label-info p-2 rounded">
                                        <i class="bx bx-user text-info fs-3"></i>
                                    </span>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-muted">{{ __('totalUsers') }}</span>
                            <h3 class="card-title mb-1 fw-bold">{{ $userCount }}</h3>
                            <small class="text-info fw-medium">{{ __('registered') }}</small>
                        </div>
                    </div>
                </div>
                @endif
                <div class="{{ auth()->user()->isAdmin() ? 'col-6 col-md-6 col-lg-12' : 'col-12' }} mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-2">
                                <div class="avatar flex-shrink-0">
                                    <span class="badge bg-label-warning p-2 rounded">
                                        <i class="bx bx-category text-warning fs-3"></i>
                                    </span>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1 text-muted">{{ __('uniqueCategories') }}</span>
                            <h3 class="card-title mb-1 fw-bold">{{ $categoryCount }}</h3>
                            <small class="text-warning fw-medium">{{ __('categoriesLabel') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Latest Books & Latest Registered Users -->
    <div class="row">
        <!-- Latest Books -->
        <div class="{{ auth()->user()->isAdmin() ? 'col-md-6 col-lg-6' : 'col-12' }} mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between pb-3">
                    <h5 class="card-title m-0 me-2 fw-semibold">{{ __('latestBooks') }}</h5>
                    <a href="{{ route('books.index') }}" class="btn btn-sm btn-outline-primary">
                        {{ __('viewAll') }}
                    </a>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr class="table-light">
                                <th>{{ __('bookInfo') }}</th>
                                <th>{{ __('category') }}</th>
                                <th class="text-center">{{ __('stock') }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($latestBooks as $book)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($book->cover_url)
                                                <img
                                                    src="{{ $book->cover_url }}"
                                                    alt="{{ $book->title }}"
                                                    class="me-2 rounded border bg-white flex-shrink-0"
                                                    style="width: 36px; height: 54px; object-fit: cover;"
                                                >
                                            @else
                                                <div class="me-2 bg-light rounded border d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 54px;">
                                                    <i class="bx bx-book text-secondary"></i>
                                                </div>
                                            @endif
                                            <div class="d-inline-block">
                                                <span class="fw-semibold d-block text-truncate" style="max-width: 180px;" title="{{ $book->title }}">
                                                    {{ $book->title }}
                                                </span>
                                                <small class="text-muted text-truncate d-block" style="max-width: 180px;">{{ $book->author }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-primary text-capitalize">
                                            {{ $book->category ?: __('other') }}
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold">{{ $book->stock }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">{{ __('noDataBooks') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if(auth()->user()->isAdmin())
        <!-- Latest Registered Users -->
        <div class="col-md-6 col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between pb-3">
                    <h5 class="card-title m-0 me-2 fw-semibold">{{ __('latestUsers') }}</h5>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary">
                            {{ __('viewAll') }}
                        </a>
                    @endif
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr class="table-light">
                                <th>{{ __('userName') }}</th>
                                <th>{{ __('tableRole') }}</th>
                                <th>{{ __('tableStatus') }}</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @forelse($latestUsers as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar flex-shrink-0 me-2 bg-light rounded d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                <i class="bx bx-user text-secondary"></i>
                                            </div>
                                            <div class="d-inline-block">
                                                <span class="fw-semibold d-block text-truncate" style="max-width: 180px;">{{ $user->name }}</span>
                                                <small class="text-muted text-truncate d-block" style="max-width: 180px;">{{ $user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-{{ $user->isAdmin() ? 'danger' : 'info' }} text-capitalize">
                                            {{ $user->role === 'admin' ? __('admin') : __('member') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->statusBadgeColor() }}">
                                            {{ $user->statusLabel() }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">{{ __('noDataUsers') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

@push('scripts')
    @if(count($chartLabels) > 0)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartEl = document.querySelector('#categoryChart');
            if (chartEl) {
                const labels = @json($chartLabels);
                const series = @json($chartSeries);

                const chartOptions = {
                    chart: {
                        height: 250,
                        type: 'donut',
                        parentHeightOffset: 0,
                        toolbar: {
                            show: false
                        }
                    },
                    labels: labels,
                    series: series,
                    colors: [
                        '#696cff', // primary
                        '#71dd37', // success
                        '#03c3ec', // info
                        '#ffab00', // warning
                        '#ff3e1d', // danger
                        '#8592a3', // secondary
                        '#001f3f', // dark blue
                        '#39cccc', // teal
                        '#f012be', // magenta
                        '#01ff70'  // lime
                    ],
                    stroke: {
                        width: 5,
                        colors: ['#fff']
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    legend: {
                        show: true,
                        position: 'right',
                        horizontalAlign: 'center',
                        labels: {
                            colors: '#566a7f',
                            useSeriesColors: false
                        },
                        markers: {
                            width: 12,
                            height: 12,
                            radius: 100
                        },
                        itemMargin: {
                            horizontal: 5,
                            vertical: 3
                        }
                    },
                    grid: {
                        padding: {
                            top: 0,
                            bottom: 0,
                            right: 15,
                            left: 15
                        }
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '75%',
                                labels: {
                                    show: true,
                                    value: {
                                        fontSize: '1.2rem',
                                        fontFamily: 'Public Sans',
                                        color: '#566a7f',
                                        offsetY: -10,
                                        formatter: function (val) {
                                            return parseInt(val) + ' {{ __('titlesUnit') }}';
                                        }
                                    },
                                    name: {
                                        offsetY: 20,
                                        fontFamily: 'Public Sans'
                                    },
                                    total: {
                                        show: true,
                                        fontSize: '0.85rem',
                                        color: '#a1acb8',
                                        label: '{{ __('totalTitles') }}',
                                        formatter: function (w) {
                                            return w.globals.seriesTotals.reduce((a, b) => {
                                                return a + b;
                                            }, 0) + ' {{ __('titlesUnit') }}';
                                        }
                                    }
                                }
                            }
                        }
                    },
                    responsive: [
                        {
                            breakpoint: 991,
                            options: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    ]
                };

                const chart = new ApexCharts(chartEl, chartOptions);
                chart.render();
            }
        });
    </script>
    @endif
@endpush
