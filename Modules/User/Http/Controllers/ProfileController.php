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
use Modules\Core\Entities\Currency;
use Modules\Core\Http\Requests\DiscountUpdateRequest;
use Modules\Pump\Entities\PumpBrand;
use Modules\User\Entities\Business;
use Modules\User\Entities\Country;
use Modules\User\Entities\Discount;
use Modules\User\Http\Requests\UserPasswordUpdateRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Transformers\ProfileUserResource;

class ProfileController extends Controller
{
    /**
     * Display a user profile.
     *
     * @return Response
     */
    public function index(): Response
    {
        $discounts = auth()->user()->discounts()
            ->where('discountable_type', '=', 'pump_brand')
            ->with(['discountable' => function (MorphTo $morphTo) {
                $morphTo->morphWith([
                    PumpBrand::class => ['series', 'series.discounts' => function ($query) {
                        $query->where('user_id', Auth::id());
                    }]
                ]);
            }])
            ->get()
            ->filter(fn($discount) => $discount->discountable)
            ->map(fn($discount) => [
                'key' => $discount->discountable_id . '-' . $discount->discountable_type . '-' . $discount->user_id,
                'discountable_id' => $discount->discountable_id,
                'discountable_type' => $discount->discountable_type,
                'user_id' => $discount->user_id,
                'name' => $discount->discountable->name,
                'value' => $discount->value,
                'children' => $discount->discountable->series
                    ->filter(fn($series) => count($series->discounts) > 0)
                    ->map(fn($series) => [
                        'key' => $series->discounts[0]->discountable_id
                            . '-' . $series->discounts[0]->discountable_type
                            . '-' . $series->discounts[0]->user_id,
                        'discountable_id' => $series->discounts[0]->discountable_id,
                        'discountable_type' => $series->discounts[0]->discountable_type,
                        'user_id' => $series->discounts[0]->user_id,
                        'name' => $series->name,
                        'value' => $series->discounts[0]->value,
                    ])->values()
            ])->values();
        return Inertia::render('User::Profile', [
            'user' => new ProfileUserResource(auth()->user()),
            'businesses' => Business::all(),
            'countries' => Country::all(),
            'currencies' => Currency::all()->transform(function ($currency) {
                return [
                    'id' => $currency->id,
                    'name' => $currency->code . ' - ' . $currency->name
                ];
            }),
            'discounts' => $discounts]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @return RedirectResponse
     */
    public function update(UpdateUserRequest $request): RedirectResponse
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
