<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emergencies', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->string('tipo');
            $table->string('prioridad')->default('media');
            $table->string('estado')->default('en_evaluacion');
            $table->string('direccion');
            $table->string('referencia')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('llamante')->nullable();
            $table->string('telefono')->nullable();
            $table->string('unidades')->nullable();
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergencies');
    }
};