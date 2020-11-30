<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\{User, Patient, Doctor, ServiceList, ServicePayment};
use Carbon\Carbon;

class Service extends Model
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

    public function getDeliveryDateAttribute($date)
    {
        return Carbon::parse($date)->format('d M, Y, g:i A');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d M, Y, g:i A');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d M, Y, g:i A');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id')->withTrashed();
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id')->withTrashed();
    }

    public function lists()
    {
        return $this->hasMany(ServiceList::class, 'service_id');
    }

    public function payments()
    {
        return $this->hasMany(ServicePayment::class, 'service_id');
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
