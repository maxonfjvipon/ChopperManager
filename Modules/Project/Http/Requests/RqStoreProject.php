<?php

namespace Modules\Project\Http\Requests;

use BenSampo\Enum\Rules\EnumValue;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\In;
use Modules\Project\Entities\ProjectStatus;
use Modules\Project\Rules\ProjectContractorRegex;
use Modules\ProjectParticipant\Entities\Contractor;
use Modules\ProjectParticipant\Entities\Dealer;

/**
 * Store project request.
 *
 * @property string|null $installer
 * @property string|null $customer
 * @property string|null $designer
 * @property string $name
 * @property int $area_id
 * @property int $status
 * @property string $description
 * @property int $dealer_id
 */
class RqStoreProject extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @throws Exception
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:255'],
            'area_id' => ['required', 'exists:areas,id'],
            'status' => ['required', new EnumValue(ProjectStatus::class)],
            'description' => ['sometimes', 'nullable', 'string'],
            'installer' => ['sometimes', 'nullable', new ProjectContractorRegex()],
            'customer' => ['sometimes', 'nullable', new ProjectContractorRegex()],
            'designer' => ['sometimes', 'nullable', new ProjectContractorRegex()],
            'dealer_id' => ['sometimes', 'nullable', new In(Dealer::allOrCached()->pluck('id')->all())],
        ];
    }

    /**
     * @return array
     */
    public function projectProps(): array
    {
        return [
            'name' => $this->name,
            'area_id' => $this->area_id,
            'status' => $this->status,
            'description' => $this->description,
            'installer_id' => Contractor::getOrCreateFrom($this->installer)?->id,
            'customer_id' => Contractor::getOrCreateFrom($this->customer)?->id,
            'designer_id' => Contractor::getOrCreateFrom($this->designer)?->id,
            'dealer_id' => $this->dealer_id ?? $this->user()->dealer->id
        ];
    }
}
