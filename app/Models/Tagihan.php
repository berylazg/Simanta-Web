<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $table = 'tagihans';

    protected $fillable = [
        'user_id', 'vendor_id', 'kategori_id',
        'nomor_invoice', 'nama_tagihan', 'nomor_kontrak',
        'nominal', 'tanggal_invoice', 'tanggal_jatuh_tempo',
        'tanggal_reminder', 'status', 'deskripsi', 'file_invoice'
    ];

    protected $casts = [
        'tanggal_invoice'      => 'date',
        'tanggal_jatuh_tempo'  => 'date',
        'tanggal_reminder'     => 'date',
        'nominal'              => 'decimal:2',
    ];

    // Relasi ke vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    // Relasi ke kategori
    public function kategori()
    {
        return $this->belongsTo(KategoriTagihan::class, 'kategori_id');
    }

    // Relasi ke pembayaran
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'tagihan_id');
    }

    // Relasi ke Notifikasi
    public function notifikasi()
{
    return $this->hasMany(Notifikasi::class);
}

    // Relasi ke reminder
    public function reminders()
    {
    return $this->hasMany(Reminder::class, 'tagihan_id');
    }
}
