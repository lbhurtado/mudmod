<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Jobs\Collect;
use App\Models\Contact;
use LBHurtado\Missive\Missive;
use App\CommandBus\CollectAction;
use LBHurtado\Missive\Models\SMS;
use Illuminate\Support\Facades\Bus;
use LBHurtado\Missive\Routing\Router;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CollectActionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }

    /** @test */
    public function subscriber_collect_action_dispatches_collect_job()
    {
        /*** arrange ***/
        Bus::fake();
        $sms = $this->prepareToRedeemAs('subscriber');
        $tag = $this->faker->word;
        $name = $this->faker->name;

        /*** act ***/
        app(CollectAction::class)('COLLECT', compact('tag', 'name'));

        /*** assert ***/
        Bus::assertDispatched(Collect::class, function ($job) use ($sms, $tag, $name) {
            return $job->contact === $sms->origin && $job->tag == $tag && $job->name == $name;
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
