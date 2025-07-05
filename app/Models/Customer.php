<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'status'
    ];

    public function orders(){
        return $this->hasMany(Order::class);
    }
    public function courses(){
        return $this->hasManyThrough(Course::class, Order::class, 'customer_id', 'id', 'id', 'course_id');
    }

}
