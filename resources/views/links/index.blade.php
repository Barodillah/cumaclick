@extends('layouts.master')

@section('content')
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-semibold mb-0">Links</h4>
        <a href="/" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Create new
        </a>
    </div>

    {{-- Toolbar --}}
    <div class="row g-2 mb-3 align-items-center">

        {{-- SEARCH --}}
        <div class="col-12 col-md-4 col-lg-3">
            <div class="input-group">
                <span class="input-group-text bg-white">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text"
                    id="searchInput"
                    class="form-control"
                    placeholder="Search links">
            </div>
        </div>

        {{-- FILTER DATE --}}
        <div class="col-6 col-md-3 col-lg-auto">
            <button class="btn btn-outline-secondary w-100">
                <i class="fa-solid fa-calendar me-1"></i>
                <span class="d-none d-lg-inline">Filter by created date</span>
                <span class="d-inline d-lg-none">Date</span>
            </button>
        </div>

        {{-- ADD FILTER --}}
        <div class="col-6 col-md-3 col-lg-auto">
            <button class="btn btn-outline-secondary w-100">
                <i class="fa-solid fa-sliders me-1"></i>
                <span class="d-none d-lg-inline">Add filters</span>
                <span class="d-inline d-lg-none">Filters</span>
            </button>
        </div>

        {{-- SHOW STATUS --}}
        <div class="col-12 col-md-2 col-lg-auto ms-lg-auto">
            <select class="form-select">
                <option>Show: Active</option>
                <option>Show: Expired</option>
                <option>Show: All</option>
            </select>
        </div>

    </div>

    {{-- List --}}
    <div id="linkList">
        @include('links.partials.list', ['links' => $links])
    </div>

</div>

@include('links.partials.modals')
@endsection
