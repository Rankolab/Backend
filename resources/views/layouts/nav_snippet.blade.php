@auth
    @if(Auth::user()->role === 'super_admin')
        <li class="nav-item"><a class="nav-link" href="{{ route('super.agent') }}">Super Agent</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('admin.delegation') }}">Delegation</a></li>
    @endif
@endauth