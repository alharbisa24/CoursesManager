<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course_list extends Model
{
    protected $fillable= [
        'title',
        'course_id',
    ];

    public function course(){
        return $this->belongsTo(Course::class);
    }
    public function videos(){
     return $this->hasMany(Course_list_videos::class);
    }
}
