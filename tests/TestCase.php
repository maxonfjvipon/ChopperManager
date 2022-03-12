<?php

namespace Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Concerns\RefreshDatabase;

// TODO: TestListener will be removed in PHPUnit 10
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected function setUpTraits(): array
    {
        $uses = parent::setUpTraits();

        if (isset($uses[RefreshDatabase::class])) {
            $this->refreshDatabase();
        }

        return $uses;
    }
}
