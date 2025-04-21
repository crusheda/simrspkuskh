<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    protected $connection = 'db_aplikasi';
    protected $table = 'pengguna';

    protected $primaryKey = 'ID'; // atau sesuaikan
    public $timestamps = false;

    protected $fillable = ['LOGIN', 'PASSWORD'];
}
