<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Registration;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get counts for statistics
        $studentCount = Student::count();
        $instructorCount = Instructor::where('is_active', true)->count();
        
        // Get upcoming lessons
        $upcomingLessons = Registration::with(['student.user', 'instructor.user', 'package'])
            ->where('start_date', '>', now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_date')
            ->take(5)
            ->get();
            
        // Get unpaid lessons
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
            'unpaidLessons' => $unpaidLessons
        ]);
    }
}
