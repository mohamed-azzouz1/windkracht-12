<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Instructor;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Kitesurfer;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LessonController extends Controller
{
    public function studentOverview()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Je hebt geen studentenprofiel.');
        }
        
        // Simplified query to ensure we're getting registrations
        $registrations = Registration::where('student_id', $student->id)
            ->with(['package', 'instructor.user', 'kitesurfer'])
            ->orderBy('start_date')
            ->get();
        
        // Dump and die to check what's being retrieved
        // dd($registrations, $student);
        
        // Check if registrations is empty and add a message
        if ($registrations->isEmpty()) {
            session()->flash('info', 'Er zijn nog geen lessen gevonden voor jouw account.');
        }
        
        // Simple filtering to avoid any issues
        $upcoming = $registrations->filter(function($reg) {
            return $reg->start_date >= now() && $reg->status != 'cancelled';
        });
        
        $past = $registrations->filter(function($reg) {
            return $reg->end_date < now() || $reg->status == 'completed';
        });
            
        return view('lessons.student-overview', [
            'registrations' => $registrations,
            'upcoming' => $upcoming,
            'past' => $past,
            'student' => $student, // Pass student to view for debugging
        ]);
    }
    
    public function instructorOverview()
    {
        $user = Auth::user();
        $instructor = Instructor::where('user_id', $user->id)->first();
        
        if (!$instructor) {
            return redirect()->route('dashboard')->with('error', 'Je hebt geen instructeurprofiel.');
        }
        
        $registrations = Registration::where('instructor_id', $instructor->id)
            ->with(['package', 'student.user', 'kitesurfer'])
            ->orderBy('start_date')
            ->get();
            
        return view('lessons.instructor-overview', [
            'registrations' => $registrations,
            'upcoming' => $registrations->filter(function($registration) {
                return $registration->start_date >= now() && $registration->status != 'cancelled';
            }),
            'today' => $registrations->filter(function($registration) {
                return $registration->start_date <= now() && $registration->end_date >= now() && $registration->status == 'confirmed';
            }),
            'past' => $registrations->filter(function($registration) {
                return $registration->end_date < now() || $registration->status == 'completed';
            }),
        ]);
    }
}
