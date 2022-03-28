<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArmaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('armature', function (Blueprint $table) {
            $table->id();
            $table->string("article", 30)->unique();
            $table->float("length");
            $table->float('weight');
            $table->float('price');
            $table->timestamp('price_updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->softDeletes();

            $table->bigInteger('dn_id')->unsigned();
            $table->bigInteger('type_id')->unsigned();
            $table->bigInteger('connection_type_id')->unsigned();
            $table->bigInteger('currency_id')->unsigned();

            $table->foreign('dn_id')->references('id')->on('dns')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('type_id')->references('id')->on('armature_types')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('connection_type_id')->references('id')->on('connection_types')->cascadeOnDelete()->cascadeOnDelete();
            $table->foreign('currency_id')->references('id')->on('currencies')->cascadeOnDelete()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('armature');
    }
}
