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
    public function show_login_page(): void
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
    public function validation_seller_login(): void
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
        $this->postJson(
            $this->urlPrefix . 'send_verification_code',
            [
                'mobile' => $this->seller['mobile']
            ]
        )->assertStatus(201);

        $seller = Seller::first();

        $this->assertNotEmpty($seller);
        $this->assertNotEmpty($seller->verificationCode->code);
    }

    /**
     * ----------
     */
    /** @test */
    public function check_verification_code(): void
    {
        $this->postJson(
            $this->urlPrefix . 'send_verification_code',
            [
                'mobile' => $this->seller['mobile']
            ]
        );

        $seller = Seller::with('verificationCode')->first();

        $this->postJson(
            $this->urlPrefix . 'check_verification_code',
            [
                'mobile' => $this->seller['mobile'],
                'code' => $seller->verificationCode->code
            ]
        )
            ->assertStatus(200)
            ->assertJson(['message' => 'password not set']);


        $this->assertEquals(auth('seller')->user()->mobile, $this->seller['mobile']);
    }

    /**
     * ----------
     */
    /** @test */
    public function check_verification_codeـwithـaـlongـinterval(): void
    {
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
            $this->urlPrefix . 'check_verification_code',
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
        $seller = Seller::factory()->create();

        $this->actingAs($seller, 'seller')->postJson(
            $this->urlPrefix . 'password',
            [
                'password' => '123456',
                'password_confirmation' => '123456'
            ]
        )->assertStatus(201);

        $sellerPass = Seller::pluck('password')->first();

        $this->assertNotEmpty($sellerPass);
    }

    /**
     * ---------
     */
    /** @test */
    public function show_dashboard_page(): void
    {
        $seller = Seller::factory()->create();

        $res = $this->actingAs($seller, 'seller')->get('sellers/dashboard');

        $res->assertStatus(200);
    }

    /**
     * ----------
     */
    /** @test */
    public function login_when_password_before_set(): void
    {
        $seller = Seller::factory()->create([
            'password' => 'password'
        ]);

        $this->postJson(
            $this->urlPrefix . 'send_verification_code',
            [
                'mobile' => $seller->mobile
            ]
        )->assertStatus(200);
    }

    /**
     * ----------
     */
    /** @test */
    public function login_with_password(): void
    {
        $seller = Seller::factory()->create([
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
        ]);

        $this->postJson(
            $this->urlPrefix . 'login',
            [
                'mobile' => $seller->mobile,
                'password' => 'password',
            ]
        )->assertStatus(200);

        $this->assertNotEmpty(auth('seller')->user());
    }

    /**
     * ---------
     */
    /** @test */
    public function show_password_forgot_page(): void
    {
        $this->get('sellers/password-forgot')->assertStatus(200);
    }

}
