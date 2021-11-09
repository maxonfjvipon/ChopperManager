<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTenantAndSelectionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('tenants_and_selection_types');
        Schema::create('tenants_and_selection_types', function (Blueprint $table) {
            $table->bigInteger('tenant_id')->unsigned();
            $table->bigInteger('type_id')->unsigned();
            $table->string('img')->nullable();
            $table->primary(['tenant_id', 'type_id']);

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('type_id')->references('id')->on('selection_types')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenants_and_selection_types');
    }
}
