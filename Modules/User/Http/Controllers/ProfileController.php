<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Maxonfjvipon\Elegant_Elephant\Arrayable\ArrMapped;
use Modules\Core\Entities\Currency;
use Modules\User\Http\Requests\DiscountUpdateRequest;
use Modules\Pump\Entities\PumpBrand;
use Modules\User\Entities\Business;
use Modules\User\Entities\Country;
use Modules\User\Entities\Discount;
use Modules\User\Http\Requests\UpdateProfileRequest;
use Modules\User\Http\Requests\UserPasswordUpdateRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Transformers\ProfileUserResource;

class ProfileController extends Controller
{
    /**
     * Display a user profile.
     *
     * @return Response
     * @throws \Exception
     */
    public function index(): Response
    {
        return Inertia::render('User::Profile', [
            'user' => new ProfileUserResource(Auth::user()),
            'businesses' => Business::allOrCached(),
            'countries' => Country::allOrCached(),
            'currencies' => ArrMapped::new(
                [...Currency::allOrCached()],
                fn($currency) => [
                    'id' => $currency->id,
                    'name' => $currency->name_code
                ]
            )->asArray(),
            'discounts' => auth()->user()->formatted_discounts]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProfileRequest $request
     * @return RedirectResponse
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        Auth::user()->update($request->validated());
        return Redirect::back()->with('success', __('flash.users.updated'));
    }

    /**
     * Update user password
     *
     * @param UserPasswordUpdateRequest $request
     * @return RedirectResponse
     */
    public function changePassword(UserPasswordUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        if (array_key_exists('password', $validated) && $validated['password'] != null) {
            Auth::user()->update([
                'password' => Hash::make($validated['password'])
            ]);
            return Redirect::back()->with('success', __('flash.users.password_changed'));
        }
        return Redirect::back();
    }

    /**
     * Update user discounts
     * TODO: update or create ???
     *
     * @param DiscountUpdateRequest $request
     * @return RedirectResponse
     */
    public function updateDiscount(DiscountUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $discount = Discount::where('user_id', $request->user_id)
            ->where('discountable_id', $request->discountable_id)
            ->where('discountable_type', $request->discountable_type)
            ->first();
        $discount->update($validated);

        // update series discounts as producer discount
        if ($discount->discountable_type === 'pump_brand') {
            Discount::where('user_id', $request->user_id)
                ->whereIn('discountable_id', $discount->discountable->series()->pluck('id')->all())
                ->where('discountable_type', 'pump_series')
                ->update(['value' => $request->value]);
        }
        return Redirect::back();
    }
}
