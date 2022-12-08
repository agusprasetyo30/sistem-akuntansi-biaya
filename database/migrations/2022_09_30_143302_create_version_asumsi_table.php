<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVersionAsumsiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('version_asumsi', function (Blueprint $table) {
            $table->id();
            $table->string('version')->unique();
            $table->integer('data_bulan');
            $table->timestamp('awal_periode');
            $table->timestamp('akhir_periode');
            $table->timestamp('saldo_awal');
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
        Schema::dropIfExists('version_asumsi');
    }
}
