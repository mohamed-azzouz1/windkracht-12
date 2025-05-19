<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Instructor;
use App\Models\Registration;
use App\Mail\RegistrationCancelled;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class LessonController extends Controller
{
    /**
     * Display a listing of the instructor's lessons.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $instructor = Instructor::where('user_id', $user->id)->firstOrFail();
        
        $query = Registration::where('instructor_id', $instructor->id)
            ->with(['student.user', 'package', 'kitesurfer']);
        
        // Apply filters
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('start_date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('end_date', '<=', $request->end_date);
        }
        
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->whereHas('student.user', function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }
        
        // Sort registrations
        $query->orderBy('start_date', 'asc');
        
        // Paginate results
        $lessons = $query->paginate(15);
        
        // Get upcoming, today, and past lessons for stats
        $upcomingCount = Registration::where('instructor_id', $instructor->id)
            ->where('start_date', '>', Carbon::today()->endOfDay())
            ->where('status', '!=', 'cancelled')
            ->count();
            
        $todayCount = Registration::where('instructor_id', $instructor->id)
            ->whereDate('start_date', Carbon::today())
            ->where('status', '!=', 'cancelled')
            ->count();
            
        $completedCount = Registration::where('instructor_id', $instructor->id)
            ->where('status', 'completed')
            ->count();
        
        return view('instructor.lessons.index', [
            'lessons' => $lessons,
            'upcomingCount' => $upcomingCount,
            'todayCount' => $todayCount,
            'completedCount' => $completedCount,
            'statuses' => ['pending', 'confirmed', 'cancelled', 'completed'],
        ]);
    }
    
    /**
     * Display the daily view of lessons.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dayView(Request $request)
    {
        $user = Auth::user();
        $instructor = Instructor::where('user_id', $user->id)->firstOrFail();
        
        // Get selected date or default to today
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $selectedDate = Carbon::createFromFormat('Y-m-d', $date);
        
        // Get lessons for selected date
        $lessons = Registration::where('instructor_id', $instructor->id)
            ->whereDate('start_date', $selectedDate)
            ->with(['student.user', 'package', 'kitesurfer'])
            ->orderBy('start_date')
            ->get();
        
        return view('instructor.lessons.day', [
            'lessons' => $lessons,
            'selectedDate' => $selectedDate,
            'previousDay' => $selectedDate->copy()->subDay()->format('Y-m-d'),
            'nextDay' => $selectedDate->copy()->addDay()->format('Y-m-d'),
        ]);
    }
    
    /**
     * Display the weekly view of lessons.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function weekView(Request $request)
    {
        $user = Auth::user();
        $instructor = Instructor::where('user_id', $user->id)->firstOrFail();
        
        // Get selected week or default to current week
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $selectedDate = Carbon::createFromFormat('Y-m-d', $date);
        
        // Calculate week start (Monday) and end (Sunday)
        $weekStart = $selectedDate->copy()->startOfWeek();
        $weekEnd = $selectedDate->copy()->endOfWeek();
        
        // Get lessons for selected week
        $lessons = Registration::where('instructor_id', $instructor->id)
            ->whereBetween('start_date', [$weekStart, $weekEnd])
            ->with(['student.user', 'package', 'kitesurfer'])
            ->orderBy('start_date')
            ->get();
        
        // Group lessons by day
        $lessonsByDay = [];
        foreach ($lessons as $lesson) {
            $dayIndex = $lesson->start_date->format('Y-m-d');
            if (!isset($lessonsByDay[$dayIndex])) {
                $lessonsByDay[$dayIndex] = [];
            }
            $lessonsByDay[$dayIndex][] = $lesson;
        }
        
        return view('instructor.lessons.week', [
            'lessonsByDay' => $lessonsByDay,
            'weekDays' => $this->getWeekDays($weekStart),
            'selectedDate' => $selectedDate,
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
            'previousWeek' => $selectedDate->copy()->subWeek()->format('Y-m-d'),
            'nextWeek' => $selectedDate->copy()->addWeek()->format('Y-m-d'),
        ]);
    }
    
    /**
     * Display the monthly view of lessons.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function monthView(Request $request)
    {
        $user = Auth::user();
        $instructor = Instructor::where('user_id', $user->id)->firstOrFail();
        
        // Get selected month or default to current month
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $selectedDate = Carbon::createFromFormat('Y-m-d', $date);
        
        // Calculate month start and end
        $monthStart = $selectedDate->copy()->startOfMonth();
        $monthEnd = $selectedDate->copy()->endOfMonth();
        
        // Get lessons for selected month
        $lessons = Registration::where('instructor_id', $instructor->id)
            ->whereBetween('start_date', [$monthStart, $monthEnd])
            ->with(['student.user', 'package', 'kitesurfer'])
            ->orderBy('start_date')
            ->get();
        
        // Group lessons by day
        $lessonsByDay = [];
        foreach ($lessons as $lesson) {
            $dayIndex = $lesson->start_date->format('Y-m-d');
            if (!isset($lessonsByDay[$dayIndex])) {
                $lessonsByDay[$dayIndex] = [];
            }
            $lessonsByDay[$dayIndex][] = $lesson;
        }
        
        // Get calendar days (including padding days)
        $calendarDays = $this->getCalendarDays($monthStart);
        
        return view('instructor.lessons.month', [
            'lessonsByDay' => $lessonsByDay,
            'calendarDays' => $calendarDays,
            'selectedDate' => $selectedDate,
            'monthStart' => $monthStart,
            'monthEnd' => $monthEnd,
            'previousMonth' => $selectedDate->copy()->subMonth()->format('Y-m-d'),
            'nextMonth' => $selectedDate->copy()->addMonth()->format('Y-m-d'),
        ]);
    }
    
    /**
     * Display the specified lesson.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $instructor = Instructor::where('user_id', $user->id)->firstOrFail();
        
        $lesson = Registration::where('instructor_id', $instructor->id)
            ->with(['student.user', 'package', 'kitesurfer'])
            ->findOrFail($id);
        
        return view('instructor.lessons.show', [
            'lesson' => $lesson
        ]);
    }
    
    /**
     * Show the form for cancelling a lesson.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showCancelForm($id)
    {
        $user = Auth::user();
        $instructor = Instructor::where('user_id', $user->id)->firstOrFail();
        
        $lesson = Registration::where('instructor_id', $instructor->id)
            ->with(['student.user', 'package'])
            ->findOrFail($id);
        
        // Check if lesson can be cancelled
        if ($lesson->status === 'cancelled' || $lesson->status === 'completed') {
            return redirect()->route('instructor.lessons.show', $id)
                ->with('error', 'Deze les kan niet meer geannuleerd worden.');
        }
        
        return view('instructor.lessons.cancel', [
            'lesson' => $lesson
        ]);
    }
    
    /**
     * Cancel a lesson with custom reason.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request, $id)
    {
        $validatedData = $request->validate([
            'cancellation_reason' => 'required|string|max:255',
        ]);
        
        $user = Auth::user();
        $instructor = Instructor::where('user_id', $user->id)->firstOrFail();
        
        $lesson = Registration::where('instructor_id', $instructor->id)
            ->with(['student.user', 'package'])
            ->findOrFail($id);
        
        // Update lesson status
        $lesson->status = 'cancelled';
        $lesson->cancellation_reason = $validatedData['cancellation_reason'];
        $lesson->cancellation_type = 'other';
        $lesson->cancelled_at = now();
        $lesson->save();
        
        // Send cancellation emails
        $this->sendCancellationEmails($lesson);
        
        return redirect()->route('instructor.lessons.index')
            ->with('success', 'Les succesvol geannuleerd.');
    }
    
    /**
     * Cancel a lesson due to weather conditions.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancelWeather($id)
    {
        $user = Auth::user();
        $instructor = Instructor::where('user_id', $user->id)->firstOrFail();
        
        $lesson = Registration::where('instructor_id', $instructor->id)
            ->with(['student.user', 'package'])
            ->findOrFail($id);
        
        // Update lesson status
        $lesson->status = 'cancelled';
        $lesson->cancellation_reason = 'Slechte weersomstandigheden (windkracht > 10)';
        $lesson->cancellation_type = 'weather';
        $lesson->cancelled_at = now();
        $lesson->save();
        
        // Send cancellation emails
        $this->sendCancellationEmails($lesson);
        
        return redirect()->route('instructor.lessons.index')
            ->with('success', 'Les succesvol geannuleerd wegens slechte weersomstandigheden.');
    }
    
    /**
     * Cancel a lesson due to instructor illness.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancelSick($id)
    {
        $user = Auth::user();
        $instructor = Instructor::where('user_id', $user->id)->firstOrFail();
        
        $lesson = Registration::where('instructor_id', $instructor->id)
            ->with(['student.user', 'package'])
            ->findOrFail($id);
        
        // Update lesson status
        $lesson->status = 'cancelled';
        $lesson->cancellation_reason = 'Instructeur ziek';
        $lesson->cancellation_type = 'instructor_sick';
        $lesson->cancelled_at = now();
        $lesson->save();
        
        // Send cancellation emails
        $this->sendCancellationEmails($lesson);
        
        return redirect()->route('instructor.lessons.index')
            ->with('success', 'Les succesvol geannuleerd wegens ziekte.');
    }
    
    /**
     * Send cancellation emails to student and others.
     *
     * @param  \App\Models\Registration  $lesson
     * @return void
     */
    private function sendCancellationEmails($lesson)
    {
        // Load relationships if not already loaded
        if (!$lesson->relationLoaded('student') || 
            !$lesson->relationLoaded('instructor') || 
            !$lesson->relationLoaded('package')) {
            $lesson->load(['student.user', 'instructor.user', 'package']);
        }
        
        // Send email to student
        if ($lesson->student && $lesson->student->user) {
            Mail::to($lesson->student->user->email)
                ->send(new RegistrationCancelled($lesson, 'student'));
        }
        
        // Send email to duo if applicable
        if ($lesson->duo_email) {
            Mail::to($lesson->duo_email)
                ->send(new RegistrationCancelled($lesson, 'duo'));
        }
    }
    
    /**
     * Get an array of Carbon days for a week.
     *
     * @param  \Carbon\Carbon  $weekStart
     * @return array
     */
    private function getWeekDays(Carbon $weekStart)
    {
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $days[] = $weekStart->copy()->addDays($i);
        }
        return $days;
    }
    
    /**
     * Get calendar days including padding days.
     *
     * @param  \Carbon\Carbon  $monthStart
     * @return array
     */
    private function getCalendarDays(Carbon $monthStart)
    {
        $days = [];
        
        // Add padding days from previous month
        $firstDayOfWeek = $monthStart->dayOfWeek; // 0 (Sunday) to 6 (Saturday)
        $firstDayOfWeek = $firstDayOfWeek === 0 ? 7 : $firstDayOfWeek; // Convert to 1 (Monday) to 7 (Sunday)
        
        for ($i = 1; $i < $firstDayOfWeek; $i++) {
            $days[] = $monthStart->copy()->subDays($firstDayOfWeek - $i);
        }
        
        // Add days of current month
        $daysInMonth = $monthStart->daysInMonth;
        for ($i = 0; $i < $daysInMonth; $i++) {
            $days[] = $monthStart->copy()->addDays($i);
        }
        
        // Add padding days for next month to complete the last week
        $lastDay = $monthStart->copy()->endOfMonth();
        $remainingDays = 7 - $lastDay->dayOfWeek;
        if ($remainingDays < 7) {
            for ($i = 1; $i <= $remainingDays; $i++) {
                $days[] = $lastDay->copy()->addDays($i);
            }
        }
        
        return $days;
    }
}
