<?php

namespace App\Models;

trait LocaleTraits
{

    protected function getLocale($data)
    {
        $locale = request()->get('lang');

        $data = json_decode($data);

        if (in_array($locale, config('locales.locales'))) {

            if (!empty($data->$locale)) return $data->$locale;

        }

        return $data;
    }

}
