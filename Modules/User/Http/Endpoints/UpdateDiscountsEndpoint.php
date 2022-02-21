<?php

namespace Modules\User\Http\Endpoints;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Modules\User\Entities\Discount;
use Modules\User\Http\Requests\DiscountUpdateRequest;

/**
 * Update discounts endpoint
 */
final class UpdateDiscountsEndpoint extends Controller
{
    /**
     * @param DiscountUpdateRequest $request
     * @return RedirectResponse
     */
    public function __invoke(DiscountUpdateRequest $request): RedirectResponse
    {
        $discount = Discount::where('user_id', $request->user_id)
            ->where('discountable_id', $request->discountable_id)
            ->where('discountable_type', $request->discountable_type)
            ->first();
        $discount->update($request->validated());

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
