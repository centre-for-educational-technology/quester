<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view users');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = User::with('roles')->paginate(20);
        return Inertia::render('Users/Index', ['users' => $users]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User $user
     * @return Response
     */
    public function edit(User $user)
    {
        $roles = Role::all();

        // get user role, each user has only one role
        $user_role = "";
        $user_role_names = $user->getRoleNames();
        if (count($user_role_names) > 0) {
            $user_role = $user_role_names[0];
        }

        return Inertia::render('Users/Edit', [
            'roles' => $roles,
            'user_role' => $user_role,
            'selected_user' => $user ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        // update user role
        DB::table('model_has_roles')->where('model_id',$user->id)->delete();
        $user->assignRole($request->input('role'));

        return Redirect::route('users.index');
    }
}
