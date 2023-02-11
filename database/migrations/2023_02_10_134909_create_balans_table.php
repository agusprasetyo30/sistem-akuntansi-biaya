<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balans', function (Blueprint $table) {
            $table->id();
            $table->string('plant_code')->unsigned();
            $table->foreign('plant_code')->references('plant_code')->on('plant')->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId('kategori_balans_id')->references('id')->on('kategori_balans')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('asumsi_umum_id')->references('id')->on('asumsi_umum')->onDelete("cascade")->onUpdate("cascade");
            $table->string('company_code')->unsigned();
            $table->foreign('company_code')->references('company_code')->on('company')->onDelete("cascade")->onUpdate("cascade");
            $table->double('q', 8, 2);
            $table->double('p', 8, 2);
            $table->double('nilai', 8, 2);
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
        Schema::dropIfExists('balans');
    }
}
