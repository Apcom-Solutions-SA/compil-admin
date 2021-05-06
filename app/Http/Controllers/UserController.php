<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Group;

class UserController extends Controller
{
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
            $users = User::with(['user_groups'])->where('role_id', 3)->orderBy('id', 'desc');
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

    // api for update groups for a certain user
    public function update_groups(Request $request)
    {
        $user_id = $request->user_id;
        $groups_id = array_map('intval', $request->groups);
        DB::table('user_group')
            ->where('user_id', $user_id)
            ->whereNotIn('user_id', $groups_id)
            ->delete();
        foreach ($groups_id as $group_id) {
            UserGroup::firstOrCreate([
                'user_id' => $user_id,
                'group_id' => $group_id
            ]);
        }
        $user_groups = UserGroup::where('user_id', $user_id)->get();
        return response()->json([
            'user_groups' => $user_groups,
            'message' => 'Mis à jour groupes',
        ]);
    }
}
