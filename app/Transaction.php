<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function cars()
    {
        return $this->belongsToMany(Car::class)
            ->withPivot(['quantity'])
            ->withTimestamps();
    }
}
