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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('tokens')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('qr_code')->nullable();
            $table->unsignedBigInteger('parking_id'); // Relación con el parqueo
            $table->foreign('parking_id')->references('id')->on('parkings')->onDelete('cascade');
            $table->unsignedBigInteger('created_by'); // Usuario (rol 2) que crea el paquete
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
