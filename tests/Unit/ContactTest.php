<?php

namespace Tests\Unit;

use App\Jobs\PlaceBet;
use Tests\TestCase;
use App\Models\{Contact, Ration, Role};
use BeyondCode\Vouchers\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    protected $mobile = '09171234567';
    protected $contact;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        $this->artisan('db:seed', ['--class' => 'VoucherSeeder']);
        $this->artisan('db:seed', ['--class' => 'RationSeeder']);
        $this->contact = factory(Contact::class)->create(['mobile' => $this->mobile]);
    }

    /** @test */
    public function contact_is_initially_a_subscriber()
    {
        /*** assert ***/
        $this->assertTrue($this->contact->hasRole('subscriber'));
        $this->assertFalse($this->contact->hasRole('listener'));
        $this->assertFalse($this->contact->hasRole('spokesman'));
        $this->assertFalse($this->contact->hasRole('forwarder'));
    }

    /** @test */
    public function contact_has_no_email_initially()
    {
        /*** assert ***/
        $this->assertNull($this->contact->email);
    }

    /** @test */
    public function contact_has_no_tag_initially()
    {
        /*** assert ***/
        $this->assertEmpty($this->contact->hashtags);
    }

    /** @test */
    public function contact_can_be_searched_using_mobile_number()
    {
        /*** arrange ***/
        $mobile = $this->mobile;

        /*** act ***/
        $contact = Contact::bearing($mobile);

        /*** assert ***/
        $this->assertTrue($this->contact->is($contact));
    }

    /** @test */
    public function contact_has_zero_balance_at_the_onset()
    {
        /*** arrange ***/
        $balance = 0.00;

        /*** act ***/

        /*** assert ***/
        $this->assertTrue($this->contact->balance == $balance);
    }

    /** @test */
    public function contact_can_increase_balance()
    {
        /*** arrange ***/
        $amount = rand(100, 1000);

        /*** act ***/
        $this->contact->increase($amount);

        /*** assert ***/
        $this->assertTrue($this->contact->balance == $amount);
    }

    /** @test */
    public function contact_can_redeem_role_voucher()
    {
        /*** assert ***/
        $roleName = 'agent';
        $this->assertFalse(in_array($roleName, $this->contact->getRoleNames()->toArray()));

        /*** arrange ***/
        $voucher_code = $this->getRoleVoucherCodeFromRoleName($roleName);
        $handle = $this->faker->name;

        /*** act ***/
        $this->contact->enlistRole($voucher_code, $handle);

        /*** assert ***/
        $this->assertTrue(in_array($roleName, $this->contact->getRoleNames()->toArray()));
        $this->assertEquals($handle, $this->contact->handle);
    }

    /** @test */
    public function contact_as_agent_can_ration()
    {
        /*** arrange ***/
        $this->contact->syncRoles('agent');
        $ration = Ration::all()->random();

        /*** act ***/
        $this->contact->ration($ration->code, $this->faker->word);

        /*** assert ***/
        $this->assertEquals($ration, $this->getVoucherFromRation($ration)->model);
    }

    /** @test */
    public function contact_as_subscriber_can_collect()
    {
        /*** arrange ***/
        $agent = $this->createContact('+639107654321', 'agent');
        $ration = Ration::all()->random();
        $tag = $this->faker->word;
        $handle = $this->faker->name;

        /*** act ***/
        $agent->ration($ration->code, $tag);
        $this->contact->collectRation($tag, $handle);

        /*** assert ***/
        $this->assertEquals($this->contact->balance, $ration->amount);
        $this->assertEquals($this->contact->handle, $handle);
    }

    /** @test */
    public function contact_is_initially_unverified()
    {
        /*** assert ***/
        $this->assertFalse($this->contact->verified());
    }

    /** @test */
    public function contact_cannot_be_verified_via_wrong_otp()
    {
        /*** arrange ***/
        $this->contact->challenge();
        $otp = $this->faker->numberBetween(1000,9999);

        /*** act ***/
        $this->contact->verify($otp);

        /*** assert ***/
        $this->assertFalse($this->contact->verified());
    }

    /** @test */
    public function contact_can_be_verified_via_correct_otp()
    {
        /*** arrange ***/
        $this->contact->challenge();
        $otp = $this->contact->getTOTP()->now();

        /*** act ***/
        $this->contact->verify($otp);

        /*** assert ***/
        $this->assertTrue($this->contact->verified());
    }

    /** @test */
    public function contact_can_bet()
    {
        /*** arrange ***/
        $date = $this->faker->date('Y-m-d');
        $game = $this->faker->numberBetween(1,350);
        $hand = $this->faker->randomElement(['MERON', 'WALA', 'DRAW', 'CANCELLED']);
        $amount = $this->faker->randomElement([100,200,500,1000,5000,10000]);

        /*** act ***/
        $this->contact->placeBet($date, $game, $hand, $amount);

        /*** assert ***/
        $this->assertEquals($this->contact->bet, compact('date', 'game', 'hand', 'amount'));
    }

    /**
     * @param string $roleName
     * @return string
     */
    protected function getRoleVoucherCodeFromRoleName(string $roleName): string
    {
        $role = $this->getRoleFromRoleName($roleName);
        $voucher = $this->getVoucherFromRole($role);

        return $voucher->code;
    }

    /**
     * @param string $roleName
     * @return mixed
     */
    protected function getRoleFromRoleName(string $roleName)
    {
        return Role::where('name', $roleName)->first();
    }

    /**
     * @param Role $role
     * @return Voucher
     */
    protected function getVoucherFromRole(Role $role): Voucher
    {
        $role_id = $role->getId();

        return Voucher::where('model_type', Role::class)
            ->where('model_id', $role_id)
            ->first();
    }

    /**
     * @param Ration $ration
     * @return mixed
     */
    protected function getVoucherFromRation(Ration $ration)
    {
        $model_id = $ration->getId();

        return Voucher::where('model_type', Ration::class)
            ->where(compact('model_id'))
            ->first();
    }

    /**
     * @param string $mobile
     * @param string $role
     * @return mixed
     */
    protected function createContact(string $mobile, string $role)
    {
        return Contact::factory()
            ->create(compact('mobile'))
            ->syncRoles($role)
            ;
    }
}
