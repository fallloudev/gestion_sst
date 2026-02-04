<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdreProduction;
use App\Models\Produit;
use App\Models\MatierePremiere;
use App\Models\ConsommationMP;
use App\Models\Stock;
use App\Models\MouvementStock;
use App\Constant;
use Illuminate\Support\Facades\DB;

class OrdreProductionController extends Controller
{

    public function listOrdreProduction(Request $request)
    {
        $query = OrdreProduction::with([
            'produit',
            'consommation_m_p_s.matiere_premiere'
        ]);

        // 🔍 Recherche par nom produit
        if ($request->filled('search')) {
            $query->whereHas('produit', function ($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                ->orWhere('type', 'like', '%' . $request->search . '%');
            });
        }

        // 🧭 Ordonnancement logique
        $ordres = $query
            ->orderByRaw("FIELD(statut, 'EN_ATTENTE', 'EN_COURS', 'TERMINE')")
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('pages.ordreProduction.listOrdreProduction', compact('ordres'));
    }

    public function addOrdreProduction()
    {
        return view('pages.ordreProduction.addOrdreProduction', [
            'produits' => Produit::orderBy('nom')->get(),
            'matierePremieres' => MatierePremiere::orderBy('nom')->get(),
            'statuts' => Constant::STATUT_ORDRE_PRODUCTION,
        ]);
    }



    // public function storeOrdreProduction(Request $request)
    // {
    //     $validated = $request->validate([
    //         'produit_id' => 'required|exists:produits,id',
    //         'quantite_prevue' => 'required|integer|min:1',
    //         'statut' => 'required|in:' . implode(',', Constant::STATUT_ORDRE_PRODUCTION),
    //         'mp_ids' => 'required|array',
    //         'quantites' => 'required|array',
    //         'date' => 'required|date',
    //     ]);

    //     try {
    //         DB::transaction(function () use ($validated, $request) {

    //             // 1️⃣ Créer l’ordre
    //             $ordre = OrdreProduction::create([
    //                 'produit_id' => $validated['produit_id'],
    //                 'quantite_prevue' => $validated['quantite_prevue'],
    //                 'quantite_produite' => 0,
    //                 'statut' => $validated['statut'],
    //                 'date' => $validated['date'],
    //                 'reference' => 'OP-' . $ordre->id,
    //             ]);

    //             // 2️⃣ Consommation MP + mouvements
    //             foreach ($request->mp_ids as $index => $mpId) {

    //                 $qte = (int) $request->quantites[$index];
    //                 if ($qte <= 0) continue;

    //                 $stock = Stock::where('matiere_premiere_id', $mpId)
    //                     ->lockForUpdate()
    //                     ->firstOrFail();

    //                 // 🔴 Stock insuffisant
    //                 if ($qte > $stock->quantite) {
    //                     throw new \Exception("Stock insuffisant pour la matière première ID $mpId");
    //                 }


    //                 // 3️⃣ Consommation MP
    //                 ConsommationMP::create([
    //                     'ordre_production_id' => $ordre->id,
    //                     'matiere_premiere_id' => $mpId,
    //                     'quantite' => $qte,
    //                 ]);

    //                 // 4️⃣ Mouvement SORTIE
    //                 MouvementStock::create([
    //                     'stock_id' => $stock->id,
    //                     'type' => Constant::TYPESMOUVEMENT['SORTIE'],
    //                     'quantite' => $qte,
    //                     'reference' => 'OP-' . $ordre->id,
    //                     'date' => $ordre->date,
    //                 ]);

    //                 // 5️⃣ Mise à jour stock
    //                 $stock->quantite -= $qte;
    //                 $stock->save();
    //             }
    //         });

    //     } 
        
    //     catch (\Exception $e) {
    //         return $e;
    //     }

    //     return redirect()
    //         ->route('ordreProduction.list')
    //         ->with('success', 'Ordre de production créé avec succès');
    // }

    // public function storeOrdreProduction(Request $request)
    // {
    //     $validated = $request->validate([
    //         'produit_id' => 'required|exists:produits,id',
    //         'quantite_prevue' => 'required|integer|min:1',
    //         'quantite_produite' => 'required|integer|min:1',
    //         'statut' => 'required|in:' . implode(',', Constant::STATUT_ORDRE_PRODUCTION),
    //         'mp_ids' => 'required|array',
    //         'quantites' => 'required|array',
    //         'date' => 'required|date',
    //     ]);

    //     try {
    //         DB::transaction(function () use ($validated, $request) {

    //             // 1️⃣ Création de l’ordre (SANS référence)
    //             $ordre = OrdreProduction::create([
    //                 'produit_id' => $validated['produit_id'],
    //                 'quantite_prevue' => $validated['quantite_prevue'],
    //                 'quantite_produite' => $validated['quantite_produite'],
    //                 'statut' => $validated['statut'],
    //                 'date' => $validated['date'],
    //             ]);

    //             // 2️⃣ Génération de la référence OP
    //             $ordre->reference = 'OP-' . $ordre->id;
    //             $ordre->save();

    //             // 3️⃣ Consommation MP + mouvements de stock
    //             foreach ($request->mp_ids as $index => $mpId) {

    //                 $qte = (int) $request->quantites[$index];
    //                 if ($qte <= 0) {
    //                     continue;
    //                 }

    //                 $stock = Stock::where('matiere_premiere_id', $mpId)
    //                     ->lockForUpdate()
    //                     ->firstOrFail();

    //                 // 🔴 Vérification stock
    //                 if ($qte > $stock->quantite) {
    //                     throw new \Exception(
    //                         "Stock insuffisant pour la matière première ID {$mpId}"
    //                     );
    //                 }

    //                 // 4️⃣ Consommation MP
    //                 ConsommationMP::create([
    //                     'ordre_production_id' => $ordre->id,
    //                     'matiere_premiere_id' => $mpId,
    //                     'quantite' => $qte,
    //                 ]);

    //                 // 5️⃣ Mouvement de stock SORTIE (avec référence OP)
    //                 MouvementStock::create([
    //                     'stock_id' => $stock->id,
    //                     'type' => Constant::TYPESMOUVEMENT['SORTIE'],
    //                     'quantite' => $qte,
    //                     'reference' => $ordre->reference, // ✅ OP-XX
    //                     'date' => $validated['date'],
    //                 ]);

    //                 // 6️⃣ Mise à jour du stock
    //                 $stock->quantite -= $qte;
    //                 $stock->save();
    //             }
    //         });

    //     } catch (\Exception $e) {
    //         return redirect()
    //             ->route('ordreProduction.add')
    //             ->with('error', $e->getMessage());
    //     }

    //     return redirect()
    //         ->route('ordreProduction.list')
    //         ->with('success', 'Ordre de production créé avec succès');
    // }

    public function storeOrdreProduction(Request $request)
{
    $validated = $request->validate([
        'produit_id' => 'required|exists:produits,id',
        'quantite_prevue' => 'required|integer|min:1',
        'quantite_produite' => 'required|integer|min:1',
        'statut' => 'required|in:' . implode(',', Constant::STATUT_ORDRE_PRODUCTION),
        'mp_ids' => 'required|array',
        'quantites' => 'required|array',
        'date' => 'required|date',
    ]);

    try {
        DB::transaction(function () use ($validated, $request) {

            /* =========================
               1️⃣ CRÉATION ORDRE
            ==========================*/
            $ordre = OrdreProduction::create([
                'produit_id' => $validated['produit_id'],
                'quantite_prevue' => $validated['quantite_prevue'],
                'quantite_produite' => $validated['quantite_produite'],
                'statut' => $validated['statut'],
                'date' => $validated['date'],
            ]);

            // Référence OP
            $ordre->reference = 'OP-' . $ordre->id;
            $ordre->save();

            /* =========================
               2️⃣ SORTIE MATIÈRES PREMIÈRES
            ==========================*/
            foreach ($request->mp_ids as $index => $mpId) {

                $qte = (int) $request->quantites[$index];
                if ($qte <= 0) continue;

                $stockMp = Stock::where('matiere_premiere_id', $mpId)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($qte > $stockMp->quantite) {
                    throw new \Exception(
                        "Stock insuffisant pour la matière première ID {$mpId}"
                    );
                }

                // Consommation MP
                ConsommationMP::create([
                    'ordre_production_id' => $ordre->id,
                    'matiere_premiere_id' => $mpId,
                    'quantite' => $qte,
                ]);

                // Mouvement SORTIE
                MouvementStock::create([
                    'stock_id' => $stockMp->id,
                    'type' => Constant::TYPESMOUVEMENT['SORTIE'],
                    'quantite' => $qte,
                    'reference' => $ordre->reference,
                    'date' => $validated['date'],
                ]);

                // Mise à jour stock MP
                $stockMp->quantite -= $qte;
                $stockMp->save();
            }

            /* =========================
               3️⃣ ENTRÉE PRODUIT FINI ✅
            ==========================*/
            $stockProduit = Stock::where('produit_id', $validated['produit_id'])
                ->lockForUpdate()
                ->first();

            // ➕ Créer stock produit s’il n’existe pas
            if (!$stockProduit) {
                $stockProduit = Stock::create([
                    'produit_id' => $validated['produit_id'],
                    'entrepot_id' => 1, // ⚠️ à adapter (entrepôt production)
                    'quantite' => 0,
                    'date' => $validated['date'],
                    'commentaire' => 'Stock initial produit',
                ]);
            }

            // ➕ Ajouter la quantité produite
            $stockProduit->quantite += $validated['quantite_produite'];
            $stockProduit->date = $validated['date'];
            $stockProduit->save();

            // Mouvement ENTREE produit
            MouvementStock::create([
                'stock_id' => $stockProduit->id,
                'type' => Constant::TYPESMOUVEMENT['ENTREE'],
                'quantite' => $validated['quantite_produite'],
                'reference' => $ordre->reference,
                'date' => $validated['date'],
            ]);
        });

    } catch (\Exception $e) {
        return redirect()
            ->route('ordreProduction.add')
            ->with('error', $e->getMessage());
    }

    return redirect()
        ->route('ordreProduction.list')
        ->with('success', 'Ordre de production créé avec succès');
}


    public function editOrdreProduction($id)
    {
        return view('pages.ordreProduction.editOrdreProduction', [
            'ordre' => OrdreProduction::with([
                'produit',
                'consommation_m_p_s.matiere_premiere'
            ])->findOrFail($id),

            'produits' => Produit::orderBy('nom')->get(),
        ]);
    }

    public function updateOrdreProduction(Request $request, $id)
    {
        $ordre = OrdreProduction::findOrFail($id);

        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'quantite_prevue' => 'required|integer|min:1',
            'quantite_produite' => 'nullable|integer|min:0',
            'statut' => 'required|in:' . implode(',', Constant::STATUT_ORDRE_PRODUCTION),
            'date' => 'required|date',
        ]);

        $ordre->update($validated);

        return redirect()
            ->route('ordreProduction.list')
            ->with('success', 'Ordre de production mis à jour avec succès');
    }


    public function deleteOrdreProduction($id)
    {
        try {
            DB::transaction(function () use ($id) {

                $ordre = OrdreProduction::with([
                    'consommation_m_p_s',
                ])->findOrFail($id);

                // 1️⃣ Récupérer les mouvements SORTIE liés à l’OP
                $mouvements = MouvementStock::where('reference', $ordre->reference)
                    ->where('type', Constant::TYPESMOUVEMENT['SORTIE'])
                    ->get();

                // 2️⃣ Rétablir le stock
                foreach ($mouvements as $mouvement) {
                    $stock = Stock::lockForUpdate()->findOrFail($mouvement->stock_id);
                    $stock->quantite += $mouvement->quantite;
                    $stock->save();
                }

                // 3️⃣ Supprimer les mouvements de stock
                MouvementStock::where('reference', $ordre->reference)->delete();

                // 4️⃣ Supprimer les consommations MP
                $ordre->consommation_m_p_s()->delete();

                // 5️⃣ Supprimer l’ordre de production
                $ordre->delete();
            });

        } catch (\Exception $e) {
            return redirect()
                ->route('ordreProduction.list')
                ->with('error', 'Erreur suppression OP : ' . $e->getMessage());
        }

        return redirect()
            ->route('ordreProduction.list')
            ->with('success', 'Ordre de production supprimé et stock rétabli avec succès');
    }

}
