<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Event
 *
 * @property int $id
 * @property string $visitor_id
 * @property int $action_id
 * @property int $page_id
 * @property string|null $message
 * @property int|null $product_view_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereActionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereProductViewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereVisitorId($value)
 * @mixin \Eloquent
 * @property-read \App\Action $action
 * @property-read \App\Page $page
 * @property-read \App\ProductView|null $productView
 * @property-read \App\Visitor $visitor
 */
class Event extends Model
{
    protected $fillable = ['visitor_id','action_id','page_id','message','product_view_id'];

    public static function eventsBetween($startDate, $endDate)
    {
        $eventCount = Event::whereBetween('created_at', [$startDate, $endDate])->count('*');
        if($eventCount !== 0){
            return $eventCount;
        }

        return null;
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function action()
    {
        return $this->belongsTo(Action::class);
    }

    public function productView()
    {
        return $this->belongsTo(ProductView::class);
    }

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
