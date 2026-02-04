@extends('layouts.main')

@section('page')
    <span>Entrepot</span>
@endsection

@section('titre')
    <span>Liste des Entrepots</span>
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
                    <h5 class="mb-0">Entrepot</h5>

                    <a href="{{ route('entrepot.add') }}" class="btn btn-primary">
                        Ajouter
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
                               placeholder="Rechercher par nom ou type..."
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
                                <th>Localisation</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($entrepots as $entrepot)
                                <tr>
                                    <td>{{ $entrepot->nom }}</td>
                                    <td>{{ $entrepot->localisation }}</td>

                                    {{-- Actions --}}
                                    <td class="text-end">
                                        <a href="{{ route('entrepot.edit', $entrepot->id) }}"
                                           class="btn btn-sm btn-warning">
                                            Modifier
                                        </a>

                                        <form action="{{ route('entrepot.delete', $entrepot->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Supprimer cette matière première ?');">
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
                                    <td colspan="6" class="text-center text-muted">
                                        Aucun entrepot trouvée
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $entrepots->links() }}
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
