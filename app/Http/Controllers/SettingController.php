<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use TCG\Voyager\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        // Check permission
        $this->authorize('browse', Voyager::model('Setting'));
        Cache::forget('settings'); // added to clean cache

        $data = Voyager::model('Setting')->orderBy('order', 'ASC')->get();

        $settings = [];
        $settings[__('voyager::settings.group_general')] = [];
        foreach ($data as $d) {
            if ($d->group == '' || $d->group == __('voyager::settings.group_general')) {
                $settings[__('voyager::settings.group_general')][] = $d;
            } else {
                $settings[$d->group][] = $d;
            }
        }
        if (count($settings[__('voyager::settings.group_general')]) == 0) {
            unset($settings[__('voyager::settings.group_general')]);
        }

        $groups_data = Voyager::model('Setting')->select('group')->distinct()->get();
        $groups = [];
        foreach ($groups_data as $group) {
            if ($group->group != '') {
                $groups[] = $group->group;
            }
        }

        $active = (request()->session()->has('setting_tab')) ? request()->session()->get('setting_tab') : old('setting_tab', key($settings));

        return view('settings.index', compact('settings', 'groups', 'active'));
    }

    // to share settings in the whole site by vuex
    public function list_api()
    {
        if (config('app.debug') == true){
            return  DB::table('settings')->select('key', 'value')->pluck('value', 'key');
        }
        
        $settings = Cache::remember('settings', 24 * 60 * 60, function () {
            return  DB::table('settings')->select('key', 'value')->pluck('value', 'key');
        });
        return  $settings;
    }

    /**
     * update a settings by key
     */
    public function update_value(Request $request)
    {
        $request->validate([
            'key' => 'string|required',
            'value' => 'required',
        ]);
        $item = Setting::where('key', $request->key)->first();
        $item->update(['value' => $request->value]);
        Cache::forget('settings');
        return $item;
    }
}
