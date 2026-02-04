<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MouvementStock;
use App\Models\Stock;
use App\Constant;
use Illuminate\Support\Facades\DB;

class MouvementStockController extends Controller
{

//Matiere Premiere
    public function listMouvementStock(Request $request)
    {
        $query = MouvementStock::with([
            'stock.matiere_premiere',
            'stock.entrepot'
        ])
        ->whereHas('stock', function ($q) {
            $q->whereNotNull('matiere_premiere_id'); // ✅ EXCLUT les stocks Produit
        });

        // 🔽 Filtre par type (ENTREE / SORTIE)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // 🔍 Filtre par référence
        if ($request->filled('reference')) {
            $query->where('reference', 'like', '%' . $request->reference . '%');
        }

        // ⏱️ Ordonnancement par date (le plus récent d’abord)
        $mouvements = $query
            ->orderByDesc('date')
            ->paginate(10)
            ->withQueryString();

        return view('pages.mouvementStock.listMouvementStock', compact('mouvements'));
    }

    public function addMouvementStock()
    {
        return view('pages.mouvementStock.addMouvementStock', [
            'stocks' => Stock::with(['matiere_premiere', 'entrepot'])
                ->whereNotNull('matiere_premiere_id') // ✅ clé métier
                ->orderBy('entrepot_id')
                ->get()
        ]);
    }


    public function storeMouvementStock(Request $request)
    {
        $validated = $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'type' => 'required|in:' . implode(',', Constant::TYPESMOUVEMENT),
            'quantite' => 'required|integer|min:1',
            // 'reference' => 'required|string|max:255|unique:mouvement_stocks,reference',
            'date' => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($validated) {

                $stock = Stock::lockForUpdate()->findOrFail($validated['stock_id']);

                // 🔽 Sortie : vérifier stock
                if (
                    $validated['type'] === Constant::TYPESMOUVEMENT['SORTIE']
                    && $validated['quantite'] > $stock->quantite
                ) {
                    throw new \Exception('Stock insuffisant pour cette sortie');
                }

                // 🔄 Mise à jour stock
                if ($validated['type'] === Constant::TYPESMOUVEMENT['ENTREE']) {
                    $stock->quantite += $validated['quantite'];
                } else {
                    $stock->quantite -= $validated['quantite'];
                }

                $stock->save();

                // 📜 Enregistrer le mouvement
                MouvementStock::create([
                    'stock_id' => $stock->id,
                    'type' => $validated['type'],
                    'quantite' => $validated['quantite'],
                    'reference' => Constant::REF['NO_REF'],
                    'date' => $validated['date'],
                ]);
            });

        } catch (\Exception $e) {
            return redirect()
                ->route('mouvementStock.add')
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('mouvementStock.list')
            ->with('success', 'Mouvement enregistré avec succès');
    }


    public function deleteMouvementStock($id)
    {
        try {
            DB::transaction(function () use ($id) {

                $mouvement = MouvementStock::lockForUpdate()->findOrFail($id);
                $stock = Stock::lockForUpdate()->findOrFail($mouvement->stock_id);

                // 🔁 Annuler impact
                if ($mouvement->type === 'ENTREE') {
                    $stock->quantite -= $mouvement->quantite;
                } else {
                    $stock->quantite += $mouvement->quantite;
                }

                if ($stock->quantite < 0) {
                    throw new \Exception('Impossible de supprimer : stock incohérent');
                }

                $stock->save();
                $mouvement->delete();
            });

        } catch (\Exception $e) {
            return redirect()
                ->route('mouvementStock.list')
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('mouvementStock.list')
            ->with('success', 'Mouvement supprimé et stock ajusté');
    }

    // PRODUITS

    public function listMouvementStockProduit(Request $request)
    {
        $query = MouvementStock::with([
            'stock.produit',
            'stock.entrepot'
        ])
        ->whereHas('stock', function ($q) {
            $q->whereNotNull('produit_id'); // ✅ EXCLUT les stocks MP
        });

        // 🔽 Filtre par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // 🔍 Filtre par référence
        if ($request->filled('reference')) {
            $query->where('reference', 'like', '%' . $request->reference . '%');
        }

        $mouvements = $query
            ->orderByDesc('date')
            ->paginate(10)
            ->withQueryString();

        return view(
            'pages.mouvementStockProduit.listMouvementStockProduit',
            compact('mouvements')
        );
    }


    public function addMouvementStockProduit()
    {
        return view('pages.mouvementStockProduit.addMouvementStockProduit', [
            'stocks' => Stock::with(['produit', 'entrepot'])
                ->whereNotNull('produit_id') // ✅ clé métier
                ->orderBy('entrepot_id')
                ->orderBy('produit_id')
                ->get()
        ]);
    }



    public function storeMouvementStockProduit(Request $request)
    {
        $validated = $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'type' => 'required|in:' . implode(',', Constant::TYPESMOUVEMENT),
            'quantite' => 'required|integer|min:1',
            // 'reference' => 'required|string|max:255|unique:mouvement_stocks,reference',
            'date' => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($validated) {

                $stock = Stock::lockForUpdate()->findOrFail($validated['stock_id']);

                // 🔽 Sortie : vérifier stock
                if (
                    $validated['type'] === Constant::TYPESMOUVEMENT['SORTIE']
                    && $validated['quantite'] > $stock->quantite
                ) {
                    throw new \Exception('Stock insuffisant pour cette sortie');
                }

                // 🔄 Mise à jour stock
                if ($validated['type'] === Constant::TYPESMOUVEMENT['ENTREE']) {
                    $stock->quantite += $validated['quantite'];
                } else {
                    $stock->quantite -= $validated['quantite'];
                }

                $stock->save();

                // 📜 Enregistrer le mouvement
                MouvementStock::create([
                    'stock_id' => $stock->id,
                    'type' => $validated['type'],
                    'quantite' => $validated['quantite'],
                    'reference' => Constant::REF['NO_REF'],
                    'date' => $validated['date'],
                ]);
            });

        } catch (\Exception $e) {
            return redirect()
                ->route('mouvementStockProduit.add')
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('mouvementStockProduit.list')
            ->with('success', 'Mouvement enregistré avec succès');
    }


    public function deleteMouvementStockProduit($id)
    {
        try {
            DB::transaction(function () use ($id) {

                $mouvement = MouvementStock::lockForUpdate()->findOrFail($id);
                $stock = Stock::lockForUpdate()->findOrFail($mouvement->stock_id);

                // 🔁 Annuler impact
                if ($mouvement->type === 'ENTREE') {
                    $stock->quantite -= $mouvement->quantite;
                } else {
                    $stock->quantite += $mouvement->quantite;
                }

                if ($stock->quantite < 0) {
                    throw new \Exception('Impossible de supprimer : stock incohérent');
                }

                $stock->save();
                $mouvement->delete();
            });

        } catch (\Exception $e) {
            return redirect()
                ->route('mouvementStockProduit.list')
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('mouvementStockProduit.list')
            ->with('success', 'Mouvement supprimé et stock ajusté');
    }



}
