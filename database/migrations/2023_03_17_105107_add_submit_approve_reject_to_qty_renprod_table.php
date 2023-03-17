<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubmitApproveRejectToQtyRenprodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qty_renprod', function (Blueprint $table) {
            $table->dateTime('submited_at')->nullable();
            $table->integer('submited_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->integer('approved_by')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->integer('rejected_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qty_renprod', function (Blueprint $table) {
            //
        });
    }
}
