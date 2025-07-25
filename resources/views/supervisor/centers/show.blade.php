@extends('layouts.supervisor')

@section('title', 'Détails du Centre')

@section('content')
<div class="container">
    <h1 class="page-title">Centre : {{ $center->name }}</h1>
    <p class="page-subtitle">Gérez les agents et les informations du centre</p>

    <!-- Informations du centre -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Informations du Centre</h5>
            <p><strong>Nom :</strong> {{ $center->name }}</p>
            <p><strong>Département :</strong> {{ $center->department->name ?? 'Non spécifié' }}</p>
            <p><strong>Région :</strong> {{ $center->department->region->name ?? 'Non spécifié' }}</p>
        </div>
    </div>

    <!-- Liste des agents -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Agents du Centre</h5>
            @if ($agents->isEmpty())
                <p>Aucun agent n'est assigné à ce centre.</p>
            @else
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agents as $agent)
                            <tr>
                                <td>{{ $agent->name }}</td>
                                <td>{{ $agent->email }}</td>
                                <td>

                                    <form action="{{ route('supervisor.agents.destroy', $agent->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet agent ?')">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
