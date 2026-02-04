<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationDetail extends Model
{
    use HasFactory;

    protected $guarded = []; // Allow mass assignment for all fields

    protected $casts = [
        'dob' => 'date',
        'reg_date' => 'date',
    ];

    public function applicationHead(): BelongsTo
    {
        return $this->belongsTo(ApplicationHead::class);
    }
}
