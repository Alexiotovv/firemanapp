<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();
            $table->string('nombres_apellidos')->nullable()->comment('Nombres y apellidos');
            $table->string('dni')->nullable()->comment('Documento Nacional de Identidad');

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

        $legacyFields = [
            'codigo', 'grados', 'fecha_asenso', 'fecha_graduacion',
            'curso_basicos', 'curso_tecnicos', 'curso_liderazgo',
            'telefono', 'ubo', 'correo_personal', 'ultimo_cargo', 'tipo_sangre',
        ];

        DB::table('users')->orderBy('id')->get()->each(function ($user) use ($legacyFields) {
            $profile = [
                'user_id' => $user->id,
                'nombres_apellidos' => trim(($user->name ?? '') . ' ' . ($user->apellidos ?? '')),
                'dni' => $user->dni ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            foreach ($legacyFields as $field) {
                $profile[$field] = $user->{$field} ?? null;
            }

            DB::table('profiles')->insert($profile);
        });

        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'codigo', 'grados', 'fecha_asenso', 'fecha_graduacion',
                'curso_basicos', 'curso_tecnicos', 'curso_liderazgo', 'telefono',
                'ubo', 'correo_personal', 'ultimo_cargo', 'tipo_sangre',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
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

        DB::table('profiles')->orderBy('user_id')->get()->each(function ($profile) {
            DB::table('users')->where('id', $profile->user_id)->update([
                'codigo' => $profile->codigo,
                'grados' => $profile->grados,
                'fecha_asenso' => $profile->fecha_asenso,
                'fecha_graduacion' => $profile->fecha_graduacion,
                'curso_basicos' => $profile->curso_basicos,
                'curso_tecnicos' => $profile->curso_tecnicos,
                'curso_liderazgo' => $profile->curso_liderazgo,
                'telefono' => $profile->telefono,
                'ubo' => $profile->ubo,
                'correo_personal' => $profile->correo_personal,
                'ultimo_cargo' => $profile->ultimo_cargo,
                'tipo_sangre' => $profile->tipo_sangre,
            ]);
        });

        Schema::dropIfExists('profiles');
    }
};
