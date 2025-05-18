<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use App\Models\Instructor;

class AccountController extends Controller
{
    /**
     * Display a listing of all user accounts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('role')->paginate(20);
        
        return view('admin.accounts.index', [
            'users' => $users,
            'roles' => Role::all(),
        ]);
    }
    
    /**
     * Show the form for creating a new account.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.accounts.create', [
            'roles' => Role::all(),
        ]);
    }
    
    /**
     * Store a newly created account in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);
        
        // Create the user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role_id' => $validatedData['role_id'],
        ]);
        
        return redirect()->route('admin.accounts.index')
            ->with('success', 'Account succesvol aangemaakt.');
    }
    
    /**
     * Display the specified account.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with('role')->findOrFail($id);
        
        // Load profile based on role
        if ($user->role->name === 'student') {
            $profile = Student::where('user_id', $user->id)->first();
        } elseif ($user->role->name === 'instructor') {
            $profile = Instructor::where('user_id', $user->id)->first();
        } else {
            $profile = null;
        }
        
        return view('admin.accounts.show', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }
    
    /**
     * Show the form for editing the specified account.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        return view('admin.accounts.edit', [
            'user' => $user,
            'roles' => Role::all(),
        ]);
    }
    
    /**
     * Update the specified account in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Validate input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        // Update user
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->role_id = $validatedData['role_id'];
        
        if (!empty($validatedData['password'])) {
            $user->password = bcrypt($validatedData['password']);
        }
        
        $user->save();
        
        return redirect()->route('admin.accounts.show', $id)
            ->with('success', 'Account succesvol bijgewerkt.');
    }
    
    /**
     * Remove the specified account from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Check if this is not the last admin account
        if ($user->role->name === 'admin') {
            $adminCount = User::whereHas('role', function($query) {
                $query->where('name', 'admin');
            })->count();
            
            if ($adminCount <= 1) {
                return redirect()->route('admin.accounts.index')
                    ->with('error', 'Kan het laatste admin account niet verwijderen.');
            }
        }
        
        $user->delete();
        
        return redirect()->route('admin.accounts.index')
            ->with('success', 'Account succesvol verwijderd.');
    }
}
