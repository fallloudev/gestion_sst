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
        <div id="flash-message" class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (session('warning'))
        <div id="flash-message" class="alert alert-danger">
            {{ session('warning') }}
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card table-card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Utilisateurs 🧑🏻‍🎓</h5>
                        <a href="{{ route('user.addUser') }}" class="btn btn-primary">
                            Ajouter
                        </a>
                    </div>
                </div>

                <div class="card-body pb-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Profil</th>
                                    <th>Statut</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role->libelle }}</td>

                                        {{-- Statut --}}
                                        <td>
                                            <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $user->is_active ? 'Actif' : 'Inactif' }}
                                            </span>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="text-end">
                                            <a href="{{ route('user.editUser', $user->id) }}"
                                                class="btn btn-sm btn-warning">
                                                Modifier
                                            </a>

                                            <form action="{{ route('user.changeUserStatut', $user->id) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Voulez-vous changer le statut de cet utilisateur ?');">
                                              @csrf
                                              @method('PUT')
                                          
                                              <button type="submit"
                                                  class="btn btn-sm {{ $user->is_active ? 'btn-secondary' : 'btn-success' }}">
                                                  {{ $user->is_active ? 'Désactiver' : 'Activer' }}
                                              </button>
                                          </form>
                                          



                                            {{-- Supprimer --}}
                                            <form action="{{ route('user.deleteUser', $user->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            Aucun utilisateur trouvé
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
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
