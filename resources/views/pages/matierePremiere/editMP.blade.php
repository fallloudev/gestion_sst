@extends('layouts.main')

@section('page')
    <span>Matières premières</span>
@endsection

@section('titre')
    <span>Modifier une matière première</span>
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
                <h5>Modifier les informations</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('mp.update', $mp->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        {{-- Nom --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input
                                    type="text"
                                    class="form-control @error('nom') is-invalid @enderror"
                                    id="nom"
                                    name="nom"
                                    placeholder="Nom"
                                    value="{{ old('nom', $mp->nom) }}"
                                >
                                <label for="nom">Nom</label>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Type --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select
                                    name="type"
                                    class="form-select @error('type') is-invalid @enderror"
                                    id="type"
                                >
                                    <option value="">-- Sélectionner le type --</option>
                                    @foreach($typeMps as $typeMp)
                                        <option
                                            value="{{ $typeMp }}"
                                            {{ old('type', $mp->type) == $typeMp ? 'selected' : '' }}
                                        >
                                            {{ $typeMp }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="type">Type</label>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Unité --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input
                                    type="text"
                                    class="form-control @error('unite') is-invalid @enderror"
                                    id="unite"
                                    name="unite"
                                    placeholder="Unité (kg, litre...)"
                                    value="{{ old('unite', $mp->unite) }}"
                                >
                                <label for="unite">Unité</label>
                                @error('unite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Seuil minimum --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input
                                    type="number"
                                    class="form-control @error('seuil_min') is-invalid @enderror"
                                    id="seuil_min"
                                    name="seuil_min"
                                    placeholder="Seuil minimum"
                                    value="{{ old('seuil_min', $mp->seuil_min) }}"
                                >
                                <label for="seuil_min">Seuil minimum</label>
                                @error('seuil_min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Date de péremption --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input
                                    type="date"
                                    class="form-control @error('peremption_date') is-invalid @enderror"
                                    id="peremption_date"
                                    name="peremption_date"
                                    value="{{ old('peremption_date', $mp->peremption_date) }}"
                                >
                                <label for="peremption_date">Date de péremption</label>
                                @error('peremption_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <br>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        <a href="{{ route('mp.list') }}" class="btn btn-secondary">Retour</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- Auto-hide message succès --}}
<script>
    setTimeout(() => {
        document.getElementById('success-alert')?.remove();
    }, 5000);
</script>
@endsection
