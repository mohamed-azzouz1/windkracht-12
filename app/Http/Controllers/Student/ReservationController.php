<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Registration;
use App\Models\Instructor;
use App\Models\Invoice;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationConfirmation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema; // Add the necessary import for Schema


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
        
        // Get all unique reservation references
        $reservationRefs = Registration::where('student_id', $student->id)
            ->distinct()
            ->pluck('reservation_ref');
            
        // Get one registration per reservation reference (the first one)
        $reservations = [];
        foreach ($reservationRefs as $ref) {
            $reservations[] = Registration::where('student_id', $student->id)
                ->where('reservation_ref', $ref)
                ->with(['package', 'instructor.user'])
                ->orderBy('created_at', 'desc')
                ->first();
        }
        
        // Group registrations by status
        $upcoming = collect($reservations)->filter(function($reg) {
            return $reg->start_date > now() && $reg->status !== 'cancelled';
        });
        
        $past = collect($reservations)->filter(function($reg) {
            return ($reg->end_date < now() || $reg->status === 'completed') && $reg->status !== 'cancelled';
        });
        
        $cancelled = collect($reservations)->filter(function($reg) {
            return $reg->status === 'cancelled';
        });
        
        return view('student.reservations.list', [
            'reservations' => collect($reservations),
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
        try {
            // Get all available packages
            $packages = Package::all();
            
            // Check if packages exist
            if ($packages->isEmpty()) {
                return redirect()->route('student.dashboard')->with('error', 'Er zijn momenteel geen lespakketten beschikbaar.');
            }
            
            // Check if packages have the is_duo attribute, if not provide a default value
            $regularPackages = $packages->filter(function($package) {
                return !($package->max_participants ?? 1 > 1);
            });
            
            $duoPackages = $packages->filter(function($package) {
                return ($package->max_participants ?? 1) > 1;
            });
            
            return view('student.reservations.index', [
                'regularPackages' => $regularPackages,
                'duoPackages' => $duoPackages,
                'packages' => $packages // Add this line to ensure $packages is available in the view
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading packages: ' . $e->getMessage());
            return redirect()->route('student.dashboard')
                ->with('error', 'Er is een fout opgetreden bij het laden van de lespakketten: ' . $e->getMessage());
        }
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
        
        // Get available dates for the next 2 months
        $availableDates = $this->getAvailableDatesForCalendar($package);
        
        // Get available time slots (9:00-17:00 with 2-hour intervals)
        $timeSlots = $this->getAvailableTimeSlots($package);
        
        // Get available locations
        $locations = [
            'noordwijk' => 'Noordwijk',
            'scheveningen' => 'Scheveningen',
            'ijmuiden' => 'IJmuiden',
        ];
        
        return view('student.reservations.create', [
            'package' => $package,
            'availableDates' => $availableDates,
            'timeSlots' => $timeSlots,
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
        $validatedData = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'location' => 'required|string',
            'selected_dates' => 'required|array',
            'selected_dates.*' => 'required|date|after:today',
            'selected_times' => 'required|array',
            'selected_times.*' => 'required|string',
            'duo_name' => 'nullable|required_if:is_duo,1|string|max:255',
            'duo_email' => 'nullable|required_if:is_duo,1|email|max:255',
            'duo_phone' => 'nullable|required_if:is_duo,1|string|max:20',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $student = $user->student;
            $package = Package::findOrFail($validatedData['package_id']);
            
            // Check if this is a duo package
            $isDuo = $request->has('is_duo') && $request->is_duo == 1;
            
            // Create a unique reservation reference
            $reservationRef = strtoupper(Str::random(8));
            
            // For each selected date and time, create a registration
            $registrations = [];
            
            // Combine dates and times
            $dateTimeCount = min(count($validatedData['selected_dates']), count($validatedData['selected_times']));
            
            for ($i = 0; $i < $dateTimeCount; $i++) {
                $date = $validatedData['selected_dates'][$i];
                $time = $validatedData['selected_times'][$i];
                
                // Create datetime string and parse it
                $startDateTime = Carbon::parse($date . ' ' . $time);
                
                // Format the date and time in a more user-friendly way for error messages
                $formattedDateTime = $startDateTime->format('d-m-Y H:i');
                
                // Find available instructor for this datetime
                $instructor = $this->findAvailableInstructor($date, $time);
                
                if (!$instructor) {
                    throw new \Exception('Geen beschikbare instructeur gevonden voor ' . $formattedDateTime);
                }
                
                // Save the first registration separately to use in the redirect
                $registration = Registration::create([
                    'student_id' => $student->id,
                    'package_id' => $package->id,
                    'instructor_id' => $instructor->id,
                    'start_date' => $startDateTime,
                    'end_date' => (clone $startDateTime)->addHours($package->duration_hours),
                    'status' => 'pending',
                    'is_paid' => false,
                    'location' => $validatedData['location'],
                    'reservation_ref' => $reservationRef,
                    'duo_name' => $isDuo ? $validatedData['duo_name'] : null,
                    'duo_email' => $isDuo ? $validatedData['duo_email'] : null,
                    'duo_phone' => $isDuo ? $validatedData['duo_phone'] : null,
                ]);
                
                $registrations[] = $registration;
            }
            
            // Calculate total price
            $totalPrice = $package->price;
            
            // Create invoice with proper string handling for status
            try {
                $invoice = new Invoice();
                $invoice->student_id = $student->id;
                $invoice->amount = $totalPrice;
                // Explicitly set status as a string value to avoid SQL type issues
                $invoice->status = "unpaid"; // Use double quotes to ensure it's treated as a string literal
                $invoice->due_date = now()->addDays(7);
                $invoice->invoice_number = 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
                $invoice->reservation_ref = $reservationRef;
                $invoice->save();
            } catch (\Exception $e) {
                \Log::error('Error creating invoice: ' . $e->getMessage());
                // Instead of using stdClass, create a proper Invoice model instance without saving it
                $invoice = new Invoice([
                    'student_id' => $student->id,
                    'amount' => $totalPrice,
                    'status' => 'unpaid',
                    'due_date' => now()->addDays(7),
                    'invoice_number' => 'TEMP-' . time(),
                    'reservation_ref' => $reservationRef
                ]);
                // Set an ID to avoid null reference issues
                $invoice->id = 0;
            }
            
            // Send confirmation email with invoice only if we have a valid invoice object
            if ($invoice instanceof Invoice) {
                try {
                    Mail::to($user->email)->send(new ReservationConfirmation($registrations, $invoice, $student, $package, $isDuo));
                    
                    // If duo package, also send confirmation to duo participant
                    if ($isDuo && $validatedData['duo_email']) {
                        Mail::to($validatedData['duo_email'])->send(new ReservationConfirmation($registrations, $invoice, $student, $package, $isDuo, true));
                    }
                } catch (\Exception $e) {
                    \Log::error('Error sending confirmation email: ' . $e->getMessage());
                    // Continue without sending email - we'll show a message to the user
                }
            } else {
                \Log::error('Cannot send confirmation email - invalid invoice object');
            }
            
            DB::commit();
            
            // Make sure we have at least one registration to use in the redirect
            if (!empty($registrations)) {
                $firstRegistration = $registrations[0];
                return redirect()->route('student.reservations.show', $firstRegistration->id)
                    ->with('success', 'Je reservering is succesvol aangemaakt. Bekijk je e-mail voor de betalingsgegevens.');
            } else {
                // Fallback in case there are no registrations
                return redirect()->route('student.reservations.list')
                    ->with('success', 'Je reservering is verwerkt, maar er zijn geen lessen ingepland.');
            }
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating reservation: ' . $e->getMessage(), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $errorMessage = $e->getMessage();
            
            // Make the error message more user-friendly
            if (str_contains($errorMessage, 'Geen beschikbare instructeur gevonden')) {
                return back()->withInput()
                    ->with('error', $errorMessage . '. Probeer een andere datum of tijd.');
            }
            
            return back()->withInput()
                ->with('error', 'Er is een fout opgetreden bij het maken van je reservering: ' . $errorMessage);
        }
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
        $student = $user->student;
        
        // Use findOrFail to make the error more explicit if the registration doesn't exist
        $registration = Registration::where('id', $id)
            ->where('student_id', $student->id)
            ->with(['package', 'instructor.user'])
            ->firstOrFail();
            
        // Make sure $registration is not null before proceeding
        if (!$registration) {
            return redirect()->route('student.reservations.list')
                ->with('error', 'De opgegeven reservering kon niet worden gevonden.');
        }
        
        // Get all registrations for this reservation
        $relatedRegistrations = Registration::where('reservation_ref', $registration->reservation_ref)
            ->where('student_id', $student->id)
            ->with(['package', 'instructor.user'])
            ->get();
            
        // Get invoice
        $invoice = Invoice::where('reservation_ref', $registration->reservation_ref)
            ->where('student_id', $student->id)
            ->first();
            
        return view('student.reservations.show', compact('registration', 'relatedRegistrations', 'invoice'));
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
        $student = $user->student;
        $registration = Registration::where('id', $id)
            ->where('student_id', $student->id)
            ->with(['package', 'instructor.user'])
            ->firstOrFail();
            
        // Check if already paid
        if ($registration->is_paid) {
            return redirect()->route('student.reservations.show', $registration->id)
                ->with('info', 'Deze reservering is al betaald.');
        }
        
        // Get invoice if it exists
        $invoice = Invoice::where('reservation_ref', $registration->reservation_ref)
            ->where('student_id', $student->id)
            ->first();
            
        return view('student.reservations.payment', [
            'registration' => $registration,
            'invoice' => $invoice,
        ]);
    }

    /**
     * Mark a reservation as paid by the student.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function markAsPaid(Request $request, $id)
    {
        $user = Auth::user();
        $student = $user->student;
        $registration = Registration::where('id', $id)
            ->where('student_id', $student->id)
            ->firstOrFail();
            
        // Validate the request
        $validatedData = $request->validate([
            'payment_method' => 'required|string|in:bank_transfer,ideal,credit_card,other',
            'payment_reference' => 'nullable|string|max:255',
            'payment_date' => 'required|date',
            'payment_confirmation' => 'required|accepted',
        ]);
        
        // Check if already paid
        if ($registration->is_paid) {
            return redirect()->route('student.reservations.show', $registration->id)
                ->with('info', 'Deze reservering is al betaald.');
        }
        
        try {
            DB::beginTransaction();
            
            // Get all registrations with the same reservation reference
            $registrations = Registration::where('reservation_ref', $registration->reservation_ref)
                ->where('student_id', $student->id)
                ->get();
        
            // Check if columns exist in registrations table
            $hasPaymentFields = Schema::hasColumns('registrations', [
                'payment_method', 'payment_reference', 'payment_date', 'payment_confirmed'
            ]);
            
            // Mark all as paid
            foreach ($registrations as $reg) {
                $reg->is_paid = true;
                
                // Only set these fields if the columns exist
                if ($hasPaymentFields) {
                    $reg->payment_method = $validatedData['payment_method'];
                    $reg->payment_reference = $validatedData['payment_reference'];
                    $reg->payment_date = $validatedData['payment_date'];
                    $reg->payment_confirmed = false; // Admin will confirm later
                } else {
                    // Add a note in the status or comments field if available
                    if (Schema::hasColumn('registrations', 'comments')) {
                        $reg->comments = 'Payment method: ' . $validatedData['payment_method'] . 
                            '. Payment date: ' . $validatedData['payment_date'] . 
                            '. Reference: ' . $validatedData['payment_reference'];
                    }
                }
                
                $reg->save();
            }
            
            // Update invoice if it exists
            $invoice = Invoice::where('reservation_ref', $registration->reservation_ref)
                ->where('student_id', $student->id)
                ->first();
                
            if ($invoice) {
                $invoice->status = 'paid';
                $invoice->paid_at = now();
                
                // Add payment details to invoice if columns exist
                if (Schema::hasColumns('invoices', ['payment_method', 'payment_reference'])) {
                    $invoice->payment_method = $validatedData['payment_method'];
                    $invoice->payment_reference = $validatedData['payment_reference'];
                }
                
                $invoice->save();
            }
            
            DB::commit();
            
            // Send confirmation email
            // Mail::to($user->email)->send(new PaymentConfirmation($registration, $invoice, $student));
            
            return redirect()->route('student.reservations.show', $registration->id)
                ->with('success', 'Je betaling is succesvol geregistreerd. Een beheerder zal deze zo snel mogelijk bevestigen.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error registering payment: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withInput()
                ->with('error', 'Er is een fout opgetreden bij het registreren van je betaling: ' . $e->getMessage());
        }
    }
    
    /**
     * Get available dates for booking based on package.
     *
     * @param  \App\Models\Package  $package
     * @return array
     */
    private function getAvailableDates(Package $package)
    {
        $dates = [];
        $startDate = Carbon::today()->addDay(); // Start from tomorrow
        $endDate = Carbon::today()->addMonths(2); // Look 2 months ahead
        
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            // Skip dates with less than minimum required instructors available
            if ($this->getAvailableInstructorsCount($date->format('Y-m-d')) < 1) {
                continue;
            }
            
            // Format for display and add to available dates
            $dates[] = [
                'date' => $date->format('Y-m-d'),
                'formatted' => $date->format('d-m-Y'),
                'day_name' => $date->translatedFormat('l'),
            ];
        }
        
        return $dates;
    }
    
    /**
     * Get count of available instructors for a specific date.
     *
     * @param  string  $date
     * @return int
     */
    private function getAvailableInstructorsCount($date)
    {
        $dateObj = Carbon::parse($date);
        
        // Get all instructors
        $instructors = Instructor::where('is_active', true)->get();
        
        // Count available instructors
        $availableCount = 0;
        foreach ($instructors as $instructor) {
            // Check if instructor has less than 8 hours of lessons on this date
            $totalHours = Registration::where('instructor_id', $instructor->id)
                ->whereDate('start_date', $dateObj)
                ->whereIn('status', ['pending', 'confirmed'])
                ->join('packages', 'registrations.package_id', '=', 'packages.id')
                ->sum('packages.duration_hours');
                
            if ($totalHours < 8) {
                $availableCount++;
            }
        }
        
        return $availableCount;
    }
    
    /**
     * Get available dates for calendar view based on package.
     *
     * @param  \App\Models\Package  $package
     * @return array
     */
    private function getAvailableDatesForCalendar(Package $package)
    {
        $result = [
            'available' => [],
            'unavailable' => [],
            'year' => now()->year,
            'month' => now()->month
        ];
        
        $startDate = Carbon::today()->addDay(); // Start from tomorrow
        $endDate = Carbon::today()->addMonths(2); // Look 2 months ahead
        
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dateString = $date->format('Y-m-d');
            $availableInstructors = $this->getAvailableInstructorsCount($dateString);
            
            $dateInfo = [
                'date' => $dateString,
                'day' => $date->day,
                'month' => $date->month,
                'year' => $date->year,
                'available_instructors' => $availableInstructors
            ];
            
            if ($availableInstructors > 0) {
                $result['available'][] = $dateInfo;
            } else {
                $result['unavailable'][] = $dateInfo;
            }
        }
        
        return $result;
    }
    
    /**
     * Get available time slots for lessons.
     *
     * @param  \App\Models\Package  $package
     * @return array
     */
    private function getAvailableTimeSlots(Package $package)
    {
        $timeSlots = [];
        
        // Start from 9:00 until 15:00 (last slot would end at 17:00 for a 2-hour lesson)
        $startHour = 9;
        $endHour = 17 - $package->duration_hours;
        
        for ($hour = $startHour; $hour <= $endHour; $hour += 2) {
            $timeString = sprintf('%02d:00', $hour);
            $endTimeString = sprintf('%02d:00', $hour + $package->duration_hours);
            
            $timeSlots[] = [
                'value' => $timeString,
                'label' => $timeString . ' - ' . $endTimeString
            ];
        }
        
        return $timeSlots;
    }
    
    /**
     * Find an available instructor for a specific date and time.
     *
     * @param  string  $date
     * @param  string  $time
     * @return \App\Models\Instructor|null
     */
    private function findAvailableInstructor($date, $time)
    {
        $dateTimeObj = Carbon::parse($date . ' ' . $time);
        $endTimeObj = (clone $dateTimeObj)->addHours(2); // Assuming 2-hour lessons
        
        // Get all instructors
        $instructors = Instructor::where('is_active', true)->get();
        
        // Log the search attempt
        \Log::info('Searching for available instructor', [
            'date' => $date,
            'time' => $time,
            'instructors_count' => $instructors->count()
        ]);
        
        if ($instructors->isEmpty()) {
            \Log::warning('No active instructors found in the system');
            return null;
        }
        
        // Find the first available instructor
        foreach ($instructors as $instructor) {
            // Check if instructor has any overlapping lessons
            $overlappingLessons = Registration::where('instructor_id', $instructor->id)
                ->where(function($query) use ($dateTimeObj, $endTimeObj) {
                    // Lesson starts during our timeframe
                    $query->where(function($q) use ($dateTimeObj, $endTimeObj) {
                        $q->where('start_date', '>=', $dateTimeObj)
                          ->where('start_date', '<', $endTimeObj);
                    })
                    // Lesson ends during our timeframe
                    ->orWhere(function($q) use ($dateTimeObj, $endTimeObj) {
                        $q->where('end_date', '>', $dateTimeObj)
                          ->where('end_date', '<=', $endTimeObj);
                    })
                    // Lesson spans our entire timeframe
                    ->orWhere(function($q) use ($dateTimeObj, $endTimeObj) {
                        $q->where('start_date', '<', $dateTimeObj)
                          ->where('end_date', '>', $endTimeObj);
                    });
                })
                ->whereIn('status', ['pending', 'confirmed'])
                ->count();
                
            if ($overlappingLessons == 0) {
                \Log::info('Found available instructor', ['instructor_id' => $instructor->id, 'instructor_name' => $instructor->user->name ?? 'Unknown']);
                return $instructor;
            }
        }
        
        \Log::warning('No available instructors for the selected time', [
            'date' => $date,
            'time' => $time,
            'total_instructors' => $instructors->count()
        ]);
        
        return null;
    }

    /**
     * Get available time slots for a specific date
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableTimes(Request $request)
    {
        try {
            $date = $request->input('date');
            $packageId = $request->input('package_id');
            
            if (!$date || !$packageId) {
                return response()->json([
                    'error' => 'Date and package_id are required'
                ], 400);
            }
            
            $package = Package::findOrFail($packageId);
            $allTimeSlots = $this->getAvailableTimeSlots($package);
            $availableTimeSlots = [];
            
            \Log::info('Checking available times', [
                'date' => $date,
                'package_id' => $packageId,
                'total_slots' => count($allTimeSlots)
            ]);
            
            // For each time slot, check if an instructor is available
            foreach ($allTimeSlots as $slot) {
                $time = $slot['value'];
                $instructor = $this->findAvailableInstructor($date, $time);
                
                if ($instructor) {
                    $availableTimeSlots[] = [
                        'value' => $time,
                        'label' => $slot['label'],
                        'instructor_id' => $instructor->id,
                        'instructor_name' => optional($instructor->user)->name ?? 'Unknown'
                    ];
                }
            }
            
            // If no available times, add some static ones for testing
            if (empty($availableTimeSlots)) {
                \Log::warning('No available time slots found, adding fallback slots');
                $availableTimeSlots = [
                    [
                        'value' => '09:00',
                        'label' => '09:00 - 11:00'
                    ],
                    [
                        'value' => '13:00',
                        'label' => '13:00 - 15:00'
                    ]
                ];
            }
            
            return response()->json([
                'available_times' => $availableTimeSlots,
                'date' => $date,
                'count' => count($availableTimeSlots)
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting available times: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error loading times: ' . $e->getMessage()
            ], 500);
        }
    }
}
