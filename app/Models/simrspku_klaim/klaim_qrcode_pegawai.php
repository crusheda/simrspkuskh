<?php

namespace App\Models\simrspku_klaim;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class klaim_qrcode_pegawai extends Model
{
    use SoftDeletes;
    protected $connection = 'db_custom';
    protected $table = 'klaim_qrcode_pegawai';
}
