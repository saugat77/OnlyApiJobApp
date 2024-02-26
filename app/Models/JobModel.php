<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = ['title','company_name','location','description','application_instruments','created_by'];
}
