<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parte_incendios', function (Blueprint $table) {
            $table->id();
            
            // Información básica
            $table->integer('numero_parte')->nullable();
            $table->integer('clasificacion_servicio')->nullable();
            $table->string('clasificacion_servicio_texto')->nullable();
            $table->date('fecha')->nullable();
            $table->time('hora_salida')->nullable();
            $table->time('hora_retorno')->nullable();
            
            // Persona que recibió el aviso
            $table->string('persona_recepciono_aviso')->nullable();
            
            // Información de la máquina
            $table->string('maquina')->nullable();
            $table->string('mando_maquina')->nullable();
            $table->string('piloto_maquina')->nullable();
            
            // Dirección y causa
            $table->text('direccion_emergencia')->nullable();
            $table->text('causa_emergencia')->nullable();
            
            // Detalles del inmueble/vehículo
            $table->string('clase_inmueble')->nullable();
            $table->string('tipo_construccion')->nullable();
            $table->string('vehiculo')->nullable();
            $table->string('modelo_vehiculo')->nullable();
            $table->string('placa_vehiculo')->nullable();
            $table->string('color_vehiculo')->nullable();
            $table->string('marca_vehiculo')->nullable();
            $table->text('danos_vehiculo')->nullable();
            
            // Personas lesionadas (guardaremos como JSON)
            $table->json('personas_lesionadas')->nullable();
            
            // Trabajo efectuado
            $table->text('trabajo_efectuado')->nullable();
            $table->text('material_utilizado')->nullable();
            
            // Unidades de apoyo
            $table->text('unidades_apoyo_cgbvp')->nullable();
            $table->string('mando_operaciones_cgbvp')->nullable();
            $table->text('unidades_policiales')->nullable();
            
            // Personal asistente (guardaremos como JSON)
            $table->json('personal_asistente')->nullable();
            
            // Personal bombero accidentado
            $table->text('personal_bombero_accidentado')->nullable();
            
            // Personal asistente en el cuartel
            $table->text('personal_asistente_cuartel')->nullable();
            
            // Damnificados por incendios
            $table->text('damnificados_incendios')->nullable();
            
            // Observaciones
            $table->text('observaciones')->nullable();
            
            // Firmas
            $table->string('firma_bombero_mando')->nullable();
            $table->string('firma_bombero_confecciona')->nullable();
            $table->string('firma_segundo_jefe')->nullable();
            
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
        Schema::dropIfExists('parte_incendios');
    }
};