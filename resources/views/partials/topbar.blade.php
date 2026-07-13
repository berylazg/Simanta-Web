@php
    $initials = collect(explode(' ', auth()->user()->name ?? 'User'))
        ->filter()
        ->map(fn($w) => strtoupper(substr($w, 0, 1)))
        ->take(2)
        ->implode('');
@endphp
<div class="topbar">
    <div>
        <h2>{{ $title ?? '' }}</h2>
        <p>{{ $subtitle ?? '' }}</p>
    </div>
    <div class="topbar-right">
        <div class="topbar-search">
            @include('partials.icon', ['name' => 'search'])
            <input type="text" placeholder="{{ $searchPlaceholder ?? 'Cari tagihan...' }}">
        </div>

        <span class="topbar-date">
            @include('partials.icon', ['name' => 'calendar'])
            {{ now()->translatedFormat('l, d F Y') }}
        </span>

        <div class="notif-wrap">
            <button type="button" onclick="toggleNotif()" class="notif-btn">
                @include('partials.icon', ['name' => 'bell'])
                <span id="notifBadge" class="notif-badge">0</span>
            </button>
            <div id="notifDropdown" class="notif-dropdown">
                <div id="notifList"></div>
            </div>
        </div>

        <a href="{{ route('profile.edit') }}" class="user-chip">
            @if(auth()->user()->foto)
                <img src="{{ asset('storage/foto-profil/' . auth()->user()->foto) }}" class="avatar-img">
            @else
                <div class="avatar">{{ $initials ?: 'U' }}</div>
            @endif
            <div class="user-chip-text">
                <p>{{ Str::limit(auth()->user()->name, 16) }}</p>
                <span>Administrator</span>
            </div>
        </a>
    </div>
</div>
