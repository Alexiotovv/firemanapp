<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('profiles', 'nombres_apellidos')) {
                $table->string('nombres_apellidos')->nullable()->after('user_id')->comment('Nombres y apellidos');
            }
            if (!Schema::hasColumn('profiles', 'dni')) {
                $table->string('dni')->nullable()->after('correo_personal')->comment('Documento Nacional de Identidad');
            }
        });
    }

    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (Schema::hasColumn('profiles', 'dni')) {
                $table->dropColumn('dni');
            }
            if (Schema::hasColumn('profiles', 'nombres_apellidos')) {
                $table->dropColumn('nombres_apellidos');
            }
            if (Schema::hasColumn('profiles', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropIndex(['user_id']);
            }
        });

        Schema::table('profiles', function (Blueprint $table) {
            if (Schema::hasColumn('profiles', 'user_id')) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();
            }
        });
    }
};
