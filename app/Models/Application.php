<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'reasons',
        'name',
        'father_name',
        'address',
        'district',
        'pincode',
        'police_station',
        'aadhaar',
        'dob',
        'blood_group',
        'mobile',
        'email',
        'reg_number',
        'reg_date',
        'qualification',
        'examination',
        'held_in',
        'university',
        'college',
        'final_roll_no',
        'term',
        'university_reg_no',
        'uploaded_files',
        'status',
        'reference_id',
    ];

    protected $casts = [
        'reasons' => 'array',
        'uploaded_files' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($application) {
            $application->reference_id = 'CHM' . now()->format('YmdHis') . rand(100, 999);
        });
    }
}
