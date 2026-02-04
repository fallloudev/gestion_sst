<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Constant;
use App\Models\Stock;
use App\Models\MouvementStock;
use App\Models\Facture;
use App\Models\Paiement;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PaiementController extends Controller
{

    
    public function listPaiement(Request $request)
    {
        $query = Paiement::with([
            'facture.commande.client'
        ]);
    
        // 🔍 Recherche (client / référence / numéro facture)
        if ($request->filled('search')) {
            $search = $request->search;
    
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhereHas('facture', function ($q2) use ($search) {
                      $q2->where('numero', 'like', "%{$search}%");
                  })
                  ->orWhereHas('facture.commande.client', function ($q3) use ($search) {
                      $q3->where('nom', 'like', "%{$search}%");
                  });
            });
        }
    
        // 🎚 Filtre par mode de paiement
        if ($request->filled('mode')) {
            $query->where('mode_paiement', $request->mode);
        }
    
        // ⏱️ Ordre : plus récent d’abord
        $paiements = $query
            ->orderByDesc('date')
            ->paginate(10)
            ->withQueryString();
    
        return view('pages.paiement.listPaiement', compact('paiements'));
    }


    public function showPaiement($paiementId)
    {
        $paiement = Paiement::with([
            'facture.commande.client',
            'facture.commande.lignes.produit'
        ])->findOrFail($paiementId);

        return view('pages.paiement.showPaiement', compact('paiement'));
    }


    public function addPaiement($factureId)
    {
        $facture = Facture::with([
            'commande.client',
            'commande.lignes.produit'
        ])->findOrFail($factureId);

        // 🚫 Sécurité : facture déjà payée
        if ($facture->statut === 'PAYEE') {
            return redirect()
                ->route('commande.list')
                ->with('warning', 'Cette facture est déjà payée');
        }

        return view('pages.paiement.addPaiement', [
            'facture' => $facture,
            'modesPaiement' => Constant::MODES_PAIEMENT
        ]);
    }


    public function storePaiement(Request $request)
    {
        $validated = $request->validate([
            'facture_id' => 'required|exists:factures,id',
            'mode_paiement' => 'required|string',
            'montant' => 'required|numeric|min:1',
            'date' => 'required|date',
            'reference' => 'nullable|string'
        ]);
    
        try {
            DB::transaction(function () use ($validated) {
    
                // 🔒 Verrouiller la facture
                $facture = Facture::with('commande.lignes.produit')
                    ->lockForUpdate()
                    ->findOrFail($validated['facture_id']);
    
                // 🚫 Bloquer double paiement
                if ($facture->statut === 'PAYEE') {
                    throw new \Exception('Cette facture a déjà été réglée.');
                }
    
                // ❗ Vérification du montant (paiement total uniquement)
                if ($validated['montant'] != $facture->total_ttc) {
                    throw new \Exception(
                        'Le montant payé doit être égal au total de la facture.'
                    );
                }
    
                // 1️⃣ Enregistrer le paiement
                Paiement::create([
                    'facture_id'   => $facture->id,
                    'mode_paiement'=> $validated['mode_paiement'],
                    'montant'      => $validated['montant'],
                    'reference'    => $validated['reference'],
                    'date'         => $validated['date'],
                ]);
    
                // 2️⃣ SORTIE DE STOCK PRODUITS (UNE SEULE FOIS)
                foreach ($facture->commande->lignes as $ligne) {
    
                    $stock = Stock::where('produit_id', $ligne->produit_id)
                        ->lockForUpdate()
                        ->first();
    
                    if (!$stock) {
                        throw new \Exception(
                            "Aucun stock trouvé pour le produit {$ligne->produit->nom}"
                        );
                    }
    
                    if ($stock->quantite < $ligne->quantite) {
                        throw new \Exception(
                            "Stock insuffisant pour le produit {$ligne->produit->nom}"
                        );
                    }
    
                    // ➖ Mouvement SORTIE
                    MouvementStock::create([
                        'stock_id' => $stock->id,
                        'type'     => Constant::TYPESMOUVEMENT['SORTIE'],
                        'quantite' => $ligne->quantite,
                        'reference'=> $facture->numero, // FAC-2026-0001
                        'date'     => $validated['date'],
                    ]);
    
                    // ➖ Mise à jour stock
                    $stock->quantite -= $ligne->quantite;
                    $stock->save();
                }
    
                // 3️⃣ Marquer la facture comme PAYÉE
                $facture->update([
                    'statut' => Constant::FACTURE['PAYEE']
                ]);

                // 2️⃣ Mettre à jour la commande
                $facture->commande->update([
                    'statut' => 'PAYER'
                ]);
            });
    
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    
        return redirect()
            ->route('commande.list')
            ->with('success', 'Paiement validé, facture réglée et stock mis à jour.');
    }


    public function paiementPdf($id)
    {
        $paiement = Paiement::with([
            'facture.commande.client',
            'facture.commande.lignes.produit'
        ])->findOrFail($id);

        $pdf = Pdf::loadView(
            'pages.paiement.pdfPaiement',
            compact('paiement')
        )->setPaper('a4', 'portrait');

        return $pdf->download(
            'PAIEMENT-' . $paiement->facture->numero . '.pdf'
        );
    }

    public function paiementPrint($id)
    {
        $paiement = Paiement::with([
            'facture.commande.client',
            'facture.commande.lignes.produit'
        ])->findOrFail($id);

        return view(
            'pages.paiement.pdfPaiement',
            compact('paiement')
        );
    }

    



}
