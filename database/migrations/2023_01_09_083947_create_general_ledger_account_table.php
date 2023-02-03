<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralLedgerAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gl_account_fc', function (Blueprint $table) {
            $table->string('gl_account_fc')->primary();
            $table->string('gl_account_fc_desc');
            $table->string('company_code')->unsigned();
            $table->foreign('company_code')->references('company_code')->on('company')->onDelete("cascade")->onUpdate("cascade");
            $table->string('group_account_fc')->unsigned();
            $table->foreign('group_account_fc')->references('group_account_fc')->on('group_account_fc')->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('gl_account_fc');
    }
}
