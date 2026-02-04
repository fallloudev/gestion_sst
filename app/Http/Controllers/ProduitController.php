<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use App\Constant;

class ProduitController extends Controller
{


    public function listProduit(Request $request)
    {
        $query = Produit::query();

        // 🔍 Recherche par nom ou type
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                ->orWhere('type', 'like', '%' . $request->search . '%');
            });
        }

        $produits = $query
            ->orderBy('nom')
            ->paginate(10)
            ->withQueryString();

        return view('pages.produit.listProduit', compact('produits'));
    }

    public function addProduit()
    {
        return view('pages.produit.addProduit', [
            'typeProduits' => Constant::TYPE_PRODUITS,
        ]);
    }

    public function storeProduit(Request $request)
    {
        $validated = $request->validate([
            'nom'        => 'required|string|max:255',
            'type'       => 'required|in:' . implode(',', Constant::TYPE_PRODUITS),
            'unite'      => 'required|string|max:50',
            'poids'      => 'required|string|max:50',
            'prix_vente' => 'required|numeric|min:0',
        ]);

        Produit::create($validated);

        return redirect()
            ->route('produit.list')
            ->with('success', 'Produit ajouté avec succès');
    }

    public function editProduit($id)
    {
        return view('pages.produit.editProduit', [
            'produit' => Produit::findOrFail($id),
            'typeProduits' => Constant::TYPE_PRODUITS,
        ]);
    }

    public function updateProduit(Request $request, $id)
    {
        $produit = Produit::findOrFail($id);

        $validated = $request->validate([
            'nom'        => 'required|string|max:255',
            'type'       => 'required|in:' . implode(',', Constant::TYPE_PRODUITS),
            'unite'      => 'required|string|max:50',
            'poids'      => 'required|string|max:50',
            'prix_vente' => 'required|numeric|min:0',
        ]);

        $produit->update($validated);

        return redirect()
            ->route('produit.list')
            ->with('success', 'Produit modifié avec succès');
    }


    public function deleteProduit($id)
    {
        Produit::findOrFail($id)->delete();

        return redirect()
            ->route('produit.list')
            ->with('success', 'Produit supprimé avec succès');
    }


}
