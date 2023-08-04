<?php

namespace App\Models;

use App\Models\Traits\HasDates;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OpenAiRequestLog
 *
 * @property int $id 主键
 * @property int $uid 用户ID
 * @property string|null $prompt 提示词
 * @property string|null $reply 回复内容
 * @property \Illuminate\Support\Carbon|null $created_at 创建时间
 * @property \Illuminate\Support\Carbon|null $updated_at 更新时间
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiRequestLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiRequestLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiRequestLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiRequestLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiRequestLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiRequestLog wherePrompt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiRequestLog whereReply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiRequestLog whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiRequestLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OpenAiRequestLog extends Model
{
    use HasDates;

    protected $table = 'openai_request_log';
    protected $fillable = ['uid', 'prompt', 'reply'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'uid');
    }
}
