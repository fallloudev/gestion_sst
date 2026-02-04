@extends('layouts.main')

@section('page')
    <span>Entrepot</span>
@endsection

@section('titre')
    <span>Ajouter un Entrepot</span>
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
                <h5>Remplir les informations</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('entrepot.store') }}" method="POST">
                    @csrf

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
                                    value="{{ old('nom') }}"
                                >
                                <label for="nom">Nom</label>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- localisation --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input
                                    type="text"
                                    class="form-control @error('localisation') is-invalid @enderror"
                                    id="localisation"
                                    name="localisation"
                                    placeholder="localisation (Dakar, Thies...)"
                                    value="{{ old('localisation') }}"
                                >
                                <label for="localisation">Localisation</label>
                                @error('localisation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <br>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                        <a href="{{ route('entrepot.list') }}" class="btn btn-secondary">Retour</a>
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
