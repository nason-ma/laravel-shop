<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

/**
 * App\Models\CouponCode
 *
 * @method static \Illuminate\Database\Eloquent\Builder|CouponCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CouponCode query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $type
 * @property string $value
 * @property int $total
 * @property int $used
 * @property string $min_amount
 * @property \Illuminate\Support\Carbon|null $not_before
 * @property \Illuminate\Support\Carbon|null $not_after
 * @property bool $enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CouponCode whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponCode whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponCode whereMinAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponCode whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponCode whereNotAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponCode whereNotBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponCode whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponCode whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponCode whereUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CouponCode whereValue($value)
 */
class CouponCode extends BaseModel
{
    use HasFactory;

    // 用常量的方式定义支持的优惠券类型
    const TYPE_FIXED = 'fixed';
    const TYPE_PERCENT = 'percent';

    public static $typeMap = [
        self::TYPE_FIXED => '固定金额',
        self::TYPE_PERCENT => '比例',
    ];

    protected $fillable = [
        'name',
        'code',
        'type',
        'value',
        'total',
        'used',
        'min_amount',
        'not_before',
        'not_after',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    // 指明这两个字段是日期类型
    protected $dates = ['not_before', 'not_after'];

    protected $appends = ['description'];

    public function getDescriptionAttribute()
    {
        $str = '';

        if ($this->min_amount > 0) {
            $str = '满 ' . str_replace('.00', '', $this->min_amount);
        }
        if ($this->type === self::TYPE_PERCENT) {
            return $str . ' 优惠 ' . str_replace('.00', '', $this->value) . '%';
        }

        return $str . ' 减 ' . str_replace('.00', '', $this->value);
    }

    public static function findAvailableCode($length = 16)
    {
        do {
            // 生成一个指定长度的随机字符串，并转成大写
            $code = strtoupper(Str::random($length));
            // 如果生成的码已存在就继续循环
        } while (self::query()->where('code', $code)->exists());

        return $code;
    }
}
