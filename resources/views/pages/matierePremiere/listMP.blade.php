@extends('layouts.main')

@section('page')
    <span>Matières premières</span>
@endsection

@section('titre')
    <span>Liste des matières premières</span>
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
                    <h5 class="mb-0">Matières premières 📦</h5>

                    <a href="{{ route('mp.add') }}" class="btn btn-primary">
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
                                <th>Type</th>
                                <th>Unité</th>
                                <th>Seuil min</th>
                                <th>Péremption</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($mps as $mp)
                                <tr>
                                    <td>{{ $mp->nom }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $mp->type }}
                                        </span>
                                    </td>
                                    <td>{{ $mp->unite }}</td>
                                    <td>
                                        <span class="badge bg-warning">
                                            {{ $mp->seuil_min }}
                                        </span>
                                    </td>
                                    <td>
                                        @if(\Carbon\Carbon::parse($mp->peremption_date)->isPast())
                                            <span class="badge bg-danger">Périmée</span>
                                        @else
                                            {{ \Carbon\Carbon::parse($mp->peremption_date)->format('d/m/Y') }}
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="text-end">
                                        <a href="{{ route('mp.edit', $mp->id) }}"
                                           class="btn btn-sm btn-warning">
                                            Modifier
                                        </a>

                                        <form action="{{ route('mp.delete', $mp->id) }}"
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
                                        Aucune matière première trouvée
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $mps->links() }}
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
