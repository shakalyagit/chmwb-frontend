<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'address_line',
        'district',
        'pincode',
        'state',
        'police_station',
    ];

    public function presentPharmacists()
    {
        return $this->hasMany(Pharmacist::class, 'present_address_id');
    }

    public function permanentPharmacists()
    {
        return $this->hasMany(Pharmacist::class, 'permanent_address_id');
    }
}