<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatisticAttributesToProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->bigInteger('status_id')->unsigned()->nullable();
            $table->bigInteger('delivery_status_id')->unsigned()->nullable();
            $table->text('comment')->nullable();

            $table->foreign('status_id')->references('id')->on('project_statuses')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('delivery_status_id')->references('id')->on('project_delivery_statuses')->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('projects_delivery_status_id_foreign');
            $table->dropForeign('projects_status_id_foreign');

            $table->dropColumn('comment');
            $table->dropColumn('delivery_status_id');
            $table->dropColumn('status_id');
        });
    }
}
