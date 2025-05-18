<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kitesurfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'instructor_id',
        'skill_level',
        'has_own_equipment',
        'equipment_needs',
    ];

    protected $casts = [
        'has_own_equipment' => 'boolean',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }
}
