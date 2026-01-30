<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parte_emergencias', function (Blueprint $table) {
            $table->id();
            
            // Información básica
            $table->integer('numero_parte')->nullable();
            $table->date('fecha')->nullable();
            $table->time('hora_salida')->nullable();
            $table->time('hora_ingreso')->nullable();
            
            // Información del vehículo
            $table->string('piloto_maquina')->nullable();
            $table->string('velocimetro_salida')->nullable();
            $table->string('velocimetro_retorno')->nullable();
            
            // Clasificación del servicio (JSON con opciones)
            $table->json('clasificacion_servicio')->nullable();
            
            // Información del lugar y traslado
            $table->string('tipo_lugar')->nullable();
            $table->text('direccion_incidente')->nullable();
            $table->string('traslado_a')->nullable();
            $table->string('persona_responsable')->nullable();
            
            // Datos de la persona afectada
            $table->string('nombre_afectado')->nullable();
            $table->string('dni_afectado')->nullable();
            $table->integer('edad_afectado')->nullable();
            $table->string('sexo_afectado')->nullable()->comment('M, F');
            
            // Signos vitales
            $table->string('presion_arterial')->nullable();
            $table->string('pulso')->nullable();
            $table->string('respiracion')->nullable();
            $table->string('temperatura')->nullable();
            $table->string('neurologico_consciente')->nullable();
            $table->string('neurologico_inconsciente')->nullable();
            $table->string('pupilas')->nullable();
            $table->string('spo2')->nullable();
            
            // Información médica
            $table->text('lesiones_observadas')->nullable();
            $table->text('diagnostico_presuntivo')->nullable();
            $table->text('tratamiento_efectuado')->nullable();
            $table->text('material_utilizado')->nullable();
            $table->text('observaciones')->nullable();
            
            // Unidades
            $table->text('unidades_cgbvp')->nullable();
            $table->text('unidades_policiales')->nullable();
            
            // Concurrentes y no concurrentes
            $table->text('concurrentes')->nullable();
            $table->text('no_concurrentes')->nullable();
            $table->integer('total_concurrentes')->default(0);
            $table->integer('total_no_concurrentes')->default(0);
            
            // Firmas
            $table->string('firma_bombero_mando')->nullable();
            $table->string('firma_bombero_confecciona')->nullable();
            
            // Información del usuario
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('compania_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Control de estado
            $table->boolean('completado')->default(false);
            $table->boolean('aprobado')->default(false);
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parte_emergencias');
    }
};