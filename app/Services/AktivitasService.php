<?php

namespace App\Services;

use App\Models\AktivitasLog;
use Illuminate\Support\Facades\Auth;

class AktivitasService
{
    public static function log(
        string $aksi,
        ?string $modelType = null,
        ?int $modelId = null,
        ?string $keterangan = null
    ): void
    {
        AktivitasLog::create([
            'user_id' => Auth::id() ?? 1,
            'aksi' => $aksi,
            'model_tipe' => $modelType,
            'model_id' => $modelId,
            'keterangan' => $keterangan,
            'created_at' => now(),
        ]);
    }
}