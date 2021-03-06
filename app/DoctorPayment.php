<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\{User, Doctor};
use Carbon\Carbon;

class DoctorPayment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date', 'deleted_at'];

    //date accessors
    public function getDateAttribute($date)
    {
        return Carbon::parse($date)->format('d M, Y');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d M, Y, g:i A');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d M, Y, g:i A');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id')->withTrashed();
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }
}
