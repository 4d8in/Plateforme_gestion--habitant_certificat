<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Certificat;
use App\Models\Habitant;
use App\Services\PayDunyaService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CertificatController extends Controller
{
    /**
     * Display a listing of the certificats with simple search/filter.
     */
    public function index(Request $request): View
    {
        $search = (string) $request->query('search', '');
        $statut = (string) $request->query('statut', '');

        $query = Certificat::with('habitant')->orderByDesc('date_certificat');

        if ($search !== '') {
            $query->whereHas('habitant', function ($q) use ($search): void {
                $q->where('nom', 'ilike', '%'.$search.'%')
                    ->orWhere('prenom', 'ilike', '%'.$search.'%')
                    ->orWhere('email', 'ilike', '%'.$search.'%')
                    ->orWhere('quartier', 'ilike', '%'.$search.'%');
            });
        }

        if ($statut !== '') {
            $query->where('statut', $statut);
        }

        $certificats = $query->paginate(10)->withQueryString();

        return view('certificats.index', [
            'certificats' => $certificats,
            'search' => $search,
            'statut' => $statut,
        ]);
    }

    /**
     * Show a confirmation screen before creating a certificat and redirecting to PayDunya.
     */
    public function create(Habitant $habitant): View
    {
        return view('certificats.create', [
            'habitant' => $habitant,
        ]);
    }

    /**
     * Create a certificat then create a PayDunya checkout invoice and redirect to payment page.
     */
    public function store(Habitant $habitant, PayDunyaService $payDunya): RedirectResponse
    {
        $certificat = $habitant->certificats()->create([
            'date_certificat' => now()->toDateString(),
            'statut' => Certificat::STATUT_EN_ATTENTE,
            'montant' => 5000,
        ]);

        try {
            $response = $payDunya->createCheckoutInvoice(
                $certificat,
                route('paydunya.return', $certificat),
                route('paydunya.cancel', $certificat),
                route('paydunya.callback')
            );
        } catch (\Throwable $e) {
            $certificat->delete();

            return redirect()
                ->route('habitants.show', $habitant)
                ->with('error', 'Impossible de créer la facture PayDunya : '.$e->getMessage());
        }

        $token = (string) ($response['token'] ?? '');
        $paymentUrl = (string) ($response['response_text'] ?? '');

        if ($token === '' || $paymentUrl === '') {
            $certificat->delete();

            return redirect()
                ->route('habitants.show', $habitant)
                ->with('error', 'Réponse inattendue de PayDunya lors de la création de la facture.');
        }

        $certificat->update([
            'reference_paiement' => $token,
        ]);

        return redirect()->away($paymentUrl);
    }

    /**
     * Handle redirection after successful payment.
     */
    public function handleReturn(Request $request, Certificat $certificat, PayDunyaService $payDunya): RedirectResponse
    {
        $token = (string) $request->query('token', $certificat->reference_paiement);

        try {
            $data = $payDunya->confirmCheckoutInvoice($token);
        } catch (\Throwable $e) {
            return redirect()
                ->route('certificats.show', $certificat)
                ->with('error', 'Erreur lors de la confirmation du paiement : '.$e->getMessage());
        }

        $status = (string) ($data['status'] ?? $data['invoice']['status'] ?? '');

        if ($status === 'completed') {
            $certificat->update([
                'statut' => Certificat::STATUT_PAYE,
            ]);

            return redirect()
                ->route('certificats.show', $certificat)
                ->with('success', 'Paiement confirmé avec succès.');
        }

        return redirect()
            ->route('certificats.show', $certificat)
            ->with('error', 'Le paiement n\'a pas été confirmé (statut: '.$status.').');
    }

    /**
     * Handle redirection when user cancels payment.
     */
    public function handleCancel(Certificat $certificat): RedirectResponse
    {
        return redirect()
            ->route('certificats.show', $certificat)
            ->with('error', 'Paiement annulé par l\'utilisateur.');
    }

    /**
     * Handle PayDunya IPN callback.
     */
    public function handleCallback(Request $request): void
    {
        // PayDunya envoie un POST application/x-www-form-urlencoded
        $payload = $request->input('data');

        if (! is_array($payload)) {
            return;
        }

        $invoice = $payload['invoice'] ?? [];
        $token = $invoice['token'] ?? null;

        if (! $token) {
            return;
        }

        /** @var Certificat|null $certificat */
        $certificat = Certificat::where('reference_paiement', $token)->first();

        if (! $certificat) {
            return;
        }

        $status = (string) ($payload['status'] ?? '');

        if ($status === 'completed') {
            $certificat->update([
                'statut' => Certificat::STATUT_PAYE,
            ]);
        }
    }

    /**
     * Display the specified certificat.
     */
    public function show(Certificat $certificat): View
    {
        return view('certificats.show', [
            'certificat' => $certificat->load('habitant'),
        ]);
    }

    /**
     * Show the form for editing the specified certificat.
     */
    public function edit(Certificat $certificat): View
    {
        return view('certificats.edit', [
            'certificat' => $certificat->load('habitant'),
        ]);
    }

    /**
     * Update the specified certificat in storage.
     */
    public function update(Request $request, Certificat $certificat): RedirectResponse
    {
        $validated = $request->validate([
            'date_certificat' => ['required', 'date'],
            'statut' => ['required', 'in:'.implode(',', [
                Certificat::STATUT_EN_ATTENTE,
                Certificat::STATUT_PAYE,
                Certificat::STATUT_DELIVRE,
            ])],
            'montant' => ['required', 'integer', 'min:0'],
            'reference_paiement' => ['nullable', 'string', 'max:255'],
        ]);

        $certificat->update($validated);

        return redirect()
            ->route('certificats.show', $certificat)
            ->with('success', 'Certificat mis à jour avec succès.');
    }

    /**
     * Remove the specified certificat from storage.
     */
    public function destroy(Certificat $certificat): RedirectResponse
    {
        $certificat->delete();

        return redirect()
            ->route('certificats.index')
            ->with('success', 'Certificat supprimé avec succès.');
    }

    /**
     * Download certificat as PDF.
     */
    public function pdf(Certificat $certificat)
    {
        if ($certificat->statut === Certificat::STATUT_PAYE) {
            $certificat->update([
                'statut' => Certificat::STATUT_DELIVRE,
            ]);
        }

        $pdf = Pdf::loadView('certificats.pdf', [
            'certificat' => $certificat->load('habitant'),
        ]);

        $filename = sprintf('certificat-residence-%d.pdf', $certificat->id);

        return $pdf->download($filename);
    }
}

