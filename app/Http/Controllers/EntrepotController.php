<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Entrepot;
use Illuminate\Support\Facades\Hash;
use App\Constant;

class EntrepotController extends Controller
{
    /**
     * Liste des entrepots
     */
    public function listEntrepot(Request $request)
    {
        $query = Entrepot::query();

        // Recherche
        if ($request->filled('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%')
                ->orWhere('localisation', 'like', '%' . $request->search . '%');
        }

        $entrepots = $query
            ->orderBy('nom')
            ->paginate(10)
            ->withQueryString(); // garde la recherche en pagination

        return view('pages.entrepot.listEntrepot', compact('entrepots'));
    }

    /**
     * Formulaire ajout utilisateur
     */
    public function addEntrepot()
    {
        return view('pages.entrepot.addEntrepot');
    }

    /**
     * Enregistrer un utilisateur
     */
    public function storeEntrepot(Request $request)
    {
        $validated = $request->validate([
            'nom'     => 'required|string|max:255',
            'localisation'    => 'required|string|max:255',
        ]);

        Entrepot::create([
            'nom'      => $validated['nom'],
            'localisation'     => $validated['localisation'],
        ]);

        return redirect()
            ->route('entrepot.list')
            ->with('success', 'Entrepot créé avec succès');
    }

    /**
     * Formulaire édition Entrepot
     */
    public function editEntrepot(string $entrepotId)
    {
        $entrepot = Entrepot::findOrFail($entrepotId);
        return view('pages.entrepot.editEntrepot', compact('entrepot'));
    }

    /**
     * Mise à jour Entrepot
     */
    public function updateEntrepot(Request $request, string $entrepotId)
    {
        $entrepot = Entrepot::findOrFail($entrepotId);

        $validated = $request->validate([
            'nom'     => 'required|string|max:255',
            'localisation'    => 'required|string|max:255',
        ]);

        $entrepot->update([
            'nom'    => $validated['nom'],
            'localisation'   => $validated['localisation'],
        ]);

        return redirect()
            ->route('entrepot.edit', $entrepot->id)
            ->with('success', 'Entrepot modifié avec succès');
    }

    /**
     * Supprimer Entrepot
     */
    public function deleteEntrepot(string $entrepotId)
    {
        Entrepot::findOrFail($entrepotId)->delete();

        return redirect()
            ->route('entrepot.list')
            ->with('success', 'Entrepot supprimé avec succès');
    }

}
