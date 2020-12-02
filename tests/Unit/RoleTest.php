<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Role;
use BeyondCode\Vouchers\Traits\HasVouchers;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    protected $roles = [
        'admin',
        'agent',
        'cashier',
        'subscriber'
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }

    /** @test */
    public function all_roles_present()
    {
        foreach ($this->roles as  $role) {
            $this->assertTrue(in_array($role, Role::pluck('name')->toArray()));
        }
    }

    /** @test */
    public function roles_has_vouchers()
    {
        $this->assertTrue(in_array(HasVouchers::class, class_uses(Role::first())));
    }
}
