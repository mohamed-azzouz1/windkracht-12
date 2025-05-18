<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Package;
use App\Models\Registration;
use Carbon\Carbon;

class CreateTestRegistration extends Command
{
    protected $signature = 'test:registration {user_id?}';
    protected $description = 'Create a test registration for a student';

    public function handle()
    {
        $userId = $this->argument('user_id') ?? $this->ask('Enter the User ID for the student');
        
        $student = Student::where('user_id', $userId)->first();
        
        if (!$student) {
            $this->error('No student found with this user ID!');
            return 1;
        }
        
        $instructor = Instructor::first();
        if (!$instructor) {
            $this->error('No instructors found in the system!');
            return 1;
        }
        
        $package = Package::first();
        if (!$package) {
            $this->error('No packages found in the system!');
            return 1;
        }
        
        // Create upcoming registration
        Registration::create([
            'student_id' => $student->id,
            'instructor_id' => $instructor->id,
            'package_id' => $package->id,
            'start_date' => Carbon::now()->addDays(5),
            'end_date' => Carbon::now()->addDays(5)->addHours($package->duration_hours ?? 2),
            'status' => 'confirmed',
        ]);
        
        // Create past registration
        Registration::create([
            'student_id' => $student->id,
            'instructor_id' => $instructor->id,
            'package_id' => $package->id,
            'start_date' => Carbon::now()->subDays(10),
            'end_date' => Carbon::now()->subDays(10)->addHours($package->duration_hours ?? 2),
            'status' => 'completed',
        ]);
        
        $this->info('Test registrations created successfully!');
        return 0;
    }
}
