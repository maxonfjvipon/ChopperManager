<?php

namespace Modules\ProjectParticipant\Http\Requests;

use Exception;

/**
 * Update dealer request.
 * @property string|int $dealer
 */
final class RqUpdateDealer extends RqStoreDealer
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @throws Exception
     */
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                'itn' => ['sometimes', 'nullable', 'max:12', 'unique:dealers,itn,' . $this->dealer],
                'email' => ['sometimes', 'nullable', 'string', 'email', 'max:255', 'unique:dealers,email,' . $this->dealer],
            ]
        );
    }
}
