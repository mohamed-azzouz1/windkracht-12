<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Registration;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the appropriate dashboard based on user role.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Check if user is an admin
        if ($user->role && $user->role->name === 'admin') {
            return $this->adminDashboard();
        }
        
        // Check if user is a student
        $student = Student::where('user_id', $user->id)->first();
        if ($student) {
            return $this->studentDashboard($student);
        }
        
        // Check if user is an instructor
        $instructor = Instructor::where('user_id', $user->id)->first();
        if ($instructor) {
            return $this->instructorDashboard($instructor);
        }
        
        // Default dashboard for other roles
        return view('dashboard');
    }
    
    /**
     * Show the student dashboard
     */
    private function studentDashboard($student)
    {
        // Check if profile is completed
        $profileCompleted = !empty($student->address) && 
                           !empty($student->city) && 
                           !empty($student->phone) && 
                           !empty($student->date_of_birth);
        
        // Get upcoming lessons
        $upcomingLessons = Registration::where('student_id', $student->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('start_date', '>=', now())
            ->with(['package', 'instructor.user'])
            ->orderBy('start_date')
            ->take(3)
            ->get();
        
        return view('dashboard.student', [
            'student' => $student,
            'profileCompleted' => $profileCompleted,
            'upcomingLessons' => $upcomingLessons,
        ]);
    }
    
    /**
     * Show the instructor dashboard.
     */
    private function instructorDashboard($instructor)
    {
        // Get registrations for this instructor
        $registrations = Registration::where('instructor_id', $instructor->id)
            ->with(['package', 'student.user', 'kitesurfer'])
            ->orderBy('start_date')
            ->get();
        
        // Calculate statistics
        $todayLessons = $registrations->filter(function($reg) {
            return $reg->start_date->isToday() && $reg->status != 'cancelled';
        });
        
        $upcomingWeekLessons = $registrations->filter(function($reg) {
            return $reg->start_date >= now() && 
                   $reg->start_date <= now()->addDays(7) && 
                   $reg->status != 'cancelled';
        });
        
        $completedLessons = $registrations->filter(function($reg) {
            return $reg->status == 'completed';
        });
        
        // Get unique student count
        $activeStudentIds = $registrations->where('status', '!=', 'cancelled')
            ->where('start_date', '>=', now()->subDays(30))
            ->pluck('student_id')
            ->unique();
        
        return view('dashboard.instructor', [
            'instructor' => $instructor,
            'todayLessons' => $todayLessons,
            'todayLessonsCount' => $todayLessons->count(),
            'upcomingWeekLessonsCount' => $upcomingWeekLessons->count(),
            'completedLessonsCount' => $completedLessons->count(),
            'activeStudentsCount' => $activeStudentIds->count(),
            'upcomingLessons' => $upcomingWeekLessons->take(5), // Just show 5 for the preview
        ]);
    }
    
    /**
     * Show the admin dashboard.
     */
    private function adminDashboard()
    {
        // Get system statistics
        $totalUsers = \App\Models\User::count();
        $totalStudents = Student::count();
        $activeInstructors = Instructor::where('is_active', true)->count();
        $totalLessons = Registration::count();
        
        // Get recent activities (you might need to create an Activity model for this)
        $recentActivities = \App\Models\Notification::latest()->take(10)->get();
        
        return view('dashboard.admin', [
            'totalUsers' => $totalUsers,
            'totalStudents' => $totalStudents,
            'activeInstructors' => $activeInstructors,
            'totalLessons' => $totalLessons,
            'recentActivities' => $recentActivities,
        ]);
    }
}
