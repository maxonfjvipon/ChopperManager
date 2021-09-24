<?php


namespace App\Actions;


use App\Http\Requests\RegisterRequest;
use App\Models\Users\Country;
use App\Models\Users\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    public function execute(RegisterRequest $request)
    {
        $user = User::create([
            'organization_name' => $request->organization_name,
            'itn' => $request->itn,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'city' => $request->city,
            'postcode' => $request->postcode,
            'country_id' => $request->country_id,
            'currency_id' => Country::find($request->country_id)->first()->currency_id,
            'business_id' => $request->business_id,
        ]);

        event(new Registered($user));

        Auth::login($user);
    }
}
