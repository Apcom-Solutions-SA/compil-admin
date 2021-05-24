<?php

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Barryvdh\TranslationManager\Models\Translation;


if (!function_exists('random_key')) {
    function random_key(int $length=16, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-#@=' ): ?string
    {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        if ($max < 1) {
            throw new Exception('$keyspace must be at least two characters long');
        }
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }
}

if (!function_exists('getLocales')) {
    // get locals from table of translation manager, so that admin can set locales          
    function getLocales()
    {
        $locales = array_merge(
            [config('app.locale')],
            Translation::groupBy('locale')->pluck('locale')->toArray()
        );
        return  array_unique($locales);
    }
}