<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\RegistrationController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\InstructorMiddleware;
use App\Http\Middleware\StudentMiddleware;

// Home routes
Route::get('/', function () {
    return view('home.index');
});
Route::get('/home', function () {
    return view('home.index');
});

// Custom authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard route now uses the controller
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Lesson overview routes
    // Student routes
    Route::get('/lessons/student', [LessonController::class, 'studentOverview'])
        ->middleware(StudentMiddleware::class)  // Make sure this is 'student', not 'Student'
        ->name('lessons.student');

    // Instructor routes
    Route::get('/lessons/instructor', [LessonController::class, 'instructorOverview'])
        ->middleware(InstructorMiddleware::class)
        ->name('lessons.instructor');

    // Instructor routes with prefix
    Route::middleware(InstructorMiddleware::class)->prefix('instructor')->name('instructor.')->group(function () {
        // Profile management
        Route::get('/profile', [App\Http\Controllers\Instructor\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [App\Http\Controllers\Instructor\ProfileController::class, 'update'])->name('profile.update');
        
        // Student management
        Route::get('/students', [App\Http\Controllers\Instructor\StudentController::class, 'index'])->name('students.index');
        Route::get('/students/{id}', [App\Http\Controllers\Instructor\StudentController::class, 'show'])->name('students.show');
        
        // Lesson management
        Route::get('/lessons', [App\Http\Controllers\Instructor\LessonController::class, 'index'])->name('lessons.index');
        Route::get('/lessons/day', [App\Http\Controllers\Instructor\LessonController::class, 'dayView'])->name('lessons.day');
        Route::get('/lessons/week', [App\Http\Controllers\Instructor\LessonController::class, 'weekView'])->name('lessons.week');
        Route::get('/lessons/month', [App\Http\Controllers\Instructor\LessonController::class, 'monthView'])->name('lessons.month');
        Route::get('/lessons/{id}', [App\Http\Controllers\Instructor\LessonController::class, 'show'])->name('lessons.show');
        
        // Lesson cancellation
        Route::get('/lessons/{id}/cancel', [App\Http\Controllers\Instructor\LessonController::class, 'showCancelForm'])->name('lessons.cancel.form');
        Route::post('/lessons/{id}/cancel', [App\Http\Controllers\Instructor\LessonController::class, 'cancel'])->name('lessons.cancel');
        Route::post('/lessons/{id}/cancel-weather', [App\Http\Controllers\Instructor\LessonController::class, 'cancelWeather'])->name('lessons.cancel.weather');
        Route::post('/lessons/{id}/cancel-sick', [App\Http\Controllers\Instructor\LessonController::class, 'cancelSick'])->name('lessons.cancel.sick');
    });

    // Admin routes with the AdminMiddleware 
    Route::middleware([AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
        // Account management routes
        Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
        Route::get('/accounts/create', [AccountController::class, 'create'])->name('accounts.create');
        Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');
        Route::get('/accounts/{id}', [AccountController::class, 'show'])->name('accounts.show');
        Route::get('/accounts/{id}/edit', [AccountController::class, 'edit'])->name('accounts.edit');
        Route::put('/accounts/{id}', [AccountController::class, 'update'])->name('accounts.update');
        Route::delete('/accounts/{id}', [AccountController::class, 'destroy'])->name('accounts.destroy');
        
        // Added routes for the account functionality
        Route::patch('/accounts/{id}/change-role', [AccountController::class, 'changeRole'])->name('accounts.change-role');
        Route::get('/accounts/{id}/create-profile', [AccountController::class, 'createProfile'])->name('accounts.create-profile');
        
        // Registration management routes
        Route::get('/registrations', [RegistrationController::class, 'index'])->name('registrations.index');
        Route::get('/registrations/create', [RegistrationController::class, 'create'])->name('registrations.create');
        Route::post('/registrations', [RegistrationController::class, 'store'])->name('registrations.store');
        Route::get('/registrations/{id}', [RegistrationController::class, 'show'])->name('registrations.show');
        Route::get('/registrations/{id}/edit', [RegistrationController::class, 'edit'])->name('registrations.edit');
        Route::put('/registrations/{id}', [RegistrationController::class, 'update'])->name('registrations.update');
        Route::delete('/registrations/{id}', [RegistrationController::class, 'destroy'])->name('registrations.destroy');
        Route::patch('/registrations/{id}/mark-as-paid', [RegistrationController::class, 'markAsPaid'])->name('registrations.mark-as-paid');
        Route::patch('/registrations/{id}/mark-as-confirmed', [RegistrationController::class, 'markAsConfirmed'])->name('registrations.mark-as-confirmed');
        Route::post('/registrations/{id}/cancel', [RegistrationController::class, 'cancel'])->name('registrations.cancel');
    });

    // Student routes
    Route::middleware(\App\Http\Middleware\StudentMiddleware::class)->prefix('student')->name('student.')->group(function () {
        // Profile management
        Route::get('/profile', [App\Http\Controllers\Student\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [App\Http\Controllers\Student\ProfileController::class, 'update'])->name('profile.update');
        
        // Reservations
        Route::get('/reservations', [App\Http\Controllers\Student\ReservationController::class, 'list'])->name('reservations.list');
        Route::get('/reservations/create', [App\Http\Controllers\Student\ReservationController::class, 'index'])->name('reservations.index');
        Route::get('/reservations/package', [App\Http\Controllers\Student\ReservationController::class, 'create'])->name('reservations.create');
        Route::post('/reservations', [App\Http\Controllers\Student\ReservationController::class, 'store'])->name('reservations.store');
        Route::get('/reservations/{id}', [App\Http\Controllers\Student\ReservationController::class, 'show'])->name('reservations.show');
        
        // Cancellations
        Route::get('/reservations/{id}/cancel', [App\Http\Controllers\Student\ReservationController::class, 'showCancelForm'])->name('reservations.cancel.form');
        Route::post('/reservations/{id}/cancel', [App\Http\Controllers\Student\ReservationController::class, 'cancel'])->name('reservations.cancel');
        
        // Payments
        Route::get('/reservations/{id}/payment', [App\Http\Controllers\Student\ReservationController::class, 'showPaymentForm'])->name('reservations.payment.form');
        Route::post('/reservations/{id}/payment', [App\Http\Controllers\Student\ReservationController::class, 'markAsPaid'])->name('reservations.payment');
    });
});
