<?php

namespace App\Models;

use App\Models\Traits\HasDates;
use App\Models\Traits\UserHasChildren;
use App\Models\Traits\UserHasPosts;
use EloquentFilter\Filterable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;


/**
 * App\Models\User
 *
 * @property int $uid 主键
 * @property int $gid 管理权限
 * @property string|null $nickname 昵称
 * @property string|null $phone 手机号
 * @property string|null $email 邮箱
 * @property string|null $avatar 头像
 * @property int $credits 积分
 * @property string|null $password 密码
 * @property string|null $remember_token
 * @property string|null $websocket_token websocket认证token
 * @property int $freeze 冻结
 * @property float $latitude 纬度
 * @property float $longitude 经度
 * @property int $status 状态
 * @property int $email_status 邮箱验证状态
 * @property int $name_status 实名认证状态
 * @property int $is_paid 是否付费用户
 * @property int $is_agent 是否代理商
 * @property int $member_type 会员类别
 * @property string|null $member_expires_at 会员到期时间
 * @property string|null $code_image 二维码图片
 * @property int $parent_id 上级用户
 * @property int $free_plan_times 免费计划剩余次数
 * @property \Illuminate\Support\Carbon|null $created_at 创建时间
 * @property \Illuminate\Support\Carbon|null $updated_at 更新时间
 * @property-read \App\Models\UserAccount|null $account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserAddress> $addresses
 * @property-read int|null $addresses_count
 * @property-read \App\Models\AdminUser|null $admin
 * @property-read \App\Models\UserCertify|null $certify
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Client> $clients
 * @property-read int|null $clients_count
 * @property-read \App\Models\UserCode|null $code
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PostItem> $collectedPosts
 * @property-read int|null $collected_posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserCommission> $commissions
 * @property-read int|null $commissions_count
 * @property-read User|null $commonlyTransferUsers
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserConnect> $connects
 * @property-read int|null $connects_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserEducation> $educations
 * @property-read int|null $educations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $fans
 * @property-read int|null $fans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserField> $fields
 * @property-read int|null $fields_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $follows
 * @property-read int|null $follows_count
 * @property-read array|string|null $status_des
 * @property-read \App\Models\UserGroup|null $group
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserLog> $logs
 * @property-read int|null $logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CommonMaterial> $materials
 * @property-read int|null $materials_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \App\Models\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read User|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserPosition> $positions
 * @property-read int|null $positions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PostItem> $posts
 * @property-read int|null $posts_count
 * @property-read \App\Models\UserProfile|null $profile
 * @property-read \App\Models\UserStats|null $stats
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $subUsers
 * @property-read int|null $sub_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Token> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserTransaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserTransferCommonly> $transferCommonly
 * @property-read int|null $transfer_commonly_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserWithdrawal> $withdrawals
 * @property-read int|null $withdrawals_count
 * @method static \Illuminate\Database\Eloquent\Builder|User filter(array $input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User simplePaginateFilter(?int $perPage = null, ?int $columns = [], ?int $pageName = 'page', ?int $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBeginsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCodeImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCredits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEndsWith(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFreeze($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLike(string $column, string $value, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMemberExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMemberType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNameStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereWebsocketToken($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable, Filterable, HasApiTokens, HasDates;
    use UserHasPosts, UserHasChildren;

    protected $table = 'user';
    protected $primaryKey = 'uid';
    protected $fillable = [
        'uid', 'gid', 'nickname', 'phone', 'email', 'password', 'remember_token',
        'avatar', 'status', 'email_status', 'name_status', 'freeze', 'credits',
        'is_paid', 'member_type', 'member_expires_at', 'websocket_token',
        'code_image', 'is_agent', 'parent_id', 'free_plan_times'
    ];
    protected $hidden = ['password', 'remember_token', 'websocket_token'];
    protected $appends = [
        'status_des'
    ];
    protected $dates = ['member_expires_at'];
    protected $casts = [
        'member_expires_at' => 'datetime:Y-m-d'
    ];

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
        static::creating(function (User $user) {
            if (!$user->gid) {
                if ($group = UserGroup::orderBy('credits')->first()) {
                    $user->gid = $group->gid;
                }
            }
        });

        static::created(function (User $user) {
            $user->profile()->create();
            $user->certify()->create();
            $user->account()->create();
            $user->stats()->create();
        });

        static::deleting(function (User $user) {
            $user->profile()->delete();
            $user->stats()->delete();
            $user->certify()->delete();
            $user->account()->delete();
            $user->fields()->delete();
            $user->connects()->delete();
            $user->logs()->delete();
            $user->addresses()->delete();
            $user->notifications()->delete();
            $user->transactions()->delete();
        });
    }

    /**
     * @return string|null
     */
    public function getAvatarAttribute($value)
    {
        return $value ? image_url($value) : asset('images/common/avatar_default.png');
    }

    /**
     * @return array|string|null
     */
    public function getStatusDesAttribute()
    {
        return is_null($this->status) ? null : trans('user.status_options.' . $this->status);
    }

    /**
     * Find the user instance for the given username.
     * @param $username
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function findForPassport($username)
    {
        $user = $this->where('phone', $username)->first();
        if ($user) {
            return $user;
        }

        $user = $this->where('email', $username)->first();
        if ($user) {
            return $user;
        }

        return $this->where('username', $username)->first();
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        if ($this->isFounder()) {
            return true;
        }
        return !is_null($this->admin);
    }

    public function isFounder()
    {
        return $this->uid == 1000000;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function admin()
    {
        return $this->hasOne(AdminUser::class, 'uid', 'uid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function connects()
    {
        return $this->hasMany(UserConnect::class, 'uid', 'uid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fields()
    {
        return $this->hasMany(UserField::class, 'uid', 'uid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(UserGroup::class, 'gid', 'gid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'uid', 'uid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function certify()
    {
        return $this->hasOne(UserCertify::class, 'uid', 'uid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany(UserLog::class, 'uid', 'uid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function stats()
    {
        return $this->hasOne(UserStats::class, 'uid', 'uid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|UserAccount
     */
    public function account()
    {
        return $this->hasOne(UserAccount::class, 'uid', 'uid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function materials()
    {
        return $this->hasMany(CommonMaterial::class, 'uid', 'uid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|UserAddress
     */
    public function addresses()
    {
        return $this->hasMany(UserAddress::class, 'uid', 'uid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function fans()
    {
        return $this->hasManyThrough(
            User::class,
            UserFans::class,
            'uid',
            'uid',
            'uid',
            'fans_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function follows()
    {
        return $this->hasManyThrough(
            User::class,
            UserFans::class,
            'fans_id',
            'uid',
            'uid',
            'uid'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|UserTransaction
     */
    public function transactions()
    {
        return $this->hasMany(UserTransaction::class, 'uid', 'uid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transferCommonly()
    {
        return $this->hasMany(UserTransferCommonly::class, 'uid', 'uid');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough|User
     */
    public function commonlyTransferUsers()
    {
        return $this->hasOneThrough(
            User::class,
            UserTransferCommonly::class,
            'uid',
            'uid',
            'uid',
            'payee_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|UserEducation
     */
    public function educations()
    {
        return $this->hasMany(UserEducation::class, 'uid', 'uid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|UserPosition
     */
    public function positions()
    {
        return $this->hasMany(UserPosition::class, 'uid', 'uid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|UserCommission
     */
    public function commissions()
    {
        return $this->hasMany(UserCommission::class, 'uid', 'uid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|UserWithdrawal
     */
    public function withdrawals()
    {
        return $this->hasMany(UserWithdrawal::class, 'uid', 'uid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|UserCode
     */
    public function code()
    {
        return $this->hasOne(UserCode::class, 'uid', 'uid');
    }

    public function refreshWebsocketToken()
    {
        $this->websocket_token = Str::random(32);
        $this->save();
    }
}
