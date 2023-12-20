<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Seller;

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
    public function show_wheel_index_page(): void
    {
        $this->get('wheels')->assertRedirectToRoute('sellers.login');

        $seller = Seller::factory()->create();
        $this->actingAs($seller, 'seller')->get('wheels')->assertStatus(200);
    }

    /**
     * ---------
     */
    /** @test */
    public function store_wheel(): void
    {
        $seller = Seller::factory()->create();

        $this->actingAs($seller, 'seller')->postJson(
            $this->urlPrefix . 'create',
            [
                'slice_num' => 10,
            ]
        )->assertStatus(201);
    }

    /**
     * ---------
     */
    /** @test */
    public function show_wheel_edit_page(): void
    {
        $seller = Seller::factory()->create();

        $res = $this->actingAs($seller, 'seller')->postJson(
            $this->urlPrefix . 'create',
            [
                'slice_num' => 10,
            ]
        );

        $this->actingAs($seller, 'seller')->get("wheels/{$res['data']['slug']}/edit")->assertStatus(200);
    }
}
