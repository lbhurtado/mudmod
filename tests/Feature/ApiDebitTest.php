<?php

namespace Tests\Feature;

use App\Models\Contact;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiDebitTest extends TestCase
{
    use RefreshDatabase;

    protected $endpoint = '/api/debit';

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);

    }

    /** @test */
    public function ApiDebitEndPointOnly404()
    {
        /*** arrange ***/
        $status = Response::HTTP_NOT_FOUND;

        /*** act ***/
        $response = $this->post($this->endpoint);

        /*** assert ***/
        $response->assertStatus($status);
    }

    /** @test */
    public function ApiDebitMobileOnly404()
    {
        /*** arrange ***/
        $mobile = '09171234567';

        /*** act ***/
        $response = $this->post($this->endpoint . "/$mobile");

        /*** assert ***/
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function ApiDebitMobileAndOTPOnly404()
    {
        /*** arrange ***/
        $mobile = '09171234567';
        $otp = '1234';

        /*** act ***/
        $response = $this->post($this->endpoint . "/$mobile/$otp");

        /*** assert ***/
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function ApiDebitRequiresExistingMobileValidOTPValidAmount()
    {
        /*** arrange ***/
        $mobile = '09171234567';
        $otp = $this->faker->numberBetween(1000,9999);
        $amount = $this->faker->numberBetween(0,1000);

        /*** act ***/
        $response = $this->post($this->endpoint . "/$mobile/$otp/$amount");

        /*** assert ***/
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /** @test */
    public function ApiDebitRequiresExistingMobileInvalidValidOTPValidAmount_404()
    {
        /*** arrange ***/
        $mobile = '09171234567';
        $this->createContact($mobile)->challenge();
        $otp = 'ABCD';
        $amount = $this->faker->numberBetween(0,1000);

        /*** act ***/
        $response = $this->post($this->endpoint . "/$mobile/$otp/$amount");

        /*** assert ***/
        $response
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function ApiDebitRequiresExistingMobileInvalidValidOTPValidAmountUnlessBypassed()
    {
        /*** arrange ***/
        $mobile = '09171234567';
        $contact = $this->createContact($mobile)->challenge();
        $otp = '0000';;
        $amount = $this->faker->numberBetween(100,1000); //max amount requested
        $starting_balance = $this->faker->numberBetween(100,1000);
        $contact->credit($starting_balance);

        /*** act ***/
        $this->app['config']->set('mudmod.otp.bypass', 1);
        $response = $this->post($this->endpoint . "/$mobile/$otp/$amount");

        /*** assert ***/
        $this->assertEquals($starting_balance < $amount ? 0 : $starting_balance - $amount, $contact->balance);
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'mobile' => $contact->mobile,
                'amount' => min($starting_balance, $amount),
                'message' => 'The quick brown fox...'
            ]);
    }

    /** @test */
    public function ApiDebitRequiresExistingMobileCorrectOTPInvalidValidAmount_404()
    {
        /*** arrange ***/
        $mobile = '09171234567';
        $contact = $this->createContact($mobile)->challenge();
        $otp = $contact->getTOTP()->now();;
        $amount = 'XXXX';

        /*** act ***/
        $response = $this->post($this->endpoint . "/$mobile/$otp/$amount");

        /*** assert ***/
        $response
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function ApiDebitRequiresExistingMobileCorrectOTPValidAmount()
    {
        /*** arrange ***/
        $mobile = '09171234567';
        $contact = $this->createContact($mobile)->challenge();
        $otp = $contact->getTOTP()->now();;
        $amount = $this->faker->numberBetween(100,1000); //max amount requested
        $starting_balance = $this->faker->numberBetween(100,1000);
        $contact->credit($starting_balance);

        /*** act ***/
        $response = $this->post($this->endpoint . "/$mobile/$otp/$amount");

        /*** assert ***/
        $this->assertEquals($starting_balance < $amount ? 0 : $starting_balance - $amount, $contact->balance);
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'mobile' => $contact->mobile,
                'amount' => min($starting_balance, $amount),
                'message' => 'The quick brown fox...'
            ]);
    }

    /**
     * @param string $mobile
     * @param string $role
     * @return mixed
     */
    protected function createContact(string $mobile)
    {
        return Contact::factory()
            ->create(compact('mobile'))
            ;
    }
}
