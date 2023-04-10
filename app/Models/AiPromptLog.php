<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AiPromptLog
 *
 * @property int $id
 * @property int $uid
 * @property string|null $prompt
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|AiPromptLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AiPromptLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AiPromptLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|AiPromptLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiPromptLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiPromptLog wherePrompt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiPromptLog whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiPromptLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AiPromptLog extends Model
{
    //use HasFactory;
    protected $table = 'ai_prompt_log';
    protected $fillable = ['uid', 'prompt'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'uid');
    }
}
