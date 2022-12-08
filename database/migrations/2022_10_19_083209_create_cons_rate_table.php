<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cons_rate', function (Blueprint $table) {
            $table->id();
            $table->string('company_code')->unsigned();
            $table->foreign('company_code')->references('company_code')->on('company');
            $table->string('plant_code')->unsigned();
            $table->foreign('plant_code')->references('plant_code')->on('plant');
            $table->string('cons_rate');
            $table->string('cons_rate_desc');
            $table->boolean('is_active')->default(true);
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cons_rate');
    }
}
