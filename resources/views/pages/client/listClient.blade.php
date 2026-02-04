@extends('layouts.main')

@section('page')
    <span>Clients</span>
@endsection

@section('titre')
    <span>Liste des clients</span>
@endsection

@section('content')

{{-- Messages flash --}}
@foreach (['success', 'error', 'warning'] as $type)
    @if(session($type))
        <div id="flash-message" class="alert alert-{{ $type == 'success' ? 'success' : 'danger' }}">
            {{ session($type) }}
        </div>
    @endif
@endforeach

<div class="row">
    <div class="col-12">
        <div class="card table-card">

            {{-- Header --}}
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="mb-0">Clients 👥</h5>

                    <a href="{{ route('client.add') }}" class="btn btn-primary">
                        Ajouter un client
                    </a>
                </div>
            </div>

            <br>

            {{-- Recherche --}}
            <div class="card-body m-2 align-items-center">
                <form method="GET" class="row g-2 mb-3">
                    <div class="col-md-4">
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Rechercher par nom, type ou téléphone..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-secondary">Rechercher</button>
                    </div>
                </form>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Type</th>
                                <th>Téléphone</th>
                                <th>Adresse</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($clients as $client)
                                <tr>
                                    <td>
                                        <strong>{{ $client->nom }}</strong>
                                    </td>

                                    <td>
                                        <span class="badge bg-info">
                                            {{ $client->type }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $client->telephone }}
                                    </td>

                                    <td>
                                        {{ $client->adresse }}
                                    </td>

                                    {{-- Actions --}}
                                    <td class="text-end">
                                        <a href="{{ route('client.edit', $client->id) }}"
                                           class="btn btn-sm btn-warning">
                                            Modifier
                                        </a>

                                        <form action="{{ route('client.delete', $client->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Supprimer ce client ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Aucun client trouvé
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $clients->links() }}
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Auto-hide alert --}}
<script>
    setTimeout(() => {
        document.getElementById('flash-message')?.remove();
    }, 5000);
</script>

@endsection
