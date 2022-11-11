<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsumsiUmumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asumsi_umum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->references('id')->on('periode')->onUpdate('cascade')->onDelete('cascade');
            $table->float('kurs');
            $table->float('handling_bb');
            $table->float('data_saldo_awal');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asumsi_umum');
    }
}