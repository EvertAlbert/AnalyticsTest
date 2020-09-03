<?php
/**
 * Created by PhpStorm.
 * User: evert
 * Date: 25-Feb-20
 * Time: 15:58
 */

namespace App\Helpers;

use App\Action;
use App\Event;
use App\Product;
use App\ProductView;
use App\Visitor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

abstract class ApiHelper
{

    /**
     * @param $ages
     * @return array
     */
    public static function categoriseAges($ages): array
    {
        //[-12, 12-18, 19-25, 26-60, 61+]
        $ageCountPerCategory = [0, 0, 0, 0, 0];

        foreach ($ages as $age) {
            if ($age > 0 && $age <= 12) {
                $ageCountPerCategory[0]++;
            }
            if ($age > 12 && $age <= 18) {
                $ageCountPerCategory[1]++;
            }
            if ($age > 18 && $age <= 25) {
                $ageCountPerCategory[2]++;
            }
            if ($age > 25 && $age <= 60) {
                $ageCountPerCategory[3]++;
            }
            if ($age > 60 && $age < 150) {
                $ageCountPerCategory[4]++;
            }
        }

        return $ageCountPerCategory;
    }

    public static function generateTimeLabels()
    {
        $resultArray = [];
        for ($i = 0; $i <= 23; $i++) {
            if ($i < 10) {
                $resultArray[] = '0' . $i . ':00';
            } else {
                $resultArray[] = $i . ':00';
            }
        }
        return $resultArray;
    }

    public static function dataSelector($time)
    {
        if ($time === null) {
            $start = null;
            $end = Carbon::tomorrow()->toDateString();
            return [$start, $end];
        }

        $functionMapping = [
            'today' => static function () {
                $start = Carbon::today()->toDateString();
                $end = Carbon::tomorrow()->toDateString();
                return [$start, $end];
            },
            'yesterday' => static function () {
                $start = Carbon::yesterday()->toDateString();
                $end = Carbon::yesterday()->toDateString();
                return [$start, $end];
            },
            'week' => static function () {
                $start = Carbon::today()->startOfWeek()->subWeek()->toDateString();
                $end = Carbon::today()->startOfWeek()->toDateString();
                return [$start, $end];
            },
            'month' => static function () {
                $start = Carbon::today()->startOfMonth()->subMonth()->toDateString();
                $end = Carbon::today()->startOfMonth()->toDateString();
                return [$start, $end];
            },
            'year' => static function () {
                $start = Carbon::today()->startOfYear()->subYear()->toDateString();
                $end = Carbon::today()->startOfYear()->subYear()->toDateString();
                return [$start, $end];
            }
        ];

        return self::runOverFunctionMappingArray($functionMapping, $time);
    }

    private static function runOverFunctionMappingArray(array $functionMapping, $time)
    {
        foreach ($functionMapping as $k => $v) {
            if ($k === $time) {
                return $v();
            }
        }

        abort(404, 'this timeperiod could not be found');
        return null;
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public static function getEventsPerHourBetween($startDate, $endDate)
    {
        /** @var Event[] $events */
        $events = Event::whereBetween('created_at', [$startDate, $endDate]);

        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return $events
                ->selectRaw('strftime(\'%H\',created_at) as hour, count(*) as amount')
                ->groupBy(DB::raw('strftime(\'%H\',created_at)'))
                ->get();
        }

        return $events
            ->selectRaw('hour(created_at) as hour, count(*) as amount')
            ->groupBy(DB::raw('hour(created_at)'))
            ->get();
    }

    /**
     * @return mixed
     */
    public static function getAllEventsPerHour()
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return Event::selectRaw('strftime(\'%H\',created_at) as hour, count(*) as amount')
                ->groupBy(DB::raw('strftime(\'%H\',created_at)'))
                ->get();
        }

        return Event::selectRaw('hour(created_at) as hour, count(*) as amount')
            ->groupBy(DB::raw('hour(created_at)'))
            ->get();
    }
}
