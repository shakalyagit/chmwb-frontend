<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationReason extends Model
{
    use HasFactory;

    protected $fillable = ['application_head_id', 'reason_id'];

    public function applicationHead(): BelongsTo
    {
        return $this->belongsTo(ApplicationHead::class);
    }
}
