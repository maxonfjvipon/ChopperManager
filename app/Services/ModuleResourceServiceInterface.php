<?php

namespace App\Services;

interface ModuleResourceServiceInterface
{
    public function indexPath(): string;

    public function showPath(): string;

    public function editPath(): string;

    public function createPath(): string;
}
