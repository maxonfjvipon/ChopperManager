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
            $table->string('article', 30)->unique();
            $table->integer('length');
            $table->float('weight');
            $table->float('price');
            $table->timestamp('price_updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->unsignedTinyInteger('type');

            $table->softDeletes();

            $table->unsignedSmallInteger('dn');
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
        Schema::dropIfExists('armature');
    }
}
