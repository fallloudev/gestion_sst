@extends('layouts.main')

@section('page')
    <span>Stock</span>
@endsection

@section('titre')
    <span>Mouvements de stock</span>
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
                        <h5 class="mb-0">Historique des mouvements</h5>
                        <a href="{{ route('mouvementStock.add') }}" class="btn btn-primary">
                            Ajouter
                        </a>
                    </div>
                </div>

                <div class="card-body p-3">

                    {{-- 🔍 Filtres --}}
                    <form method="GET" class="row g-3 mb-3 align-items-end">

                        {{-- Filtre type --}}
                        <div class="col-md-3 ">
                            <label class="form-label">Type de mouvement</label>
                            <select name="type" class="form-select">
                                <option value="">-- Tous --</option>
                                <option value="ENTREE" {{ request('type') == 'ENTREE' ? 'selected' : '' }}>
                                    Entrée
                                </option>
                                <option value="SORTIE" {{ request('type') == 'SORTIE' ? 'selected' : '' }}>
                                    Sortie
                                </option>
                            </select>
                        </div>

                        {{-- Filtre référence --}}
                        <div class="col-md-4 ">
                            <label class="form-label">Référence</label>
                            <input type="text" name="reference" class="form-control" placeholder="Référence du mouvement"
                                value="{{ request('reference') }}">
                        </div>

                        {{-- Boutons --}}
                        <div class="col-md-3 d-flex gap-2">
                            <button class="btn btn-secondary">Filtrer</button>
                            <a href="{{ route('mouvementStock.list') }}" class="btn btn-outline-secondary">
                                Réinitialiser
                            </a>
                        </div>

                    </form>

                    {{-- 📋 Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Matière première</th>
                                    <th>Entrepôt</th>
                                    <th>Quantité</th>
                                    <th>Référence</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($mouvements as $mouvement)
                                    <tr>
                                        <td>{{ $mouvement->date->format('d/m/Y H:i') }}</td>

                                        {{-- Type --}}
                                        <td>
                                            @if ($mouvement->type === \App\Constant::TYPESMOUVEMENT['ENTREE'])
                                                <span class="badge bg-success">⬆ Entrée</span>
                                            @else
                                                <span class="badge bg-danger">⬇ Sortie</span>
                                            @endif
                                        </td>

                                        <td>
                                            {{ $mouvement->stock->matiere_premiere->nom }}
                                        </td>

                                        <td>
                                            {{ $mouvement->stock->entrepot->nom }}
                                        </td>


                                        {{-- Quantité --}}
                                        <td>
                                            {{ $mouvement->quantite }}
                                        </td>

                                        {{-- Référence --}}
                                        <td>
                                            {{ $mouvement->reference }}
                                        </td>

                                        <td>
                                            @if ($mouvement->reference === \App\Constant::REF['NO_REF'])
                                                <form action="{{ route('mouvementStock.delete', $mouvement->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Supprimer cet ordre de production ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            Aucun mouvement trouvé
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-3">
                        {{ $mouvements->links() }}
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
