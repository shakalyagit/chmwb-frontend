<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ApplicationHead extends Model
{
    use HasFactory;

    protected $fillable = ['reference_id', 'status'];

    public function details(): HasOne
    {
        return $this->hasOne(ApplicationDetail::class);
    }

    public function reasons(): HasMany
    {
        return $this->hasMany(ApplicationReason::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(ApplicationMedia::class);
    }
}
