<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Registration;
use App\Models\Package;
use App\Models\Instructor;
use App\Models\Kitesurfer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class StudentLessonController extends Controller
{
    /**
     * Show the form for creating a new lesson.
     */
    public function create($id)
    {
        $student = Student::with('user')->findOrFail($id);
        $packages = Package::all();
        $instructors = Instructor::with('user')
            ->where('is_active', true)
            ->get();
            
        return view('admin.students.lessons.create', [
            'student' => $student,
            'packages' => $packages,
            'instructors' => $instructors,
            'locations' => [
                'noordwijk' => 'Noordwijk', 
                'scheveningen' => 'Scheveningen', 
                'ijmuiden' => 'IJmuiden'
            ],
        ]);
    }

    /**
     * Store a newly created lesson in storage.
     */
    public function store(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        
        $validatedData = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'instructor_id' => 'required|exists:instructors,id',
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'required|string',
            'duo_name' => 'nullable|string|max:255',
            'duo_email' => 'nullable|email',
            'duo_phone' => 'nullable|string|max:20',
            'status' => 'required|in:pending,confirmed',
            'is_paid' => 'boolean',
            'notes' => 'nullable|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            $package = Package::findOrFail($validatedData['package_id']);
            
            // Calculate start and end times
            $startDateTime = Carbon::parse($validatedData['date'] . ' ' . $validatedData['time']);
            $endDateTime = $startDateTime->copy()->addHours($package->duration_hours);
            
            // Create registration
            $registration = Registration::create([
                'student_id' => $student->id,
                'package_id' => $package->id,
                'instructor_id' => $validatedData['instructor_id'],
                'start_date' => $startDateTime,
                'end_date' => $endDateTime,
                'status' => $validatedData['status'],
                'is_paid' => $request->has('is_paid'),
                'location' => $validatedData['location'],
                'duo_name' => $validatedData['duo_name'],
                'duo_email' => $validatedData['duo_email'],
                'duo_phone' => $validatedData['duo_phone'],
                'notes' => $validatedData['notes'],
                'payment_date' => $request->has('is_paid') ? now() : null,
                'payment_verified_at' => $request->has('is_paid') ? now() : null,
            ]);
            
            // Create kitesurfer profile
            Kitesurfer::create([
                'registration_id' => $registration->id,
                'instructor_id' => $validatedData['instructor_id'],
                'skill_level' => $student->skill_level ?? 'beginner',
                'has_own_equipment' => false,
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.students.show', $student->id)
                ->with('success', 'Les aangemaakt!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating lesson: ' . $e->getMessage());
            
            return back()->withInput()->with('error', 'Er is een fout opgetreden bij het aanmaken van de les: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified lesson.
     */
    public function edit($id, $lessonId)
    {
        $student = Student::with('user')->findOrFail($id);
        $lesson = Registration::with(['package', 'instructor.user'])
            ->where('student_id', $id)
            ->findOrFail($lessonId);
            
        $packages = Package::all();
        $instructors = Instructor::with('user')
            ->where('is_active', true)
            ->get();
            
        return view('admin.students.lessons.edit', [
            'student' => $student,
            'lesson' => $lesson,
            'packages' => $packages,
            'instructors' => $instructors,
            'locations' => [
                'noordwijk' => 'Noordwijk', 
                'scheveningen' => 'Scheveningen', 
                'ijmuiden' => 'IJmuiden'
            ],
        ]);
    }

    /**
     * Update the specified lesson in storage.
     */
    public function update(Request $request, $id, $lessonId)
    {
        $student = Student::findOrFail($id);
        $lesson = Registration::where('student_id', $id)->findOrFail($lessonId);
        
        $validatedData = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'instructor_id' => 'required|exists:instructors,id',
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'required|string',
            'duo_name' => 'nullable|string|max:255',
            'duo_email' => 'nullable|email',
            'duo_phone' => 'nullable|string|max:20',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'is_paid' => 'boolean',
            'notes' => 'nullable|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            $package = Package::findOrFail($validatedData['package_id']);
            
            // Calculate start and end times
            $startDateTime = Carbon::parse($validatedData['date'] . ' ' . $validatedData['time']);
            $endDateTime = $startDateTime->copy()->addHours($package->duration_hours);
            
            // Update registration
            $lesson->package_id = $package->id;
            $lesson->instructor_id = $validatedData['instructor_id'];
            $lesson->start_date = $startDateTime;
            $lesson->end_date = $endDateTime;
            $lesson->status = $validatedData['status'];
            $lesson->is_paid = $request->has('is_paid');
            $lesson->location = $validatedData['location'];
            $lesson->duo_name = $validatedData['duo_name'];
            $lesson->duo_email = $validatedData['duo_email'];
            $lesson->duo_phone = $validatedData['duo_phone'];
            $lesson->notes = $validatedData['notes'];
            
            // Handle payment date and cancellation status
            if ($request->has('is_paid') && !$lesson->payment_date) {
                $lesson->payment_date = now();
                $lesson->payment_verified_at = now();
            }
            
            if ($validatedData['status'] === 'cancelled' && !$lesson->cancelled_at) {
                $lesson->cancelled_at = now();
                $lesson->cancellation_reason = $request->input('cancellation_reason', 'Geannuleerd door beheerder');
                $lesson->cancellation_type = 'admin_request';
            }
            
            $lesson->save();
            
            // Update kitesurfer profile
            $kitesurfer = Kitesurfer::where('registration_id', $lesson->id)->first();
            if ($kitesurfer) {
                $kitesurfer->instructor_id = $validatedData['instructor_id'];
                $kitesurfer->save();
            }
            
            DB::commit();
            
            return redirect()->route('admin.students.show', $student->id)
                ->with('success', 'Les bijgewerkt!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating lesson: ' . $e->getMessage());
            
            return back()->withInput()->with('error', 'Er is een fout opgetreden bij het bijwerken van de les: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified lesson from storage.
     */
    public function destroy($id, $lessonId)
    {
        $student = Student::findOrFail($id);
        $lesson = Registration::where('student_id', $id)->findOrFail($lessonId);
        
        try {
            DB::beginTransaction();
            
            // Delete kitesurfer profile first
            Kitesurfer::where('registration_id', $lesson->id)->delete();
            
            // Delete registration
            $lesson->delete();
            
            DB::commit();
            
            return redirect()->route('admin.students.show', $student->id)
                ->with('success', 'Les verwijderd!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting lesson: ' . $e->getMessage());
            
            return back()->with('error', 'Er is een fout opgetreden bij het verwijderen van de les: ' . $e->getMessage());
        }
    }
}
