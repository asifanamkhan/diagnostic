<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\{User, DoctorPayment, Service, Test};
use Carbon\Carbon;

class Doctor extends Model
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
    protected $dates = ['deleted_at'];

    //date accessors
    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d M, Y, g:i A');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d M, Y, g:i A');
    }

    public static function boot() 
    {
        parent::boot();

        static::deleting(function($doctor) {
            $doctor->commissions()->detach();
            return true;
        });
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'doctor_id');
    }

    public function payments()
    {
        return $this->hasMany(DoctorPayment::class, 'doctor_id');
    }

    public function commissions()
    {
        return $this->belongsToMany(Test::class, 'commissions', 'doctor_id', 'test_id')
            ->withPivot('commission_type', 'commission', 'description', 'created_by', 'updated_by')
            ->withTimestamps();
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
