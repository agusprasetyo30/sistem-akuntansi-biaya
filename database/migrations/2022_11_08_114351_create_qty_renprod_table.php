<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQtyRenProdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qty_renprod', function (Blueprint $table) {
            $table->id();
            $table->string('company_code')->unsigned();
            $table->foreign('company_code')->references('company_code')->on('company')->onDelete("cascade")->onUpdate("cascade");
            $table->string('cost_center')->unsigned();
            $table->foreign('cost_center')->references('cost_center')->on('cost_center')->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId('version_id')->references('id')->on('version_asumsi')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('asumsi_umum_id')->references('id')->on('asumsi_umum')->onDelete("cascade")->onUpdate("cascade");
            $table->float('qty_renprod_value');
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
        Schema::dropIfExists('qty_ren_prod');
    }
}
