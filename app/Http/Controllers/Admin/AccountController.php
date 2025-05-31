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
    public function index(Request $request)
    {
        $query = User::with('role');
        
        // Apply search filter if provided
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }
        
        // Apply role filter if provided
        if ($request->has('role') && !empty($request->role)) {
            $query->where('role_id', $request->role);
        }
        
        // Paginate results
        $users = $query->paginate(20);
        
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
    
    /**
     * Change the role of a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $newRoleName = $request->input('role');
        
        // Find the role
        $role = Role::where('name', $newRoleName)->first();
        
        if (!$role) {
            return redirect()->back()->with('error', 'De opgegeven rol bestaat niet.');
        }
        
        // Update the user's role
        $user->role_id = $role->id;
        $user->save();
        
        // If changing to instructor, create an instructor profile if one doesn't exist
        if ($newRoleName === 'instructor' && !Instructor::where('user_id', $user->id)->exists()) {
            Instructor::create([
                'user_id' => $user->id,
                'is_active' => true,
            ]);
        }
        
        // If changing to student, create a student profile if one doesn't exist
        if ($newRoleName === 'student' && !Student::where('user_id', $user->id)->exists()) {
            Student::create([
                'user_id' => $user->id,
            ]);
        }
        
        return redirect()->back()->with('success', 'Gebruikersrol succesvol gewijzigd naar ' . ucfirst($newRoleName) . '.');
    }
    
    /**
     * Create a profile for a user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createProfile($id)
    {
        $user = User::with('role')->findOrFail($id);
        
        if ($user->role->name === 'student') {
            // Create student profile if it doesn't exist
            if (!Student::where('user_id', $user->id)->exists()) {
                Student::create([
                    'user_id' => $user->id,
                    'skill_level' => 'beginner',
                ]);
            }
        } elseif ($user->role->name === 'instructor') {
            // Create instructor profile if it doesn't exist
            if (!Instructor::where('user_id', $user->id)->exists()) {
                Instructor::create([
                    'user_id' => $user->id,
                    'is_active' => true,
                ]);
            }
        }
        
        return redirect()->route('admin.accounts.show', $id)
            ->with('success', 'Profiel aangemaakt. Deze kan nu verder worden ingevuld.');
    }
}
