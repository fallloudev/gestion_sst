@extends('layouts.main')

@section('page')
    <span>Clients</span>
@endsection

@section('titre')
    <span>Modifier un client</span>
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
                <h5>Modifier les informations du client</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('client.update', $client->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        {{-- Nom --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text"
                                       class="form-control @error('nom') is-invalid @enderror"
                                       name="nom"
                                       value="{{ old('nom', $client->nom) }}"
                                       placeholder="Nom">
                                <label>Nom</label>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Type --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="type"
                                        class="form-select @error('type') is-invalid @enderror">
                                    <option value="">-- Sélectionner le type --</option>
                                    @foreach ($typesClients as $type)
                                        <option value="{{ $type }}"
                                            {{ old('type', $client->type) == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                <label>Type de client</label>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Téléphone --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text"
                                       class="form-control @error('telephone') is-invalid @enderror"
                                       name="telephone"
                                       value="{{ old('telephone', $client->telephone) }}"
                                       placeholder="Téléphone">
                                <label>Téléphone</label>
                                @error('telephone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Adresse --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text"
                                       class="form-control @error('adresse') is-invalid @enderror"
                                       name="adresse"
                                       value="{{ old('adresse', $client->adresse) }}"
                                       placeholder="Adresse">
                                <label>Adresse</label>
                                @error('adresse')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <br>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            Mettre à jour
                        </button>
                        <a href="{{ route('client.list') }}" class="btn btn-secondary">
                            Retour
                        </a>
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
