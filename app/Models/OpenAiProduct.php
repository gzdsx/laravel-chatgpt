<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OpenAiProduct
 *
 * @property int $id 主键
 * @property string|null $title 名称
 * @property int $type 类型
 * @property string $price 价格
 * @property string $original_price 原价
 * @property string $iap_price 内购价
 * @property string|null $iap_id 内购ID
 * @property string $day_fee 平均每天费用
 * @property int $enable 是否可用
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiProduct whereDayFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiProduct whereEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiProduct whereIapId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiProduct whereIapPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiProduct whereOriginalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiProduct wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiProduct whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OpenAiProduct whereType($value)
 * @mixin \Eloquent
 */
class OpenAiProduct extends Model
{
    const TYPE_A_DAY = 1;
    const TYPE_A_WEEK = 2;
    const TYPE_A_MONTH = 3;
    const TYPE_A_QUARTER = 4;
    const TYPE_A_YEAR = 5;
    const TYPE_A_LIFE = 6;

    protected $table = 'openai_product';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title', 'type', 'price', 'original_price',
        'enable', 'iap_price', 'iap_id', 'day_fee'
    ];

    public $timestamps = false;
}
