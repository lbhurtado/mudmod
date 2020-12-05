<?php

namespace Tests\Feature;

use Mockery;
use Setting;
use Tests\TestCase;
use App\Models\{Role, Contact};
use App\CommandBus\EnlistAction;
use LBHurtado\Missive\Routing\Router;
use BeyondCode\Vouchers\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EnlistTest extends TestCase
{
    use RefreshDatabase;

    protected $code;

    protected $name;

    protected $router;

    protected $action;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        $this->artisan('db:seed', ['--class' => 'SettingSeeder']);
        $this->artisan('db:seed', ['--class' => 'VoucherSeeder']);

        $this->router = app(Router::class);
        $this->action = Mockery::mock(EnlistAction::class);
    }

    /** @test */
    public function correct_role_voucher_code_and_name_invokes_codes_action()
    {
        /*** arrange ***/
        $regex_code = '';
        $regex_name = '';
        extract(enlist_regex());
        $regex_code = Role::codeRegex();
        $regex_name = Contact::nameRegex();

        $code = Voucher::where('model_type', Role::class)->first()->code;
        $name = $this->faker->name;
        $from = '09171234567'; $to = '09182222222'; $message = "{$code} {$name}";

        /*** act ***/
        $this->router->register("{code={$regex_code}} {name={$regex_name}}", $this->action);
        $this->json($this->method, $this->uri, compact('from', 'to', 'message'));
        $this->sleep_after_url();

        /*** assert ***/
        $this->action->shouldHaveReceived('__invoke');
    }

    /** @test */
    public function wrong_role_voucher_code_and_name_invokes_codes_action()
    {
        /*** arrange ***/
        $regex_code = '';
        $regex_name = '';
        extract(enlist_regex());
        $regex_code = Role::codeRegex();
        $regex_name = Contact::nameRegex();

        $code = 'XXXX-XXXX';
        $name = $this->faker->name;

        $from = '09171234567'; $to = '09182222222'; $message = "{$code} {$name}";

        /*** act ***/
        $this->router->register("{code={$regex_code}} {name={$regex_name}}", $this->action);
        $this->json($this->method, $this->uri, compact('from', 'to', 'message'));
        $this->sleep_after_url();

        /*** assert ***/
        $this->action->shouldNotHaveReceived('__invoke');
    }
}
