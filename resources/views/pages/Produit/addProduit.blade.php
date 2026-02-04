@extends('layouts.main')

@section('page')
    <span>Produits</span>
@endsection

@section('titre')
    <span>Ajouter un produit</span>
@endsection

@section('content')
    <div class="row">

        {{-- Messages --}}
        @if (session('success'))
            <div class="alert alert-success" id="success-alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Remplir les informations du produit</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('produit.store') }}" method="POST">
                        @csrf

                        <div class="row g-4">

                            {{-- Nom --}}
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                        name="nom" placeholder="Nom du produit" value="{{ old('nom') }}">
                                    <label>Nom</label>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Type --}}
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="type" class="form-select @error('type') is-invalid @enderror">
                                        <option value="">-- Sélectionner le type --</option>
                                        @foreach ($typeProduits as $type)
                                            <option value="{{ $type }}"
                                                {{ old('type') == $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label>Type</label>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Poids --}}
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('poids') is-invalid @enderror"
                                        name="poids" placeholder="Poids" value="{{ old('poids') }}">
                                    <label>Poids</label>
                                    @error('poids')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Unité --}}
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('unite') is-invalid @enderror"
                                        name="unite" placeholder="kg, sachet, paquet..." value="{{ old('unite') }}">
                                    <label>Unité</label>
                                    @error('unite')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Prix de vente --}}
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" step="0.01"
                                        class="form-control @error('prix_vente') is-invalid @enderror" name="prix_vente"
                                        placeholder="Prix de vente" value="{{ old('prix_vente') }}">
                                    <label>Prix de vente (FCFA)</label>
                                    @error('prix_vente')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <br>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                            <a href="{{ route('produit.list') }}" class="btn btn-secondary">Retour</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(() => {
            document.getElementById('success-alert')?.remove();
        }, 5000);
    </script>
@endsection
