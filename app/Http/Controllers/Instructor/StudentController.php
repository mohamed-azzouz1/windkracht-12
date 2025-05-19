<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\Registration;

class StudentController extends Controller
{
    /**
     * Display a listing of students for this instructor.
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
     * Display the specified student and their lessons.
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
}
