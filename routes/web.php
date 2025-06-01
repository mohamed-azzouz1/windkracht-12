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
        Route::get('/students/create', [App\Http\Controllers\Instructor\StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [App\Http\Controllers\Instructor\StudentController::class, 'store'])->name('students.store');
        Route::get('/students/{id}', [App\Http\Controllers\Instructor\StudentController::class, 'show'])->name('students.show');
        Route::get('/students/{id}/edit', [App\Http\Controllers\Instructor\StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{id}', [App\Http\Controllers\Instructor\StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{id}', [App\Http\Controllers\Instructor\StudentController::class, 'destroy'])->name('students.destroy');
        
        // Student lessons
        Route::get('/students/{id}/lessons/create', [App\Http\Controllers\Instructor\StudentController::class, 'createLesson'])->name('students.lessons.create');
        Route::post('/students/{id}/lessons', [App\Http\Controllers\Instructor\StudentController::class, 'storeLesson'])->name('students.lessons.store');
        
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

    // Admin routes
    Route::middleware([AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // User management
        Route::get('/users', [AppHttp\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/{id}/edit', [AppHttp\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [AppHttp\Controllers\Admin\UserController::class, 'update'])->name('users.update');
        
        // Students management
        Route::resource('students', App\Http\Controllers\Admin\StudentController::class);
        
        // Student lessons
        Route::get('/students/{id}/lessons/create', [App\Http\Controllers\Admin\StudentLessonController::class, 'create'])->name('students.lessons.create');
        Route::post('/students/{id}/lessons', [App\Http\Controllers\Admin\StudentLessonController::class, 'store'])->name('students.lessons.store');
        Route::get('/students/{id}/lessons/{lesson}/edit', [App\Http\Controllers\Admin\StudentLessonController::class, 'edit'])->name('students.lessons.edit');
        Route::put('/students/{id}/lessons/{lesson}', [App\Http\Controllers\Admin\StudentLessonController::class, 'update'])->name('students.lessons.update');
        Route::delete('/students/{id}/lessons/{lesson}', [AppHttp\Controllers\Admin\StudentLessonController::class, 'destroy'])->name('students.lessons.destroy');
        
        // Registrations/lessons
        Route::get('/registrations', [App\Http\Controllers\Admin\RegistrationController::class, 'index'])->name('registrations.index');
        Route::get('/registrations/create', [App\Http\Controllers\Admin\RegistrationController::class, 'create'])->name('registrations.create');
        
        // Unpaid registrations overview - moved up to avoid conflict with {id} route
        Route::get('/registrations/unpaid', [App\Http\Controllers\Admin\RegistrationController::class, 'unpaid'])->name('registrations.unpaid');
        
        Route::post('/registrations', [App\Http\Controllers\Admin\RegistrationController::class, 'store'])->name('registrations.store');
        Route::get('/registrations/{id}', [App\Http\Controllers\Admin\RegistrationController::class, 'show'])->name('registrations.show');
        Route::get('/registrations/{id}/edit', [App\Http\Controllers\Admin\RegistrationController::class, 'edit'])->name('registrations.edit');
        Route::put('/registrations/{id}', [App\Http\Controllers\Admin\RegistrationController::class, 'update'])->name('registrations.update');
        Route::delete('/registrations/{id}', [App\Http\Controllers\Admin\RegistrationController::class, 'destroy'])->name('registrations.destroy');
        Route::patch('/registrations/{id}/mark-as-paid', [App\Http\Controllers\Admin\RegistrationController::class, 'markAsPaid'])->name('registrations.mark-as-paid');
        Route::patch('/registrations/{id}/mark-as-confirmed', [App\Http\Controllers\Admin\RegistrationController::class, 'markAsConfirmed'])->name('registrations.mark-as-confirmed');
        
        // Cancellation routes
        Route::get('/registrations/{id}/cancel', [App\Http\Controllers\Admin\RegistrationController::class, 'showCancelForm'])->name('registrations.cancel.form');
        Route::post('/registrations/{id}/cancel', [AppHttp\Controllers\Admin\RegistrationController::class, 'cancel'])->name('registrations.cancel');
        Route::post('/registrations/{id}/cancel-illness', [AppHttp\Controllers\Admin\RegistrationController::class, 'cancelIllness'])->name('registrations.cancel.illness');
        Route::post('/registrations/{id}/cancel-weather', [AppHttp\Controllers\Admin\RegistrationController::class, 'cancelWeather'])->name('registrations.cancel.weather');
        
        // Instructors
        // Move the schedule routes BEFORE the resource controller to prevent conflicts
        Route::get('/instructors/schedule', [App\Http\Controllers\Admin\InstructorScheduleController::class, 'index'])->name('instructors.schedule.index'); // Changed route name to include .index
        Route::get('/instructors/{id}/schedule/day', [App\Http\Controllers\Admin\InstructorScheduleController::class, 'day'])->name('instructors.schedule.day');
        Route::get('/instructors/{id}/schedule/week', [App\Http\Controllers\Admin\InstructorScheduleController::class, 'week'])->name('instructors.schedule.week');
        Route::get('/instructors/{id}/schedule/month', [App\Http\Controllers\Admin\InstructorScheduleController::class, 'month'])->name('instructors.schedule.month');
        
        // Then the resource controller
        Route::resource('instructors', App\Http\Controllers\Admin\InstructorController::class);
        
        // Profile
        Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    });

    // Student routes
    Route::middleware(\App\Http\Middleware\StudentMiddleware::class)->prefix('student')->name('student.')->group(function () {
        // Profile management
        Route::get('/profile', [App\Http\Controllers\Student\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [App\Http\Controllers\Student\ProfileController::class, 'update'])->name('profile.update');
        
        // Reservations - Move the available-times route before the route with parameters
        Route::get('/reservations/available-times', [App\Http\Controllers\Student\ReservationController::class, 'getAvailableTimes'])->name('reservations.available-times');
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
