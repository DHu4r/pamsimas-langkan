<?php

namespace App\Traits;

use Carbon\Carbon;

trait TanggalIndo
{
    public function getTanggalIndo($field, $withTime = false)
    {
        $date = $this->{$field};

        if (!$date) {
            return null;
        }

        $format = $withTime ? 'd F Y H:i' : 'd F Y';

        return Carbon::parse($date)->translatedFormat($format);
    }

    // Magic accessor: panggil _indo langsung di blade
    public function __get($key)
    {
        if (str_ends_with($key, '_indo')) {
            $field = str_replace('_indo', '', $key);

            if ($this->{$field} ?? false) {
                // Kalau fieldnya created_at / updated_at → tampilkan dengan jam
                $withTime = in_array($field, ['created_at', 'updated_at']);
                return $this->getTanggalIndo($field, $withTime);
            }
        }

        return parent::__get($key);
    }
}
