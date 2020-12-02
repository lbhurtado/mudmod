<?php

namespace Tests\Unit;

use Tests\TestCase;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    protected $permissions = [
        'issue command',
        'send message',
        'broadcast message'
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }

    /** @test */
    public function all_permissions_present()
    {
        foreach ($this->permissions as  $permission) {
            $this->assertTrue(in_array($permission, Permission::pluck('name')->toArray()));
        }
    }
}
