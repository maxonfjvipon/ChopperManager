<?php

namespace Modules\AdminPanel\Support;

use Illuminate\Support\Facades\DB;

class DatabaseManager
{
    public function createDatabase($database): bool
    {
        return DB::statement("create database {$database} character set utf8mb4 collate utf8mb4_unicode_ci");
    }

    public function dropDatabaseIfExists($database): bool
    {
        return DB::statement("drop database if exists {$database}");
    }
}
