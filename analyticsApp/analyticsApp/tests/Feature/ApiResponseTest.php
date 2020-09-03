<?php

namespace Tests\Feature;

use App\Event;
use App\Page;
use App\ProductView;
use App\Visitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiResponseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function testLangDataApi()
    {
        $this->json('GET', '/api/langdata')
            ->assertStatus(200)
            ->assertJson([['Dutch', 'French', 'English'], [0, 0, 0]]);

        $dutchVisitor = factory(Visitor::class)->create([
            'language_id' => 1,
        ]);

        $this->json('GET', '/api/langdata')
            ->assertStatus(200)
            ->assertJson([['Dutch', 'French', 'English'], [1, 0, 0]]);

        $englishVisitor = factory(Visitor::class)->create([
            'language_id' => 3,
        ]);


        $this->json('GET', '/api/langdata')
            ->assertStatus(200)
            ->assertJson([['Dutch', 'French', 'English'], [1, 0, 1]]);
    }

    public function testAgeData()
    {
        $this->json('GET', '/api/agedata')
            ->assertStatus(200)
            ->assertJson([['-12', '13-18', '19-25', '26-60', '61+'], [0, 0, 0, 0, 0]]);

        $veryYoungVisitor = factory(Visitor::class)->create([
            'age' => 1,
        ]);

        $this->json('GET', '/api/agedata')
            ->assertStatus(200)
            ->assertJson([['-12', '13-18', '19-25', '26-60', '61+'], [1, 0, 0, 0, 0]]);

        $veryOldVisitor = factory(Visitor::class)->create([
            'age' => 100,
        ]);

        $this->json('GET', '/api/agedata')
            ->assertStatus(200)
            ->assertJson([['-12', '13-18', '19-25', '26-60', '61+'], [1, 0, 0, 0, 1]]);

        $twentyFiveYearOldUser = factory(Visitor::class)->create([
            'age' => 25,
        ]);

        $this->json('GET', '/api/agedata')
            ->assertStatus(200)
            ->assertJson([['-12', '13-18', '19-25', '26-60', '61+'], [1, 0, 1, 0, 1]]);
    }

    public function testPageVisits()
    {
        $count = 5;
        $pageAddress = '/test';
        $someAddress = '/somePage';
        $lastPagAddress = '/last';

        $this->json('GET', '/api/pagevisits')
            ->assertStatus(200)
            ->assertJson([[], []]);

        $newPage = factory(Page::class)->create([
            'url' => $pageAddress,
            'visit_count' => $count
        ]);

        $this->json('GET', '/api/pagevisits')
            ->assertStatus(200)
            ->assertJson([[$pageAddress], [$count]]);

        for ($i = 0; $i<3; $i++){
            $anotherPage = factory(Page::class)->create([
                'url' => $someAddress,
                'visit_count' => 0
            ]);
        }

        $lastPage = factory(Page::class)->create([
            'url' => $lastPagAddress,
            'visit_count' => $count
        ]);

        $this->json('GET', '/api/pagevisits')
            ->assertStatus(200)
            ->assertJson([[$pageAddress,$someAddress,$someAddress,$someAddress,$lastPagAddress], [$count,0,0,0,$count]]);
    }

    public function testEventsPerHour()
    {
        $eventCountArray = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $eventHour = 5;

        $this->json('GET', '/api/eventsPerHour')
            ->assertStatus(200)
            ->assertJson([[
                '00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00',
                '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00',
                '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00',
                '21:00', '22:00', '23:00'],
                $eventCountArray
                ]);

        $lastPage = factory(Event::class)->create([
            'created_at' => "2020-02-17 $eventHour:45:00"
        ]);

        //$eventCountArray[$eventHour] = (int)1;

        $this->json('GET', '/api/eventsPerHour')
            ->assertStatus(200)
            ->assertJson([[
                '00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00',
                '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00',
                '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00',
                '21:00', '22:00', '23:00'],
                [0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]]);

    }

    public function testProductViews()
    {
        $this->json('GET', '/api/productViews')
            ->assertStatus(200)
            ->assertJsonCount(2,0)
            ->assertJsonCount(10,[0])
            ->assertJsonCount(10, [1]);

        $productView = factory(ProductView::class)->create([
            'product_id' => 1
        ]);

        $this->json('GET', '/api/productViews')
            ->assertStatus(200)
            ->assertJsonCount(2,0)
            ->assertJsonCount(10,[0])
            ->assertJsonCount(10, [1])
            ->assertJson([
                [],
                [1, 0, 0, 0, 0, 0, 0, 0, 0, 0, ]
            ]);

        $productView2 = factory(ProductView::class)->create([
            'product_id' => 5
        ]);
        $productView3 = factory(ProductView::class)->create([
            'product_id' => 10
        ]);

        $this->json('GET', '/api/productViews')
            ->assertStatus(200)
            ->assertJsonCount(2,0)
            ->assertJsonCount(10,[0])
            ->assertJsonCount(10, [1])
            ->assertJson([
                [],
                [1, 0, 0, 0, 1, 0, 0, 0, 0, 1, ]
            ]);

    }

    public function testWrongEndpointResponse()
    {
        $this->json('GET', '/api/langData/invalidLink')
            ->assertStatus(404);
        $this->json('GET', '/api/agedata/invalidLink')
            ->assertStatus(404);
        $this->json('GET', '/api/pagevisits/invalidLink')
            ->assertStatus(404);
        $this->json('GET', '/api/eventsPerHour/invalidLink')
            ->assertStatus(404);
        $this->json('GET', '/api/productViews/invalidLink')
            ->assertStatus(404);

    }
}
