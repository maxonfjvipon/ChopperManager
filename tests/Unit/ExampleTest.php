<?php

namespace Tests\Unit;

use Modules\AdminPanel\Entities\Tenant;
use Modules\User\Entities\PMUser;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        Tenant::find(1)->execute(function () {
            dd((new PMUser())->available_series);
        });
    }
}
