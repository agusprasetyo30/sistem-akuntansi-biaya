<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKategoriBalansDescToBalansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('balans', function (Blueprint $table) {
            $table->string('kategori_balans_desc')->nullable();
            $table->foreignId('version_id')->default(1)->references('id')->on('version_asumsi')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('order_view')->nullable();
            $table->string('material_name')->nullable();
            $table->timestamp('month_year')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('balans', function (Blueprint $table) {
            //
        });
    }
}
