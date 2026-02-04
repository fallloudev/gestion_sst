@extends('layouts.main')

@section('page')
    <span>Ventes</span>
@endsection

@section('titre')
    <span>Liste des factures</span>
@endsection

@section('content')

{{-- Messages flash --}}
@foreach (['success', 'error', 'warning'] as $type)
    @if (session($type))
        <div id="flash-message"
             class="alert alert-{{ $type == 'success' ? 'success' : 'danger' }}">
            {{ session($type) }}
        </div>
    @endif
@endforeach

<div class="row">
    <div class="col-12">
        <div class="card table-card">

            {{-- Header --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Factures 🧾</h5>
            </div>

            <br>

            {{-- Filtres --}}
            <div class="card-body m-2">
                <form method="GET" class="row g-2 mb-3">

                    <div class="col-md-4">
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="Numéro facture / Client"
                               value="{{ request('search') }}">
                    </div>

                    <div class="col-md-3">
                        <select name="statut" class="form-select">
                            <option value="">-- Statut --</option>
                            <option value="PAYEE" {{ request('statut') == 'PAYEE' ? 'selected' : '' }}>
                                Payée
                            </option>
                            <option value="EN_ATTENTE" {{ request('statut') == 'EN_ATTENTE' ? 'selected' : '' }}>
                                En attente
                            </option>
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
                                <th>Facture</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Total</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($factures as $facture)
                                <tr>
                                    <td>
                                        <strong>{{ $facture->numero }}</strong>
                                    </td>

                                    <td>
                                        {{ $facture->commande->client->nom ?? '—' }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($facture->date)->format('d/m/Y') }}
                                    </td>

                                    <td>
                                        <span class="badge {{ $facture->statut == 'PAYEE' ? 'bg-success' : 'bg-warning' }}">
                                            {{ $facture->statut }}
                                        </span>
                                    </td>

                                    <td>
                                        <strong>
                                            {{ number_format($facture->total_ttc, 0, ',', ' ') }} FCFA
                                        </strong>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="text-end">

                                        {{-- PDF --}}
                                        <a href="{{ route('facture.pdf', $facture->id) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="PDF">
                                            <i class="ti ti-file-text"></i>
                                        </a>

                                        {{-- Print --}}
                                        <a href="{{ route('facture.print', $facture->id) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-outline-secondary"
                                           title="Imprimer">
                                            <i class="ti ti-printer"></i>
                                        </a>

                                        {{-- Paiement --}}
                                        @if($facture->statut !== 'PAYEE')
                                            <a href="{{ route('paiement.add', $facture->id) }}"
                                               class="btn btn-sm btn-outline-success"
                                               title="Encaisser">
                                                <i class="ti ti-cash"></i>
                                            </a>
                                        @endif

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Aucune facture trouvée
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $factures->links() }}
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
