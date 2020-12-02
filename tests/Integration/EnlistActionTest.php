<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Jobs\Enlist;
use App\Models\Contact;
use LBHurtado\Missive\Missive;
use App\CommandBus\EnlistAction;
use LBHurtado\Missive\Models\SMS;
use Illuminate\Support\Facades\Bus;
use LBHurtado\Missive\Routing\Router;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EnlistActionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }

    /** @test */
    public function subscriber_redeem_action_dispatches_redeem_job()
    {
        /*** arrange ***/
        Bus::fake();
        $sms = $this->prepareToRedeemAs('subscriber');
        $code = $this->faker->word;
        $name = $this->faker->name;

        /*** act ***/
        app(EnlistAction::class)('ENLIST', compact('code', 'name'));

        /*** assert ***/
        Bus::assertDispatched(Enlist::class, function ($job) use ($sms, $code, $name) {
            return $job->contact === $sms->origin && $job->code == $code && $job->name == $name;
        });
    }

    protected function prepareToRedeemAs(string $role): \LBHurtado\Missive\Models\SMS
    {
        $from = '+639191234567';
        $sms = factory(SMS::class)->create(compact('from'));
        $this->createContact($from, $role);

        $missive = app(Missive::class)->setSMS($sms);
        (new Router($missive))->process($sms);

        return $sms;
    }

    protected function createContact(string $mobile, string $role)
    {
        factory(Contact::class)
            ->create(compact('mobile'))
            ->syncRoles($role)
        ;

        return $this;
    }
}
