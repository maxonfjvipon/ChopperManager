<?php


namespace App\Actions;


use App\Models\Pumps\PumpProducer;
use App\Models\Pumps\PumpSeries;
use App\Models\Users\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction implements Executable
{
    public function execute(array $validated)
    {
        $user = User::create([
            'organization_name' => $validated['organization_name'],
            'itn' => $validated['itn'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'city_id' => $validated['city_id'],
            'business_id' => $validated['business_id'],
        ]);

        $user->discounts()->insert(array_map(function ($series) use ($user) {
            return ['user_id' => $user->id, 'discountable_id' => $series['id'], 'discountable_type' => 'pump_series'];
        }, PumpSeries::all()->toArray()));

        $user->discounts()->insert(array_map(function ($producer) use ($user) {
            return ['user_id' => $user->id, 'discountable_id' => $producer['id'], 'discountable_type' => 'pump_producer'];
        }, PumpProducer::all()->toArray()));

        event(new Registered($user));

        Auth::login($user);
    }
}
