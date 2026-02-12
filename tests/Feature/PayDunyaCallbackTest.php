<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Certificat;
use App\Models\Habitant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class PayDunyaCallbackTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_updates_certificat_on_completed_callback(): void
    {
        Log::spy();

        $user = User::factory()->create();
        $habitant = Habitant::factory()->create();
        $certificat = Certificat::factory()->create([
            'habitant_id' => $habitant->id,
            'statut' => Certificat::STATUT_EN_ATTENTE,
            'reference_paiement' => 'TOKEN_X',
        ]);

        $payload = [
            'data' => [
                'status' => 'completed',
                'invoice' => [
                    'token' => 'TOKEN_X',
                ],
            ],
        ];

        $this->actingAs($user)
            ->post(route('paydunya.callback'), $payload)
            ->assertStatus(200);

        $this->assertEquals(Certificat::STATUT_PAYE, $certificat->fresh()->statut);
        Log::shouldHaveReceived('info')->withArgs(function ($message, $context) {
            return str_contains($message, 'certificat marked as payé') && ($context['token'] ?? null) === 'TOKEN_X';
        });
    }
}
