<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public $seller = [
        'mobile' => '09391121001',
    ];

    /**
     * A basic test example.
     */
    /** @test */
    public function send_verification_code(): void
    {
        $response = $this->get('/api/v1/seller/send_verification_code');

        $response->assertStatus(200);
    }
}
