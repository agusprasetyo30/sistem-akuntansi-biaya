<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cons_rate', function (Blueprint $table) {
            $table->id();
            $table->string('plant_code')->unsigned();
            $table->foreign('plant_code')->references('plant_code')->on('plant')->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId('version_id')->references('id')->on('version_asumsi')->onDelete("cascade")->onUpdate("cascade");
            $table->string('product_code')->unsigned();
            $table->foreign('product_code')->references('material_code')->on('material')->onDelete("cascade")->onUpdate("cascade");
            $table->string('material_code')->unsigned();
            $table->foreign('material_code')->references('material_code')->on('material')->onDelete("cascade")->onUpdate("cascade");
            $table->string('company_code')->unsigned();
            $table->foreign('company_code')->references('company_code')->on('company')->onDelete("cascade")->onUpdate("cascade");
            $table->double('cons_rate', 8, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamp('month_year')->nullable();
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
        Schema::dropIfExists('cons_rate');
    }
}
