<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emergencies', function (Blueprint $table) {
            $table->foreignId('parte_user_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
        });

        Schema::table('parte_incendios', function (Blueprint $table) {
            $table->foreignId('emergency_id')->nullable()->after('user_id')->unique()->constrained('emergencies')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('parte_incendios', function (Blueprint $table) {
            $table->dropConstrainedForeignId('emergency_id');
        });

        Schema::table('emergencies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parte_user_id');
        });
    }
};
