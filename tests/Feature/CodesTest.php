<?php

namespace Tests\Feature;

use Mockery;
use Setting;
use Tests\TestCase;
use App\CommandBus\CodesAction;
use LBHurtado\Missive\Routing\Router;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CodesTest extends TestCase
{
    use RefreshDatabase;

    protected $keyword = 'CODES';

    protected $router;

    protected $action;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        $this->artisan('db:seed', ['--class' => 'SettingSeeder']);

        $this->router = app(Router::class);
        $this->action = Mockery::mock(CodesAction::class);
    }

    /** @test */
    public function default_pin_1234_and_codes_keyword_invokes_codes_action()
    {
        /*** arrange ***/
        $default_pin = Setting::get('PIN');
        $pin = '1234';
        $from = '09171234567'; $to = '09182222222'; $message = "{$pin} {$this->keyword}";

        /*** act ***/
        $this->router->register("{$default_pin} {$this->keyword}", $this->action);
        $this->json($this->method, $this->uri, compact('from', 'to', 'message'));
        $this->sleep_after_url();

        /*** assert ***/
        $this->action->shouldHaveReceived('__invoke');
    }

    /** @test */
    public function new_pin_and_codes_keyword_invokes_codes_action()
    {
        /*** arrange ***/
        Setting::forget('PIN');
        Setting::set('PIN', rand(1000,9999));
        $new_pin = Setting::get('PIN');
        $pin = '1234';
        $from = '09171234567'; $to = '09182222222'; $message = "{$new_pin} {$this->keyword}";

        /*** act ***/
        $this->router->register("{$new_pin} {$this->keyword}", $this->action);
        $this->json($this->method, $this->uri, compact('from', 'to', 'message'));
        $this->sleep_after_url();

        /*** assert ***/
        $this->action->shouldHaveReceived('__invoke');
    }

    /** @test */
    public function wrong_pin_and_codes_keyword_deos_not_invoke_codes_action()
    {
        /*** arrange ***/
        $default_pin = Setting::get('PIN');
        $pin = '4321';
        $from = '09171234567'; $to = '09182222222'; $message = "{$pin} {$this->keyword}";

        /*** act ***/
        $this->router->register("{$default_pin} {$this->keyword}", $this->action);
        $this->json($this->method, $this->uri, compact('from', 'to', 'message'));
        $this->sleep_after_url();

        /*** assert ***/
        $this->action->shouldNotHaveReceived('__invoke');
    }

    /** @test */
    public function default_pin_1234_and_wrong_keyword_does_not_invoke_codes_action()
    {
        /*** arrange ***/
        $default_pin = Setting::get('PIN');
        $pin = '1234';
        $keyword = 'WRONG';
        $from = '09171234567'; $to = '09182222222'; $message = "{$pin} {$keyword}";

        /*** act ***/
        $this->router->register("{$default_pin} {$this->keyword}", $this->action);
        $this->json($this->method, $this->uri, compact('from', 'to', 'message'));
        $this->sleep_after_url();

        /*** assert ***/
        $this->action->shouldNotHaveReceived('__invoke');
    }
}
