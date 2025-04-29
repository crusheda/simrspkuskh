<?php

namespace App\Models\simrspku_klaim;

use Illuminate\Database\Eloquent\Model;

class klaim_verifikasi extends Model
{
    protected $connection = 'db_custom';
    protected $table = 'klaim_verifikasi';
    use HasFactory;
}
