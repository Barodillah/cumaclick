@extends('layouts.master')

@section('title', 'Admin Panel')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-1">Admin Dashboard</h4>
            <small class="text-muted">System overview & user management</small>
        </div>

        <div class="btn-group">
            <a href="{{ route('admin.features.index') }}" class="btn btn-light border">
                <i class="fa-solid fa-puzzle-piece me-1 text-primary"></i>
                Features
            </a>
            <a href="{{ route('admin.wallet.index') }}" class="btn btn-light border">
                <i class="fa-solid fa-wallet me-1 text-success"></i>
                Wallet
            </a>
        </div>
    </div>

    {{-- STATS --}}
    <div class="row g-3 mb-4">

        {{-- TOTAL USERS --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-muted">Total Users</small>
                            <h3 class="fw-bold mb-0">{{ number_format($totalUsers) }}</h3>
                        </div>
                        <div class="text-primary opacity-75">
                            <i class="fa-solid fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ACTIVE USERS --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-muted">Verified Users</small>
                            <h3 class="fw-bold mb-0">{{ number_format($activeUsers) }}</h3>
                        </div>
                        <div class="text-success opacity-75">
                            <i class="fa-solid fa-user-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- REVENUE --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <small class="text-muted">Total Revenue</small>
                            <h4 class="fw-bold mb-0">
                                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                            </h4>
                        </div>
                        <div class="text-warning opacity-75">
                            <i class="fa-solid fa-coins fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- USER TABLE --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="fw-semibold mb-0">User Management</h6>
                <small class="text-muted">Latest registered users</small>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light small text-uppercase">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <!-- <th class="text-end">Action</th> -->
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="text-muted">
                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $user->name }}</div>
                                <small class="text-muted">{{ $user->email }}</small>
                            </td>
                            <td>
                                @if ($user->email_verified_at)
                                    <span class="badge bg-success-subtle text-success">
                                        Verified
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">
                                        Unverified
                                    </span>
                                @endif
                            </td>
                            <td class="text-muted">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <!-- <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="#" class="btn btn-light border">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-light border text-danger">
                                        <i class="fa-solid fa-ban"></i>
                                    </a>
                                </div>
                            </td> -->
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                No users available
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users instanceof \Illuminate\Pagination\AbstractPaginator)
            <div class="card-footer bg-white border-top-0">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <div class="card mt-4">
        <div class="card-header fw-bold">
            Topup Terbaru
        </div>
        <div class="card-body table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light small text-uppercase">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Coins</th>
                        <th>Nominal</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topups as $topup)
                        <tr>
                            <td>
                                {{ ($topups->currentPage() - 1) * $topups->perPage() + $loop->iteration }}
                            </td>
                            <td>{{ $topup->user->email ?? '-' }}</td>
                            <td>{{ number_format($topup->coins, 0, ',', '.') }}</td>
                            <td>{{ number_format($topup->gross_amount, 0, ',', '.') }}</td>
                            <td>{{ $topup->payment_type ?? '-' }}</td>
                            <td>
                                <span class="badge 
                                    @if($topup->transaction_status === 'success') bg-success
                                    @elseif($topup->transaction_status === 'pending') bg-warning
                                    @else bg-danger @endif">
                                    {{ ucfirst($topup->transaction_status) }}
                                </span>
                            </td>
                            <td>{{ $topup->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada topup</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-2">
                {{ $topups->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
