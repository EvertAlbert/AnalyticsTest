<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Language
 *
 * @property int $id
 * @property string $name_short
 * @property string $name_full
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Language newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Language newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Language query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Language whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Language whereNameFull($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Language whereNameShort($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Visitor[] $visitors
 * @property-read int|null $visitors_count
 */
class Language extends Model
{
    public $timestamps = false;

    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }
}
