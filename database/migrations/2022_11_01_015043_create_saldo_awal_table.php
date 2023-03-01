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
            $table->foreign('company_code')->references('company_code')->on('company')->onDelete("cascade")->onUpdate("cascade");
            $table->timestamp('month_year')->nullable();
            $table->string('gl_account')->unsigned();
            $table->foreign('gl_account')->references('gl_account')->on('gl_account')->onDelete("cascade")->onUpdate("cascade");
            $table->string('valuation_class')->nullable();
            $table->string('price_control')->nullable();
            $table->string('material_code')->unsigned();
            $table->foreign('material_code')->references('material_code')->on('material')->onDelete("cascade")->onUpdate("cascade");
            $table->string('plant_code')->unsigned();
            $table->foreign('plant_code')->references('plant_code')->on('plant')->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId('version_id')->references('id')->on('version_asumsi')->onUpdate('cascade')->onDelete('cascade');
            $table->double('total_stock', 8, 2)->nullable();
            $table->double('total_value', 8, 2)->nullable();
            $table->double('nilai_satuan', 8, 2)->nullable();
            $table->string('kode_future')->nullable();
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
