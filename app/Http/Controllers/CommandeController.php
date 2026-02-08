<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Client;
use App\Models\Facture;
use App\Models\Paiement;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\MouvementStock;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Constant;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;





class CommandeController extends Controller
{

    // public function listCommande()
    // {
    //     $commandes = Commande::with(['client', 'lignes'])
    //         ->orderByDesc('date')
    //         ->paginate(10);

    //     return view('pages.commande.listCommande', compact('commandes'));
    // }
    
    // public function listCommande()
    // {
    //     $commandes = Commande::with([
    //             'client',
    //             'lignes',
    //             'facture.paiements'
    //         ])
    //         // 1️⃣ Factures PAYÉES d’abord
    //         ->orderByDesc(
    //             DB::raw("
    //                 (SELECT statut 
    //                  FROM factures 
    //                  WHERE factures.commande_id = commandes.id 
    //                  LIMIT 1) = 'PAYEE'
    //             ")
    //         )
    
    //         // 2️⃣ Numéro de facture (si existe)
    //         ->orderByDesc(
    //             DB::raw("
    //                 (SELECT numero 
    //                  FROM factures 
    //                  WHERE factures.commande_id = commandes.id 
    //                  LIMIT 1)
    //             ")
    //         )
    
    //         // 3️⃣ Date de commande
    //         ->orderByDesc('commandes.date')
    
    //         ->paginate(10);
    
    //     return view('pages.commande.listCommande', compact('commandes'));
    // }


    public function listCommande()
    {
        $user = Auth::user();

        $query = Commande::with([
            'client',
            'lignes',
            'facture.paiements'
        ]);

        // 🔐 FILTRAGE PAR RÔLE
        if ($user->role->libelle === Constant::ROLES['COMMERCIAL']) {
            $query->where('user_id', $user->id);
        }

        // ADMIN / ROOT : pas de filtre
        // PRODUCTION : lecture seule (pas de filtre)

        $commandes = $query
            ->orderByDesc(
                DB::raw("
                    (SELECT statut 
                    FROM factures 
                    WHERE factures.commande_id = commandes.id 
                    LIMIT 1) = 'PAYEE'
                ")
            )
            ->orderByDesc(
                DB::raw("
                    (SELECT numero 
                    FROM factures 
                    WHERE factures.commande_id = commandes.id 
                    LIMIT 1)
                ")
            )
            ->orderByDesc('commandes.date')
            ->paginate(10);

        return view('pages.commande.listCommande', compact('commandes'));
    }

    


    // public function showCommande($id)
    // {
    //     $commande = Commande::with(['client', 'lignes.produit'])
    //         ->findOrFail($id);

    //     return view('pages.commande.showCommande', compact('commande'));
    // }

    public function showCommande($id)
    {
        $commande = Commande::with(['client', 'lignes.produit'])
            ->findOrFail($id);

        if (
            Auth::user()->role->libelle === Constant::ROLES['COMMERCIAL']
            && $commande->user_id !== Auth::id()
        ) {
            abort(403);
        }

        return view('pages.commande.showCommande', compact('commande'));
    }


    public function addCommande()
    {
        return view('pages.commande.addCommande', [
            'clients'  => Client::orderBy('nom')->get(),
            'produits' => Produit::orderBy('nom')->get(),
        ]);
    }

    public function storeCommande(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'produit_ids' => 'required|array',
            'quantites' => 'required|array',
            'prix_unitaires' => 'required|array',
        ]);

        DB::transaction(function () use ($request, $validated) {

            // 1️⃣ Création commande
            $commande = Commande::create([
                'client_id' => $validated['client_id'],
                'date' => $validated['date'],
                'statut' => 'EN_ATTENTE',
                'total' => 0,
                'user_id' => Auth::user()->id,
            ]);

            $total = 0;

            // 2️⃣ Lignes de commande
            foreach ($request->produit_ids as $index => $produitId) {

                $qte = (int) $request->quantites[$index];
                $prix = (float) $request->prix_unitaires[$index];

                if ($qte <= 0) {
                    continue;
                }

                LigneCommande::create([
                    'commande_id' => $commande->id,
                    'produit_id' => $produitId,
                    'quantite' => $qte,
                    'prix_unitaire' => $prix,
                ]);

                $total += $qte * $prix;
            }

            // 3️⃣ Mise à jour du total
            $commande->update(['total' => $total]);
        });

        return redirect()
            ->route('commande.list')
            ->with('success', 'Commande créée avec succès');
    }

    public function editCommande($id)
    {
        $commande = Commande::with('lignes')->findOrFail($id);
        if (
            Auth::user()->role->libelle === Constant::ROLES['COMMERCIAL']
            && $commande->user_id !== Auth::id()
        ) {
            abort(403);
        }
        

        return view('pages.commande.editCommande', [
            'commande' => $commande,
            'clients'  => Client::orderBy('nom')->get(),
            'produits' => Produit::orderBy('nom')->get(),
        ]);
    }

    public function updateCommande(Request $request, $id)
    {
        $commande = Commande::findOrFail($id);
        if (
            Auth::user()->role->libelle === Constant::ROLES['COMMERCIAL']
            && $commande->user_id !== Auth::id()
        ) {
            abort(403);
        }
        
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'produit_ids' => 'required|array',
            'quantites' => 'required|array',
            'prix_unitaires' => 'required|array',
        ]);

        DB::transaction(function () use ($request, $commande, $validated) {

            $commande->update([
                'client_id' => $validated['client_id'],
                'date' => $validated['date'],
            ]);

            // 🔄 Supprimer anciennes lignes
            $commande->lignes()->delete();

            $total = 0;

            foreach ($request->produit_ids as $index => $produitId) {

                $qte  = (int) $request->quantites[$index];
                $prix = (float) $request->prix_unitaires[$index];

                if ($qte <= 0) continue;

                LigneCommande::create([
                    'commande_id' => $commande->id,
                    'produit_id' => $produitId,
                    'quantite' => $qte,
                    'prix_unitaire' => $prix,
                ]);

                $total += $qte * $prix;
            }

            $commande->update(['total' => $total]);
        });

        return redirect()
            ->route('commande.list')
            ->with('success', 'Commande mise à jour avec succès');
    }
    

    public function deleteCommande($id)
    {
        $commande = Commande::findOrFail($id);
        if (
            Auth::user()->role->libelle === Constant::ROLES['COMMERCIAL']
            && $commande->user_id !== Auth::id()
        ) {
            abort(403);
        }
        
        try {
            DB::transaction(function () use ($id) {

                $commande = Commande::with([
                    'lignes.produit',
                    'facture.paiements'
                ])->lockForUpdate()->findOrFail($id);

                // 1️⃣ S'il y a une facture
                if ($commande->facture) {

                    $facture = $commande->facture;

                    // 2️⃣ ANNULER LA SORTIE DE STOCK PRODUIT
                    foreach ($commande->lignes as $ligne) {

                        $stock = Stock::where('produit_id', $ligne->produit_id)
                            ->lockForUpdate()
                            ->first();

                        if ($stock) {
                            // ➕ Restituer le stock
                            $stock->quantite += $ligne->quantite;
                            $stock->save();
                        }

                        // ❌ Supprimer mouvements SORTIE liés à cette facture
                        MouvementStock::where('reference', $facture->numero)
                            ->where('type', 'SORTIE')
                            ->delete();
                    }

                    // 3️⃣ Supprimer les paiements
                    $facture->paiements()->delete();

                    // 4️⃣ Supprimer la facture
                    $facture->delete();
                }

                // 5️⃣ Supprimer les lignes de commande
                $commande->lignes()->delete();

                // 6️⃣ Supprimer la commande
                $commande->delete();
            });

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('commande.list')
            ->with('success', 'Commande, facture, paiements et stock supprimés avec succès');
    }



    //FACTURE - COMMANDE

    public function facturerCommande($id)
    {
        $commande = Commande::with(['client', 'lignes.produit', 'facture'])
            ->findOrFail($id);

        // 🔒 Sécurité : déjà facturée
        if ($commande->facture) {
            return redirect()
                ->route('commande.list')
                ->with('warning', 'Cette commande est déjà facturée');
        }

        return view('pages.commande.factureCommande', compact('commande'));
    }


    private function generateFactureReference(): string
    {
        $year = now()->year;

        // Dernière facture de l'année
        $lastFacture = Facture::whereYear('created_at', $year)
            ->orderByDesc('id')
            ->first();

        if ($lastFacture && preg_match('/FAC-' . $year . '-(\d+)/', $lastFacture->numero, $matches)) {
            $number = (int) $matches[1] + 1;
        } else {
            $number = 1;
        }

        return sprintf('FAC-%d-%04d', $year, $number);
    }


    public function storeFactureCommande($id)
    {
        $commande = Commande::with('facture')->findOrFail($id);

        if ($commande->facture) {
            return redirect()
                ->route('commande.list')
                ->with('warning', 'Commande déjà facturée');
        }

        DB::transaction(function () use ($commande) {

            $reference = $this->generateFactureReference();

            // 1️⃣ Créer la facture
            Facture::create([
                'commande_id' => $commande->id,
                'date' => now(),
                'tva' => 0,
                // 'total_ht' => $commande->total,
                'total_ttc' => $commande->total,
                'numero' => $reference,
                'statut' => Constant::FACTURE['NO_PAYEE']


            ]);

            // 2️⃣ Mettre à jour la commande
            $commande->update([
                'statut' => 'FACTUREE'
            ]);
        });

        return redirect()
            ->route('commande.list')
            ->with('success', 'Commande facturée avec succès');
    }











}
