<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'amount',
        'tax_amount',
        'total_amount',
        'status',
        'student_id',       // Add this field
        'reservation_ref',  // Add this field
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'status' => 'string',  // Explicitly cast status as string
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    
    // Add a relation to student
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    
    // Custom setter for status to ensure it's always a string
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = (string) $value;
    }
}
