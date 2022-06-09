<?php

namespace Modules\Pump\Tests\Unit;

use App\Support\Rates\FakeRates;
use Exception;
use Modules\Project\Entities\Currency;
use Modules\Pump\Entities\Pump;
use Modules\PumpSeries\Entities\PumpSeries;
use Modules\Pump\Entities\PumpsPriceList;
use Modules\User\Entities\Country;
use Modules\User\Entities\Discount;
use Modules\User\Entities\User;
use Tests\TestCase;

/**
 * @see Pump::currentPrices()
 * @author Max Trunnikov
 */
class PumpCurrentPricesTest extends TestCase
{
    /**
     * @return void
     */
    public function test_without_price_list()
    {
        $this->actingAs(User::fakeWithRole());
        $this->assertEquals(
            ['simple' => 0, 'discounted' => 0],
            Pump::factory()->create()->currentPrices(new FakeRates())
        );
    }

    /**
     * @return void
     * @throws Exception
     */
    public function test_with_price_list_for_different_country_without_discounts_with_the_same_base()
    {
        $pump = Pump::factory()->create();
        $currency = Currency::allOrCached()->random();
        $countryId = Country::allOrCached()->first()->id;
        $diffCountryId = Country::allOrCached()->last()->id;
        PumpsPriceList::factory()->create([
            'pump_id' => $pump->id,
            'currency_id' => $currency->id,
            'country_id' => $countryId,
            'price' => 100
        ]);
        $user = User::factory()->create([
            'country_id' => $diffCountryId,
            'currency_id' => $currency->id
        ]);
        $this->actingAs($user);
        $this->assertEquals(
            ['simple' => 0, 'discounted' => 0],
            $pump->currentPrices(new FakeRates($currency->code))
        );
    }

    /**
     * @return void
     * @throws Exception
     */
    public function test_with_price_list_without_discounts_with_the_same_base()
    {
        $pump = Pump::factory()->create();
        $currency = Currency::allOrCached()->random();
        $countryId = Country::allOrCached()->random()->id;
        PumpsPriceList::factory()->create([
            'pump_id' => $pump->id,
            'currency_id' => $currency->id,
            'country_id' => $countryId,
            'price' => $price = 100
        ]);
        $user = User::factory()->create([
            'country_id' => $countryId,
            'currency_id' => $currency->id
        ]);
        $this->actingAs($user);
        $this->assertEquals(
            ['simple' => $price, 'discounted' => $price],
            $pump->currentPrices(new FakeRates($currency->code))
        );
    }

    /**
     * @return void
     * @throws Exception
     */
    public function test_with_price_list_without_discounts_with_not_the_same_base()
    {
        $pump = Pump::factory()->create();
        $firstCurrency = Currency::allOrCached()->first();
        $lastCurrency = Currency::allOrCached()->last();
        $countryId = Country::allOrCached()->random()->id;
        PumpsPriceList::factory()->create([
            'pump_id' => $pump->id,
            'currency_id' => $firstCurrency->id,
            'country_id' => $countryId,
            'price' => $hundred = 100
        ]);
        $user = User::factory()->create([
            'country_id' => $countryId,
            'currency_id' => $firstCurrency->id
        ]);
        $five = $hundred / ($twenty = 20);
        $this->actingAs($user);
        $this->assertEquals(
            ['simple' => $five, 'discounted' => $five],
            $pump->currentPrices(new FakeRates($lastCurrency->code, $twenty))
        );
    }

    /**
     * @return void
     * @throws Exception
     */
    public function test_with_price_list_with_discounts_with_the_same_base()
    {
        $pump = Pump::factory()->create();
        $currency = Currency::allOrCached()->random();
        $countryId = Country::allOrCached()->random()->id;
        PumpsPriceList::factory()->create([
            'pump_id' => $pump->id,
            'currency_id' => $currency->id,
            'country_id' => $countryId,
            'price' => $hundred = 100
        ]);
        $user = User::factory()->create([
            'country_id' => $countryId,
            'currency_id' => $currency->id
        ]);
        Discount::updateForUser([$pump->series->id], $user);
        Discount::where('user_id', $user->id)->update(['value' => $twenty = 20]);
        $this->actingAs($user);
        $this->assertEquals(
            ['simple' => $hundred, 'discounted' => $hundred - ($hundred * $twenty) / 100],
            $pump->currentPrices(new FakeRates($currency->code))
        );
    }
}
