<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayarans';

    protected $fillable = [
        'tagihan_id', 'user_id', 'tanggal_bayar',
        'jumlah_bayar', 'metode_bayar',
        'nomor_referensi', 'file_bukti', 'catatan'
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'jumlah_bayar'  => 'decimal:2',
    ];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }
}
