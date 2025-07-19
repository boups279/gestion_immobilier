<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Paiement;
use App\Models\Location;
use App\Models\Locataire;
use App\Models\Immeuble;
use App\Models\Proprietaire;

class PaiementTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $proprietaire;
    protected $locataire;
    protected $immeuble;
    protected $location;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($this->user, 'api');

        $this->proprietaire = Proprietaire::factory()->create();
        $this->locataire = Locataire::factory()->create();
        $this->immeuble = Immeuble::factory()->create(['proprietaire_id' => $this->proprietaire->id]);
        $this->location = Location::factory()->create([
            'locataire_id' => $this->locataire->id,
            'immeuble_id' => $this->immeuble->id
        ]);
    }

    public function test_peut_verifier_paiement_locataire()
    {
        // Créer un paiement
        Paiement::factory()->create([
            'location_id' => $this->location->id,
            'locataire_id' => $this->locataire->id,
            'mois_concerne' => 7,
            'annee_concernee' => 2025,
            'statut' => 'paye'
        ]);

        $response = $this->postJson('/api/paiements/verifier', [
            'locataire_id' => $this->locataire->id,
            'mois' => 7,
            'annee' => 2025
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'a_paye' => true
                    ]
                ]);
    }

    public function test_peut_verifier_paiement_manquant()
    {
        $response = $this->postJson('/api/paiements/verifier', [
            'locataire_id' => $this->locataire->id,
            'mois' => 6,
            'annee' => 2025
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'a_paye' => false
                    ]
                ]);
    }
}