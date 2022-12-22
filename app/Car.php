<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The transactions that belong to the car.
     */
    public function transactions()
    {
        return $this->belongsToMany(Transaction::class)
            ->withTimestamps();
    }

    /**
     * set the car's name
     *
     * @param  string $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Str::title($value);
    }

    /**
     * set the car's merk
     *
     * @param  string $value
     * @return void
     */
    public function setMerkAttribute($value)
    {
        $this->attributes['merk'] = Str::title($value);
    }

    /**
     * set the car's plat number
     *
     * @param  string $value
     * @return void
     */
    public function setPlatNumberAttribute($value)
    {
        $this->attributes['plat_number'] = Str::upper($value);
    }

    /**
     * set the car's color
     *
     * @param  string $value
     * @return void
     */
    public function setColorAttribute($value)
    {
        $this->attributes['color'] = Str::title($value);
    }

    /**
     * get the car's image
     *
     * @return mixed
     */
    public function getImage()
    {
        return ($this->image) ? asset('/storage' . '/' . $this->image) : asset('/img/default/no-image.png');
    }
}
