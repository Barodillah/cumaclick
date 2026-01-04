@extends('layouts.master')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #f8f9fa;">

    {{-- HEADER --}}
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold text-dark mb-1">Dashboard Overview</h3>
            <p class="text-muted">Selamat datang kembali, berikut ringkasan sistem hari ini.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="btn-group shadow-sm">
                <a href="{{ route('admin.features.index') }}" class="btn btn-white border-0 px-3">
                    <i class="fa-solid fa-puzzle-piece me-2 text-primary"></i>Features
                </a>
                <a href="{{ route('admin.wallet.index') }}" class="btn btn-white border-0 px-3">
                    <i class="fa-solid fa-wallet me-2 text-success"></i>Wallet
                </a>
            </div>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded-3">
                            <i class="fa-solid fa-users fa-xl text-primary"></i>
                        </div>
                        <div class="ms-3">
                            <small class="text-muted fw-medium d-block mb-1">Total Users</small>
                            <h3 class="fw-bold mb-0">{{ number_format($stats->totalUsers) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 p-3 rounded-3">
                            <i class="fa-solid fa-user-check fa-xl text-success"></i>
                        </div>
                        <div class="ms-3">
                            <small class="text-muted fw-medium d-block mb-1">Verified Users</small>
                            <h3 class="fw-bold mb-0">{{ number_format($stats->activeUsers) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-warning bg-opacity-10 p-3 rounded-3">
                            <i class="fa-solid fa-coins fa-xl text-warning"></i>
                        </div>
                        <div class="ms-3">
                            <small class="text-muted fw-medium d-block mb-1">Total Revenue</small>
                            <h4 class="fw-bold mb-0">Rp{{ number_format($stats->totalRevenue, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-danger bg-opacity-10 p-3 rounded-3">
                            <i class="fa-solid fa-bolt fa-xl text-danger"></i>
                        </div>
                        <div class="ms-3">
                            <small class="text-muted fw-medium d-block mb-1">New Users (Today)</small>
                            <h3 class="fw-bold mb-0">{{ number_format($stats->newUsersToday) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CHARTS ROW --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold">Revenue Trend</h5>
                    <small class="text-muted">Pendapatan harian dalam 7 hari terakhir</small>
                </div>
                <div class="card-body">
                    <div id="revenueChart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold">User Growth</h5>
                    <small class="text-muted">Pendaftaran 7 hari terakhir</small>
                </div>
                <div class="card-body">
                    <div id="userGrowthChart"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLES ROW --}}
    <div class="row g-4">
        {{-- USER TABLE --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">User Management</h6>
                    <span class="badge bg-primary-subtle text-primary rounded-pill">Latest 10</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">User Details</th>
                                <th>Status</th>
                                <th>Joined Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded-circle p-2 me-3 text-center" style="width: 40px">
                                            <i class="fa-solid fa-user text-secondary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $user->name }}</div>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($user->email_verified_at)
                                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">
                                            <i class="fa-solid fa-circle-check me-1"></i> Verified
                                        </span>
                                    @else
                                        <span class="badge bg-light text-secondary px-3 py-2 rounded-pill">Unverified</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $user->created_at->format('d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white border-0 py-3">
                    {{ $users->links() }}
                </div>
            </div>
        </div>

        {{-- TOPUP TABLE --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">Recent Transactions</h6>
                    <span class="badge bg-success-subtle text-success rounded-pill">Topup History</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">Customer</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topups as $topup)
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-medium">{{ $topup->user->email ?? 'Guest' }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold">Rp{{ number_format($topup->gross_amount, 0, ',', '.') }}</div>
                                    <small class="text-primary">{{ number_format($topup->coins) }} Coins</small>
                                </td>
                                <td><span class="badge border text-dark fw-normal">{{ strtoupper($topup->payment_type ?? 'N/A') }}</span></td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'success' => 'bg-success',
                                            'pending' => 'bg-warning text-dark',
                                            'failed'  => 'bg-danger'
                                        ][$topup->transaction_status] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $statusClass }} px-3 py-2 rounded-pill">
                                        {{ ucfirst($topup->transaction_status) }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $topup->created_at->format('d/m H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white border-0 py-3">
                    {{ $topups->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- APEXCHARTS SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Revenue Chart
    var revOptions = {
        series: [{
            name: 'Revenue',
            data: @json($revenueChart)
        }],
        chart: { type: 'area', height: 350, toolbar: { show: false }, zoom: { enabled: false } },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        colors: ['#0d6efd'],
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1, stops: [0, 90, 100] }
        },
        xaxis: { categories: @json($chartLabels) },
        yaxis: { labels: { formatter: function (v) { return "Rp " + v.toLocaleString(); } } }
    };
    new ApexCharts(document.querySelector("#revenueChart"), revOptions).render();

    // User Growth Chart
    var userOptions = {
        series: [{
            name: 'New Users',
            data: @json($userChart)
        }],
        chart: { type: 'bar', height: 350, toolbar: { show: false } },
        plotOptions: { bar: { borderRadius: 8, columnWidth: '50%' } },
        colors: ['#198754'],
        xaxis: { categories: @json($chartLabels) }
    };
    new ApexCharts(document.querySelector("#userGrowthChart"), userOptions).render();
</script>

<style>
    .avatar-sm { display: flex; align-items: center; justify-content: center; }
    .btn-white { background: #fff; color: #444; border: 1px solid #edf2f9; }
    .btn-white:hover { background: #f9fbff; }
    .card { transition: transform 0.2s; }
    .table thead th { font-weight: 600; letter-spacing: 0.5px; border-top: none; }
</style>
@endsection