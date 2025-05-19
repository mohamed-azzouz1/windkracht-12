<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Package;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationConfirmed;
use App\Mail\RegistrationCancelled;
use Carbon\Carbon;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the registrations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Registration::with(['student.user', 'instructor.user', 'package', 'kitesurfer']);
        
        // Apply filters
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('paid') && $request->paid != '') {
            $query->where('is_paid', $request->paid === 'paid');
        }
        
        if ($request->has('instructor_id') && $request->instructor_id != '') {
            $query->where('instructor_id', $request->instructor_id);
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
        $query->orderBy('start_date', 'desc');
        
        // Paginate results
        $registrations = $query->paginate(15);
        
        return view('admin.registrations.index', [
            'registrations' => $registrations,
            'instructors' => Instructor::with('user')->where('is_active', true)->get(),
            'statuses' => ['pending', 'confirmed', 'cancelled', 'completed'],
        ]);
    }

    /**
     * Show the form for creating a new registration.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.registrations.create', [
            'students' => Student::with('user')->get(),
            'instructors' => Instructor::with('user')->where('is_active', true)->get(),
            'packages' => Package::all(),
        ]);
    }

    /**
     * Store a newly created registration in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'instructor_id' => 'required|exists:instructors,id',
            'package_id' => 'required|exists:packages,id',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'is_paid' => 'boolean',
            'duo_name' => 'nullable|string|max:255',
            'duo_email' => 'nullable|email',
        ]);
        
        // Get package duration
        $package = Package::find($validatedData['package_id']);
        
        // Calculate end date and time
        $startDateTime = Carbon::parse($validatedData['start_date'] . ' ' . $validatedData['start_time']);
        $endDateTime = $startDateTime->copy()->addHours($package->duration_hours);
        
        // Create registration
        $registration = Registration::create([
            'student_id' => $validatedData['student_id'],
            'instructor_id' => $validatedData['instructor_id'],
            'package_id' => $validatedData['package_id'],
            'start_date' => $startDateTime,
            'end_date' => $endDateTime,
            'status' => $validatedData['status'],
            'is_paid' => $request->has('is_paid'),
            'duo_name' => $validatedData['duo_name'] ?? null,
            'duo_email' => $validatedData['duo_email'] ?? null,
        ]);
        
        return redirect()->route('admin.registrations.show', $registration->id)
            ->with('success', 'Registratie succesvol aangemaakt.');
    }

    /**
     * Display the specified registration.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $registration = Registration::with([
            'student.user', 
            'instructor.user', 
            'package', 
            'kitesurfer'
        ])->findOrFail($id);
        
        return view('admin.registrations.show', [
            'registration' => $registration,
        ]);
    }

    /**
     * Show the form for editing the specified registration.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $registration = Registration::findOrFail($id);
        
        return view('admin.registrations.edit', [
            'registration' => $registration,
            'students' => Student::with('user')->get(),
            'instructors' => Instructor::with('user')->where('is_active', true)->get(),
            'packages' => Package::all(),
            'statuses' => ['pending', 'confirmed', 'cancelled', 'completed'],
        ]);
    }

    /**
     * Update the specified registration in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'instructor_id' => 'required|exists:instructors,id',
            'package_id' => 'required|exists:packages,id',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'is_paid' => 'boolean',
            'duo_name' => 'nullable|string|max:255',
            'duo_email' => 'nullable|email',
        ]);
        
        $registration = Registration::findOrFail($id);
        
        // Get package duration
        $package = Package::find($validatedData['package_id']);
        
        // Calculate end date and time
        $startDateTime = Carbon::parse($validatedData['start_date'] . ' ' . $validatedData['start_time']);
        $endDateTime = $startDateTime->copy()->addHours($package->duration_hours);
        
        // Check if status is changing from pending to confirmed
        $statusChanged = $registration->status !== $validatedData['status'] && 
                         $validatedData['status'] === 'confirmed';
        
        // Update registration
        $registration->student_id = $validatedData['student_id'];
        $registration->instructor_id = $validatedData['instructor_id'];
        $registration->package_id = $validatedData['package_id'];
        $registration->start_date = $startDateTime;
        $registration->end_date = $endDateTime;
        $registration->status = $validatedData['status'];
        $registration->is_paid = $request->has('is_paid');
        $registration->duo_name = $validatedData['duo_name'] ?? null;
        $registration->duo_email = $validatedData['duo_email'] ?? null;
        $registration->save();
        
        // Send confirmation email if status changed to confirmed
        if ($statusChanged && $registration->is_paid) {
            $this->sendConfirmationEmails($registration);
        }
        
        return redirect()->route('admin.registrations.show', $registration->id)
            ->with('success', 'Registratie succesvol bijgewerkt.');
    }

    /**
     * Remove the specified registration from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $registration = Registration::findOrFail($id);
        $registration->delete();
        
        return redirect()->route('admin.registrations.index')
            ->with('success', 'Registratie succesvol verwijderd.');
    }
    
    /**
     * Mark a registration as paid.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markAsPaid($id)
    {
        $registration = Registration::findOrFail($id);
        $registration->is_paid = true;
        $registration->save();
        
        // If already confirmed, send confirmation emails
        if ($registration->status === 'confirmed') {
            $this->sendConfirmationEmails($registration);
        }
        
        return redirect()->back()
            ->with('success', 'Registratie gemarkeerd als betaald.');
    }
    
    /**
     * Mark a registration as confirmed.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markAsConfirmed($id)
    {
        $registration = Registration::findOrFail($id);
        $registration->status = 'confirmed';
        $registration->save();
        
        // If already paid, send confirmation emails
        if ($registration->is_paid) {
            $this->sendConfirmationEmails($registration);
        }
        
        return redirect()->back()
            ->with('success', 'Registratie gemarkeerd als bevestigd.');
    }
    
    /**
     * Cancel a registration with a reason.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request, $id)
    {
        $validatedData = $request->validate([
            'cancellation_reason' => 'required|string|max:255',
            'cancellation_type' => 'required|in:weather,instructor_sick,other',
        ]);
        
        $registration = Registration::findOrFail($id);
        $registration->status = 'cancelled';
        $registration->cancellation_reason = $validatedData['cancellation_reason'];
        $registration->cancellation_type = $validatedData['cancellation_type'];
        $registration->cancelled_at = now();
        $registration->save();
        
        // Send cancellation email
        $this->sendCancellationEmail($registration);
        
        return redirect()->back()
            ->with('success', 'Registratie geannuleerd.');
    }
    
    /**
     * Send confirmation emails to student and instructor.
     *
     * @param  \App\Models\Registration  $registration
     * @return void
     */
    private function sendConfirmationEmails($registration)
    {
        // Load relationships if not already loaded
        if (!$registration->relationLoaded('student') || 
            !$registration->relationLoaded('instructor') || 
            !$registration->relationLoaded('package')) {
            $registration->load(['student.user', 'instructor.user', 'package']);
        }
        
        // Send email to student
        if ($registration->student && $registration->student->user) {
            Mail::to($registration->student->user->email)
                ->send(new RegistrationConfirmed($registration, 'student'));
        }
        
        // Send email to instructor
        if ($registration->instructor && $registration->instructor->user) {
            Mail::to($registration->instructor->user->email)
                ->send(new RegistrationConfirmed($registration, 'instructor'));
        }
        
        // Send email to duo if applicable
        if ($registration->duo_email) {
            Mail::to($registration->duo_email)
                ->send(new RegistrationConfirmed($registration, 'duo'));
        }
    }
    
    /**
     * Send cancellation email to student and instructor.
     *
     * @param  \App\Models\Registration  $registration
     * @return void
     */
    private function sendCancellationEmail($registration)
    {
        // Load relationships if not already loaded
        if (!$registration->relationLoaded('student') || 
            !$registration->relationLoaded('instructor') || 
            !$registration->relationLoaded('package')) {
            $registration->load(['student.user', 'instructor.user', 'package']);
        }
        
        // Send email to student
        if ($registration->student && $registration->student->user) {
            Mail::to($registration->student->user->email)
                ->send(new RegistrationCancelled($registration, 'student'));
        }
        
        // Send email to instructor
        if ($registration->instructor && $registration->instructor->user) {
            Mail::to($registration->instructor->user->email)
                ->send(new RegistrationCancelled($registration, 'instructor'));
        }
        
        // Send email to duo if applicable
        if ($registration->duo_email) {
            Mail::to($registration->duo_email)
                ->send(new RegistrationCancelled($registration, 'duo'));
        }
    }
}
