<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salrs', function (Blueprint $table) {
            $table->id();
            $table->string('cost_center')->unsigned();
            $table->foreign('cost_center')->references('cost_center')->on('cost_center');
            $table->string('group_account_fc')->unsigned();
            $table->foreign('group_account_fc')->references('group_account_fc')->on('group_account_fc');
            $table->string('gl_account_fc')->unsigned();
            $table->foreign('gl_account_fc')->references('gl_account_fc')->on('gl_account_fc');
            $table->string('company_code')->unsigned();
            $table->foreign('company_code')->references('company_code')->on('company');
            $table->timestamp('periode');
            $table->double('value', 8, 2);
            $table->string('name')->nullable();
            $table->string('partner_cost_center')->nullable();
            $table->string('username')->nullable();
            $table->string('material_code')->nullable();
            $table->string('document_number')->nullable();
            $table->string('document_number_text')->nullable();
            $table->string('purchase_order')->nullable();
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
        Schema::dropIfExists('salrs');
    }
}
