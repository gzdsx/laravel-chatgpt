<?php

namespace App\Models;

use App\Models\Traits\HasDates;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Verify
 *
 * @property int $id
 * @property string|null $code 验证码
 * @property string|null $phone 手机号
 * @property string|null $email 邮箱
 * @property string|null $token 验证token
 * @property \Illuminate\Support\Carbon|null $expires_at 失效时间
 * @property \Illuminate\Support\Carbon|null $created_at 发送时间
 * @property \Illuminate\Support\Carbon|null $updated_at 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerify newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerify newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerify query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerify whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerify whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerify whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerify whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerify whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerify wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerify whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerify whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserVerify extends Model
{
    use HasDates;

    protected $table = 'user_verify';
    protected $primaryKey = 'id';
    protected $fillable = ['code', 'phone', 'email', 'token', 'expires_at'];
    protected $dates = ['expires_at'];

    /**
     * @param int $len
     * @return string
     */
    public static function generateCode($len = 6)
    {
        $code = '';
        $pattern = '1234567890';
        for ($i = 0; $i < $len; $i++) {
            $code .= $pattern[mt_rand(0, 9)];
        }

        return $code;
    }
}
