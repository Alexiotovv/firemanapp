<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('availability_checks', function (Blueprint $table) {
            $table->boolean('en_emergencia')->default(false)->after('disponible');
        });

        Schema::create('emergency_element', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emergency_id')->constrained()->cascadeOnDelete();
            $table->foreignId('element_id')->constrained('availability_elements')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['emergency_id', 'element_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_element');
        Schema::table('availability_checks', function (Blueprint $table) {
            $table->dropColumn('en_emergencia');
        });
    }
};
