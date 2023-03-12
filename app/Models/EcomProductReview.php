<?php

namespace App\Models;

use App\Models\Traits\HasDates;
use Illuminate\Database\Eloquent\Model;




/**
 * App\Models\EcomProductReview
 *
 * @property int $id 主键
 * @property int $uid 用户
 * @property int $itemid 关联商品
 * @property int $order_id 关联订单
 * @property string|null $message 评论内容
 * @property int|null $item_star 商品评分
 * @property int $service_star 服务评分
 * @property int $wuliu_star 物流评分
 * @property int $anony 匿名评论
 * @property \Illuminate\Support\Carbon|null $created_at 评论时间
 * @property \Illuminate\Support\Carbon|null $updated_at 更新时间
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EcomProductReviewImage[] $images
 * @property-read int|null $images_count
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\EcomProductItem|null $product
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|EcomProductReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EcomProductReview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EcomProductReview query()
 * @method static \Illuminate\Database\Eloquent\Builder|EcomProductReview whereAnony($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EcomProductReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EcomProductReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EcomProductReview whereItemStar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EcomProductReview whereItemid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EcomProductReview whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EcomProductReview whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EcomProductReview whereServiceStar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EcomProductReview whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EcomProductReview whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EcomProductReview whereWuliuStar($value)
 * @mixin \Eloquent
 */
class EcomProductReview extends Model
{
    use HasDates;

    protected $table = 'ecom_product_review';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uid', 'itemid', 'order_id', 'message', 'item_star', 'service_star', 'wuliu_star', 'anony'
    ];

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::deleting(function (EcomProductReview $review) {
            $review->images()->delete();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'uid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(EcomProductItem::class, 'itemid', 'itemid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(EcomProductReviewImage::class, 'review_id', 'id');
    }
}
