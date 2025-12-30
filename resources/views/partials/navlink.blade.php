<!--  membuat child header -->
@if (Request::is('links') || Request::is('dashboard') || Request::is('premium'))
<div class="container nav nav-pills justify-content-center bg-black/20 rounded-md p-2">
    <a href="{{ route('links.dashboard') }}" class="nav-item nav-link @if (Request::is('dashboard')) active @endif flex-fill text-center">
        Dashboard
    </a>
    <a href="{{ route('links.index') }}" class="nav-item nav-link @if (Request::is('links')) active @endif flex-fill text-center">
        My Links
    </a>
    <a href="{{ route('links.premium') }}" class="nav-item nav-link @if (Request::is('premium')) active @endif flex-fill text-center">
        <i class="fa-solid fa-coins me-1"></i> 50 Coins
    </a>
</div>
@endif