<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'pharmacist_id',
        'registration_number',
    ];

    public function pharmacist()
    {
        return $this->belongsTo(Pharmacist::class);
    }
}