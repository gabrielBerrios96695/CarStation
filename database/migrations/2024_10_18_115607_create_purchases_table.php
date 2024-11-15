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
            $table->string('description');
            $table->unsignedBigInteger('package_id'); 
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->tinyInteger('status')->default(2); 
            $table->decimal('amount', 10, 2);
            $table->unsignedInteger('hours_purchases');
            $table->unsignedInteger('hours'); // Nuevo campo para almacenar las horas del paquete comprado
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
