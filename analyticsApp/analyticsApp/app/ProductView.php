<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\ProductView
 *
 * @property int $id
 * @property string $visitor_id
 * @property int $product_id
 * @property string|null $look_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductView newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductView query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductView whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductView whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductView whereLookTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductView whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductView whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ProductView whereVisitorId($value)
 * @mixin \Eloquent
 * @property-read \App\Event $event
 * @property-read \App\Product $product
 * @property-read \App\Visitor $visitor
 */
class ProductView extends Model
{
    protected $fillable = ['visitor_id','product_id'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return mixed
     */
    public static function averageLookTime()
    {
        $productAvgTimesNew = self::select('product_id',DB::raw('sec_to_time(avg(product_views.look_time)) as average_look_time'))
            ->groupBy('product_id');

        return Product::joinSub($productAvgTimesNew,'average_times',function ($join){
                $join->on('products.id','=','average_times.product_id');
            })
            ->select('name','average_look_time')
            ->get();
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public static function averageLookTimeBetween($startDate, $endDate)
    {
        $productAvgTimesNew = self::select('product_id',DB::raw('sec_to_time(avg(product_views.look_time)) as average_look_time'))
            ->whereBetween('created_at',[$startDate,$endDate])
            ->groupBy('product_id');

        return Product::joinSub($productAvgTimesNew,'average_times',function ($join){
            $join->on('products.id','=','average_times.product_id');
        })
            ->select('name','average_look_time')
            ->get();
    }

    /**
     * @return mixed
     */
    public static function getMostViewedProductName()
    {
        $mostViewedProductId = self::selectRaw('product_id, count(*) as view_amount')
            ->groupBy('product_id')
            ->orderBy('view_amount', 'desc')
            ->value('product_id'); //max amount doesn't always return the same when 2 items have same amount

        return Product::where('id', '=', $mostViewedProductId)->value('name');
    }

    public static function getMostViewedProductNameBetween($startDate,$endDate)
    {
        $mostViewedProductId = self::selectRaw('product_id, count(*) as view_amount')
            ->whereBetween('created_at',[$startDate,$endDate])
            ->groupBy('product_id')
            ->orderBy('view_amount', 'desc')
            ->value('product_id'); //max amount doesn't always return the same when 2 items have same amount
        return Product::where('id', '=', $mostViewedProductId)->value('name');
    }

}
