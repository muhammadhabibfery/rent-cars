<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $guarded = ['gambar'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Str::title($value);
        $this->attributes['slug'] = Str::slug($value, '-');
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function getAvatar()
    {
        return ($this->avatar) ? asset('/storage' . '/' . $this->avatar) : asset('/img/default/default.jpg');
    }
}
