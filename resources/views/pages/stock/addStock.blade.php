@extends('layouts.main')

@section('page')
    <span>Stock</span>
@endsection

@section('titre')
    <span>Ajouter un stock</span>
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
                <h5>Remplir les informations du stock</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('stock.store') }}" method="POST">
                    @csrf

                    <div class="row g-4">

                        {{-- Matière première --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="matiere_premiere_id"
                                        class="form-select @error('matiere_premiere_id') is-invalid @enderror">
                                    <option value="">-- Sélectionner la matière première --</option>
                                    @foreach($matierePremieres as $mp)
                                        <option value="{{ $mp->id }}"
                                            {{ old('matiere_premiere_id') == $mp->id ? 'selected' : '' }}>
                                            {{ $mp->nom }} ({{ $mp->unite }})
                                        </option>
                                    @endforeach
                                </select>
                                <label>Matière première</label>
                                @error('matiere_premiere_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Entrepôt --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="entrepot_id"
                                        class="form-select @error('entrepot_id') is-invalid @enderror">
                                    <option value="">-- Sélectionner l’entrepôt --</option>
                                    @foreach($entrepots as $entrepot)
                                        <option value="{{ $entrepot->id }}"
                                            {{ old('entrepot_id') == $entrepot->id ? 'selected' : '' }}>
                                            {{ $entrepot->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                <label>Entrepôt</label>
                                @error('entrepot_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Quantité --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number"
                                       class="form-control @error('quantite') is-invalid @enderror"
                                       name="quantite"
                                       placeholder="Quantité"
                                       value="{{ old('quantite') }}">
                                <label>Quantité</label>
                                @error('quantite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Date --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date"
                                       class="form-control @error('date') is-invalid @enderror"
                                       name="date"
                                       value="{{ old('date', now()->format('Y-m-d')) }}">
                                <label>Date</label>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Commentaire --}}
                        <div class="col-md-12">
                            <div class="form-floating">
                                <textarea class="form-control"
                                          name="commentaire"
                                          placeholder="Commentaire"
                                          style="height: 100px">{{ old('commentaire') }}</textarea>
                                <label>Commentaire</label>
                            </div>
                        </div>

                    </div>

                    <br>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                        <a href="{{ route('stock.listMp') }}" class="btn btn-secondary">Retour</a>
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
