<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facture;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Constant;


class FactureController extends Controller
{

    private function generateFactureReference(): string
    {
        $year = now()->year;

        // Dernière facture de l'année
        $lastFacture = Facture::whereYear('created_at', $year)
            ->orderByDesc('id')
            ->first();

        if ($lastFacture && preg_match('/FAC-' . $year . '-(\d+)/', $lastFacture->reference, $matches)) {
            $number = (int) $matches[1] + 1;
        } else {
            $number = 1;
        }

        return sprintf('FAC-%d-%04d', $year, $number);
    }



    public function pdfFacture($id)
    {
        $facture = Facture::with([
            'commande.client',
            'commande.lignes.produit'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('pages.facture.pdfFacture', compact('facture'));

        return $pdf->download('Facture_'.$facture->id.'.pdf');
    }

    public function printFacture($id)
    {
        $facture = Facture::with([
            'commande.client',
            'commande.lignes.produit'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('pages.facture.pdfFacture', compact('facture'));

        return $pdf->stream('Facture_'.$facture->id.'.pdf');
    }


    // public function listFacture(Request $request)
    // {
    //     $query = Facture::with('commande.client');

    //     if ($request->filled('search')) {
    //         $query->where('numero', 'like', '%' . $request->search . '%')
    //             ->orWhereHas('commande.client', function ($q) use ($request) {
    //                 $q->where('nom', 'like', '%' . $request->search . '%');
    //             });
    //     }

    //     if ($request->filled('statut')) {
    //         $query->where('statut', $request->statut);
    //     }

    //     $factures = $query
    //         ->orderByDesc('date')
    //         ->paginate(10)
    //         ->withQueryString();

    //     return view('pages.facture.listFacture', compact('factures'));
    // }



    public function listFacture(Request $request)
    {
        $query = Facture::with('commande.client');

        // 🔐 FILTRAGE PAR RÔLE
        if (Auth::user()->role->libelle === Constant::ROLES['COMMERCIAL']) {
            $query->whereHas('commande', function ($q) {
                $q->where('user_id', Auth::id());
            });
        }

        // 🔍 Recherche
        if ($request->filled('search')) {
            $query->where('numero', 'like', '%' . $request->search . '%')
                ->orWhereHas('commande.client', function ($q) use ($request) {
                    $q->where('nom', 'like', '%' . $request->search . '%');
                });
        }

        // 🏷️ Statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $factures = $query
            ->orderByDesc('date')
            ->paginate(10)
            ->withQueryString();

        return view('pages.facture.listFacture', compact('factures'));
    }


}
