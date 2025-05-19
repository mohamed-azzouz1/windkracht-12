<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'package_id',
        'instructor_id',
        'start_date',
        'end_date',
        'status',
        'is_paid',
        'location',
        'duo_name',
        'duo_email',
        'duo_phone',
        'cancellation_reason',
        'cancellation_type',
        'cancelled_at',
        'payment_date',
        'payment_reference',
        'payment_reported_at',
        'payment_verified_at',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_paid' => 'boolean',
        'cancelled_at' => 'datetime',
        'payment_date' => 'date',
        'payment_reported_at' => 'datetime',
        'payment_verified_at' => 'datetime',
    ];

    /**
     * Get the student that owns the registration.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the instructor that owns the registration.
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    /**
     * Get the package that belongs to the registration.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the kitesurfer that belongs to the registration.
     */
    public function kitesurfer(): BelongsTo
    {
        return $this->belongsTo(Kitesurfer::class);
    }
}
