<?php

namespace App\Models;

use App\Models\Traits\HasDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserWithdrawal
 *
 * @property int $id
 * @property int $uid
 * @property string $amount
 * @property int $state
 * @property int $teller_id
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $state_des
 * @property-read \App\Models\User|null $teller
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereTellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithdrawal whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserWithdrawal extends Model
{
    use HasDates;

    protected $table = 'user_withdrawal';
    protected $fillable = ['amount', 'state', 'completed_at'];
    protected $dates = ['completed_at'];
    protected $appends = ['state_des'];

    protected $stateMap = [
        '0' => '待处理',
        '1' => '已完成',
        '2' => '以失败'
    ];

    /**
     * @return string|null
     */
    public function getStateDesAttribute()
    {
        return $this->stateMap[$this->state] ?? null;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'uid');
    }

    public function teller()
    {
        return $this->belongsTo(User::class, 'teller_id', 'uid');
    }
}
