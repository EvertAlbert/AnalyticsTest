<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Visitor
 *
 * @property string $id
 * @property int|null $language_id
 * @property int|null $age
 * @property int|null $rating
 * @property string $arrival_time
 * @property string|null $departure_time
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Visitor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Visitor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Visitor query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Visitor whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Visitor whereArrivalTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Visitor whereDepartureTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Visitor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Visitor whereLanguageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Visitor whereRating($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Event[] $events
 * @property-read int|null $events_count
 * @property-read \App\Language|null $language
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ProductView[] $productViews
 * @property-read int|null $product_views_count
 */
class Visitor extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'language_id', 'age', 'rating', 'arrival_time', 'departure_time'];
    public $timestamps = false;

    public function productViews()
    {
        return $this->hasMany(ProductView::class, 'product_view_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public static function routes()
    {
        /** @var Visitor[] $visitors */
        $visitors = Visitor::with(['events.action', 'events.page'])->whereHas('events', function ($query) {
            $query->whereHas('action', function ($query) {
                $query->where('name', 'connect');
            });
        })->get();

        return self::generateRouteArrayForVisitors($visitors);
    }

    public static function routesBetween($startDate, $endDate)
    {
        /** @var Visitor[] $visitors */
        $visitors = self::with(['events.action', 'events.page'])
            ->whereBetween('arrival_time', [$startDate, $endDate])
            ->whereHas('events', function ($query) {
            $query->whereHas('action', function ($query) {
                $query->where('name', 'connect');
            });
        })->get();

        return self::generateRouteArrayForVisitors($visitors);

    }

    public static function generateRouteArrayForVisitors($visitors = null)
    {
        foreach ($visitors as $visitor) {
            $arrayOfUrls = [];

            $events = $visitor
                ->events
                ->where('action_id', '=', '1');

            foreach ($events as $event) {
                $arrayOfUrls[] = $event->page->url;
            }

            $resultArray[] = ['user' => $visitor->id, 'route' => $arrayOfUrls];;
        }
        return $resultArray ?? $resultArray = null;
    }

    /**
     * @return mixed
     */
    public static function averageVisitTime()
    {
        return self::selectRaw('sec_to_time(avg(time_to_sec(timediff(departure_time, arrival_time)))) as averageTime')
            ->value('averageTime');
    }

    public static function averageVisitTimeBetween($startDate,$endDate)
    {
        return self::selectRaw('sec_to_time(avg(time_to_sec(timediff(departure_time, arrival_time)))) as averageTime')
            ->whereBetween('arrival_time',[$startDate,$endDate])
            ->value('averageTime'); //TODO test if this works
    }
}
