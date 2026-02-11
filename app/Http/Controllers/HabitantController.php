<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreHabitantRequest;
use App\Http\Requests\UpdateHabitantRequest;
use App\Models\Habitant;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HabitantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = (string) $request->query('search', '');
        $quartier = (string) $request->query('quartier', '');

        $query = Habitant::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('nom', 'ilike', '%'.$search.'%')
                    ->orWhere('prenom', 'ilike', '%'.$search.'%')
                    ->orWhere('email', 'ilike', '%'.$search.'%');
            });
        }

        if ($quartier !== '') {
            $query->where('quartier', 'ilike', '%'.$quartier.'%');
        }

        $habitants = $query->latest()->paginate(10)->withQueryString();

        return view('habitants.index', [
            'habitants' => $habitants,
            'search' => $search,
            'quartier' => $quartier,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('habitants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHabitantRequest $request): RedirectResponse
    {
        $habitant = Habitant::create($request->validated());

        return redirect()
            ->route('habitants.show', $habitant)
            ->with('success', 'Habitant créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Habitant $habitant): View
    {
        return view('habitants.show', [
            'habitant' => $habitant->load('certificats'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Habitant $habitant): View
    {
        return view('habitants.edit', [
            'habitant' => $habitant,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHabitantRequest $request, Habitant $habitant): RedirectResponse
    {
        $habitant->update($request->validated());

        return redirect()
            ->route('habitants.show', $habitant)
            ->with('success', 'Habitant mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Habitant $habitant): RedirectResponse
    {
        $habitant->delete();

        return redirect()
            ->route('habitants.index')
            ->with('success', 'Habitant supprimé avec succès.');
    }
}

