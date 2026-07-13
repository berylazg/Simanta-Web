<script>
async function toggleNotif() {
    const dropdown = document.getElementById('notifDropdown');
    const isOpen = dropdown.style.display === 'block';
    dropdown.style.display = isOpen ? 'none' : 'block';
    if (!isOpen) await muatNotifikasi();
}

async function muatNotifikasi() {
    const res = await fetch("{{ route('notifikasi.data') }}");
    const data = await res.json();
    const list = document.getElementById('notifList');
    const badge = document.getElementById('notifBadge');

    list.innerHTML = '';
    if (data.notifikasi.length === 0) {
        list.innerHTML = '<p style="padding:20px;text-align:center;color:#94a3b8;font-size:13px;">Tidak ada notifikasi</p>';
    } else {
        data.notifikasi.forEach(n => {
            const warna = (n.tipe === 'paid' || n.tipe === 'lunas') ? '#16a34a'
                        : (n.tipe === 'overdue' || n.tipe === 'terlambat') ? '#dc2626'
                        : '#ca8a04';
            list.innerHTML += `
                <div style="padding:10px 12px;border-bottom:1px solid #f1f5f9;cursor:pointer;background:${n.is_read ? 'white' : '#f0f9ff'};"
                     onclick="bacaNotif(${n.id})">
                    <p style="font-size:13px;color:#374151;border-left:3px solid ${warna};padding-left:8px;">${n.pesan}</p>
                    <span style="font-size:11px;color:#94a3b8;padding-left:11px;">${n.waktu}</span>
                </div>`;
        });
    }

    if (data.unread_count > 0) {
        badge.style.display = 'flex';
        badge.innerText = data.unread_count;
    } else {
        badge.style.display = 'none';
    }
}

async function bacaNotif(id) {
    await fetch(`/notifikasi/${id}/dibaca`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    });
    muatNotifikasi();
}

document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('notifDropdown');
    if (!e.target.closest('.notif-wrap')) {
        dropdown.style.display = 'none';
    }
});

muatNotifikasi();
</script>
