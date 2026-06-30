<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $fillable = ['tagihan_id', 'tipe', 'pesan', 'is_read'];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class);
    }
}
