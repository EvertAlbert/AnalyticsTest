<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Action
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereName($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Event[] $events
 * @property-read int|null $events_count
 */
class Action extends Model
{
    public $timestamps = false;

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
