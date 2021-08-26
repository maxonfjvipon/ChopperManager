<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiscountUpdateRequest;
use App\Http\Requests\UserPasswordUpdateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\Discount;
use App\Models\Pumps\PumpProducer;
use App\Models\Users\Area;
use App\Models\Users\Business;
use App\Models\Users\Role;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    /**
     * Display a user profile.
     *
     * @return Response
     */
    public function profile(): Response
    {
        return Inertia::render('Profile/Index', [
            'user' => new UserResource(auth()->user()),
            'roles' => Role::where('id', '!=', 1)->get(),
            'businesses' => Business::all(),
            'areasWithCities' => Area::with('cities')->get(),
            'discounts' => auth()->user()->discounts()
                ->where('discountable_type', '=', 'pump_producer')
                ->with(['discountable' => function (MorphTo $morphTo) {
                    $morphTo->morphWith([
                        PumpProducer::class => ['series', 'series.discount']
                    ]);
                }])
                ->get()
                ->map(function ($discount) {
                    return [
                        'key' => $discount->discountable_id . '-' . $discount->discountable_type . '-' . $discount->user_id,
                        'discountable_id' => $discount->discountable_id,
                        'discountable_type' => $discount->discountable_type,
                        'user_id' => $discount->user_id,
                        'name' => $discount->discountable->name,
                        'value' => $discount->value,
                        'children' => $discount->discountable->series->map(function ($series) {
                            return [
                                'key' => $series->discount->discountable_id
                                    . '-' . $series->discount->discountable_type
                                    . '-' . $series->discount->user_id,
                                'discountable_id' => $series->discount->discountable_id,
                                'discountable_type' => $series->discount->discountable_type,
                                'user_id' => $series->discount->user_id,
                                'name' => $series->name,
                                'value' => $series->discount->value,
                            ];
                        })
                    ];
                })
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(UserUpdateRequest $request): RedirectResponse
    {
        Auth::user()->update($request->validated());
        return Redirect::back()->with('success', 'Информация успешно обновлена');
    }

    /**
     * Update user password
     *
     * @param UserPasswordUpdateRequest $request
     * @return RedirectResponse
     */
    public function changePassword(UserPasswordUpdateRequest $request): RedirectResponse
    {
        Auth::user()->update([
            'password' => Hash::make($request->validated()['password'])
        ]);
        return Redirect::back()->with('success', 'Пароль успешно обновлен');
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
        if ($discount->discountable_type === 'pump_producer') {
            Discount::where('user_id', $request->user_id)
                ->whereIn('discountable_id', $discount->discountable->series()->pluck('id')->all())
                ->where('discountable_type', 'pump_series')
                ->update(['value' => $request->value]);
        }
        return Redirect::back();
    }
}
