<?php

namespace App\Models;

use App\Models\Traits\HasImageAttribute;
use App\Models\Traits\HasThumbAttribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\EcomProductImage
 *
 * @property int $id 主键
 * @property int $itemid 产品
 * @property string $thumb 小图
 * @property string $image 大图
 * @property int $sort_num 显示顺序
 * @method static Builder|EcomProductImage newModelQuery()
 * @method static Builder|EcomProductImage newQuery()
 * @method static Builder|EcomProductImage query()
 * @method static Builder|EcomProductImage whereId($value)
 * @method static Builder|EcomProductImage whereImage($value)
 * @method static Builder|EcomProductImage whereItemid($value)
 * @method static Builder|EcomProductImage whereSortNum($value)
 * @method static Builder|EcomProductImage whereThumb($value)
 * @mixin \Eloquent
 */
class EcomProductImage extends Model
{
    use HasThumbAttribute, HasImageAttribute;

    protected $table = 'ecom_product_image';
    protected $primaryKey = 'id';
    protected $fillable = ['itemid', 'thumb', 'image', 'sort_num'];
    protected $hidden = ['itemid'];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::addGlobalScope('sort', function (Builder $builder) {
            return $builder->orderBy('sort_num')->orderBy('id');
        });
    }
}