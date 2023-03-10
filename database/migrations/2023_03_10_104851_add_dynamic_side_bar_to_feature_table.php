<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDynamicSideBarToFeatureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feature', function (Blueprint $table) {
            $table->integer('order_view')->nullable();
            $table->string('route')->nullable();
            $table->string('icons')->nullable();
            $table->text('icons_svg')->nullable();
            $table->string('feature_kode')->nullable();
            $table->boolean('list')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feature', function (Blueprint $table) {
            //
        });
    }
}
