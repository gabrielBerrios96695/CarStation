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
            $table->unsignedBigInteger('user_id'); // Dueño del parqueo (usuario con rol 2)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
       
        Schema::create('plaza_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plaza_id')->constrained('plazas')->onDelete('cascade');
            $table->timestamp('start_time'); // Hora de inicio de la reserva
            $table->timestamp('end_time');   // Hora de fin de la reserva
            $table->tinyInteger('status')->default(1); // Estado de la reserva (1 = activa, 0 = cancelada)
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
