<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Seller;
use App\Models\VerificationCode;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public $urlPrefix = 'api/v1/sellers/';

    public $seller = [
        'mobile' => '09391121001',
    ];

    /**
     * validation of submitted user login parameters
     */
    /** @test */
    public function validation_seller_login()
    {
        $res = $this->postJson($this->urlPrefix . 'send_verification_code');

        $res->assertJsonValidationErrorFor('mobile');
    }

    /**
     * ----------
     */
    /** @test */
    public function send_verification_code(): void
    {
        // $this->withoutExceptionHandling();
        $res = $this->postJson(
            $this->urlPrefix . 'send_verification_code',
            [
                'mobile' => $this->seller['mobile']
            ]
        );

        $seller = Seller::first();
        $verificationCode = VerificationCode::first();

        $this->assertNotEmpty($seller);
        $this->assertNotEmpty($verificationCode);
        $res->assertStatus(201);
        // $this->assertEquals(4, strlen($res['data']['code']));
    }
}
