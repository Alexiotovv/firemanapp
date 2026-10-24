<?php

namespace Tests\Feature;

use App\Models\Emergency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmergencyParteAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_assigns_part_completion_and_assigned_user_uses_emergency_number(): void
    {
        $admin = $this->createUser('admin', true);
        $assignedUser = $this->createUser('asignado');
        $emergency = $this->createEmergency($admin);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('finalizeEmergencyModal');

        $this->actingAs($admin)
            ->get(route('admin.emergencies.edit', $emergency))
            ->assertOk()
            ->assertSee('parte_user_id');

        $this->actingAs($admin)
            ->patchJson(route('admin.emergencies.estado', $emergency), ['estado' => 'finalizada'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('parte_user_id');

        $this->actingAs($admin)
            ->patchJson(route('admin.emergencies.estado', $emergency), [
                'estado' => 'finalizada',
                'parte_user_id' => $assignedUser->id,
            ])
            ->assertOk()
            ->assertJson(['ok' => true, 'parte_user_id' => $assignedUser->id]);

        $this->actingAs($assignedUser)
            ->get(route('partes-incendios.index'))
            ->assertOk()
            ->assertSee($emergency->numero)
            ->assertSee('Completar parte');

        $this->actingAs($assignedUser)
            ->get(route('partes-incendios.create', ['emergency_id' => $emergency->id]))
            ->assertOk()
            ->assertSee($emergency->numero)
            ->assertSee($emergency->direccion);

        $this->actingAs($assignedUser)
            ->post(route('partes-incendios.store'), $this->parteData($emergency))
            ->assertRedirect();

        $this->actingAs($assignedUser)
            ->post(route('partes-incendios.store'), $this->parteData($emergency))
            ->assertForbidden();

        $this->assertDatabaseHas('parte_incendios', [
            'emergency_id' => $emergency->id,
            'numero_parte' => 202600001,
            'user_id' => $assignedUser->id,
            'completado' => true,
        ]);
    }

    public function test_only_the_assigned_user_can_open_an_emergency_part_form(): void
    {
        $admin = $this->createUser('admin', true);
        $assignedUser = $this->createUser('asignado');
        $otherUser = $this->createUser('otro');
        $emergency = $this->createEmergency($admin, [
            'estado' => 'finalizada',
            'parte_user_id' => $assignedUser->id,
        ]);

        $this->actingAs($otherUser)
            ->get(route('partes-incendios.create', ['emergency_id' => $emergency->id]))
            ->assertNotFound();

        $this->actingAs($otherUser)
            ->post(route('partes-incendios.store'), $this->parteData($emergency))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('partes-incendios.index'))
            ->assertOk()
            ->assertSee($emergency->numero)
            ->assertSee('asignado');
    }

    public function test_admin_can_assign_a_part_user_after_an_emergency_was_already_finalized(): void
    {
        $admin = $this->createUser('admin', true);
        $assignedUser = $this->createUser('asignado');
        $emergency = $this->createEmergency($admin, ['estado' => 'finalizada']);

        $this->actingAs($admin)
            ->get(route('partes-incendios.index'))
            ->assertOk()
            ->assertSee('Seleccionar usuario')
            ->assertSee(route('admin.emergencies.assign-parte', $emergency));

        $this->actingAs($admin)
            ->patch(route('admin.emergencies.assign-parte', $emergency), [
                'parte_user_id' => $assignedUser->id,
            ])
            ->assertRedirect(route('partes-incendios.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('emergencies', [
            'id' => $emergency->id,
            'parte_user_id' => $assignedUser->id,
        ]);

        $this->actingAs($assignedUser)
            ->get(route('partes-incendios.index'))
            ->assertOk()
            ->assertSee($emergency->numero)
            ->assertSee('Completar parte');
    }

    public function test_admin_sees_delete_button_before_the_part_is_completed(): void
    {
        $admin = $this->createUser('admin', true);
        $assignedUser = $this->createUser('asignado');
        $emergency = $this->createEmergency($admin, [
            'estado' => 'finalizada',
            'parte_user_id' => $assignedUser->id,
        ]);

        $this->actingAs($admin)
            ->get(route('partes-incendios.index'))
            ->assertOk()
            ->assertSee('delete-parte-incendio')
            ->assertSee(route('admin.emergencies.destroy', $emergency));

        $this->actingAs($assignedUser)
            ->get(route('partes-incendios.index'))
            ->assertOk()
            ->assertDontSee(route('admin.emergencies.destroy', $emergency));

        $this->actingAs($admin)
            ->delete(route('admin.emergencies.destroy', $emergency))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('emergencies', ['id' => $emergency->id]);
    }

    private function createUser(string $identifier, bool $isAdmin = false): User
    {
        return User::create([
            'name' => ucfirst($identifier),
            'apellidos' => 'Prueba',
            'compania' => 'Central',
            'dni' => $identifier,
            'email' => $identifier.'@example.test',
            'password' => 'secret-password',
            'is_admin' => $isAdmin,
        ]);
    }

    private function createEmergency(User $admin, array $overrides = []): Emergency
    {
        return Emergency::create($overrides + [
            'numero' => '2026-00001',
            'tipo' => 'incendio_estructural',
            'prioridad' => 'alta',
            'estado' => 'atendiendo',
            'direccion' => 'Jr. Prueba 123',
            'descripcion' => 'Incendio de prueba',
            'lat' => -3.7491000,
            'lng' => -73.2538000,
            'user_id' => $admin->id,
        ]);
    }

    private function parteData(Emergency $emergency): array
    {
        return [
            'emergency_id' => $emergency->id,
            'clasificacion_servicio' => 1,
            'fecha' => now()->toDateString(),
            'hora_salida' => '10:00',
            'hora_retorno' => '11:00',
            'persona_recepciono_aviso' => 'Operador',
            'maquina' => 'M41-6',
            'mando_maquina' => 'Mando',
            'piloto_maquina' => 'Piloto',
            'direccion_emergencia' => $emergency->direccion,
            'causa_emergencia' => 'Prueba',
            'trabajo_efectuado' => 'Atención realizada',
            'material_utilizado' => 'Agua',
            'firma_bombero_confecciona' => 'Bombero asignado',
        ];
    }
}
