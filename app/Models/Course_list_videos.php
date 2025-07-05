<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course_list_videos extends Model
{
    protected $fillable= [
        'title',
        'video',
        'description',
        'course_id',
        'course_list_id',
    ];

    public function courseList(){
        return $this->belongsTo(Course_list::class);
    }
    public function course(){
        return $this->belongsTo(Course::class);
    }
}
