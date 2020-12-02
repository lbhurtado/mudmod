<?php

namespace Tests\Unit;

use Setting;
use Tests\TestCase;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    protected $settings = [
        'PIN' => 537537,
        'keyword.expiration' => 60,
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'SettingSeeder']);
    }

    /** @test */
    public function all_settings_present()
    {
        foreach ($this->settings as  $key=>$value) {
            $this->assertTrue(Setting::get($key) == $value);
        }
    }
}
