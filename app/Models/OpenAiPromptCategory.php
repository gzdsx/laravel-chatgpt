<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OpenAiPromptCategory
 *
 * @property int $cate_id
 * @property string|null $cate_name
 * @property int $sort_num
 * @property int $enable
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OpenAiPromptModel> $items
 * @property-read int|null $items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OpenAiPromptModel> $models
 * @property-read int|null $models_count
 * @method static Builder|OpenAiPromptCategory newModelQuery()
 * @method static Builder|OpenAiPromptCategory newQuery()
 * @method static Builder|OpenAiPromptCategory query()
 * @method static Builder|OpenAiPromptCategory whereCateId($value)
 * @method static Builder|OpenAiPromptCategory whereCateName($value)
 * @method static Builder|OpenAiPromptCategory whereEnable($value)
 * @method static Builder|OpenAiPromptCategory whereSortNum($value)
 * @mixin \Eloquent
 */
class OpenAiPromptCategory extends Model
{
    protected $table = 'openai_prompt_category';
    protected $primaryKey = 'cate_id';
    protected $fillable = ['cate_name', 'sort_num', 'enable'];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::addGlobalScope('sort', function (Builder $builder) {
            return $builder->orderByDesc('sort_num')->orderBy('cate_id');
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|OpenAiPromptModel
     */
    public function models()
    {
        return $this->hasMany(OpenAiPromptModel::class, 'cate_id', 'cate_id')->where('enable', 1);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|OpenAiPromptModel
     */
    public function items()
    {
        return $this->hasMany(OpenAiPromptModel::class, 'cate_id', 'cate_id')->where('enable', 1);
    }
}
