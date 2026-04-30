<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Información profesional
            $table->string('codigo')->nullable()->after('is_admin')->comment('Código o Matrícula del bombero');
            $table->string('grados')->nullable()->after('codigo')->comment('Grados o Rango');
            $table->date('fecha_asenso')->nullable()->after('grados')->comment('Fecha del último ascenso');
            $table->date('fecha_graduacion')->nullable()->after('fecha_asenso')->comment('Fecha de graduación');
            
            // Capacitación
            $table->string('curso_basicos')->nullable()->after('fecha_graduacion')->comment('Cursos Básicos completados');
            $table->string('curso_tecnicos')->nullable()->after('curso_basicos')->comment('Cursos Técnicos completados');
            $table->string('curso_liderazgo')->nullable()->after('curso_tecnicos')->comment('Cursos de Liderazgo completados');
            
            // Información de contacto
            $table->string('telefono')->nullable()->after('curso_liderazgo')->comment('Teléfono de contacto');
            $table->string('ubo')->nullable()->after('telefono')->comment('Unidad Base de Operaciones');
            $table->string('correo_personal')->nullable()->after('ubo')->comment('Correo electrónico personal');
            
            // Información adicional
            $table->string('ultimo_cargo')->nullable()->after('correo_personal')->comment('Último cargo ocupado');
            $table->string('tipo_sangre')->nullable()->after('ultimo_cargo')->comment('Tipo de sangre');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'codigo',
                'grados',
                'fecha_asenso',
                'fecha_graduacion',
                'curso_basicos',
                'curso_tecnicos',
                'curso_liderazgo',
                'telefono',
                'ubo',
                'correo_personal',
                'ultimo_cargo',
                'tipo_sangre'
            ]);
        });
    }
};
