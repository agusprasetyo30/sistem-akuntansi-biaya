<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropDataSaldoAwalFromAsumsiUmumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asumsi_umum', function (Blueprint $table) {
            $table->dropColumn('data_saldo_awal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asumsi_umum', function (Blueprint $table) {
            //
        });
    }
}
