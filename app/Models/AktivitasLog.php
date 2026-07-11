<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AktivitasLog extends Model
{
    protected $table      = 'aktivitas_logs';
    public    $timestamps = false;

    protected $fillable = [
        'user_id', 'aksi', 'model_tipe', 'model_id', 'keterangan', 'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
