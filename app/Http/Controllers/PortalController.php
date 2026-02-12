<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Habitant;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PortalController extends Controller
{
    public function showLogin(): View
    {
        return view('portal.login');
    }

    public function showSetPassword(): View
    {
        return view('portal.set-password');
    }

    public function showForgotPassword(): View
    {
        return view('portal.forgot-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:habitants,email'],
        ], [
            'email.exists' => 'Cet email n\'est pas enregistré comme habitant.',
        ]);

        $token = str()->random(64);
        cache()->put('portal_reset_'.$token, $validated['email'], now()->addMinutes(30));

        $resetUrl = route('portal.reset_password', ['token' => $token]);

        return back()->with('success', 'Lien de réinitialisation : '.$resetUrl.' (copie ce lien dans ton navigateur)');
    }

    public function showResetPassword(string $token): View
    {
        return view('portal.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $email = cache()->get('portal_reset_'.$validated['token']);

        if (! $email) {
            return back()->withErrors(['token' => 'Lien expiré ou invalide.']);
        }

        $habitant = Habitant::where('email', $email)->firstOrFail();
        $habitant->update(['password' => bcrypt($validated['password'])]);

        cache()->forget('portal_reset_'.$validated['token']);

        return redirect()->route('portal.login')->with('success', 'Mot de passe réinitialisé. Vous pouvez vous connecter.');
    }

    public function setPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:habitants,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.exists' => 'Cet email n\'est pas enregistré comme habitant.',
        ]);

        $habitant = Habitant::where('email', $validated['email'])->firstOrFail();
        $habitant->update([
            'password' => bcrypt($validated['password']),
        ]);

        return redirect()->route('portal.login')->with('success', 'Mot de passe défini. Vous pouvez vous connecter.');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $habitant = Habitant::where('email', $request->input('email'))->first();

        $stored = $habitant?->password;
        $isBcrypt = $stored && password_get_info($stored)['algo'] === PASSWORD_BCRYPT;

        if (! $habitant) {
            return back()->withErrors(['email' => 'Identifiants invalides ou compte inexistant.']);
        }

        if (! $isBcrypt) {
            return redirect()
                ->route('portal.set_password')
                ->with('error', 'Aucun mot de passe n\'est défini pour cet email, veuillez en créer un.')
                ->withInput(['email' => $habitant->email]);
        }

        if (! Hash::check($request->input('password'), $stored)) {
            return back()->withErrors(['email' => 'Identifiants invalides.']);
        }

        $request->session()->put('portal_habitant_id', $habitant->id);

        return redirect()->route('portal.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('portal_habitant_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.login');
    }

    public function dashboard(Request $request): View
    {
        $habitant = Habitant::with('certificats')->findOrFail($request->session()->get('portal_habitant_id'));

        return view('portal.dashboard', [
            'habitant' => $habitant,
        ]);
    }

    public function editProfile(Request $request): View
    {
        $habitant = Habitant::findOrFail($request->session()->get('portal_habitant_id'));

        return view('portal.profile', [
            'habitant' => $habitant,
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $habitant = Habitant::findOrFail($request->session()->get('portal_habitant_id'));

        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:habitants,email,'.$habitant->id],
            'telephone' => ['nullable', 'string', 'max:30'],
            'quartier' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $habitant->update($validated);

        return back()->with('success', 'Profil mis à jour.');
    }
}
