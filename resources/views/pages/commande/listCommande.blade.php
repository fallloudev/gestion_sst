@extends('layouts.main')

@section('page')
    <span>Ventes</span>
@endsection

@section('titre')
    <span>Liste des commandes</span>
@endsection

@section('content')
    {{-- Messages flash --}}
    @foreach (['success', 'error', 'warning'] as $type)
        @if (session($type))
            <div class="alert alert-{{ $type == 'success' ? 'success' : 'danger' }}">
                {{ session($type) }}
            </div>
        @endif
    @endforeach

    <div class="card table-card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Commandes clients 🧾</h5>
            <a href="{{ route('commande.add') }}" class="btn btn-primary">
                Nouvelle commande
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Total</th>
                            <th>Lignes</th>
                            <th>N° Facture</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($commandes as $commande)
                            <tr>
                                <td>{{ $commande->id }}</td>

                                <td>
                                    {{ $commande->client->nom ?? '—' }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($commande->date)->format('d/m/Y') }}
                                </td>

                                <td>
                                    <span class="badge bg-info">
                                        {{ $commande->statut }}
                                    </span>
                                </td>

                                <td>
                                    <strong>
                                        {{ number_format($commande->total, 0, ',', ' ') }} FCFA
                                    </strong>
                                </td>

                                <td>
                                    {{ $commande->lignes->count() }}
                                </td>
                                <td>
                                    @if ($commande->facture)
                                        <span class="badge bg-success">
                                            {{ $commande->facture->numero }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ \App\Constant::FACTURE['NO_FACT'] }}
                                        </span>
                                    @endif
                                </td>


                                <td class="text-end">

                                    @if (!$commande->facture)
                                        <a href="{{ route('commande.facture', $commande->id) }}"
                                            class="btn btn-sm btn-success">
                                            Facturer
                                        </a>
                                    @endif
                                    <a href="{{ route('commande.show', $commande->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-circle-info"></i>

                                    </a>


                                    @if ($commande->facture && $commande->facture->statut !== \App\Constant::FACTURE['PAYEE'])
                                        <a href="{{ route('facture.pdf', $commande->facture->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="Télécharger PDF">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </a>

                                        <a href="{{ route('facture.print', $commande->facture->id) }}"
                                            class="btn btn-sm btn-outline-secondary" target="_blank"
                                            title="Imprimer la facture">
                                            <i class="fa-solid fa-print"></i>
                                        </a>
                                    @endif

                                    @if (
                                        $commande->facture &&
                                            $commande->facture->statut == \App\Constant::FACTURE['PAYEE'] &&
                                            $commande->facture->paiements)
                                        <a href="{{ route('paiement.pdf', $commande->facture->paiements->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="Télécharger PDF">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </a>

                                        <a href="{{ route('paiement.print', $commande->facture->paiements->id) }}"
                                            class="btn btn-sm btn-outline-secondary" target="_blank"
                                            title="Imprimer la facture">
                                            <i class="fa-solid fa-print"></i>
                                        </a>
                                    @endif




                                    @if ($commande->facture && $commande->facture->statut !== \App\Constant::FACTURE['PAYEE'])
                                        <a href="{{ route('paiement.add', $commande->facture->id) }}"
                                            class="btn btn-sm btn-outline-secondary" title="Payer la facture">
                                            <i class="ti ti-cash"></i>
                                        </a>
                                    @endif

                                    {{-- @if (!($commande->facture && $commande->facture->statut === 'PAYEE')) --}}
                                    @if (true)
                                        <form action="{{ route('commande.delete', $commande->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Supprimer cette commande ?');">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-danger">
                                                <i class="fa-solid fa-trash"></i>

                                            </button>
                                        </form>
                                    @endif

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    Aucune commande trouvée
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $commandes->links() }}
            </div>
        </div>
    </div>
@endsection
