<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('status');

            $table->bigInteger('area_id')->unsigned();
            $table->bigInteger('created_by')->unsigned();
            $table->bigInteger('installer_id')->unsigned()->nullable();
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->bigInteger('designer_id')->unsigned()->nullable();
            $table->bigInteger('dealer_id')->unsigned()->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('area_id')->references('id')->on('areas')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('installer_id')->references('id')->on('contractors')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('customer_id')->references('id')->on('contractors')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('designer_id')->references('id')->on('contractors')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('dealer_id')->references('id')->on('dealers')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
