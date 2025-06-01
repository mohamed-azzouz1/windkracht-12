<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Registration;
use App\Models\Instructor;
use App\Models\User;
use App\Models\Role;
use App\Models\Package;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class StudentController extends Controller
{
    /**
     * Display a listing of students for the instructor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $instructor = Instructor::where('user_id', $user->id)->firstOrFail();
        
        // Get unique students from registrations
        $registrations = Registration::where('instructor_id', $instructor->id)
            ->with(['student.user'])
            ->get();
            
        $studentIds = $registrations->pluck('student_id')->unique();
        
        // Query students with search filter
        $query = Student::whereIn('id', $studentIds)
            ->with('user');
            
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->whereHas('user', function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }
        
        // Get students and count their lessons
        $students = $query->get();
        
        // Add lesson counts to each student
        foreach ($students as $student) {
            $student->lesson_count = $registrations->where('student_id', $student->id)->count();
            $student->completed_count = $registrations->where('student_id', $student->id)
                ->where('status', 'completed')->count();
            $student->upcoming_count = $registrations->where('student_id', $student->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('start_date', '>=', now())->count();
        }
        
        // Sort by name if no search, otherwise maintain search relevance
        if (!$request->has('search') || $request->search == '') {
            $students = $students->sortBy(function($student) {
                return $student->user->name;
            });
        }
        
        return view('instructor.students.index', [
            'students' => $students,
            'totalStudents' => $students->count(),
        ]);
    }
    
    /**
     * Show the form for creating a new student.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('instructor.students.create');
    }
    
    /**
     * Store a newly created student in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'skill_level' => 'nullable|string|in:beginner,intermediate,advanced',
            'notes' => 'nullable|string',
            'package_id' => 'required|exists:packages,id', // Add package_id validation
        ]);
        
        try {
            // Start transaction
            \DB::beginTransaction();
            
            // Create user
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);
            
            // Create student record
            $student = Student::create([
                'user_id' => $user->id,
                'address' => $validatedData['address'] ?? null,
                'city' => $validatedData['city'] ?? null,
                'phone' => $validatedData['phone'] ?? null,
                'date_of_birth' => $validatedData['date_of_birth'] ?? null,
                'skill_level' => $validatedData['skill_level'] ?? 'beginner',
                'notes' => $validatedData['notes'] ?? null,
            ]);
            
            // Get current instructor
            $instructor = Auth::user()->instructor;
            
            // Create registration to link student with instructor including the package_id
            Registration::create([
                'student_id' => $student->id,
                'instructor_id' => $instructor->id,
                'package_id' => $validatedData['package_id'], // Include package_id
                'status' => 'pending',
                'start_date' => now(),
                'end_date' => now()->addDays(30), // Set a default end date or based on package
                'location' => $request->location ?? 'Te bepalen',
            ]);
            
            \DB::commit();
            
            return redirect()->route('instructor.students.show', $student->id)
                ->with('success', 'Student succesvol aangemaakt.');
                
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error creating student: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Er is een fout opgetreden bij het aanmaken van de student: ' . $e->getMessage());
        }
    }
    
    /**
     * Display the specified student.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $instructor = Instructor::where('user_id', $user->id)->firstOrFail();
        
        $student = Student::with('user')->findOrFail($id);
        
        // Get registrations for this student and instructor
        $registrations = Registration::where('instructor_id', $instructor->id)
            ->where('student_id', $student->id)
            ->with(['package', 'kitesurfer'])
            ->orderBy('start_date', 'desc')
            ->get();
        
        // Check if instructor has lessons with this student
        if ($registrations->isEmpty()) {
            return redirect()->route('instructor.students.index')
                ->with('error', 'Je hebt geen lessen met deze student.');
        }
        
        // Group registrations
        $upcoming = $registrations->filter(function($reg) {
            return $reg->start_date >= now() && $reg->status != 'cancelled';
        });
        
        $past = $registrations->filter(function($reg) {
            return $reg->end_date < now() || $reg->status == 'completed';
        });
        
        $cancelled = $registrations->filter(function($reg) {
            return $reg->status == 'cancelled';
        });
        
        return view('instructor.students.show', [
            'student' => $student,
            'registrations' => $registrations,
            'upcoming' => $upcoming,
            'past' => $past,
            'cancelled' => $cancelled,
        ]);
    }
    
    /**
     * Show the form for editing the specified student.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        $instructor = Instructor::where('user_id', $user->id)->firstOrFail();
        
        $student = Student::with('user')->findOrFail($id);
        
        // Validate instructor has taught this student
        $hasLessons = Registration::where('student_id', $student->id)
            ->where('instructor_id', $instructor->id)
            ->exists();
            
        if (!$hasLessons) {
            return redirect()->route('instructor.students.index')
                ->with('error', 'Je hebt geen lessen gegeven aan deze student.');
        }
        
        return view('instructor.students.edit', [
            'student' => $student
        ]);
    }
    
    /**
     * Update the specified student's information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $instructor = Instructor::where('user_id', $user->id)->firstOrFail();
            
            $student = Student::with('user')->findOrFail($id);
            
            // Validate instructor has taught this student
            $hasLessons = Registration::where('student_id', $student->id)
                ->where('instructor_id', $instructor->id)
                ->exists();
                
            if (!$hasLessons) {
                return redirect()->route('instructor.students.index')
                    ->with('error', 'Je hebt geen lessen gegeven aan deze student.');
            }
            
            // Validate request
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'date_of_birth' => 'nullable|date|before:today',
                'skill_level' => 'nullable|string|in:beginner,intermediate,advanced',
                'notes' => 'nullable|string',
            ]);
            
            \DB::beginTransaction();
            
            // Update user name
            $studentUser = $student->user;
            $studentUser->name = $validatedData['name'];
            $studentUser->save();
            
            // Update student information
            $student->address = $validatedData['address'];
            $student->city = $validatedData['city'];
            $student->phone = $validatedData['phone'];
            $student->date_of_birth = $validatedData['date_of_birth'];
            $student->skill_level = $validatedData['skill_level'] ?? $student->skill_level;
            $student->notes = $validatedData['notes'];
            $student->save();
            
            \DB::commit();
            
            // Log the successful update
            \Log::info('Student updated successfully', [
                'student_id' => $student->id,
                'instructor_id' => $instructor->id,
                'data' => $validatedData
            ]);
            
            return redirect()->route('instructor.students.show', $student->id)
                ->with('success', 'Studentgegevens zijn succesvol bijgewerkt.');
                
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error updating student: ' . $e->getMessage(), [
                'student_id' => $id,
                'request_data' => $request->all(),
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withInput()
                ->with('error', 'Er is een fout opgetreden bij het bijwerken van de student: ' . $e->getMessage());
        }
    }
    
    /**
     * Remove the specified student from storage.
     * Note: This will only remove the association with the instructor,
     * not delete the actual student account.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $instructor = Instructor::where('user_id', $user->id)->firstOrFail();
        
        $student = Student::findOrFail($id);
        
        // Find all lessons between this instructor and student
        $lessons = Registration::where('student_id', $student->id)
            ->where('instructor_id', $instructor->id)
            ->get();
            
        if ($lessons->isEmpty()) {
            return back()->with('error', 'Geen lessen gevonden voor deze student.');
        }
        
        // Mark lessons as deleted or remove them
        foreach ($lessons as $lesson) {
            // Only mark future lessons as cancelled
            if ($lesson->start_date > now() && $lesson->status !== 'cancelled') {
                $lesson->status = 'cancelled';
                $lesson->cancellation_reason = 'Geannuleerd door instructeur';
                $lesson->cancellation_type = 'instructor_request';
                $lesson->cancelled_at = now();
                $lesson->save();
            }
        }
        
        return redirect()->route('instructor.students.index')
            ->with('success', 'Student is succesvol verwijderd uit jouw lessenrooster.');
    }
    
    /**
     * Show the form for creating a new lesson for this student.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createLesson($id)
    {
        $user = Auth::user();
        $instructor = Instructor::where('user_id', $user->id)->firstOrFail();
        
        $student = Student::with('user')->findOrFail($id);
        $packages = Package::all();
        
        return view('instructor.students.create_lesson', [
            'student' => $student,
            'instructor' => $instructor,
            'packages' => $packages
        ]);
    }
    
    /**
     * Store a newly created lesson for this student.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeLesson(Request $request, $id)
    {
        $validatedData = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'location' => 'required|string',
            'duo_name' => 'nullable|string|max:255',
            'duo_email' => 'nullable|email|max:255',
            'duo_phone' => 'nullable|string|max:20',
        ]);
        
        $user = Auth::user();
        $instructor = Instructor::where('user_id', $user->id)->firstOrFail();
        
        $student = Student::findOrFail($id);
        $package = Package::findOrFail($validatedData['package_id']);
        
        // Calculate start and end times
        $startDateTime = Carbon::parse($validatedData['date'] . ' ' . $validatedData['time']);
        $endDateTime = $startDateTime->copy()->addHours($package->duration_hours);
        
        // Create registration
        $registration = Registration::create([
            'student_id' => $student->id,
            'package_id' => $package->id,
            'instructor_id' => $instructor->id,
            'start_date' => $startDateTime,
            'end_date' => $endDateTime,
            'status' => 'confirmed', // Instructor-created lessons are confirmed by default
            'is_paid' => false,
            'location' => $validatedData['location'],
            'duo_name' => $validatedData['duo_name'],
            'duo_email' => $validatedData['duo_email'],
            'duo_phone' => $validatedData['duo_phone'],
        ]);
        
        return redirect()->route('instructor.lessons.show', $registration->id)
            ->with('success', 'Les is succesvol ingepland voor de student.');
    }
}
