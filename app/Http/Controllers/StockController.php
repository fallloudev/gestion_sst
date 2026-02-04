<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\MatierePremiere;
use App\Models\Entrepot;
use App\Models\Produit;

class StockController extends Controller
{
    /** 📋 Liste stock MP */
    public function listStockMp(Request $request)
    {
        $query = Stock::with(['matiere_premiere', 'entrepot'])
            ->whereNotNull('matiere_premiere_id');

        // 🔍 Recherche MP
        if ($request->filled('search')) {
            $query->whereHas('matiere_premiere', function ($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                  ->orWhere('type', 'like', '%' . $request->search . '%');
            });
        }

        // 🏭 Filtre entrepôt
        if ($request->filled('entrepot_id')) {
            $query->where('entrepot_id', $request->entrepot_id);
        }

        $stocks = $query
            ->orderBy('entrepot_id')
            ->orderByDesc('date')
            ->paginate(10)
            ->withQueryString();

        $entrepots = Entrepot::orderBy('nom')->get();

        return view('pages.stock.listStockMp', compact('stocks', 'entrepots'));
    }

    /** ➕ Form ajout stock */
    public function addStock()
    {
        return view('pages.stock.addStock', [
            'matierePremieres' => MatierePremiere::orderBy('nom')->get(),
            'entrepots' => Entrepot::orderBy('nom')->get(),
        ]);
    }

    /** 💾 Enregistrer stock (ANTI-DOUBLON) */
    public function storeStock(Request $request)
    {
        $validated = $request->validate([
            'matiere_premiere_id' => 'required|exists:matiere_premieres,id',
            'entrepot_id' => 'required|exists:entrepots,id',
            'quantite' => 'required|integer|min:1',
            'date' => 'required|date',
            'commentaire' => 'nullable|string',
        ]);

        $stock = Stock::where('matiere_premiere_id', $validated['matiere_premiere_id'])
            ->where('entrepot_id', $validated['entrepot_id'])
            ->first();

        if ($stock) {
            $stock->quantite += $validated['quantite'];
            $stock->date = $validated['date'];
            $stock->commentaire = $validated['commentaire'];
            $stock->save();
        } else {
            Stock::create($validated);
        }

        return redirect()
            ->route('stock.listMp')
            ->with('success', 'Stock mis à jour avec succès');
    }

    /** ✏️ Form édition stock */
    public function editStock($id)
    {
        return view('pages.stock.editStock', [
            'stock' => Stock::with(['matiere_premiere', 'entrepot'])->findOrFail($id),
        ]);
    }

    /** 🔄 Mise à jour stock */
    public function updateStock(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);

        $validated = $request->validate([
            'quantite' => 'required|integer|min:0',
            'date' => 'required|date',
            'commentaire' => 'nullable|string',
        ]);

        $stock->update($validated);

        return redirect()
            ->route('stock.listMp')
            ->with('success', 'Stock modifié avec succès');
    }

    /** 🗑️ Supprimer stock */
    public function deleteStock($id)
    {
        Stock::findOrFail($id)->delete();

        return redirect()
            ->route('stock.listMp')
            ->with('success', 'Stock supprimé avec succès');
    }


    // PRODUITS
    /** 📋 Liste stock Produits */
    public function listStockProduit(Request $request)
    {
        $query = Stock::with(['produit', 'entrepot'])
            ->whereNotNull('produit_id');

        // 🔍 Recherche MP
        if ($request->filled('search')) {
            $query->whereHas('produit', function ($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                    ->orWhere('type', 'like', '%' . $request->search . '%');
            });
        }

        // 🏭 Filtre entrepôt
        if ($request->filled('entrepot_id')) {
            $query->where('entrepot_id', $request->entrepot_id);
        }

        $stocks = $query
            ->orderBy('entrepot_id')
            ->orderByDesc('date')
            ->paginate(10)
            ->withQueryString();

        $entrepots = Entrepot::orderBy('nom')->get();

        return view('pages.stockProduit.listStockProduit', compact('stocks', 'entrepots'));
    }

    /** ➕ Form ajout stock */
    public function addStockProduit()
    {
        return view('pages.stockProduit.addStockProduit', [
            'produits' => Produit::orderBy('nom')->get(),
            'entrepots' => Entrepot::orderBy('nom')->get(),
        ]);
    }

    /** 💾 Enregistrer stock (ANTI-DOUBLON) */
    public function storeStockProduit(Request $request)
    {
        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'entrepot_id' => 'required|exists:entrepots,id',
            'quantite' => 'required|integer|min:1',
            'date' => 'required|date',
            'commentaire' => 'nullable|string',
        ]);

        $stock = Stock::where('produit_id', $validated['produit_id'])
            ->where('entrepot_id', $validated['entrepot_id'])
            ->first();

        if ($stock) {
            $stock->quantite += $validated['quantite'];
            $stock->date = $validated['date'];
            $stock->commentaire = $validated['commentaire'];
            $stock->save();
        } else {
            Stock::create($validated);
        }

        return redirect()
            ->route('stockProduit.list')
            ->with('success', 'Stock mis à jour avec succès');
    }


    /** ✏️ Form édition stock */
    public function editStockProduit($id)
    {
        return view('pages.stockProduit.editStockProduit', [
            'stock' => Stock::with(['produit', 'entrepot'])->findOrFail($id),
        ]);
    }

    /** 🔄 Mise à jour stock */
    public function updateStockProduit(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);

        $validated = $request->validate([
            'quantite' => 'required|integer|min:0',
            'date' => 'required|date',
            'commentaire' => 'nullable|string',
        ]);

        $stock->update($validated);

        return redirect()
            ->route('stockProduit.list')
            ->with('success', 'Stock modifié avec succès');
    }

    /** 🗑️ Supprimer stock */
    public function deleteStockProduit($id)
    {
        Stock::findOrFail($id)->delete();

        return redirect()
            ->route('stockProduit.list')
            ->with('success', 'Stock supprimé avec succès');
    }

    
}
