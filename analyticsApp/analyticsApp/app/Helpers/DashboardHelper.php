<?php


namespace App\Helpers;


use App\Event;
use App\ProductView;
use App\Visitor;
use Carbon\Carbon;

abstract class DashboardHelper
{
    /**
     * @param string $startDate
     * @param string $endDate
     * @param string $time
     * @param string $message
     * @return array
     */
    public static function getData(string $startDate, string $endDate, string $time, string $message): array
    {
        return [
            'totalVisitors' => self::countVisitors($startDate, $endDate),
            'rating' => self::calculateRating($startDate, $endDate),
            'mostViewedProduct' => self::searchMostViewedProductName($startDate, $endDate),
            'averageVisitTime' => self::calculateAverageVisitTime($startDate, $endDate),
            'eventAmount' => self::countTotalAmountOfEvents($startDate, $endDate),
            'userRoute' => self::findUserRoutes($startDate, $endDate),
            'productTimes' => self::calculateAverageViewTimePerProduct($startDate, $endDate),
            'message' => $message,
            'time' => $time
        ];
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    private static function countVisitors($startDate, $endDate)
    {
        if(sizeof(Visitor::whereBetween('arrival_time',[$startDate, $endDate])->select('*')->get()) === 0){
            return null;
        }

        return Visitor::whereBetween('arrival_time', [$startDate, $endDate])->count('*');
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return float|int
     */
    public static function calculateRating($startDate = null, $endDate = null)
    {
        if ($startDate === null && $endDate === null){
            $endDate = Carbon::tomorrow()->toDateString();
            $startDate = Carbon::today()->subCentury()->toDateString();
        }

        $allRatings[] = Visitor::whereBetween('arrival_time',[$startDate, $endDate])->select('rating')->pluck('rating');

        $isEmpty = true;
        foreach ($allRatings[0] as $rating=>$value){
            if ($value !== null){
                $isEmpty = false;
            }
        }

        if ($isEmpty){
            return null;
        }

        return (Visitor::whereBetween('arrival_time', [$startDate, $endDate])->avg('rating'))/ 2 * 100;
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    private static function searchMostViewedProductName($startDate, $endDate)
    {
        return ProductView::getMostViewedProductNameBetween($startDate, $endDate);
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    private static function calculateAverageVisitTime($startDate, $endDate)
    {
        return Visitor::averageVisitTimeBetween($startDate, $endDate);
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    private static function countTotalAmountOfEvents($startDate, $endDate)
    {
        return Event::eventsBetween($startDate,$endDate);
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return array
     */
    private static function findUserRoutes($startDate, $endDate): ?array
    {
        return Visitor::routesBetween($startDate, $endDate);
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    private static function calculateAverageViewTimePerProduct($startDate, $endDate)
    {
        return ProductView::averageLookTimeBetween($startDate, $endDate);
    }
}
