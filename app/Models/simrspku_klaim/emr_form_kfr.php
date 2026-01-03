<?php

namespace App\Models\simrspku_klaim;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class emr_form_kfr extends Model
{
    use SoftDeletes;
    protected $connection = 'db_custom';
    protected $table = 'emr_form_kfr';
}
