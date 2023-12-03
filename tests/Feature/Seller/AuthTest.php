<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Seller;
use Carbon\Carbon;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public $urlPrefix = 'api/v1/sellers/';

    public $seller = [
        'mobile' => '09391121001',
    ];

    /**
     * ---------
     */
    /** @test */
    public function show_login_page()
    {
        $this->get('sellers/login')->assertStatus(200);

        $seller = Seller::factory()->create();
        $this->actingAs($seller, 'seller')->get('sellers/login')
            ->assertRedirectToRoute('sellers.show.dashboard');
    }

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

        $this->assertNotEmpty($seller);
        $this->assertNotEmpty($seller->verificationCode->code);
        $res->assertStatus(201);
    }

    /**
     * ----------
     */
    /** @test */
    public function enter_verification_code(): void
    {
        $this->postJson(
            $this->urlPrefix . 'send_verification_code',
            [
                'mobile' => $this->seller['mobile']
            ]
        );

        $seller = Seller::with('verificationCode')->first();

        $res = $this->postJson(
            $this->urlPrefix . 'enter_verification_code',
            [
                'mobile' => $this->seller['mobile'],
                'code' => $seller->verificationCode->code
            ]
        );

        $res->assertStatus(200)->assertJson(['message' => 'password not set',]);
        $this->assertEquals(auth('seller')->user()->mobile, '09391121001');
    }

    /**
     * ----------
     */
    /** @test */
    public function enter_verification_codeـwithـaـlongـinterval(): void
    {
        $this->withoutExceptionHandling();
        $this->postJson(
            $this->urlPrefix . 'send_verification_code',
            [
                'mobile' => $this->seller['mobile']
            ]
        );

        $seller = Seller::with('verificationCode')->first();

        $seller->verificationCode->updated_at = (new Carbon($seller->verificationCode->updated_at))->subMinutes(5);
        $seller->verificationCode->save(['timestamps' => FALSE]);


        $this->postJson(
            $this->urlPrefix . 'enter_verification_code',
            [
                'mobile' => $this->seller['mobile'],
                'code' => $seller->verificationCode->code
            ]
        )->assertStatus(410);
    }

    /**
     * ----------
     */
    /** @test */
    public function store_password(): void
    {
        $this->withoutExceptionHandling();

        $seller = Seller::factory()->create();

        $res = $this->actingAs($seller, 'seller')->postJson(
            $this->urlPrefix . 'password',
            [
                'password' => '123456',
                'password_confirmation' => '123456'
            ]
        );

        $sellerPass = Seller::pluck('password')->first();

        $this->assertNotEmpty($sellerPass);
        $res->assertStatus(201);
    }

    /**
     * ---------
     */
    /** @test */
    public function show_dashboard_page()
    {
        $seller = Seller::factory()->create();

        $res = $this->actingAs($seller, 'seller')->get('sellers/dashboard');

        $res->assertStatus(200);
    }
}
