<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\Registration;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the appropriate dashboard based on user role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $role = $user->role->name;
        
        if ($role === 'admin') {
            // Admin/Owner dashboard
            $studentCount = Student::count();
            $instructorCount = Instructor::where('is_active', true)->count();
            $upcomingLessons = Registration::with(['student.user', 'instructor.user', 'package'])
                ->where('start_date', '>', Carbon::now())
                ->where('status', '!=', 'cancelled')
                ->orderBy('start_date')
                ->take(5)
                ->get();
            $unpaidLessons = Registration::with(['student.user', 'package'])
                ->where('is_paid', false)
                ->where('status', '!=', 'cancelled')
                ->orderBy('start_date')
                ->take(5)
                ->get();
                
            return view('admin.dashboard', [
                'studentCount' => $studentCount,
                'instructorCount' => $instructorCount,
                'upcomingLessons' => $upcomingLessons,
                'unpaidLessons' => $unpaidLessons,
            ]);
        } elseif ($role === 'instructor') {
            $instructor = Instructor::where('user_id', $user->id)->first();
            return $this->instructorDashboard($instructor);
        } elseif ($role === 'student') {
            $student = Student::where('user_id', $user->id)->first();
            return $this->studentDashboard($student);
        }
        
        // Default dashboard if no specific role
        return view('dashboard');
    }
    
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    private function adminDashboard()
    {
        // Admin dashboard implementation
        return view('dashboard.admin');
    }
    
    /**
     * Display the instructor dashboard.
     *
     * @param  \App\Models\Instructor|null  $instructor
     * @return \Illuminate\Http\Response
     */
    private function instructorDashboard($instructor)
    {
        // Create instructor if it doesn't exist
        if (!$instructor) {
            $instructor = Instructor::create([
                'user_id' => Auth::id(),
                'is_active' => true,
            ]);
        }
        
        // Profile completeness check
        $profileCompleted = !empty($instructor->address) && 
                            !empty($instructor->city) && 
                            !empty($instructor->date_of_birth) && 
                            !empty($instructor->bsn) && 
                            !empty($instructor->phone);
        
        // Dashboard statistics
        $todayLessons = Registration::where('instructor_id', $instructor->id)
            ->whereDate('start_date', Carbon::today())
            ->where('status', '!=', 'cancelled')
            ->count();
            
        $weekLessons = Registration::where('instructor_id', $instructor->id)
            ->whereBetween('start_date', [Carbon::now(), Carbon::now()->endOfWeek()])
            ->where('status', '!=', 'cancelled')
            ->count();
            
        $totalStudents = Registration::where('instructor_id', $instructor->id)
            ->distinct('student_id')
            ->count('student_id');
            
        $upcomingLessons = Registration::where('instructor_id', $instructor->id)
            ->where('start_date', '>', Carbon::now())
            ->where('status', '!=', 'cancelled')
            ->with(['student.user', 'package'])
            ->orderBy('start_date')
            ->take(5)
            ->get();
        
        return view('dashboard.instructor', [
            'profileCompleted' => $profileCompleted,
            'todayLessons' => $todayLessons,
            'weekLessons' => $weekLessons,
            'totalStudents' => $totalStudents,
            'upcomingLessons' => $upcomingLessons,
        ]);
    }
    
    /**
     * Show the student dashboard.
     *
     * @param  \App\Models\Student|null  $student
     * @return \Illuminate\Http\Response
     */
    private function studentDashboard($student)
    {
        // Create student if it doesn't exist
        if (!$student) {
            $student = Student::create([
                'user_id' => Auth::id(),
            ]);
        }
        
        // Profile completeness check
        $profileCompleted = !empty($student->address) && 
                            !empty($student->city) && 
                            !empty($student->postal_code) && 
                            !empty($student->date_of_birth) && 
                            !empty($student->phone);
        
        // Get upcoming lessons
        $upcomingLessons = Registration::where('student_id', $student->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('start_date', '>', Carbon::now())
            ->with(['package', 'instructor.user'])
            ->orderBy('start_date')
            ->take(3)
            ->get();
        
        // Get past lessons
        $pastLessons = Registration::where('student_id', $student->id)
            ->where(function($query) {
                $query->where('status', 'completed')
                      ->orWhere(function($q) {
                          $q->whereIn('status', ['pending', 'confirmed'])
                            ->where('end_date', '<', Carbon::now());
                      });
            })
            ->with(['package', 'instructor.user'])
            ->orderBy('start_date', 'desc')
            ->take(2)
            ->get();
        
        // Get cancelled lessons
        $cancelledLessons = Registration::where('student_id', $student->id)
            ->where('status', 'cancelled')
            ->with(['package', 'instructor.user'])
            ->orderBy('start_date', 'desc')
            ->take(2)
            ->get();
        
        return view('dashboard.student', [
            'profileCompleted' => $profileCompleted,
            'upcomingLessons' => $upcomingLessons,
            'pastLessons' => $pastLessons,
            'cancelledLessons' => $cancelledLessons,
        ]);
    }
}
