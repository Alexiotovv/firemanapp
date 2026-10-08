<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('availability_categories', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('icono')->default('bi-truck');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('availability_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('color')->default('secondary'); // success, warning, danger, secondary, info
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();
        });

        Schema::create('availability_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('availability_categories')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nombre');
            $table->foreignId('status_id')->nullable()->constrained('availability_statuses')->nullOnDelete();
            $table->string('nota')->nullable();
            $table->timestamps();
        });

        $now = now();
        DB::table('availability_statuses')->insert([
            ['nombre' => 'Disponible', 'color' => 'success', 'orden' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'No disponible', 'color' => 'warning', 'orden' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'En emergencia', 'color' => 'danger', 'orden' => 3, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('availability_items');
        Schema::dropIfExists('availability_statuses');
        Schema::dropIfExists('availability_categories');
    }
};
