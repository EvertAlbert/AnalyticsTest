<?php

namespace Tests\Feature;

use App\Helpers\WebSocketHelper;
use App\Page;
use App\ProductView;
use App\Visitor;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WebsocketHelperTest extends TestCase
{
    use RefreshDatabase;
    private $arrivalTime;
    private $departureTime = null;
    private $uuid;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function createEventLog(string $action, string $message, string $page): array
    {
        $this->arrivalTime = Carbon::now()->toDateTimeString();
        $this->uuid = '355239f7-5961-4d73-aaa1-05673169bade';
        return [
            'visitorId' => $this->uuid,
            'action' => $action,
            'message' => $message,
            'page' => $page
        ];
    }

    public function testUserConnection(): void
    {
        WebSocketHelper::connected($this->createEventLog('connect', '/', 'http://homestead.test/'));
        $this->assertDatabaseHas('visitors', ['id' => $this->uuid,]);
        $this->assertDatabaseHas('visitors', ['arrival_time' => $this->arrivalTime,]);
    }

    public function testIfDatabaseRemembersOriginalArrivalTime(): void
    {
        WebSocketHelper::connected($this->createEventLog('connect', '/', 'http://homestead.test/'));
        $this->assertDatabaseHas('visitors', ['id' => $this->uuid,]);
        $this->assertDatabaseHas('visitors', ['arrival_time' => $this->arrivalTime,]);

        $oldTime = $this->arrivalTime;
        sleep(2);
        WebSocketHelper::connected($this->createEventLog('connect', '/', 'http://homestead.test/'));
        $this->assertDatabaseHas('visitors', ['id' => $this->uuid,]);
        $this->assertDatabaseHas('visitors', ['arrival_time' => $oldTime,]);
    }

    public function testUserDisconnection(): void
    {
        //create a new connection
        WebSocketHelper::connected($this->createEventLog('connect', '/', 'http://homestead.test/'));
        $this->assertDatabaseHas('visitors', ['id' => $this->uuid,]);
        $this->assertDatabaseHas('visitors', ['arrival_time' => $this->arrivalTime,]);

        //simulate a disconnect from the user above
        $this->departureTime = Carbon::now()->toDateTimeString() .
            WebSocketHelper::disconnected($this->createEventLog('disconnect', '/', 'http://homestead.test/'));
        $this->assertDatabaseHas('visitors', [
            'id' => $this->uuid,
            'departure_time' => $this->departureTime
        ]);
    }

    public function testIfAgeUpdatesCorrectly()
    {
        //create a new connection
        WebSocketHelper::connected($this->createEventLog('connect', '/', 'http://homestead.test/'));

        //update user age
        $age = 4;
        WebSocketHelper::setAge($this->createEventLog('age', $age, 'http://homestead.test/'));
        $this->assertDatabaseHas('visitors', [
            'id' => $this->uuid,
            'age' => $age
        ]);
    }

    public function testWrongAgeUpdateInDB()
    {
        //create a new connection
        WebSocketHelper::connected($this->createEventLog('connect', '/', 'http://homestead.test/'));

        //update user age
        $age = -4;
        WebSocketHelper::setAge($this->createEventLog('age', $age, 'http://homestead.test/'));
        $this->assertDatabaseHas('visitors', [
            'id' => $this->uuid,
            'age' => null
        ]);

        $age2 = 'random wrong message';
        WebSocketHelper::setAge($this->createEventLog('age', $age2, 'http://homestead.test/'));
        $this->assertDatabaseHas('visitors', [
            'id' => $this->uuid,
            'age' => null
        ]);
    }

    public function testRatingUpdate()
    {
        //create a new connection
        WebSocketHelper::connected($this->createEventLog('connect', '/', 'http://homestead.test/'));

        //give wrong value in rating
        $wrongRating = 5;
        WebSocketHelper::setRating($this->createEventLog('rate', $wrongRating, 'http://homestead.test/'));
        $this->assertDatabaseHas('visitors', [
            'id' => $this->uuid,
            'rating' => null
        ]);

        //update user rating
        $correctRating = 1;
        WebSocketHelper::setRating($this->createEventLog('rate', $correctRating, 'http://homestead.test/'));
        $this->assertDatabaseHas('visitors', [
            'id' => $this->uuid,
            'rating' => $correctRating
        ]);

        //give another wrong rating after a correct rating ($correctRating)
        $anotherWrongRating = -6;
        WebSocketHelper::setRating($this->createEventLog('rate', $anotherWrongRating, 'http://homestead.test/'));
        $this->assertDatabaseHas('visitors', [
            'id' => $this->uuid,
            'rating' => $correctRating
        ]);
    }

    public function testLanguageUpdate()
    {
        //create a new connection
        WebSocketHelper::connected($this->createEventLog('connect', '/', 'http://homestead.test/'));

        //add a nonsense message as language
        $wrongLanguageString = 'this is some random text and shoul not be added to the database';
        WebSocketHelper::setLanguage($this->createEventLog('language', $wrongLanguageString, 'http://homestead.test/'));
        $this->assertDatabaseHas('visitors', [
            'id' => $this->uuid,
            'language_id' => null
        ]);

        //add a legitimate language
        $correctLanguageString = 'FRA';
        WebSocketHelper::setLanguage($this->createEventLog('language', $correctLanguageString,
            'http://homestead.test/'));
        $this->assertDatabaseHas('visitors', [
            'id' => $this->uuid,
            'language_id' => 2
        ]);

        //add a legitimate with full name
        $anotherCorrectLanguageString = 'English';
        WebSocketHelper::setLanguage($this->createEventLog('language', $anotherCorrectLanguageString,
            'http://homestead.test/'));
        $this->assertDatabaseHas('visitors', [
            'id' => $this->uuid,
            'language_id' => 3
        ]);

        //add an unsupported language after correct update
        $anotherWrongLanguageString = 'Norwegian';
        WebSocketHelper::setLanguage($this->createEventLog('language', $anotherWrongLanguageString,
            'http://homestead.test/'));
        $this->assertDatabaseHas('visitors', [
            'id' => $this->uuid,
            'language_id' => 3
        ]);
    }

    public function testProductViewUpdate()
    {
        //this is the product that is about to be viewed
        $productId = 10;
        //register necessary pages
        factory(Page::class)->create([
            'url' => '/',
        ]);
        factory(Page::class)->create([
            'url' => '/products',
        ]);
        factory(Page::class)->create([
            'url' => '/products/' . $productId,
        ]);

        //create a new connection
        WebSocketHelper::connected($this->createEventLog('connect', 'http://homestead.test/', '/'));

        //generate a productView in the database
        WebSocketHelper::productClicked($this->createEventLog('productClick', $productId, '/products'));
        $this->assertDatabaseHas('product_views', [
            'visitor_id' => $this->uuid,
            'product_id' => $productId,
            'look_time' => null
        ]);

        //give the productView a wrong viewTime
        $wrongMessage = 'some random text';
        WebSocketHelper::setProductViewTime($this->createEventLog('viewTime', $wrongMessage,
            '/products/' . $productId));
        $this->assertDatabaseHas('product_views', [
            'visitor_id' => $this->uuid,
            'product_id' => $productId,
            'look_time' => null
        ]);

        //give the productView a viewTime
        $viewTime = '25';
        WebSocketHelper::setProductViewTime($this->createEventLog('viewTime', $productId . '_' . $viewTime,
            '/products/' . $productId));
        $this->assertDatabaseHas('product_views', [
            'visitor_id' => $this->uuid,
            'product_id' => $productId,
            'look_time' => $viewTime
        ]);
    }
}
