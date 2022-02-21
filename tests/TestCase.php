<?php

namespace Tests;

use Database\Seeders\FullDatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(FullDatabaseSeeder::class);
        var_dump("set up");
    }

    protected function tearDown(): void
    {
        var_dump("tear down");
    }
}
