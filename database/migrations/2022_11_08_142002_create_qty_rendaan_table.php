<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQtyRendaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qty_rendaan', function (Blueprint $table) {
            $table->id();
            $table->string('company_code')->unsigned();
            $table->foreign('company_code')->references('company_code')->on('company');
            $table->string('material_produk_code')->unsigned();
            $table->foreign('material_produk_code')->references('material_produk_code')->on('material_produk');
            $table->foreignId('region_id')->references('id')->on('regions')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamp('month_year');
            $table->string('qty_rendaan_desc');
            $table->float('qty_rendaan_value');
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
        Schema::dropIfExists('qty_rendaan');
    }
}
