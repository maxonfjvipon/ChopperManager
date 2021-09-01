<?php


namespace App\Actions;


interface Executable
{
    public function execute(array $validated);
}
