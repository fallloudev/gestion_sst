@extends('layouts.main')

@section('page')
    <span>Ventes</span>
@endsection

@section('titre')
    <span>Enregistrer un paiement</span>
@endsection

@section('content')
<div class="row">

    {{-- Messages --}}
    @foreach (['success','error','warning'] as $type)
        @if(session($type))
            <div class="alert alert-{{ $type == 'success' ? 'success' : 'danger' }}">
                {{ session($type) }}
            </div>
        @endif
    @endforeach

    <div class="col-md-8 offset-md-2">
        <div class="card">

            <div class="card-header">
                <h5>Paiement de la facture</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('paiement.store') }}" method="POST">
                    @csrf

                    {{-- FACTURE (FIXE) --}}
                    <input type="hidden" name="facture_id" value="{{ $facture->id }}">

                    <div class="alert alert-info">
                        <strong>Facture :</strong> {{ $facture->numero }} <br>
                        <strong>Client :</strong> {{ $facture->commande->client->nom }} <br>
                        <strong>Total :</strong>
                        {{ number_format($facture->total_ttc, 0, ',', ' ') }} FCFA
                    </div>

                    {{-- MODE DE PAIEMENT --}}
                    <div class="form-floating mb-3">
                        <select name="mode_paiement"
                                class="form-select @error('mode_paiement') is-invalid @enderror">
                            <option value="">-- Mode de paiement --</option>
                            @foreach($modesPaiement as $mode)
                                <option value="{{ $mode }}"
                                    {{ old('mode_paiement') == $mode ? 'selected' : '' }}>
                                    {{ $mode }}
                                </option>
                            @endforeach
                        </select>
                        <label>Mode de paiement</label>
                        @error('mode_paiement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- MONTANT --}}
                    <div class="form-floating mb-3">
                        <input type="number"
                               name="montant"
                               class="form-control @error('montant') is-invalid @enderror"
                               value="{{ old('montant', $facture->total_ttc) }}"
                               min="1">
                        <label>Montant payé (FCFA)</label>
                        @error('montant')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- RÉFÉRENCE --}}
                    <div class="form-floating mb-3">
                        <input type="text"
                               name="reference"
                               class="form-control"
                               value="{{ old('reference') }}">
                        <label>Référence (optionnelle)</label>
                    </div>

                    {{-- DATE --}}
                    <div class="form-floating mb-4">
                        <input type="date"
                               name="date"
                               class="form-control"
                               value="{{ old('date', now()->format('Y-m-d')) }}">
                        <label>Date</label>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="text-end">
                        <button class="btn btn-success">
                            💳 Valider le paiement
                        </button>
                        <a href="{{ route('commande.list') }}" class="btn btn-secondary">
                            Retour
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
