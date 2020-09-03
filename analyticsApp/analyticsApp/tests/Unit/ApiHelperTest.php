<?php

namespace Tests\Unit;

use App\Helpers\ApiHelper;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class ApiHelperTest extends TestCase
{
    public function testCategorizeAgesCategorizesCorrectly()
    {
        $ageArray = [-1,12,16,55,99,102,32,61,300];

        $categorisedAges = ApiHelper::categoriseAges($ageArray);
        $this->assertEquals([
            0 => 1,
            1 => 1,
            2 => 0,
            3 => 2,
            4 => 3
        ], $categorisedAges);
    }

    public function testIfGenerateTimeLabelsFunctionsGeneratesCorrectLabels()
    {
        $expectedTimeLabels = [
            '00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00',
            '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00',
            '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00',
            '21:00', '22:00', '23:00'];

        $generatedTimeLabels = ApiHelper::generateTimeLabels();

        $this->assertEquals($expectedTimeLabels, $generatedTimeLabels);
        $this->assertEquals(24, sizeof($generatedTimeLabels));
        $this->assertEquals('09:00', $generatedTimeLabels[9]);
        $this->assertEquals('array', gettype($generatedTimeLabels));
        $this->assertEquals('string', gettype($generatedTimeLabels[0]));
    }

    public function testIfDataSelectorShowsTheRightData(){
        $time = 'week';

        $generatedTimeArray = ApiHelper::dataSelector('week');

        $this->assertEquals(
            [Carbon::today()->startOfWeek()->subWeek()->toDateString(),
                Carbon::today()->startOfWeek()->toDateString()],
            $generatedTimeArray);

        $time2 = null;

        $generatedTimeArray2 = ApiHelper::dataSelector($time2);

        $this->assertEquals(
            [null, Carbon::tomorrow()->toDateString()],
            $generatedTimeArray2
        );
    }
}
