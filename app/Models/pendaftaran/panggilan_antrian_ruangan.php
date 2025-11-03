<?php

namespace App\Models\pendaftaran;

use Illuminate\Database\Eloquent\Model;

class panggilan_antrian_ruangan extends Model
{
    protected $connection = 'db_pendaftaran';
    protected $table = 'panggilan_antrian_ruangan';
    public $timestamps = false;
}
