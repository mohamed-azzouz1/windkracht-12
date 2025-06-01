<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Student;

class Instructor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'is_active',
        'certification',
        'years_of_experience',
        'specialization',
        'biography',
        'address',
        'city',
        'date_of_birth',
        'bsn',
        'phone',
        'bio',
        // Add any other fillable fields here
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'date_of_birth' => 'date',
    ];

    /**
     * Get the user associated with the instructor.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the lessons for the instructor.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get all students associated with this instructor through registrations.
     *
     * @return \Illuminate\Database\Eloquent\Collection

     */
    public function getStudents()
    {
        // Directly query the students through registrations
        $studentIds = $this->registrations()
            ->pluck('student_id')
            ->unique()
            ->toArray();
        
        return Student::whereIn('id', $studentIds)
            ->with(['user', 'registrations' => function($query) {
                $query->where('instructor_id', $this->id);
            }])
            ->get();
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($instructor) {
            // Delete all registrations when instructor is deleted
            $instructor->registrations()->delete();
        });
    }
}
