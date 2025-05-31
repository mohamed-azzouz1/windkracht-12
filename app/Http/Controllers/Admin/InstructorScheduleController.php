<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Instructor;
use App\Models\Registration;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class InstructorScheduleController extends Controller
{
    /**
     * Display the instructor selection page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $instructors = Instructor::with('user')->orderBy('id')->get();
        
        return view('admin.instructors.schedule.index', [
            'instructors' => $instructors
        ]);
    }

    /**
     * Display the day view for an instructor's schedule.
     */
    public function day(Request $request, $id)
    {
        $instructor = Instructor::with('user')->findOrFail($id);
        $date = $request->has('date') ? Carbon::parse($request->date) : Carbon::today();
        
        $lessons = Registration::with(['student.user', 'package'])
            ->where('instructor_id', $id)
            ->whereDate('start_date', $date)
            ->orderBy('start_date')
            ->get();
        
        return view('admin.instructors.schedule.day', [
            'instructor' => $instructor,
            'date' => $date,
            'lessons' => $lessons,
            'previousDay' => $date->copy()->subDay()->format('Y-m-d'),
            'nextDay' => $date->copy()->addDay()->format('Y-m-d'),
        ]);
    }

    /**
     * Display the week view for an instructor's schedule.
     */
    public function week(Request $request, $id)
    {
        $instructor = Instructor::with('user')->findOrFail($id);
        $startDate = $request->has('date') ? Carbon::parse($request->date)->startOfWeek() : Carbon::now()->startOfWeek();
        $endDate = $startDate->copy()->endOfWeek();
        
        $lessons = Registration::with(['student.user', 'package'])
            ->where('instructor_id', $id)
            ->whereBetween('start_date', [$startDate, $endDate])
            ->orderBy('start_date')
            ->get();
        
        $days = [];
        $period = CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $date) {
            $days[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('D'),
                'number' => $date->format('j'),
                'lessons' => $lessons->filter(function($lesson) use ($date) {
                    return $lesson->start_date->format('Y-m-d') === $date->format('Y-m-d');
                })
            ];
        }
        
        return view('admin.instructors.schedule.week', [
            'instructor' => $instructor,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'days' => $days,
            'previousWeek' => $startDate->copy()->subWeek()->format('Y-m-d'),
            'nextWeek' => $startDate->copy()->addWeek()->format('Y-m-d'),
        ]);
    }

    /**
     * Display the month view for an instructor's schedule.
     */
    public function month(Request $request, $id)
    {
        $instructor = Instructor::with('user')->findOrFail($id);
        
        // Get the start of the month
        $date = $request->has('date') ? Carbon::parse($request->date) : Carbon::now();
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();
        
        // Start from the beginning of the week that contains the first day of the month
        $calendarStart = $startOfMonth->copy()->startOfWeek();
        // End at the end of the week that contains the last day of the month
        $calendarEnd = $endOfMonth->copy()->endOfWeek();
        
        $lessons = Registration::with(['student.user', 'package'])
            ->where('instructor_id', $id)
            ->whereBetween('start_date', [$startOfMonth, $endOfMonth])
            ->orderBy('start_date')
            ->get();
        
        // Group lessons by date
        $lessonsByDate = [];
        foreach ($lessons as $lesson) {
            $dateKey = $lesson->start_date->format('Y-m-d');
            if (!isset($lessonsByDate[$dateKey])) {
                $lessonsByDate[$dateKey] = [];
            }
            $lessonsByDate[$dateKey][] = $lesson;
        }
        
        // Generate calendar days
        $calendarDays = [];
        $period = CarbonPeriod::create($calendarStart, $calendarEnd);
        
        foreach ($period as $day) {
            $dayLessons = isset($lessonsByDate[$day->format('Y-m-d')]) ? $lessonsByDate[$day->format('Y-m-d')] : [];
            
            $calendarDays[] = [
                'date' => $day->format('Y-m-d'),
                'day' => $day->day,
                'isCurrentMonth' => $day->month === $date->month,
                'isToday' => $day->isToday(),
                'lessons' => $dayLessons,
                'lessonCount' => count($dayLessons)
            ];
        }
        
        // Split into weeks
        $weeks = array_chunk($calendarDays, 7);
        
        return view('admin.instructors.schedule.month', [
            'instructor' => $instructor,
            'currentDate' => $date,
            'weeks' => $weeks,
            'previousMonth' => $date->copy()->subMonth()->format('Y-m-d'),
            'nextMonth' => $date->copy()->addMonth()->format('Y-m-d'),
        ]);
    }
}
