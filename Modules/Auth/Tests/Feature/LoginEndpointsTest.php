<?php

use Illuminate\Support\Facades\Auth;
use Tests\TenantTestCase;

class LoginEndpointsTest extends TenantTestCase
{
    protected function tenantId(): int
    {
        return 1;
    }

    public function test_login()
    {
        Auth::logout();
        $this->get(route('login'))
            ->assertInertia(fn($page) => $page
                ->component('Auth::Login', false)
            )->assertStatus(200);
        $this->assertNull(Auth::user());
    }

    public function test_redirect_from_login()
    {
        $this->get(route('login'))
            ->assertRedirect(route('projects.index'))
            ->assertStatus(302);
    }
}
