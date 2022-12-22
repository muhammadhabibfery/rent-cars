<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get the transactions for the customer
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * set the customer's name
     *
     * @param  string $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Str::title($value);
    }

    /**
     * set the customer's email
     *
     * @param  string $value
     * @return void
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * get the customer's avatar
     *
     * @return mixed
     */
    public function getAvatar()
    {
        return ($this->avatar) ? asset('/storage' . '/' . $this->avatar) : asset('/img/default/default.jpg');
    }
}
