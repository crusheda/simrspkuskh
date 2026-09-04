<?php

namespace App\Models\simrspku_klaim;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class klaim_verifikasi_catatan extends Model
{
    use SoftDeletes;
    protected $connection = 'db_custom';
    protected $table = 'klaim_verifikasi_catatan';

    protected $casts = [
        'unit' => 'array',
        'status' => 'boolean',
        'solved' => 'boolean',
    ];
}
