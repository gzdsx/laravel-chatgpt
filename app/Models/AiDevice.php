<?php

namespace App\Models;

use App\Models\Traits\HasDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AiDevice
 *
 * @property int $id
 * @property string|null $device_id
 * @property int $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AiDevice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AiDevice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AiDevice query()
 * @method static \Illuminate\Database\Eloquent\Builder|AiDevice whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiDevice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiDevice whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiDevice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiDevice whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AiDevice extends Model
{
    //use HasFactory;

    use HasDates;

    protected $table = 'ai_device';
    protected $fillable = ['device_id', 'amount'];
}
