<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserRoleHelper
{
    /**
     * Cek satu jenis user.
     */
    public static function hasJenisRole(int $jenisUser): bool
    {
        return self::hasMultipleJenisRole([$jenisUser]);
    }

    /**
     * Cek beberapa jenis user.
     */
    public static function hasMultipleJenisRole(array $jenisUser): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();

        return DB::table('simrspku_klaim.model_has_roles as mhr')
            ->join('simrspku_klaim.roles as r', 'r.id', '=', 'mhr.role_id')
            ->where('mhr.model_id', $user->id)
            ->where('mhr.model_type', get_class($user))
            ->whereIn('r.jenis', $jenisUser)
            ->exists();
    }
}
