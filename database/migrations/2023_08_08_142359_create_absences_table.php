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
        Schema::create("absences", function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger("user_id");
            $table
                ->foreignId("reservation_id")
                ->constrained("reservations")
                ->cascadeOnDelete();

            $table->date("date")->index();

            $table->unique(["reservation_id", "date"]);

            $table
                ->foreign("user_id")
                ->references("id")
                ->on("users")
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("absences");
    }
};
