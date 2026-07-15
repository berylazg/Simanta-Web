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

        <div class="user-chip">
            <div class="avatar">
                {{ $initials }}
            </div>

            <div class="user-chip-text">
                <p>{{ Str::limit(auth()->user()->name,16) }}</p>
                <span>Administrator</span>
            </div>
        </div>
    </div>
</div>
