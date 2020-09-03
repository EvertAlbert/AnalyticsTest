<?php
/**
 * Created by PhpStorm.
 * User: evert
 * Date: 24-Feb-20
 * Time: 15:04
 */

namespace App\Helpers;

use App\Action;
use App\Event;
use App\Language;
use App\Page;
use App\ProductView;
use App\Visitor;
use Carbon\Carbon;

abstract class WebSocketHelper
{
    /**
     * @param array $data
     */
    public static function connected(array $data): void
    {
        $visitorId = $data['visitorId'];
        $page = $data['page'];

        self::updatePageCount($page);

        if (Visitor::where('id', '=', $visitorId)->value('id') === null) {
            self::createVisitor($visitorId);
        }
    } //update pageCount and add/update user info

    /**
     * @param string $pageUrl
     */
    private static function updatePageCount(string $pageUrl): void
    {
        /** @var Page $page */
        $page = Page::where('url', $pageUrl)->first();

        if ($page !== null) {
            $page->visit_count++;
            $page->save();
        } else {
            $page = new Page([
                'url' => $pageUrl,
                'visit_count' => 1
            ]);
            $page->save();
        }
    } //this page registers all links in the site and counts how many times they are visited

    /**
     * @param $data
     */
    public static function disconnected($data): void
    {
        $visitorId = $data['visitorId'];

        /** @var Visitor $visitor */
        $visitor = Visitor::where('id', '=', $visitorId)->first();
        $visitor->departure_time = Carbon::now()->toDateTimeString();
        $visitor->save();
    } //update departure_time for visitor

    public static function setLanguage($data)
    {
        $visitorId = $data['visitorId'];
        $visitorLanguage = $data['message'];

        if (Language::where('name_short', '=', $visitorLanguage)->orWhere('name_full', '=', $visitorLanguage)->value('id') === null)
        {
            return null;
        }

        $languageId = Language::where('name_short', '=', $visitorLanguage)->orWhere('name_full', '=', $visitorLanguage)->value('id');
        $visitor = Visitor::where('id', '=', $visitorId)->first();
        $visitor->language_id = $languageId;
        $visitor->save();
    }

    /**
     * @param $data
     */
    public static function setAge($data): void
    {
        $visitorId = $data['visitorId'];
        $visitorAge = null;

        if ((int)$data['message'] > 0 && (int)$data['message'] < 150)
        {
            $visitorAge = $data['message'];
        }

        $visitor = Visitor::where('id', $visitorId)->first();
        $visitor->age = $visitorAge;
        $visitor->save();
    }

    /**
     * @param $data
     */
    public static function productClicked($data): void
    {
        $visitorId = $data['visitorId'];
        $productId = $data['message'];

        $productView = ProductView::create([
            'visitor_id' => $visitorId,
            'product_id' => $productId
        ]);
        $productView->save();

        self::registerEvent($data, $productView->id);
    }

    public static function setRating($data)
    {
        $visitorId = $data['visitorId'];
        $rating = $data['message'];

        if ((int)$data['message'] < 0 || (int)$data['message'] > 2){
            return null;
        }

        $visitor = Visitor::where('id', $visitorId)->first();
        $visitor->rating = $rating;
        $visitor->save();
    }

    public static function setProductViewTime($data)
    {
        //update the time of the last product_view where the visitor_id is the current visitor and the item looked at is the current item the visitor is looking at
        $visitorId = $data['visitorId'];
        $message = $data['message'] ?? '';

        $infoArray = explode('_', $message);

        /** @var ProductView $productView */
        $productView = ProductView::where([
            ['visitor_id', '=', $visitorId],
            ['product_id', '=', (int)$infoArray[0]] //the first item in the array should be the product id
        ])->orderBy('created_at', 'desc')->first();

        if (!isset($infoArray[1]))
        {
            return null;
        }
        
        $productView->look_time = (int)$infoArray[1]; //the second item in the array should be the time spent on the page
        $productView->save();
    }

    /**
     * @param $data
     * @param null $productViewId
     */
    public static function registerEvent($data, $productViewId = null): void
    {
        $visitor_id = $data['visitorId'];
        $action_id = Action::where('name', '=', $data['action'])->value('id');
        $page_id = Page::where('url', '=', $data['page'])->value('id');
        $message = $data['message'];
        $product_view_id = $productViewId; //this is null unless the product_view_id was given with the registerEvent();

        $event = new Event([
            'visitor_id' => $visitor_id,
            'action_id' => $action_id,
            'page_id' => $page_id,
            'message' => $message,
            'product_view_id' => $product_view_id
        ]);
        $event->save();
    }

    /**
     * @param $visitorId
     */
    private static function createVisitor($visitorId): void
    {
        $arrivalTime = Carbon::now()->toDateTimeString();
        $visitor = Visitor::create([
            'id' => $visitorId,
            'arrival_time' => $arrivalTime
        ]);
        $visitor->save();
    }
}
