@extends('layouts.main')

@section('page')
    <span>Production</span>
@endsection

@section('titre')
    <span>Ordres de production</span>
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Ordres de production 🏭</h5>

                    <a href="{{ route('ordreProduction.add') }}" class="btn btn-primary">
                        Nouvel ordre
                    </a>
                </div>

                <div class="card-body">

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Matières premières utilisées</th>
                                    <th>Qte prévue</th>
                                    <th>Qte produite</th>
                                    <th>Perte</th>
                                    <th>Statut</th>
                                    <th>date</th>
                                    <th>Référence</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($ordres as $ordre)
                                    <tr>
                                        {{-- Produit --}}
                                        <td>
                                            <strong>{{ $ordre->produit->nom }}</strong><br>
                                            <small class="text-muted">
                                                {{ $ordre->produit->type }} |
                                                {{ $ordre->produit->poids }} {{ $ordre->produit->unite }}
                                            </small>
                                        </td>

                                        {{-- Consommation MP --}}
                                        <td>
                                            @foreach ($ordre->consommation_m_p_s as $conso)
                                                <div>
                                                    • {{ $conso->matiere_premiere->nom }}
                                                    :
                                                    <strong>{{ $conso->quantite }}</strong>
                                                    {{ $conso->matiere_premiere->unite }}
                                                </div>
                                            @endforeach
                                        </td>

                                        {{-- Quantité prévue --}}
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $ordre->quantite_prevue }}
                                            </span>
                                        </td>

                                        {{-- Quantité produite --}}
                                        <td>
                                            <span
                                                class="badge {{ $ordre->quantite_produite >= $ordre->quantite_prevue ? 'bg-success' : 'bg-warning' }}">
                                                {{ $ordre->quantite_produite ?? 0 }}
                                            </span>
                                        </td>

                                        {{-- Quantité produite --}}
                                        <td>
                                            <span class="badge bg-danger">
                                                {{ $ordre->quantite_prevue - $ordre->quantite_produite }}
                                            </span>
                                        </td>

                                        {{-- Statut --}}
                                        <td>
                                            @php
                                                $colors = [
                                                    'EN_ATTENTE' => 'secondary',
                                                    'EN_COURS' => 'primary',
                                                    'TERMINE' => 'success',
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $colors[$ordre->statut] ?? 'dark' }}">
                                                {{ $ordre->statut }}
                                            </span>
                                        </td>



                                        {{-- Date  --}}
                                        <td>
                                            <span class="">
                                                {{ $ordre->date }}
                                            </span>
                                        </td>

                                        {{-- Date  --}}
                                        <td>
                                            <span class="">
                                                {{ $ordre->reference }}
                                            </span>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="text-end">

                                            <a href="{{ route('ordreProduction.edit', $ordre->id) }}"
                                                class="btn btn-sm btn-warning">
                                                Modifier
                                            </a>

                                            <form action="{{ route('ordreProduction.delete', $ordre->id) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Supprimer cet ordre de production ?');">
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
                                            Aucun ordre de production trouvé
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-3">
                        {{ $ordres->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(() => {
            document.getElementById('flash-message')?.remove();
        }, 5000);
    </script>
@endsection
