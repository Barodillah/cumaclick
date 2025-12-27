@extends('layouts.master')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-1">
            <a href="{{ route('links.index') }}">
                <i class="fa-solid fa-angle-left me-2"></i>
            </a>
            Click Analytics</h4>
            <div class="text-muted small">{{ $link->short_code }}</div>
        </div>
    </div>

    {{-- SUMMARY --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Clicks</h6>
                    <h3 class="fw-bold">{{ $totalClicks }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Unique Clicks</h6>
                    <h3 class="fw-bold">{{ $uniqueClicks }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Conversion Rate</h6>
                    <h3 class="fw-bold">
                        {{ $totalClicks ? round(($uniqueClicks / $totalClicks) * 100, 1) : 0 }}%
                    </h3>
                </div>
            </div>
        </div>
    </div>

    {{-- BREAKDOWN --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">Devices</div>
                <ul class="list-group list-group-flush">
                    @foreach($deviceStats as $device => $count)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ ucfirst($device ?? 'Unknown') }}</span>
                            <span>{{ $count }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">Browsers</div>
                <ul class="list-group list-group-flush">
                    @foreach($browserStats as $browser => $count)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $browser ?? 'Unknown' }}</span>
                            <span>{{ $count }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">Countries</div>
                <ul class="list-group list-group-flush">
                    @forelse($countryStats as $country => $count)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $country ?? 'Unknown' }}</span>
                            <span>{{ $count }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted text-center">No data</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- CLICK TABLE --}}
    <div class="card shadow-sm">
        <div class="card-header fw-semibold">Click Details</div>
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Time</th>
                        <th>IP</th>
                        <th>Device</th>
                        <th>Browser</th>
                        <th>OS</th>
                        <th>Referer</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($clicks as $click)
                    <tr>
                        <td>{{ $click->clicked_at->format('d M Y H:i') }}</td>
                        <td>{{ $click->ip_address }}</td>
                        <td>{{ ucfirst($click->device_type) }}</td>
                        <td>{{ $click->browser }}</td>
                        <td>{{ $click->os }}</td>
                        <td class="text-truncate" style="max-width:200px;">
                            {{ $click->referer ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No clicks recorded
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
