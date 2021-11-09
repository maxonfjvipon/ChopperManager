<?php

namespace Modules\AdminPanel\Support;

use Illuminate\Support\Facades\DB;

class DatabaseManager
{
    public function createDatabase($database): bool
    {
        return DB::statement("create database {$database} character set utf8mb4 collate utf8mb4_unicode_ci");
    }

    public function renameDatabase($database, $prevDatabase): bool
    {
        // TODO: rename database logic
//        if ($database !== $prevDatabase) {
//            if (!$this->createDatabase($database)) {
//                return false;
//            }
//            $tables = DB::select("select table_name from information_schema.tables where table_schema='{$prevDatabase}'");
//            foreach ($tables as $table) {
//
//            }
//        }
        return true;
    }

    public function dropDatabaseIfExists($database): bool
    {
        return DB::statement("drop database if exists {$database}");
    }
}
