<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AiPaymentPlan
 *
 * @property int $id
 * @property string|null $title
 * @property int $type
 * @property int|null $value
 * @property string $price
 * @property int $enable
 * @property int $iap_enable
 * @property string $iap_price
 * @property string|null $iap_id
 * @property-read string $desc
 * @property-read mixed $type_des
 * @method static \Illuminate\Database\Eloquent\Builder|AiPaymentPlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AiPaymentPlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AiPaymentPlan query()
 * @method static \Illuminate\Database\Eloquent\Builder|AiPaymentPlan whereEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiPaymentPlan whereIapEnable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiPaymentPlan whereIapId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiPaymentPlan whereIapPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiPaymentPlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiPaymentPlan wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiPaymentPlan whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiPaymentPlan whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AiPaymentPlan whereValue($value)
 * @mixin \Eloquent
 */
class AiPaymentPlan extends Model
{
    //use HasFactory;

    const TYPE_DAYS = 1;
    const TYPE_POINTS = 2;
    protected $table = 'ai_payment_plan';
    protected $primaryKey = 'id';
    protected $fillable = ['type', 'value', 'price', 'enable', 'iap_enable', 'iap_price', 'iap_id'];
    protected $appends = ['desc', 'type_des'];

    public $timestamps = false;

    /**
     * @return string
     */
    public function getDescAttribute()
    {
        if ($this->type == 1) {
            return $this->value . '天计划';
        } else {
            return $this->value . '点AI豆';
        }
    }

    public function getTypeDesAttribute()
    {
        return $this->type == 1 ? '天计划' : '点计划';
    }
}
