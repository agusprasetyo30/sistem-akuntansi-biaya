<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CretaePjPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pj_penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('company_code')->unsigned();
            $table->foreign('company_code')->references('company_code')->on('company')->onDelete("cascade")->onUpdate("cascade");
            $table->string('material_code')->unsigned();
            $table->foreign('material_code')->references('material_code')->on('material')->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId('version_id')->references('id')->on('version_asumsi')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('asumsi_umum_id')->references('id')->on('asumsi_umum')->onDelete("cascade")->onUpdate("cascade");
            $table->float('pj_penjualan_value');
            $table->string('kode_feature')->nullable();
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
        //
    }
}
