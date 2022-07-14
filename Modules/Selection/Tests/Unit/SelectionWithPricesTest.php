<?php

namespace Modules\Selection\Tests\Unit;

use App\Support\Rates\FakeRates;
use Exception;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Project\Entities\Currency;
use Modules\Project\Entities\Project;
use Modules\Pump\Entities\Pump;
use Modules\Pump\Entities\PumpsPriceList;
use Modules\Selection\Entities\Selection;
use Modules\User\Entities\Country;
use Modules\User\Entities\Discount;
use Modules\User\Entities\User;
use Tests\TestCase;

/**
 * @see Selection::withPrices()
 *
 * @author Max Trunnikov
 */
class SelectionWithPricesTest extends TestCase
{
    use WithFaker;

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testSinglePumpSelectionWithPrices()
    {
        $count = $this->faker->numberBetween(1, 8);
        $pump = Pump::factory()->create(['pumpable_type' => Pump::$SINGLE_PUMP]);
        $currency = Currency::allOrCached()->random();
        $countryId = Country::allOrCached()->random()->id;
        PumpsPriceList::factory()->create([
            'pump_id' => $pump->id,
            'currency_id' => $currency->id,
            'country_id' => $countryId,
            'price' => $hundred = 100,
        ]);
        $selection = Selection::factory()->create([
            'pump_id' => $pump->id,
            'pumps_count' => $count,
            'project_id' => Project::fakeForUser(
                $user = User::factory()->create([ // user with
                    'country_id' => $countryId,
                    'currency_id' => $currency->id,
                ])
            )->id,
        ]);
        Discount::updateForUser([$pump->series->id], $user); // user has discount
        Discount::where('user_id', $user->id)->update(['value' => $twenty = 20]); // user has 20% discount
        $this->actingAs($user);
        $selection = $selection->withPrices(new FakeRates($currency->code));

        $this->assertEquals($hundred, $selection->retail_price);
        $this->assertEquals($hundred * $count, $selection->total_retail_price);
        $this->assertEquals($hundred - ($hundred * $twenty) / 100, $selection->discounted_price);
        $this->assertEquals(($hundred - ($hundred * $twenty) / 100) * $count, $selection->total_discounted_price);
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testDoublePumpSelectionWithPrices()
    {
        $pump = Pump::factory()->create(['pumpable_type' => Pump::$DOUBLE_PUMP]);
        $currency = Currency::allOrCached()->random();
        $countryId = Country::allOrCached()->random()->id;
        PumpsPriceList::factory()->create([
            'pump_id' => $pump->id,
            'currency_id' => $currency->id,
            'country_id' => $countryId,
            'price' => $hundred = 100,
        ]);
        $selection = Selection::factory()->create([
            'pump_id' => $pump->id,
            'project_id' => Project::fakeForUser(
                $user = User::factory()->create([ // user with
                    'country_id' => $countryId,
                    'currency_id' => $currency->id,
                ])
            )->id,
        ]);
        Discount::updateForUser([$pump->series->id], $user); // user has discount
        Discount::where('user_id', $user->id)->update(['value' => $twenty = 20]); // user has 20% discount
        $this->actingAs($user);
        $selection = $selection->withPrices(new FakeRates($currency->code));

        $this->assertEquals($hundred, $selection->retail_price);
        $this->assertEquals($hundred, $selection->total_retail_price);
        $this->assertEquals($hundred - ($hundred * $twenty) / 100, $selection->discounted_price);
        $this->assertEquals(($hundred - ($hundred * $twenty) / 100), $selection->total_discounted_price);
    }
}
