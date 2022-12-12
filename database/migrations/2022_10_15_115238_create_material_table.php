<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material', function (Blueprint $table) {
            $table->string('material_code')->primary();
            $table->string('company_code')->unsigned();
            $table->foreign('company_code')->references('company_code')->on('company');
            $table->foreignId('kategori_material_id')->references('id')->on('kategori_material')->onUpdate('cascade')->onDelete('cascade');
            $table->string('group_account_code')->unsigned();
            $table->foreign('group_account_code')->references('group_account_code')->on('group_account');
            $table->string('material_produk_name')->nullable();
            $table->text('material_produk_desc')->nullable();
            $table->string('material_produk_uom')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_dummy')->default(true);
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
        Schema::dropIfExists('material_produk');
    }
}
