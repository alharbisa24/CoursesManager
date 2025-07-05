<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_id',
        'course_id',
        'price',
        'status'
    ];
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function course(){
        return $this->belongsTo(Course::class);
    }
}
