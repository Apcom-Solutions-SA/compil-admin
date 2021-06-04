<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Log;

/*
 * Tutorial: https://blog.pusher.com/web-application-laravel-vue-part-2/
 */

class AuthController extends Controller
{

    // auth for api token
    public function login(Request $request)
    {
        // Impossible de se connecter avec une clé secrète valable si le compte est not verified.
        // unless for email verification
        $email = request('email'); 
        $user = User::where('email', $email)->first(); 
        if (is_null($user)) {
            return response()->json([
                'message' => __('front.email_not_registered')
            ], 401);
        }

        if (is_null($user->email_verified_at) && is_null($request->hash)) {
            return response()->json([
                'message' => __('front.email_not_verified')
            ], 401);
        }


        $credentials = [
            'email' => request('email'),
            'password' => request('password')
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user(); 
            if ($request->hash && ! $user->hasVerifiedEmail()){
                if (! hash_equals((string) $request->id, (string) $user->getKey())) {
                    throw new AuthorizationException;
                }                
                if (! hash_equals((string) $request->hash, sha1($user->getEmailForVerification()))) {
                    throw new AuthorizationException;
                }
                $user->markEmailAsVerified(); 
            }

            $token = $user->createToken($user->email)->accessToken;  
            
            return response()->json([
                'token' => $token, 
                'user' => $user,
            ]);
        }

        return response()->json(['message' => __('Unauthorised')], 401);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);
        $token = $user->createToken('MyApp')->accessToken;
        $name = $user->name;

        return response()->json([
            'token' => $token
        ]);
    }

    public function getDetails()
    {
        return response()->json(['success' => Auth::user()]);
    }
}
