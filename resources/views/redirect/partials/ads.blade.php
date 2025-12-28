{{-- Slot Iklan --}}
<div class="col-lg-3 col-md-6 order-2 order-lg-1">
    <div class="card shadow-sm h-100">
        <div class="card-body d-flex align-items-center justify-content-center">
            <div class="ad-slot text-center w-100">
                <small class="text-muted d-block mb-2">Iklan</small>

                @if(isset($ads[0]))
                    <a href="{{ $ads[0]['link'] }}" target="_blank" rel="noopener noreferrer" style="text-decoration:none;">
                        <img src="{{ $ads[0]['img'] }}" class="img-fluid mb-1" alt="{{ $ads[0]['title'] }}">
                        <div>{{ $ads[0]['title'] }}</div>
                    </a>
                @else
                    <span>Ad Placeholder</span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Slot Iklan --}}
<div class="col-lg-3 col-md-6 order-3 order-lg-3">
    <div class="card shadow-sm h-100">
        <div class="card-body d-flex align-items-center justify-content-center">
            <div class="ad-slot text-center w-100">
                <small class="text-muted d-block mb-2">Iklan</small>

                @if(isset($ads[1]))
                    <a href="{{ $ads[1]['link'] }}" target="_blank" rel="noopener noreferrer" style="text-decoration:none;">
                        <img src="{{ $ads[1]['img'] }}" class="img-fluid mb-1" alt="{{ $ads[1]['title'] }}">
                        <div>{{ $ads[1]['title'] }}</div>
                    </a>
                @else
                    <span>Ad Placeholder</span>
                @endif
            </div>
        </div>
    </div>
</div>
