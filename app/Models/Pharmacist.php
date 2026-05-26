<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pharmacist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'present_address_id',
        'permanent_address_id',
        'status',
    ];

    public function presentAddress()
    {
        return $this->belongsTo(Address::class, 'present_address_id');
    }

    public function permanentAddress()
    {
        return $this->belongsTo(Address::class, 'permanent_address_id');
    }

    public function pharmacyRegistrations()
    {
        return $this->hasMany(PharmacyRegistration::class);
    }
}