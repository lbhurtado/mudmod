<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use App\Models\{Contact, Ration};
use App\CommandBus\CollectAction;
use LBHurtado\Missive\Routing\Router;
use BeyondCode\Vouchers\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CollectTest extends TestCase
{
    use RefreshDatabase;

    protected $router;

    protected $action;

    protected $prepend = '@';

    protected $tags = 'TAG1 TAG2 TAG3';

    protected $regex_tags;

    protected $name;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        $this->artisan('db:seed', ['--class' => 'RationSeeder']);

        $this->router = app(Router::class);
        $this->action = Mockery::mock(CollectAction::class);

        $this->tags = $this->faker->words(3);
        $this->name = $this->faker->name;

        tap(Contact::factory(['mobile' => '+639171234567'])->create()->assignRole('agent'))
            ->ration(Ration::all()->random()->code, $this->tags);

        $this->regex_tags = Ration::tagsRegex();
    }

    /** @test */
    public function prepended_tag_and_name_invokes_collect_action()
    {
        /*** arrange ***/
        $tag = $this->tags[array_rand($this->tags)];
        $from = '09171234567'; $to = '09182222222'; $message = "{$this->prepend}{$tag} {$this->name}}";

        /*** act ***/
        $this->router->register("{$this->prepend}{tag={$this->regex_tags}} {name}", $this->action);
        $this->json($this->method, $this->uri, compact('from', 'to', 'message'));
        $this->sleep_after_url();

        /*** assert ***/
        $this->action->shouldHaveReceived('__invoke');
    }

    /** @test */
    public function prepended_non_existent_tag_and_name_does_not_invoke_collect_action()
    {
        /*** arrange ***/
        $tag = $this->faker->word;
        $from = '09171234567'; $to = '09182222222'; $message = "{$this->prepend}{$tag} {$this->name}}";

        /*** act ***/
        $this->router->register("{$this->prepend}{tag={$this->regex_tags}} {name}", $this->action);
        $this->json($this->method, $this->uri, compact('from', 'to', 'message'));
        $this->sleep_after_url();

        /*** assert ***/
        $this->action->shouldNotHaveReceived('__invoke');
    }

    /** @test */
    public function prepended_tag_but_no_name_does_not_invoke_collect_action()
    {
        /*** arrange ***/
        $tag = $this->tags[array_rand($this->tags)];
        $from = '09171234567'; $to = '09182222222'; $message = "{$this->prepend}{$tag}}";

        /*** act ***/
        $this->router->register("{$this->prepend}{tag={$this->regex_tags}} {name}", $this->action);
        $this->json($this->method, $this->uri, compact('from', 'to', 'message'));
        $this->sleep_after_url();

        /*** assert ***/
        $this->action->shouldNotHaveReceived('__invoke');
    }
}
