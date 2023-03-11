<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Kernel{
/**
 * App\Kernel\ShardModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ShardModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShardModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShardModel query()
 * @mixin \Eloquent
 */
	class ShardModel extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Area
 *
 * @property int $id
 * @property int $level 层级
 * @property string $parent_code
 * @property string $area_code
 * @property string $zip_code 邮编
 * @property string $city_code 区号
 * @property string $name 简称
 * @property string $short_name 简称
 * @property string $merger_name
 * @property string $pinyin
 * @property string $lng 经度
 * @property string $lat 纬度
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Area|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder|Area newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Area newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Area query()
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereAreaCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereCityCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereMergerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereParentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area wherePinyin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereZipCode($value)
 */
	class Area extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MiniScene
 *
 * @property int $id
 * @property int|null $wx_uid
 * @property string $code
 * @property string $path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MiniScene newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MiniScene newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MiniScene query()
 * @method static \Illuminate\Database\Eloquent\Builder|MiniScene whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiniScene whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiniScene whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiniScene wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiniScene whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MiniScene whereWxUid($value)
 */
	class MiniScene extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Option
 *
 * @property int $id
 * @property string $key
 * @property array|null $config
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Option newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Option newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Option query()
 * @method static \Illuminate\Database\Eloquent\Builder|Option whereConfig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Option whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Option whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Option whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Option whereUpdatedAt($value)
 */
	class Option extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Permission
 *
 * @property int $id
 * @property int $parent_id 上级id
 * @property string|null $name 菜单名称
 * @property string|null $path 访问路径
 * @property string $title 页面标题
 * @property string|null $component 组件路径
 * @property int $hidden 是否隐藏路由
 * @property int $no_cache 是否弃用缓存
 * @property int $affix 固定显示在tab中
 * @property int $no_tags_view 是否在tab中隐藏
 * @property int $always_show 是否始终显示
 * @property string $frame_src iframe网址
 * @property string $active_menu 强制高度哪个path的菜单
 * @property int|null $rank 排序
 * @property string|null $icon 图标
 * @property int $type 类型 0菜单 1按钮
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereActiveMenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereAffix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereAlwaysShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereFrameSrc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereNoCache($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereNoTagsView($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 */
	class Permission extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PersonalAccessToken
 *
 * @property int $id
 * @property string $tokenable_type
 * @property int $tokenable_id
 * @property string $name
 * @property string $token
 * @property array|null $abilities
 * @property \Illuminate\Support\Carbon|null $last_used_at
 * @property \Illuminate\Support\Carbon|null $expired_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $tokenable
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereAbilities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereLastUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereTokenableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereTokenableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PersonalAccessToken whereUpdatedAt($value)
 */
	class PersonalAccessToken extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UploadFile
 *
 * @property int $id
 * @property string $user_type
 * @property int $user_id
 * @property string $path
 * @property string|null $ext
 * @property string|null $mime
 * @property int $size
 * @property string|null $driver
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $user
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile query()
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile whereDriver($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile whereExt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile whereMime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UploadFile whereUserType($value)
 */
	class UploadFile extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $phone
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $avatar_url 头像
 * @property string|null $remember_token
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\WxUser
 *
 * @property int $id
 * @property string $open_id
 * @property string|null $union_id
 * @property string|null $name
 * @property string|null $tel
 * @property string $avatar_url
 * @property string $session_key
 * @property int $type 类型
 * @property int $gender 性别
 * @property string|null $city
 * @property string|null $province
 * @property string|null $country
 * @property int $is_admin
 * @property string|null $password
 * @property string $email
 * @property \Illuminate\Support\Fluent $settings 设置项
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereOpenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereSessionKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereTel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereUnionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WxUser whereUpdatedAt($value)
 */
	class WxUser extends \Eloquent {}
}

