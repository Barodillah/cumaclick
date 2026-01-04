@extends('layouts.master')

@section('content')
<style>
    .card { border: none; border-radius: 12px; transition: transform 0.2s; }
    .card:hover { transform: translateY(-5px); }
    .stat-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .bg-soft-primary { background-color: #e0e7ff; color: #4338ca; }
    .bg-soft-success { background-color: #dcfce7; color: #15803d; }
    .bg-soft-info { background-color: #e0f2fe; color: #0369a1; }
    .bg-soft-warning { background-color: #fef9c3; color: #a16207; }
    .table thead th { background-color: #f8f9fa; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; color: #6c757d; border-bottom: none; }
    .btn-copy { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
</style>

<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">Analytics Overview</h3>
            <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}! Monitoring performa link Anda.</p>
        </div>
        <div>
            <a href="{{ route('home') }}" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">
                <i class="fas fa-plus me-2"></i> Create New Link
            </a>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-xl-3 col-sm-6 mb-4 mb-xl-0">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted fw-medium mb-1">Total Links</p>
                            <h2 class="fw-bold mb-0">{{ number_format($totalLinks) }}</h2>
                        </div>
                        <div class="stat-icon bg-soft-primary">
                            <i class="fas fa-link"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-4 mb-xl-0">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted fw-medium mb-1">Total Clicks</p>
                            <h2 class="fw-bold mb-0">{{ number_format($totalClicks) }}</h2>
                        </div>
                        <div class="stat-icon bg-soft-success">
                            <i class="fas fa-mouse-pointer"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-4 mb-xl-0">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted fw-medium mb-1">Unique Visitors</p>
                            <h2 class="fw-bold mb-0">{{ number_format($uniqueClicks) }}</h2>
                        </div>
                        <div class="stat-icon bg-soft-info">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-4 mb-xl-0">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted fw-medium mb-1">Avg. Conversion</p>
                            <h2 class="fw-bold mb-0">{{ $totalClicks ? round(($uniqueClicks / $totalClicks) * 100, 1) : 0 }}%</h2>
                        </div>
                        <div class="stat-icon bg-soft-warning">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $totalClicks ? ($uniqueClicks / $totalClicks) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark">Click Activity (Last 7 Days)</h5>
                    <span class="badge bg-soft-primary text-primary">Live Updates</span>
                </div>
                <div class="border-0 overflow-hidden">
                    <div class="card-body p-4">
                        <div id="clickChart" style="min-height: 350px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="fas fa-fire text-danger me-2"></i> Top Performing Links
                        </h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Short Link</th>
                                <th>Destination</th>
                                <th class="text-center">Total Clicks</th>
                                <th class="text-center">Unique</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topLinks as $link)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <span class="fw-semibold text-primary me-2">{{ url($link->short_code) }}</span>
                                        <button onclick="copyToClipboard('{{ url($link->short_code) }}')" class="btn btn-outline-secondary btn-copy border-0 shadow-none" title="Copy Link">
                                            <i class="far fa-copy"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted d-block" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $link->destination_url }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border px-3 py-2">{{ number_format($link->click_count) }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-primary border px-3 py-2">{{ number_format($link->unique_click_count) }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('links.clicks', $link->short_code) }}" class="btn btn-sm btn-light border">
                                        <i class="fas fa-chart-bar"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="mb-3 opacity-50">
                                    <p class="text-muted">No data available yet. Start by creating a link!</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var options = {
        series: [{
            name: 'Total Clicks',
            data: {!! json_encode($clickData) !!}
        }],
        chart: {
            type: 'area',
            height: 350,
            toolbar: {
                show: false // Sembunyikan toolbar untuk tampilan clean
            },
            zoom: {
                enabled: false
            },
            fontFamily: 'Plus Jakarta Sans, sans-serif' // Gunakan font modern jika ada
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3,
            colors: ['#4338ca']
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [20, 100, 100, 100]
            }
        },
        colors: ['#4338ca'],
        grid: {
            borderColor: '#f1f1f1',
            strokeDashArray: 4,
            xaxis: {
                lines: {
                    show: true
                }
            },
            yaxis: {
                lines: {
                    show: false
                }
            }
        },
        xaxis: {
            categories: {!! json_encode($days) !!},
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
            labels: {
                style: {
                    colors: '#9ca3af',
                    fontSize: '12px'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#9ca3af',
                    fontSize: '12px'
                },
                formatter: function (val) {
                    return val.toFixed(0);
                }
            }
        },
        tooltip: {
            x: {
                format: 'dd MMM'
            },
            y: {
                title: {
                    formatter: (seriesName) => 'Clicks'
                }
            },
            marker: {
                show: false
            },
            theme: 'light'
        },
        markers: {
            size: 5,
            colors: ['#4338ca'],
            strokeColors: '#fff',
            strokeWidth: 2,
            hover: {
                size: 7
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#clickChart"), options);
    chart.render();
</script>
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Link copied to clipboard!');
    });
}
</script>
@endsection