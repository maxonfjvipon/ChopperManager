<?php

namespace Tests\Unit;

use App\Support\TxtView;
use Exception;
use Tests\TestCase;

/**
 * @see TxtView
 * @see resources/views/test.blade.php
 *
 * @author Max Trunnikov
 */
class TxtViewTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testAsString()
    {
        $html = <<<EOF
        <!DOCTYPE html>
        <html>
        <head>
            <title>Test</title>
        </head>
        <body>
        <div>Hello world</div>
        </body>
        </html>\n
        EOF;
        $this->assertEquals(
            $html,
            (new TxtView('test', ['text' => 'Hello world']))->asString()
        );
    }
}
