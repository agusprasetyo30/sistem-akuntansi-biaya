<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupAccountFcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_account_fc', function (Blueprint $table) {
            $table->string('group_account_fc')->primary();
            $table->string('group_account_fc_desc');
            $table->string('company_code')->unsigned();
            $table->foreign('company_code')->references('company_code')->on('company')->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('group_account_fc');
    }
}
