<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'timezone',
    ];

    public function users(){
        return $this->belongsToMany(User::class, GroupUser::class)->whereNull('group_users.deleted_at')->whereNotNull('group_users.accepted_at');
    }

    public function usersAll(){
        return $this->belongsToMany(User::class, GroupUser::class)->whereNull('group_users.deleted_at');
    }

    public function groupUsers(){
        return $this->hasMany(GroupUser::class);
    }

    public function devices(){
        return $this->hasMany(Devices::class);
    }

    public function applets(){
        return $this->hasMany(Applet::class);
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
