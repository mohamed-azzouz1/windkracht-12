<?php

namespace Database\Seeders;

use App\Models\Instructor;
use App\Models\Invoice;
use App\Models\Kitesurfer;
use App\Models\Notification;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin', 'description' => 'Administrator']);
        $instructorRole = Role::create(['name' => 'instructor', 'description' => 'Kitesurf Instructor']);
        $studentRole = Role::create(['name' => 'student', 'description' => 'Student']);
        
        // Create admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@windkracht12.nl',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);
        
        // Create some instructors
        $instructors = [];
        for ($i = 1; $i <= 5; $i++) {
            $instructorUser = User::create([
                'name' => "Instructor {$i}",
                'email' => "instructor{$i}@windkracht12.nl",
                'password' => Hash::make('password'),
                'role_id' => $instructorRole->id,
            ]);
            
            $instructors[] = Instructor::create([
                'user_id' => $instructorUser->id,
                'certification' => 'IKO Level ' . rand(1, 4),
                'years_of_experience' => rand(1, 10),
                'is_active' => true,
            ]);
        }
        
        // Create some students
        $students = [];
        for ($i = 1; $i <= 20; $i++) {
            $studentUser = User::create([
                'name' => "Student {$i}",
                'email' => "student{$i}@example.com",
                'password' => Hash::make('password'),
                'role_id' => $studentRole->id,
            ]);
            
            $students[] = Student::create([
                'user_id' => $studentUser->id,
                'date_of_birth' => now()->subYears(rand(18, 50))->format('Y-m-d'),
                'skill_level' => ['beginner', 'intermediate', 'advanced'][rand(0, 2)],
                'notes' => rand(0, 1) ? 'Some notes about this student' : null,
            ]);
        }
        
        // Create test instructor account for middleware testing
        $testInstructorUser = User::create([
            'name' => 'Test Instructor',
            'email' => 'test.instructor@windkracht12.nl',
            'password' => Hash::make('instructor123'),
            'role_id' => $instructorRole->id,
        ]);
        
        $testInstructor = Instructor::create([
            'user_id' => $testInstructorUser->id,
            'certification' => 'IKO Level 3',
            'years_of_experience' => 5,
            'is_active' => true,
        ]);
        
        // Create test student account for middleware testing
        $testStudentUser = User::create([
            'name' => 'Test Student',
            'email' => 'test.student@example.com',
            'password' => Hash::make('student123'),
            'role_id' => $studentRole->id,
        ]);
        
        $testStudent = Student::create([
            'user_id' => $testStudentUser->id,
            'date_of_birth' => '1995-05-15',
            'skill_level' => 'intermediate',
            'notes' => 'This is a test student account for middleware testing',
        ]);
        
        // Create packages
        $packages = [
            Package::create([
                'name' => 'Materiaal huur',
                'description' => '2,5 uur kitesurfen',
                'price' => 175,
                'original_price' => 190,
                'duration_hours' => 2.5,
                'number_of_sessions' => 1,
                'max_participants' => 1,
            ]),
            Package::create([
                'name' => 'Lesse Duo Kiteles',
                'description' => 'Op het board in 1 dagen!',
                'price' => 135,
                'original_price' => 200,
                'duration_hours' => 3.5,
                'number_of_sessions' => 1,
                'max_participants' => 2,
            ]),
            Package::create([
                'name' => 'Kitesurf Duo lespakket 3 lessen',
                'description' => 'Op het board in 3 dagen!',
                'price' => 375,
                'original_price' => 500,
                'duration_hours' => 10.5,
                'number_of_sessions' => 3,
                'max_participants' => 2,
            ]),
            Package::create([
                'name' => 'Kitesurf Duo lespakket 5 lessen',
                'description' => 'Op het board in 5 dagen!',
                'price' => 675,
                'original_price' => 800,
                'duration_hours' => 17.5,
                'number_of_sessions' => 5,
                'max_participants' => 2,
            ]),
        ];
        
        // Create registrations and related data
        foreach ($students as $index => $student) {
            // Not all students have registrations
            if (rand(0, 10) < 8) { // 80% chance of having a registration
                $package = $packages[array_rand($packages)];
                $instructor = $instructors[array_rand($instructors)];
                
                $registration = Registration::create([
                    'student_id' => $student->id,
                    'package_id' => $package->id,
                    'instructor_id' => $instructor->id,
                    'start_date' => now()->subDays(rand(0, 30)),
                    'end_date' => now()->addDays(rand(0, 60)),
                    'status' => ['pending', 'confirmed', 'completed'][rand(0, 2)],
                ]);
                
                // Create kitesurfer profile
                Kitesurfer::create([
                    'registration_id' => $registration->id,
                    'instructor_id' => $instructor->id,
                    'skill_level' => ['beginner', 'intermediate', 'advanced'][rand(0, 2)],
                    'has_own_equipment' => (bool)rand(0, 1),
                    'equipment_needs' => rand(0, 1) ? 'Needs kite and board' : null,
                ]);
                
                // Create additional notifications for instructors
                foreach ($instructors as $instructor) {
                    Notification::create([
                        'user_id' => $instructor->user_id,
                        'title' => 'New Class Assignment',
                        'message' => 'You have been assigned to teach a new class. Please check your schedule.',
                        'type' => 'info',
                        'is_read' => false,
                    ]);
                }
            }
        }
   

        
        
    }
}
