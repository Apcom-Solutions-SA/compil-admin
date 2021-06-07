<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Group;
use App\Mail\UserAdded;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class UserController extends Controller
{
    public function test()
    {
        $users = User::whereNull('public_id')->get(); 
        foreach ($users as $user){
            $user->public_id = md5($user->email);
            $user->update();
        }         
    }
    /**
     * add user from compil 
     */
    public function email(Request $request)
    {
        // create user         
        $request->validate([
            'email' => 'required|email|unique:users',
        ]);

        $password = random_key();  // defined in helper

        $email = strtolower($request->email);
        $public_id = md5($email);
        $parent_user = $request->user();
        if (!$parent_user->public_id) {
            $parent_user->public_id = md5($parent_user->email);
            $parent_user->save();
        }

        $user = User::create($request->except(['password']) + [
            'email' => $email,
            'password' => Hash::make($password),
            'role_id' => 3,  // 
            'public_id' => $public_id,
            'parent' => $parent_user->public_id
        ]);

        // get verification url 
        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $parsed_url = parse_url($url); 
        if (! $parsed_url) return response()->json(['message' => 'failed to parse url'], 500); 
        $new_url = config('app.front_url').$parsed_url['path'].'?'.$parsed_url['query']; 


        Mail::to($user)->send(new UserAdded($password, $public_id, $new_url));

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function index_admins(Request $request)
    {
        // DB::table('users')->whereBetween('id', [88,137])->update(['role_id'=>3]); 
        $groups = Group::where('table_name', 'users')->where('role_id', 2)->orderBy('name')->get(['id', 'name']);
        return view('users.index', [
            'title' => trans('admin.admins'),
            'page' => 'admins',
            'role_id' => 1,
            'groups' => $groups,
            'id' => $request->id ?? 0,
        ]);
    }

    public function index_clients(Request $request)
    {
        $groups = Group::where('table_name', 'users')->where('role_id', 3)->orderBy('name')->get(['id', 'name']);
        return view('users.index', [
            'title' => trans('admin.clients'),
            'page' => 'clients',
            'role_id' => 3,
            'groups' => $groups,
            'id' => $request->id ?? 0,
        ]);
    }

    public function index_api(Request $request)
    {
        $role_id = $request->role_id;
        if ($role_id == 1) {
            $users = User::whereIn('role_id', [1, 2])->orderBy('id', 'desc');   // employees
        }
        if ($role_id == 3) {
            $users = User::where('role_id', 3)->orderBy('id', 'desc');
        }
        if ($request->search) {
            $search = $request->search;

            $users->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }
        $per_page = $request->per_page ?? setting('admin.per_page');
        return $users->paginate($per_page);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
        ]);

        $user = User::create($request->except(['password']) + [
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request_user = $request->user();
        // if is not admin, cannot update other's profile
        if (!($request_user->isAdmin() || $request_user->id == $user->id)) {
            return response()->json([
                'message' => "Vous n'avez pas la permission de mettre à jour ce profil",
            ]);
        }
        $request->validate([
            'email' => 'nullable|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->except(['password']));
        if ($request->password) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return response()->json([
            'user' => $user,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
