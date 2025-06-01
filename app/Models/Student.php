<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'phone',
        // Add any other fillable fields here
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'datetime',
    ];

    /**
     * Get the user associated with the student.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the registrations for the student.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get formatted date of birth.
     *
     * @return string
     */
    public function getFormattedDateOfBirthAttribute()
    {
        return $this->date_of_birth ? ($this->date_of_birth instanceof \DateTime 
            ? $this->date_of_birth->format('d-m-Y') 
            : $this->date_of_birth) : '';
    }
}
