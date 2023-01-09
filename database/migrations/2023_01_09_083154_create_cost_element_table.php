<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostElementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cost_element', function (Blueprint $table) {
            $table->string('cost_element')->primary();
            $table->string('cost_element_desc');
            $table->string('company_code')->unsigned();
            $table->foreign('company_code')->references('company_code')->on('company');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cost_element');
    }
}
