<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplyJobModel extends Model
{
    use HasFactory;
    public $fillable = ['user_id','job_id','resumes','cover_letter'];
}
