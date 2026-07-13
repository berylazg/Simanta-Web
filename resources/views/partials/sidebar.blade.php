@php
    $navItems = [
        ['route' => '/dashboard',  'icon' => 'grid',        'label' => 'Dashboard',             'key' => 'dashboard'],
        ['route' => '/monitoring', 'icon' => 'activity',     'label' => 'Monitoring Tagihan',     'key' => 'monitoring'],
        ['route' => '/tagihan',    'icon' => 'database',     'label' => 'Kelola Data Tagihan',    'key' => 'tagihan'],
        ['route' => '/pembayaran', 'icon' => 'credit-card',  'label' => 'Mencatat Pembayaran',    'key' => 'pembayaran'],
        ['route' => '/laporan',    'icon' => 'bar-chart',    'label' => 'Laporan',                'key' => 'laporan'],
        ['route' => '/pengaturan', 'icon' => 'sliders',      'label' => 'Pengaturan',             'key' => 'pengaturan'],
    ];
    $initials = collect(explode(' ', auth()->user()->name ?? 'User'))
        ->filter()
        ->map(fn($w) => strtoupper(substr($w, 0, 1)))
        ->take(2)
        ->implode('');
@endphp
<div class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">@include('partials.icon', ['name' => 'building'])</div>
        <div>
            <h1>SIMANTA</h1>
            <p>Surveyor Indonesia</p>
        </div>
    </div>

    <div class="sidebar-label">Navigasi</div>
    <nav class="sidebar-nav">
        @foreach($navItems as $item)
            <a href="{{ $item['route'] }}" class="{{ ($active ?? '') === $item['key'] ? 'active' : '' }}">
                @include('partials.icon', ['name' => $item['icon']])
                <span>{{ $item['label'] }}</span>
                @if(($active ?? '') === $item['key'])<i class="nav-dot"></i>@endif
            </a>
        @endforeach
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar">{{ $initials ?: 'U' }}</div>
            <div class="sidebar-user-text">
                <p>{{ auth()->user()->name }}</p>
                <span>Administrator</span>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                @include('partials.icon', ['name' => 'log-out'])
                Logout
            </button>
        </form>
    </div>
</div>
