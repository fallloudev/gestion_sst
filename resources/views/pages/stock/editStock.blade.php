@extends('layouts.main')

@section('page')
    <span>Stock</span>
@endsection

@section('titre')
    <span>Modifier le stock</span>
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
                <h5>Modifier les informations du stock</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('stock.update', $stock->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        {{-- Matière première (lecture seule) --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" disabled>
                                    <option>
                                        {{ $stock->matiere_premiere->nom }}
                                        ({{ $stock->matiere_premiere->unite }})
                                    </option>
                                </select>
                                <label>Matière première</label>
                            </div>
                        </div>

                        {{-- Entrepôt (lecture seule) --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" disabled>
                                    <option>
                                        {{ $stock->entrepot->nom }}
                                    </option>
                                </select>
                                <label>Entrepôt</label>
                            </div>
                        </div>

                        {{-- Quantité --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number"
                                       class="form-control @error('quantite') is-invalid @enderror"
                                       name="quantite"
                                       placeholder="Quantité"
                                       value="{{ old('quantite', $stock->quantite) }}">
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
                                       value="{{ old('date', $stock->date) }}">
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
                                          style="height: 100px">{{ old('commentaire', $stock->commentaire) }}</textarea>
                                <label>Commentaire</label>
                            </div>
                        </div>

                    </div>

                    <br>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
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
