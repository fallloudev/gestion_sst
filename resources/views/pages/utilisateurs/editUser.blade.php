@extends('layouts.main')

@section('page')
    <span>Utilisateurs</span>
@endsection
@section('titre')
    <span>Liste des apprenants</span>
@endsection

@section('content')
    {{-- Messages flash --}}
    @if (session('success'))
        <div id="flash-message" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Modifier les informations</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('user.updateUser', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">

                            {{-- Nom --}}
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="Nom"
                                        value="{{ old('name', $user->name) }}">
                                    <label for="name">Nom</label>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" placeholder="Email"
                                        value="{{ old('email', $user->email) }}">
                                    <label for="email">Email</label>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Rôle --}}
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="role" class="form-select @error('role') is-invalid @enderror"
                                        id="role">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ old('role', $user->role_id) == $role->id ? 'selected' : '' }}>
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
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="Mot de passe"
                                        value="{{ old('password', $user->password) }}">
                                    <label for="password">Mot de passe</label>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <br>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">Modifier</button>
                            <a href="{{ route('user.list') }}" class="btn btn-secondary">Retour</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>

    {{-- Auto-disparition du message succès --}}
    <script>
        setTimeout(() => {
            document.getElementById('flash-message')?.remove();
        }, 5000);
    </script>
@endsection
