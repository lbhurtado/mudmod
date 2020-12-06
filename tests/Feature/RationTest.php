<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use App\CommandBus\RationAction;
use LBHurtado\Missive\Routing\Router;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RationTest extends TestCase
{
    use RefreshDatabase;

    protected $ration_array;

    protected $ration_keys;

    protected $regex_code;

    protected $regex_tags;

    protected $router;

    protected $action;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        $this->artisan('db:seed', ['--class' => 'RationSeeder']);

        $this->router = app(Router::class);
        $this->action = Mockery::mock(RationAction::class);

        $this->populateVariables();
    }

    /** @test */
    public function correct_ration_code_and_tags_invokes_ration_action()
    {
        /*** arrange ***/
        $from = '09171234567'; $to = '09182222222'; $message = "{$this->getCode()} {$this->getTags()}";

        /*** act ***/
        $this->router->register("{code={$this->regex_code}} {tags={$this->regex_tags}}", $this->action);
        $this->json($this->method, $this->uri, compact('from', 'to', 'message'));
        $this->sleep_after_url();

        /*** assert ***/
        $this->action->shouldHaveReceived('__invoke');
    }

    /** @test */
    public function incorrect_ration_code_does_not_invoke_ration_action()
    {
        /*** arrange ***/
        $wrong_code = $this->faker->word;
        $from = '09171234567'; $to = '09182222222'; $message = "{$wrong_code} {$this->getTags()}";

        /*** act ***/
        $this->router->register("{code={$this->regex_code}} {tags={$this->regex_tags}}", $this->action);
        $this->json($this->method, $this->uri, compact('from', 'to', 'message'));
        $this->sleep_after_url();

        /*** assert ***/
        $this->action->shouldNotHaveReceived('__invoke');
    }

    /** @test */
    public function no_tags_does_not_invoke_ration_action()
    {
        /*** arrange ***/
        $no_tags = "";
        $from = '09171234567'; $to = '09182222222'; $message = "{$this->getCode()} {$no_tags}";

        /*** act ***/
        $this->router->register("{code={$this->regex_code}} {tags={$this->regex_tags}}", $this->action);
        $this->json($this->method, $this->uri, compact('from', 'to', 'message'));
        $this->sleep_after_url();

        /*** assert ***/
        $this->action->shouldNotHaveReceived('__invoke');
    }

    /**
     * @return mixed
     */
    protected function getCode(): string
    {
        return $this->ration_keys[array_rand($this->ration_keys)];
    }

    /**
     * @return string
     */
    protected function getTags(): string
    {
        $tags_array = [];
        $n = rand(1, 5);
        for ($i = 0; $i < $n; $i++)
            $tags_array [] = $this->faker->word;

        return implode(' ', $tags_array);
    }

    protected function populateVariables(): void
    {
        parse_str(config('mudmod.rations.default'), $this->ration_array);
        $this->ration_keys = array_keys($this->ration_array);
        $this->regex_code = implode('|', $this->ration_keys);
        $this->regex_tags = '.*';
    }
}
