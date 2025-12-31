@extends('layouts.master')

@section('content')
<div class="container py-4">
    <h4 class="mb-4"><a href="{{ route('links.index') }}"><i class="fa-solid fa-angle-left me-2"></i></a> Edit Shortlink</h4>

    @if(session('msg'))
        <div class="alert alert-success">{{ session('msg') }}</div>
    @endif

    <form method="POST" action="{{ route('links.update', $link->short_code) }}">
        @csrf
        @method('PUT')

        {{-- BASIC --}}
        <div class="card mb-4">
            <div class="card-header">Basic</div>
            <div class="card-body row g-3">

                {{-- SHORT CODE --}}
                <div class="col-md-6">
                    <label class="form-label">
                        Short Link <span class="text-danger">*</span>
                    </label>

                    <div class="input-group">
                        <span class="input-group-text" readonly>
                            {{ url('/') }}/
                        </span>
                        <input name="short_code"
                               class="form-control @error('short_code') is-invalid @enderror"
                               value="{{ old('short_code', $link->short_code) }}">
                        @error('short_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- CUSTOM ALIAS --}}
                <div class="col-md-6">
                    <label class="form-label">Custom Alias</label>
                    <input name="custom_alias"
                           class="form-control @error('custom_alias') is-invalid @enderror"
                           value="{{ old('custom_alias', $link->custom_alias) }}">
                    @error('custom_alias')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- DESTINATION URL (ONLY IF TYPE = URL) --}}
                @if($link->destination_type === 'url')
                <div class="col-md-12">
                    <label class="form-label">
                        Destination URL <span class="text-danger">*</span>
                    </label>
                    <input name="destination_url"
                           class="form-control @error('destination_url') is-invalid @enderror"
                           value="{{ old('destination_url', $link->destination_url) }}">
                    @error('destination_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @endif

                {{-- FILE PREVIEW (ONLY IF TYPE = FILE) --}}
                @if($link->destination_type === 'file')
                <div class="col-md-12">
                    <label class="form-label">Destination File</label>

                    <div class="alert alert-info mb-3">
                        File terhubung dengan shortlink ini dan tidak dapat diubah.
                    </div>

                    {{-- INLINE PREVIEW --}}
                    @php
                        $url = $link->destination_url;
                        $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));

                        $images = ['jpg','jpeg','png','webp','gif'];
                        $videos = ['mp4','webm','ogg'];
                    @endphp
                    @if(in_array($ext, $images))
                    <div class="d-flex justify-content-center mb-3">
                        <div class="border rounded shadow-sm p-2"
                            style="max-width:720px;">
                            <img
                                src="{{ route('file.stream', $link->short_code) }}"
                                class="img-fluid rounded"
                                style="max-height:400px; object-fit:contain;">
                        </div>
                    </div>
                    @endif
                    @if($ext === 'pdf')
                    <div class="d-flex justify-content-center mb-3">
                        <div class="border rounded shadow-sm overflow-hidden"
                            style="width:100%; max-width:720px; height:400px;">
                            <embed
                                src="{{ route('file.stream', $link->short_code) }}"
                                type="application/pdf"
                                width="100%"
                                height="100%">
                        </div>
                    </div>
                    @endif
                    @if(in_array($ext, $videos))
                    <div class="d-flex justify-content-center mb-3">
                        <video
                            src="{{ route('file.stream', $link->short_code) }}"
                            controls
                            class="rounded shadow-sm"
                            style="max-width:720px; width:100%; max-height:400px;">
                        </video>
                    </div>
                    @endif
                    @if(!in_array($ext, array_merge($images, $videos, ['pdf'])))
                    <div class="text-center mb-3">
                        <div class="alert alert-secondary">
                            Preview tidak tersedia untuk file <strong>.{{ $ext }}</strong>
                        </div>

                        <a href="{{ route('file.stream', $link->short_code) }}"
                        target="_blank"
                        class="btn btn-outline-primary btn-sm">
                            <i class="fa fa-download"></i> Unduh File
                        </a>
                    </div>
                    @endif

                    <div class="text-center">
                        <a href="{{ route('file.stream', $link->short_code) }}"
                        target="_blank"
                        class="btn btn-sm btn-outline-secondary">
                            <i class="fa fa-up-right-from-square"></i> Buka di Tab Baru
                        </a>
                    </div>
                </div>
                @endif

            </div>
        </div>

        {{-- SECURITY --}}
        <div class="card mb-4">
            <div class="card-header">Security</div>
            <div class="card-body row g-3">

                <div class="col-md-4">
                    <label class="form-label">PIN Code</label>
                    <input name="pin_code"
                           class="form-control @error('pin_code') is-invalid @enderror"
                           value="{{ old('pin_code', $link->pin_code) }}">
                    @error('pin_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label">Password Hint</label>
                    <input name="password_hint"
                           class="form-control @error('password_hint') is-invalid @enderror"
                           value="{{ old('password_hint', $link->password_hint) }}">
                    @error('password_hint')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 form-check">
                    <input type="checkbox" class="form-check-input"
                           name="require_otp"
                           {{ old('require_otp', $link->require_otp) ? 'checked' : '' }}>
                    <label class="form-check-label">Require OTP</label>
                </div>
            </div>
        </div>

        {{-- RULES & STATUS --}}
        <div class="card mb-4">
            <div class="card-header">Rules & Status</div>
            <div class="card-body row g-3">

                <div class="col-md-4">
                    <label>Max Click</label>
                    <input type="number" name="max_click"
                           class="form-control @error('max_click') is-invalid @enderror"
                           value="{{ old('max_click', $link->max_click) }}">
                    @error('max_click')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label>Active From</label>
                    <input type="datetime-local" name="active_from"
                           class="form-control"
                           value="{{ optional($link->active_from)->format('Y-m-d\TH:i') }}">
                </div>

                <div class="col-md-4">
                    <label>Active Until</label>
                    <input type="datetime-local" name="active_until"
                           class="form-control"
                           value="{{ optional($link->active_until)->format('Y-m-d\TH:i') }}">
                </div>

                <div class="col-md-12">
                    <label>Expired At</label>
                    <input type="datetime-local" name="expired_at"
                           class="form-control"
                           value="{{ optional($link->expired_at)->format('Y-m-d\TH:i') }}">
                </div>

                <div class="col-md-12 d-flex gap-3">
                    <div class="form-check">
                        <input type="checkbox" name="is_active"
                               class="form-check-input"
                               {{ old('is_active', $link->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label">Active</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="one_time"
                               class="form-check-input"
                               {{ old('one_time', $link->one_time) ? 'checked' : '' }}>
                        <label class="form-check-label">One Time</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="enable_preview"
                               class="form-check-input"
                               {{ old('enable_preview', $link->enable_preview) ? 'checked' : '' }}>
                        <label class="form-check-label">Enable Preview</label>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" name="enable_ads"
                               class="form-check-input"
                               {{ old('enable_ads', $link->enable_ads) ? 'checked' : '' }}>
                        <label class="form-check-label">Enable Ads</label>
                    </div>
                </div>
            </div>
        </div>

        {{-- META --}}
        <div class="card mb-4">
            <div class="card-header">Meta</div>
            <div class="card-body">
                <label>Title</label>
                <input name="title"
                       class="form-control mb-2"
                       value="{{ old('title', $link->title) }}">

                <label>Description</label>
                <textarea name="description"
                          class="form-control mb-2">{{ old('description', $link->description) }}</textarea>

                <label>Note</label>
                <textarea name="note"
                          class="form-control">{{ old('note', $link->note) }}</textarea>
            </div>
        </div>

        <button class="btn btn-primary">
            <i class="fa-solid fa-save me-1"></i> Simpan Perubahan
        </button>
    </form>
</div>
@endsection
