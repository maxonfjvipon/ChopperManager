<?php

use App\Models\Enums\Currency;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Pump\Entities\CollectorSwitch;
use Modules\Pump\Entities\ConnectionType;
use Modules\Pump\Entities\DN;
use Modules\Pump\Entities\PumpOrientation;

class CreatePumpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pumps', function (Blueprint $table) {
            $table->id();
            $table->string('article')->unique();
            $table->string('name');
            $table->float('price');
            $table->timestamp('price_updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->float('power')->unsigned();
            $table->float('current')->unsigned();
            $table->string('size')->comment('LENGTHxHEIGHTxWIDTH');
            $table->integer('suction_height')->unsigned();
            $table->integer('ptp_length')->unsigned();
            $table->float('weight')->unsigned();
            $table->text('performance');
            $table->boolean('is_discontinued')->default(false);

            $table->softDeletes();

            $table->unsignedSmallInteger('dn_suction');
            $table->unsignedSmallInteger('dn_pressure');
            $table->unsignedTinyInteger('connection_type');
            $table->unsignedTinyInteger('collector_switch');
            $table->unsignedTinyInteger('orientation');

            $table->bigInteger('series_id')->unsigned();

            $table->foreign('series_id')->references('id')->on('pump_series')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pumps');
    }
}
