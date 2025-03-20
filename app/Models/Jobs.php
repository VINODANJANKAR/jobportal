<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Skills;
use App\Models\Experiences;
use App\Models\User;

class Jobs extends Model
{
    use HasFactory;
    use HasFactory;
    protected $fillable = [
        'post_id','post_date', 'valid_up_to','post_type','job_type','upload_image','position', 
        'company_name', 'job_description','contact_person', 'contact_email', 'contact_phone',
         'location','skill_id', 'experience_id', 'is_repost','original_post_id', 'repost_date','status', 'post_by_id',
    ];
    
    public function users()
    {
        return $this->belongsTo(User::class, 'post_by_id');
    }
    public function skills()
    {
        return $this->belongsTo(Skills::class, 'skill_id');
    }
    public function experiences()
    {
        return $this->belongsTo(Experiences::class, 'experience_id');
    }
}