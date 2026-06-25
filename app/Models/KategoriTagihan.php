<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriTagihan extends Model
{
    protected $table = 'kategori_tagihans';

    protected $fillable = [
        'nama_kategori', 'deskripsi'
    ];

    public function tagihans()
    {
        return $this->hasMany(Tagihan::class, 'kategori_id');
    }
}
