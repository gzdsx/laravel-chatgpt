<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AiQuickly
 *
 * @property int $id
 * @property int|null $cate_id
 * @property string|null $title
 * @property string|null $icon
 * @property string|null $api
 * @property string|null $route
 * @property string|null $tips
 * @property string|null $prompt
 * @property string|null $template
 * @property string|null $options
 * @property int $enable
 * @property-read \App\Models\AiQuicklyCategory|null $category
 * @method static \Illuminate\Database\Eloquent\Builder|AiQuickly newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AiQuickly newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AiQuickly query()
 * @method static \Illuminate\Database\Eloquent\Builder|AiQuickly whereApi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiQuickly whereCateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiQuickly whereEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiQuickly whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiQuickly whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiQuickly whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiQuickly wherePrompt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiQuickly whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiQuickly whereTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiQuickly whereTips($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiQuickly whereTitle($value)
 * @mixin \Eloquent
 */
class AiQuickly extends Model
{
    //use HasFactory;

    protected $table = 'ai_quickly';
    protected $fillable = ['cate_id', 'title', 'icon', 'api', 'route', 'tips', 'prompt', 'template', 'options'];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(AiQuicklyCategory::class, 'cate_id', 'cate_id');
    }

    public function getIconAttribute($value)
    {
        return material_url($value);
    }
}
