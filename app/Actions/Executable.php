<?php


namespace App\Actions;


use Illuminate\Foundation\Http\FormRequest;

interface Executable
{
    public function execute(FormRequest $request);
}
