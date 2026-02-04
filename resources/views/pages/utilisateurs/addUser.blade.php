@extends('layouts.main')

@section('page')
    <span>Utilisateurs</span>
@endsection

@section('titre')
    <span>Ajouter un utilisateur</span>
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
                <form action="{{ route('user.storeUser') }}" method="POST">
                    @csrf

                    <div class="row g-4">

                        {{-- Nom --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input
                                    type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    id="name"
                                    name="name"
                                    placeholder="Nom"
                                    value="{{ old('name') }}"
                                >
                                <label for="name">Nom</label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input
                                    type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    placeholder="Email"
                                    value="{{ old('email') }}"
                                >
                                <label for="email">Adresse email</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Rôle --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select
                                    name="role"
                                    class="form-select @error('role') is-invalid @enderror"
                                    id="role"
                                >
                                    <option value="">-- Sélectionner un rôle --</option>
                                    @foreach ($roles as $role)
                                        <option
                                            value="{{ $role->id }}"
                                            {{ old('role') == $role->id ? 'selected' : '' }}
                                        >
                                            {{ $role->libelle }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="role">Rôle de l'utilisateur</label>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Mot de passe --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input
                                    type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    id="password"
                                    name="password"
                                    placeholder="Mot de passe"
                                >
                                <label for="password">Mot de passe</label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <br>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                        <a href="{{ route('user.list') }}" class="btn btn-secondary">Retour</a>
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
