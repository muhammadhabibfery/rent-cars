<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use SoftDeletes;

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class)
            ->withPivot(['quantity'])
            ->withTimestamps();
    }

    protected $guarded = ['gambar'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Str::title($value);
    }

    public function setMerkAttribute($value)
    {
        $this->attributes['merk'] = Str::title($value);
    }

    public function setPlatNumberAttribute($value)
    {
        $this->attributes['plat_number'] = Str::upper($value);
    }

    public function setColorAttribute($value)
    {
        $this->attributes['color'] = Str::title($value);
    }

    public function getCarImage()
    {
        return ($this->car_image) ? asset('/storage' . '/' . $this->car_image) : asset('/img/default/no-image.png');
    }
}
