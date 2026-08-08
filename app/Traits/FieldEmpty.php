<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait FieldEmpty
{
    /**
     * Cek apakah seluruh field skrining kosong.
     *
     * null dan '' dianggap kosong.
     * 0 tetap dianggap sebagai nilai yang valid.
     */
    protected function isFieldEmpty(Request $request, array $fields): bool
    {
        foreach ($fields as $field) {
            $value = $request->input($field);

            if ($value !== null && $value !== '') {
                return false;
            }
        }

        return true;
    }
}
