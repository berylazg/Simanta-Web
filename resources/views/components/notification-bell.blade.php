<div style="position:relative;" id="notif-wrapper">
    <button id="notif-bell-btn" style="position:relative; background:none; border:none; cursor:pointer; padding:8px; border-radius:50%; display:flex; align-items:center; justify-content:center;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2">
            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
        </svg>
        <span id="notif-badge" style="display:none; position:absolute; top:4px; right:4px; background:#ef4444; color:white; font-size:10px; font-weight:700; min-width:16px; height:16px; border-radius:50%; align-items:center; justify-content:center; padding:0 3px;"></span>
    </button>

    <div id="notif-dropdown" style="display:none; position:absolute; right:0; top:42px; width:340px; max-height:420px; overflow-y:auto; background:white; border:1px solid #e2e8f0; border-radius:10px; box-shadow:0 10px 25px rgba(0,0,0,0.1); z-index:100;">
        <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 14px; border-bottom:1px solid #f1f5f9;">
            <strong style="font-size:13px; color:#1e293b;">Notifikasi</strong>
            <button id="notif-tandai-semua" style="font-size:11px; color:#005BAC; background:none; border:none; cursor:pointer;">Tandai semua dibaca</button>
        </div>
        <div id="notif-list" style="padding:6px;">
            <p style="text-align:center; color:#94a3b8; font-size:12px; padding:20px;">Memuat...</p>
        </div>
    </div>
</div>

<script>
(function () {
    const btn = document.getElementById('notif-bell-btn');
    const dropdown = document.getElementById('notif-dropdown');
    const badge = document.getElementById('notif-badge');
    const list = document.getElementById('notif-list');
    const tandaiSemuaBtn = document.getElementById('notif-tandai-semua');

    function muatNotifikasi() {
        fetch("{{ route('notifikasi.data') }}")
            .then(res => res.json())
            .then(data => {
                if (data.jumlah_belum_dibaca > 0) {
                    badge.style.display = 'flex';
                    badge.innerText = data.jumlah_belum_dibaca > 9 ? '9+' : data.jumlah_belum_dibaca;
                } else {
                    badge.style.display = 'none';
                }

                if (data.notifikasi.length === 0) {
                    list.innerHTML = '<p style="text-align:center; color:#94a3b8; font-size:12px; padding:20px;">Tidak ada notifikasi</p>';
                    return;
                }

                list.innerHTML = data.notifikasi.map(n => `
                    <div class="notif-item" data-id="${n.id}" style="padding:10px 12px; border-radius:8px; cursor:pointer; background:${n.dibaca ? 'white' : '#eff6ff'}; margin-bottom:2px;">
                        <div style="font-size:12.5px; font-weight:600; color:#1e293b;">${n.judul}</div>
                        <div style="font-size:11.5px; color:#64748b; margin-top:2px;">${n.pesan}</div>
                        <div style="font-size:10.5px; color:#94a3b8; margin-top:4px;">${n.waktu}</div>
                    </div>
                `).join('');

                document.querySelectorAll('.notif-item').forEach(item => {
                    item.addEventListener('click', function () {
                        const id = this.dataset.id;
                        fetch(`/notifikasi/${id}/dibaca`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                            }
                        }).then(() => muatNotifikasi());
                    });
                });
            });
    }

    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        const isOpen = dropdown.style.display === 'block';
        dropdown.style.display = isOpen ? 'none' : 'block';
        if (!isOpen) muatNotifikasi();
    });

    document.addEventListener('click', function (e) {
        if (!document.getElementById('notif-wrapper').contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });

    tandaiSemuaBtn.addEventListener('click', function () {
        fetch("{{ route('notifikasi.dibacaSemua') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            }
        }).then(() => muatNotifikasi());
    });

    muatNotifikasi();
    setInterval(muatNotifikasi, 30000); // refresh tiap 30 detik
})();
</script><div style="position:relative;" id="notif-wrapper">
    <button id="notif-bell-btn" style="position:relative; background:none; border:none; cursor:pointer; padding:8px; border-radius:50%; display:flex; align-items:center; justify-content:center;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2">
            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
        </svg>
        <span id="notif-badge" style="display:none; position:absolute; top:4px; right:4px; background:#ef4444; color:white; font-size:10px; font-weight:700; min-width:16px; height:16px; border-radius:50%; align-items:center; justify-content:center; padding:0 3px;"></span>
    </button>

    <div id="notif-dropdown" style="display:none; position:absolute; right:0; top:42px; width:340px; max-height:420px; overflow-y:auto; background:white; border:1px solid #e2e8f0; border-radius:10px; box-shadow:0 10px 25px rgba(0,0,0,0.1); z-index:100;">
        <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 14px; border-bottom:1px solid #f1f5f9;">
            <strong style="font-size:13px; color:#1e293b;">Notifikasi</strong>
            <button id="notif-tandai-semua" style="font-size:11px; color:#005BAC; background:none; border:none; cursor:pointer;">Tandai semua dibaca</button>
        </div>
        <div id="notif-list" style="padding:6px;">
            <p style="text-align:center; color:#94a3b8; font-size:12px; padding:20px;">Memuat...</p>
        </div>
    </div>
</div>

<script>
(function () {
    const btn = document.getElementById('notif-bell-btn');
    const dropdown = document.getElementById('notif-dropdown');
    const badge = document.getElementById('notif-badge');
    const list = document.getElementById('notif-list');
    const tandaiSemuaBtn = document.getElementById('notif-tandai-semua');

    function muatNotifikasi() {
        fetch("{{ route('notifikasi.data') }}")
            .then(res => res.json())
            .then(data => {
                if (data.jumlah_belum_dibaca > 0) {
                    badge.style.display = 'flex';
                    badge.innerText = data.jumlah_belum_dibaca > 9 ? '9+' : data.jumlah_belum_dibaca;
                } else {
                    badge.style.display = 'none';
                }

                if (data.notifikasi.length === 0) {
                    list.innerHTML = '<p style="text-align:center; color:#94a3b8; font-size:12px; padding:20px;">Tidak ada notifikasi</p>';
                    return;
                }

                list.innerHTML = data.notifikasi.map(n => `
                    <div class="notif-item" data-id="${n.id}" style="padding:10px 12px; border-radius:8px; cursor:pointer; background:${n.dibaca ? 'white' : '#eff6ff'}; margin-bottom:2px;">
                        <div style="font-size:12.5px; font-weight:600; color:#1e293b;">${n.judul}</div>
                        <div style="font-size:11.5px; color:#64748b; margin-top:2px;">${n.pesan}</div>
                        <div style="font-size:10.5px; color:#94a3b8; margin-top:4px;">${n.waktu}</div>
                    </div>
                `).join('');

                document.querySelectorAll('.notif-item').forEach(item => {
                    item.addEventListener('click', function () {
                        const id = this.dataset.id;
                        fetch(`/notifikasi/${id}/dibaca`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                            }
                        }).then(() => muatNotifikasi());
                    });
                });
            });
    }

    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        const isOpen = dropdown.style.display === 'block';
        dropdown.style.display = isOpen ? 'none' : 'block';
        if (!isOpen) muatNotifikasi();
    });

    document.addEventListener('click', function (e) {
        if (!document.getElementById('notif-wrapper').contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });

    tandaiSemuaBtn.addEventListener('click', function () {
        fetch("{{ route('notifikasi.dibacaSemua') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            }
        }).then(() => muatNotifikasi());
    });

    muatNotifikasi();
    setInterval(muatNotifikasi, 30000); // refresh tiap 30 detik
})();
</script>
