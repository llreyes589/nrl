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
            'signature' => $path,
            'created_by' => auth()->id()
        ]);
        switch ($request->role) {
            case 'admin':
                $user->assignRole('admin');
                break;
            case 'encoder':
                $user->assignRole('encoder');
                break;
            case 'encoder2':
                $user->assignRole(['encoder','encoder2']);
                break;                
            case 'verifier':
                $user->assignRole('verifier');
                break;
            case 'head':
                $user->assignRole('head');
                break;                
            default:
                $request->role;
                break;
        }
        

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
        $user->syncRoles([]);
        if($request->file('signature')){

            $path = $request->file('signature')->store('signatures');
        }else{
            $path = $user->signature;
        }
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'signature' => $path,
            'updated_by' => auth()->id()
        ]);
        switch ($request->role) {
            case 'admin':
                $user->assignRole('admin');
                break;
            case 'encoder':
                $user->assignRole('encoder');
                break;
            case 'encoder2':
                $user->assignRole(['encoder','encoder2']);
                break;                
            case 'verifier':
                $user->assignRole('verifier');
                break;
            case 'head':
                $user->assignRole('head');
                break;                
            default:
                $request->role;
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
        $user->deleted_by = \auth()->id();
        $user->save();
        return redirect()->route('users.index')->with('message', 'User deleted successfully.')->with('classname', 'alert-danger');
    }
}
