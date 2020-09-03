<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');
    }

    /**
     * @test
     */
    public function testIfProductPageLoadsCorrectly()
    {
        $response = $this->get('/products');
        $response->assertSeeText('Products');
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function testIfProductDetailPageLoads()
    {
        $response = $this->get('/products/1');
        $response->assertSeeText('1');
        $response->assertStatus(200);
    }



    /*
 * 1. navigating to page shows products (productseeder generates 10 products)
 * 2. clicking on "Show product..." show a page with url /products/product_id
 * 3. when moving away from a detail page, the time spent on the detail page gets logged to de product_views table
 * */
}
