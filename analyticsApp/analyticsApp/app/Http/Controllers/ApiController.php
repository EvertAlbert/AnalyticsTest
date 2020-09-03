<?php

namespace App\Http\Controllers;

use App\Event;
use App\Helpers\ApiHelper;
use App\Language;
use App\Page;
use App\Product;
use App\Visitor;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    private $startDate;
    private $endDate;

    public function setTime($time): void
    {
        $this->startDate = ApiHelper::dataSelector($time)[0];
        $this->endDate = ApiHelper::dataSelector($time)[1];
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLangData($time = null)
    {
        $this->setTime($time);

        $languages = Language::all();

        $countArray = [];

        if ($time !== null) {
            foreach ($languages as $language) {
                $countArray[] = $language->visitors->whereBetween('arrival_time',
                    [$this->startDate, $this->endDate])->count();
            }
        } else {
            foreach ($languages as $language) {
                $countArray[] = $language->visitors->count();
            }
        }

        $resultArray = [$languages->pluck('name_full'), $countArray];

        return response()->json($resultArray, 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAgeData($time = null)
    {
        $this->setTime($time);
        if ($time !== null) {
            $ages = Visitor::whereBetween('arrival_time', [$this->startDate, $this->endDate])->pluck('age');
        } else {
            $ages = Visitor::pluck('age');
        }
        $categorisedAges = ApiHelper::categoriseAges($ages);

        $categoryArray = ['-12', '13-18', '19-25', '26-60', '61+'];

        $resultArray = [$categoryArray, $categorisedAges];
        return response()->json($resultArray, 200);
        //this should return [x, x, x, x, x] where the x's represent the amount of people with an aged categorized like: [-12, 12-18, 19-25, 26-60, 61+]
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPageVisits($time = null)
    {
        $this->setTime($time);
        $allPages = Page::all();

        $pageCount = [];
            if ($time !== null) {
                $pages = Page::with(['events.action'])->whereHas('events', function ($query) {
                    $query->whereBetween('created_at', [$this->startDate, $this->endDate])
                        ->whereHas('action', function ($query) {
                            $query->where('name', 'connect');
                        });
                })->get();

                foreach ($pages as $page) {
                    $pageCount[] = $page->events
                        ->count();
                }
            } else {
                $pageCount = $allPages->pluck('visit_count');
            }

            $resultArray = [$allPages->pluck('url'), $pageCount];

            return response()->json($resultArray, 200);
            //this should return [[URL1, URL2, ...],[visitCount URL1, visitCount URL2, ...]]
            //TODO remove the product detail pages from this list -> maybe count with /products/
        }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPageEventsPerHour($time = null)
    {
        $this->setTime($time);

        $timeLabels = ApiHelper::generateTimeLabels();
        $eventsArray = [];
        for ($i = 0; $i < 24; $i++) {
            $eventsArray[] = 0;
        }

        if ($time !== null) {
            $eventsPerHour = ApiHelper::getEventsPerHourBetween($this->startDate,$this->endDate);
        } else {
            $eventsPerHour = ApiHelper::getAllEventsPerHour();
        }

        foreach ($eventsPerHour as $event) {
            $eventsArray[(int)$event->hour] = (int)$event->amount;
        }

        $resultArray = [$timeLabels, $eventsArray];
        return response()->json($resultArray, 200);
    }

    public function getProductViews($time = null)
    {
        $this->setTime($time);
        $products = Product::all();
        $countsArray = [];

        if ($time !== null) {
            foreach ($products as $product) {
                $countsArray[] = $product->productViews
                    ->whereBetween('created_at', [$this->startDate, $this->endDate])
                    ->count();
            }
        } else {
            foreach ($products as $product) {
                $countsArray[] = $product->productViews->count();
            }
        }

        $resultArray = [$products->pluck('name'), $countsArray];

        return response()->json($resultArray, 200);
    }
}
