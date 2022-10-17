<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger("user_id");
            $table->unsignedSmallInteger("service_id");

            $table->foreign("user_id")->references("id")->on("users")->cascadeOnDelete();
            $table->foreign("service_id")->references("id")->on("services")->cascadeOnDelete();

            $table->unique(["service_id", "user_id"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_user');
    }
};
