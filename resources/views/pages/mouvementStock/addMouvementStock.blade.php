@extends('layouts.main')

@section('page')
    <span>Stock</span>
@endsection

@section('titre')
    <span>Ajouter un mouvement de stock</span>
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
            <div class="alert alert-danger" id="success-alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Enregistrer un mouvement (Entrée / Sortie)</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('mouvementStock.store') }}" method="POST">
                        @csrf

                        <div class="row g-4">

                            {{-- Stock --}}
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="stock_id" class="form-select @error('stock_id') is-invalid @enderror">
                                        <option value="">-- Sélectionner le stock --</option>
                                        @foreach ($stocks as $stock)
                                            <option value="{{ $stock->id }}"
                                                {{ old('stock_id') == $stock->id ? 'selected' : '' }}>
                                                {{ $stock->matiere_premiere->nom }}
                                                | {{ $stock->entrepot->nom }}
                                                | Qté: {{ $stock->quantite }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label>Stock</label>
                                    @error('stock_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Type mouvement --}}
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="type" class="form-select @error('type') is-invalid @enderror">
                                        <option value="">-- Type de mouvement --</option>
                                        @foreach (\App\Constant::TYPESMOUVEMENT as $typeMouvement)
                                            <option value="{{ $typeMouvement }}"
                                                {{ old('type') == $typeMouvement ? 'selected' : '' }}>
                                                {{ $typeMouvement }}
                                            </option>
                                        @endforeach

                                        {{-- <option value="ENTREE" {{ old('type') == 'ENTREE' ? 'selected' : '' }}>
                                            Entrée
                                        </option>
                                        <option value="SORTIE" {{ old('type') == 'SORTIE' ? 'selected' : '' }}>
                                            Sortie
                                        </option> --}}
                                    </select>
                                    <label>Type</label>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Quantité --}}
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" class="form-control @error('quantite') is-invalid @enderror"
                                        name="quantite" min="1" placeholder="Quantité"
                                        value="{{ old('quantite') }}">
                                    <label>Quantité</label>
                                    @error('quantite')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Référence --}}
                            {{-- <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('reference') is-invalid @enderror"
                                        name="reference" placeholder="Référence (BL, facture...)"
                                        value="{{ old('reference') }}">
                                    <label>Référence</label>
                                    @error('reference')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div> --}}

                            {{-- Date --}}
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="datetime-local" class="form-control @error('date') is-invalid @enderror"
                                        name="date" value="{{ old('date', now()->format('Y-m-d\TH:i')) }}">
                                    <label>Date</label>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <br>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                            <a href="{{ route('mouvementStock.list') }}" class="btn btn-secondary">Retour</a>
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
