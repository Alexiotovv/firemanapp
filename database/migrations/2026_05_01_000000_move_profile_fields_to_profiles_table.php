<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();

            // Información profesional
            $table->string('codigo')->nullable()->comment('Código o Matrícula del bombero');
            $table->string('grados')->nullable()->comment('Grados o Rango');
            $table->date('fecha_asenso')->nullable()->comment('Fecha del último ascenso');
            $table->date('fecha_graduacion')->nullable()->comment('Fecha de graduación');

            // Capacitación
            $table->string('curso_basicos')->nullable()->comment('Cursos Básicos completados');
            $table->string('curso_tecnicos')->nullable()->comment('Cursos Técnicos completados');
            $table->string('curso_liderazgo')->nullable()->comment('Cursos de Liderazgo completados');

            // Información de contacto
            $table->string('telefono')->nullable()->comment('Teléfono de contacto');
            $table->string('ubo')->nullable()->comment('Unidad Base de Operaciones');
            $table->string('correo_personal')->nullable()->comment('Correo electrónico personal');

            // Información adicional
            $table->string('ultimo_cargo')->nullable()->comment('Último cargo ocupado');
            $table->string('tipo_sangre')->nullable()->comment('Tipo de sangre');

            $table->timestamps();
        });

        // Quitar columnas de la tabla users si existen
        Schema::table('users', function (Blueprint $table) {
            $cols = [
                'codigo', 'grados', 'fecha_asenso', 'fecha_graduacion',
                'curso_basicos', 'curso_tecnicos', 'curso_liderazgo',
                'telefono', 'ubo', 'correo_personal', 'ultimo_cargo', 'tipo_sangre'
            ];

            foreach ($cols as $c) {
                if (Schema::hasColumn('users', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }

    public function down()
    {
        // Restaurar columnas en users
        Schema::table('users', function (Blueprint $table) {
            // Nota: no siempre posible recuperar comentarios/after positions
            $table->string('codigo')->nullable();
            $table->string('grados')->nullable();
            $table->date('fecha_asenso')->nullable();
            $table->date('fecha_graduacion')->nullable();
            $table->string('curso_basicos')->nullable();
            $table->string('curso_tecnicos')->nullable();
            $table->string('curso_liderazgo')->nullable();
            $table->string('telefono')->nullable();
            $table->string('ubo')->nullable();
            $table->string('correo_personal')->nullable();
            $table->string('ultimo_cargo')->nullable();
            $table->string('tipo_sangre')->nullable();
        });

        Schema::dropIfExists('profiles');
    }
};
