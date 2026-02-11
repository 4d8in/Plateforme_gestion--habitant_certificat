<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreCertificatRequest;
use App\Http\Requests\UpdateCertificatRequest;
use App\Models\Certificat;
use App\Models\Habitant;
use App\Services\PayDunyaService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CertificatController extends Controller
{
    /**
     * Display a listing of the certificats with simple search/filter.
     */
    public function index(Request $request): View
    {
        $pendingAlertDays = (int) config('certificat.pending_alert_days', 7);

        $certificats = $this->filteredQuery($request)->paginate(10)->withQueryString();

        $retardCountTotal = Certificat::where('statut', Certificat::STATUT_EN_ATTENTE)
            ->whereDate('created_at', '<=', now()->subDays($pendingAlertDays))
            ->count();

        return view('certificats.index', [
            'certificats' => $certificats,
            'search' => (string) $request->query('search', ''),
            'statut' => (string) $request->query('statut', ''),
            'dateFrom' => (string) $request->query('date_from', ''),
            'dateTo' => (string) $request->query('date_to', ''),
            'montantMin' => (string) $request->query('montant_min', ''),
            'montantMax' => (string) $request->query('montant_max', ''),
            'onlyLate' => (bool) $request->boolean('retards'),
            'pendingAlertDays' => $pendingAlertDays,
            'retardCountTotal' => $retardCountTotal,
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
    public function store(StoreCertificatRequest $request, Habitant $habitant, PayDunyaService $payDunya): RedirectResponse
    {
        $montant = (int) ($request->validated()['montant'] ?? config('certificat.default_montant', 5000));

        $certificat = $habitant->certificats()->create([
            'date_certificat' => now()->toDateString(),
            'statut' => Certificat::STATUT_EN_ATTENTE,
            'montant' => $montant,
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
    public function update(UpdateCertificatRequest $request, Certificat $certificat): RedirectResponse
    {
        $certificat->update($request->validated());

        return redirect()
            ->route('certificats.show', $certificat)
            ->with('success', 'Certificat mis à jour avec succès.');
    }

    /**
     * Export filtered certificats as CSV or PDF.
     */
    public function export(Request $request): Response
    {
        $format = strtolower((string) $request->query('format', 'csv'));
        $certificats = $this->filteredQuery($request)->with('habitant')->get();

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('certificats.export', [
                'certificats' => $certificats,
                'generatedAt' => now(),
            ]);

            return $pdf->download('certificats-'.now()->format('Ymd_His').'.pdf');
        }

        $filename = 'certificats-'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($certificats): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Habitant', 'Email', 'Quartier', 'Date', 'Statut', 'Montant', 'Référence']);

            foreach ($certificats as $certificat) {
                fputcsv($handle, [
                    $certificat->id,
                    $certificat->habitant->nom_complet,
                    $certificat->habitant->email,
                    $certificat->habitant->quartier,
                    optional($certificat->date_certificat)->format('Y-m-d'),
                    $certificat->statut,
                    $certificat->montant,
                    $certificat->reference_paiement,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Build a filtered query reused by index/export.
     */
    protected function filteredQuery(Request $request): Builder
    {
        $search = (string) $request->query('search', '');
        $statut = (string) $request->query('statut', '');
        $dateFrom = (string) $request->query('date_from', '');
        $dateTo = (string) $request->query('date_to', '');
        $montantMin = (string) $request->query('montant_min', '');
        $montantMax = (string) $request->query('montant_max', '');
        $onlyLate = (bool) $request->boolean('retards');
        $pendingAlertDays = (int) config('certificat.pending_alert_days', 7);

        $query = Certificat::with('habitant')->orderByDesc('date_certificat');

        if ($search !== '') {
            $normalized = strtolower($search);
            $query->whereHas('habitant', function ($q) use ($normalized): void {
                $q->whereRaw('LOWER(nom) LIKE ?', ['%'.$normalized.'%'])
                    ->orWhereRaw('LOWER(prenom) LIKE ?', ['%'.$normalized.'%'])
                    ->orWhereRaw('LOWER(email) LIKE ?', ['%'.$normalized.'%'])
                    ->orWhereRaw('LOWER(quartier) LIKE ?', ['%'.$normalized.'%']);
            });
        }

        if ($statut !== '') {
            $query->where('statut', $statut);
        }

        if ($dateFrom !== '') {
            $query->whereDate('date_certificat', '>=', $dateFrom);
        }

        if ($dateTo !== '') {
            $query->whereDate('date_certificat', '<=', $dateTo);
        }

        if ($montantMin !== '') {
            $query->where('montant', '>=', (int) $montantMin);
        }

        if ($montantMax !== '') {
            $query->where('montant', '<=', (int) $montantMax);
        }

        if ($onlyLate) {
            $query->where('statut', Certificat::STATUT_EN_ATTENTE)
                ->whereDate('created_at', '<=', now()->subDays($pendingAlertDays));
        }

        return $query;
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
