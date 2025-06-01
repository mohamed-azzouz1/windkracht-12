<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Instructor;
use App\Models\User;
use App\Models\Role;
use App\Models\Registration;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class InstructorController extends Controller
{
    /**
     * Display a listing of instructors.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Instructor::with('user');
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->whereHas('user', function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            })
            ->orWhere('phone', 'like', "%{$searchTerm}%")
            ->orWhere('specialization', 'like', "%{$searchTerm}%");
        }
        
        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        // Apply sorting
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        
        if ($sortField === 'name' || $sortField === 'email') {
            $query->join('users', 'instructors.user_id', '=', 'users.id')
                  ->orderBy("users.{$sortField}", $sortDirection)
                  ->select('instructors.*');
        } else {
            $query->orderBy($sortField, $sortDirection);
        }
        
        $instructors = $query->paginate(15)->withQueryString();
        
        return view('admin.instructors.index', [
            'instructors' => $instructors,
        ]);
    }

    /**
     * Show the form for creating a new instructor.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.instructors.create');
    }

    /**
     * Store a newly created instructor in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
            'bsn' => 'nullable|string|max:255',
            'certification' => 'nullable|string|max:255',
            'years_of_experience' => 'nullable|integer|min:0',
            'specialization' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Get instructor role
            $instructorRole = Role::where('name', 'instructor')->firstOrFail();
            
            // Create user
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role_id' => $instructorRole->id,
            ]);
            
            // Create instructor
            $instructor = Instructor::create([
                'user_id' => $user->id,
                'address' => $validatedData['address'],
                'city' => $validatedData['city'],
                'phone' => $validatedData['phone'],
                'date_of_birth' => $validatedData['date_of_birth'],
                'bsn' => $validatedData['bsn'],
                'certification' => $validatedData['certification'],
                'years_of_experience' => $validatedData['years_of_experience'],
                'specialization' => $validatedData['specialization'],
                'biography' => $validatedData['biography'],
                'is_active' => $request->has('is_active'),
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.instructors.show', $instructor->id)
                ->with('success', 'Instructeur succesvol aangemaakt.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating instructor: ' . $e->getMessage());
            
            return back()->withInput()->with('error', 'Er is een fout opgetreden bij het aanmaken van de instructeur: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified instructor.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $instructor = Instructor::with('user')->findOrFail($id);
        
        // Get upcoming lessons for this instructor
        $upcomingLessons = Registration::where('instructor_id', $id)
            ->with(['student.user', 'package'])
            ->where('start_date', '>', now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_date')
            ->paginate(10);
            
        return view('admin.instructors.show', [
            'instructor' => $instructor,
            'upcomingLessons' => $upcomingLessons
        ]);
    }

    /**
     * Show the form for editing the specified instructor.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $instructor = Instructor::with('user')->findOrFail($id);
        
        return view('admin.instructors.edit', [
            'instructor' => $instructor
        ]);
    }

    /**
     * Update the specified instructor in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $instructor = Instructor::with('user')->findOrFail($id);
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($instructor->user_id),
            ],
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'bsn' => 'nullable|string|max:255',
            'certification' => 'nullable|string|max:255',
            'years_of_experience' => 'nullable|integer|min:0',
            'specialization' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'is_active' => 'boolean',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Update user
            $instructor->user->name = $validatedData['name'];
            $instructor->user->email = $validatedData['email'];
            
            // Update password if provided
            if (!empty($validatedData['password'])) {
                $instructor->user->password = Hash::make($validatedData['password']);
            }
            
            $instructor->user->save();
            
            // Update instructor
            $instructor->address = $validatedData['address'];
            $instructor->city = $validatedData['city'];
            $instructor->phone = $validatedData['phone'];
            $instructor->date_of_birth = $validatedData['date_of_birth'];
            $instructor->bsn = $validatedData['bsn'];
            $instructor->certification = $validatedData['certification'];
            $instructor->years_of_experience = $validatedData['years_of_experience'];
            $instructor->specialization = $validatedData['specialization'];
            $instructor->biography = $validatedData['biography'];
            $instructor->is_active = $request->has('is_active');
            $instructor->save();
            
            DB::commit();
            
            return redirect()->route('admin.instructors.show', $instructor->id)
                ->with('success', 'Instructeur bijgewerkt!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating instructor: ' . $e->getMessage());
            
            return back()->withInput()->with('error', 'Er is een fout opgetreden bij het bijwerken van de instructeur: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified instructor from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $instructor = Instructor::findOrFail($id);
            
            // First, handle all registrations (lessons) associated with this instructor
            $registrations = $instructor->registrations;
            
            if ($registrations->count() > 0) {
                // Option 1: Delete all registrations
                foreach ($registrations as $registration) {
                    $registration->delete();
                }
                
                // Alternative option: Mark lessons as cancelled instead of deleting
                // foreach ($registrations as $registration) {
                //     $registration->status = 'cancelled';
                //     $registration->cancellation_reason = 'Instructeur verwijderd door admin';
                //     $registration->cancellation_type = 'admin';
                //     $registration->cancelled_at = now();
                //     $registration->save();
                // }
            }
            
            // Then delete the instructor
            $instructor->delete();
            
            // Finally, we could optionally handle the associated user account
            // $user = $instructor->user;
            // $user->delete(); // Only if you want to delete the user account as well
            
            DB::commit();
            
            return redirect()->route('admin.instructors.index')
                ->with('success', 'Instructeur en alle bijbehorende lessen zijn succesvol verwijderd.');
    
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting instructor: ' . $e->getMessage());
            
            return back()->withErrors(['error' => 'Er is een fout opgetreden bij het verwijderen van de instructeur: ' . $e->getMessage()]);
        }
    }
}
