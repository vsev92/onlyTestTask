<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('positions_car_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_id')->constrained('staff_positions')->onDelete('cascade');
            $table->foreignId('car_comfort_class_id')->constrained('car_comfort_classes')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['position_id', 'car_comfort_class_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions_car_classes');
    }
};
