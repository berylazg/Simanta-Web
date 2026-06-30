<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    // Ambil data notifikasi untuk dropdown lonceng
    public function data()
    {
        $notifikasi = Notifikasi::with('tagihan')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($n) {
                return [
                    'id'       => $n->id,
                    'tipe'     => $n->tipe,
                    'pesan'    => $n->pesan,
                    'is_read'  => $n->is_read,
                    'waktu'    => $n->created_at->diffForHumans(),
                ];
            });

        $unreadCount = Notifikasi::where('is_read', false)->count();

        return response()->json([
            'notifikasi'   => $notifikasi,
            'unread_count' => $unreadCount,
        ]);
    }

    // Tandai 1 notifikasi sebagai dibaca
    public function tandaiDibaca($id)
    {
        $notif = Notifikasi::findOrFail($id);
        $notif->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    // Tandai semua notifikasi sebagai dibaca
    public function tandaiSemuaDibaca()
    {
        Notifikasi::where('is_read', false)->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
