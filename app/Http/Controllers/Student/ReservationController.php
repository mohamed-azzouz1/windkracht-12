<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\Student;
use App\Models\Package;
use App\Models\Instructor;
use App\Models\Kitesurfer;
use App\Mail\RegistrationCancelled;
use App\Mail\PaymentConfirmed;
use App\Mail\ReservationConfirmation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    /**
     * Display a listing of the student's reservations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();
        
        $registrations = Registration::where('student_id', $student->id)
            ->with(['package', 'instructor.user'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Group registrations by status
        $upcoming = $registrations->filter(function($reg) {
            return $reg->start_date > now() && $reg->status !== 'cancelled';
        });
        
        $past = $registrations->filter(function($reg) {
            return ($reg->end_date < now() || $reg->status === 'completed') && $reg->status !== 'cancelled';
        });
        
        $cancelled = $registrations->filter(function($reg) {
            return $reg->status === 'cancelled';
        });
        
        return view('student.reservations.list', [
            'registrations' => $registrations,
            'upcoming' => $upcoming,
            'past' => $past,
            'cancelled' => $cancelled,
        ]);
    }
    
    /**
     * Show the form for creating a new reservation - package selection.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all available packages
        // Changed: removing the where clause since 'active' column doesn't exist
        $packages = Package::all();
        
        // Group packages by type for better display
        $regularPackages = $packages->filter(function($package) {
            return !$package->is_duo;
        });
        
        $duoPackages = $packages->filter(function($package) {
            return $package->is_duo;
        });
        
        return view('student.reservations.index', [
            'regularPackages' => $regularPackages,
            'duoPackages' => $duoPackages
        ]);
    }
    
    /**
     * Show the form for creating a new reservation - details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $packageId = $request->input('package_id');
        $package = Package::findOrFail($packageId);
        
        // Get available dates
        $availableDates = $this->getAvailableDates($package);
        
        // Get available locations
        $locations = [
            'noordwijk' => 'Noordwijk',
            'scheveningen' => 'Scheveningen',
            'ijmuiden' => 'IJmuiden',
        ];
        
        return view('student.reservations.create', [
            'package' => $package,
            'availableDates' => $availableDates,
            'locations' => $locations,
        ]);
    }
    
    /**
     * Store a newly created reservation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $package = Package::findOrFail($request->input('package_id'));
        
        // Adjust validation based on whether it's a duo package
        $validationRules = [
            'package_id' => 'required|exists:packages,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'location' => 'required|string',
        ];
        
        // Add duo participant validation rules if it's a duo package
        if ($package->is_duo) {
            $validationRules = array_merge($validationRules, [
                'duo_name' => 'required|string|max:255',
                'duo_email' => 'required|email|max:255',
                'duo_phone' => 'required|string|max:20',
            ]);
        }
        
        $validatedData = $request->validate($validationRules);
        
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();
        
        // Check if profile is complete
        if (empty($student->address) || empty($student->city) || empty($student->phone) || empty($student->date_of_birth)) {
            return redirect()->route('student.profile.edit')
                ->with('error', 'Vul eerst je profiel in voordat je een reservering maakt.');
        }
        
        // Find an available instructor
        $instructors = Instructor::where('is_active', true)->get();
        if ($instructors->isEmpty()) {
            return back()->with('error', 'Geen instructeurs beschikbaar. Probeer het later opnieuw.');
        }
        $instructor = $instructors->random();
        
        // Calculate start and end times
        $startDateTime = Carbon::parse($validatedData['date'] . ' ' . $validatedData['time']);
        $endDateTime = $startDateTime->copy()->addHours($package->duration_hours);
        
        // Create registration
        $registration = Registration::create([
            'student_id' => $student->id,
            'package_id' => $package->id,
            'instructor_id' => $instructor->id,
            'start_date' => $startDateTime,
            'end_date' => $endDateTime,
            'status' => 'pending',
            'is_paid' => false,
            'location' => $validatedData['location'],
            'duo_name' => $package->is_duo ? $validatedData['duo_name'] : null,
            'duo_email' => $package->is_duo ? $validatedData['duo_email'] : null,
            'duo_phone' => $package->is_duo ? $validatedData['duo_phone'] : null,
        ]);
        
        // Create kitesurfer profile if the model exists
        try {
            if (class_exists('App\Models\Kitesurfer')) {
                \App\Models\Kitesurfer::create([
                    'registration_id' => $registration->id,
                    'instructor_id' => $instructor->id,
                    'skill_level' => $student->skill_level ?? 'beginner',
                    'has_own_equipment' => false,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error creating kitesurfer profile: ' . $e->getMessage());
            // Continue with the process
        }
        
        // Send confirmation email with error handling
        try {
            // Make sure the ReservationConfirmation class exists
            if (!class_exists('App\Mail\ReservationConfirmation')) {
                throw new \Exception('ReservationConfirmation mail class not found');
            }
            
            Mail::to($user->email)->send(new \App\Mail\ReservationConfirmation($registration));
            
            // Also notify instructor
            if ($instructor && $instructor->user) {
                Mail::to($instructor->user->email)->send(new \App\Mail\ReservationConfirmation($registration, 'instructor'));
            }
            
            // Log successful email
            Log::info('Reservation confirmation email sent to: ' . $user->email);
        } catch (\Exception $e) {
            // Log the error but don't prevent the reservation from being created
            Log::error('Email error: ' . $e->getMessage());
        }
        
        return redirect()->route('student.reservations.show', $registration->id)
            ->with('success', 'Reservering succesvol gemaakt! Bekijk de betalingsinstructies.');
    }
    
    /**
     * Display the specified reservation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();
        
        $reservation = Registration::where('id', $id)
            ->where('student_id', $student->id)
            ->with(['package', 'instructor.user'])
            ->firstOrFail();
        
        return view('student.reservations.show', [
            'reservation' => $reservation
        ]);
    }
    
    /**
     * Show the form for cancelling a reservation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showCancelForm($id)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();
        
        $reservation = Registration::where('id', $id)
            ->where('student_id', $student->id)
            ->with(['package', 'instructor.user'])
            ->firstOrFail();
        
        // Check if reservation can be cancelled
        if ($reservation->status === 'cancelled' || $reservation->status === 'completed') {
            return redirect()->route('student.reservations.show', $id)
                ->with('error', 'Deze reservering kan niet meer geannuleerd worden.');
        }
        
        return view('student.reservations.cancel', [
            'reservation' => $reservation
        ]);
    }
    
    /**
     * Cancel a reservation.
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
        $student = Student::where('user_id', $user->id)->firstOrFail();
        
        $reservation = Registration::where('student_id', $student->id)
            ->with(['instructor.user'])
            ->findOrFail($id);
        
        // Check if reservation can be cancelled
        if ($reservation->start_date->diffInHours(now()) < 24) {
            return redirect()->back()->with('error', 'Reserveringen kunnen alleen meer dan 24 uur van tevoren worden geannuleerd.');
        }
        
        // Update reservation status
        $reservation->status = 'cancelled';
        $reservation->cancellation_reason = $validatedData['cancellation_reason'];
        $reservation->cancellation_type = 'student_request';
        $reservation->cancelled_at = now();
        $reservation->save();
        
        // Send cancellation email to student
        try {
            Mail::to($user->email)
                ->send(new RegistrationCancelled($reservation, 'student'));
            
            // Also notify instructor
            if ($reservation->instructor && $reservation->instructor->user) {
                Mail::to($reservation->instructor->user->email)
                    ->send(new RegistrationCancelled($reservation, 'instructor'));
            }
        } catch (\Exception $e) {
            // Log error but continue
            Log::error('Email error: ' . $e->getMessage());
        }
        
        return redirect()->route('student.reservations.list')
            ->with('success', 'Je reservering is succesvol geannuleerd.');
    }
    
    /**
     * Show the form for marking a reservation as paid.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPaymentForm($id)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();
        
        $reservation = Registration::where('id', $id)
            ->where('student_id', $student->id)
            ->with(['package', 'instructor.user'])
            ->firstOrFail();
        
        // Check if already paid
        if ($reservation->is_paid) {
            return redirect()->route('student.reservations.show', $id)
                ->with('info', 'Deze reservering is al gemarkeerd als betaald.');
        }
        
        return view('student.reservations.payment', [
            'reservation' => $reservation
        ]);
    }
    
    /**
     * Mark a reservation as paid.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markAsPaid(Request $request, $id)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();
        
        $reservation = Registration::where('student_id', $student->id)
            ->findOrFail($id);
        
        // Update payment status
        $reservation->is_paid = true;
        $reservation->payment_date = now();
        $reservation->payment_reference = $request->input('payment_reference') ?? 'Online betaling';
        $reservation->payment_reported_at = now();
        $reservation->save();
        
        // Send payment confirmation email
        try {
            Mail::to($user->email)
                ->send(new PaymentConfirmed($reservation, 'student'));
                
            // Also notify instructor
            if ($reservation->instructor && $reservation->instructor->user) {
                Mail::to($reservation->instructor->user->email)
                    ->send(new PaymentConfirmed($reservation, 'instructor'));
            }
        } catch (\Exception $e) {
            // Log error but continue
            Log::error('Email error: ' . $e->getMessage());
        }
        
        return redirect()->route('student.reservations.show', $reservation->id)
            ->with('success', 'Betaling is succesvol geregistreerd.');
    }
    
    /**
     * Get available dates for a package.
     *
     * @param  \App\Models\Package  $package
     * @return array
     */
    private function getAvailableDates($package)
    {
        $availableDates = [];
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addDays(30); // Show next 30 days
        
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            // In a real application, you would check availability of instructors
            // and consider other factors before adding a date
            
            $availableDates[] = [
                'date' => $date->format('Y-m-d'),
                'display_date' => $date->format('d-m-Y'),
                'readable_date' => $date->locale('nl')->isoFormat('D MMMM YYYY'),
                'day_name' => $date->locale('nl')->isoFormat('dddd'),
                'times' => $this->getAvailableTimes($date, $package),
            ];
        }
        
        return $availableDates;
    }
    
    /**
     * Get available times for a date.
     *
     * @param  \Carbon\Carbon  $date
     * @param  \App\Models\Package  $package
     * @return array
     */
    private function getAvailableTimes($date, $package)
    {
        // In a real application, you would check availability of instructors
        // For this example, we'll provide fixed time slots
        
        $availableTimes = [];
        
        // Morning slots
        $availableTimes[] = '09:00';
        $availableTimes[] = '10:00';
        $availableTimes[] = '11:00';
        
        // Afternoon slots
        $availableTimes[] = '13:00';
        $availableTimes[] = '14:00';
        $availableTimes[] = '15:00';
        
        return $availableTimes;
    }
}
