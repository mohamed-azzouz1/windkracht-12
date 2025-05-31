<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Package;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\LessonCancelled;
use App\Mail\InstructorIllness;
use App\Mail\SevereWeather;
use App\Mail\StudentLessonConfirmed;
use App\Mail\InstructorLessonConfirmed;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the registrations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Registration::with(['student.user', 'instructor.user', 'package']);
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->whereHas('student.user', function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            })
            ->orWhereHas('instructor.user', function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%");
            });
        }
        
        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Apply payment filter
        if ($request->has('is_paid') && $request->is_paid !== '') {
            $query->where('is_paid', $request->is_paid);
        }
        
        // Apply date filters
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('start_date', '<=', $request->date_to);
        }
        
        // Apply sorting
        $sortField = $request->input('sort', 'start_date');
        $sortDirection = $request->input('direction', 'desc');
        
        $query->orderBy($sortField, $sortDirection);
        
        $registrations = $query->paginate(15)->withQueryString();
        
        return view('admin.registrations.index', [
            'registrations' => $registrations,
        ]);
    }

    /**
     * Show the form for creating a new registration.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $students = Student::with('user')->get();
        $instructors = Instructor::with('user')->where('is_active', true)->get();
        $packages = Package::all();
        
        return view('admin.registrations.create', [
            'students' => $students,
            'instructors' => $instructors,
            'packages' => $packages,
            'locations' => [
                'noordwijk' => 'Noordwijk', 
                'scheveningen' => 'Scheveningen', 
                'ijmuiden' => 'IJmuiden'
            ],
            'default_instructor_id' => request('instructor_id'),
            'default_student_id' => request('student_id'),
            'default_date' => request('date'),
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
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'required|string',
            'status' => 'required|in:pending,confirmed',
            'is_paid' => 'boolean',
            'duo_name' => 'nullable|string|max:255',
            'duo_email' => 'nullable|email',
            'duo_phone' => 'nullable|string|max:20',
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
                'student_id' => $validatedData['student_id'],
                'instructor_id' => $validatedData['instructor_id'],
                'package_id' => $package->id,
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
            
            DB::commit();
            
            return redirect()->route('admin.registrations.show', $registration->id)
                ->with('success', 'Les aangemaakt!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating registration: ' . $e->getMessage());
            
            return back()->withInput()->with('error', 'Er is een fout opgetreden bij het aanmaken van de les: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified registration.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $registration = Registration::with(['student.user', 'instructor.user', 'package'])->findOrFail($id);
        
        return view('admin.registrations.show', [
            'registration' => $registration
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
        $registration = Registration::with(['student.user', 'instructor.user', 'package'])->findOrFail($id);
        $students = Student::with('user')->get();
        $instructors = Instructor::with('user')->where('is_active', true)->get();
        $packages = Package::all();
        
        return view('admin.registrations.edit', [
            'registration' => $registration,
            'students' => $students,
            'instructors' => $instructors,
            'packages' => $packages,
            'locations' => [
                'noordwijk' => 'Noordwijk', 
                'scheveningen' => 'Scheveningen', 
                'ijmuiden' => 'IJmuiden'
            ],
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
        $registration = Registration::findOrFail($id);
        
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'instructor_id' => 'required|exists:instructors,id',
            'package_id' => 'required|exists:packages,id',
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'required|string',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'is_paid' => 'boolean',
            'duo_name' => 'nullable|string|max:255',
            'duo_email' => 'nullable|email',
            'duo_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            $package = Package::findOrFail($validatedData['package_id']);
            
            // Calculate start and end times
            $startDateTime = Carbon::parse($validatedData['date'] . ' ' . $validatedData['time']);
            $endDateTime = $startDateTime->copy()->addHours($package->duration_hours);
            
            // Update registration
            $registration->student_id = $validatedData['student_id'];
            $registration->instructor_id = $validatedData['instructor_id'];
            $registration->package_id = $package->id;
            $registration->start_date = $startDateTime;
            $registration->end_date = $endDateTime;
            $registration->status = $validatedData['status'];
            $registration->is_paid = $request->has('is_paid');
            $registration->location = $validatedData['location'];
            $registration->duo_name = $validatedData['duo_name'];
            $registration->duo_email = $validatedData['duo_email'];
            $registration->duo_phone = $validatedData['duo_phone'];
            $registration->notes = $validatedData['notes'];
            
            // Handle payment date and cancellation status
            if ($request->has('is_paid') && !$registration->payment_date) {
                $registration->payment_date = now();
                $registration->payment_verified_at = now();
            }
            
            if ($validatedData['status'] === 'cancelled' && !$registration->cancelled_at) {
                $registration->cancelled_at = now();
                $registration->cancellation_reason = $request->input('cancellation_reason', 'Geannuleerd door beheerder');
                $registration->cancellation_type = 'admin_request';
            }
            
            $registration->save();
            
            DB::commit();
            
            return redirect()->route('admin.registrations.show', $registration->id)
                ->with('success', 'Les bijgewerkt!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating registration: ' . $e->getMessage());
            
            return back()->withInput()->with('error', 'Er is een fout opgetreden bij het bijwerken van de les: ' . $e->getMessage());
        }
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
        
        try {
            DB::beginTransaction();
            
            // Delete registration
            $registration->delete();
            
            DB::commit();
            
            return redirect()->route('admin.registrations.index')
                ->with('success', 'Les verwijderd!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting registration: ' . $e->getMessage());
            
            return back()->with('error', 'Er is een fout opgetreden bij het verwijderen van de les: ' . $e->getMessage());
        }
    }

    /**
     * Mark a registration as paid and send confirmation emails.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markAsPaid($id)
    {
        $registration = Registration::with(['student.user', 'instructor.user', 'package'])->findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Update payment status
            $registration->is_paid = true;
            $registration->payment_date = now();
            $registration->payment_verified_at = now();
            
            // Change status from pending to confirmed
            if ($registration->status === 'pending') {
                $registration->status = 'confirmed';
            }
            
            $registration->save();
            
            // Send confirmation emails
            Mail::to($registration->student->user->email)
                ->send(new StudentLessonConfirmed($registration));
                
            Mail::to($registration->instructor->user->email)
                ->send(new InstructorLessonConfirmed($registration));
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Betaling geregistreerd en bevestigingen verstuurd naar student en instructeur.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error confirming payment: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Er is een fout opgetreden bij het bevestigen van de betaling: ' . $e->getMessage());
        }
    }

    /**
     * Mark a registration as confirmed and send notifications.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markAsConfirmed($id)
    {
        $registration = Registration::with(['student.user', 'instructor.user', 'package'])->findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Change status to confirmed
            $registration->status = 'confirmed';
            $registration->save();
            
            // Only send emails if the lesson is also paid
            if ($registration->is_paid) {
                // Send confirmation emails
                Mail::to($registration->student->user->email)
                    ->send(new StudentLessonConfirmed($registration));
                    
                Mail::to($registration->instructor->user->email)
                    ->send(new InstructorLessonConfirmed($registration));
                
                $successMessage = 'Les definitief bevestigd en bevestigingen verstuurd naar student en instructeur.';
            } else {
                $successMessage = 'Les definitief bevestigd. Bevestigingen worden pas verstuurd na betaling.';
            }
            
            DB::commit();
            
            return redirect()->back()->with('success', $successMessage);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error confirming registration: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Er is een fout opgetreden bij het bevestigen van de les: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for cancelling a registration.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showCancelForm($id)
    {
        $registration = Registration::with(['student.user', 'instructor.user', 'package'])->findOrFail($id);
        
        // If already cancelled, redirect back with message
        if ($registration->status === 'cancelled') {
            return redirect()->route('admin.registrations.show', $registration->id)
                ->with('info', 'Deze les is al geannuleerd.');
        }
        
        return view('admin.registrations.cancel', [
            'registration' => $registration
        ]);
    }

    /**
     * Cancel a registration with a custom reason.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request, $id)
    {
        $registration = Registration::findOrFail($id);
        
        // Validate the request
        $validatedData = $request->validate([
            'cancellation_reason' => 'required|string|max:255',
            'send_email' => 'boolean'
        ]);
        
        try {
            DB::beginTransaction();
            
            // Update registration status
            $registration->status = 'cancelled';
            $registration->cancellation_reason = $validatedData['cancellation_reason'];
            $registration->cancellation_type = 'admin_request';
            $registration->cancelled_at = now();
            $registration->save();
            
            // Send cancellation email if requested
            if ($request->has('send_email')) {
                Mail::to($registration->student->user->email)
                    ->send(new LessonCancelled($registration, $validatedData['cancellation_reason']));
            }
            
            DB::commit();
            
            return redirect()->route('admin.registrations.show', $registration->id)
                ->with('success', 'Les succesvol geannuleerd.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error cancelling registration: ' . $e->getMessage());
            
            return back()->with('error', 'Er is een fout opgetreden bij het annuleren van de les: ' . $e->getMessage());
        }
    }

    /**
     * Cancel a registration due to instructor illness.
     * Sends a standardized email to the student.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancelIllness($id)
    {
        $registration = Registration::with(['student.user', 'instructor.user'])->findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Update registration status
            $registration->status = 'cancelled';
            $registration->cancellation_reason = 'Instructeur ziek';
            $registration->cancellation_type = 'instructor_illness';
            $registration->cancelled_at = now();
            $registration->save();
            
            // Send standardized illness cancellation email
            Mail::to($registration->student->user->email)
                ->send(new InstructorIllness($registration));
            
            DB::commit();
            
            return redirect()->route('admin.registrations.show', $registration->id)
                ->with('success', 'Les geannuleerd wegens ziekte en e-mail verzonden naar klant.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error cancelling registration due to illness: ' . $e->getMessage());
            
            return back()->with('error', 'Er is een fout opgetreden bij het annuleren van de les: ' . $e->getMessage());
        }
    }

    /**
     * Cancel a registration due to severe weather conditions.
     * Sends a standardized email to the student.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancelWeather($id)
    {
        $registration = Registration::with(['student.user', 'instructor.user'])->findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Update registration status
            $registration->status = 'cancelled';
            $registration->cancellation_reason = 'Slechte weersomstandigheden (windkracht > 10)';
            $registration->cancellation_type = 'severe_weather';
            $registration->cancelled_at = now();
            $registration->save();
            
            // Send standardized weather cancellation email
            Mail::to($registration->student->user->email)
                ->send(new SevereWeather($registration));
            
            DB::commit();
            
            return redirect()->route('admin.registrations.show', $registration->id)
                ->with('success', 'Les geannuleerd wegens slechte weersomstandigheden en e-mail verzonden naar klant.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error cancelling registration due to weather: ' . $e->getMessage());
            
            return back()->with('error', 'Er is een fout opgetreden bij het annuleren van de les: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of unpaid registrations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unpaid(Request $request)
    {
        $query = Registration::with(['student.user', 'instructor.user', 'package'])
            ->where('is_paid', false)
            ->where('status', '!=', 'cancelled');
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->whereHas('student.user', function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }
        
        // Apply date filters
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('start_date', '<=', $request->date_to);
        }
        
        // Apply sorting
        $sortField = $request->input('sort', 'start_date');
        $sortDirection = $request->input('direction', 'asc');
        
        $query->orderBy($sortField, $sortDirection);
        
        $unpaidRegistrations = $query->paginate(15)->withQueryString();
        
        return view('admin.registrations.unpaid', [
            'registrations' => $unpaidRegistrations,
        ]);
    }
}
