<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_their_profile_without_creating_duplicates(): void
    {
        $user = User::create([
            'name' => 'Ana',
            'apellidos' => 'Bombera',
            'compania' => 'Central',
            'dni' => '12345678',
            'email' => 'ana@example.test',
            'password' => 'secret-password',
        ]);

        $this->actingAs($user)->post(route('profile.store'), [
            'nombres_apellidos' => 'Ana Bombera',
            'codigo' => 'B-12',
        ]);

        $this->actingAs($user)->post(route('profile.store'), [
            'nombres_apellidos' => 'Ana Bombera Actualizada',
            'codigo' => 'B-15',
        ]);

        $this->assertDatabaseCount('profiles', 1);
        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'nombres_apellidos' => 'Ana Bombera Actualizada',
            'codigo' => 'B-15',
        ]);
    }
}