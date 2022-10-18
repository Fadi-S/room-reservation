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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger("service_id");
            $table->unsignedSmallInteger("room_id");
            $table->unsignedSmallInteger("user_id");
            $table->string("description")->nullable();
            $table->unsignedSmallInteger("approved_by_id")->nullable();
            $table->time("start");
            $table->time("end");
            $table->date("date")->nullable();
            $table->unsignedTinyInteger("day_of_week");
            $table->boolean("is_repeating")->default(false)->index();
            $table->timestamp("stopped_at")->nullable();
            $table->timestamp("approved_at")->nullable();

            $table->timestamps();

            $table->index(["stopped_at", "approved_at", "date"]); // To render the whole week calendar

            $table->index(["date", "start"]); // To render the whole day calendar
            $table->index(["day_of_week", "start"]);

            $table->index(["room_id", "date", "start"]); // To render the room calendar in a certain day
            $table->index(["room_id", "day_of_week", "start"]);

            $table->foreign("service_id")->references("id")->on("services")->cascadeOnDelete();
            $table->foreign("room_id")->references("id")->on("rooms")->cascadeOnDelete();
            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("approved_by_id")->references("id")->on("users");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};
