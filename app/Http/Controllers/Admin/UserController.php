<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Instructor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }
        
        // Apply role filter - improved to avoid showing users with multiple roles
        if ($request->has('role') && !empty($request->role)) {
            $role = $request->role;
            
            if ($role == 'admin') {
                // Show only admin users
                $query->where('is_admin', true);
            } elseif ($role == 'instructor') {
                // Show only instructors who are not admins
                $query->whereHas('instructor')
                      ->where(function($q) {
                          $q->where('is_admin', false)
                            ->orWhereNull('is_admin');
                      });
            } elseif ($role == 'student') {
                // Show only students who are not instructors or admins
                $query->whereHas('student')
                      ->whereDoesntHave('instructor')
                      ->where(function($q) {
                          $q->where('is_admin', false)
                            ->orWhereNull('is_admin');
                      });
            }
        }
        
        // Sort users
        $sortField = $request->input('sort', 'name');
        $sortDirection = $request->input('direction', 'asc');
        $query->orderBy($sortField, $sortDirection);
        
        $users = $query->paginate(15)->withQueryString();
        
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:student,instructor,admin',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Create the user
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'is_admin' => ($validatedData['role'] === 'admin'),
            ]);
            
            // Handle role-specific logic
            if ($validatedData['role'] === 'instructor') {
                Instructor::create([
                    'user_id' => $user->id,
                    'bio' => $request->input('bio', ''),
                    'phone' => $request->input('phone', '')
                ]);
            } elseif ($validatedData['role'] === 'student') {
                Student::create([
                    'user_id' => $user->id,
                    'phone' => $request->input('student_phone', '')
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('admin.users.index')
                ->with('success', 'Gebruiker succesvol aangemaakt.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating user: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Er is een fout opgetreden bij het aanmaken van de gebruiker: ' . $e->getMessage());
        }
    }
    
    /**
     * Show the form for editing a user's role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        return view('admin.users.edit', compact('user'));
    }
    
    /**
     * Update the user's role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:student,instructor,admin',
        ]);
        
        $user = User::findOrFail($id);
        $newRole = $request->role;
        
        try {
            DB::beginTransaction();
            
            // First, ensure is_admin column exists and is set correctly
            if (Schema::hasColumn('users', 'is_admin')) {
                $user->is_admin = ($newRole === 'admin');
                $user->save();
            }
            
            // Handle role-specific logic based on the new role
            if ($newRole === 'instructor') {
                // Create instructor if it doesn't exist
                if (!$user->instructor) {
                    // Check if the instructors table has the 'bio' column
                    $hasColumn = Schema::hasColumn('instructors', 'bio');
                    
                    // Create the instructor with only the required fields
                    $instructorData = [
                        'user_id' => $user->id,
                    ];
                    
                    // Only add bio and phone if they exist and are provided
                    if ($hasColumn && $request->has('bio')) {
                        $instructorData['bio'] = $request->input('bio');
                    }
                    
                    if (Schema::hasColumn('instructors', 'phone') && $request->has('phone')) {
                        $instructorData['phone'] = $request->input('phone');
                    }
                    
                    Instructor::create($instructorData);
                }
            } 
            
            if ($newRole === 'student') {
                // Create student if it doesn't exist
                if (!$user->student) {
                    $studentData = [
                        'user_id' => $user->id,
                    ];
                    
                    // Only add phone if it exists and is provided
                    if (Schema::hasColumn('students', 'phone') && $request->has('student_phone')) {
                        $studentData['phone'] = $request->input('student_phone');
                    }
                    
                    Student::create($studentData);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.users.index')
                ->with('success', "De rol van {$user->name} is gewijzigd naar " . ucfirst($newRole));
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error changing user role: ' . $e->getMessage(), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return back()->withErrors(['error' => 'Er is een fout opgetreden bij het wijzigen van de gebruikersrol: ' . $e->getMessage()]);
        }
    }
}
