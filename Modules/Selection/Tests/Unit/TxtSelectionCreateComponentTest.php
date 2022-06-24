<?php

namespace Modules\Selection\Tests\Unit;

use Exception;
use Illuminate\Http\Request;
use Modules\Pump\Entities\Pump;
use Modules\Selection\Support\TxtSelectionComponent;
use Tests\TestCase;

class TxtSelectionCreateComponentTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_as_string()
    {
        $req = new Request();
        $req->pumpable_type = Pump::$SINGLE_PUMP;
        $this->assertEquals(
            "Selection::SinglePump",
            (new TxtSelectionComponent($req))->asString()
        );
        $req->pumpable_type = Pump::$DOUBLE_PUMP;
        $this->assertEquals(
            "Selection::DoublePump",
            (new TxtSelectionComponent($req))->asString()
        );
    }
}
