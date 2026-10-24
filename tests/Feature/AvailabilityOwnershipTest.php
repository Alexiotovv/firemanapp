<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailabilityOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_and_elements_are_independent_per_user(): void
    {
        $admin = $this->createUser('admin', true);
        $firstUser = $this->createUser('first');
        $secondUser = $this->createUser('second');

        $this->actingAs($admin)->post(route('admin.availability.categories.store'), [
            'nombre' => 'Vehículos',
            'user_id' => $firstUser->id,
        ])->assertRedirect(route('admin.availability.index', ['user_id' => $firstUser->id]));

        $this->actingAs($admin)->post(route('admin.availability.categories.store'), [
            'nombre' => 'Vehículos',
            'user_id' => $secondUser->id,
        ]);

        $firstCategory = $firstUser->availabilityCategories()->firstOrFail();
        $secondCategory = $secondUser->availabilityCategories()->firstOrFail();

        $this->actingAs($admin)->post(route('admin.availability.elements.store', $firstCategory), [
            'nombre' => 'Unidad propia',
        ]);
        $this->actingAs($admin)->post(route('admin.availability.elements.store', $secondCategory), [
            'nombre' => 'Unidad ajena',
        ]);

        $firstElement = $firstCategory->elements()->firstOrFail();
        $secondElement = $secondCategory->elements()->firstOrFail();

        $this->actingAs($admin)->put(route('admin.availability.elements.update', $firstElement), [
            'nombre' => 'Unidad actualizada',
        ])->assertRedirect();

        $this->assertDatabaseHas('availability_elements', [
            'id' => $firstElement->id,
            'nombre' => 'Unidad actualizada',
        ]);
        $this->assertDatabaseHas('availability_elements', [
            'id' => $secondElement->id,
            'nombre' => 'Unidad ajena',
        ]);

        $this->actingAs($secondUser)
            ->get(route('availability.index'))
            ->assertOk()
            ->assertSee('Unidad ajena')
            ->assertDontSee('Unidad actualizada');
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
}
