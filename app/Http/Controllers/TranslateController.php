<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\TranslationManager\Models\Translation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class TranslateController extends Controller
{
    public function postPublish($group = null)
    {
        // php artisan translation:export
        \App::call('\Barryvdh\TranslationManager\Controller@postPublish', ['group'=>$group]); 
        // php artisan vue-i18n:generate --format {es6,umd,json}
        Artisan::call('vue-i18n:generate --format umd'); 
    }
}
