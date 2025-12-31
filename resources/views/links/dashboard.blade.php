@extends('layouts.master')
@section('content')
<div class="container py-4">
    <h4 class="fw-semibold mb-2">Dashboard</h4>
    <p>Welcome to your dashboard! Here you can manage your links and view analytics.</p>
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Links</h6>
                    <h3 class="fw-bold">{{ $totalLinks }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Total Clicks</h6>
                    <h3 class="fw-bold">{{ $totalClicks }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Unique Clicks</h6>
                    <h3 class="fw-bold">{{ $uniqueClicks }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h6 class="text-muted">Conversion Rate</h6>
                    <h3 class="fw-bold">
                        {{ $totalClicks ? round(($uniqueClicks / $totalClicks) * 100, 1) : 0 }}%
                    </h3>
                </div>
            </div>
        </div>

        <!-- //top links -->
        <div class="col-12 mt-4">
            <h4 class="mb-2 fw-semibold">Top 5 Links</h4>
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle">
                <thead>
                    <tr>
                        <th>Short URL</th>
                        <th>Original URL</th>
                        <th>Total Clicks</th>
                        <th>Unique Clicks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topLinks as $link)
                    <tr>
                        <td><a href="{{ url($link->short_code) }}" target="_blank">{{ url($link->short_code) }}</a></td>
                        <td>{{ Str::limit($link->destination_url, 50) }}</td>
                        <td>{{ $link->click_count }}</td>
                        <td>{{ $link->unique_click_count }}</td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection