<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendors';

    protected $fillable = [
        'nama_vendor', 'kontak_person',
        'email', 'telepon', 'alamat'
    ];

    public function tagihans()
    {
        return $this->hasMany(Tagihan::class, 'vendor_id');
    }
}
