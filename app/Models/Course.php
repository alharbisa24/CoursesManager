<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Course_list;
class Course extends Model
{


    protected $fillable= [
        'image',
        'title',
        'description',
        'seats',
        'price',
        'status',
        'purchase_status'

    ];

    public function orders(){
        return $this->hasMany(Order::class);
    }
    public function course_list(){
        return $this->hasMany(Course_list::class);
    }

    public function course_videos(){
        return $this->hasMany(Course_list_videos::class);
    }
}
