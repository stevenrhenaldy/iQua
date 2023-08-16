<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Devices extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->serial_number = (string) Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'serial_number';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'serial_number',
        'group_id',
        'device_type_id',
        'name',
        'status',
        'assigned_at',
        'assigned_by_id'
    ];

    public function type(){
        return $this->belongsTo(DeviceType::class, 'device_type_id');
    }

    public function meta(){
        return $this->hasMany(DeviceMeta::class);
    }

    public function getNameAttribute(){
        return $this->name?? $this->type?->name;
    }

    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function events(){
        return $this->hasMany(DeviceEvent::class);
    }

    public function assigned_by(){
        return $this->belongsTo(User::class);
    }
}
