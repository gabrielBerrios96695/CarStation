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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('user_id');
            $table->unsignedBigInteger('package_id'); // Paquete comprado
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->tinyInteger('status')->default(1); // 1: pendiente, 2: completa, 3: cancelada
            $table->decimal('amount', 10, 2); // Monto pagado
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
