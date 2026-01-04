@extends('layouts.master')

@section('content')
<div class="container py-4">

    {{-- Header Section --}}
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold text-dark mb-0">
            <a href="{{ route('links.index') }}" class="text-decoration-none">
                <i class="fa-solid fa-angle-left text-primary me-2"></i>
            </a>    
            Click Analytics
            </h3>
            <p class="text-muted mb-0 small">Monitoring performance for: <span class="fw-medium text-primary">/{{ $link->short_code }}</span></p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 me-2" onclick="window.location.reload()">
                <i class="fa-solid fa-rotate me-1"></i> Refresh
            </button>
            <a href="{{ $link->destination_url }}" target="_blank" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="fa-solid fa-external-link me-1"></i> Visit Link
            </a>
        </div>
    </div>

    {{-- Key Metrics Summary --}}
    <div class="row mb-4 g-3">
        @php
            $humanClicks = $totalClicks - $botClicks;
            $conversionRate = $totalClicks ? round(($uniqueClicks / $totalClicks) * 100, 1) : 0;
        @endphp

        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small fw-medium mb-1">Total Clicks</div>
                    <div class="h4 fw-bold mb-0">{{ number_format($totalClicks) }}</div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small fw-medium mb-1">Unique Users</div>
                    <div class="h4 fw-bold mb-0">{{ number_format($uniqueClicks) }}</div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100 border-start border-success border-4">
                <div class="card-body">
                    <div class="text-muted small fw-medium mb-1 text-success">Human</div>
                    <div class="h4 fw-bold mb-0 text-success">{{ number_format($humanClicks) }}</div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100 border-start border-danger border-4">
                <div class="card-body">
                    <div class="text-muted small fw-medium mb-1 text-danger">Bot / Crawlers</div>
                    <div class="h4 fw-bold mb-0 text-danger">{{ number_format($botClicks) }}</div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small fw-medium mb-1">Conv. Rate</div>
                    <div class="h4 fw-bold mb-0">{{ $conversionRate }}%</div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm h-100 bg-light">
                <div class="card-body">
                    <div class="text-muted small fw-medium mb-1">Last Activity</div>
                    <div class="fw-bold small text-truncate">
                        {{ optional($clicks->first())->clicked_at?->diffForHumans() ?? '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Analytics Charts --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Clicks Over Time (Last 30 Days)</h6>
                </div>
                <div class="card-body">
                    <div id="dailyClicksChart" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4 g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between">
                    <h6 class="mb-0 fw-bold">Device Breakdown</h6>
                    <i class="fa-solid fa-mobile-screen-button text-muted"></i>
                </div>
                <div class="card-body">
                    <div id="deviceChart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between">
                    <h6 class="mb-0 fw-bold">Browsers</h6>
                    <i class="fa-solid fa-globe text-muted"></i>
                </div>
                <div class="card-body">
                    <div id="browserChart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between">
                    <h6 class="mb-0 fw-bold">Top Countries</h6>
                    <i class="fa-solid fa-earth-americas text-muted"></i>
                </div>
                <div class="card-body">
                    <div id="countryChart"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4 g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Operating Systems</h6>
                </div>
                <div class="card-body">
                    <div id="osChart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Traffic Source (UTM)</h6>
                </div>
                <div class="card-body">
                    <div id="utmChart"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Log Table --}}
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Click Log Details</h6>
            <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill">Total Data: {{ $clicks->total() }}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 border-0 text-muted small text-uppercase">Time</th>
                        <th class="border-0 text-muted small text-uppercase">IP Address</th>
                        <th class="border-0 text-muted small text-uppercase">Location</th>
                        <th class="border-0 text-muted small text-uppercase">Device / OS</th>
                        <th class="border-0 text-muted small text-uppercase">Source</th>
                        <th class="border-0 text-muted small text-uppercase text-center">Type</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($clicks as $click)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-medium">{{ $click->clicked_at->format('d M Y') }}</div>
                            <div class="text-muted small">{{ $click->clicked_at->format('H:i:s') }}</div>
                        </td>
                        <td>
                            <code class="text-primary fw-medium">{{ $click->ip_address }}</code>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="me-2 fw-semibold">{{ $click->country ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="small fw-medium">{{ $click->browser }}</div>
                            <div class="text-muted extra-small">{{ ucfirst($click->device_type) }} • {{ $click->os }}</div>
                        </td>
                        <td class="text-truncate" style="max-width:220px;">
                            <span class="text-muted small" title="{{ $click->referer }}">
                                {{ $click->referer ?? 'Direct' }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($click->is_bot)
                                <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-3">Bot</span>
                            @else
                                <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3">Human</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-inbox fa-3x mb-3 opacity-25"></i>
                            <p class="mb-0">No analytical data recorded yet.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{-- Pagination --}}
        @if($clicks->hasPages())
        <div class="card-footer bg-white py-3 border-0">
            <div class="d-flex justify-content-center">
                {{ $clicks->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.1); }
    .extra-small { font-size: 0.75rem; }
    .table thead th { font-weight: 600; letter-spacing: 0.5px; }
    .card { transition: transform 0.2s ease; }
    .card:hover { transform: translateY(-2px); }
</style>

{{-- Script section remains similar but with refined chart colors --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    const defaultColors = ['#0d6efd', '#20c997', '#ffc107', '#fd7e14', '#6610f2', '#0dcaf0'];

    // Daily Clicks Chart
    new ApexCharts(document.querySelector("#dailyClicksChart"), {
        chart: { type: 'area', height: 350, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
        series: [{ name: 'Clicks', data: @json(array_values($dailyClicks->toArray())) }],
        xaxis: { categories: @json(array_keys($dailyClicks->toArray())) },
        colors: ['#0d6efd'],
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05 } },
        stroke: { curve: 'smooth', width: 3 },
        dataLabels: { enabled: false }
    }).render();

    // Device Chart
    new ApexCharts(document.querySelector("#deviceChart"), {
        chart: { type: 'donut', height: 280 },
        series: @json(array_values($deviceStats->toArray())),
        labels: @json(array_keys($deviceStats->toArray())),
        colors: defaultColors,
        legend: { position: 'bottom' },
        plotOptions: { pie: { donut: { size: '75%' } } }
    }).render();

    // Browser Chart
    new ApexCharts(document.querySelector("#browserChart"), {
        chart: { type: 'bar', height: 250, toolbar: { show: false } },
        plotOptions: { bar: { borderRadius: 4, horizontal: true } },
        series: [{ name: 'Clicks', data: @json(array_values($browserStats->toArray())) }],
        xaxis: { categories: @json(array_keys($browserStats->toArray())) },
        colors: ['#0dcaf0']
    }).render();

    // OS Chart
    new ApexCharts(document.querySelector("#osChart"), {
        chart: { type: 'bar', height: 250, toolbar: { show: false } },
        plotOptions: { bar: { borderRadius: 4, columnWidth: '45%' } },
        series: [{ name: 'Clicks', data: @json(array_values($osStats->toArray())) }],
        xaxis: { categories: @json(array_keys($osStats->toArray())) },
        colors: ['#6610f2']
    }).render();

    // Country Chart
    new ApexCharts(document.querySelector("#countryChart"), {
        chart: { type: 'bar', height: 250, toolbar: { show: false } },
        plotOptions: { bar: { borderRadius: 4, horizontal: true } },
        series: [{ name: 'Clicks', data: @json(array_values($countryStats->toArray())) }],
        xaxis: { categories: @json(array_keys($countryStats->toArray())) },
        colors: ['#20c997']
    }).render();

    // UTM Chart
    new ApexCharts(document.querySelector("#utmChart"), {
        chart: { type: 'donut', height: 280 },
        series: @json(array_values($utmSourceStats->toArray())),
        labels: @json(array_keys($utmSourceStats->toArray())),
        colors: defaultColors,
        legend: { position: 'bottom' }
    }).render();
});
</script>
@endsection