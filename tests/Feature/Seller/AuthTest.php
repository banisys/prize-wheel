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
     * ----------
     */
    /** @test */
    public function send_verification_code(): void
    {
        // $this->withoutExceptionHandling();
        $res = $this->postJson('api/v1/sellers/send_verification_code', ['mobile' => $this->seller['mobile']]);

        $res->assertStatus(200);
        $this->assertEquals(4, strlen($res['data']['code']));
    }
}
