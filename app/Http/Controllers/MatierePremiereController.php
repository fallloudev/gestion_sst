<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MatierePremiere;
use App\Constant;

class MatierePremiereController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

        /**
     * Remove the specified resource from storage.
     */

    public function listMp(Request $request)
    {
        $query = MatierePremiere::query();

        // Recherche
        if ($request->filled('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%')
                ->orWhere('type', 'like', '%' . $request->search . '%');
        }

        $mps = $query
            ->orderBy('nom')
            ->paginate(10)
            ->withQueryString(); // garde la recherche en pagination

        return view('pages.matierePremiere.listMP', compact('mps'));
    }

    public function addMp()
    {
        $typeMps = Constant::MATIERE_PREMIERE_TYPES;
        return view('pages.matierePremiere.addMP', compact('typeMps'));
    }

    public function storeMp(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string',
            'unite' => 'required|string|max:50',
            'seuil_min' => 'required|integer|min:0',
            'peremption_date' => 'required|date|after:today',
        ]);

        MatierePremiere::create([
            'nom'      => $validated['nom'],
            'type'     => $validated['type'],
            'unite'  => $validated['unite'],
            'seuil_min'   => $validated['seuil_min'],
            'peremption_date' => $validated['peremption_date'],
        ]);

        return redirect()->route('mp.list')->with('success', 'Matiére Premiére créée avec succès');
    }

    public function editMp(string $mpId)
    {
        $mp = MatierePremiere::findOrFail($mpId);
        $typeMps = Constant::MATIERE_PREMIERE_TYPES;

        return view('pages.matierePremiere.editMP', compact('mp', 'typeMps'));
    }

    public function updateMp(Request $request, string $mpId)
    {
        $mp = MatierePremiere::findOrFail($mpId);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string',
            'unite' => 'required|string|max:50',
            'seuil_min' => 'required|integer|min:0',
            'peremption_date' => 'required|date|after:today',
        ]);

        $mp->update([
            'nom'      => $validated['nom'],
            'type'     => $validated['type'],
            'unite'  => $validated['unite'],
            'seuil_min'   => $validated['seuil_min'],
            'peremption_date' => $validated['peremption_date'],
        ]);

        return redirect()
            ->route('mp.edit', $mp->id)
            ->with('success', 'Matiére Premiére modifié avec succès');
    }

    public function deleteMp(string $mpId)
    {
        MatierePremiere::findOrFail($mpId)->delete();

        return redirect()
            ->route('mp.list')
            ->with('success', 'Matiére Premiére supprimé avec succès');
    }



}
