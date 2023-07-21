<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("pauses", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("reservation_id")
                ->references("id")
                ->on("reservations");
            $table->unsignedSmallInteger("paused_by_id");
            $table->date("date")->index();
            $table->timestamps();

            $table->unique(["date", "reservation_id"]);

            $table
                ->foreign("paused_by_id")
                ->references("id")
                ->on("users");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("pauses");
    }
};
