<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Proprietaire;

class ProprietaireTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($this->user, 'api');
    }

    public function test_peut_lister_les_proprietaires()
    {
        Proprietaire::factory()->count(3)->create();

        $response = $this->getJson('/api/proprietaires');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'data' => [
                            '*' => ['id', 'nom', 'prenom', 'email', 'telephone']
                        ]
                    ]
                ]);
    }

    public function test_peut_creer_un_proprietaire()
    {
        $proprietaireData = [
            'nom' => 'Diallo',
            'prenom' => 'Mamadou',
            'email' => 'mamadou@test.com',
            'telephone' => '77 123 45 67',
            'adresse' => 'Dakar, Sénégal'
        ];

        $response = $this->postJson('/api/proprietaires', $proprietaireData);

        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'data' => $proprietaireData
                ]);

        $this->assertDatabaseHas('proprietaires', $proprietaireData);
    }

    public function test_validation_creation_proprietaire()
    {
        $response = $this->postJson('/api/proprietaires', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['nom', 'prenom', 'email', 'telephone', 'adresse']);
    }
}