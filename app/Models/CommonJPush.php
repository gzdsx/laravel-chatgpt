<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\CommonJPush
 *
 * @property int $id
 * @property int|null $uid
 * @property string|null $platform
 * @property string|null $registrationid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static Builder|CommonJPush android()
 * @method static Builder|CommonJPush ios()
 * @method static Builder|CommonJPush newModelQuery()
 * @method static Builder|CommonJPush newQuery()
 * @method static Builder|CommonJPush query()
 * @method static Builder|CommonJPush whereCreatedAt($value)
 * @method static Builder|CommonJPush whereId($value)
 * @method static Builder|CommonJPush wherePlatform($value)
 * @method static Builder|CommonJPush whereRegistrationid($value)
 * @method static Builder|CommonJPush whereUid($value)
 * @method static Builder|CommonJPush whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CommonJPush extends Model
{
    protected $table = 'common_jpush';
    protected $primaryKey = 'id';
    protected $fillable = ['uid', 'platform', 'registrationid'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'uid');
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeIos(Builder $builder)
    {
        return $builder->where('platform', 'ios');
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function scopeAndroid(Builder $builder)
    {
        return $builder->where('platform', 'android');
    }
}
