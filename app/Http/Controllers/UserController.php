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
    public function email(Request $request)
    {        
        // create user         
        $request->validate([
            'email' => 'required|email|unique:users',
        ]);        
        
        $key = random_key();
                   
        $user = User::create($request->except(['password']) + [
            'email' => $request->email,
            'password' => Hash::make($key), 
            'active' => 0,
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
        Mail::to($user)->send(new UserAdded($key, $url));        

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
            'role_id' => 2,
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
        if ($role_id == 2) {
            $users = User::whereIn('role_id', [1, 2])->orderBy('id', 'desc');   // employees
        }
        if ($role_id == 3) {
            $users = User::where('role_id', 3)->orderBy('id', 'desc');
        }
        if ($request->search) {
            $search = $request->search;

            $users->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('family_name', 'LIKE', '%' . $search . '%')
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
            'password' => Hash::make($request->password),
            'active' => 1,
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
