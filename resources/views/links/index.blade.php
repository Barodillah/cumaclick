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
        <div class="col-12 col-md-6 col-lg-7">
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
        <div class="col-6 col-md-3 col-lg-3">
            <button class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#customRangeModal">
                <i class="fa-solid fa-calendar me-1"></i>
                <span class="d-none d-lg-inline">Filter by created date</span>
                <span class="d-inline d-lg-none">Date</span>
            </button>
        </div>

        <!-- Modal Custom Range -->
        <div class="modal fade" id="customRangeModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa-solid fa-calendar-days me-1"></i> Filter by created date</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <form method="GET" action="">
                            <div class="mb-3">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" id="startDate" name="startDate" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" id="endDate" name="endDate" class="form-control">
                            </div>

                            <!-- Quick Filter Buttons -->
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <button type="button" class="btn btn-light quick-range" data-days="1">Last hour</button>
                                <button type="button" class="btn btn-light quick-range" data-days="0">Today</button>
                                <button type="button" class="btn btn-light quick-range" data-days="7">Last 7 days</button>
                                <button type="button" class="btn btn-light quick-range" data-days="30">Last 30 days</button>
                                <button type="button" class="btn btn-light quick-range" data-days="60">Last 60 days</button>
                                <button type="button" class="btn btn-light quick-range" data-days="90">Last 90 days</button>
                            </div>
                            
                            <div class="modal-footer">
                                <a href="javascript:void(0)" class="btn btn-outline-secondary clearFilters"><i class="fa-solid fa-xmark me-1"></i> Clear all filters</a>
                                <button type="button" data-bs-dismiss="modal" class="btn btn-primary">Apply</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <script>
        document.querySelectorAll('.quick-range').forEach(button => {
            button.addEventListener('click', function() {
                const days = parseInt(this.getAttribute('data-days'));
                const end = new Date();
                let start;

                if(days === 1 && this.textContent.includes('hour')) {
                    // Last hour
                    start = new Date(end.getTime() - 60 * 60 * 1000);
                } else if(days === 0) {
                    // Today
                    start = new Date();
                } else {
                    start = new Date();
                    start.setDate(end.getDate() - days);
                }

                // Format YYYY-MM-DD
                const formatDate = d => d.toISOString().split('T')[0];

                document.getElementById('startDate').value = formatDate(start);
                document.getElementById('endDate').value = formatDate(end);
            });
        });
        </script>

        {{-- ADD FILTER --}}
        <div class="col-6 col-md-3 col-lg-2">
            <button class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fa-solid fa-sliders me-1"></i>
                <span class="d-none d-lg-inline">Add filters</span>
                <span class="d-inline d-lg-none">Filters</span>
            </button>
        </div>
        
        {{-- Modal Filter --}}
        <div class="modal fade" id="filterModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa-solid fa-sliders me-1"></i> Add filters</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <form method="GET" action="">
                            
                            <div class="mb-3">
                                <label for="typeFilter" class="form-label">Type</label>
                                <select id="typeFilter" name="type" class="form-select">
                                    <option value="">-- Select Type --</option>
                                    <option value="url">Url</option>
                                    <option value="file">File</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="statusFilter" class="form-label">Status</label>
                                <select id="statusFilter" name="status" class="form-select">
                                    <option value="">-- Select Status --</option>
                                    <option value="active">Active</option>
                                    <option value="expired">Expired</option>
                                    <option value="blocked">Blocked</option>
                                </select>
                            </div>

                            <div class="modal-footer">
                                <a href="javascript:void(0)" class="btn btn-outline-secondary clearFilters"><i class="fa-solid fa-xmark me-1"></i> Clear all filters</a>
                                <button type="button" data-bs-dismiss="modal" class="btn btn-primary">Apply</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- SHOW STATUS --}}
        <!-- <div class="col-12 col-md-2 col-lg-auto ms-lg-auto">
            <select class="form-select">
                <option>Show: Active</option>
                <option>Show: Expired</option>
                <option>Show: All</option>
            </select>
        </div> -->

    </div>

    {{-- List --}}
    <div id="linkList">
        @include('links.partials.list', ['links' => $links])
    </div>

</div>

@include('links.partials.modals')
@endsection
