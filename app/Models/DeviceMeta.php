<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceMeta extends Model
{
    use HasFactory;

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'devices_id',
        'entity_id',
        'value',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }
}
