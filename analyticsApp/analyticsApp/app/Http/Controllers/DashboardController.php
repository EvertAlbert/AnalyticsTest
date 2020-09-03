<?php

namespace App\Http\Controllers;

use App\Event;
use App\Helpers\ApiHelper;
use App\Helpers\DashboardHelper;
use App\ProductView;
use App\Visitor;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function index($time = null)
    {
        /**
         * @param $time
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
         */
        if ($time === null) {
            $data =
                [
                    'totalVisitors' => Visitor::count('*'),
                    'rating' => DashboardHelper::calculateRating(),
                    'mostViewedProduct' => ProductView::getMostViewedProductName(),
                    'averageVisitTime' => Visitor::averageVisitTime(),
                    'eventAmount' => Event::count('*'),
                    'userRoute' => Visitor::routes(),
                    'productTimes' => ProductView::averageLookTime(),
                    'message' => '',
                    'time' => ''
                ];

            return view('dashboard.index')
                ->with('data', $data);
        }

        $functionMapping = [
            'today' => static function ($time) {
                $start = Carbon::today()->toDateString();
                $end = Carbon::tomorrow()->toDateString();
                $data = DashboardHelper::getData($start, $end, $time,
                    "$start");

                return view('dashboard.index')
                    ->with('data', $data);
            },
            'yesterday' => static function ($time) {
                $start = Carbon::yesterday()->toDateString();
                $end = Carbon::today()->toDateString();
                $data = DashboardHelper::getData($start, $end, $time,
                    "$start");

                return view('dashboard.index')
                    ->with('data', $data);
            },
            'week' => static function ($time) {
                $start = Carbon::today()->startOfWeek()->subWeek()->toDateString();
                $end = Carbon::today()->startOfWeek()->toDateString();
                $data = DashboardHelper::getData($start, $end, $time,
                    "$start to $end");

                return view('dashboard.index')
                    ->with('data', $data);
            },
            'month' => static function ($time) {
                $start = Carbon::today()->startOfMonth()->subMonth()->toDateString();
                $end = Carbon::today()->startOfMonth()->toDateString();
                $data = DashboardHelper::getData($start, $end, $time,
                    "$start to $end");

                return view('dashboard.index')
                    ->with('data', $data);
            },
            'year' => static function ($time) {
                /** @var Carbon $start */
                $start = Carbon::today()->startOfYear()->subYear()->toDateString();
                $end = Carbon::today()->startOfYear()->subYear()->toDateString();
                $data = DashboardHelper::getData($start, $end, $time,
                    Carbon::today()->startOfYear()->subYear()->year);

                return view('dashboard.index')
                    ->with('data', $data);
            }
        ];

        return self::runOverFunctionMappingArray($functionMapping, $time);
    }

    /**
     * @param array $functionMapping
     * @param $time
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|null
     */
    public static function runOverFunctionMappingArray(array $functionMapping, $time)
    {
        foreach ($functionMapping as $k => $v) {
            if ($k === $time) {
                return $v($time);
            }
        }

        abort(404);
        return null;
    }
}

