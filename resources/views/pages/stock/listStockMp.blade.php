@extends('layouts.main')

@section('page')
    <span>Stock</span>
@endsection

@section('titre')
    <span>Stock des matières premières</span>
@endsection

@section('content')
    {{-- Messages flash --}}
    @foreach (['success', 'error', 'warning'] as $type)
        @if (session($type))
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
                        <h5 class="mb-0">Stock matières premières 📦</h5>

                        <a href="{{ route('stock.add') }}" class="btn btn-primary">
                            Ajouter
                        </a>
                    </div>
                </div>

                {{-- Recherche --}}
                <div class="card-body m-2">
                    <form method="GET" class="row g-2 mb-3 align-items-end">

                        {{-- Recherche texte --}}
                        <div class="col-md-4">
                            <label class="form-label">Recherche</label>
                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   placeholder="Nom ou type de MP"
                                   value="{{ request('search') }}">
                        </div>
                    
                        {{-- Filtre entrepôt --}}
                        <div class="col-md-4">
                            <label class="form-label">Entrepôt</label>
                            <select name="entrepot_id" class="form-select">
                                <option value="">-- Tous les entrepôts --</option>
                                @foreach($entrepots as $entrepot)
                                    <option value="{{ $entrepot->id }}"
                                        {{ request('entrepot_id') == $entrepot->id ? 'selected' : '' }}>
                                        {{ $entrepot->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    
                        {{-- Boutons --}}
                        <div class="col-md-4 d-flex gap-2">
                            <button class="btn btn-secondary">Filtrer</button>
                    
                            <a href="{{ route('stock.listMp') }}" class="btn btn-outline-secondary">
                                Réinitialiser
                            </a>
                        </div>
                    </form>
                    

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Matière première</th>
                                    <th>Quantité</th>
                                    <th>Unité</th>
                                    <th>Entrepôt</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Commentaire</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($stocks as $stock)
                                    <tr>
                                        <td>{{ $stock->matiere_premiere->nom ?? '-' }}</td>
                                        {{-- <td>
                                        <span class="badge bg-info">
                                            {{ $stock->matiere_premiere->type ?? '-' }}
                                        </span>
                                    </td> --}}
                                        <td>
                                            <strong>{{ $stock->quantite }}</strong>
                                        </td>
                                        <td>{{ $stock->matiere_premiere->unite ?? '-' }}</td>
                                        <td>{{ $stock->entrepot->nom ?? '-' }}</td>
                                        <td>
                                            @switch($stock->statut_stock)
                                                @case('critique')
                                                    <span class="badge bg-danger">🔴 Critique</span>
                                                @break

                                                @case('faible')
                                                    <span class="badge bg-warning text-dark">🟠 Faible</span>
                                                @break

                                                @default
                                                    <span class="badge bg-success">🟢 OK</span>
                                            @endswitch

                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($stock->date)->format('d/m/Y') }}</td>
                                        <td>{{ $stock->commentaire ?? '-' }}</td>

                                        {{-- Actions --}}
                                        <td class="text-end">
                                            <a href="{{ route('stock.edit', $stock->id) }}" class="btn btn-sm btn-warning">
                                                Modifier
                                            </a>

                                            <form action="{{ route('stock.delete', $stock->id) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Supprimer ce stock ?');">
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
                                            <td colspan="8" class="text-center text-muted">
                                                Aucun stock de matière première trouvé
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center mt-3">
                            {{ $stocks->links() }}
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
