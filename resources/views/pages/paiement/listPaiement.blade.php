@extends('layouts.main')

@section('page')
    <span>Ventes</span>
@endsection

@section('titre')
    <span>Liste des paiements</span>
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
                    <h5 class="mb-0">Paiements 💳</h5>
                </div>

                <br>

                {{-- Filtres --}}
                <div class="card-body m-2">
                    <form method="GET" class="row g-2 mb-3">

                        {{-- Recherche --}}
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control"
                                placeholder="Client / Référence / Facture" value="{{ request('search') }}">
                        </div>

                        {{-- Mode de paiement --}}
                        <div class="col-md-3">
                            <select name="mode" class="form-select">
                                <option value="">-- Mode --</option>
                                @foreach (\App\Constant::MODES_PAIEMENT as $mode)
                                    <option value="{{ $mode }}" {{ request('mode') == $mode ? 'selected' : '' }}>
                                        {{ $mode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-secondary">
                                Filtrer
                            </button>
                        </div>
                    </form>

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Client</th>
                                    <th>Facture</th>
                                    <th>Mode</th>
                                    <th>Référence</th>
                                    <th>Montant</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($paiements as $paiement)
                                    <tr>
                                        <td>
                                            {{ \Carbon\Carbon::parse($paiement->date)->format('d/m/Y') }}
                                        </td>

                                        <td>
                                            {{ $paiement->facture->commande->client->nom ?? '—' }}
                                        </td>

                                        <td>
                                            <strong>
                                                {{ $paiement->facture->numero ?? '—' }}
                                            </strong>
                                        </td>

                                        <td>
                                            <span class="badge bg-info">
                                                {{ $paiement->mode_paiement }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ $paiement->reference ?? '—' }}
                                        </td>

                                        <td>
                                            <strong>
                                                {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                                            </strong>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="text-end">

                                            {{-- Détails --}}
                                            <a href="{{ route('paiement.show', $paiement->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Détails">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('paiement.pdf', $paiement->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Télécharger PDF">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </a>
    
                                            <a href="{{ route('paiement.print', $paiement->id) }}"
                                                class="btn btn-sm btn-outline-secondary" target="_blank"
                                                title="Imprimer la facture">
                                                <i class="fa-solid fa-print"></i>
                                            </a>

                                            {{-- Suppression --}}
                                            {{-- <form action="{{ route('paiement.delete', $paiement->id) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Supprimer ce paiement ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form> --}}

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            Aucun paiement trouvé
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-3">
                        {{ $paiements->links() }}
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
