<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyLocations extends Model
{
    use HasFactory;

    protected $table = 'companylocations';

    public function company(){
        return $this->belongsTo(Company::class, 'id');
    }
}
