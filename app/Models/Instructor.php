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
        'bio',
        'phone',
        'address',
        'city',
        'postal_code',
        'date_of_birth',
        'bsn',
        'is_active',
        'hourly_rate',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
        'hourly_rate' => 'decimal:2',
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
