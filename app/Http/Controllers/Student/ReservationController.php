<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Registration;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Kitesurfer;
use App\Mail\ReservationConfirmation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ReservationController extends Controller
{
    /**
     * Show available packages for reservation.
     */
    public function index()
    {
        $packages = Package::all();
        
        return view('student.reservations.index', [
            'packages' => $packages,
        ]);
    }
    
    /**
     * Show the form for creating a new reservation.
     */
    public function create(Request $request)
    {
        $packageId = $request->input('package_id');
        $package = Package::findOrFail($packageId);
        
        // Get available dates (in this case, for the next 30 days)
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
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'location' => 'required|string',
            'has_duo' => 'boolean',
            'duo_name' => 'required_if:has_duo,1|nullable|string|max:255',
            'duo_email' => 'required_if:has_duo,1|nullable|email|max:255',
            'duo_phone' => 'required_if:has_duo,1|nullable|string|max:15',
        ]);
        
        $package = Package::findOrFail($validatedData['package_id']);
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();
        
        // Assign instructor (simplified - random assignment)
        $instructors = Instructor::where('is_active', true)->get();
        $instructor = $instructors->random();
        
        // Calculate start and end times
        $startDateTime = Carbon::parse($validatedData['date'] . ' ' . $validatedData['time']);
        $endDateTime = (clone $startDateTime)->addHours($package->duration_hours);
        
        // Create reservation
        $registration = Registration::create([
            'student_id' => $student->id,
            'package_id' => $package->id,
            'instructor_id' => $instructor->id,
            'start_date' => $startDateTime,
            'end_date' => $endDateTime,
            'status' => 'pending',
            'is_paid' => false,
            'location' => $validatedData['location'],
            'duo_name' => $request->has('has_duo') ? $validatedData['duo_name'] : null,
            'duo_email' => $request->has('has_duo') ? $validatedData['duo_email'] : null,
            'duo_phone' => $request->has('has_duo') ? $validatedData['duo_phone'] : null,
        ]);
        
        // Create kitesurfer profile
        Kitesurfer::create([
            'registration_id' => $registration->id,
            'instructor_id' => $instructor->id,
            'skill_level' => $student->skill_level ?? 'beginner',
            'has_own_equipment' => false,
        ]);
        
        // Send confirmation email
        Mail::to($user->email)->send(new ReservationConfirmation($registration));
        
        return redirect()->route('student.reservations.show', $registration->id)
            ->with('success', 'Je reservering is succesvol aangemaakt. Controleer je e-mail voor betalingsinstructies.');
    }
    
    /**
     * Display the specified reservation.
     */
    public function show($id)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();
        
        $reservation = Registration::where('student_id', $student->id)
            ->with(['package', 'instructor.user', 'kitesurfer'])
            ->findOrFail($id);
        
        return view('student.reservations.show', [
            'reservation' => $reservation,
        ]);
    }
    
    /**
     * Show all reservations for the student.
     */
    public function list()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();
        
        $reservations = Registration::where('student_id', $student->id)
            ->with(['package', 'instructor.user'])
            ->orderBy('start_date', 'desc')
            ->get();
        
        // Group registrations by status
        $upcoming = $reservations->filter(function($reg) {
            return $reg->start_date >= now() && $reg->status != 'cancelled';
        });
        
        $past = $reservations->filter(function($reg) {
            return $reg->end_date < now() || $reg->status == 'completed';
        });
        
        $cancelled = $reservations->filter(function($reg) {
            return $reg->status == 'cancelled';
        });
        
        return view('student.reservations.list', [
            'reservations' => $reservations,
            'upcoming' => $upcoming,
            'past' => $past,
            'cancelled' => $cancelled,
        ]);
    }
    
    /**
     * Show the form for cancelling a reservation.
     */
    public function showCancelForm($id)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();
        
        $reservation = Registration::where('student_id', $student->id)
            ->with(['package', 'instructor.user'])
            ->findOrFail($id);
        
        // Check if reservation can be cancelled
        if ($reservation->status === 'cancelled' || $reservation->status === 'completed') {
            return redirect()->route('student.reservations.show', $id)
                ->with('error', 'Deze reservering kan niet meer geannuleerd worden.');
        }
        
        return view('student.reservations.cancel', [
            'reservation' => $reservation,
        ]);
    }
    
    /**
     * Cancel a reservation.
     */
    public function cancel(Request $request, $id)
    {
        $validatedData = $request->validate([
            'cancellation_reason' => 'required|string|max:255',
        ]);
        
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();
        
        $reservation = Registration::where('student_id', $student->id)
            ->findOrFail($id);
        
        // Update reservation status
        $reservation->status = 'cancelled';
        $reservation->cancellation_reason = $validatedData['cancellation_reason'];
        $reservation->cancellation_type = 'student_request';
        $reservation->cancelled_at = now();
        $reservation->save();
        
        return redirect()->route('student.reservations.list')
            ->with('success', 'Je reservering is geannuleerd. Je reden voor annulering zal worden beoordeeld.');
    }
    
    /**
     * Show the form for marking a reservation as paid.
     */
    public function showPaymentForm($id)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();
        
        $reservation = Registration::where('student_id', $student->id)
            ->with(['package', 'instructor.user'])
            ->findOrFail($id);
        
        // Check if already paid
        if ($reservation->is_paid) {
            return redirect()->route('student.reservations.show', $id)
                ->with('info', 'Deze reservering is al gemarkeerd als betaald.');
        }
        
        return view('student.reservations.payment', [
            'reservation' => $reservation,
        ]);
    }
    
    /**
     * Mark a reservation as paid.
     */
    public function markAsPaid(Request $request, $id)
    {
        $validatedData = $request->validate([
            'payment_date' => 'required|date',
            'payment_reference' => 'nullable|string|max:255',
        ]);
        
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();
        
        $reservation = Registration::where('student_id', $student->id)
            ->findOrFail($id);
        
        // Update payment status
        $reservation->payment_date = $validatedData['payment_date'];
        $reservation->payment_reference = $validatedData['payment_reference'];
        $reservation->payment_reported_at = now();
        $reservation->save();
        
        return redirect()->route('student.reservations.show', $id)
            ->with('success', 'Bedankt voor het melden van je betaling. Dit zal worden geverifieerd door onze administratie.');
    }
    
    /**
     * Get available dates for reservation.
     */
    private function getAvailableDates($package)
    {
        $availableDates = [];
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addDays(30);
        
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            // Skip dates that are already fully booked
            // This is a simplified version - in a real application you would check instructor availability
            
            // Skip bad weather days
            // This is a simplified version - in a real application you might integrate with a weather API
            
            // Add to available dates
            $availableDates[] = [
                'date' => $date->format('Y-m-d'),
                'readable_date' => $date->format('d-m-Y'),
                'day_name' => $date->locale('nl')->isoFormat('dddd'),
                'available_times' => $this->getAvailableTimes($date, $package),
            ];
        }
        
        return $availableDates;
    }
    
    /**
     * Get available times for a date.
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
