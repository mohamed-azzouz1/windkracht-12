<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\Role;
use App\Models\Registration;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of the students.
     */
    public function index(Request $request)
    {
        $query = Student::with('user');
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->whereHas('user', function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            })
            ->orWhere('phone', 'like', "%{$searchTerm}%")
            ->orWhere('address', 'like', "%{$searchTerm}%")
            ->orWhere('city', 'like', "%{$searchTerm}%");
        }
        
        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
            if ($request->status === 'active') {
                $query->whereHas('registrations', function($q) {
                    $q->where('status', '!=', 'cancelled')
                      ->where(function($q) {
                          $q->where('start_date', '>=', now())
                            ->orWhere('end_date', '>=', now());
                      });
                });
            } elseif ($request->status === 'inactive') {
                $query->whereDoesntHave('registrations', function($q) {
                    $q->where('status', '!=', 'cancelled')
                      ->where(function($q) {
                          $q->where('start_date', '>=', now())
                            ->orWhere('end_date', '>=', now());
                      });
                });
            }
        }
        
        // Load relationship counts
        $query->withCount(['registrations']);
        
        // Apply sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        
        if ($sortField === 'name' || $sortField === 'email') {
            $query->join('users', 'students.user_id', '=', 'users.id')
                  ->orderBy("users.{$sortField}", $sortDirection)
                  ->select('students.*');
        } else {
            $query->orderBy($sortField, $sortDirection);
        }
        
        $students = $query->paginate(15)->withQueryString();
        
        return view('admin.students.index', [
            'students' => $students,
        ]);
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        return view('admin.students.create');
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'skill_level' => 'nullable|string|in:beginner,intermediate,advanced',
            'notes' => 'nullable|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Get student role
            $studentRole = Role::where('name', 'student')->firstOrFail();
            
            // Create user
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role_id' => $studentRole->id,
            ]);
            
            // Create student
            $student = Student::create([
                'user_id' => $user->id,
                'address' => $validatedData['address'],
                'city' => $validatedData['city'],
                'phone' => $validatedData['phone'],
                'date_of_birth' => $validatedData['date_of_birth'],
                'skill_level' => $validatedData['skill_level'] ?? 'beginner',
                'notes' => $validatedData['notes'],
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.students.show', $student->id)
                ->with('success', 'Student aangemaakt!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating student: ' . $e->getMessage());
            
            return back()->withInput()->with('error', 'Er is een fout opgetreden bij het aanmaken van de student: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified student.
     */
    public function show($id)
    {
        $student = Student::with('user')->findOrFail($id);
        $lessons = $student->registrations()
            ->with(['package', 'instructor.user'])
            ->orderBy('start_date', 'desc')
            ->paginate(10);
            
        return view('admin.students.show', [
            'student' => $student,
            'lessons' => $lessons
        ]);
    }
    
    /**
     * Show the form for editing the specified student.
     */
    public function edit($id)
    {
        $student = Student::with('user')->findOrFail($id);
        
        return view('admin.students.edit', [
            'student' => $student
        ]);
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, $id)
    {
        $student = Student::with('user')->findOrFail($id);
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($student->user_id),
            ],
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'skill_level' => 'nullable|string|in:beginner,intermediate,advanced',
            'notes' => 'nullable|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Update user
            $student->user->name = $validatedData['name'];
            $student->user->email = $validatedData['email'];
            $student->user->save();
            
            // Update student
            $student->address = $validatedData['address'];
            $student->city = $validatedData['city'];
            $student->phone = $validatedData['phone'];
            $student->date_of_birth = $validatedData['date_of_birth'];
            $student->skill_level = $validatedData['skill_level'];
            $student->notes = $validatedData['notes'];
            $student->save();
            
            DB::commit();
            
            return redirect()->route('admin.students.show', $student->id)
                ->with('success', 'Student bijgewerkt!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating student: ' . $e->getMessage());
            
            return back()->withInput()->with('error', 'Er is een fout opgetreden bij het bijwerken van de student: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy($id)
    {
        $student = Student::with('user')->findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Delete student (this will cascade to registrations)
            $student->delete();
            
            // Delete user
            $student->user->delete();
            
            DB::commit();
            
            return redirect()->route('admin.students.index')
                ->with('success', 'Student verwijderd!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting student: ' . $e->getMessage());
            
            return back()->with('error', 'Er is een fout opgetreden bij het verwijderen van de student: ' . $e->getMessage());
        }
    }
}
