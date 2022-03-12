<?php

namespace Tests;

use Illuminate\Support\Facades\File;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use PHPUnit\Framework\TestSuite;

class DatabaseTestListener implements TestListener
{
    use TestListenerDefaultImplementation;

    /**
     * Set up the database for testing.
     *
     * @param TestSuite $suite
     */
    public function startTestSuite(TestSuite $suite): void
    {
        if (str_contains($suite->getName(), 'Endpoint') || str_contains($suite->getName(), 'DB')) {
            $this->dropFiles();

            chdir(__DIR__ . '/..');

            if (shell_exec('php artisan migrate:fresh') == null)
                var_dump('cant migrate');
            if (shell_exec('php artisan db:seed --class=TestingSeeder') == null)
                var_dump('cant seed');
        }
    }

    /**
     * Clean up the database files.
     *
     * @param TestSuite $suite
     */
    public function endTestSuite(TestSuite $suite): void
    {
        if (str_contains($suite->getName(), 'Endpoint') || str_contains($suite->getName(), 'DB')) {
            $this->dropFiles();
        }
    }

    private function dropFiles()
    {
        $basePath = __DIR__ . '/../database/base.sqlite';

        shell_exec('rm -f ' . $basePath);

        $copyPath = __DIR__ . '/../database/database.sqlite';

        shell_exec('rm ' . $copyPath);
        shell_exec('touch ' . $copyPath);
}
}
