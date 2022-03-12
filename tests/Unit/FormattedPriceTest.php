<?php

namespace Tests\Unit;

use App\Support\FormattedPrice;
use Exception;
use Tests\TestCase;

class FormattedPriceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_as_string()
    {
        $this->assertEquals(
            "1 123 456,78",
            (new FormattedPrice(1123456.78))->asString()
        );
        $this->assertEquals(
            "1 123,9",
            (new FormattedPrice(1123.91231, 1))->asString()
        );
        $this->assertEquals(
            0,
            (new FormattedPrice(0, 3))->asString()
        );
    }
}
