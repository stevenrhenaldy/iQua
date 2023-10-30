<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppletNode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'applet_id',
        'group_id',
        'device_id',
        'type',
        'entity_id',
        'condition',
        'value',
    ];

    /**
     * Get the applet that owns the AppletNode
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function applet(): BelongsTo
    {
        return $this->belongsTo(Applet::class);
    }

    /**
     * Get the group that owns the AppletNode
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the device that owns the AppletNode
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Devices::class);
    }

    /**
     * Get the entity that owns the AppletNode
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

}
