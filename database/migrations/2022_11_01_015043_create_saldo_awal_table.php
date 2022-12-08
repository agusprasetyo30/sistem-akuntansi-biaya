<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaldoAwalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saldo_awal', function (Blueprint $table) {
            $table->id();
            $table->string('company_code')->unsigned();
            $table->foreign('company_code')->references('company_code')->on('company');
            $table->timestamp('month_year')->nullable();
            $table->string('gl_account')->nullable();
            $table->string('valuation_class')->nullable();
            $table->string('price_control')->nullable();
            $table->string('material_produk_code')->unsigned();
            $table->foreign('material_produk_code')->references('material_produk_code')->on('material_produk');
            $table->string('plant_code')->unsigned();
            $table->foreign('plant_code')->references('plant_code')->on('plant');
            $table->string('total_stock')->nullable();
            $table->string('total_value')->nullable();
            $table->string('nilai_satuan')->nullable();
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
        Schema::dropIfExists('saldo_awal');
    }
}
