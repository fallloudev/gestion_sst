<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Client;
use App\Constant;
use Illuminate\Support\Facades\Auth;


class ClientController extends Controller
{
   
    // public function listClient(Request $request)
    // {
    //     $query = Client::query();

    //     // 🔍 Recherche
    //     if ($request->filled('search')) {
    //         $query->where('nom', 'like', '%' . $request->search . '%')
    //             ->orWhere('type', 'like', '%' . $request->search . '%')
    //             ->orWhere('telephone', 'like', '%' . $request->search . '%');
    //     }

    //     $clients = $query
    //         ->orderBy('nom')
    //         ->paginate(10)
    //         ->withQueryString();

    //     return view('pages.client.listClient', compact('clients'));
    // }


    public function listClient(Request $request)
    {
        $query = Client::query();
        $user = Auth::user();

        // 🔐 FILTRAGE PAR RÔLE
        if ($user->role->libelle === Constant::ROLES['COMMERCIAL']) {
            $query->where('user_id', $user->id);
        }

        // 🔍 Recherche
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                ->orWhere('type', 'like', '%' . $request->search . '%')
                ->orWhere('telephone', 'like', '%' . $request->search . '%');
            });
        }

        $clients = $query
            ->orderBy('nom')
            ->paginate(10)
            ->withQueryString();

        return view('pages.client.listClient', compact('clients'));
    }


    public function addClient()
    {
        $typeClients = Constant::TYPES_CLIENT;
        return view('pages.client.addClient', compact('typeClients'));
    }

    public function storeClient(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string',
            'telephone' => 'required|string|max:50',
            'adresse' => 'nullable|string|max:255',
        ]);

        Client::create([
            'nom' => $validated['nom'],
            'type' => $validated['type'],
            'telephone' => $validated['telephone'],
            'adresse' => $validated['adresse'],
            'user_id' => Auth::user()->id,
        ]);

        return redirect()
            ->route('client.list')
            ->with('success', 'Client ajouté avec succès');
    }

    public function editClient($id)
    {
        $client = Client::findOrFail($id);

        return view('pages.client.editClient', [
            'client' => $client,
            'typesClients' => Constant::TYPES_CLIENT,
        ]);
    }

    public function updateClient(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string',
            'telephone' => 'required|string|max:30',
            'adresse' => 'nullable|string|max:255',
        ]);

        $client->update($validated);

        return redirect()
            ->route('client.list')
            ->with('success', 'Client modifié avec succès');
    }

    public function deleteClient($id)
    {
        $client = Client::findOrFail($id);

        $client->delete();

        return redirect()
            ->route('client.list')
            ->with('warning', 'Client supprimé avec succès');
    }



}
