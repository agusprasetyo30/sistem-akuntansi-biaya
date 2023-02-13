<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimulasiProyeksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simulasi_proyeksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id')->references('id')->on('version_asumsi')->onUpdate('cascade')->onDelete('cascade');
            $table->string('plant_code')->unsigned();
            $table->foreign('plant_code')->references('plant_code')->on('plant')->onDelete("cascade")->onUpdate("cascade");
            $table->string('product_code')->unsigned();
            $table->foreign('product_code')->references('material_code')->on('material')->onDelete("cascade")->onUpdate("cascade");
            $table->string('cost_center')->unsigned();
            $table->foreign('cost_center')->references('cost_center')->on('cost_center')->onDelete("cascade")->onUpdate("cascade");
            $table->integer('no')->nullable();
            $table->integer('kategori')->nullable();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->double('harga_satuan', 8, 2)->nullable();
            $table->double('cr', 8, 2)->nullable();
            $table->double('biaya_perton', 8, 2)->nullable();
            $table->double('total_biaya', 8, 2)->nullable();
            $table->timestamp('periode')->nullable();
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
        Schema::dropIfExists('simulasi_proyeksi');
    }
}
