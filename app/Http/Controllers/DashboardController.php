<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\Commande;
use App\Models\LigneCommande;
use App\Models\Client;
use App\Models\Facture;
use App\Models\Paiement;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\MouvementStock;
use App\Models\MatierePremiere;
use App\Models\OrdreProduction;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Constant;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role->libelle;



        return match ($role) {
            Constant::ROLES['ROOT'],
            Constant::ROLES['ADMIN'] => $this->adminDashboard(),

            Constant::ROLES['PRODUCTION'] => $this->productionDashboard(),

            Constant::ROLES['COMMERCIAL'] => $this->commercialDashboard(),

            default => abort(403),
        };
    }

    private function adminDashboard()
    {
        return view('pages.dashboard', [
            'mode' => 'ADMIN',
            'clients' => Client::count(),
            'commandes' => Commande::count(),
            'factures' => Facture::count(),
            'ca' => Paiement::sum('montant'),
            'stocksCritiques' => Stock::where('quantite', '<=', 10)->count(),
        ]);
    }

    private function productionDashboard()
    {
        return view('pages.dashboard', [
            'mode' => 'PRODUCTION',
            'mpCount' => MatierePremiere::count(),
            'stockMpCritique' => Stock::whereNotNull('matiere_premiere_id')
                ->where('quantite', '<=', 10)->count(),
            'ordreEnCours' => OrdreProduction::where('statut', 'EN_COURS')->count(),
            'productionJour' => OrdreProduction::whereDate('date', today())->count(),
        ]);
    }

    private function commercialDashboard()
    {
        return view('pages.dashboard', [
            'mode' => 'COMMERCIAL',
            'clients' => Client::count(),
            'commandesJour' => Commande::whereDate('date', today())->count(),
            'facturesAttente' => Facture::where('statut', 'EN_ATTENTE')->count(),
            'caJour' => Paiement::whereDate('date', today())->sum('montant'),
        ]);
    }



  

}
