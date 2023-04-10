<?php

namespace App\Models;

use App\Models\Traits\HasDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserCommission
 *
 * @property int $id
 * @property int $uid
 * @property int $transaction_id
 * @property string $amount
 * @property string|null $detail
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\UserTransaction|null $transaction
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserCommission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCommission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCommission query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCommission whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCommission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCommission whereDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCommission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCommission whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCommission whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCommission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserCommission extends Model
{
    use HasDates;

    protected $table = 'user_commission';
    protected $fillable = ['amount', 'detail'];

    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'uid');
    }

    public function transaction()
    {
        return $this->belongsTo(UserTransaction::class, 'transaction_id', 'id');
    }
}
