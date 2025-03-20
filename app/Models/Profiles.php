<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'first_name', 
        'last_name', 
        'gender', 
        'mobile_number', 
        'aadhar_card_no', 
        'address',
        'city', 
        'state', 
        'pin_code',
        'skill_id',
        'qualification_id',
        'experience_id', 
        'current_salary', 
        'photo', 
        'cv', 
        'password', 
        'current_location',
        'passing_year',
    ];

    
    public function skills()
    {
        return $this->belongsTo(Skills::class, 'skill_id');
    }

    public function experiences()
    {
        return $this->belongsTo(Experiences::class, 'experience_id');
    }

    public function qualifications()
    {
        return $this->belongsTo(Qualifications::class, 'qualification_id');
    }
}
