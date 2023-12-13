<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Seller;
use Carbon\Carbon;

class WheelTest extends TestCase
{
    use RefreshDatabase;

    public $urlPrefix = 'api/v1/wheels/';

    public $seller = [
        'mobile' => '09391121001',
    ];

    /**
     * ---------
     */
    /** @test */
    public function show_wheel_create_page(): void
    {
        $this->get('wheels/create')->assertRedirectToRoute('sellers.login');

        $seller = Seller::factory()->create();
        $this->actingAs($seller, 'seller')->get('wheels/create')->assertStatus(200);
    }


}
