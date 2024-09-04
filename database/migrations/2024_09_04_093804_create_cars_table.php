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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categories')->onDelete('cascade');
            $table->string('marca', 50);
            $table->string('modelo', 50);
            $table->string('matricula', 10);
            $table->string('color', 25);
            $table->year('año_fabricacion')->nullable();
            $table->text('otros_datos')->nullable();
            $table->string('imagen_principal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
