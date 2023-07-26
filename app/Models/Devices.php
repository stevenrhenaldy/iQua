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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'serial_number',
        'group_id',
        'type',
        'name',
        'status',
        'assigned_at',
        'assigned_by_id'
    ];

    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function assigned_by(){
        return $this->belongsTo(User::class);
    }
}