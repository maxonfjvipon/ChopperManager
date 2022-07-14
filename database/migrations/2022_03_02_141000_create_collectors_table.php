<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collectors', function (Blueprint $table) {
            $table->id();
            $table->string('article')->unique();
            $table->integer('pipes_count')->unsigned();
            $table->float('price')->unsigned();
            $table->timestamp('price_updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->float('weight')->unsigned();
            $table->integer('length')->unsigned();
            $table->integer('pipes_length')->unsigned();

            $table->softDeletes();

            $table->unsignedTinyInteger('material');
            $table->unsignedTinyInteger('type');
            $table->unsignedSmallInteger('dn_common');
            $table->unsignedSmallInteger('dn_pipes');
            $table->unsignedTinyInteger('connection_type');
            $table->unsignedTinyInteger('currency');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collectors');
    }
}
