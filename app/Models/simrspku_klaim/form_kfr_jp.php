<?php

namespace App\Models\simrspku_klaim;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class form_kfr_jp extends Model
{
    use SoftDeletes;
    protected $connection = 'db_custom';
    protected $table = 'form_kfr_jp';
}
