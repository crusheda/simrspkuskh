<?php

namespace App\Modules\AiKlaim\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeBase extends Model
{
    protected $connection = 'db_custom';
    protected $guard_name = 'web';
    protected $table = 'ai_klaim_kb';
    protected $fillable = ['kategori','judul','konten','aktif'];
}
