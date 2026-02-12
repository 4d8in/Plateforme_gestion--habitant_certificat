<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Certificat;
use App\Models\Habitant;
use App\Services\PayDunyaService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PortalCertificatController extends Controller
{
    public function index(Request $request): View
    {
        $habitant = Habitant::with('certificats')->findOrFail($request->session()->get('portal_habitant_id'));

        return view('portal.certificats', [
            'habitant' => $habitant,
            'certificats' => $habitant->certificats()->latest()->get(),
        ]);
    }

    public function show(Request $request, Certificat $certificat): View
    {
        $habitantId = $request->session()->get('portal_habitant_id');
        abort_unless($certificat->habitant_id === $habitantId, 403);

        return view('portal.certificat-show', [
            'certificat' => $certificat->load('habitant'),
        ]);
    }

    public function pay(Request $request, Certificat $certificat, PayDunyaService $payDunya): RedirectResponse
    {
        $habitantId = $request->session()->get('portal_habitant_id');

        if ($certificat->habitant_id !== $habitantId) {
            abort(403);
        }

        // Relancer une facture PayDunya si besoin
        $certificat->update([
            'statut' => Certificat::STATUT_EN_ATTENTE,
        ]);

        try {
            $response = $payDunya->createCheckoutInvoice(
                $certificat,
                route('paydunya.return', $certificat),
                route('paydunya.cancel', $certificat),
                route('paydunya.callback')
            );
        } catch (\Throwable $e) {
            return back()->with('error', 'Impossible de créer la facture PayDunya : '.$e->getMessage());
        }

        $token = (string) ($response['token'] ?? '');
        $paymentUrl = (string) ($response['response_text'] ?? '');

        if ($token === '' || $paymentUrl === '') {
            return back()->with('error', 'Réponse inattendue de PayDunya lors de la création de la facture.');
        }

        $certificat->update([
            'reference_paiement' => $token,
        ]);

        return redirect()->away($paymentUrl);
    }
}
