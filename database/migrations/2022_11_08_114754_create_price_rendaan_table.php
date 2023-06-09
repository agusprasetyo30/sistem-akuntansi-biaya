<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceRendaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_rendaan', function (Blueprint $table) {
            $table->id();
            $table->string('company_code')->unsigned();
            $table->foreign('company_code')->references('company_code')->on('company')->onDelete("cascade")->onUpdate("cascade");
            $table->string('material_code')->unsigned();
            $table->foreign('material_code')->references('material_code')->on('material')->onDelete("cascade")->onUpdate("cascade");
            $table->string('region_name')->unsigned();
            $table->foreign('region_name')->references('region_name')->on('regions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('version_id')->references('id')->on('version_asumsi')->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId('asumsi_umum_id')->references('id')->on('asumsi_umum')->onDelete("cascade")->onUpdate("cascade");
            $table->double('price_rendaan_value', 8, 2)->default(0);
            $table->string('type_currency');
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
        Schema::dropIfExists('price_rendaan');
    }
}
