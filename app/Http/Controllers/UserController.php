<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        return view('users.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required'],
        ]);
        $path = $request->file('signature')->store('signatures');
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'signature' => $path
        ]);
        switch ($request->role) {
            case 'admin':
                $user->assignRole('admin');
                break;
            case 'encoder':
                $user->assignRole('encoder');
                break;
            case 'verifier':
                $user->assignRole('verifier');
                break;
            default:
                $user->assignRole('head');
                break;
        }
        

        return $path;
        return redirect()->route('users.index')->with('message', 'User added successfully.')->with('classname', 'alert-success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.create', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $validated = $request->validate([
            
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $path = $request->file('signature')->store('signatures');
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'signature' => $path
        ]);
        switch ($request->role) {
            case 'admin':
                $user->syncRoles('admin');
                break;
            case 'encoder':
                $user->syncRoles('encoder');
                break;
            case 'verifier':
                $user->syncRoles('verifier');
                break;
            default:
                $user->syncRoles('head');
                break;
        }
        return redirect()->route('users.index')->with('message', 'User updated successfully.')->with('classname', 'alert-success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if(auth()->id() === $user->id){
            return redirect()->route('users.index')->with('message', 'User deleted failed. You cannot delete your own profile.')->with('classname', 'alert-warning');
        }
        User::destroy($user->id);
        return redirect()->route('users.index')->with('message', 'User deleted successfully.')->with('classname', 'alert-danger');
    }
}
