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
        'instructor_id',
        'package_id',
        'kitesurfer_id',
        'start_date',
        'end_date',
        'status',
        // Add other fillable fields here
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
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
