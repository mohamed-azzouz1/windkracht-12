<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\DashboardController;

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
        ->middleware('student')
        ->name('lessons.student');
    
    // Instructor routes
    Route::get('/lessons/instructor', [LessonController::class, 'instructorOverview'])
        ->middleware('instructor')  // Make sure this is 'instructor', not 'Instructor'
        ->name('lessons.instructor');
});
