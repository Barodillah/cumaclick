<!--  membuat child header -->
@auth
@if (Request::is('links') || Request::is('dashboard') || Request::is('premium') || Request::is('admin') || Request::is('/') || Request::is('files'))
<div class="container nav nav-pills justify-content-center bg-black/20 rounded-md p-2">
    
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.index') }}" class="nav-item nav-link @if (Request::is('admin')) active @endif flex-fill text-center">
                Admin
            </a>
        @endif
    <a href="{{ route('links.dashboard') }}" class="nav-item nav-link @if (Request::is('dashboard')) active @endif flex-fill text-center">
        Dashboard
    </a>
    <a href="{{ route('links.index') }}" class="nav-item nav-link @if (Request::is('links')) active @endif flex-fill text-center">
        My Links
    </a>
    <a href="{{ route('files.index') }}" class="nav-item nav-link @if (Request::is('files')) active @endif flex-fill text-center">
        My Files
    </a>
    <a href="{{ route('links.premium') }}" class="nav-item nav-link @if (Request::is('premium')) active @endif flex-fill text-center">
        <i class="fa-solid fa-coins me-1"></i> {{ $coinBalance }} Coins
    </a>
</div>
@endif
    @endauth