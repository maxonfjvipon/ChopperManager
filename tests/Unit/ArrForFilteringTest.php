<?php

namespace Tests\Unit;

use App\Support\ArrForFiltering;
use Exception;
use Tests\TestCase;

class ArrForFilteringTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_as_array()
    {
        $expected = [
            'props1' => [
                [
                    'text' => 'foo',
                    'value' => 'foo'
                ], [
                    'text' => 'bar',
                    'value' => 'bar'
                ]
            ],
            'props2' => [
                [
                    'text' => 'foobar',
                    'value' => 'foobar',
                ], [
                    'text' => 'barfoo',
                    'value' => 'barfoo'
                ]
            ]
        ];
        $this->assertEquals(
            $expected,
            (new ArrForFiltering([
                'props1' => ['foo', 'bar'],
                'props2' => ['foobar', 'barfoo']
            ]))->asArray()
        );
    }
}
