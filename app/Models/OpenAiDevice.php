<?php

namespace App\Models;

use App\Models\Traits\HasDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OpenAiDevice
 *
 * @property int $id
 * @property string|null $device_id
 * @property int $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiDevice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiDevice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiDevice query()
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiDevice whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiDevice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiDevice whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiDevice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiDevice whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OpenAiDevice extends Model
{
    use HasDates;

    protected $table = 'openai_device';
    protected $fillable = ['uid', 'device_id', 'amount'];
}
