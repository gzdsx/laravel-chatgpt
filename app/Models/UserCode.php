<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\UserCode
 *
 * @property int $uid
 * @property string|null $code
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCode whereUid($value)
 * @mixin \Eloquent
 */
class UserCode extends Model
{
    //use HasFactory;

    protected $table = 'user_code';
    protected $primaryKey = 'uid';
    protected $fillable = ['uid', 'code'];

    public $timestamps = false;
    public $incrementing = false;


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'uid');
    }

    /**
     * @return string|null
     */
    public static function generateCode()
    {
        while (true) {
            $code = strtoupper(Str::random(10));
            if (!static::query()->where('code', $code)->exists()) {
                return $code;
            }
        }

        return null;
    }
}
