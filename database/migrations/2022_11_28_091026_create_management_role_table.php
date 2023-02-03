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
            $table->string('username');
            $table->string('login_method')->default('DB');
            $table->string('company_code')->unsigned();
            $table->foreign('company_code')->references('company_code')->on('company')->onDelete("cascade")->onUpdate("cascade");
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
