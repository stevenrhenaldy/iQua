<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use function PHPUnit\Framework\isNull;

class GroupUser extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'group_id',
        'name_alias',
        'invite_id',
        'role',
        'active_until',
        'accepted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active_until' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    public function getNameAttribute()
    {
        // dd($this->name_alias, $this->user->name);
        return !$this->accepted_at?"Pending Member" : $this->name_alias ?? $this->user->name;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function getExpiresAttribute(){
        if(!$this->active_until) return false;
        if(!isNull($this->accepted_at)) return true;
        $now = Carbon::now();
        return $this->active_until->lte($now);
    }

}
