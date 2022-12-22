<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The name of current date time with specific timezone
     *
     * @var constant
     */
    private const TIMEZONE = 'Asia/Jakarta';

    /**
     * Get the customer that owns the transaction.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * The cars that belong to the transaction.
     */
    public function cars()
    {
        return $this->belongsToMany(Car::class)
            ->withTimestamps();
    }

    /**
     * get the transaction's start_date with custom format
     *
     * @return mixed
     */
    public function getStartDateWithDayAttribute()
    {
        return transformDateFormat($this->start_date, 'l, j F Y H:i');
    }

    /**
     * get the transaction's finish_date with custom format
     *
     * @return mixed
     */
    public function getFinishDateWithDayAttribute()
    {
        return transformDateFormat($this->finish_date, 'l, j F Y H:i');
    }

    /**
     * get the transaction's return_date with custom format
     *
     * @return mixed
     */
    public function getReturnDateWithDayAttribute()
    {
        return transformDateFormat($this->return_date, 'l, j F Y H:i');
    }

    /**
     * get the transaction's status with conditions
     *
     * @return bool
     */
    public function getTransactionStatusOnGoing()
    {
        return $this->finish_date > now(self::TIMEZONE);
    }

    /**
     * get the transaction's status with conditions
     *
     * @return bool
     */
    public function getTransactionStatusLate()
    {
        return $this->finish_date < now(self::TIMEZONE);
    }

    /**
     * get the transaction's status with conditions
     *
     * @return bool
     */
    public function getTransactionStatusCompleted()
    {
        return $this->status === 'COMPLETED';
    }

    /**
     * get the transaction's status
     *
     * @return array
     */
    public function getTransactionStatusAttribute()
    {
        if ($this->getTransactionStatusCompleted()) return ['text-success', 'SELESAI'];

        if ($this->getTransactionStatusOnGoing()) return ['text-primary', 'BERJALAN'];

        if ($this->getTransactionStatusLate()) return ['text-danger', 'TERLAMBAT'];
    }

    /**
     * Scope a query to only include transactions status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTransactionStatus($query, $status)
    {
        if (in_array($status, ['>', '<']))
            return $query->where('finish_date', $status, now(self::TIMEZONE))
                ->where('status', 'DP');

        if ($status === 'COMPLETED') return $query->where('status', $status);
    }
}
