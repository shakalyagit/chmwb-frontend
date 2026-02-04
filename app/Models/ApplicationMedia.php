<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationMedia extends Model
{
    use HasFactory;

    protected $fillable = ['application_head_id', 'document_type', 'url', 'original_name', 'ext'];

    public function applicationHead(): BelongsTo
    {
        return $this->belongsTo(ApplicationHead::class);
    }
}
