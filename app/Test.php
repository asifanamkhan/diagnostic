<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\{User, TestCategory, Doctor};
use Carbon\Carbon;

class Test extends Model
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

        static::deleting(function($test) {
            $test->commissions()->detach();
            return true;
        });
    }

    public function category()
    {
        return $this->belongsTo(TestCategory::class, 'test_category_id')->withTrashed();
    }

    public function commissions()
    {
        return $this->belongsToMany(Doctor::class, 'commissions', 'test_id', 'doctor_id')
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
