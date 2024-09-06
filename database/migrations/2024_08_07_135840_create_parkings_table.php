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
        Schema::create('parkings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);

            $table->tinyInteger('status')->default(1);
            $table->time('opening_time');
            $table->time('closing_time');
            $table->timestamps();
        });
        Schema::create('plazas', function (Blueprint $table) {
            $table->id(); // ID de la plaza
            $table->foreignId('parking_id')->constrained('parkings')->onDelete('cascade'); // Relación con la tabla parkings

            $table->timestamps();
        });
       
        Schema::create('parking_spaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parking_id')->constrained('parkings')->onDelete('cascade'); // Foreign key to 'parkings'
            $table->string('space_number'); // Identifier for each parking space
            $table->tinyInteger('status')->default(1); // 1: Free, 0: Occupied
            $table->timestamps();
        });
        

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parkings');
    }
};
