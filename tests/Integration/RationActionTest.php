<?php

namespace Tests\Integration;

use TypeError;
use Tests\TestCase;
use App\Jobs\Ration;
use App\Models\Contact;
use LBHurtado\Missive\Missive;
use App\CommandBus\RationAction;
use LBHurtado\Missive\Models\SMS;
use App\Models\Ration as Rations;
use Illuminate\Support\Facades\Bus;
use LBHurtado\Missive\Routing\Router;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RationActionTest extends TestCase
{
    use RefreshDatabase;

    /** @var array */
    protected $tags = ['tag1', 'tag2', 'tag3'];

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        $this->artisan('db:seed', ['--class' => 'RationSeeder']);
    }

    /** @test */
    public function agent_ration_action_receives_hashtags()
    {
        /*** arrange ***/
        Bus::fake();
        $sms = $this->prepareToListenAs('agent');
        $code = Rations::all()->random()->code;
        $tags = $this->getSpaceDelimitedTags();

        /*** act ***/
        app(RationAction::class)('RATION', compact('code','tags'));

        /*** assert ***/
        Bus::assertDispatched(Ration::class, function ($job) use ($tags, $sms) {
            return $job->contact === $sms->origin && $job->tags == $tags;
        });
    }

    /** @test */
    public function admin_ration_action_receives_hashtags()
    {
        /*** arrange ***/
        Bus::fake();
        $sms = $this->prepareToListenAs('admin');
        $code = Rations::all()->random()->code;
        $tags = $this->getSpaceDelimitedTags();

        /*** act ***/
        app(RationAction::class)('RATION', compact('code','tags'));

        /*** assert ***/
        Bus::assertDispatched(Ration::class, function ($job) use ($tags, $sms) {
            return $job->contact === $sms->origin && $job->tags == $tags;
        });
    }

    /** @test */
    public function subscriber_listen_action_does_not_receive_hashtags()
    {
        /*** arrange ***/
        Bus::fake();
        $sms = $this->prepareToListenAs('subscriber');
        $code = Rations::all()->random()->code;
        $tags = $this->getSpaceDelimitedTags();
        $this->expectException(TypeError::class);

        /*** act ***/
        app(RationAction::class)('RATION', compact('code','tags'));

        /*** assert ***/
        Bus::assertNotDispatched(Ration::class);
    }

    protected function prepareToListenAs(string $role): \LBHurtado\Missive\Models\SMS
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

    /**
     * @return string
     * i.e "tag1 tag2 tag3"
     */
    protected function getSpaceDelimitedTags()
    {
        return implode(' ', $this->tags);
    }
}
