<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WechatMenu
 *
 * @property int $id 主键
 * @property int $parent_id 父级ID
 * @property string|null $name 菜单名称
 * @property string|null $type 菜单类型
 * @property string|null $key key
 * @property string|null $media_id 素材ID
 * @property string|null $url 跳转链接
 * @property string|null $appid 小程序appid
 * @property string|null $pagepath 小程序页面路径
 * @property int $sort_num 排序
 * @property-read \Illuminate\Database\Eloquent\Collection|WechatMenu[] $children
 * @property-read int|null $children_count
 * @property-read array|\Illuminate\Contracts\Translation\Translator|string|null $type_des
 * @method static Builder|WechatMenu newModelQuery()
 * @method static Builder|WechatMenu newQuery()
 * @method static Builder|WechatMenu query()
 * @method static Builder|WechatMenu whereAppid($value)
 * @method static Builder|WechatMenu whereId($value)
 * @method static Builder|WechatMenu whereKey($value)
 * @method static Builder|WechatMenu whereMediaId($value)
 * @method static Builder|WechatMenu whereName($value)
 * @method static Builder|WechatMenu wherePagepath($value)
 * @method static Builder|WechatMenu whereParentId($value)
 * @method static Builder|WechatMenu whereSortNum($value)
 * @method static Builder|WechatMenu whereType($value)
 * @method static Builder|WechatMenu whereUrl($value)
 * @mixin \Eloquent
 */
class WechatMenu extends Model
{
    protected $table = 'wechat_menu';
    protected $primaryKey = 'id';
    protected $fillable = ['parent_id', 'name', 'type', 'key', 'media_id', 'url', 'appid', 'pagepath', 'sort_num'];
    protected $appends = ['type_des'];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::deleting(function (WechatMenu $wechatMenu) {
            $wechatMenu->children()->delete();
        });
        static::addGlobalScope('sort', function (Builder $builder) {
            return $builder->orderBy('sort_num')->orderBy('id');
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(WechatMenu::class, 'parent_id', 'id');
    }

    /**
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function getTypeDesAttribute()
    {
        return $this->type ? trans('wechat.menu.types.' . $this->type) : null;
    }
}
