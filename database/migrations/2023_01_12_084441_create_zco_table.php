<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZcoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zco', function (Blueprint $table) {
            $table->id();
            $table->string('company_code')->unsigned();
            $table->foreign('company_code')->references('company_code')->on('company')->onDelete("cascade")->onUpdate("cascade");
            $table->string('plant_code')->unsigned();
            $table->foreign('plant_code')->references('plant_code')->on('plant')->onDelete("cascade")->onUpdate("cascade");
            $table->timestamp('periode')->nullable();
            $table->string('product_code')->unsigned();
            $table->foreign('product_code')->references('material_code')->on('material')->onDelete("cascade")->onUpdate("cascade");
            $table->double('product_qty', 8, 2)->default(0)->nullable();
            $table->string('cost_element')->unsigned();
            $table->foreign('cost_element')->references('gl_account')->on('gl_account')->onDelete("cascade")->onUpdate("cascade");
            $table->string('material_code')->nullable();
            $table->double('total_qty', 8, 2)->default(0)->nullable();
            $table->string('currency')->nullable();
            $table->double('total_amount', 8, 2)->default(0)->nullable();
            $table->double('unit_price_product', 8, 2)->default(0)->nullable();
            $table->dateTime('created_at');
            $table->integer('created_by');
            $table->dateTime('updated_at')->nullable();
            $table->integer('updated_by')->nullable();
            $table->dateTime('deleted_at')->nullable();
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
        Schema::dropIfExists('zco');
    }
}
