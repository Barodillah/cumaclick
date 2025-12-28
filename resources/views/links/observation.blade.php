@extends('layouts.master')
@section('title', 'Link Observation - ' . $link->short_code)

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11 col-md-12">

            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <a href="{{ route('links.index') }}"><i class="fa-solid fa-angle-left me-2"></i></a> Link Observation
                    </h4>
                    <span class="badge {{ $link->is_active ? 'bg-success' : 'bg-danger' }}">
                        {{ $link->is_active ? 'ACTIVE' : 'INACTIVE' }}
                    </span>
                </div>

                <div class="card-body">

                    {{-- BASIC INFO --}}
                    <h6 class="text-muted mb-3">
                        <i class="fa-solid fa-circle-info me-2"></i> Basic Information
                    </h6>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-2">
                            <strong>Short Code</strong><br>
                            <span class="text-primary">{{ $link->short_code }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Custom Alias</strong><br>
                            {{ $link->custom_alias ?? '-' }}
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Destination Type</strong><br>
                            <span class="badge bg-secondary">{{ strtoupper($link->destination_type) }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Destination URL</strong><br>
                            @if($link->destination_url)
                                <a href="{{ $link->destination_url }}" target="_blank">
                                    {{ $link->destination_url }}
                                </a>
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    {{-- SECURITY --}}
                    <h6 class="text-muted mb-3">
                        <i class="fa-solid fa-shield-halved me-2"></i> Security & Access
                    </h6>

                    <div class="row mb-4">
                        <div class="col-md-4 mb-2">
                            <strong>PIN Protected</strong><br>
                            {{ $link->pin_code ? 'Yes' : 'No' }}
                        </div>
                        <div class="col-md-4 mb-2">
                            <strong>Password Hint</strong><br>
                            {{ $link->password_hint ?? '-' }}
                        </div>
                        <div class="col-md-4 mb-2">
                            <strong>Require OTP</strong><br>
                            {{ $link->require_otp ? 'Yes' : 'No' }}
                        </div>
                    </div>

                    {{-- STATUS & LIMIT --}}
                    <h6 class="text-muted mb-3">
                        <i class="fa-solid fa-toggle-on me-2"></i> Status & Limitation
                    </h6>

                    <div class="row mb-4">
                        <div class="col-md-3 mb-2">
                            <strong>One Time</strong><br>
                            {{ $link->one_time ? 'Yes' : 'No' }}
                        </div>
                        <div class="col-md-3 mb-2">
                            <strong>Max Click</strong><br>
                            {{ $link->max_click ?? 'Unlimited' }}
                        </div>
                        <div class="col-md-3 mb-2">
                            <strong>Click Count</strong><br>
                            {{ $link->click_count ?? 0 }}
                        </div>
                        <div class="col-md-3 mb-2">
                            <strong>Abuse Score</strong><br>
                            {{ $link->abuse_score ?? 0 }}
                        </div>
                    </div>

                    {{-- TIME --}}
                    <h6 class="text-muted mb-3">
                        <i class="fa-solid fa-clock me-2"></i> Time Information
                    </h6>

                    <div class="row mb-4">
                        <div class="col-md-4 mb-2">
                            <strong>Active From</strong><br>
                            {{ optional($link->active_from)->format('d M Y H:i') ?? '-' }}
                        </div>
                        <div class="col-md-4 mb-2">
                            <strong>Active Until</strong><br>
                            {{ optional($link->active_until)->format('d M Y H:i') ?? '-' }}
                        </div>
                        <div class="col-md-4 mb-2">
                            <strong>Expired At</strong><br>
                            {{ optional($link->expired_at)->format('d M Y H:i') ?? '-' }}
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Last Clicked At</strong><br>
                            {{ optional($link->last_clicked_at)->format('d M Y H:i') ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Blocked At</strong><br>
                            {{ optional($link->blocked_at)->format('d M Y H:i') ?? '-' }}
                        </div>
                    </div>

                    {{-- META --}}
                    <h6 class="text-muted mb-3">
                        <i class="fa-solid fa-tags me-2"></i> Metadata
                    </h6>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-2">
                            <strong>Title</strong><br>
                            {{ $link->title ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Description</strong><br>
                            {{ $link->description ?? '-' }}
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Enable Preview</strong><br>
                            {{ $link->enable_preview ? 'Yes' : 'No' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Note</strong><br>
                            {{ $link->note ?? '-' }}
                        </div>
                    </div>

                    {{-- SYSTEM --}}
                    <h6 class="text-muted mb-3">
                        <i class="fa-solid fa-server me-2"></i> System Info
                    </h6>

                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <strong>User ID</strong><br>
                            {{ $link->user_id }}
                        </div>
                        <div class="col-md-4 mb-2">
                            <strong>Created IP</strong><br>
                            {{ $link->created_ip ?? '-' }}
                        </div>
                        <div class="col-md-4 mb-2">
                            <strong>User Agent</strong><br>
                            <small class="text-muted">{{ $link->created_ua ?? '-' }}</small>
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Created At</strong><br>
                            {{ $link->created_at->format('d M Y, H:i') }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Updated At</strong><br>
                            {{ $link->updated_at->format('d M Y, H:i') }}
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
