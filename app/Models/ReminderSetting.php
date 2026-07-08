<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReminderSetting extends Model
{
    protected $fillable = [
        'status',
        'h30',
        'h14',
        'h7',
        'h3',
        'h1'
    ];
}