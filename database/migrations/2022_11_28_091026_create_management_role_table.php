<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagementRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('management_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId('role_id')->references('id')->on('role')->onDelete("cascade")->onUpdate("cascade");
            $table->string('username')->nullable();
            $table->string('login_method')->default('DB')->nullable();
            $table->string('kode_feature')->nullable()->nullable();
            $table->string('company_code')->unsigned()->nullable();
            $table->foreign('company_code')->references('company_code')->on('company')->onDelete("cascade")->onUpdate("cascade");
            $table->boolean('create')->default(0)->nullable();
            $table->boolean('read')->default(0)->nullable();
            $table->boolean('update')->default(0)->nullable();
            $table->boolean('delete')->default(0)->nullable();
            $table->boolean('approve')->default(0)->nullable();
            $table->boolean('submit')->default(0)->nullable();
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
        Schema::dropIfExists('management_role');
    }
}
