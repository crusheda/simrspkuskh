<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class rating extends Model
{
    use SoftDeletes;
    protected $connection = 'db_custom';
    protected $table = 'rating';
    public $timestamps = true;

    protected $fillable = [
        'rating',
        'respon',
        'ip'
    ];
}
