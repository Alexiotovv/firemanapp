<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('availability_items');
        Schema::dropIfExists('availability_statuses');

        Schema::create('availability_elements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('availability_categories')->cascadeOnDelete();
            $table->string('nombre');
            $table->timestamps();
        });

        Schema::create('availability_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('element_id')->constrained('availability_elements')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('disponible')->default(false);
            $table->timestamps();
            $table->unique(['element_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('availability_checks');
        Schema::dropIfExists('availability_elements');
    }
};