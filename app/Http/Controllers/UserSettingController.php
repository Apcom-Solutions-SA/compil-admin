<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSetting; 

class UserSettingController extends Controller
{
    public function show(Request $request, int $user_id){
        $user_setting =  UserSetting::firstOrCreate([
            'user_id' => $user_id
        ]); 
        return response()->json([
            'user_setting' => $user_setting
        ]);
    }

    public function update(Request $request){
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'set_min' => 'nullable|boolean',
        ]);

        $user_setting = UserSetting::firstOrCreate([
            'user_id' => $request->user_id
        ]); 

        $user_setting->update($request->only(['set_min', 'min'])); 
        return response()->json([
            'user_setting' => $user_setting
        ]); 
    }
}
